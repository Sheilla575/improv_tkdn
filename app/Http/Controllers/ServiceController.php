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
                    // Filter items berdasarkan project_type melalui master data menggunakan integer classification
                    if ($project->project_type === 'tkdn_jasa') {
                        $query->whereHas('estimationItem', function ($estimationQuery) {
                            $estimationQuery->where(function ($q) {
                                $q->whereHas('worker', function ($workerQuery) {
                                    $workerQuery->whereIn('classification_tkdn', [1, 2, 3, 4]); // Overhead & Manajemen, Alat Kerja / Fasilitas, Konstruksi & Fabrikasi, Peralatan (Jasa Umum)
                                })->orWhereHas('material', function ($materialQuery) {
                                    $materialQuery->whereIn('classification_tkdn', [1, 2, 3, 4]);
                                })->orWhereHas('equipment', function ($equipmentQuery) {
                                    $equipmentQuery->whereIn('classification_tkdn', [1, 2, 3, 4]);
                                });
                            });
                        });
                    } elseif ($project->project_type === 'tkdn_barang_jasa') {
                        $query->whereHas('estimationItem', function ($estimationQuery) {
                            $estimationQuery->where(function ($q) {
                                $q->whereHas('worker', function ($workerQuery) {
                                    $workerQuery->whereIn('classification_tkdn', [1, 2, 3, 4, 5, 6]); // Semua classification termasuk Material (Bahan Baku) dan Peralatan (Barang Jadi)
                                })->orWhereHas('material', function ($materialQuery) {
                                    $materialQuery->whereIn('classification_tkdn', [1, 2, 3, 4, 5, 6]);
                                })->orWhereHas('equipment', function ($equipmentQuery) {
                                    $equipmentQuery->whereIn('classification_tkdn', [1, 2, 3, 4, 5, 6]);
                                });
                            });
                        });
                    }
                    $query->orderBy('id');
                }, 'project', 'items.estimationItem'])
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

                    $hppData[] = [
                        'id' => $hpp->id,
                        'code' => $hpp->code ?? 'N/A',
                        'name_hpp' => $hpp->name_hpp ?? 'N/A',
                        'total_cost' => $hpp->grand_total ?? 0,
                        'items_count' => $hpp->items ? $hpp->items->filter(function ($item) use ($hpp) {
                            $classificationInt = $item->estimationItem->classification_tkdn ?? null;
                            if (! $classificationInt) {
                                return false;
                            }

                            if ($hpp->project->project_type === 'tkdn_jasa') {
                                $formNumbers = \App\Models\Material::getFormNumbersForClassification($classificationInt, 'tkdn_jasa');

                                return ! empty($formNumbers);
                            } elseif ($hpp->project->project_type === 'tkdn_barang_jasa') {
                                $formNumbers = \App\Models\Material::getFormNumbersForClassification($classificationInt, 'tkdn_barang_jasa');

                                return ! empty($formNumbers);
                            }

                            return true;
                        })->count() : 0,
                        'project_name' => $hpp->project ? $hpp->project->name : 'N/A',
                        'project_code' => $hpp->project ? $hpp->project->code : 'N/A',
                        'project_type' => $hpp->project ? $hpp->project->project_type : 'tkdn_jasa',
                        'tkdn_breakdown' => $hpp->items ? $hpp->items->groupBy(function ($item) {
                            // Get classification from master data through estimation item and convert to string
                            $classificationInt = $item->estimationItem->classification_tkdn ?? null;

                            return $classificationInt ? \App\Helpers\StringHelper::intToClassificationTkdn($classificationInt) : 'Unknown';
                        })->map(function ($items, $classification) {
                            return [
                                'classification' => $classification,
                                'count' => $items->count(),
                                'total_cost' => $items->sum('total_price'),
                            ];
                        })->filter(function ($data, $classification) use ($hpp) {
                            // Skip 'Unknown' classifications
                            if ($classification === 'Unknown') {
                                return false;
                            }

                            // Convert string classification back to integer for getFormNumbersForClassification
                            $classificationInt = \App\Helpers\StringHelper::classificationTkdnToInt($classification);
                            if (! $classificationInt) {
                                return false;
                            }

                            // Filter berdasarkan project_type menggunakan mapping yang baru
                            if ($hpp->project->project_type === 'tkdn_jasa') {
                                $formNumbers = \App\Models\Material::getFormNumbersForClassification($classificationInt, 'tkdn_jasa');

                                return ! empty($formNumbers);
                            } elseif ($hpp->project->project_type === 'tkdn_barang_jasa') {
                                $formNumbers = \App\Models\Material::getFormNumbersForClassification($classificationInt, 'tkdn_barang_jasa');

                                return ! empty($formNumbers);
                            }

                            return true;
                        }) : [],
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

        // Generate form berdasarkan klasifikasi yang ada di master data
        $formsToGenerate = $service->getFormsToGenerate();
        $generatedForms = [];

        // Hapus semua service items yang ada sebelumnya untuk menghindari duplikasi
        Log::info('Cleaning up existing service items before generation', [
            'service_id' => $service->id,
            'existing_items_count' => $service->items()->count(),
        ]);
        $service->items()->delete();

        foreach ($formsToGenerate as $formCode => $formName) {
            // PERBAIKAN UTAMA: Pass hpp_id sebagai parameter ketiga untuk filter HPP items spesifik
            $hppItems = $this->getHppItemsByTkdnClassification($hpp->project_id, $formCode, $hpp->id);
            $hppItemsCount = $hppItems->count();

            if ($hppItemsCount > 0) {
                Log::info("Generating Form {$formCode} - {$formName} from HPP (found {$hppItemsCount} items)", [
                    'hpp_id' => $hpp->id,
                    'form_code' => $formCode,
                    'items_count' => $hppItemsCount,
                    'unique_hpp_ids' => $hppItems->pluck('hpp_id')->unique()->toArray(), // Verifikasi hanya 1 HPP
                ]);

                // PERBAIKAN: Pastikan tidak ada duplikasi dengan cek existing items
                $existingItemsForForm = $service->items()->where('tkdn_classification', $formCode)->count();
                if ($existingItemsForForm > 0) {
                    Log::warning("Form {$formCode} already has {$existingItemsForForm} items, skipping generation to avoid duplication");
                    continue;
                }

                $this->createTkdnFormFromHpp($service, $hpp, $formCode, $formName);
                $generatedForms[] = $formCode;

                // Verifikasi item telah dibuat
                $createdItemsCount = $service->items()->where('tkdn_classification', $formCode)->count();
                Log::info("Form {$formCode} generation completed", [
                    'service_items_created' => $createdItemsCount,
                    'hpp_items_processed' => $hppItemsCount,
                    'hpp_id' => $hpp->id,
                ]);
            } else {
                Log::info("Skipping Form {$formCode} - {$formName} (no HPP items found for this specific HPP)", [
                    'hpp_id' => $hpp->id,
                    'project_id' => $hpp->project_id,
                ]);
            }
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
                'estimation_item_id' => $hppItem->estimation_item_id,
                'description' => $hppItem->description,
                'volume' => $hppItem->volume,
                'duration' => $hppItem->duration,
                'total_price' => $hppItem->total_price,
            ]);

            // 2. Ambil data AHS items berdasarkan HPP item (estimation_item)
            if ($hppItem->estimation_item_id) {
                $estimationItem = $hppItem->estimationItem;
                if ($estimationItem) {
                    // Ambil estimation dari estimation_item
                    $estimation = $estimationItem->estimation;
                    $ahsItems = $estimation->items;

                    Log::info('AHS items found', [
                        'estimation_item_id' => $estimationItem->id,
                        'estimation_id' => $estimation->id ?? 'N/A',
                        'ahs_items_count' => $ahsItems ? $ahsItems->count() : 0,
                    ]);

                    // 3. Insert data AHS items ke table service_items
                    // PERBAIKAN: Hanya buat 1 service item per HPP item, bukan semua AHS items
                    // untuk menghindari duplikasi yang menyebabkan looping data

                    // Tentukan TKDN percentage berdasarkan form
                    $tkdnPercentage = $this->calculateTkdnPercentageForForm($formNumber);
                    $totalCost = $hppItem->total_price ?? 0;
                    $domesticCost = $totalCost * ($tkdnPercentage / 100);
                    $foreignCost = $totalCost - $domesticCost;

                    Log::info('Creating single service item from HPP item', [
                        'hpp_item_id' => $hppItem->id,
                        'estimation_item_id' => $estimationItem->id,
                        'tkdn_percentage' => $tkdnPercentage,
                        'total_cost' => $totalCost,
                        'domestic_cost' => $domesticCost,
                        'foreign_cost' => $foreignCost,
                    ]);

                    ServiceItem::create([
                        'service_id' => $service->id,
                        'estimation_item_id' => $estimationItem->id,
                        'item_number' => $itemNumber++,
                        'tkdn_classification' => $formNumber,
                        'description' => $hppItem->description ?? 'Item ' . $itemNumber,
                        'qualification' => $this->getQualificationFromHppItem($hppItem),
                        'nationality' => 'WNI',
                        'tkdn_percentage' => $tkdnPercentage,
                        'quantity' => $hppItem->volume ?? 1,
                        'duration' => $hppItem->duration ?? 1,
                        'duration_unit' => $hppItem->duration_unit ?? 'ls',
                        'wage' => $hppItem->total_price ?? 0,
                        'domestic_cost' => $domesticCost,
                        'foreign_cost' => $foreignCost,
                        'total_cost' => $totalCost,
                    ]);
                } else {
                    // Jika tidak ada estimationItem, buat dari HPP item langsung
                    $this->createServiceItemFromHpp($service, $hppItem, $formNumber, $itemNumber++);
                }
            } else {
                // Jika tidak ada estimation_item_id, buat dari HPP item langsung  
                $this->createServiceItemFromHpp($service, $hppItem, $formNumber, $itemNumber++);
            }
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
        $tkdnPercentage = $this->calculateTkdnPercentageForForm($formNumber);
        $totalCost = $hppItem->total_price ?? 0;
        $domesticCost = $totalCost * ($tkdnPercentage / 100);
        $foreignCost = $totalCost - $domesticCost;

        Log::info('Creating service item from HPP item directly', [
            'hpp_item_id' => $hppItem->id,
            'form_number' => $formNumber,
            'item_number' => $itemNumber,
            'tkdn_percentage' => $tkdnPercentage,
            'total_cost' => $totalCost,
        ]);

        ServiceItem::create([
            'service_id' => $service->id,
            'estimation_item_id' => $hppItem->estimation_item_id ?? null,
            'item_number' => $itemNumber,
            'tkdn_classification' => $formNumber,
            'description' => $hppItem->description ?? 'Item ' . $itemNumber,
            'qualification' => $this->getQualificationFromHppItem($hppItem),
            'nationality' => 'WNI',
            'tkdn_percentage' => $tkdnPercentage,
            'quantity' => $hppItem->volume ?? 1,
            'duration' => $hppItem->duration ?? 1,
            'duration_unit' => $hppItem->duration_unit ?? 'ls',
            'wage' => $hppItem->total_price ?? 0,
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
                    // 'hpp_id' => $validated['hpp_id'],
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

        // Ambil HPP items yang sesuai dengan project_type melalui master data
        $hppItems = collect();
        if ($service->project_id) {
            // Gunakan integer classification sesuai dengan master data
            $classifications = $projectType === 'tkdn_jasa'
                ? [1, 2, 3, 4]  // Integer format untuk tkdn_jasa
                : [1, 2, 3, 4, 5, 6]; // Integer format untuk tkdn_barang_jasa

            $hppItems = \App\Models\HppItem::whereHas('hpp', function ($query) use ($service) {
                $query->where('project_id', $service->project_id);
            })
                ->whereHas('estimationItem', function ($estimationQuery) use ($classifications) {
                    $estimationQuery->where(function ($q) use ($classifications) {
                        $q->whereHas('worker', function ($workerQuery) use ($classifications) {
                            $workerQuery->whereIn('classification_tkdn', $classifications);
                        })->orWhereHas('material', function ($materialQuery) use ($classifications) {
                            $materialQuery->whereIn('classification_tkdn', $classifications);
                        })->orWhereHas('equipment', function ($equipmentQuery) use ($classifications) {
                            $equipmentQuery->whereIn('classification_tkdn', $classifications);
                        });
                    });
                })
                ->with(['hpp', 'estimation', 'estimationItem.worker', 'estimationItem.material', 'estimationItem.equipment'])
                ->get();


            // Group by classification dari master data dengan format string yang benar
            $hppItems = $hppItems->groupBy(function ($item) use ($projectType) {
                // Get classification dari master data
                if ($item->estimationItem) {
                    $classification = null;
                    if ($item->estimationItem->worker) {
                        $classification = $item->estimationItem->worker->classification_tkdn;
                    } elseif ($item->estimationItem->material) {
                        $classification = $item->estimationItem->material->classification_tkdn;
                    } elseif ($item->estimationItem->equipment) {
                        $classification = $item->estimationItem->equipment->classification_tkdn;
                    }

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
                ->with(['hpp', 'estimationItem.worker', 'estimationItem.material', 'estimationItem.equipment'])
                ->get();

            $allHppItemsFlat = $hppItemsFromId->map(function ($item) {
                return [
                    'id' => $item->id,
                    'hpp_id' => $item->hpp_id,
                    'description' => $item->description,
                    'volume' => $item->volume,
                    'duration' => $item->duration,
                    'total_price' => $item->total_price,
                    'estimation_item_id' => $item->estimation_item_id,
                    'master_classification' => [
                        'worker' => ($item->estimationItem && $item->estimationItem->worker) ? $item->estimationItem->worker->classification_tkdn : null,
                        'material' => ($item->estimationItem && $item->estimationItem->material) ? $item->estimationItem->material->classification_tkdn : null,
                        'equipment' => ($item->estimationItem && $item->estimationItem->equipment) ? $item->estimationItem->equipment->classification_tkdn : null,
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
        Log::info('Getting HPP items by form number', [
            'project_id' => $projectId,
            'form_number' => $formNumber,
            'hpp_id' => $hppId,
        ]);

        // Ambil project untuk mendapatkan project_type
        $project = Project::find($projectId);
        if (! $project) {
            Log::warning('Project not found for HPP items query', ['project_id' => $projectId]);

            return HppItem::whereRaw('1 = 0')->get(); // Return empty Eloquent Collection
        }

        // Dapatkan klasifikasi yang sesuai dengan form number dan project type
        $classifications = $this->getClassificationsForFormNumber($formNumber, $project->project_type);

        if (empty($classifications)) {
            Log::warning('No classifications found for form number', ['form_number' => $formNumber, 'project_type' => $project->project_type]);

            return HppItem::whereRaw('1 = 0')->get(); // Return empty Eloquent Collection
        }

        // Konversi string classifications ke integer untuk query
        $classificationInts = array_map(function ($classification) {
            return \App\Helpers\StringHelper::classificationTkdnToInt($classification);
        }, $classifications);
        $classificationInts = array_filter($classificationInts); // Remove null values

        // Ambil HPP items berdasarkan project_id dan filter dari master data
        $hppItemsQuery = HppItem::whereHas('hpp', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })
            ->whereHas('estimationItem', function ($query) use ($classificationInts) {
                $query->where(function ($q) use ($classificationInts) {
                    $q->whereHas('worker', function ($workerQuery) use ($classificationInts) {
                        $workerQuery->whereIn('classification_tkdn', $classificationInts);
                    })->orWhereHas('material', function ($materialQuery) use ($classificationInts) {
                        $materialQuery->whereIn('classification_tkdn', $classificationInts);
                    })->orWhereHas('equipment', function ($equipmentQuery) use ($classificationInts) {
                        $equipmentQuery->whereIn('classification_tkdn', $classificationInts);
                    });
                });
            });

        // Filter by specific HPP if provided
        if ($hppId) {
            $hppItemsQuery->where('hpp_items.hpp_id', $hppId);
        }

        $hppItems = $hppItemsQuery->with(['hpp', 'estimationItem.worker', 'estimationItem.material', 'estimationItem.equipment'])
            ->get();

        Log::info('HPP items query result', [
            'project_id' => $projectId,
            'form_number' => $formNumber,
            'classifications' => $classifications,
            'hpp_items_count' => $hppItems->count(),
            'hpp_items_ids' => $hppItems->pluck('id')->toArray(),
            'hpp_items_details' => $hppItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'description' => $item->description,
                    'volume' => $item->volume,
                    'duration' => $item->duration,
                    'total_price' => $item->total_price,
                    'estimation_item_id' => $item->estimation_item_id,
                    'hpp_id' => $item->hpp_id,
                ];
            })->toArray(),
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
            $formNumbers = \App\Models\Material::getFormNumbersForClassification($classificationInt, $projectType);
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

        // Tentukan quantity dan duration berdasarkan form number
        switch ($formNumber) {
            case '3.1':
            case '3.2':
            case '3.3':
            case '3.4':
            case '3.5':
                $quantity = 1;
                break;
            default:
                $quantity = 1;
                break;
        }

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

        // Hitung costs berdasarkan TKDN percentage
        $tkdnPercentage = $this->calculateTkdnPercentageForForm($formNumber);
        $wage = 1000000; // Default wage 1 juta
        $totalCost = $wage * $quantity * $duration;
        $domesticCost = ($totalCost * $tkdnPercentage) / 100;
        $foreignCost = $totalCost - $domesticCost;

        Log::info('Placeholder service item details', [
            'form_number' => $formNumber,
            'qualification' => $qualification,
            'quantity' => $quantity,
            'duration' => $duration,
            'duration_unit' => $durationUnit,
            'wage' => $wage,
            'total_cost' => $totalCost,
            'tkdn_percentage' => $tkdnPercentage,
            'domestic_cost' => $domesticCost,
            'foreign_cost' => $foreignCost,
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
     * Get qualification from HPP item based on estimation data
     */
    private function getQualificationFromHppItem($hppItem): ?string
    {
        // Jika ada estimation item, coba ambil qualification dari worker
        if ($hppItem->estimationItem && $hppItem->estimationItem->estimation) {
            $estimation = $hppItem->estimationItem->estimation;

            // Cek apakah ada worker data
            if ($estimation->items && $estimation->items->isNotEmpty()) {
                $firstItem = $estimation->items->first();

                // Jika ada worker, ambil qualification
                if ($firstItem->worker) {
                    return $firstItem->worker->qualification ?? 'Pekerja';
                }

                // Jika ada material, ambil kategori
                if ($firstItem->material) {
                    return 'Material: ' . $firstItem->material->category ?? 'Umum';
                }

                // Jika ada equipment, ambil kategori
                if ($firstItem->equipment) {
                    return 'Equipment: ' . $firstItem->equipment->category ?? 'Umum';
                }
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
            })->with(['hpp', 'estimationItem.estimation.items'])->get();

            // Group by tkdn_classification
            $groupedItems = $allHppItems->groupBy('tkdn_classification');

            // Log untuk debugging
            Log::info('Debug HPP Items for Service', [
                'service_id' => $service->id,
                'project_id' => $projectId,
                'total_hpp_items' => $allHppItems->count(),
                'grouped_by_classification' => $groupedItems->map(function ($items) {
                    return [
                        'count' => $items->count(),
                        'items' => $items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'description' => $item->description,
                                'volume' => $item->volume,
                                'duration' => $item->duration,
                                'total_price' => $item->total_price,
                                'estimation_item_id' => $item->estimation_item_id,
                                'has_estimation_item' => $item->estimationItem ? 'Yes' : 'No',
                                'has_estimation' => $item->estimationItem && $item->estimationItem->estimation ? 'Yes' : 'No',
                                'ahs_items_count' => $item->estimationItem && $item->estimationItem->estimation ?
                                    $item->estimationItem->estimation->items->count() : 0,
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
                    'grouped_by_classification' => $groupedItems->map(function ($items) {
                        return [
                            'classification' => $items->first()->tkdn_classification,
                            'count' => $items->count(),
                            'items' => $items->map(function ($item) {
                                return [
                                    'id' => $item->id,
                                    'description' => $item->description,
                                    'volume' => $item->volume,
                                    'duration' => $item->duration,
                                    'total_price' => $item->total_price,
                                    'estimation_item_id' => $item->estimation_item_id,
                                    'has_estimation_item' => $item->estimationItem ? 'Yes' : 'No',
                                    'has_estimation' => $item->estimationItem && $item->estimationItem->estimation ? 'Yes' : 'No',
                                    'ahs_items_count' => $item->estimationItem && $item->estimationItem->estimation ?
                                        $item->estimationItem->estimation->items->count() : 0,
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
            ->whereHas('estimationItem', function ($query) use ($projectType) {
                $query->forProjectType($projectType);
            })
            ->with(['hpp', 'estimationItem.worker', 'estimationItem.material', 'estimationItem.equipment'])
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

        // Ambil data HPP berdasarkan code
        $hpp = Hpp::where('code', $hppCode)->first();
        $hppahs = HppAhs::select('volume')
            ->where('hpp_id', $hpp->id)
            ->first();

        // dd($hppahs);

        // Grouping otomatis berdasarkan tkdn_classification (3.1 - 3.5, 4.1 - 4.7)
        $groupedItems = $serviceItems->groupBy('tkdn_classification');

        return view('dataservice.about', compact('serviceItems', 'groupedItems', 'hppahs'));
    }
}
