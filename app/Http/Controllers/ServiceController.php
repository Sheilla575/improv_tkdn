<?php

namespace App\Http\Controllers;

use App\Models\EstimationItem;
use App\Models\Hpp;
use App\Models\HppAhs;
use App\Models\HppItem;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Services\ServiceApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\select;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-service')->only(['create', 'store', 'edit', 'update', 'destroy', 'generate']);
    }

    public function index()
    {
        $services = Service::with(['project', 'items', 'project.hpps'])
            ->latest()
            ->paginate(10);

        return view('service.index', compact('services'));
    }

    public function create()
    {
        $projects = Project::where('status', '!=', 'completed')->get();

        return view('service.create', compact('projects'));
    }

    /**
     * Get HPP data for AJAX request
     */
    public function getHppData(Request $request)
    {
        try {
            $projectId = $request->project_id;

            if (! $projectId) {
                return response()->json(['error' => 'Project ID diperlukan'], 400);
            }

            // Log request for debugging
            Log::info('getHppData request', [
                'project_id' => $projectId,
                'request_data' => $request->all(),
            ]);

            // Validate project exists
            $project = Project::find($projectId);
            if (! $project) {
                Log::warning('Project not found', ['project_id' => $projectId]);

                return response()->json(['error' => 'Project tidak ditemukan'], 404);
            }

            Log::info('Project found', [
                'project_id' => $project->id,
                'project_name' => $project->name,
            ]);

            // Ambil semua HPP untuk project ini dengan error handling
            $hpps = Hpp::where('project_id', $projectId)
                ->where('status', 'approved')
                ->with(['items' => function ($query) use ($project) {
                    // Filter berdasarkan project type dengan classification_tkdn dari master data
                    $classifications = $project->project_type === 'tkdn_jasa'
                        ? [1, 2, 3, 4]
                        : [1, 2, 3, 4, 5, 6];

                    $query->where(function ($q) use ($classifications) {
                        // Filter berdasarkan model polymorphic (Worker, Material, Equipment, JournalWorker)
                        $q->where(function ($wq) use ($classifications) {
                            $wq->where('item_type', \App\Models\Worker::class)
                                ->whereHasMorph('item', [\App\Models\Worker::class], function ($workerQuery) use ($classifications) {
                                    $workerQuery->whereIn('classification_tkdn', $classifications);
                                });
                        })
                            ->orWhere(function ($mq) use ($classifications) {
                                $mq->where('item_type', \App\Models\Material::class)
                                    ->whereHasMorph('item', [\App\Models\Material::class], function ($materialQuery) use ($classifications) {
                                        $materialQuery->whereIn('classification_tkdn', $classifications);
                                    });
                            })
                            ->orWhere(function ($eq) use ($classifications) {
                                $eq->where('item_type', \App\Models\Equipment::class)
                                    ->whereHasMorph('item', [\App\Models\Equipment::class], function ($equipmentQuery) use ($classifications) {
                                        $equipmentQuery->whereIn('classification_tkdn', $classifications);
                                    });
                            })
                            ->orWhere(function ($jq) use ($classifications) {
                                $jq->where('item_type', \App\Models\JournalWorker::class)
                                    ->whereHasMorph('item', [\App\Models\JournalWorker::class], function ($journalQuery) use ($classifications) {
                                        $journalQuery->whereIn('classification_tkdn', $classifications);
                                    });
                            });
                    });

                    $query->orderBy('id');
                }, 'project', 'items.item'])
                ->get();


            Log::info('HPP query result', [
                'project_id' => $projectId,
                'name_hpp' => $hpps->pluck('name_hpp')->toArray(),
                'hpp_count' => $hpps->count(),
                'hpp_ids' => $hpps->pluck('id')->toArray(),
            ]);

            if ($hpps->isEmpty()) {
                Log::info('No HPP found for project', ['project_id' => $projectId]);

                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada HPP ditemukan untuk project ini',
                ]);
            }

            $hppData = [];
            foreach ($hpps as $hpp) {
                try {
                    Log::info('Processing HPP', [
                        'hpp_id' => $hpp->id,
                        'name_hpp' => $hpp->name_hpp,
                        'hpp_code' => $hpp->code,
                        'items_count' => $hpp->items ? $hpp->items->count() : 0,
                    ]);
                    // Ambil semua item terkait
                    $items = $hpp->items ?? collect();

                    // Hitung jumlah item valid dan kumpulkan klasifikasi
                    $classifications = collect();

                    foreach ($items as $item) {
                        $relatedModel = $item->item; // Relasi morphTo → Worker/Material/Equipment/Ahs

                        if ($relatedModel && property_exists($relatedModel, 'classification_tkdn')) {
                            if (!empty($relatedModel->classification_tkdn)) {
                                $classifications->push($relatedModel->classification_tkdn);
                            }
                        }
                        // Jika item-nya AHS, kamu bisa ambil klasifikasi dari detail AHS-nya juga
                        elseif ($relatedModel instanceof \App\Models\Ahs) {
                            $detailItems = $relatedModel->hppItems ?? collect();
                            foreach ($detailItems as $detail) {
                                $subItem = $detail->item;
                                if ($subItem && property_exists($subItem, 'classification_tkdn')) {
                                    $classifications->push($subItem->classification_tkdn);
                                }
                            }
                        }
                    }

                    $hppData[] = [
                        'id'            => $hpp->id,
                        'code'          => $hpp->code ?? 'N/A',
                        'name_hpp'      => $hpp->name_hpp ?? 'N/A',
                        'total_cost'    => $hpp->grand_total ?? 0,
                        'items_count'   => $items->count(),
                        'project_name'  => $hpp->project ? $hpp->project->name : 'N/A',
                        'project_code'  => $hpp->project ? $hpp->project->code : 'N/A',
                        'project_type'  => $hpp->project ? $hpp->project->project_type : 'tkdn_jasa',

                        // 🔹 Hitung TKDN Breakdown langsung dari relasi master data
                        'tkdn_breakdown' => $hpp->items
                            ? $hpp->items
                            ->groupBy(function ($item) {
                                // Ambil classification langsung dari model relasi polymorphic
                                $related = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker

                                if ($related && property_exists($related, 'classification_tkdn')) {
                                    $classificationInt = $related->classification_tkdn;
                                } else {
                                    $classificationInt = null;
                                }

                                return $classificationInt
                                    ? \App\Helpers\StringHelper::intToClassificationTkdn($classificationInt)
                                    : 'Unknown';
                            })
                            ->map(function ($items, $classification) {
                                return [
                                    'classification' => $classification,
                                    'count'          => $items->count(),
                                    'total_cost'     => $items->sum('total_price'),
                                ];
                            })
                            ->filter(function ($data, $classification) use ($hpp) {
                                // ❌ skip Unknown
                                if ($classification === 'Unknown') {
                                    return false;
                                }

                                $classificationInt = \App\Helpers\StringHelper::classificationTkdnToInt($classification);
                                if (! $classificationInt) {
                                    return false;
                                }

                                // 🔹 Filter berdasarkan project type
                                if ($hpp->project->project_type === 'tkdn_jasa') {
                                    $formNumbers = \App\Models\Project::getFormNumbersForClassification(
                                        $classificationInt,
                                        'tkdn_jasa'
                                    );

                                    return ! empty($formNumbers);
                                }

                                if ($hpp->project->project_type === 'tkdn_barang_jasa') {
                                    $formNumbers = \App\Models\Material::getFormNumbersForClassification(
                                        $classificationInt,
                                        'tkdn_barang_jasa'
                                    );

                                    return ! empty($formNumbers);
                                }

                                return true;
                            })
                            : [],
                    ];
                } catch (\Exception $itemError) {
                    Log::warning('Error processing HPP item', [
                        'hpp_id' => $hpp->id,
                        'error' => $itemError->getMessage(),
                        'trace' => $itemError->getTraceAsString(),
                    ]);

                    // Add fallback data for this HPP
                    $hppData[] = [
                        'id' => $hpp->id,
                        'code' => $hpp->code ?? 'N/A',
                        'total_cost' => 0,
                        'items_count' => 0,
                        'project_name' => 'N/A',
                        'project_code' => 'N/A',
                        'tkdn_breakdown' => [],
                        'error' => 'Data tidak dapat diproses',
                    ];
                }
            }

            Log::info('getHppData success', [
                'project_id' => $projectId,
                'data_count' => count($hppData),
            ]);

            return response()->json([
                'success' => true,
                'data' => $hppData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getHppData', [
                'project_id' => $request->project_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengambil data HPP: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate TKDN forms dari data HPP
     */
    private function generateTkdnFormsFromHpp(Service $service, Hpp $hpp)
    {
        Log::info('Starting TKDN forms generation from HPP 2', [
            'service_id' => $service->id,
            'hpp_id' => $hpp->id,
            'service_type' => $service->service_type,
            'project_id' => $service->project_id,
            'form_category' => $service->form_category,
        ]);

        // Tentukan daftar form langsung dari data HPP yang dipilih
        $formsFromHpp = $hpp->items
            ->pluck('tkdn_classification')
            ->filter()
            ->unique()
            ->values();

        // Siapkan judul form sederhana
        $formTitles = [
            '3.1' => 'Overhead & Manajemen',
            '3.2' => 'Alat / Fasilitas Kerja',
            '3.3' => 'Konstruksi & Fabrikasi',
            '3.4' => 'Peralatan (Jasa Umum)',
            '3.5' => 'Summary',
            '4.1' => 'Material (Bahan Baku)',
            '4.2' => 'Peralatan (Barang Jadi)',
            '4.3' => 'Overhead & Manajemen',
            '4.4' => 'Alat / Fasilitas Kerja',
            '4.5' => 'Konstruksi & Fabrikasi',
            '4.6' => 'Peralatan (Jasa Umum)',
            '4.7' => 'Summary',
        ];

        // Jika HPP belum memiliki tkdn_classification pada item, fallback sesuai category
        if ($formsFromHpp->isEmpty()) {
            $formsFromHpp = $service->form_category === Service::CATEGORY_TKDN_JASA
                ? collect(['3.1', '3.2', '3.3', '3.4', '3.5'])
                : collect(['4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7']);
        }

        // Bentuk map formCode => formTitle
        $formsToGenerate = $formsFromHpp->mapWithKeys(function ($code) use ($formTitles) {
            return [$code => ($formTitles[$code] ?? ('Form ' . $code))];
        })->toArray();
        $generatedForms = [];

        // Hapus semua service items yang ada sebelumnya untuk menghindari duplikasi
        Log::info('Cleaning up existing service items before generation', [
            'service_id' => $service->id,
            'existing_items_count' => $service->items()->count(),
        ]);
        $service->items()->delete();

        foreach ($formsToGenerate as $formCode => $formName) {
            // Selalu panggil creator; kalau kosong akan dibuat placeholder
            $this->createTkdnFormFromHpp($service, $hpp, $formCode, $formName);
            $generatedForms[] = $formCode;

            // Verifikasi item telah dibuat
            $createdItemsCount = $service->items()->where('tkdn_classification', $formCode)->count();
            Log::info("Form {$formCode} generation completed (direct call)", [
                'service_items_created' => $createdItemsCount,
                'hpp_id' => $hpp->id,
            ]);
        }

        // Generate Form 3.5 sebagai rangkuman dari form lainnya (hanya untuk kategori TKDN Jasa)
        if ($service->form_category === Service::CATEGORY_TKDN_JASA) {
            Log::info('Generating Form 3.5 - Rangkuman TKDN Jasa as summary from other forms');

            // Hapus form 3.5 yang lama jika ada
            $existing35Count = $service->items()->where('tkdn_classification', '3.5')->count();
            if ($existing35Count > 0) {
                Log::info('Deleting existing Form 3.5 items before regeneration', [
                    'existing_count' => $existing35Count,
                ]);
                $service->items()->where('tkdn_classification', '3.5')->delete();
            }

            $this->createTkdnForm35Summary($service);
            $generatedForms[] = '3.5';

            // Verifikasi form 3.5 telah dibuat
            $created35Count = $service->items()->where('tkdn_classification', '3.5')->count();
            Log::info('Form 3.5 generation completed', [
                'service_items_created' => $created35Count,
            ]);
        }

        // Recalculate service totals
        $service->calculateTotals();

        // Verifikasi semua form telah di-generate
        $actualGeneratedForms = $service->items()->select('tkdn_classification')->distinct()->pluck('tkdn_classification')->toArray();
        $totalServiceItems = $service->items()->count();

        Log::info('Generated forms verification from HPP', [
            'service_id' => $service->id,
            'hpp_id' => $hpp->id,
            'generated_forms' => $actualGeneratedForms,
            'expected_forms' => $generatedForms,
            'total_service_items' => $totalServiceItems,
            'forms_generated' => count($actualGeneratedForms),
            'breakdown' => array_map(function ($formCode) use ($service) {
                return [
                    'form' => $formCode,
                    'items_count' => $service->items()->where('tkdn_classification', $formCode)->count(),
                ];
            }, $actualGeneratedForms),
        ]);

        // Warning jika jumlah form tidak sesuai
        if (count($actualGeneratedForms) !== count($generatedForms)) {
            Log::warning('Form generation mismatch', [
                'expected_count' => count($generatedForms),
                'actual_count' => count($actualGeneratedForms),
                'missing_forms' => array_diff($generatedForms, $actualGeneratedForms),
                'unexpected_forms' => array_diff($actualGeneratedForms, $generatedForms),
                'hpp_id' => $hpp->id,
            ]);
        }

        Log::info('TKDN forms generation from HPP completed', [
            'service_id' => $service->id,
            'hpp_id' => $hpp->id,
            'total_service_items' => $totalServiceItems,
            'forms_generated' => count($actualGeneratedForms),
            'generation_successful' => count($actualGeneratedForms) > 0,
        ]);
    }

    /**
     * Create TKDN form dari data HPP dengan AHS items
     */
    private function createTkdnFormFromHpp(Service $service, Hpp $hpp, string $formNumber, string $formTitle)
    {
        Log::info('Creating TKDN form from HPP', [
            'service_id' => $service->id,
            'hpp_id' => $hpp->id,
            'form_number' => $formNumber,
            'form_title' => $formTitle,
        ]);

        // 1. Ambil HPP items sesuai tkdn_classification dari master data (filtered by hpp_id)
        $hppItems = $this->getHppItemsByTkdnClassification($hpp->project_id, $formNumber, $hpp->id);

        Log::info('HPP items found for form', [
            'form_number' => $formNumber,
            'hpp_items_count' => $hppItems->count(),
            'hpp_items_ids' => $hppItems->pluck('id')->toArray(),
        ]);

        // Jika tidak ada HPP items, buat placeholder item untuk form 3.4 dan 3.5
        if ($hppItems->isEmpty()) {
            Log::info('No HPP items found, creating placeholder', [
                'service_id' => $service->id,
                'form_number' => $formNumber,
            ]);

            if ($formNumber === '3.4') {
                $this->createPlaceholderServiceItems($service, $formNumber, 'Peralatan (Jasa Umum)');
            } elseif ($formNumber === '3.5') {
                $this->createPlaceholderServiceItems($service, $formNumber, 'Summary');
            } else {
                // Untuk form lain yang tidak memiliki HPP items, buat placeholder juga
                Log::info('Creating placeholder for form without HPP items', [
                    'form_number' => $formNumber,
                ]);
                $this->createPlaceholderServiceItems($service, $formNumber, $formTitle);
            }

            return;
        }

        $itemNumber = 1;

        foreach ($hppItems as $hppItem) {
            Log::info('Processing HPP item', [
                'hpp_item_id' => $hppItem->id,
                'form_number' => $formNumber,
                // 'estimation_item_id' => $hppItem->estimation_item_id,
                'description' => $hppItem->description,
                'volume' => $hppItem->volume,
                'unit' => $hppItem->unit,
                'duration_unit' => $hppItem->duration_unit,
                'koefisien' => $hppItem->koefisien,
                'unit_price' => $hppItem->unit_price,
                'total_price' => $hppItem->total_price,
                'duration' => $hppItem->duration,
                'total_price' => $hppItem->total_price,
            ]);

            // 2. Buat service item langsung dari HPP item dengan master data
            $this->createServiceItemFromHpp($service, $hppItem, $formNumber, $itemNumber++);
        }

        // Recalculate totals
        $service->calculateTotals();

        Log::info('TKDN form from HPP created successfully', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'service_items_count' => $service->items()->where('tkdn_classification', $formNumber)->count(),
        ]);
    }

    /**
     * Create service item from HPP item directly (helper method)
     */
    private function createServiceItemFromHpp(Service $service, $hppItem, string $formNumber, int $itemNumber)
    {
        // Prefer tkdn_classification from hpp_items if present
        $tkdnForm = $hppItem->tkdn_classification ?: $formNumber;

        // Derive costs based on unit price, quantity and duration; fallback to total_price
        $quantity = $hppItem->volume ?? 1;
        $duration = $hppItem->duration ?? 1;
        $unitPrice = $hppItem->unit_price ?? 0;
        $computedTotal = ($unitPrice * $quantity * $duration);
        $totalCost = ($hppItem->total_price !== null) ? $hppItem->total_price : $computedTotal;

        $tkdnPercentage = $this->calculateTkdnPercentageForForm($tkdnForm);
        $domesticCost = ($totalCost * $tkdnPercentage) / 100;
        $foreignCost = $totalCost - $domesticCost;

        // Build description and nationality from master data when available
        $related = $hppItem->item; // morph: Worker/Material/Equipment/JournalWorker
        $description = $hppItem->description
            ?? ($related->name ?? ($related->nama_pekerjaan ?? ('Item ' . $itemNumber)));

        $nationality = 'WNI';
        if ($related) {
            // Attempt to infer nationality from typical fields
            $origin = $related->negara_asal ?? null;
            if ($origin && is_string($origin)) {
                $nationality = (strtoupper($origin) === 'INDONESIA') ? 'WNI' : 'WNA';
            }
        }

        Log::info('Creating service item from HPP item directly', [
            'hpp_item_id' => $hppItem->id,
            'form_number' => $tkdnForm,
            'item_number' => $itemNumber,
            'tkdn_percentage' => $tkdnPercentage,
            'quantity' => $quantity,
            'duration' => $duration,
            'unit_price' => $unitPrice,
            'total_cost' => $totalCost,
        ]);

        ServiceItem::create([
            'service_id' => $service->id,
            'estimation_item_id' => $hppItem->estimation_item_id ?? null,
            'item_number' => $itemNumber,
            'tkdn_classification' => $tkdnForm,
            'description' => $description,
            'qualification' => $this->getQualificationFromHppItem($hppItem),
            'nationality' => $nationality,
            'tkdn_percentage' => $tkdnPercentage,
            'quantity' => $quantity,
            'duration' => $duration,
            'duration_unit' => $hppItem->duration_unit ?? 'ls',
            'wage' => $unitPrice,
            'domestic_cost' => $domesticCost,
            'foreign_cost' => $foreignCost,
            'total_cost' => $totalCost,
        ]);
    }

    /**
     * Hitung TKDN percentage berdasarkan form dan kategori AHS
     */
    private function calculateTkdnPercentage(string $formNumber, string $category): float
    {
        // Form 3.1: Jasa Manajemen Proyek - Default 100% TKDN
        if ($formNumber === '3.1') {
            return 100.0;
        }

        // Form 3.2: Jasa Alat Kerja - Default 0% TKDN
        if ($formNumber === '3.2') {
            return 0.0;
        }

        // Form 3.3: Jasa Konstruksi - Default 0% TKDN
        if ($formNumber === '3.3') {
            return 0.0;
        }

        // Form 3.4: Jasa Konsultasi - Default 100% TKDN (WNI)
        if ($formNumber === '3.4') {
            return 100.0;
        }

        // Form 3.5: Rangkuman - Default 100% TKDN
        if ($formNumber === '3.5') {
            return 100.0;
        }

        // Form 4.1: Jasa Teknik dan Rekayasa - Default 100% TKDN (WNI)
        if ($formNumber === '4.1') {
            return 100.0;
        }

        // Form 4.2: Jasa Pengadaan dan Logistik - Default 80% TKDN
        if ($formNumber === '4.2') {
            return 80.0;
        }

        // Form 4.3: Jasa Operasi dan Pemeliharaan - Default 100% TKDN (WNI)
        if ($formNumber === '4.3') {
            return 100.0;
        }

        // Form 4.4: Jasa Pelatihan dan Sertifikasi - Default 100% TKDN (WNI)
        if ($formNumber === '4.4') {
            return 100.0;
        }

        // Form 4.5: Jasa Teknologi Informasi - Default 70% TKDN
        if ($formNumber === '4.5') {
            return 70.0;
        }

        // Form 4.6: Jasa Lingkungan dan Keamanan - Default 100% TKDN (WNI)
        if ($formNumber === '4.6') {
            return 100.0;
        }

        // Form 4.7: Jasa Lainnya - Default 60% TKDN
        if ($formNumber === '4.7') {
            return 60.0;
        }

        // Default berdasarkan kategori
        switch ($category) {
            case 'worker':
                return 100.0;    // Pekerja WNI
            case 'material':
                return 0.0;      // Material impor
            case 'equipment':
                return 0.0;      // Equipment impor
            default:
                return 50.0;     // Default 50%
        }
    }

    /**
     * Tentukan form_category otomatis berdasarkan form yang tersedia di HPP
     */
    private function determineFormCategoryFromHpp(Hpp $hpp): string
    {
        // Ambil semua tkdn_classification yang ada di HPP items
        $availableClassifications = $hpp->items()
            ->select('tkdn_classification')
            ->distinct()
            ->pluck('tkdn_classification')
            ->toArray();

        Log::info('Determining form category from HPP', [
            'hpp_id' => $hpp->id,
            'available_classifications' => $availableClassifications,
        ]);

        // Cek apakah ada form 4.x (TKDN Barang & Jasa)
        $hasForm4 = collect($availableClassifications)->filter(function ($classification) {
            return str_starts_with($classification, '4.');
        })->isNotEmpty();

        // Cek apakah ada form 3.x (TKDN Jasa)
        $hasForm3 = collect($availableClassifications)->filter(function ($classification) {
            return str_starts_with($classification, '3.');
        })->isNotEmpty();

        // Prioritas: Jika ada form 4.x, gunakan TKDN Barang & Jasa
        if ($hasForm4) {
            Log::info('Form category determined: TKDN Barang & Jasa (found form 4.x)', [
                'hpp_id' => $hpp->id,
                'form4_classifications' => collect($availableClassifications)
                    ->filter(fn($c) => str_starts_with($c, '4.'))
                    ->values()
                    ->toArray(),
            ]);

            return Service::CATEGORY_TKDN_BARANG_JASA;
        }

        // Jika hanya ada form 3.x atau tidak ada form sama sekali, gunakan TKDN Jasa
        if ($hasForm3 || empty($availableClassifications)) {
            Log::info('Form category determined: TKDN Jasa (found form 3.x or no forms)', [
                'hpp_id' => $hpp->id,
                'form3_classifications' => collect($availableClassifications)
                    ->filter(fn($c) => str_starts_with($c, '3.'))
                    ->values()
                    ->toArray(),
            ]);

            return Service::CATEGORY_TKDN_JASA;
        }

        // Default fallback ke TKDN Jasa
        Log::info('Form category determined: TKDN Jasa (default fallback)', [
            'hpp_id' => $hpp->id,
            'available_classifications' => $availableClassifications,
        ]);

        return Service::CATEGORY_TKDN_JASA;
    }

    /**
     * Generate description untuk AHS item
     */
    private function getAhsItemDescription(EstimationItem $ahsItem): string
    {
        try {
            switch ($ahsItem->category) {
                case 'worker':
                    return ($ahsItem->worker && $ahsItem->worker->name) ? $ahsItem->worker->name : ($ahsItem->code ?? 'Worker Item');
                case 'material':
                    return ($ahsItem->material && $ahsItem->material->name) ? $ahsItem->material->name : ($ahsItem->code ?? 'Material Item');
                case 'equipment':
                    return ($ahsItem->equipment && $ahsItem->equipment->name) ? $ahsItem->equipment->name : ($ahsItem->code ?? 'Equipment Item');
                default:
                    return $ahsItem->code ?? 'Item';
            }
        } catch (\Exception $e) {
            Log::warning('Error getting AHS item description', [
                'ahs_item_id' => $ahsItem->id,
                'category' => $ahsItem->category,
                'error' => $e->getMessage(),
            ]);

            return $ahsItem->code ?? 'Item';
        }
    }

    /**
     * Generate qualification untuk AHS item
     */
    private function getAhsItemQualification(EstimationItem $ahsItem): ?string
    {
        try {
            switch ($ahsItem->category) {
                case 'worker':
                    return ($ahsItem->worker && $ahsItem->worker->position) ? $ahsItem->worker->position : 'Pekerja';
                case 'material':
                    return ($ahsItem->material && $ahsItem->material->specification) ? $ahsItem->material->specification : 'Material';
                case 'equipment':
                    return ($ahsItem->equipment && $ahsItem->equipment->type) ? $ahsItem->equipment->type : 'Equipment';
                default:
                    return 'Umum';
            }
        } catch (\Exception $e) {
            Log::warning('Error getting AHS item qualification', [
                'ahs_item_id' => $ahsItem->id,
                'category' => $ahsItem->category,
                'error' => $e->getMessage(),
            ]);

            return 'Umum';
        }
    }

    private function determineServiceTypeFromProjectType(string $projectType): string
    {
        switch ($projectType) {
            case 'tkdn_jasa':
                return 'project'; // Default untuk TKDN Jasa
            case 'tkdn_barang_jasa':
                return 'project'; // Default untuk TKDN Barang & Jasa
            default:
                return 'project';
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hpp_id' => 'required|exists:hpps,id',
        ]);

        Log::info('Starting TKDN forms generation', [
            'service_id' => "mulai dari HPP ID {$validated['hpp_id']}",
        ]);


        try {
            $service = null;

            DB::transaction(function () use ($validated, &$service) {
                // Ambil data HPP dengan project
                $hpp = Hpp::with(['items', 'project'])->findOrFail($validated['hpp_id']);

                // Tentukan form_category otomatis berdasarkan project_type
                $formCategory = $hpp->project->project_type === 'tkdn_jasa' ? 'tkdn_jasa' : 'tkdn_barang_jasa';

                // Tentukan service_type berdasarkan project_type
                $serviceType = $this->determineServiceTypeFromProjectType($hpp->project->project_type);

                // Auto-generate service name dari HPP code
                $serviceName = 'Service TKDN - ' . $hpp->code;

                $service = Service::create([
                    'project_id' => $hpp->project_id,
                    'service_name' => $serviceName,
                    'form_category' => $formCategory,
                    'service_type' => $serviceType,
                    'provider_name' => $hpp->project->company ?? 'PT Konstruksi Maju',
                    'provider_address' => $hpp->project->address ?? 'Jl. Sudirman No. 123, Jakarta Pusat',
                    'user_name' => $hpp->project->client ?? 'PT Pembangunan Indonesia',
                    'document_number' => 'DOC-' . $hpp->code,
                    'hpp_id' => $validated['hpp_id'],
                    'status' => 'draft',
                ]);

                // Generate TKDN forms otomatis dari data HPP
                $this->generateTkdnFormsFromHpp($service, $hpp);

                Log::info('Service created successfully with TKDN forms', [
                    'service_id' => $service->id,
                    'hpp_id' => $hpp->id,
                    'service_type' => $service->service_type,
                    'project_type' => $hpp->project->project_type,
                    'total_service_items' => $service->items()->count(),
                ]);
            });

            return redirect()->route('service.show', $service)
                ->with('success', 'Service berhasil dibuat dan form TKDN telah di-generate otomatis dari HPP.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan service: ' . $e->getMessage());
        }
    }

    public function show(Service $service)
    {
        // $service->load(['project']);
        $service->load(['project', 'items.estimationItem.worker', 'items.estimationItem.material', 'items.estimationItem.equipment']);
        // 

        // Get project type
        $projectType = $service->project->project_type;

        // Use optimized service items with lazy loading and caching
        $optimizedItems = $service->getOptimizedServiceItems($projectType);

        // Group items berdasarkan tkdn_classification
        $groupedItems = $optimizedItems->groupBy('tkdn_classification');

        // Ambil HPP items yang sesuai dengan project_type melalui master data langsung
        $hppItems = collect();
        if ($service->project_id) {
            // Gunakan integer classification sesuai dengan master data
            $classifications = $projectType === 'tkdn_jasa'
                ? [1, 2, 3, 4]  // Integer format untuk tkdn_jasa
                : [1, 2, 3, 4, 5, 6]; // Integer format untuk tkdn_barang_jasa

            $hppItems = \App\Models\HppItem::whereHas('hpp', function ($query) use ($service) {
                $query->where('project_id', $service->project_id);
            })
                ->where(function ($query) use ($classifications) {
                    // Filter berdasarkan model polymorphic langsung
                    $query->where(function ($q) use ($classifications) {
                        $q->where('item_type', \App\Models\Worker::class)
                            ->whereHasMorph('item', [\App\Models\Worker::class], function ($workerQuery) use ($classifications) {
                                $workerQuery->whereIn('classification_tkdn', $classifications);
                            });
                    })
                        ->orWhere(function ($q) use ($classifications) {
                            $q->where('item_type', \App\Models\Material::class)
                                ->whereHasMorph('item', [\App\Models\Material::class], function ($materialQuery) use ($classifications) {
                                    $materialQuery->whereIn('classification_tkdn', $classifications);
                                });
                        })
                        ->orWhere(function ($q) use ($classifications) {
                            $q->where('item_type', \App\Models\Equipment::class)
                                ->whereHasMorph('item', [\App\Models\Equipment::class], function ($equipmentQuery) use ($classifications) {
                                    $equipmentQuery->whereIn('classification_tkdn', $classifications);
                                });
                        })
                        ->orWhere(function ($q) use ($classifications) {
                            $q->where('item_type', \App\Models\JournalWorker::class)
                                ->whereHasMorph('item', [\App\Models\JournalWorker::class], function ($journalQuery) use ($classifications) {
                                    $journalQuery->whereIn('classification_tkdn', $classifications);
                                });
                        });
                })
                ->with(['hpp', 'item']) // Langsung load relasi morphTo
                ->get();

            // Group by classification dari master data dengan format string yang benar
            $hppItems = $hppItems->groupBy(function ($item) use ($projectType) {
                // Get classification langsung dari master data melalui relasi morphTo
                $relatedItem = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker

                if ($relatedItem && property_exists($relatedItem, 'classification_tkdn')) {
                    $classification = $relatedItem->classification_tkdn;

                    // Convert integer ke string format yang diharapkan view
                    if ($classification) {
                        return $projectType === 'tkdn_jasa'
                            ? "3.{$classification}"
                            : "4.{$classification}";
                    }
                }

                return 'unknown';
            });
        }

        // Extract HPP code from service name (e.g., "Service TKDN - HPP-20250923-V2FC" -> "HPP-20250923-V2FC")
        $hppCode = null;
        if (preg_match('/Service TKDN - (.+)/', $service->service_name, $matches)) {
            $hppCode = $matches[1];
        }

        // Get HPP ID based on project_id and extracted code
        $hppId = null;
        if ($hppCode && $service->project_id) {
            $hpp = Hpp::where('project_id', $service->project_id)
                ->where('code', $hppCode)
                ->first();
            $hppId = $hpp ? $hpp->id : null;
        }

        // Buat variabel untuk semua HPP items dalam format flat berdasarkan HPP ID
        $allHppItemsFlat = collect();
        $hppModel = null;
        if ($hppId) {
            $hppModel = $hpp; // Store the HPP model for overhead/margin data
            $hppItemsFromId = \App\Models\HppItem::where('hpp_id', $hppId)
                ->with(['hpp', 'item']) // Langsung load relasi morphTo
                ->get();

            $allHppItemsFlat = $hppItemsFromId->map(function ($item) {
                $relatedItem = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker

                return [
                    'id' => $item->id,
                    'hpp_id' => $item->hpp_id,
                    'description' => $item->description,
                    'volume' => $item->volume,
                    'duration' => $item->duration,
                    'total_price' => $item->total_price,
                    'estimation_item_id' => $item->estimation_item_id,
                    'master_classification' => [
                        'classification_tkdn' => $relatedItem && property_exists($relatedItem, 'classification_tkdn')
                            ? $relatedItem->classification_tkdn
                            : null,
                        'item_type' => $item->item_type,
                        'item_name' => $relatedItem ? ($relatedItem->name ?? $relatedItem->nama_pekerjaan ?? 'N/A') : 'N/A',
                    ]
                ];
            });
        }

        // dd($allHppItemsFlat->toArray());

        $approvalService = new ServiceApprovalService();
        $availableActions = $approvalService->getAvailableActions($service);

        // Load logs with user relationship
        $service->load(['logs.user']);

        return view('service.show', compact('service', 'groupedItems', 'hppItems', 'projectType', 'allHppItemsFlat', 'hppModel', 'approvalService', 'availableActions'));
    }

    public function edit(Service $service)
    {
        $projects = Project::where('status', '!=', 'completed')->get();
        $service->load(['project', 'items']);

        return view('service.edit', compact('service', 'projects'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'service_name' => 'required|string|max:255',
            'provider_name' => 'nullable|string|max:255',
            'provider_address' => 'nullable|string',
            'user_name' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.qualification' => 'nullable|string|max:255',
            'items.*.nationality' => 'required|in:WNI,WNA',
            'items.*.tkdn_percentage' => 'required|numeric|min:0|max:100',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.duration' => 'required|numeric|min:0',
            'items.*.duration_unit' => 'required|string|max:10',
            'items.*.wage' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated, $service) {
                $service->update([
                    'project_id' => $validated['project_id'],
                    'service_name' => $validated['service_name'],
                    'provider_name' => $validated['provider_name'],
                    'provider_address' => $validated['provider_address'],
                    'user_name' => $validated['user_name'],
                    'document_number' => $validated['document_number'],
                ]);

                // Hapus item lama
                $service->items()->delete();

                // Buat item baru
                foreach ($validated['items'] as $index => $item) {
                    $serviceItem = ServiceItem::create([
                        'service_id' => $service->id,
                        'item_number' => $index + 1,
                        'description' => $item['description'],
                        'qualification' => $item['qualification'],
                        'nationality' => $item['nationality'],
                        'tkdn_percentage' => $item['tkdn_percentage'],
                        'quantity' => $item['quantity'],
                        'duration' => $item['duration'],
                        'duration_unit' => $item['duration_unit'],
                        'wage' => $item['wage'],
                    ]);

                    $serviceItem->calculateCosts();
                }

                $service->calculateTotals();
            });

            return redirect()->route('service.index')
                ->with('success', 'Jasa berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui jasa.');
        }
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();

            return redirect()->route('service.index')
                ->with('success', 'Jasa berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus jasa.');
        }
    }

    public function submit(Service $service)
    {
        $service->update(['status' => 'submitted']);

        return redirect()->route('service.show', $service)
            ->with('success', 'Jasa berhasil diajukan.');
    }

    public function approve(Request $request, Service $service)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $approvalService = new ServiceApprovalService();
            $approvalService->approve($service, $request->notes);

            return redirect()->route('service.show', $service)
                ->with('success', 'Service berhasil disetujui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui service: ' . $e->getMessage());
        }
    }

    public function reject(Service $service)
    {
        $service->update(['status' => 'rejected']);

        return redirect()->route('service.show', $service)
            ->with('success', 'Jasa berhasil ditolak.');
    }

    public function generate(Service $service)
    {
        try {
            DB::transaction(function () use ($service) {
                // Generate semua form TKDN berdasarkan service type
                $this->generateTkdnForms($service);

                // Update status service menjadi generated
                $service->update(['status' => 'generated']);
            });

            return redirect()->route('service.show', $service)
                ->with('success', 'Form TKDN berhasil dibuat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat form TKDN: ' . $e->getMessage());
        }
    }

    /**
     * Generate specific TKDN form
     */
    public function generateForm(Service $service, string $formNumber)
    {
        try {
            // Validate form number
            $validForms = ['3.1', '3.2', '3.3', '3.4', '4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7', '3.5'];
            if (! in_array($formNumber, $validForms)) {
                return back()->with('error', 'Nomor form TKDN tidak valid.');
            }

            DB::transaction(function () use ($service, $formNumber) {
                // Generate specific form
                $this->generateSpecificTkdnForm($service, $formNumber);
            });

            return redirect()->route('service.show', $service)
                ->with('success', "Form {$formNumber} TKDN berhasil dibuat.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membuat form TKDN: ' . $e->getMessage());
        }
    }

    private function generateTkdnForms(Service $service)
    {
        Log::info('Starting TKDN forms generation', [
            'service_id' => $service->id,
            'service_type' => $service->service_type,
            'project_id' => $service->project_id,
        ]);

        // Generate Form 3.1 - Overhead & Manajemen
        if ($service->service_type === 'project') {
            $this->createTkdnForm($service, '3.1', 'Overhead & Manajemen');
        }

        // Generate Form 3.2 - Alat / Fasilitas Kerja
        if ($service->service_type === 'equipment') {
            $this->createTkdnForm($service, '3.2', 'Alat / Fasilitas Kerja');
        }

        // Generate Form 3.3 - Konstruksi Fabrikasi
        if ($service->service_type === 'construction') {
            $this->createTkdnForm($service, '3.3', 'Konstruksi Fabrikasi');
        }

        // Generate Form 3.4 - Peralatan (Jasa Umum) (selalu ada)
        Log::info('Generating Form 3.4 - Peralatan (Jasa Umum)');
        $this->createTkdnForm($service, '3.4', 'Peralatan (Jasa Umum)');

        // Generate Form 4.1 - Material (Bahan Baku) (selalu ada)
        Log::info('Generating Form 4.1 - Material (Bahan Baku)');
        $this->createTkdnForm($service, '4.1', 'Material (Bahan Baku)');

        // Generate Form 4.2 - Peralatan (Barang Jadi) (selalu ada)
        Log::info('Generating Form 4.2 - Peralatan (Barang Jadi)');
        $this->createTkdnForm($service, '4.2', 'Peralatan (Barang Jadi)');

        // Generate Form 4.3 - Overhead & Manajemen (selalu ada)
        Log::info('Generating Form 4.3 - Overhead & Manajemen');
        $this->createTkdnForm($service, '4.3', 'Overhead & Manajemen');

        // Generate Form 4.4 - Alat / Fasilitas Kerja (selalu ada)
        Log::info('Generating Form 4.4 - Alat / Fasilitas Kerja');
        $this->createTkdnForm($service, '4.4', 'Alat / Fasilitas Kerja');

        // Generate Form 4.5 - Konstruksi & Fabrikasi (selalu ada)
        Log::info('Generating Form 4.5 - Konstruksi & Fabrikasi');
        $this->createTkdnForm($service, '4.5', 'Konstruksi & Fabrikasi');

        // Generate Form 4.6 - Peralatan (Jasa Umum) (selalu ada)
        Log::info('Generating Form 4.6 - Peralatan (Jasa Umum)');
        $this->createTkdnForm($service, '4.6', 'Peralatan (Jasa Umum)');

        // Generate Form 4.7 - Summary (selalu ada)
        Log::info('Generating Form 4.7 - Summary');
        $this->createTkdnForm($service, '4.7', 'Summary');

        // Generate Form 3.5 - Summary (selalu ada)
        Log::info('Generating Form 3.5 - Summary');
        $this->createTkdnForm($service, '3.5', 'Summary');

        // Verifikasi semua form telah di-generate
        $generatedForms = $service->items()->select('tkdn_classification')->distinct()->pluck('tkdn_classification')->toArray();
        Log::info('Generated forms verification', [
            'service_id' => $service->id,
            'generated_forms' => $generatedForms,
            'expected_forms' => ['3.1', '3.2', '3.3', '3.4', '4.1', '4.2', '4.3', '4.4', '4.5', '4.6', '4.7', '3.5'],
            'total_service_items' => $service->items()->count(),
            'forms_generated' => count($generatedForms),
        ]);

        Log::info('TKDN forms generation completed', [
            'service_id' => $service->id,
            'total_service_items' => $service->items()->count(),
            'forms_generated' => count($generatedForms),
        ]);
    }

    /**
     * Generate specific TKDN form
     */
    private function generateSpecificTkdnForm(Service $service, string $formNumber)
    {
        $formTitles = [
            '3.1' => 'Overhead & Manajemen',
            '3.2' => 'Alat / Fasilitas Kerja',
            '3.3' => 'Konstruksi Fabrikasi',
            '3.4' => 'Peralatan (Jasa Umum)',
            '3.5' => 'Summary',
        ];

        $title = $formTitles[$formNumber] ?? 'Jasa TKDN';
        $this->createTkdnForm($service, $formNumber, $title);
    }

    private function createTkdnForm(Service $service, string $formNumber, string $formTitle)
    {
        // Ambil data HPP dengan tkdn_classification yang sesuai
        $hppItems = $this->getHppItemsByTkdnClassification($service->project_id, $formNumber);

        // Log untuk debugging
        Log::info('Creating TKDN form', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'form_title' => $formTitle,
            'hpp_items_count' => $hppItems->count(),
            'hpp_items_ids' => $hppItems->pluck('id')->toArray(),
        ]);

        // Generate service items berdasarkan data HPP
        $this->generateServiceItemsFromHpp($service, $hppItems, $formNumber);

        // Verifikasi bahwa service items telah dibuat untuk form ini
        $serviceItemsCount = $service->items()->where('tkdn_classification', $formNumber)->count();

        Log::info('Service items verification for form', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'form_title' => $formTitle,
            'service_items_count' => $serviceItemsCount,
            'hpp_items_count' => $hppItems->count(),
        ]);

        if ($serviceItemsCount === 0) {
            Log::warning('No service items created for form, creating placeholder', [
                'service_id' => $service->id,
                'form_number' => $formNumber,
                'form_title' => $formTitle,
                'hpp_items_count' => $hppItems->count(),
                'hpp_items_ids' => $hppItems->pluck('id')->toArray(),
            ]);

            // Buat placeholder item jika tidak ada service items yang dibuat
            $this->createPlaceholderServiceItems($service, $formNumber, $formTitle);
        }

        // Update field tkdn_classification setelah generate items
        $service->update([
            'tkdn_classification' => $formNumber,
        ]);

        // Log hasil generation
        Log::info('TKDN form created successfully', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'service_items_count' => $service->items()->where('tkdn_classification', $formNumber)->count(),
        ]);
    }

    private function getHppItemsByTkdnClassification(string $projectId, string $formNumber, ?string $hppId = null): \Illuminate\Database\Eloquent\Collection
    {
        Log::info('Getting HPP items by form number (derived from master classification)', [
            'project_id' => $projectId,
            'form_number' => $formNumber,
            'hpp_id' => $hppId,
        ]);

        // Ambil tipe project untuk pemetaan form
        $project = Project::find($projectId);
        if (! $project) {
            return HppItem::whereRaw('1=0')->get();
        }

        // Dapatkan daftar classification ints yang memetakan ke formNumber ini
        $classificationInts = [];
        foreach ([1, 2, 3, 4, 5, 6] as $ci) {
            $forms = \App\Models\Project::getFormNumbersForClassification($ci, $project->project_type);
            if (in_array($formNumber, $forms, true)) {
                $classificationInts[] = $ci;
            }
        }

        if (empty($classificationInts)) {
            return HppItem::whereRaw('1=0')->get();
        }

        $query = HppItem::whereHas('hpp', function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })
            ->where(function ($query) use ($classificationInts) {
                $query->where(function ($q) use ($classificationInts) {
                    $q->where('item_type', \App\Models\Worker::class)
                        ->whereHasMorph('item', [\App\Models\Worker::class], function ($sub) use ($classificationInts) {
                            $sub->whereIn('classification_tkdn', $classificationInts);
                        });
                })
                    ->orWhere(function ($q) use ($classificationInts) {
                        $q->where('item_type', \App\Models\Material::class)
                            ->whereHasMorph('item', [\App\Models\Material::class], function ($sub) use ($classificationInts) {
                                $sub->whereIn('classification_tkdn', $classificationInts);
                            });
                    })
                    ->orWhere(function ($q) use ($classificationInts) {
                        $q->where('item_type', \App\Models\Equipment::class)
                            ->whereHasMorph('item', [\App\Models\Equipment::class], function ($sub) use ($classificationInts) {
                                $sub->whereIn('classification_tkdn', $classificationInts);
                            });
                    })
                    ->orWhere(function ($q) use ($classificationInts) {
                        $q->where('item_type', \App\Models\JournalWorker::class)
                            ->whereHasMorph('item', [\App\Models\JournalWorker::class], function ($sub) use ($classificationInts) {
                                $sub->whereIn('classification_tkdn', $classificationInts);
                            });
                    });
            });

        if ($hppId) {
            $query->where('hpp_id', $hppId);
        }

        $hppItems = $query->with(['hpp', 'item'])->get();

        Log::info('HPP items query result (derived)', [
            'project_id' => $projectId,
            'form_number' => $formNumber,
            'hpp_items_count' => $hppItems->count(),
        ]);

        return $hppItems;
    }

    /**
     * Get classifications that should generate a specific form number based on project type
     */
    private function getClassificationsForFormNumber(string $formNumber, string $projectType): array
    {
        $classifications = [];

        // Check all master data models for classifications that generate this form number
        // Menggunakan integer classification sesuai dengan StringHelper mapping
        $allClassifications = [1, 2, 3, 4, 5, 6]; // Semua classification integer

        foreach ($allClassifications as $classificationInt) {
            $formNumbers = \App\Models\Project::getFormNumbersForClassification($classificationInt, $projectType);
            if (in_array($formNumber, $formNumbers)) {
                $classifications[] = \App\Helpers\StringHelper::intToClassificationTkdn($classificationInt);
            }
        }

        return $classifications;
    }

    private function generateServiceItemsFromHpp(Service $service, \Illuminate\Database\Eloquent\Collection $hppItems, string $formNumber)
    {
        // Hapus service items yang lama untuk form ini
        $service->items()->where('tkdn_classification', $formNumber)->delete();

        // Jika tidak ada HPP items, buat placeholder item untuk form 3.4 dan 3.5
        if ($hppItems->isEmpty()) {
            Log::info('No HPP items found, creating placeholder', [
                'service_id' => $service->id,
                'form_number' => $formNumber,
            ]);

            if ($formNumber === '3.4') {
                $this->createPlaceholderServiceItems($service, $formNumber, 'Jasa Konsultasi dan Pengawasan');
            } elseif ($formNumber === '3.5') {
                $this->createPlaceholderServiceItems($service, $formNumber, 'Rangkuman TKDN Jasa');
            } else {
                // Untuk form lain yang tidak memiliki HPP items, buat placeholder juga
                Log::info('Creating placeholder for form without HPP items', [
                    'form_number' => $formNumber,
                ]);
                $this->createPlaceholderServiceItems($service, $formNumber, 'Form TKDN ' . $formNumber);
            }

            return;
        }

        Log::info('Processing HPP items for form', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'hpp_items_count' => $hppItems->count(),
            'hpp_items_details' => $hppItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'volume' => $item->volume,
                    'duration' => $item->duration,
                    'total_price' => $item->total_price,
                    'estimation_item_id' => $item->estimation_item_id,
                ];
            })->toArray(),
        ]);

        foreach ($hppItems as $index => $hppItem) {
            // Hitung costs berdasarkan TKDN percentage
            $wage = $hppItem->total_price ?? 0;
            $tkdnPercentage = $this->calculateTkdnPercentageForForm($formNumber);
            $quantity = $hppItem->volume ?? 1;
            $duration = $hppItem->duration ?? 1;

            $totalCost = $wage * $quantity * $duration;
            $domesticCost = ($totalCost * $tkdnPercentage) / 100;
            $foreignCost = $totalCost - $domesticCost;

            // Log data processing untuk debugging
            Log::info('Processing HPP item', [
                'hpp_item_id' => $hppItem->id,
                'form_number' => $formNumber,
                'wage' => $wage,
                'quantity' => $quantity,
                'duration' => $duration,
                'total_cost' => $totalCost,
                'tkdn_percentage' => $tkdnPercentage,
                'domestic_cost' => $domesticCost,
                'foreign_cost' => $foreignCost,
            ]);

            // Buat service item baru berdasarkan data HPP
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => $formNumber,
                'item_number' => $index + 1,
                'description' => $hppItem->description ?? 'Item ' . ($index + 1),
                'qualification' => $this->getQualificationFromHppItem($hppItem),
                'nationality' => 'WNI', // Default WNI, bisa diubah sesuai kebutuhan
                'tkdn_percentage' => $tkdnPercentage,
                'quantity' => $quantity,
                'duration' => $duration,
                'duration_unit' => $hppItem->duration_unit ?? 'ls',
                'wage' => $wage,
                'domestic_cost' => $domesticCost,
                'foreign_cost' => $foreignCost,
                'total_cost' => $totalCost,
            ]);
        }

        // Recalculate totals
        $service->calculateTotals();
    }

    /**
     * Ambil data HPP dan return dalam bentuk referensi untuk ServiceItem
     */
    private function getReferenceDataFromHpp(Hpp $hpp)
    {
        // Pastikan HPP memiliki relasi items dan master data
        $hpp->load(['items.item']);

        $referenceItems = collect();

        foreach ($hpp->items as $hppItem) {
            $masterItem = $hppItem->item;

            $referenceItems->push([
                'item_type'           => $hppItem->item_type,
                'item_id'             => $hppItem->item_id,
                'description'         => $hppItem->description,
                'unit_price'          => $hppItem->unit_price,
                'volume'              => $hppItem->volume,
                'unit'                => $hppItem->unit,
                'duration'            => $hppItem->duration,
                'duration_unit'       => $hppItem->duration_unit,
                'total_price'         => $hppItem->total_price,
                'tkdn_classification' => $masterItem->classification_tkdn ?? null,
            ]);
        }

        return $referenceItems;
    }

    /**
     * Ambil harga satuan default berdasarkan tipe item
     */
    private function getDefaultUnitPriceForForm($item)
    {
        if (! $item) {
            return 0;
        }

        // Jika item punya field unit_price langsung, pakai itu
        if (isset($item->unit_price)) {
            return (float) $item->unit_price;
        }

        // Cek berdasarkan tipe master data
        $type = class_basename($item);

        switch (strtolower($type)) {
            case 'worker':
                return (float) ($item->default_rate ?? $item->salary ?? 0);
            case 'material':
                return (float) ($item->price ?? $item->unit_price ?? 0);
            case 'equipment':
                return (float) ($item->rental_price ?? $item->unit_price ?? 0);
            case 'ahs':
                // Jika AHS, ambil rata-rata atau total unit_price-nya
                return (float) $item->items->avg('unit_price') ?? 0;
            default:
                return 0;
        }
    }

    /**
     * Create placeholder service items for forms that don't have HPP data
     */
    private function createPlaceholderServiceItems(Service $service, string $formNumber, string $formTitle)
    {
        Log::info('Creating placeholder service items', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'form_title' => $formTitle,
        ]);

        // Tentukan qualification berdasarkan form number
        switch ($formNumber) {
            case '3.1':
                $qualification = 'Manajer Proyek';
                break;
            case '3.2':
                $qualification = 'Operator Alat';
                break;
            case '3.3':
                $qualification = 'Pekerja Konstruksi';
                break;
            case '3.4':
                $qualification = 'Konsultan';
                break;
            case '3.5':
                $qualification = 'Staff';
                break;
            default:
                $qualification = 'Konsultan';
                break;
        }

        // Tentukan quantity berdasarkan form number
        $quantity = 1; // Default quantity

        switch ($formNumber) {
            case '3.1':
                $duration = 12; // 12 bulan
                break;
            case '3.2':
                $duration = 6;  // 6 bulan
                break;
            case '3.3':
                $duration = 8;  // 8 bulan
                break;
            case '3.4':
                $duration = 12; // 12 bulan
                break;
            case '3.5':
                $duration = 1;  // 1 bulan (rangkuman)
                break;
            default:
                $duration = 1;
                break;
        }

        switch ($formNumber) {
            case '3.1':
            case '3.2':
            case '3.3':
            case '3.4':
                $durationUnit = 'Bulan';
                break;
            case '3.5':
                $durationUnit = 'ls';
                break;
            default:
                $durationUnit = 'ls';
                break;
        }

        // Ambil data referensi dari HPP jika tersedia
        $referenceData = $this->getReferenceDataFromHpp($service->hpp, $formNumber);

        // Gunakan data dari HPP jika tersedia, fallback ke default
        $quantity = $referenceData['quantity'] ?? $quantity;
        $duration = $referenceData['duration'] ?? $duration;
        $durationUnit = $referenceData['duration_unit'] ?? $durationUnit;
        $unitPrice = $referenceData['unit_price'] ?? $this->getDefaultUnitPriceForForm($formNumber);
        $wage = $unitPrice;

        // Hitung costs berdasarkan TKDN percentage
        $tkdnPercentage = $this->calculateTkdnPercentageForForm($formNumber);
        $totalCost = $unitPrice * $quantity * $duration;
        $domesticCost = ($totalCost * $tkdnPercentage) / 100;
        $foreignCost = $totalCost - $domesticCost;

        Log::info('Placeholder service item details (with HPP reference)', [
            'form_number' => $formNumber,
            'qualification' => $qualification,
            'quantity' => $quantity,
            'duration' => $duration,
            'duration_unit' => $durationUnit,
            'unit_price' => $unitPrice,
            'total_cost' => $totalCost,
            'tkdn_percentage' => $tkdnPercentage,
            'domestic_cost' => $domesticCost,
            'foreign_cost' => $foreignCost,
            'reference_data' => $referenceData,
        ]);

        // Buat placeholder item untuk form yang tidak memiliki data HPP
        ServiceItem::create([
            'service_id' => $service->id,
            'tkdn_classification' => $formNumber,
            'item_number' => 1,
            'description' => $formTitle,
            'qualification' => $qualification,
            'nationality' => 'WNI',
            'tkdn_percentage' => $tkdnPercentage,
            'quantity' => $quantity,
            'duration' => $duration,
            'duration_unit' => $durationUnit,
            'wage' => $wage,
            'domestic_cost' => $domesticCost,
            'foreign_cost' => $foreignCost,
            'total_cost' => $totalCost,
        ]);

        // Recalculate totals
        $service->calculateTotals();

        Log::info('Placeholder service item created successfully', [
            'service_id' => $service->id,
            'form_number' => $formNumber,
            'service_items_count' => $service->items()->where('tkdn_classification', $formNumber)->count(),
        ]);
    }

    /**
     * Create Form 3.5 sebagai rangkuman dari form 3.1, 3.2, 3.3, dan 3.4
     */
    private function createTkdnForm35Summary(Service $service)
    {
        Log::info('Creating Form 3.5 summary from other forms', [
            'service_id' => $service->id,
        ]);

        // Hapus service items lama untuk form 3.5 jika ada
        $service->items()->where('tkdn_classification', '3.5')->delete();

        // Ambil data dari form 3.1, 3.2, 3.3, dan 3.4
        $form31Items = $service->items()->where('tkdn_classification', '3.1')->get();
        $form32Items = $service->items()->where('tkdn_classification', '3.2')->get();
        $form33Items = $service->items()->where('tkdn_classification', '3.3')->get();
        $form34Items = $service->items()->where('tkdn_classification', '3.4')->get();

        // Hitung total untuk setiap form
        $form31Total = $form31Items->sum('total_cost');
        $form31Domestic = $form31Items->sum('domestic_cost');
        $form31Foreign = $form31Items->sum('foreign_cost');

        $form32Total = $form32Items->sum('total_cost');
        $form32Domestic = $form32Items->sum('domestic_cost');
        $form32Foreign = $form32Items->sum('foreign_cost');

        $form33Total = $form33Items->sum('total_cost');
        $form33Domestic = $form33Items->sum('domestic_cost');
        $form33Foreign = $form33Items->sum('foreign_cost');

        $form34Total = $form34Items->sum('total_cost');
        $form34Domestic = $form34Items->sum('domestic_cost');
        $form34Foreign = $form34Items->sum('foreign_cost');

        // Hitung total keseluruhan
        $totalDomestic = $form31Domestic + $form32Domestic + $form33Domestic + $form34Domestic;
        $totalForeign = $form31Foreign + $form32Foreign + $form33Foreign + $form34Foreign;
        $totalCost = $totalDomestic + $totalForeign;

        // Hitung TKDN percentage keseluruhan
        $overallTkdnPercentage = $totalCost > 0 ? ($totalDomestic / $totalCost) * 100 : 0;

        Log::info('Form 3.5 summary calculations', [
            'service_id' => $service->id,
            'form31_total' => $form31Total,
            'form31_domestic' => $form31Domestic,
            'form31_foreign' => $form31Foreign,
            'form32_total' => $form32Total,
            'form32_domestic' => $form32Domestic,
            'form32_foreign' => $form32Foreign,
            'form33_total' => $form33Total,
            'form33_domestic' => $form33Domestic,
            'form33_foreign' => $form33Foreign,
            'form34_total' => $form34Total,
            'form34_domestic' => $form34Domestic,
            'form34_foreign' => $form34Foreign,
            'total_domestic' => $totalDomestic,
            'total_foreign' => $totalForeign,
            'total_cost' => $totalCost,
            'overall_tkdn_percentage' => $overallTkdnPercentage,
        ]);

        // Buat service items untuk Form 3.5 berdasarkan rangkuman
        $itemNumber = 1;

        // I. Manajemen Proyek dan Perekayasaan
        if ($form31Total > 0) {
            $form31TkdnPercentage = $totalCost > 0 ? ($form31Domestic / $totalCost) * 100 : 0;
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => '3.5',
                'item_number' => $itemNumber++,
                'description' => 'I. Manajemen Proyek dan Perekayasaan',
                'qualification' => 'Rangkuman',
                'nationality' => 'WNI',
                'tkdn_percentage' => $form31TkdnPercentage,
                'quantity' => 1,
                'duration' => 1,
                'duration_unit' => 'ls',
                'wage' => $form31Total,
                'domestic_cost' => $form31Domestic,
                'foreign_cost' => $form31Foreign,
                'total_cost' => $form31Total,
            ]);
        }

        // II. Alat Kerja/Fasilitas Kerjal
        if ($form32Total > 0) {
            $form32TkdnPercentage = $totalCost > 0 ? ($form32Domestic / $totalCost) * 100 : 0;
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => '3.5',
                'item_number' => $itemNumber++,
                'description' => 'II. Alat Kerja/Fasilitas Kerja',
                'qualification' => 'Rangkuman',
                'nationality' => 'WNI',
                'tkdn_percentage' => $form32TkdnPercentage,
                'quantity' => 1,
                'duration' => 1,
                'duration_unit' => 'ls',
                'wage' => $form32Total,
                'domestic_cost' => $form32Domestic,
                'foreign_cost' => $form32Foreign,
                'total_cost' => $form32Total,
            ]);
        }

        // III. Konstruksi dan Fabrikasi
        if ($form33Total > 0) {
            $form33TkdnPercentage = $totalCost > 0 ? ($form33Domestic / $totalCost) * 100 : 0;
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => '3.5',
                'item_number' => $itemNumber++,
                'description' => 'III. Konstruksi dan Fabrikasi',
                'qualification' => 'Rangkuman',
                'nationality' => 'WNI',
                'tkdn_percentage' => $form33TkdnPercentage,
                'quantity' => 1,
                'duration' => 1,
                'duration_unit' => 'ls',
                'wage' => $form33Total,
                'domestic_cost' => $form33Domestic,
                'foreign_cost' => $form33Foreign,
                'total_cost' => $form33Total,
            ]);
        }

        // IV. Jasa Umum
        if ($form34Total > 0) {
            $form34TkdnPercentage = $totalCost > 0 ? ($form34Domestic / $totalCost) * 100 : 0;
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => '3.5',
                'item_number' => $itemNumber++,
                'description' => 'IV. Jasa Umum',
                'qualification' => 'Rangkuman',
                'nationality' => 'WNI',
                'tkdn_percentage' => $form34TkdnPercentage,
                'quantity' => 1,
                'duration' => 1,
                'duration_unit' => 'ls',
                'wage' => $form34Total,
                'domestic_cost' => $form34Domestic,
                'foreign_cost' => $form34Foreign,
                'total_cost' => $form34Total,
            ]);
        }

        // Total Jasa
        if ($totalCost > 0) {
            ServiceItem::create([
                'service_id' => $service->id,
                'tkdn_classification' => '3.5',
                'item_number' => $itemNumber++,
                'description' => 'Total Jasa',
                'qualification' => 'Total',
                'nationality' => 'WNI',
                'tkdn_percentage' => $overallTkdnPercentage,
                'quantity' => 1,
                'duration' => 1,
                'duration_unit' => 'ls',
                'wage' => $totalCost,
                'domestic_cost' => $totalDomestic,
                'foreign_cost' => $totalForeign,
                'total_cost' => $totalCost,
            ]);
        }

        // Recalculate totals
        $service->calculateTotals();

        Log::info('Form 3.5 summary created successfully', [
            'service_id' => $service->id,
            'service_items_count' => $service->items()->where('tkdn_classification', '3.5')->count(),
            'total_domestic' => $totalDomestic,
            'total_foreign' => $totalForeign,
            'total_cost' => $totalCost,
            'overall_tkdn_percentage' => $overallTkdnPercentage,
        ]);
    }

    /**
     * Calculate TKDN percentage based on form number
     */
    private function calculateTkdnPercentageForForm(string $formNumber): float
    {
        switch ($formNumber) {
            case '3.1':
                return 100.0; // Jasa Manajemen - 100% TKDN
            case '3.2':
                return 0.0;   // Jasa Alat Kerja - 0% TKDN
            case '3.3':
                return 0.0;   // Jasa Konstruksi - 0% TKDN
            case '3.4':
                return 100.0; // Jasa Konsultasi - 100% TKDN (WNI)
            case '3.5':
                return 100.0; // Rangkuman - 100% TKDN
            case '4.1':
                return 100.0; // Jasa Teknik dan Rekayasa - 100% TKDN (WNI)
            case '4.2':
                return 80.0;  // Jasa Pengadaan dan Logistik - 80% TKDN
            case '4.3':
                return 100.0; // Jasa Operasi dan Pemeliharaan - 100% TKDN (WNI)
            case '4.4':
                return 100.0; // Jasa Pelatihan dan Sertifikasi - 100% TKDN (WNI)
            case '4.5':
                return 70.0;  // Jasa Teknologi Informasi - 70% TKDN
            case '4.6':
                return 100.0; // Jasa Lingkungan dan Keamanan - 100% TKDN (WNI)
            case '4.7':
                return 60.0;  // Jasa Lainnya - 60% TKDN
            default:
                return 50.0;
        }
    }

    /**
     * Get qualification from HPP item based on master data directly
     */
    private function getQualificationFromHppItem($hppItem): ?string
    {
        // Ambil qualification langsung dari master data melalui relasi morphTo
        $relatedItem = $hppItem->item; // morphTo: Worker / Material / Equipment / JournalWorker

        if ($relatedItem) {
            switch (get_class($relatedItem)) {
                case \App\Models\Worker::class:
                    return $relatedItem->kualifikasi ?? $relatedItem->name ?? 'Pekerja';
                case \App\Models\Material::class:
                    return $relatedItem->specification ?? $relatedItem->name ?? 'Material';
                case \App\Models\Equipment::class:
                    return $relatedItem->type ?? $relatedItem->name ?? 'Equipment';
                case \App\Models\JournalWorker::class:
                    return $relatedItem->spesifikasi_or_kualifikasi ?? $relatedItem->nama_pekerjaan ?? 'Pekerja';
                default:
                    return 'Umum';
            }
        }

        // Default qualification berdasarkan form type
        switch ($hppItem->tkdn_classification) {
            case '3.1':
                return 'Manajer Proyek';
            case '3.2':
                return 'Operator Alat';
            case '3.3':
                return 'Pekerja Konstruksi';
            case '3.4':
                return 'Konsultan';
            case '3.5':
                return 'Staff';
            default:
                return 'Pekerja';
        }
    }

    /**
     * Regenerate form 3.4 secara spesifik
     */
    public function regenerateForm34(Service $service)
    {
        try {
            Log::info('Regenerating Form 3.4 for service', [
                'service_id' => $service->id,
                'project_id' => $service->project_id,
            ]);

            // Hapus service items yang lama untuk form 3.4
            $oldItems = $service->items()->where('tkdn_classification', '3.4')->delete();

            Log::info('Deleted old Form 3.4 items', [
                'service_id' => $service->id,
                'deleted_count' => $oldItems,
            ]);

            // Ambil HPP items untuk form 3.4
            $hppItems = $this->getHppItemsByTkdnClassification($service->project_id, '3.4');

            Log::info('HPP items for Form 3.4', [
                'service_id' => $service->id,
                'hpp_items_count' => $hppItems->count(),
                'hpp_items_ids' => $hppItems->pluck('id')->toArray(),
            ]);

            if ($hppItems->isNotEmpty()) {
                // Generate service items dari HPP items
                $this->generateServiceItemsFromHpp($service, $hppItems, '3.4');

                Log::info('Form 3.4 regenerated from HPP items', [
                    'service_id' => $service->id,
                    'service_items_count' => $service->items()->where('tkdn_classification', '3.4')->count(),
                ]);
            } else {
                // Buat placeholder items
                $this->createPlaceholderServiceItems($service, '3.4', 'Jasa Konsultasi dan Pengawasan');

                Log::info('Form 3.4 regenerated with placeholder items', [
                    'service_id' => $service->id,
                    'service_items_count' => $service->items()->where('tkdn_classification', '3.4')->count(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Form 3.4 berhasil di-regenerate',
                'data' => [
                    'service_id' => $service->id,
                    'form_3_4_items_count' => $service->items()->where('tkdn_classification', '3.4')->count(),
                    'hpp_items_count' => $hppItems->count(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error regenerating Form 3.4', [
                'service_id' => $service->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat regenerate Form 3.4: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Debug method untuk memeriksa data HPP items
     */
    public function debugHppItems(Service $service)
    {
        try {
            $projectId = $service->project_id;

            // Ambil semua HPP items untuk project ini
            $allHppItems = HppItem::whereHas('hpp', function ($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })->with(['hpp', 'item'])->get(); // Langsung load relasi morphTo

            // Group by classification dari master data
            $groupedItems = $allHppItems->groupBy(function ($item) {
                $relatedItem = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker
                return $relatedItem && property_exists($relatedItem, 'classification_tkdn')
                    ? $relatedItem->classification_tkdn
                    : 'unknown';
            });

            // Log untuk debugging
            Log::info('Debug HPP Items for Service', [
                'service_id' => $service->id,
                'project_id' => $projectId,
                'total_hpp_items' => $allHppItems->count(),
                'grouped_by_classification' => $groupedItems->map(function ($items) {
                    return [
                        'count' => $items->count(),
                        'items' => $items->map(function ($item) {
                            $relatedItem = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker
                            return [
                                'id' => $item->id,
                                'description' => $item->description,
                                'volume' => $item->volume,
                                'duration' => $item->duration,
                                'total_price' => $item->total_price,
                                'estimation_item_id' => $item->estimation_item_id,
                                'item_type' => $item->item_type,
                                'item_id' => $item->item_id,
                                'master_data' => $relatedItem ? [
                                    'class' => get_class($relatedItem),
                                    'name' => $relatedItem->name ?? $relatedItem->nama_pekerjaan ?? 'N/A',
                                    'classification_tkdn' => $relatedItem->classification_tkdn ?? null,
                                ] : null,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'service_id' => $service->id,
                    'project_id' => $projectId,
                    'total_hpp_items' => $allHppItems->count(),
                    'grouped_by_classification' => $groupedItems->map(function ($items, $classification) {
                        return [
                            'classification' => $classification,
                            'count' => $items->count(),
                            'items' => $items->map(function ($item) {
                                $relatedItem = $item->item; // morphTo: Worker / Material / Equipment / JournalWorker
                                return [
                                    'id' => $item->id,
                                    'description' => $item->description,
                                    'volume' => $item->volume,
                                    'duration' => $item->duration,
                                    'total_price' => $item->total_price,
                                    'estimation_item_id' => $item->estimation_item_id,
                                    'item_type' => $item->item_type,
                                    'item_id' => $item->item_id,
                                    'master_data' => $relatedItem ? [
                                        'class' => get_class($relatedItem),
                                        'name' => $relatedItem->name ?? $relatedItem->nama_pekerjaan ?? 'N/A',
                                        'classification_tkdn' => $relatedItem->classification_tkdn ?? null,
                                    ] : null,
                                ];
                            })->toArray(),
                        ];
                    })->values()->toArray(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error in debugHppItems', [
                'service_id' => $service->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat debug: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export service data to Excel based on TKDN classification
     */
    // public function exportExcel(Service $service, string $classification)
    // {
    //     try {
    //         // Validate classification
    //         $validClassifications = ['3.1', '3.2', '3.3', '3.4', '3.5', 'all'];
    //         if (! in_array($classification, $validClassifications)) {
    //             return back()->with('error', 'Klasifikasi TKDN tidak valid.');
    //         }

    //         // Check if service has been generated
    //         if ($service->status !== 'generated' && $service->status !== 'approved') {
    //             return back()->with('error', 'Service harus sudah di-generate atau approved untuk dapat di-export.');
    //         }

    //         // Use the export service
    //         $exportService = new \App\Services\ServiceExportService($service, $classification);
    //         $filepath = $exportService->export();

    //         // Get filename from path
    //         $filename = basename($filepath);

    //         // Verify file exists and is readable
    //         if (! file_exists($filepath)) {
    //             throw new \Exception('File Excel tidak ditemukan setelah dibuat.');
    //         }

    //         if (! is_readable($filepath)) {
    //             throw new \Exception('File Excel tidak dapat dibaca.');
    //         }

    //         // Check file size
    //         $fileSize = filesize($filepath);
    //         if ($fileSize === 0) {
    //             throw new \Exception('File Excel kosong (0 bytes).');
    //         }

    //         if ($fileSize < 1000) {
    //             throw new \Exception('File Excel terlalu kecil, kemungkinan rusak.');
    //         }

    //         // Verify file extension
    //         $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
    //         if ($fileExtension !== 'xlsx') {
    //             throw new \Exception('File yang dihasilkan bukan file Excel (.xlsx): '.$fileExtension);
    //         }

    //         // Verify file content (basic Excel file signature check)
    //         $fileContent = file_get_contents($filepath, false, null, 0, 4);
    //         if ($fileContent !== 'PK'.chr(0x03).chr(0x04)) {
    //             throw new \Exception('File Excel tidak memiliki signature yang valid');
    //         }

    //         // Return file download response
    //         return response()->download($filepath, $filename, [
    //             'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //             'Content-Disposition' => 'attachment; filename="'.$filename.'"',
    //             'Cache-Control' => 'no-cache, must-revalidate',
    //             'Pragma' => 'no-cache',
    //             'Expires' => '0',
    //         ])->deleteFileAfterSend(true);

    //     } catch (\Exception $e) {
    //         // Log the error for debugging
    //         Log::error('Excel export failed', [
    //             'service_id' => $service->id,
    //             'classification' => $classification,
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString(),
    //         ]);

    //         return back()->with('error', 'Terjadi kesalahan saat export Excel: '.$e->getMessage());
    //     }
    // }
    public function exportExcel(Service $service, string $classification)
    {
        try {
            // Validate classification
            $validClassifications = ['3.1', '3.2', '3.3', '3.4', '3.5', 'all'];
            if (! in_array($classification, $validClassifications)) {
                return back()->with('error', 'Klasifikasi TKDN tidak valid.');
            }

            // Check if service has been generated
            if ($service->status !== 'generated' && $service->status !== 'approved') {
                return back()->with('error', 'Service harus sudah di-generate atau approved untuk dapat di-export.');
            }

            // Use the export service
            $exportService = new \App\Services\ServiceExportService($service, $classification);
            $filepath = $exportService->export();

            // Get filename from path
            $filename = basename($filepath);

            // Verify file exists and is readable
            if (! file_exists($filepath)) {
                throw new \Exception('File Excel tidak ditemukan setelah dibuat.');
            }

            if (! is_readable($filepath)) {
                throw new \Exception('File Excel tidak dapat dibaca.');
            }

            // Check file size
            $fileSize = filesize($filepath);
            if ($fileSize === 0) {
                throw new \Exception('File Excel kosong (0 bytes).');
            }

            if ($fileSize < 1000) {
                throw new \Exception('File Excel terlalu kecil, kemungkinan rusak.');
            }

            // Verify file extension
            $fileExtension = pathinfo($filepath, PATHINFO_EXTENSION);
            if ($fileExtension !== 'xlsx') {
                throw new \Exception('File yang dihasilkan bukan file Excel (.xlsx): ' . $fileExtension);
            }

            // Verify file content (basic Excel file signature check)
            $fileContent = file_get_contents($filepath, false, null, 0, 4);
            if ($fileContent !== 'PK' . chr(0x03) . chr(0x04)) {
                throw new \Exception('File Excel tidak memiliki signature yang valid');
            }

            // Return file download response
            return response()->download($filepath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Excel export failed', [
                'service_id' => $service->id,
                'classification' => $classification,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Get HPP items filtered by project type
     */
    private function getHppItemsByProjectType(string $projectId, string $projectType): \Illuminate\Database\Eloquent\Collection
    {
        $project = Project::find($projectId);
        if (! $project) {
            Log::warning('Project not found for HPP items query', ['project_id' => $projectId]);

            return collect();
        }

        $hppItems = HppItem::whereHas('hpp', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->where(function ($query) use ($projectType) {
                // Filter berdasarkan classification_tkdn dari master data
                $classifications = $projectType === 'tkdn_jasa'
                    ? [1, 2, 3, 4]
                    : [1, 2, 3, 4, 5, 6];

                $query->where(function ($q) use ($classifications) {
                    $q->where('item_type', \App\Models\Worker::class)
                        ->whereHasMorph('item', [\App\Models\Worker::class], function ($workerQuery) use ($classifications) {
                            $workerQuery->whereIn('classification_tkdn', $classifications);
                        });
                })
                    ->orWhere(function ($q) use ($classifications) {
                        $q->where('item_type', \App\Models\Material::class)
                            ->whereHasMorph('item', [\App\Models\Material::class], function ($materialQuery) use ($classifications) {
                                $materialQuery->whereIn('classification_tkdn', $classifications);
                            });
                    })
                    ->orWhere(function ($q) use ($classifications) {
                        $q->where('item_type', \App\Models\Equipment::class)
                            ->whereHasMorph('item', [\App\Models\Equipment::class], function ($equipmentQuery) use ($classifications) {
                                $equipmentQuery->whereIn('classification_tkdn', $classifications);
                            });
                    })
                    ->orWhere(function ($q) use ($classifications) {
                        $q->where('item_type', \App\Models\JournalWorker::class)
                            ->whereHasMorph('item', [\App\Models\JournalWorker::class], function ($journalQuery) use ($classifications) {
                                $journalQuery->whereIn('classification_tkdn', $classifications);
                            });
                    });
            })
            ->with(['hpp', 'item']) // Langsung load relasi morphTo
            ->get();

        return $hppItems;
    }

    /**
     * Add comment to service
     */
    public function addComment(Request $request, Service $service)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $approvalService = new ServiceApprovalService();
            $approvalService->addComment($service, $request->comment);

            return redirect()->route('service.show', $service->id)
                ->with('success', 'Komentar berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan komentar: ' . $e->getMessage());
        }
    }

    public function dataservice()
    {
        $services = Service::all();

        return view('dataservice.main', compact('services'));
    }

    public function detailservice($id)
    {
        $service = Service::find($id);
        $serviceItems = ServiceItem::where('service_id', $id)
            ->orderBy('tkdn_classification')
            ->get();

        // Hilangkan "DOC-" dari document_number
        $hppCode = Str::replaceFirst('DOC-', '', $service->document_number);

        // Ambil data HPP berdasarkan code dengan relasi items dan master data
        $hpp = Hpp::where('code', $hppCode)->with(['items.item'])->first();
        $hppahs = HppAhs::select('volume')->where('hpp_id', $hpp->id)->first();
        // dd($hpp->items->count());
        // Grouping otomatis berdasarkan tkdn_classification (3.1 - 3.5, 4.1 - 4.7)
        $groupedItems = $serviceItems->groupBy('tkdn_classification');

        return view('dataservice.about', compact('serviceItems', 'groupedItems', 'hppahs'));
    }


    public function formservice()
    {
        return view('Service Tab.mainpage');
    }
}
