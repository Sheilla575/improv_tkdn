<?php

namespace App\Http\Controllers;

use App\Contracts\CodeGenerationServiceInterface;
use App\Helpers\StringHelper;
use App\Models\Category;
use App\Models\Worker;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Ramsey\Uuid\Type\Decimal;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class WorkerController extends Controller
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

    public function index()
    {
        // $workers = Worker::with('category')->paginate(10);
        $search = request()->query('search');

        $workers = Worker::with('category')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->paginate(10);
        return view('worker.index', compact('workers'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('worker.create', compact('categories'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|integer',
            'tkdn' => 'required',
            'location' => 'nullable|string',
            'Kewarganegaraan' =>  'required|string',
            'kualifikasi' => 'nullable|string',
        ]);

        // Generate code otomatis
        $code = $this->codeGenerationService->generateCode('worker');

        $data = $request->all();
        $data['code'] = $code;

        Worker::create($data);

        return redirect()->route('master.worker.index')->with('success', 'Worker created with code: ' . $code);
    }

    public function show(Worker $worker)
    {
        return view('worker.show', compact('worker'));
    }

    public function edit(Worker $worker)
    {
        $categories = Category::orderBy('name')->get();

        return view('worker.edit', compact('worker', 'categories'));
    }

    public function update(Request $request, Worker $worker)
    {

        $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|integer',
            'tkdn' => 'required',
            'Kewarganegaraan' =>  'nullable|string',
            'location' => 'nullable|string',
        ]);

        $worker->update($request->all());

        return redirect()->route('master.worker.index')->with('success', 'Worker updated!');
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();

        return redirect()->route('master.worker.index')->with('success', 'Worker deleted!');
    }

    /**
     * Delete all workers from the database
     */
    public function deleteAll(Request $request)
    {
        try {
            $totalRecords = Worker::count();
            
            if ($totalRecords === 0) {
                return redirect()->route('master.worker.index')->with('info', 'No worker records found to delete.');
            }

            // Use database transaction for safety
            DB::beginTransaction();
            
            // Delete all worker records
            Worker::query()->delete();
            
            // Reset auto increment counter if using MySQL
            DB::statement('ALTER TABLE workers AUTO_INCREMENT = 1');
            
            DB::commit();
            
            return redirect()->route('master.worker.index')->with('success', "Successfully deleted {$totalRecords} worker records.");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An error occurred while deleting all workers: '.$e->getMessage()]);
        }
    }

    /**
     * Download Excel template for worker import
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = ['Name', 'Unit', 'Category', 'Price', 'TKDN', 'Location', 'Classification TKDN'];
        $sheet->fromArray($headers, null, 'A1');

        // Set example data
        $exampleData = [
            ['John Doe', 'OH', 'Teknisi', '50000', '100.00', 'Jakarta', ''],
            ['Jane Smith', 'Person', 'Operator', '75000', '85.50', 'Bandung', ''],
        ];
        $sheet->fromArray($exampleData, null, 'A2');

        // Style headers
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E5E7EB');

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // === Tambahkan dropdown untuk kolom G (Classification TKDN) ===
        $dropdownOptions = [
            'Overhead & Manajemen',
            'Alat Kerja / Fasilitas',
            'Konstruksi & Fabrikasi',
            'Peralatan (Jasa Umum)',
            'Material (Bahan Baku)',
            'Peralatan (Barang Jadi)',
            'Summary',
        ]; // nilai yang muncul di dropdown

        // Tentukan range baris data (misal dari baris 2 sampai 1000 agar fleksibel)
        for ($row = 2; $row <= 1000; $row++) {
            $validation = $sheet->getCell("G{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setFormula1('"' . implode(',', $dropdownOptions) . '"'); // gabungkan list dropdown
            $validation->setPromptTitle('Pilih Klasifikasi TKDN');
            $validation->setPrompt('Silakan pilih salah satu nilai.');
            $validation->setErrorTitle('Input salah');
            $validation->setError('Nilai harus dipilih dari daftar yang tersedia.');
        }

        // Create response
        $writer = new Xlsx($spreadsheet);
        $filename = 'worker_import_template.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    /**
     * Import workers from Excel file
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
                    [0 => 'Name', 1 => 'Unit', 3 => 'Price', 4 => 'TKDN'],
                    $rowNumber
                );

                if (! empty($requiredErrors)) {
                    $errors = array_merge($errors, $requiredErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi dan cari kategori berdasarkan nama
                [$categoryId, $categoryErrors] = $this->importService->validateAndGetCategoryId(
                    $row[2] ?? null,
                    $rowNumber
                );

                if (! empty($categoryErrors)) {
                    $errors = array_merge($errors, $categoryErrors);
                    $rowNumber++;

                    continue;
                }

                // Validasi TKDN range
                $tkdnErrors = $this->importService->validateNumericRange(
                    $row[4],
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
                    $row[3],
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
                //     $row[6] ?? null,
                //     $rowNumber
                // );

                if (! empty($classificationErrors)) {
                    $errors = array_merge($errors, $classificationErrors);
                    $rowNumber++;

                    continue;
                }

                try {
                    // Generate code
                    $code = $this->codeGenerationService->generateCode('worker');

                    // Convert classification TKDN from string to integer
                    $classificationTkdn = null;
                    if (!empty($row[6])) {
                        $classificationTkdn = StringHelper::classificationTkdnToInt(trim($row[6]));
                    }

                    // Create worker
                    Worker::create([
                        'name' => trim($row[0]),
                        'unit' => trim($row[1]),
                        'category_id' => $categoryId,
                        'classification_tkdn' => $classificationTkdn,
                        'price' => (int) $row[3],
                        'tkdn' => $row[4],
                        'location' => ! empty($row[5]) ? trim($row[5]) : null,
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
            $this->importService->logImportProgress('worker', $imported, count($rows), $errors);

            if (empty($errors)) {
                return redirect()->route('master.worker.index')
                    ->with('success', "Successfully imported {$imported} workers!");
            } else {
                return redirect()->route('master.worker.index')
                    ->with('success', "Successfully imported {$imported} workers!")
                    ->with('import_errors', $errors);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('master.worker.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
