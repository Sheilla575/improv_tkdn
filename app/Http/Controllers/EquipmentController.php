<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log; // Tambahkan ini di bagian atas file
use App\Contracts\CodeGenerationServiceInterface;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\Equipment;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class EquipmentController extends Controller
{
    protected $codeGenerationService;

    protected $importService;

    /**
     * Create a new controller instance.
     */
    public function __construct(CodeGenerationServiceInterface $codeGenerationService, ImportService $importService)
    {
        $this->middleware('auth');
        $this->codeGenerationService = $codeGenerationService;
        $this->importService = $importService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipment = Equipment::with('category')->latest()->paginate(10);

        return view('equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('equipment.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'classification_tkdn' => 'required|integer|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16',
                'tkdn' => 'nullable',
                'equipment_type' => 'required|in:disposable,reusable',
                'period' => 'nullable|integer|min:0',
                'price' => 'required|integer|min:0',
                'description' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'spesifikasi' => 'nullable|string|max:255',
                'dibuat' => 'nullable|string|max:255',
                'dimiliki' => 'nullable|string|max:255',
                'satuan' => 'nullable|string|max:255',
            ]);

            // Validasi period berdasarkan jenis equipment
            if ($data['equipment_type'] === 'disposable') {
                $data['period'] = 0; // Force period to 0 for disposable items
            } else {
                // Untuk reusable, pastikan period >= 1
                if ($data['period'] < 1) {
                    return back()->withErrors(['period' => 'Period harus minimal 1 untuk peralatan reusable.'])->withInput();
                }
            }

            // Generate code otomatis
            try {
                $code = $this->codeGenerationService->generateCode('equipment');
            } catch (\Exception $e) {
                Log::error('Gagal generate code untuk equipment: ' . $e->getMessage(), [
                    'request' => $request->all(),
                    'exception' => $e
                ]);
                return back()->withErrors(['error' => 'Gagal menghasilkan kode unik. Silakan coba lagi.'])->withInput();
            }

            $data['code'] = $code;

            // Hapus equipment_type karena tidak disimpan di DB
            unset($data['equipment_type']);

            // Simpan ke database
            try {
                Equipment::create($data);
           
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan equipment ke database: ' . $e->getMessage(), [
                    'data' => $data,
                    'exception' => $e
                ]);
                return back()->withErrors(['error' => 'Gagal menyimpan data peralatan. Silakan coba lagi.'])->withInput();
            }

            return redirect()->route('master.equipment.index')
                            ->with('success', 'Peralatan berhasil ditambahkan dengan code: ' . $code);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validasi error tetap ditampilkan ke user
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Log error lengkap untuk developer
            Log::error('Error tak terduga di EquipmentController@store: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            // Beri pesan umum ke user
            return back()->withErrors([
                'error' => 'Terjadi kesalahan sistem saat menambahkan peralatan. Tim kami telah diberi tahu.'
            ])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipment $equipment)
    {
        $categories = Category::orderBy('name')->get();

        return view('equipment.edit', compact('equipment', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, Equipment $equipment)
    {
        try {
            // Validasi awal
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'nullable|exists:categories,id',
                'classification_tkdn' => 'required|integer|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16',
                'tkdn' => 'nullable',
                'equipment_type' => 'required|in:disposable,reusable',
                'period' => 'nullable|integer|min:0',
                'price' => 'required|integer|min:0',
                'description' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'spesifikasi' => 'nullable|string|max:255',
                'dibuat' => 'nullable|string|max:255',
                'dimiliki' => 'nullable|string|max:255',
                'satuan' => 'nullable|string|max:255',
                'classification_tkdn' => 'required|integer|in:1,2,3,4,5,6,7',
            ]);

            // Validasi period berdasarkan equipment_type
            if ($data['equipment_type'] === 'disposable') {
                $data['period'] = 0;
            } elseif ($data['equipment_type'] === 'reusable') {
                // Pastikan period >= 1 untuk reusable
                if ($data['period'] < 1) {
                    throw ValidationException::withMessages([
                        'period' => ['Period harus minimal 1 untuk peralatan reusable.'],
                    ]);
                }
            }

            // Hapus field yang tidak disimpan di database
            unset($data['equipment_type']);

            // Log data sebelum update (opsional, untuk debugging)
            \Log::info('Updating equipment', [
                'equipment_id' => $equipment->id,
                'data' => $data,
            ]);

            // Lakukan update
            $updated = $equipment->update($data);

            if ($updated) {
                \Log::info('Equipment updated successfully', ['equipment_id' => $equipment->id]);
                return redirect()->route('master.equipment.index')
                                ->with('success', 'Peralatan berhasil diupdate!');
            } else {
                // Tidak ada perubahan atau gagal update
                \Log::warning('Equipment update had no effect or failed', [
                    'equipment_id' => $equipment->id,
                    'data' => $data,
                ]);
                return back()->withErrors(['error' => 'Tidak ada perubahan yang disimpan atau gagal mengupdate data.'])
                            ->withInput();
            }

        } catch (ValidationException $e) {
            \Log::warning('Validation failed during equipment update', [
                'errors' => $e->validator->errors(),
                'input' => $request->all(),
            ]);
            return back()->withErrors($e->validator)->withInput();

        } catch (\Exception $e) {
            \Log::error('Unexpected error during equipment update', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'equipment_id' => $equipment->id ?? 'unknown',
            ]);
            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate peralatan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        try {
            $equipment->delete();

            return redirect()->route('master.equipment.index')->with('success', 'Peralatan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus peralatan: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete all equipment from the database
     */
    public function deleteAll(Request $request)
    {
        try {
            $totalRecords = Equipment::count();
            
            if ($totalRecords === 0) {
                return redirect()->route('master.equipment.index')->with('info', 'Tidak ada data peralatan untuk dihapus.');
            }

            // Use database transaction for safety
            DB::beginTransaction();
            
            // Delete all equipment records
            Equipment::query()->delete();
            
            // Reset auto increment counter if using MySQL
            DB::statement('ALTER TABLE equipment AUTO_INCREMENT = 1');
            
            DB::commit();
            
            return redirect()->route('master.equipment.index')->with('success', "Berhasil menghapus {$totalRecords} data peralatan.");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus semua peralatan: ' . $e->getMessage()]);
        }
    }

    /**
     * Download Excel template for equipment import
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'Name', 'Category', 'TKDN', 'Equipment Type', 
            'Price', 'Description', 'Location', 'Classification TKDN'
        ];
        $sheet->fromArray($headers, null, 'A1');

        // Set example data
        $exampleData = [
            ['Excavator Mini', 'Peralatan', '85.50', 'Dapat Dipakai Ulang', '2500000', 'Mini excavator for small projects', 'Jakarta', 'Alat Kerja / Fasilitas'],
            ['Safety Helmet', 'Peralatan', '100.00', 'Habis Pakai', '150000', 'Safety helmet for workers', 'Bandung', 'Alat Kerja / Fasilitas'],
        ];
        $sheet->fromArray($exampleData, null, 'A2');

        // Style headers
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E5E7EB');

        // Auto size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
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

        // === Tambahkan dropdown list untuk kolom I (Classification TKDN) ===
        $dropdownOptions = [
            'Alat Kerja / Fasilitas',
            'Peralatan (Jasa Umum)'
        ]; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa diubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("H{$row}")->getDataValidation();
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

        $dropdownOptions = [
            'Dapat Dipakai Ulang',
            'Habis Pakai'
        ]; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa diubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("D{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"');
            $validation->setPromptTitle('Pilih Type');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        // Create response
        $writer = new Xlsx($spreadsheet);
        $filename = 'equipment_import_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import equipment from Excel file
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
                    [0 => 'Name', 3 => 'Equipment Type', 5 => 'Price'],
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

                // Validasi equipment type
                // $typeErrors = $this->importService->validateInArray(
                //     $row[3],
                //     'Equipment Type',
                //     $rowNumber,
                //     ['disposable', 'reusable']
                // );

                // if (! empty($typeErrors)) {
                //     $errors = array_merge($errors, $typeErrors);
                //     $rowNumber++;

                //     continue;
                // }

                // Validasi TKDN range
                $tkdnErrors = $this->importService->validateNumericRange(
                    $row[2] ?? null,
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

                // Validasi Period
                // $periodErrors = $this->importService->validateNumericRange(
                //     $row[4],
                //     'Period',
                //     $rowNumber,
                //     0
                // );

                // if (! empty($periodErrors)) {
                //     $errors = array_merge($errors, $periodErrors);
                //     $rowNumber++;

                //     continue;
                // }

                // Validasi Period berdasarkan equipment type
                if ($row[3] === 'disposable' && $row[4] != 0) {
                    $errors[] = "Row {$rowNumber}: Period must be 0 for disposable equipment";
                    $rowNumber++;

                    continue;
                }

                if ($row[3] === 'reusable' && $row[4] < 1) {
                    $errors[] = "Row {$rowNumber}: Period must be at least 1 for reusable equipment";
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

                // Validasi Classification TKDN
                // $classificationErrors = $this->importService->validateClassificationTkdn(
                //     $row[8] ?? null,
                //     $rowNumber
                // );

                if (! empty($classificationErrors)) {
                    $errors = array_merge($errors, $classificationErrors);
                    $rowNumber++;

                    continue;
                }

                try {
                    // Generate code
                    $code = $this->codeGenerationService->generateCode('equipment');

                    // Convert classification TKDN from string to integer
                    $classificationTkdn = null;
                    if (!empty($row[8])) {
                        $classificationTkdn = StringHelper::classificationTkdnToInt(trim($row[8]));
                    }

                    // Create equipment
                    Equipment::create([
                        'name' => trim($row[0]),
                        'category_id' => $categoryId,
                        'classification_tkdn' => $classificationTkdn,
                        'tkdn' => ! empty($row[2]) ? (float) $row[2] : null,
                        'type' => trim($row[3]),
                        'period' => null,
                        'price' => (int) $row[4],
                        'description' => ! empty($row[5]) ? trim($row[5]) : null,
                        'location' => ! empty($row[6]) ? trim($row[6]) : null,
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
            $this->importService->logImportProgress('equipment', $imported, count($rows), $errors);

            if (empty($errors)) {
                return redirect()->route('master.equipment.index')
                    ->with('success', "Successfully imported {$imported} equipment!");
            } else {
                return redirect()->route('master.equipment.index')
                    ->with('success', "Successfully imported {$imported} equipment!")
                    ->with('import_errors', $errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('master.equipment.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
