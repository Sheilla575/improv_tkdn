<?php

namespace App\Http\Controllers;

use App\Contracts\CodeGenerationServiceInterface;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\JournalWorker;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class JournalWorkerController extends Controller
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
        $journals = JournalWorker::orderBy('created_at', 'DESC')->paginate(10);

        return view('journal.index', compact('journals'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('journal.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_pekerjaan' => 'required',
                'spesifikasi_or_kualifikasi' => 'required',
                'negara_asal' => 'required',
                'satuan_harga' => 'required',
                'tkdn' => 'required',
                'classification_tkdn' => 'required',
                // 'category_id' => 'required|exists:categories,id',
                // 'brand' => 'nullable|string',
                // 'negara_asal' => 'nullable|string',
                // 'specification' => 'nullable|string',
                // 'tkdn' => 'nullable',
                // 'price' => 'required|integer',
                // 'unit' => 'required',
                // 'link' => 'nullable|url',
                // 'price_inflasi' => 'nullable|integer|min:0',
                // 'description' => 'nullable|string',
                // 'location' => 'nullable|string',
            ]);

            // Generate code otomatis
            $code = $this->codeGenerationService->generateCode('material');

            $data = $request->all();
            $data['code'] = $code;
            
            // Konversi koma ke titik untuk TKDN jika ada
            if (!empty($data['tkdn'])) {
                $data['tkdn'] = str_replace(',', '.', $data['tkdn']);
            }

            JournalWorker::create($data);

            return redirect()->route('master.journal.index')->with('success', 'Journal created successfully with code: ' . $code);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating material: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(JournalWorker $journal)
    {
        return view('journal.show', compact('journal'));
    }

    public function edit(JournalWorker $journal)
    {
        $categories = Category::orderBy('name')->get();

        return view('journal.edit', compact('journal', 'categories'));
    }

    public function update(Request $request, JournalWorker $journal)
    {
        try {
            $request->validate([
                'nama_pekerjaan' => 'required',
                'spesifikasi_or_kualifikasi' => 'required',
                'negara_asal' => 'required',
                'satuan_harga' => 'required',
                'tkdn' => 'required',
                'classification_tkdn' => 'required',
                // 'name' => 'required',
                // 'category_id' => 'required|exists:categories,id',
                // 'brand' => 'nullable|string',
                // 'negara_asal' => 'nullable|string',
                // 'specification' => 'nullable|string',
                // 'tkdn' => 'nullable',
                // 'price' => 'required|integer',
                // 'unit' => 'required',
                // 'link' => 'nullable|url',
                // 'price_inflasi' => 'nullable|integer|min:0',
                // 'description' => 'nullable|string',
                // 'location' => 'nullable|string',
            ]);

            $data = $request->all();
            
            // Konversi koma ke titik untuk TKDN jika ada
            if (!empty($data['tkdn'])) {
                $data['tkdn'] = str_replace(',', '.', $data['tkdn']);
            }

            $journal->update($data);

            return redirect()->route('master.journal.index')->with('success', 'Journal updated successfully!');
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

            return redirect()->route('master.journal.index')->with('success', 'Material deleted successfully!');
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
                return redirect()->route('master.journal.index')->with('info', 'No material records found to delete.');
            }

            // Use database transaction for safety
            DB::beginTransaction();
            
            // Delete all material records
            Material::query()->delete();
            
            // Reset auto increment counter if using MySQL
            DB::statement('ALTER TABLE material AUTO_INCREMENT = 1');
            
            DB::commit();
            
            return redirect()->route('master.journal.index')->with('success', "Successfully deleted {$totalRecords} material records.");
            
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
            'Nama Pekerja', 'Spesifikasi atau Kualifikasi', 'Asal Negara', 'Harga Satuan', 'TKDN', 'Keterangan'
        ];
        $sheet->fromArray($headers, null, 'A1');

        // Set example data
        $exampleData = [
            ['Pengerjaan Pengecatan1', 'Internal', 'WNI', '100000', '100.00', 'Keterangan'],
            ['Pengerjaan Pengecatan2', 'Internal', 'WNI', '100000', '100.00', 'Keterangan'],
        ];
        $sheet->fromArray($exampleData, null, 'A2');

        // Style headers
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E5E7EB');

        // Auto size columns
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // === Tambahkan dropdown list untuk kolom L (Classification TKDN) ===
        $dropdownOptions = [
            'WNI',
            'WNA',
        ]; // nilai yang muncul di dropdown

        // Terapkan untuk baris 2 sampai 1000 (bisa ubah sesuai kebutuhan)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("C{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"');
            $validation->setPromptTitle('Pilih Negara');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        // Create response
        $writer = new Xlsx($spreadsheet);
        $filename = 'journal_import_template.xlsx';

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

                try {
                    // Create journal
                    JournalWorker::create([
                        'nama_pekerjaan' => trim($row[0]),
                        'spesifikasi_or_kualifikasi' => trim($row[1]),
                        'classification_tkdn' => 4,
                        'negara_asal' => ! empty($row[2]) ? trim($row[2]) : null,
                        'satuan_harga' => ! empty($row[3]) ? trim($row[3]) : null,
                        'tkdn' => ! empty($row[4]) ? (float) str_replace(',', '.', $row[4]) : 100.00,
                        'keterangan' => trim($row[5])
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                }

                $rowNumber++;
            }

            DB::commit();

            // Log progress
            $this->importService->logImportProgress('journal', $imported, count($rows), $errors);

            if (empty($errors)) {
                return redirect()->route('master.journal.index')
                    ->with('success', "Successfully imported {$imported} journals!");
            } else {
                return redirect()->route('master.journal.index')
                    ->with('success', "Successfully imported {$imported} journals!")
                    ->with('import_errors', $errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('master.journal.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
