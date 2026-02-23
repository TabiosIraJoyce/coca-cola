<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodReport;
use App\Models\PeriodReportItem;
use App\Models\PeriodReportInventory;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\PeriodTarget;

class PeriodReportController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.consolidated.period-summary');
    }

    /* =====================================================
       CREATE
    ===================================================== */
    public function create(Request $request)
 {
     $branches = ['Solsona', 'Laoag', 'Batac'];
 
     $branch   = $request->branch ?? 'Solsona';
     $periodNo = $request->period_no ?? 1;
     $date     = $request->report_date ?? now()->toDateString();
 
     $report = null;
     $items  = collect();
     $divisionId = auth()->user()->division_id;
 
     if ($request->filled('report_id')) {
         $report = PeriodReport::with(['items','inventories'])
             ->where('division_id', $divisionId)
             ->find($request->report_id);
         if ($report) {
             $items = $report->items;
             $branch   = $report->branch;
             $periodNo = $report->period_no;
             $date     = $report->report_date?->toDateString() ?? $date;
         }
     }
 
     // IMPORTANT: Do NOT auto-load yesterday/previous reports on the create screen.
     // "Add report" must always start at zero unless an explicit `report_id` is provided.

     // Detect existing reports for this date so users can still jump to edit if needed.
     $existingReportId = null;
     $existingReportCount = 0;
     if (!$report) {
         $existingReportCount = PeriodReport::query()
             ->where('division_id', $divisionId)
             ->where('branch', $branch)
             ->where('period_no', $periodNo)
             ->whereDate('report_date', $date)
             ->count();

         $existingReportId = PeriodReport::query()
             ->where('division_id', $divisionId)
             ->where('branch', $branch)
             ->where('period_no', $periodNo)
             ->whereDate('report_date', $date)
             ->orderByDesc('id')
             ->value('id');
     }

     // PRODUCTS (active + anything already saved in this report so values never "disappear")
     $products = Product::where('status', 'active')
         ->orderBy('category')
         ->orderBy('pack_size')
         ->orderBy('product_name')
         ->get();

     if ($report) {
         $existingKeys = $products->map(function ($p) {
             return (string) ($p->pack_size ?? '') . '|' . (string) ($p->product_name ?? '');
         })->flip();

         $extras = $report->items->filter(function ($i) use ($existingKeys) {
             $key = (string) ($i->pack ?? '') . '|' . (string) ($i->product ?? '');
             return !$existingKeys->has($key);
         })->map(function ($i) {
             return Product::make([
                 'pack_size'      => $i->pack,
                 'product_name'   => $i->product,
                 'category'       => $i->category,
                 'srp'            => 0,
                 // Use saved UCS as the default so totals remain consistent even if product master data changes.
                 'ucs'            => max((float) ($i->core_ucs ?? 0), (float) ($i->iws_ucs ?? 0)),
                 'status'         => 'inactive',
             ]);
         });

         $products = $products->concat($extras)
             ->sortBy([
                 fn ($p) => (string) ($p->category ?? ''),
                 fn ($p) => (string) ($p->pack_size ?? ''),
                 fn ($p) => (string) ($p->product_name ?? ''),
             ])->values();
     }

    // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸Ãƒâ€¦Ã‚Â½Ãƒâ€šÃ‚Â¯ READ TARGET (NO DIVISION, DATE SAFE)
    $target = PeriodTarget::where('branch', $branch)
        ->where('period_no', $periodNo)
        ->whereDate('start_date', '<=', $date)
        ->whereDate('end_date', '>=', $date)
        ->orderBy('start_date', 'desc')
        ->first();

    // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸Ãƒâ€¦Ã‚Â½Ãƒâ€šÃ‚Â¯ TARGET COMPUTATION
    $coreOnlyTarget  = $target->core_target_sales ?? 0;
    $petcsdTarget    = $target->petcsd_target_sales ?? 0;
    $stillsTarget    = $target->stills_target_sales ?? 0;

    $totalTarget = $coreOnlyTarget + $stillsTarget;


     // Inventory rows can come from either the normalized inventories table (preferred)
     // or a legacy JSON column (inventory_rows). The view expects an array of arrays.
     $inventoryRows = [];
     if ($report && $report->inventories && $report->inventories->isNotEmpty()) {
         $inventoryRows = $report->inventories->map(function ($inv) {
             return [
                 'pack'            => (string) ($inv->pack ?? ''),
                 'product'         => (string) ($inv->product ?? ''),
                 'srp'             => (float) ($inv->srp ?? 0),
                 'actual_inv'      => (float) ($inv->actual_inv ?? 0),
                 'ads'             => (float) ($inv->ads ?? 0),
                 'booking'         => (float) ($inv->booking ?? 0),
                 'deliveries'      => (float) ($inv->deliveries ?? 0),
                 'routing_days_p5' => (float) ($inv->routing_days_p5 ?? 0),
                 'routing_days_7'  => (float) ($inv->routing_days_7 ?? 0),
             ];
         })->values()->toArray();
     } elseif ($report && is_array($report->inventory_rows)) {
         $inventoryRows = $report->inventory_rows;
     }

     // Custom tables are currently stored as JSON (custom_tables). Default to empty array.
     $customTables = ($report && is_array($report->custom_tables)) ? $report->custom_tables : [];

     return view('admin.reports.periods.create', [
         'branches' => $branches,
         'branch'   => $branch,
         'periodNo' => $periodNo,
         'date'     => $date,

        // TARGETS
        'coreOnlyTarget'  => $coreOnlyTarget,
        'petcsdTarget'    => $petcsdTarget,
        'stillsTarget'    => $stillsTarget,
        'totalTarget'     => $totalTarget,

         'report'   => $report,
         'items'    => $items,
         'inventoryRows' => $inventoryRows,
         'customTables'  => $customTables,
         'perSkuRows'    => $report?->coke_rows ?? [],
         'products' => $products,
         'isEdit'   => (bool) $report,
         'existingReportId' => $existingReportId,
         'existingReportCount' => $existingReportCount,
     ]);
 }

    /* =====================================================
       STORE (HEADER + ITEMS)
    ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'branch'      => 'required|string',
            'period_no'   => 'required|integer',
            'report_date' => 'required|date',
            'shipment_no' => ['required', 'string', 'max:255', 'regex:/\\S/'],
        ]);

        $divisionId = auth()->user()->division_id;
        $shipmentNo = trim((string) $request->shipment_no);
        $shipmentNo = $shipmentNo === '' ? null : $shipmentNo;

        $existingSameShipmentQuery = PeriodReport::where('division_id', $divisionId)
            ->where('branch', $request->branch)
            ->where('period_no', $request->period_no)
            ->whereDate('report_date', $request->report_date);

        if ($shipmentNo === null) {
            $existingSameShipmentQuery->whereNull('shipment_no');
        } else {
            $existingSameShipmentQuery->where('shipment_no', $shipmentNo);
        }

        $existingSameShipment = $existingSameShipmentQuery
            ->orderByDesc('id')
            ->first();

        if ($existingSameShipment && in_array(($existingSameShipment->status ?? 'draft'), ['submitted', 'approved'], true)) {
            return back()
                ->withInput()
                ->with('error', 'This shipment report is already submitted/approved and cannot be overwritten.');
        }

        DB::transaction(function () use ($request, $shipmentNo, $existingSameShipment) {
            $divisionId = auth()->user()->division_id;

           $target = PeriodTarget::where('branch', $request->branch)
                ->where('period_no', $request->period_no)
                ->whereDate('start_date', '<=', $request->report_date)
                ->whereDate('end_date', '>=', $request->report_date)
                ->orderBy('start_date', 'desc')
                ->first();

            $coreOnlyTarget  = $target->core_target_sales ?? 0;
            $petcsdTarget    = $target->petcsd_target_sales ?? 0;
            $stillsTarget    = $target->stills_target_sales ?? 0;

            $totalTarget = $coreOnlyTarget + $stillsTarget;
            $isTargetLocked = $target ? (int) ($target->is_locked ?? 0) : 0;

                        /* ===============================
             * 1Ã¯Â¸ÂÃ¢Æ’Â£ UPSERT HEADER (ONE DAILY REPORT PER BRANCH + PERIOD + DATE)
             * =============================== */
            $report = $existingSameShipment;

            if (!$report) {
                $report = PeriodReport::create([
                    'division_id'   => $divisionId,
                    'branch'        => $request->branch,
                    'period_no'     => $request->period_no,
                    'report_date'   => $request->report_date,
                    'shipment_no'   => $shipmentNo,
                    'status'        => 'draft',

                    // Targets (read only)
                    'core_target_sales'   => $coreOnlyTarget,
                    'petcsd_target_sales' => $petcsdTarget,
                    'stills_target_sales' => $stillsTarget,
                    'target_sales'        => $totalTarget,
                    'is_target_locked'    => $isTargetLocked,

                    // Init
                    'actual_sales'        => 0,
                    'achievement_pct'     => 0,
                    'total_variance'      => 0,
                ]);
            } else {
                $report->update([
                    'report_date'         => $request->report_date,
                    'shipment_no'         => $shipmentNo,
                    'core_target_sales'   => $coreOnlyTarget,
                    'petcsd_target_sales' => $petcsdTarget,
                    'stills_target_sales' => $stillsTarget,
                    'target_sales'        => $totalTarget,
                    'is_target_locked'    => $isTargetLocked,
                ]);

                // Replace items for this day.
                $report->items()->delete();
            }
/* ===============================
             * 3ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¸Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢Ãƒâ€ Ã¢â‚¬â„¢Ãƒâ€šÃ‚Â£ INIT ACTUALS
             * =============================== */
            $coreActual   = 0;
            $petcsdActual = 0;
            $stillsActual = 0;

            /* ===============================
             * 4ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¸Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢Ãƒâ€ Ã¢â‚¬â„¢Ãƒâ€šÃ‚Â£ SAVE ITEMS + COMPUTE
             * =============================== */
            $salesRows = $request->input('sales_items', $request->input('items', []));
            foreach ($salesRows ?? [] as $row) {

                $corePcs = (int) ($row['core_pcs'] ?? 0);
                $coreUcs = (float) ($row['core_ucs'] ?? 1);

                $iwsPcs  = (int) ($row['iws_pcs'] ?? 0);
                $iwsUcs  = (float) ($row['iws_ucs'] ?? 1);

                $rowTotal =
                    ($corePcs * $coreUcs) +
                    ($iwsPcs  * $iwsUcs);

                if (($row['category'] ?? '') === 'core') {
                    $coreActual += $rowTotal;
                } elseif (($row['category'] ?? '') === 'petcsd') {
                    $coreActual += $rowTotal;
                    $petcsdActual += $rowTotal;
                } elseif (($row['category'] ?? '') === 'stills') {
                    $stillsActual += $rowTotal;
                }

                PeriodReportItem::create([
                    'period_report_id' => $report->id,
                    'pack'             => $row['pack'],
                    'product'          => $row['product'],
                    'category'         => $row['category'] ?? null,

                    'core_pcs'       => $corePcs,
                    'core_ucs'       => $coreUcs,
                    'core_total_ucs' => $corePcs * $coreUcs,

                    'iws_pcs'        => $iwsPcs,
                    'iws_ucs'        => $iwsUcs,
                    'iws_total_ucs'  => $iwsPcs * $iwsUcs,
                ]);
            }

            /* ===============================
             * 5ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¸Ãƒâ€šÃ‚ÂÃƒÆ’Ã‚Â¢Ãƒâ€ Ã¢â‚¬â„¢Ãƒâ€šÃ‚Â£ FINAL HEADER UPDATE
             * =============================== */
            $totalActual = $coreActual + $stillsActual;
            // ÃƒÆ’Ã‚Â¢Ãƒâ€¦Ã‚Â¡Ãƒâ€šÃ‚Â ÃƒÆ’Ã‚Â¯Ãƒâ€šÃ‚Â¸Ãƒâ€šÃ‚Â PET already included in CORE

            $report->update([
                'actual_sales' => $totalActual,

                'achievement_pct' => $totalTarget > 0
                    ? round(($totalActual / $totalTarget) * 100, 2)
                    : 0,

                // Variance = remaining target (Target - Actual)
                'total_variance' => $totalTarget - $totalActual,

                /* =========================
                   CORE (core actual includes PET CSD actual)
                ========================= */
                'core_actual_sales' => $coreActual,
                'core_variance'     => $coreOnlyTarget - $coreActual,
                'core_achievement_pct' => $coreOnlyTarget > 0
                    ? round(($coreActual / $coreOnlyTarget) * 100, 2)
                    : 0,

                /* =========================
                   PET CSD (for incentive only)
                ========================= */
                'petcsd_actual_sales' => $petcsdActual,
                'petcsd_variance'     => $petcsdTarget - $petcsdActual,
                'petcsd_achievement_pct' => $petcsdTarget > 0
                    ? round(($petcsdActual / $petcsdTarget) * 100, 2)
                    : 0,

                /* =========================
                   STILLS
                ========================= */
                'stills_actual_sales' => $stillsActual,
                'stills_variance'     => $stillsTarget - $stillsActual,
                'stills_achievement_pct' => $stillsTarget > 0
                    ? round(($stillsActual / $stillsTarget) * 100, 2)
                    : 0,
            ]);

            // PER SKU (reference)
            $perSkuRows = $request->input('per_sku', []);
            if ($request->filled('per_sku_json')) {
                $decoded = json_decode($request->input('per_sku_json'), true);
                if (is_array($decoded)) {
                    $perSkuRows = $decoded;
                }
            }
            $normalizedPerSku = [];
            foreach ($perSkuRows ?? [] as $row) {
                $pack = trim((string) ($row['pack'] ?? ''));
                $product = trim((string) ($row['product'] ?? ''));
                if ($pack === '' && $product === '') {
                    continue;
                }
                $normalizedPerSku[] = [
                    'pack'       => $pack,
                    'product'    => $product,
                    'target_pcs' => (float) ($row['target_pcs'] ?? 0),
                    'target_ucs' => (float) ($row['target_ucs'] ?? 0),
                    'actual_pcs' => (float) ($row['actual_pcs'] ?? 0),
                    'actual_ucs' => (float) ($row['actual_ucs'] ?? 0),
                ];
            }
            $report->update(['coke_rows' => $normalizedPerSku]);

            // INVENTORY (optional)
            if ($request->has('inventories')) {
                $this->saveInventories($report, $request->input('inventories', []));
            }

            // ADDITIONAL CUSTOM TABLES (optional, JSON)
            if ($request->has('custom_tables')) {
                $this->saveCustomTablesJson($report, $request->input('custom_tables'));
            }
        });

        return redirect()
            ->route('admin.reports.periods.index')
            ->with('success', 'Report saved successfully.');
    }
    /* =====================================================
       SUBMIT REPORT (LOCK)
    ===================================================== */
    public function submit(PeriodReport $report)
    {
        $divisionId = auth()->user()->division_id ?? null;
        if ($report->division_id !== null && (int) $report->division_id !== (int) $divisionId) {
            abort(403);
        }

        if (($report->status ?? 'draft') === 'approved') {
            return back()->with('error', 'Approved reports cannot be submitted again.');
        }

        $report->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('admin.reports.periods.index')
            ->with('success', 'Report submitted and locked.');
    }

    /* =====================================================
       APPROVE REPORT
    ===================================================== */
    public function approve(PeriodReport $report)
    {
        $divisionId = auth()->user()->division_id ?? null;
        if ($report->division_id !== null && (int) $report->division_id !== (int) $divisionId) {
            abort(403);
        }

        if (($report->status ?? 'draft') !== 'submitted') {
            return back()->with('error', 'Only submitted reports can be approved.');
        }

        $report->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Report approved.');
    }

    /* =====================================================
       DISABLED DETAIL ACTIONS
    ===================================================== */
    public function addDetail(Request $request, PeriodReport $report)
    {
        abort(404);
    }

    public function deleteDetail(PeriodReport $report, $detail)
    {
        abort(404);
    }
    /* ===============================
        DEFAULT PRODUCT ROWS
    ================================ */
    private function defaultRows()
    {
        return [
            ['pack' => '237ml',        'product' => 'COKE',         'category' => 'core'],
            ['pack' => '237ml',        'product' => 'SPRITE',       'category' => 'core'],
            ['pack' => '190ml SWAKTO', 'product' => 'COKE',         'category' => 'core'],

            // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒâ€šÃ‚Â¥ PET CSD (counts into CORE actuals but tracked separately)
            ['pack' => '1.5 LTR x 12', 'product' => 'COKE',         'category' => 'petcsd'],

            // STILLS
            ['pack' => '330ml',        'product' => 'MINUTE MAID',  'category' => 'stills'],
            ['pack' => '500ml',        'product' => 'WILKINS PURE', 'category' => 'stills'],
        ];
    }

    /* ===============================
        SHOW (READ ONLY)
    ================================ */
    public function show(PeriodReport $report)
    {
        $report->load([
            'items',
            'inventories',
            'customTables.cells',
        ]);

        // Inventory rows can come from either the normalized inventories table (preferred)
        // or a legacy JSON column (inventory_rows). The view expects an array of arrays.
        $savedInventoryRows = [];
        if ($report->inventories && $report->inventories->isNotEmpty()) {
            $savedInventoryRows = $report->inventories->map(function ($inv) {
                return [
                    'pack'            => (string) ($inv->pack ?? ''),
                    'product'         => (string) ($inv->product ?? ''),
                    'srp'             => (float) ($inv->srp ?? 0),
                    'actual_inv'      => (float) ($inv->actual_inv ?? 0),
                    'ads'             => (float) ($inv->ads ?? 0),
                    'booking'         => (float) ($inv->booking ?? 0),
                    'deliveries'      => (float) ($inv->deliveries ?? 0),
                    'routing_days_p5' => (float) ($inv->routing_days_p5 ?? 0),
                    'routing_days_7'  => (float) ($inv->routing_days_7 ?? 0),
                ];
            })->values()->toArray();
        } elseif (is_array($report->inventory_rows)) {
            $savedInventoryRows = $report->inventory_rows;
        }

        // Build complete inventory list using active products + extras from report items/inventories.
        $products = Product::where('status', 'active')
            ->orderBy('category')
            ->orderBy('pack_size')
            ->orderBy('product_name')
            ->get();

        $existingKeys = $products->map(function ($p) {
            return (string) ($p->pack_size ?? '') . '|' . (string) ($p->product_name ?? '');
        })->flip();

        $extraFromItems = $report->items->filter(function ($i) use ($existingKeys) {
            $key = (string) ($i->pack ?? '') . '|' . (string) ($i->product ?? '');
            return !$existingKeys->has($key);
        })->map(function ($i) {
            return Product::make([
                'pack_size'    => $i->pack,
                'product_name' => $i->product,
                'category'     => $i->category,
                'srp'          => 0,
                'status'       => 'inactive',
            ]);
        });

        $existingKeys = $existingKeys->merge(
            $extraFromItems->map(function ($p) {
                return (string) ($p->pack_size ?? '') . '|' . (string) ($p->product_name ?? '');
            })->flip()
        );

        $extraFromInventory = collect($savedInventoryRows)->filter(function ($row) use ($existingKeys) {
            $key = (string) ($row['pack'] ?? '') . '|' . (string) ($row['product'] ?? '');
            return !$existingKeys->has($key);
        })->map(function ($row) {
            return Product::make([
                'pack_size'    => $row['pack'] ?? '',
                'product_name' => $row['product'] ?? '',
                'category'     => '',
                'srp'          => (float) ($row['srp'] ?? 0),
                'status'       => 'inactive',
            ]);
        });

        $products = $products->concat($extraFromItems)->concat($extraFromInventory)
            ->sortBy([
                fn ($p) => (string) ($p->category ?? ''),
                fn ($p) => (string) ($p->pack_size ?? ''),
                fn ($p) => (string) ($p->product_name ?? ''),
            ])->values();

        $savedMap = collect($savedInventoryRows)->mapWithKeys(function ($row) {
            $key = (string) ($row['pack'] ?? '') . '|' . (string) ($row['product'] ?? '');
            return [$key => $row];
        });

        $inventoryRows = $products->map(function ($p) use ($savedMap) {
            $pack = (string) ($p->pack_size ?? '');
            $product = (string) ($p->product_name ?? '');
            $key = $pack . '|' . $product;
            $saved = $savedMap->get($key, []);

            return [
                'pack'            => $pack,
                'product'         => $product,
                'srp'             => (float) ($saved['srp'] ?? $p->srp ?? 0),
                'actual_inv'      => (float) ($saved['actual_inv'] ?? 0),
                'ads'             => (float) ($saved['ads'] ?? 0),
                'booking'         => (float) ($saved['booking'] ?? 0),
                'deliveries'      => (float) ($saved['deliveries'] ?? 0),
                'routing_days_p5' => (float) ($saved['routing_days_p5'] ?? 0),
                'routing_days_7'  => (float) ($saved['routing_days_7'] ?? 0),
            ];
        })->values()->toArray();

        // Per SKU rows (use saved report data, fallback to zero for all products)
        $savedPerSkuMap = collect(is_array($report->coke_rows) ? $report->coke_rows : [])
            ->mapWithKeys(function ($row) {
                $key = (string) ($row['pack'] ?? '') . '|' . (string) ($row['product'] ?? '');
                return [$key => $row];
            });

        $perSkuRows = $products->map(function ($p) use ($savedPerSkuMap) {
            $pack = (string) ($p->pack_size ?? '');
            $product = (string) ($p->product_name ?? '');
            $key = $pack . '|' . $product;
            $saved = $savedPerSkuMap->get($key, []);

            return [
                'pack'       => $pack,
                'product'    => $product,
                'target_pcs' => (float) ($saved['target_pcs'] ?? 0),
                'target_ucs' => (float) ($saved['target_ucs'] ?? 0),
                'actual_pcs' => (float) ($saved['actual_pcs'] ?? 0),
                'actual_ucs' => (float) ($saved['actual_ucs'] ?? 0),
            ];
        })->values()->toArray();

        // Custom tables are currently stored as JSON (custom_tables). Default to empty array.
        $customTables = is_array($report->custom_tables) ? $report->custom_tables : [];

        // Show view should render read-only inputs.
        $isView = true;

        // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒâ€šÃ‚Â¥ ACTUAL SALES = CORE (incl PET) + STILLS
        $coreActual   = $report->core_actual_sales ?? 0;
        $stillsActual = $report->stills_actual_sales ?? 0;

        $actual = $coreActual + $stillsActual;

        $achievement = $report->target_sales > 0
            ? round(($actual / $report->target_sales) * 100, 2)
            : 0;

        $variance = ($report->target_sales ?? 0) - $actual;

        return view('admin.reports.periods.show', compact(
            'report',
            'actual',
            'achievement',
            'variance',
            'inventoryRows',
            'customTables',
            'perSkuRows',
            'isView'
        ));
    }
    /* ===============================
        EDIT (EDITABLE VIEW)
    ================================ */
    public function edit(PeriodReport $report)
    {
        $divisionId = auth()->user()->division_id;
        if ($report->division_id !== null && (int) $report->division_id !== (int) $divisionId) {
            abort(403);
        }

        if (in_array(($report->status ?? 'draft'), ['submitted', 'approved'], true)) {
            return redirect()
                ->route('admin.reports.periods.show', $report->id)
                ->with('error', 'Submitted/approved reports are locked and cannot be edited.');
        }

        // Use the full create view for editing so inventory + custom tables are editable too.
        $report->load([
            'items',
            'inventories',
        ]);

        $branches = ['Solsona', 'Laoag', 'Batac'];
        $branch   = $report->branch;
        $periodNo = $report->period_no;
        $date     = $report->report_date?->toDateString() ?? now()->toDateString();

        // PRODUCTS (active + anything already saved in this report so values never "disappear")
        $products = Product::where('status', 'active')
            ->orderBy('category')
            ->orderBy('pack_size')
            ->orderBy('product_name')
            ->get();

        $existingKeys = $products->map(function ($p) {
            return (string) ($p->pack_size ?? '') . '|' . (string) ($p->product_name ?? '');
        })->flip();

        $extras = $report->items->filter(function ($i) use ($existingKeys) {
            $key = (string) ($i->pack ?? '') . '|' . (string) ($i->product ?? '');
            return !$existingKeys->has($key);
        })->map(function ($i) {
            return Product::make([
                'pack_size'    => $i->pack,
                'product_name' => $i->product,
                'category'     => $i->category,
                'srp'          => 0,
                'ucs'          => max((float) ($i->core_ucs ?? 0), (float) ($i->iws_ucs ?? 0)),
                'status'       => 'inactive',
            ]);
        });

        $products = $products->concat($extras)
            ->sortBy([
                fn ($p) => (string) ($p->category ?? ''),
                fn ($p) => (string) ($p->pack_size ?? ''),
                fn ($p) => (string) ($p->product_name ?? ''),
            ])->values();

        // TARGETS FOR THIS REPORT DATE
        $target = PeriodTarget::where('branch', $branch)
            ->where('period_no', $periodNo)
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('start_date', 'desc')
            ->first();

        $coreOnlyTarget  = $target->core_target_sales ?? 0;
        $petcsdTarget    = $target->petcsd_target_sales ?? 0;
        $stillsTarget    = $target->stills_target_sales ?? 0;

        $totalTarget = $coreOnlyTarget + $stillsTarget;

        // INVENTORY ROWS
        $inventoryRows = [];
        if ($report->inventories && $report->inventories->isNotEmpty()) {
            $inventoryRows = $report->inventories->map(function ($inv) {
                return [
                    'pack'            => (string) ($inv->pack ?? ''),
                    'product'         => (string) ($inv->product ?? ''),
                    'srp'             => (float) ($inv->srp ?? 0),
                    'actual_inv'      => (float) ($inv->actual_inv ?? 0),
                    'ads'             => (float) ($inv->ads ?? 0),
                    'booking'         => (float) ($inv->booking ?? 0),
                    'deliveries'      => (float) ($inv->deliveries ?? 0),
                    'routing_days_p5' => (float) ($inv->routing_days_p5 ?? 0),
                    'routing_days_7'  => (float) ($inv->routing_days_7 ?? 0),
                ];
            })->values()->toArray();
        } elseif (is_array($report->inventory_rows)) {
            $inventoryRows = $report->inventory_rows;
        }

        $customTables = is_array($report->custom_tables) ? $report->custom_tables : [];

        return view('admin.reports.periods.create', [
            'branches' => $branches,
            'branch'   => $branch,
            'periodNo' => $periodNo,
            'date'     => $date,

            'coreOnlyTarget'  => $coreOnlyTarget,
            'petcsdTarget'    => $petcsdTarget,
            'stillsTarget'    => $stillsTarget,
            'totalTarget'     => $totalTarget,

            'report'        => $report,
            'items'         => $report->items,
            'inventoryRows' => $inventoryRows,
            'customTables'  => $customTables,
            'perSkuRows'    => $report->coke_rows ?? [],
            'products'      => $products,
            'isEdit'        => true,
            'existingReportId' => null,
        ]);
    }

    /* ===============================
        UPDATE (EDIT MODE SAVE)
    ================================ */
    public function update(Request $request, PeriodReport $report)
    {
        $request->validate([
            'report_date' => 'required|date',
            'shipment_no' => ['required', 'string', 'max:255', 'regex:/\\S/'],
        ]);

        $divisionId = auth()->user()->division_id;
        if ($report->division_id !== null && (int) $report->division_id !== (int) $divisionId) {
            abort(403);
        }

        if (in_array(($report->status ?? 'draft'), ['submitted', 'approved'], true)) {
            return back()->with('error', 'Submitted/approved reports are locked and cannot be edited.');
        }

        $reportDate = (string) ($request->report_date ?? $report->report_date?->toDateString() ?? now()->toDateString());
        $shipmentNo = trim((string) $request->shipment_no);
        $shipmentNo = $shipmentNo === '' ? null : $shipmentNo;

        $conflictQuery = PeriodReport::where('division_id', $divisionId)
            ->where('branch', $report->branch)
            ->where('period_no', $report->period_no)
            ->whereDate('report_date', $reportDate)
            ->where('id', '!=', $report->id);

        if ($shipmentNo === null) {
            $conflictQuery->whereNull('shipment_no');
        } else {
            $conflictQuery->where('shipment_no', $shipmentNo);
        }

        $conflictingReport = $conflictQuery->orderByDesc('id')->first();
        if ($conflictingReport) {
            $isLockedConflict = in_array(($conflictingReport->status ?? 'draft'), ['submitted', 'approved'], true);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $isLockedConflict
                        ? 'Another submitted/approved report already uses this shipment number for the same date.'
                        : 'Another draft report already uses this shipment number for the same date. Please edit that report instead.'
                );
        }

        DB::transaction(function () use ($request, $report, $reportDate, $shipmentNo) {
 
           $target = PeriodTarget::where('branch', $report->branch)
                 ->where('period_no', $report->period_no)
                 ->whereDate('start_date', '<=', $reportDate)
                 ->whereDate('end_date', '>=', $reportDate)
                 ->orderBy('start_date', 'desc')
                 ->first();

            $coreOnlyTarget  = $target->core_target_sales ?? 0;
            $petcsdTarget    = $target->petcsd_target_sales ?? 0;
            $stillsTarget    = $target->stills_target_sales ?? 0;

            $totalTarget = $coreOnlyTarget + $stillsTarget;
            $isTargetLocked = $target ? (int) ($target->is_locked ?? 0) : 0;

             // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒÂ¢Ã¢â€šÂ¬Ã…Â¾ UPDATE HEADER TARGETS (NO USER INPUT)
             $report->update([
                 'report_date'        => $reportDate,
                 'shipment_no'        => $shipmentNo,
                 'core_target_sales'   => $coreOnlyTarget,
                 'petcsd_target_sales' => $petcsdTarget,
                 'stills_target_sales' => $stillsTarget,
                 'target_sales'        => $totalTarget,
                 'is_target_locked'    => $isTargetLocked,
 
             ]);

            // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒâ€šÃ‚Â RESET ITEMS
            $report->items()->delete();

            $coreActual   = 0;
            $petcsdActual = 0;
            $stillsActual = 0;

            /* =========================
               SAVE ITEMS + COMPUTE
            ========================= */
            $salesRows = $request->input('sales_items', $request->input('items', []));
            foreach ($salesRows ?? [] as $row) {

                $corePcs = (int) ($row['core_pcs'] ?? 0);
                $coreUcs = (float) ($row['core_ucs'] ?? 1);

                $iwsPcs  = (int) ($row['iws_pcs'] ?? 0);
                $iwsUcs  = (float) ($row['iws_ucs'] ?? 1);

                $rowTotal =
                    ($corePcs * $coreUcs) +
                    ($iwsPcs  * $iwsUcs);

                if (($row['category'] ?? '') === 'core') {
                    $coreActual += $rowTotal;
                } elseif (($row['category'] ?? '') === 'petcsd') {
                    $coreActual += $rowTotal;
                    $petcsdActual += $rowTotal;
                } elseif (($row['category'] ?? '') === 'stills') {
                    $stillsActual += $rowTotal;
                }

                $report->items()->create([
                    'pack'             => $row['pack'],
                    'product'          => $row['product'],
                    'category'         => $row['category'] ?? null,
                    'core_pcs'         => $corePcs,
                    'core_ucs'         => $coreUcs,
                    'core_total_ucs'   => $corePcs * $coreUcs,
                    'iws_pcs'          => $iwsPcs,
                    'iws_ucs'          => $iwsUcs,
                    'iws_total_ucs'    => $iwsPcs * $iwsUcs,
                ]);
            }

            /* =========================
               FINAL KPI UPDATE
            ========================= */
            $totalActual = $coreActual + $stillsActual;

            $report->update([
                'actual_sales' => $totalActual,
                'achievement_pct' => $totalTarget > 0
                    ? round(($totalActual / $totalTarget) * 100, 2)
                    : 0,
                // Variance = remaining target (Target - Actual)
                'total_variance' => $totalTarget - $totalActual,

                /* =========================
                   CORE (core actual includes PET CSD actual)
                ========================= */
                'core_actual_sales' => $coreActual,
                'core_variance'     => $coreOnlyTarget - $coreActual,
                'core_achievement_pct' => $coreOnlyTarget > 0
                    ? round(($coreActual / $coreOnlyTarget) * 100, 2)
                    : 0,

                /* =========================
                   PET CSD (for incentive only)
                ========================= */
                'petcsd_actual_sales' => $petcsdActual,
                'petcsd_variance'     => $petcsdTarget - $petcsdActual,
                'petcsd_achievement_pct' => $petcsdTarget > 0
                    ? round(($petcsdActual / $petcsdTarget) * 100, 2)
                    : 0,

                /* =========================
                   STILLS
                ========================= */
                'stills_actual_sales' => $stillsActual,
                'stills_variance'     => $stillsTarget - $stillsActual,
                'stills_achievement_pct' => $stillsTarget > 0
                    ? round(($stillsActual / $stillsTarget) * 100, 2)
                    : 0,
            ]);

            // PER SKU (reference)
            $perSkuRows = $request->input('per_sku', []);
            if ($request->filled('per_sku_json')) {
                $decoded = json_decode($request->input('per_sku_json'), true);
                if (is_array($decoded)) {
                    $perSkuRows = $decoded;
                }
            }
            $normalizedPerSku = [];
            foreach ($perSkuRows ?? [] as $row) {
                $pack = trim((string) ($row['pack'] ?? ''));
                $product = trim((string) ($row['product'] ?? ''));
                if ($pack === '' && $product === '') {
                    continue;
                }
                $normalizedPerSku[] = [
                    'pack'       => $pack,
                    'product'    => $product,
                    'target_pcs' => (float) ($row['target_pcs'] ?? 0),
                    'target_ucs' => (float) ($row['target_ucs'] ?? 0),
                    'actual_pcs' => (float) ($row['actual_pcs'] ?? 0),
                    'actual_ucs' => (float) ($row['actual_ucs'] ?? 0),
                ];
            }
            $report->update(['coke_rows' => $normalizedPerSku]);

            // INVENTORY (optional; only replace when payload is present)
            if ($request->has('inventories')) {
                $this->saveInventories($report, $request->input('inventories', []));
            }

            // ADDITIONAL CUSTOM TABLES (optional, JSON; only replace when payload is present)
            if ($request->has('custom_tables')) {
                $this->saveCustomTablesJson($report, $request->input('custom_tables'));
            }
        });

        return redirect()
            ->route('admin.reports.periods.show', $report->id)
            ->with('success', 'Report updated successfully.');
    }

    private function saveInventories(PeriodReport $report, array $inventories): void
    {
        $report->inventories()->delete();

        foreach ($inventories as $row) {
            $pack = trim((string) ($row['pack'] ?? ''));
            $product = trim((string) ($row['product'] ?? ''));

            $srp = (float) ($row['srp'] ?? 0);
            $actualInv = (float) ($row['actual_inv'] ?? 0);
            $ads = (float) ($row['ads'] ?? 0);
            $booking = (float) ($row['booking'] ?? 0);
            $deliveries = (float) ($row['deliveries'] ?? 0);
            $routingP5 = (float) ($row['routing_days_p5'] ?? 0);
            $routing7 = (float) ($row['routing_days_7'] ?? 0);

            if ($pack === '' || $product === '') {
                continue;
            }

            // Avoid storing completely empty rows to keep the table small.
            $hasAny =
                $srp != 0.0 ||
                $actualInv != 0.0 ||
                $ads != 0.0 ||
                $booking != 0.0 ||
                $deliveries != 0.0 ||
                $routingP5 != 0.0 ||
                $routing7 != 0.0;

            if (!$hasAny) {
                continue;
            }

            $report->inventories()->create([
                'pack'            => $pack,
                'product'         => $product,
                'srp'             => $srp,
                'actual_inv'      => $actualInv,
                'ads'             => $ads,
                'booking'         => $booking,
                'deliveries'      => $deliveries,
                'routing_days_p5' => $routingP5,
                'routing_days_7'  => $routing7,
            ]);
        }
    }

    private function saveCustomTablesJson(PeriodReport $report, $payload): void
    {
        if (is_array($payload)) {
            $report->update(['custom_tables' => $payload]);
            return;
        }

        $decoded = [];
        if (is_string($payload) && trim($payload) !== '') {
            $tmp = json_decode($payload, true);
            if (is_array($tmp)) {
                $decoded = $tmp;
            }
        }

        $report->update(['custom_tables' => $decoded]);
    }
    /* ===============================
        SHOW FULL (READ ONLY ÃƒÆ’Ã‚Â¢ÃƒÂ¢Ã¢â‚¬Å¡Ã‚Â¬ÃƒÂ¢Ã¢â€šÂ¬Ã…â€œ FULL VIEW)
    ================================ */
    public function showFull(PeriodReport $report)
    {
        $report->load([
            'items',
            'inventories',
            'customTables.cells',
        ]);

        // ÃƒÆ’Ã‚Â°Ãƒâ€¦Ã‚Â¸ÃƒÂ¢Ã¢â€šÂ¬Ã‚ÂÃƒâ€šÃ‚Â¥ ACTUAL = CORE (incl PET) + STILLS
        $coreActual   = $report->core_actual_sales ?? 0;
        $stillsActual = $report->stills_actual_sales ?? 0;

        $actual = $coreActual + $stillsActual;

        $achievement = $report->target_sales > 0
            ? round(($actual / $report->target_sales) * 100, 2)
            : 0;

        $variance = ($report->target_sales ?? 0) - $actual;

        return view('admin.reports.periods.show-full', compact(
            'report',
            'actual',
            'achievement',
            'variance'
        ));
    }

    /* ===============================
        DELETE REPORT
    ================================ */
    public function destroy(PeriodReport $report)
    {
        DB::transaction(function () use ($report) {
            $report->items()->delete();
            $report->delete();
        });

        return redirect()
            ->route('admin.reports.periods.index')
            ->with('success', 'Report deleted successfully.');
    }
}



