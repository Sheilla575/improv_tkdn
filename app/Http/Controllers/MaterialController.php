<?php

namespace App\Http\Controllers;

use App\Contracts\CodeGenerationServiceInterface;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\Material;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class MaterialController extends Controller
{
    protected $codeGenerationService;

    protected $importService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CodeGenerationServiceInterface $codeGenerationService, ImportService $importService)
    {
        $this->middleware('auth');
        $this->codeGenerationService = $codeGenerationService;
        $this->importService = $importService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $materials = Material::with('category')->paginate(10);

        return view('material.index', compact('materials'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('material.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string',
                'negara_asal' => 'nullable|string',
                'specification' => 'nullable|string',
                'tkdn' => 'nullable',
                'price' => 'required|integer',
                'unit' => 'required',
                'link' => 'nullable|url',
                'price_inflasi' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
            ]);

            // Generate code otomatis
            $code = $this->codeGenerationService->generateCode('material');

            $data = $request->all();
            $data['code'] = $code;
            
            // Konversi koma ke titik untuk TKDN jika ada
            if (!empty($data['tkdn'])) {
                $data['tkdn'] = str_replace(',', '.', $data['tkdn']);
            }

            Material::create($data);

            return redirect()->route('master.material.index')->with('success', 'Material created successfully with code: ' . $code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating material: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Material $material)
    {
        return view('material.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $categories = Category::orderBy('name')->get();

        return view('material.edit', compact('material', 'categories'));
    }

    public function update(Request $request, Material $material)
    {
        try {
            $request->validate([
                'name' => 'required',
                'category_id' => 'required|exists:categories,id',
                'brand' => 'nullable|string',
                'negara_asal' => 'nullable|string',
                'specification' => 'nullable|string',
                'tkdn' => 'nullable',
                'price' => 'required|integer',
                'unit' => 'required',
                'link' => 'nullable|url',
                'price_inflasi' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'location' => 'nullable|string',
            ]);

            $data = $request->all();
            
            // Konversi koma ke titik untuk TKDN jika ada
            if (!empty($data['tkdn'])) {
                $data['tkdn'] = str_replace(',', '.', $data['tkdn']);
            }

            $material->update($data);

            return redirect()->route('master.material.index')->with('success', 'Material updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while updating material: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Material $material)
    {
        try {
            $material->delete();

            return redirect()->route('master.material.index')->with('success', 'Material deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while deleting material: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete all materials from the database
     */
    public function deleteAll(Request $request)
    {
        try {
            $totalRecords = Material::count();
            
            if ($totalRecords === 0) {
                return redirect()->route('master.material.index')->with('info', 'No material records found to delete.');
            }

            // Use database transaction for safety
            DB::beginTransaction();
            
            // Delete all material records
            Material::query()->delete();
            
            // Reset auto increment counter if using MySQL
            DB::statement('ALTER TABLE material AUTO_INCREMENT = 1');
            
            DB::commit();
            
            return redirect()->route('master.material.index')->with('success', "Successfully deleted {$totalRecords} material records.");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred while deleting all materials: '.$e->getMessage()]);
        }
    }

    /**
     * Download Excel template for material import
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'Name', 'Category', 'Merk', 'Negara Asal', 'Tipe', 'Specification', 'TKDN', 'Price', 
            'Unit', 'Link', 'Description', 'Location', 'Classification TKDN'
        ];
        $sheet->fromArray($headers, null, 'A1');

        // Set example data
        $exampleData = [
            ['Cement Portland', 'Building Material', 'Semen Gresik', 'Dalam Negeri', 'Semen', '30KG', '100.00', '85000', 'Sak', 'https://example.com', 'Portland cement type I', 'Jakarta', 'Peralatan (Jasa Umum)'],
            ['Steel Bar', 'Steel', 'Krakatau Steel', 'Luar Negeri', 'Semen', '30KG', '100', '150000', 'Ton', 'https://example.com', 'Steel reinforcement bar', 'Bandung', 'Peralatan (Jasa Umum)'],
        ];
        $sheet->fromArray($exampleData, null, 'A2');

        // Style headers
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);
        $sheet->getStyle('A1:M1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E5E7EB');

        // Auto size columns
        foreach (range('A', 'M') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $dropdownOptions = [
            'Dalam Negeri',
            'Luar Negeri'
        ]; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa ubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("D{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"');
            $validation->setPromptTitle('Pilih Negara Asal');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        // === Tambahkan dropdown list untuk kolom L (Classification TKDN) ===
        $dropdownOptions = [
            'Peralatan (Jasa Umum)',
            'Material (Bahan Baku)',
            'Peralatan (Barang Jadi)'
        ]; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa ubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("M{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"');
            $validation->setPromptTitle('Pilih Klasifikasi TKDN');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        $dropdownOptions = Category::pluck('name')->toArray();; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa ubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("B{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"');
            $validation->setPromptTitle('Pilih Kategori');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        // Create response
        $writer = new Xlsx($spreadsheet);
        $filename = 'material_import_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import materials from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $headers = array_shift($rows);

            $imported = 0;
            $errors = [];
            $rowNumber = 2; // Start from row 2 (after header)

            // Clear cache untuk memastikan data fresh
            $this->importService->clearCache();

            foreach ($rows as $row) {
                if (empty(array_filter($row))) {
                    $rowNumber++;

                    continue; // Skip empty rows
                }

                // Validasi field required
                $requiredErrors = $this->importService->validateRequiredFields(
                    $row,
                    [0 => 'Name', 5 => 'Price', 6 => 'Unit'],
                    $rowNumber
                );

                if (! empty($requiredErrors)) {
                    $errors = array_merge($errors, $requiredErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi dan cari kategori berdasarkan nama
                [$categoryId, $categoryErrors] = $this->importService->validateAndGetCategoryId(
                    $row[1] ?? null,
                    $rowNumber
                );

                if (! empty($categoryErrors)) {
                    $errors = array_merge($errors, $categoryErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi TKDN range
                $tkdnErrors = $this->importService->validateNumericRange(
                    $row[4] ?? null,
                    'TKDN',
                    $rowNumber,
                    0,
                    100
                );

                if (! empty($tkdnErrors)) {
                    $errors = array_merge($errors, $tkdnErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi Price
                $priceErrors = $this->importService->validateNumericRange(
                    $row[5],
                    'Price',
                    $rowNumber,
                    0
                );

                if (! empty($priceErrors)) {
                    $errors = array_merge($errors, $priceErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi Price Inflasi
                // $priceInflasiErrors = $this->importService->validateNumericRange(
                //     $row[8] ?? null,
                //     'Price Inflasi',
                //     $rowNumber,
                //     0
                // );

                if (! empty($priceInflasiErrors)) {
                    $errors = array_merge($errors, $priceInflasiErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi Classification TKDN
                // $classificationErrors = $this->importService->validateClassificationTkdn(
                //     $row[11] ?? null,
                //     $rowNumber
                // );

                if (! empty($classificationErrors)) {
                    $errors = array_merge($errors, $classificationErrors);
                    $rowNumber++;

                    continue;
                }

                try {
                    // Generate code
                    $code = $this->codeGenerationService->generateCode('material');

                    // Convert classification TKDN from string to integer
                    $classificationTkdn = null;
                    if (!empty($row[12])) {
                        $classificationTkdn = StringHelper::classificationTkdnToInt(trim($row[12]));
                    }

                    // Create material
                    Material::create([
                        'name' => trim($row[0]),
                        'category_id' => $categoryId,
                        'classification_tkdn' => $classificationTkdn,
                        'brand' => ! empty($row[2]) ? trim($row[2]) : null,
                        'specification' => ! empty($row[5]) ? trim($row[5]) : null,
                        'tkdn' => ! empty($row[6]) ? (float) str_replace(',', '.', $row[6]) : 100.00,
                        'price' => (int) $row[7],
                        'unit' => trim($row[8]),
                        'link' => ! empty($row[9]) ? trim($row[9]) : null,
                        'price_inflasi' => null,
                        'negara_asal' => trim($row[4]),
                        'type' => trim($row[5]),
                        'description' => ! empty($row[10]) ? trim($row[10]) : null,
                        'location' => ! empty($row[11]) ? trim($row[11]) : null,
                        'code' => $code,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }

                $rowNumber++;
            }

            DB::commit();

            // Log progress
            $this->importService->logImportProgress('material', $imported, count($rows), $errors);

            if (empty($errors)) {
                return redirect()->route('master.material.index')
                    ->with('success', "Successfully imported {$imported} materials!");
            } else {
                return redirect()->route('master.material.index')
                    ->with('success', "Successfully imported {$imported} materials!")
                    ->with('import_errors', $errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('master.material.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
