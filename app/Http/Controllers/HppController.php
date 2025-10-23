<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Estimation;
use App\Models\EstimationItem;
use App\Models\Hpp;
use App\Models\HppItem;
use App\Models\JournalWorker;
use App\Models\Material;
use App\Models\Project;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['getAhsDataOnly', 'getAhsItems']);
        $this->middleware('can:manage-hpp')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hpps = Hpp::with(['items', 'project'])->latest()->paginate(10);

        return view('hpp.index', compact('hpps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $ahsData = $this->getAhsData();

        return view('hpp.create', compact('projects', 'ahsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('HPP Store method called', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
        ]);

        try {
            $validated = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'overhead_percentage' => 'required|numeric|min:0|max:100',
                'margin_percentage' => 'required|numeric|min:0|max:100',
                'ppn_percentage' => 'required|numeric|min:0|max:100',
                'notes' => 'nullable|string',

                // Header AHS groups - allow any type of data (AHS, worker, material, equipment, journal_worker)
                'ahs' => 'nullable|array',
                'ahs.*.description' => 'nullable|string',
                'ahs.*.volume' => 'nullable|numeric|min:0',
                'ahs.*.unit' => 'nullable|string',
                'ahs.*.duration' => 'nullable|integer|min:1',
                'ahs.*.duration_unit' => 'nullable|string',
                'ahs.*.unit_price' => 'nullable|numeric|min:0',
                'ahs.*.total_price' => 'nullable|numeric|min:0',
                'ahs.*.coefficient' => 'nullable|numeric|min:0',
                'ahs.*.item_type' => 'nullable|string|in:ahs,worker,material,equipment,journal_worker',
                'ahs.*.ahs_id' => 'nullable|string', // Allow empty string for non-AHS types
                'ahs.*.reference_id' => 'nullable|string', // ID untuk morphTo reference

                // Nested detail items under each group - make optional for non-AHS types
                'items' => 'nullable|array',
                'items.*.detail' => 'nullable|array',
                'items.*.detail.*.description' => 'nullable|string',
                'items.*.detail.*.estimation_item_id' => 'nullable|exists:estimation_items,id',
                'items.*.detail.*.item_type' => 'nullable|string|in:ahs,worker,material,equipment,journal_worker,estimation_item',
                'items.*.detail.*.reference_id' => 'nullable|string', // ID untuk morphTo reference
                'items.*.detail.*.unit_price' => 'nullable|numeric|min:0',
                'items.*.detail.*.coefficient' => 'nullable|numeric|min:0',
                // Accept either 'quantity' or 'grand_total' from frontend
                'items.*.detail.*.quantity' => 'nullable|numeric|min:0',
                'items.*.detail.*.grand_total' => 'nullable|numeric|min:0',
            ]);

            \Log::info('=== Validation Passed ===', ['validated_data' => $validated]);
            // } 
            // catch (\Illuminate\Validation\ValidationException $e) {
            //     \Log::error('=== Validation Failed ===', [
            //         'errors' => $e->errors(),
            //         'request_data' => $request->all()
            //     ]);
            //     throw $e;
            // }

            // try {
            DB::beginTransaction();
            \Log::info('=== HPP Store: Transaction Started ===');

            //generate number optimized
            $hpp_project_count = Hpp::where('project_id', $request->project_id)->count() + 1;
            $format_number = str_pad($hpp_project_count, 3, '0', STR_PAD_LEFT);
            \Log::info('HPP Count Generated', ['count' => $hpp_project_count, 'format' => $format_number]);

            // Generate kode HPP
            $code = 'HPP-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $name_hpp = 'HPP - ' . Project::find($request->project_id)->name . ' - Alternative ' . $format_number;
            $name_hpp_AHS = 'HPP ' . Project::find($request->project_id)->name;
            \Log::info('HPP Names Generated', ['code' => $code, 'name_hpp' => $name_hpp, 'name_hpp_AHS' => $name_hpp_AHS]);


            // Compute totals based on grouped AHS and nested details
            $ahsGroups = $request->input('ahs', []);
            $itemGroups = $request->input('items', []);
            \Log::info('AHS Groups Received', ['count' => count($ahsGroups), 'data' => $ahsGroups]);
            \Log::info('Item Groups Received', ['count' => count($itemGroups), 'data' => $itemGroups]);

            // Validate that we have at least one item (any type)
            if (empty($ahsGroups)) {
                return redirect()->back()->withErrors(['ahs' => 'Minimal harus ada satu item (AHS, Worker, Material, Equipment, atau Journal Worker)'])->withInput();
            }

            // Debug: Check if we have any non-AHS items
            $hasNonAHS = false;
            foreach ($ahsGroups as $groupIndex => $ahsHeader) {
                $itemType = $ahsHeader['item_type'] ?? 'ahs';
                if ($itemType !== 'ahs') {
                    $hasNonAHS = true;
                    \Log::info("Found non-AHS item", ['groupIndex' => $groupIndex, 'itemType' => $itemType, 'header' => $ahsHeader]);
                }
            }
            \Log::info('Has non-AHS items', ['hasNonAHS' => $hasNonAHS]);

            $subTotalHppAhs = 0.0; // sum of each group's total_price

            // Pre-compute per-group unit_price and total_price based on item type
            $computedGroups = [];
            foreach ($ahsGroups as $groupIndex => $ahsHeader) {
                // Ambil tipe item dan reference_id dari request
                $itemType = strtolower($ahsHeader['item_type'] ?? 'ahs');
                $referenceId = $ahsHeader['reference_id'] ?? null;

                $volume     = (float) ($ahsHeader['volume'] ?? 1);
                $coefficient = (float) ($ahsHeader['coefficient'] ?? 1);
                $unitPrice   = (float) ($ahsHeader['unit_price'] ?? 0);

                $groupTotal   = 0.0;
                $unitPriceSum = 0.0;

                \Log::info("Processing AHS Group #{$groupIndex}", [
                    'type' => $itemType,
                    'reference_id' => $referenceId,
                    'volume' => $volume,
                    'unit_price' => $unitPrice,
                ]);

                // 🔹 Hitung berdasarkan tipe model sebenarnya
                switch ($itemType) {
                    case 'ahs':
                        // Hitung total dari detail AHS
                        $details = $itemGroups[$groupIndex]['detail'] ?? [];
                        \Log::info("Group #{$groupIndex} Details", ['count' => count($details)]);

                        foreach ($details as $detailIndex => $detail) {
                            $qty             = (float) ($detail['quantity'] ?? $detail['coefficient'] ?? 1);
                            $detailUnitPrice = (float) ($detail['unit_price'] ?? 0);
                            $itemTotal       = isset($detail['grand_total'])
                                ? (float) $detail['grand_total']
                                : $detailUnitPrice * $qty;

                            $unitPriceSum += $itemTotal;
                        }

                        $duration   = (int) ($ahsHeader['duration'] ?? 1);
                        $groupTotal = $unitPriceSum * $volume * $duration;
                        break;

                    case 'journalworker':
                    case 'journal_worker':
                        // Journal Worker: volume * unit_price
                        $groupTotal   = $volume * $unitPrice;
                        $unitPriceSum = $unitPrice;
                        break;

                    case 'worker':
                    case 'material':
                    case 'equipment':
                        // Master data langsung (Worker, Material, Equipment)
                        $groupTotal   = $volume * ($coefficient * $unitPrice);
                        $unitPriceSum = $coefficient * $unitPrice;
                        break;

                    default:
                        // fallback — kalau tipe gak dikenal
                        \Log::warning("Unknown item type '{$itemType}' on group {$groupIndex}");
                        break;
                }

                // Deteksi jika bukan AHS
                if ($itemType !== 'ahs') {
                    $hasNonAHS = true;
                }

                // Simpan hasil
                $computedGroups[] = [
                    'index'       => $groupIndex,
                    'item_type'   => $itemType,
                    'reference_id' => $referenceId,
                    'volume'      => $volume,
                    'unit_price'  => $unitPrice,
                    'total_price' => $groupTotal,
                    'unit_price_sum' => $unitPriceSum,
                    'group_total' => $groupTotal,
                ];

                $subTotalHppAhs += $groupTotal;
            }

            \Log::info('Has non-AHS items', ['hasNonAHS' => $hasNonAHS]);

            \Log::info('All Groups Computed', ['subTotalHppAhs' => $subTotalHppAhs, 'computedGroups' => $computedGroups]);

            // Overhead, Margin based on subTotalHppAhs
            $overheadAmount = $subTotalHppAhs * ($request->overhead_percentage / 100);
            $marginAmount = $subTotalHppAhs * ($request->margin_percentage / 100);
            $subTotal = $subTotalHppAhs + $overheadAmount + $marginAmount;
            $ppnAmount = $subTotal * ($request->ppn_percentage / 100);
            $grandTotal = $subTotal + $ppnAmount;

            \Log::info('Financial Calculations', [
                'subTotalHppAhs' => $subTotalHppAhs,
                'overhead_percentage' => $request->overhead_percentage,
                'overheadAmount' => $overheadAmount,
                'margin_percentage' => $request->margin_percentage,
                'marginAmount' => $marginAmount,
                'subTotal' => $subTotal,
                'ppn_percentage' => $request->ppn_percentage,
                'ppnAmount' => $ppnAmount,
                'grandTotal' => $grandTotal
            ]);

            // Buat HPP
            \Log::info('Creating HPP Record');
            $hpp = Hpp::create([
                'code' => $code,
                'project_id' => $request->project_id,
                'name_hpp' => $name_hpp,
                'sub_total_hpp' => $subTotalHppAhs, // sum of AHS grup before overhead/margin/ppn
                //Hitung overhead, margin, ppn, grand total
                'overhead_percentage' => $request->overhead_percentage,
                'overhead_amount' => $overheadAmount,
                'margin_percentage' => $request->margin_percentage,
                'margin_amount' => $marginAmount,
                'sub_total' => $subTotal,
                'ppn_percentage' => $request->ppn_percentage,
                'ppn_amount' => $ppnAmount,
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                'status' => 'draft',
            ]);

            \Log::info('HPP Created Successfully', ['hpp_id' => $hpp->id, 'hpp_code' => $hpp->code]);

            // Buat HPP- AHS & Hpp - Items 
            \Log::info('=== Creating AHS and Items ===');

            $categoryMap = [
                'worker'         => \App\Models\Worker::class,
                'material'       => \App\Models\Material::class,
                'equipment'      => \App\Models\Equipment::class,
                'ahs'            => \App\Models\Estimation::class,
                'journal_worker' => \App\Models\JournalWorker::class,
            ];

            foreach ($ahsGroups as $groupIndex => $ahsHeader) {
                \Log::info("Creating AHS for Group #{$groupIndex}");

                $unitPriceSum = $computedGroups[$groupIndex]['unit_price_sum'] ?? 0.0;
                $groupTotal   = $computedGroups[$groupIndex]['group_total'] ?? 0.0;

                // Tentukan nama AHS
                $nameAhsHeader = $ahsHeader['description'] ?? null;
                if (! $nameAhsHeader && ! empty($ahsHeader['ahs_id']) && is_numeric($ahsHeader['ahs_id'])) {
                    $nameAhsHeader = 'AHS-' . $ahsHeader['ahs_id'];
                }

                if (! $nameAhsHeader) {
                    $itemType  = $ahsHeader['item_type'] ?? 'ahs';
                    $typeLabel = $this->getItemTypeLabel($itemType);
                    $nameAhsHeader = $typeLabel . ': ' . ($ahsHeader['description'] ?? 'Item');
                }

                \Log::info("Group #{$groupIndex} AHS Name Resolved", ['nameAhsHeader' => $nameAhsHeader]);

                // Hitung harga
                $itemType   = strtolower($ahsHeader['item_type'] ?? 'ahs');
                $unitPrice  = max(0, $unitPriceSum);
                $totalPrice = max(0, $groupTotal);

                if ($itemType !== 'ahs' && $unitPrice == 0) {
                    $unitPrice = (float) ($ahsHeader['unit_price'] ?? 0);
                }
                if ($itemType !== 'ahs' && $totalPrice == 0) {
                    $totalPrice = (float) ($ahsHeader['total_price'] ?? 0);
                }

                \Log::info("Creating AHS with values", [
                    'itemType'     => $itemType,
                    'unitPrice'    => $unitPrice,
                    'totalPrice'   => $totalPrice,
                    'unitPriceSum' => $unitPriceSum,
                    'groupTotal'   => $groupTotal,
                ]);

                // 🔹 Buat AHS Header di tabel HPP_AHS
                $createdAhs = $hpp->ahs()->create([
                    'name_ahs'      => $name_hpp_AHS . ' - ' . $nameAhsHeader,
                    'volume'        => $ahsHeader['volume'] ?? 1,
                    'unit'          => $ahsHeader['unit'] ?? 'Unit',
                    'duration'      => $ahsHeader['duration'] ?? 1,
                    'duration_unit' => $ahsHeader['duration_unit'] ?? 'Hari',
                    'unit_price'    => $unitPrice,
                    'total_price'   => $totalPrice,
                ]);

                // 🔹 Ambil detail item dari group
                $details = $itemGroups[$groupIndex]['detail'] ?? [];
                \Log::info("Group #{$groupIndex} Item Details", ['count' => count($details)]);

                /**
                 * =====================================================
                 * CASE 1: Tidak ada detail → buat item dari header (non-AHS)
                 * =====================================================
                 */
                if (count($details) === 0 && $itemType !== 'ahs') {
                    $modelClass = $categoryMap[$itemType] ?? null;
                    $modelId = $ahsHeader['reference_id'] ?? null;

                    if ($modelClass && $modelId && $modelClass::find($modelId)) {
                        $itemTypeForMorph = $modelClass;
                        $itemIdForMorph   = $modelId;
                    } else {
                        $itemTypeForMorph = \App\Models\Estimation::class;
                        $itemIdForMorph   = $createdAhs->id;
                    }

                    $headerCoefficient = (float) ($ahsHeader['coefficient'] ?? 1);
                    $headerUnitPrice   = (float) ($ahsHeader['unit_price'] ?? 0);
                    $headerVolume      = (float) ($ahsHeader['volume'] ?? 1);
                    $unit              = $ahsHeader['unit'] ?? $this->getUnitForItemType($itemType);

                    // Hitung total harga
                    if ($itemType === 'journal_worker') {
                        $totalPrice = $headerVolume * $headerUnitPrice;
                    } else {
                        $totalPrice = $headerVolume * ($headerCoefficient * $headerUnitPrice);
                    }

                    $hppItem = $hpp->items()->create([
                        'hpp_ahs_id'        => $createdAhs->id,
                        'item_type'         => $itemTypeForMorph,
                        'item_id'           => $itemIdForMorph,
                        'description'       => $ahsHeader['description'] ?? '',
                        'volume'            => $headerVolume,
                        'unit'              => $unit,
                        'duration'          => 1,
                        'duration_unit'     => 'Hari',
                        'koefisien'         => $headerCoefficient,
                        'unit_price'        => $headerUnitPrice,
                        'jumlah'            => $headerCoefficient,
                        'total_price'       => $totalPrice,
                    ]);

                    \Log::info("Created Header Item for Group #{$groupIndex}", [
                        'item_id' => $hppItem->id,
                        'type'    => $itemTypeForMorph,
                        'ref_id'  => $itemIdForMorph,
                    ]);

                    continue;
                }

                /**
                 * =====================================================
                 * CASE 2: Ada detail item → iterasi per detail
                 * =====================================================
                 */
                foreach ($details as $detailIndex => $detail) {
                    try {
                        $itemKey = strtolower($detail['item_type'] ?? '');
                        $modelClass = $categoryMap[$itemKey] ?? null;
                        $modelId = $detail['reference_id'] ?? null;

                        if ($modelClass && $modelId && $modelClass::find($modelId)) {
                            $itemTypeForMorph = $modelClass;
                            $itemIdForMorph   = $modelId;
                        } else {
                            // Fallback untuk AHS atau estimation item
                            $itemKey = strtolower($detail['item_type'] ?? '');
                            if ($itemKey === 'estimation_item') {
                                $estimationItemId = $detail['estimation_item_id'] ?? null;
                                if ($estimationItemId) {
                                    $itemTypeForMorph = \App\Models\EstimationItem::class;
                                    $itemIdForMorph   = $estimationItemId;
                                } else {
                                    $itemTypeForMorph = \App\Models\Estimation::class;
                                    $itemIdForMorph   = $createdAhs->id;
                                }
                            } else {
                                $itemTypeForMorph = \App\Models\Estimation::class;
                                $itemIdForMorph   = $createdAhs->id;
                            }
                        }

                        $description = $detail['description'] ?? '';
                        $unit = $detail['unit'] ?? 'Unit';
                        $coef = (float) ($detail['coefficient'] ?? 0);
                        $qty = (float) ($detail['quantity'] ?? $detail['coefficient'] ?? 1);
                        $unitPrice = (float) ($detail['unit_price'] ?? 0);

                        $totalPrice = isset($detail['grand_total'])
                            ? (float) $detail['grand_total']
                            : $unitPrice * $qty;

                        $hppItem = $hpp->items()->create([
                            'hpp_ahs_id'        => $createdAhs->id,
                            'item_type'          => $itemTypeForMorph,
                            'item_id'            => $itemIdForMorph,
                            'estimation_item_id' => $detail['estimation_item_id'] ?? null,
                            'description'        => $description,
                            'volume'            => 1,
                            'unit'              => $unit,
                            'duration'          => 1,
                            'duration_unit'     => 'Hari',
                            'koefisien'         => $coef,
                            'unit_price'        => $unitPrice,
                            'jumlah'            => $qty,
                            'total_price'       => $totalPrice,
                        ]);

                        \Log::info("Created Detail Item #{$detailIndex} for Group #{$groupIndex}", [
                            'item_id'   => $hppItem->id,
                            'type'      => $itemTypeForMorph,
                            'ref_id'    => $itemIdForMorph,
                            'unit_price' => $unitPrice,
                            'qty'       => $qty,
                            'total'     => $totalPrice,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error("Failed to create HPP item", [
                            'group'  => $groupIndex,
                            'detail' => $detailIndex,
                            'error'  => $e->getMessage(),
                        ]);
                        throw $e;
                    }
                }
            }


            \Log::info('=== All AHS and Items Created Successfully ===');
            DB::commit();
            \Log::info('=== Transaction Committed ===');

            return redirect()->route('hpp.index')->with('success', 'HPP berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('=== HPP Store Failed ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $hpp = Hpp::with(['items', 'project'])->findOrFail($id);
        $hppahs = $hpp->ahs;
        $hppitems = HppItem::where('hpp_id', $hpp->id)->get();

        return view('hpp.show', compact('hpp', 'hppahs', 'hppitems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $hpp = Hpp::with(['items.estimationItem.estimation', 'ahs', 'project'])->findOrFail($id);
        $projects = Project::all();
        $hppitems = HppItem::where('hpp_id', $hpp->id)->get();
        $ahsData = $this->getAhsData();

        return view('hpp.edit', compact('hpp', 'projects', 'ahsData', 'hppitems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if user can manage HPP
        if (! Auth::user()->can('manage-hpp')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'overhead_percentage' => 'required|numeric|min:0|max:100',
            'margin_percentage' => 'required|numeric|min:0|max:100',
            'ppn_percentage' => 'required|numeric|min:0|max:100',
            'notes' => 'nullable|string',

            // Header AHS groups - allow any type of data (AHS, worker, material, equipment, journal_worker)
            'ahs' => 'nullable|array',
            'ahs.*.description' => 'nullable|string',
            'ahs.*.volume' => 'nullable|numeric|min:0',
            'ahs.*.unit' => 'nullable|string',
            'ahs.*.duration' => 'nullable|integer|min:1',
            'ahs.*.duration_unit' => 'nullable|string',
            'ahs.*.unit_price' => 'nullable|numeric|min:0',
            'ahs.*.total_price' => 'nullable|numeric|min:0',
            'ahs.*.coefficient' => 'nullable|numeric|min:0',
            'ahs.*.item_type' => 'nullable|string|in:ahs,worker,material,equipment,journal_worker',
            'ahs.*.ahs_id' => 'nullable|string', // Allow empty string for non-AHS types

            // Nested detail items under each group - make optional for non-AHS types
            'items' => 'nullable|array',
            'items.*.detail' => 'nullable|array',
            'items.*.detail.*.description' => 'nullable|string',
            'items.*.detail.*.estimation_item_id' => 'nullable|exists:estimation_items,id',
            'items.*.detail.*.item_type' => 'nullable|string|in:worker,material,equipment,journal_worker',
            'items.*.detail.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.detail.*.coefficient' => 'nullable|numeric|min:0',
            'items.*.detail.*.quantity' => 'nullable|numeric|min:0',
            'items.*.detail.*.grand_total' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $hpp = Hpp::findOrFail($id);

            //generate number optimized
            $hpp_project_count = Hpp::where('project_id', $request->project_id)->count() + 1;
            $format_number = str_pad($hpp_project_count, 3, '0', STR_PAD_LEFT);


            // Generate kode HPP
            $code = 'HPP-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $name_hpp = 'HPP - ' . Project::find($request->project_id)->name . ' - Alternative ' . $format_number;
            $name_hpp_AHS = 'HPP ' . Project::find($request->project_id)->name;

            // Compute totals based on grouped AHS and nested details
            $ahsGroups = $request->input('ahs', []);
            $itemGroups = $request->input('items', []);

            $subTotalHppAhs = 0.0; // sum of each group's total_price
            // Pre-compute per-group unit_price (sum of item grand totals) and total_price
            $computedGroups = [];
            foreach ($ahsGroups as $groupIndex => $ahsHeader) {
                $details = $itemGroups[$groupIndex]['detail'] ?? [];
                $unitPriceSum = 0.0;
                foreach ($details as $detail) {
                    $qty = (float) ($detail['quantity'] ?? 0);
                    $unitPrice = (float) ($detail['unit_price'] ?? 0);
                    $unitPriceSum += ($unitPrice * $qty);
                }

                $volume = (float) ($ahsHeader['volume'] ?? 1);
                $duration = (int) ($ahsHeader['duration'] ?? 1);
                $groupTotal = $unitPriceSum * $volume * $duration;
                $subTotalHppAhs += $groupTotal;

                $computedGroups[$groupIndex] = [
                    'unit_price_sum' => $unitPriceSum,
                    'group_total' => $groupTotal,
                ];
            }
            // Overhead, Margin based on subTotalHppAhs
            $overheadAmount = $subTotalHppAhs * ($request->overhead_percentage / 100);
            $marginAmount = $subTotalHppAhs * ($request->margin_percentage / 100);
            $subTotal = $subTotalHppAhs + $overheadAmount + $marginAmount;
            $ppnAmount = $subTotal * ($request->ppn_percentage / 100);
            $grandTotal = $subTotal + $ppnAmount;
            // Update HPP
            $hpp->update([
                'code' => $code,
                'project_id' => $request->project_id,
                // 'name_hpp' => $name_hpp, --- IGNORE ---
                'sub_total_hpp' => $subTotalHppAhs, // sum of AHS grup before overhead/margin/ppn
                //Hitung overhead, margin, ppn, grand total
                'overhead_percentage' => $request->overhead_percentage,
                'overhead_amount' => $overheadAmount,
                'margin_percentage' => $request->margin_percentage,
                'margin_amount' => $marginAmount,
                'sub_total' => $subTotal,
                'ppn_percentage' => $request->ppn_percentage,
                'ppn_amount' => $ppnAmount,
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                'status' => 'draft',
            ]);
            // Hapus AHS lama
            $hpp->ahs()->delete();
            // Buat HPP- AHS & Hpp - Items
            foreach ($ahsGroups as $groupIndex => $ahsHeader) {
                $unitPriceSum = $computedGroups[$groupIndex]['unit_price_sum'] ?? 0.0;
                $groupTotal = $computedGroups[$groupIndex]['group_total'] ?? 0.0;

                // Resolve AHS name from description or fallback by ahs_id
                $nameAhsHeader = $ahsHeader['description'] ?? null;
                if (! $nameAhsHeader && ! empty($ahsHeader['ahs_id'])) {
                    $est = Estimation::find($ahsHeader['ahs_id']);
                    if ($est) {
                        $nameAhsHeader = $est->code . ' - ' . $est->title;
                    }
                }

                $createdAhs = $hpp->ahs()->create([
                    'name_ahs' =>  $name_hpp_AHS . ' - ' . $nameAhsHeader,
                    'volume' => $ahsHeader['volume'] ?? 1,
                    'unit' => $ahsHeader['unit'] ?? 'Unit',
                    'duration' => $ahsHeader['duration'] ?? 1,
                    'duration_unit' => $ahsHeader['duration_unit'] ?? 'Hari',
                    'unit_price' => $unitPriceSum, // sum of detail grand totals
                    'total_price' => $groupTotal, // volume * unit_price_sum * duration
                ]);

                // Buat HPP items per AHS
                $details = $itemGroups[$groupIndex]['detail'] ?? [];
                foreach ($details as $detail) {
                    $hppAhsid = $createdAhs->id;
                    $estimationItemId = $detail['estimation_item_id'] ?? null;
                    $nameAhs = $createdAhs->name_ahs;
                    $description = $detail['description'] ?? '';
                    $unit = $detail['unit'] ?? ($estimationItemId ? $this->getItemUnit(EstimationItem::find($estimationItemId)) : 'Unit');
                    $coef = (float) ($detail['coefficient'] ?? 0);
                    $qty = (float) ($detail['quantity'] ?? 0);
                    $unitPrice = (float) ($detail['unit_price'] ?? 0);
                    $totalPrice = $unitPrice * $qty;

                    $hpp->items()->create([
                        'hpp_ahs_id' => $hppAhsid,
                        'estimation_item_id' => $estimationItemId,
                        'name_ahs' => $nameAhs,
                        'description' => $description,
                        'volume' => 1,
                        'unit' => $unit,
                        'duration' => 1,
                        'duration_unit' => 'Hari',
                        'koefisien' => $coef,
                        'unit_price' => $unitPrice,
                        'jumlah' => $qty,
                        'total_price' => $totalPrice,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('hpp.index')->with('success', 'HPP berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $hpp = Hpp::findOrFail($id);
            $hpp->items()->delete();
            $hpp->delete();

            return redirect()->route('hpp.index')->with('success', 'HPP berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get estimation items for AJAX request
     */
    public function getEstimationItems(Request $request, string $id)
    {
        $hpp = Hpp::with(['items'])->findOrFail($id);
        $items = $hpp->items;

        return response()->json($items);
    }

    /**
     * Get AHS data for dropdown
     */
    public function getAhsData()
    {
        $ahsData = [];

        // Get Estimations (AHS) only
        $estimations = Estimation::with('items')->get();
        foreach ($estimations as $estimation) {
            $ahsData[] = [
                'type' => 'ahs',
                'id' => $estimation->id,
                'code' => $estimation->code,
                'title' => $estimation->title,
                'description' => $estimation->code . ' - ' . $estimation->title,
                'category' => 'AHS',
                'item_count' => $estimation->items->count(),
            ];
        }

        // Get Workers
        $workers = Worker::with('category')->get();
        foreach ($workers as $worker) {
            $ahsData[] = [
                'type' => 'worker',
                'id' => $worker->id,
                'code' => $worker->code,
                'title' => $worker->name,
                'description' => $worker->code . ' - ' . $worker->name,
                'unit_price' => $worker->price,
                'category' => 'Pekerja',
                'unit' => $worker->unit,
                'tkdn' => $worker->tkdn,
            ];
        }

        // Get Materials
        $materials = Material::with('category')->get();
        foreach ($materials as $material) {
            $ahsData[] = [
                'type' => 'material',
                'id' => $material->id,
                'code' => $material->code,
                'title' => $material->name,
                'description' => $material->code . ' - ' . $material->name,
                'unit_price' => $material->price,
                'category' => 'Material',
                'unit' => $material->unit,
                'tkdn' => $material->tkdn,
            ];
        }

        // Get Equipment
        $equipment = Equipment::with('category')->get();
        foreach ($equipment as $eq) {
            $ahsData[] = [
                'type' => 'equipment',
                'id' => $eq->id,
                'code' => $eq->code,
                'title' => $eq->name,
                'description' => $eq->code . ' - ' . $eq->name,
                'unit_price' => $eq->price,
                'category' => 'Peralatan',
                'period' => $eq->period,
                'tkdn' => $eq->tkdn,
            ];
        }

        return $ahsData;
    }

    /**
     * Get item name based on category
     */
    private function getItemName(EstimationItem $item): string
    {
        switch ($item->category) {
            case 'worker':
                return $item->worker ? $item->worker->name : 'Pekerja';
            case 'material':
                return $item->material ? $item->material->name : 'Material';
            case 'equipment':
                return $item->equipment ? $item->equipment->name : 'Peralatan';
            default:
                return 'Item';
        }
    }

    /**
     * Get item unit based on category
     */
    private function getItemUnit(EstimationItem $item): string
    {
        switch ($item->category) {
            case 'worker':
                return 'OH';
            case 'material':
                return 'Unit';
            case 'equipment':
                return 'Hari';
            default:
                return 'Unit';
        }
    }

    /**
     * Get unit for item type
     */
    private function getUnitForItemType(string $itemType): string
    {
        switch ($itemType) {
            case 'worker':
                return 'OH';
            case 'material':
                return 'Unit';
            case 'equipment':
                return 'Hari';
            case 'journal_worker':
                return 'Unit';
            default:
                return 'Unit';
        }
    }

    /**
     * Get item type label
     */
    private function getItemTypeLabel(string $itemType): string
    {
        switch ($itemType) {
            case 'worker':
                return 'Pekerja';
            case 'material':
                return 'Material';
            case 'equipment':
                return 'Peralatan';
            case 'journal_worker':
                return 'Journal Worker';
            case 'ahs':
                return 'AHS';
            default:
                return 'Item';
        }
    }

    /**
     * Get AHS data for AJAX request
     */
    // public function getAhsDataAjax(Request $request)
    // {
    //     $ahsData = $this->getAhsData();

    //     return response()->json($ahsData);
    // }
    public function getAhsDataAjax(Request $request)
    {
        try {
            // Gabungan semua data (AHS + Worker + Material + Equipment)
            // $allAhsData = $this->getAhsData();

            // Data per master (supaya bisa dipakai terpisah)
            $ahs = Estimation::select('id', 'code', 'title')->withCount('items')->get();
            $workers = Worker::select('id', 'code', 'name', 'price', 'unit', 'tkdn')->get();
            $materials = Material::select('id', 'code', 'name', 'price', 'unit', 'tkdn')->get();
            $equipment = Equipment::select('id', 'code', 'name', 'price', 'tkdn', 'period')->get();

            // Optional: master kategori (jika dibutuhkan di form dropdown)
            // $categories = Category::select('id', 'name')->get();

            return response()->json([
                // 'all' => $allAhsData,
                'ahs' => $ahs,
                'workers' => $workers,
                'materials' => $materials,
                'equipment' => $equipment,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Approve HPP
     */
    public function approve(Hpp $hpp)
    {
        // Check if user can manage HPP
        if (! Auth::user()->can('manage-hpp')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if HPP status is submitted
        // if ($hpp->status !== 'submitted') {
        //     return redirect()->route('hpp.index')
        //         ->with('error', 'HPP hanya dapat disetujui jika statusnya "Diajukan".');
        // }

        try {
            $hpp->update(['status' => 'approved']);

            return redirect()->route('hpp.index')
                ->with('success', 'HPP berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->route('hpp.index')
                ->with('error', 'Terjadi kesalahan saat menyetujui HPP.');
        }
    }

    /**
     * Reject HPP
     */
    public function reject(Hpp $hpp)
    {
        // Check if user can manage HPP
        if (! Auth::user()->can('manage-hpp')) {
            abort(403, 'Unauthorized action.');
        }

        // Check if HPP status is submitted
        if ($hpp->status !== 'submitted') {
            return redirect()->route('hpp.index')
                ->with('error', 'HPP hanya dapat ditolak jika statusnya "Diajukan".');
        }

        try {
            $hpp->update(['status' => 'rejected']);

            return redirect()->route('hpp.index')
                ->with('success', 'HPP berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->route('hpp.index')
                ->with('error', 'Terjadi kesalahan saat menolak HPP.');
        }
    }

    /**
     * Get AHS data filtered by project type
     */
    public function getAhsDataByProjectType($projectType)
    {
        $ahsData = [];

        // Get Estimations (AHS) filtered by project type
        $estimations = Estimation::with(['items' => function ($query) use ($projectType) {
            $query->forProjectType($projectType);
        }])->get();

        foreach ($estimations as $estimation) {
            $ahsData[] = [
                'type' => 'ahs',
                'id' => $estimation->id,
                'code' => $estimation->code,
                'title' => $estimation->title,
                'description' => $estimation->code . ' - ' . $estimation->title,
                'category' => 'AHS',
                'item_count' => $estimation->items->count(),
            ];
        }

        // Get Workers filtered by project type
        $workers = Worker::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($workers as $worker) {
            $ahsData[] = [
                'type' => 'worker',
                'id' => $worker->id,
                'code' => $worker->code,
                'title' => $worker->name,
                'description' => $worker->code . ' - ' . $worker->name,
                'unit_price' => $worker->price,
                'category' => 'Pekerja',
                'unit' => $worker->unit,
                'tkdn' => $worker->tkdn,
                'classification_tkdn' => $worker->classification_tkdn,
            ];
        }

        // Get Materials filtered by project type
        $materials = Material::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($materials as $material) {
            $ahsData[] = [
                'type' => 'material',
                'id' => $material->id,
                'code' => $material->code,
                'title' => $material->name,
                'description' => $material->code . ' - ' . $material->name,
                'unit_price' => $material->price,
                'category' => 'Material',
                'unit' => $material->unit,
                'tkdn' => $material->tkdn,
                'classification_tkdn' => $material->classification_tkdn,
            ];
        }

        // Get Equipment filtered by project type
        $equipment = Equipment::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($equipment as $eq) {
            $ahsData[] = [
                'type' => 'equipment',
                'id' => $eq->id,
                'code' => $eq->code,
                'title' => $eq->name,
                'description' => $eq->code . ' - ' . $eq->name,
                'unit_price' => $eq->price,
                'category' => 'Peralatan',
                'period' => $eq->period,
                'tkdn' => $eq->tkdn,
                'classification_tkdn' => $eq->classification_tkdn,
            ];
        }

        return $ahsData;
    }

    /**
     * Get classifications for project type
     */
    private function getClassificationsForProjectType(string $projectType): array
    {
        // Menggunakan integer classification sesuai dengan StringHelper mapping
        return $projectType === 'tkdn_jasa'
            ? [1, 2, 3, 4] // Overhead & Manajemen, Alat Kerja / Fasilitas, Konstruksi & Fabrikasi, Peralatan (Jasa Umum)
            : [1, 2, 3, 4, 5, 6]; // Semua classification termasuk Material (Bahan Baku) dan Peralatan (Barang Jadi)
    }

    /**
     * Get all data (AHS, Worker, Material, Equipment, JournalWorker) filtered by project type
     */
    public function getAhsDataOnly($projectType)
    {
        $allData = [];

        // Get Estimations (AHS) filtered by project type
        $estimations = Estimation::with(['items' => function ($query) use ($projectType) {
            $query->forProjectType($projectType);
        }])->get();

        foreach ($estimations as $estimation) {
            // Only include estimations that have items matching the project type
            if ($estimation->items->count() > 0) {
                $allData[] = [
                    'type' => 'ahs',
                    'id' => $estimation->id,
                    'code' => $estimation->code,
                    'title' => $estimation->title,
                    'description' => $estimation->code . ' - ' . $estimation->title,
                    'category' => 'AHS',
                    'item_count' => $estimation->items->count(),
                ];
            }
        }

        // Get Workers filtered by project type
        $workers = Worker::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($workers as $worker) {
            $allData[] = [
                'type' => 'worker',
                'id' => $worker->id,
                'code' => $worker->code,
                'title' => $worker->name,
                'description' => $worker->code . ' - ' . $worker->name,
                'unit_price' => $worker->price,
                'category' => 'Pekerja',
                'unit' => $worker->unit,
                'tkdn' => $worker->tkdn,
                'classification_tkdn' => $worker->classification_tkdn,
            ];
        }

        // Get Materials filtered by project type
        $materials = Material::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($materials as $material) {
            $allData[] = [
                'type' => 'material',
                'id' => $material->id,
                'code' => $material->code,
                'title' => $material->name,
                'description' => $material->code . ' - ' . $material->name,
                'unit_price' => $material->price,
                'category' => 'Material',
                'unit' => $material->unit,
                'tkdn' => $material->tkdn,
                'classification_tkdn' => $material->classification_tkdn,
            ];
        }

        // Get Equipment filtered by project type
        $equipment = Equipment::with('category')
            ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($equipment as $eq) {
            $allData[] = [
                'type' => 'equipment',
                'id' => $eq->id,
                'code' => $eq->code,
                'title' => $eq->name,
                'description' => $eq->code . ' - ' . $eq->name,
                'unit_price' => $eq->price,
                'category' => 'Peralatan',
                'period' => $eq->period,
                'tkdn' => $eq->tkdn,
                'classification_tkdn' => $eq->classification_tkdn,
            ];
        }

        // Get Journal Workers filtered by project type
        $journalWorkers = JournalWorker::whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
            ->get();
        foreach ($journalWorkers as $journalWorker) {
            $allData[] = [
                'type' => 'journal_worker',
                'id' => $journalWorker->id,
                'code' => 'JW-' . str_pad($journalWorker->id, 4, '0', STR_PAD_LEFT),
                'title' => $journalWorker->nama_pekerjaan,
                'description' => $journalWorker->nama_pekerjaan,
                'unit_price' => $journalWorker->satuan_harga,
                'category' => 'Journal Worker',
                'unit' => $journalWorker->satuan_or_durasi,
                'tkdn' => $journalWorker->tkdn,
                'classification_tkdn' => $journalWorker->classification_tkdn,
                'spesifikasi' => $journalWorker->spesifikasi_or_kualifikasi,
                'negara_asal' => $journalWorker->negara_asal,
            ];
        }

        return response()->json($allData);
    }

    /**
     * Get AHS items with project type filtering
     */
    public function getAhsItems($estimationId, $projectType)
    {
        $estimation = Estimation::with(['items' => function ($query) use ($projectType) {
            $query->forProjectType($projectType);
        }])->findOrFail($estimationId);

        $items = $estimation->items->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $this->getItemName($item),
                'code' => $item->code,
                'category' => $item->category,
                'unit_price' => $item->unit_price,
                'coefficient' => $item->coefficient,
                'tkdn_value' => $item->tkdn_value,
                'unit' => $this->getItemUnit($item),
                'classification_tkdn' => $item->classification_tkdn,
            ];
        });

        return response()->json([
            'estimation' => [
                'id' => $estimation->id,
                'code' => $estimation->code,
                'title' => $estimation->title,
                'description' => $estimation->code . ' - ' . $estimation->title,
            ],
            'items' => $items,
        ]);
    }

    /**
     * Get individual master data item for HPP
     */
    public function getMasterDataItem($type, $id, $projectType)
    {
        try {
            $item = null;
            $itemData = null;

            switch ($type) {
                case 'worker':
                    $item = Worker::with('category')
                        ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
                        ->findOrFail($id);
                    $itemData = [
                        'id' => $item->id,
                        'description' => $item->name,
                        'code' => $item->code,
                        'category' => 'worker',
                        'unit_price' => $item->price,
                        'coefficient' => 1,
                        'unit' => $item->unit,
                        'tkdn' => $item->tkdn,
                        'classification_tkdn' => $item->classification_tkdn,
                    ];
                    break;

                case 'material':
                    $item = Material::with('category')
                        ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
                        ->findOrFail($id);
                    $itemData = [
                        'id' => $item->id,
                        'description' => $item->name,
                        'code' => $item->code,
                        'category' => 'material',
                        'unit_price' => $item->price,
                        'coefficient' => 1,
                        'unit' => $item->unit,
                        'tkdn' => $item->tkdn,
                        'classification_tkdn' => $item->classification_tkdn,
                    ];
                    break;

                case 'equipment':
                    $item = Equipment::with('category')
                        ->whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
                        ->findOrFail($id);
                    $itemData = [
                        'id' => $item->id,
                        'description' => $item->name,
                        'code' => $item->code,
                        'category' => 'equipment',
                        'unit_price' => $item->price,
                        'coefficient' => 1,
                        'unit' => 'Hari',
                        'tkdn' => $item->tkdn,
                        'classification_tkdn' => $item->classification_tkdn,
                        'period' => $item->period,
                    ];
                    break;

                case 'journal_worker':
                    $item = JournalWorker::whereIn('classification_tkdn', $this->getClassificationsForProjectType($projectType))
                        ->findOrFail($id);
                    $itemData = [
                        'id' => $item->id,
                        'description' => $item->nama_pekerjaan,
                        'code' => 'JW-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                        'category' => 'journal_worker',
                        'unit_price' => $item->satuan_harga,
                        'coefficient' => 1,
                        'unit' => $item->satuan_or_durasi,
                        'tkdn' => $item->tkdn,
                        'classification_tkdn' => $item->classification_tkdn,
                        'spesifikasi' => $item->spesifikasi_or_kualifikasi,
                        'negara_asal' => $item->negara_asal,
                    ];
                    break;

                default:
                    return response()->json(['error' => 'Invalid item type'], 400);
            }

            return response()->json([
                'item' => $itemData,
                'type' => $type,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Item not found or not available for this project type'], 404);
        }
    }

    public function addComment(Request $request, Hpp $hpp)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        try {
            $approvalService = new \App\Services\HppApprovalService();
            $approvalService->addComment($hpp, $request->comment);

            return redirect()->route('hpp.show', $hpp->id)
                ->with('success', 'Komentar berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan komentar: ' . $e->getMessage());
        }
    }
}
