<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeriodTarget;
use App\Models\PeriodReport;
use App\Models\Product;

class PeriodTargetController extends Controller
{
    /**
     * Show create form
     */
    public function create()
    {
        $branches = ['Laoag', 'Batac', 'Solsona'];
        $products = Product::where('status', 'active')
            ->orderBy('category')
            ->orderBy('pack_size')
            ->orderBy('product_name')
            ->get();

        return view('admin.period_targets.create', compact('branches', 'products'));
    }

    /**
     * Store target (branch + period + date-range safe)
     */
    public function store(Request $request)
    {
        $request->validate([
            'branch'              => 'required|in:Laoag,Batac,Solsona',
            'period_no'           => 'required|integer|min:1|max:12',

            'core_target_sales'   => 'required|numeric|min:0',
            'petcsd_target_sales' => 'required|numeric|min:0',
            'stills_target_sales' => 'required|numeric|min:0',

            'start_date'          => 'required|date',
            'end_date'            => 'required|date|after_or_equal:start_date',

            'sku_targets'                 => 'nullable|array',
            'sku_targets.*.target_pcs'    => 'nullable|numeric|min:0',
            'sku_targets.*.target_ucs'    => 'nullable|numeric|min:0',
        ]);

        // Per-SKU targets are stored for reference only (do not override manual targets).
        $skuTargets = $request->input('sku_targets', []);
        $hasSkuTargets = false;
        $normalizedSkuTargets = [];

        foreach ($skuTargets as $productId => $row) {
            $pcs = isset($row['target_pcs']) ? (float) $row['target_pcs'] : 0.0;
            $ucs = isset($row['target_ucs']) ? (float) $row['target_ucs'] : 0.0;
            $rowTotal = $pcs * $ucs;

            // Treat per-SKU table as "used" only when it produces a positive total.
            if ($rowTotal > 0) {
                $hasSkuTargets = true;
            }

            // Only persist rows that contribute to the totals (keeps payload reasonable).
            if ($rowTotal > 0) {
                $normalizedSkuTargets[(string) $productId] = [
                    'target_pcs' => $pcs,
                    'target_ucs' => $ucs,
                ];
            }
        }

        $coreTarget = (float) $request->core_target_sales;
        $petcsdTarget = (float) $request->petcsd_target_sales;
        $stillsTarget = (float) $request->stills_target_sales;

        /**
         * Prevent overlapping targets
         * Rule: same branch + same period + overlapping date range
         */
        $overlap = PeriodTarget::where('branch', $request->branch)
            ->where('period_no', $request->period_no)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                  ->orWhere(function ($q2) use ($request) {
                      $q2->where('start_date', '<=', $request->start_date)
                         ->where('end_date', '>=', $request->end_date);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors([
                    'start_date' => 'Target dates overlap with an existing target.',
                ])
                ->withInput();
        }

        /**
         * Save target
         * NOTE:
         * - core_target_sales = CORE only
         * - petcsd_target_sales = PET CSD only
         * - Core TOTAL (for reporting) = core_target_sales + petcsd_target_sales
         */
        $savedTarget = PeriodTarget::create([
            'branch'              => $request->branch,
            'period_no'           => $request->period_no,

            'core_target_sales'   => $coreTarget,
            'petcsd_target_sales' => $petcsdTarget,
            'stills_target_sales' => $stillsTarget,

            'start_date'          => $request->start_date,
            'end_date'            => $request->end_date,
            'is_locked'           => 1,

            'sku_targets'         => $hasSkuTargets ? $normalizedSkuTargets : null,
        ]);

        // Keep existing period reports aligned with the saved target rule:
        // overall target_sales = core (without PET) + stills.
        $coreTotalForReport = $coreTarget + $petcsdTarget;
        $overallTarget = $coreTarget + $stillsTarget;

        PeriodReport::where('branch', $request->branch)
            ->where('period_no', $request->period_no)
            ->whereDate('report_date', '>=', $savedTarget->start_date)
            ->whereDate('report_date', '<=', $savedTarget->end_date)
            ->update([
                'core_target_sales'   => $coreTotalForReport,
                'petcsd_target_sales' => $petcsdTarget,
                'stills_target_sales' => $stillsTarget,
                'target_sales'        => $overallTarget,
                'is_target_locked'    => 1,
            ]);

        return redirect()
            ->route('admin.period-targets.index')
            ->with('success', 'Target saved successfully.');
    }

    /**
     * AJAX – get target by branch + period + report date
     */
    public function show(Request $request)
    {
        $request->validate([
            'branch'      => 'required|in:Laoag,Batac,Solsona',
            'period_no'   => 'required|integer|min:1|max:12',
            'report_date' => 'required|date',
        ]);

        $target = PeriodTarget::where('branch', $request->branch)
            ->where('period_no', $request->period_no)
            ->whereDate('start_date', '<=', $request->report_date)
            ->whereDate('end_date', '>=', $request->report_date)
            ->orderBy('start_date', 'desc')
            ->first();

        if (! $target) {
            return response()->json(null);
        }

        return response()->json([
            // CORE only (without PET)
            'core_only_target'  => $target->core_target_sales,

            // PET CSD only
            'petcsd_target'     => $target->petcsd_target_sales,

            // CORE TOTAL = CORE + PET
            'core_total_target' => $target->core_target_sales + $target->petcsd_target_sales,

            // STILLS
            'stills_target'     => $target->stills_target_sales,

            'locked'            => $target->is_locked,
        ]);
    }

    /**
     * Index – list all targets
     */
    public function index()
    {
        $targets = PeriodTarget::latest()->get();

        return view('admin.period_targets.index', compact('targets'));
    }

    /**
     * Delete a target row
     */
    public function destroy(PeriodTarget $target)
    {
        $target->delete();

        return redirect()
            ->route('admin.period-targets.index')
            ->with('success', 'Target deleted successfully.');
    }
}
