<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CutoffPeriod;
use App\Models\PeriodReport;
use App\Models\DailyCokeSales;

use App\Models\Receipt;
use App\Models\Remittance;
use App\Models\Receivable;
use App\Models\Borrower;
use App\Models\ReceiptItem;
use App\Models\RemittanceItem;
use App\Models\ReceivableItem;
use App\Models\BorrowerItem;
use App\Models\Bank;
use App\Models\Division;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; 
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\PeriodTarget;
use App\Models\Product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;


class ConsolidatedReportController extends Controller
{
    /* ====================================================
       DELETE PERIOD REPORT
    ==================================================== */
    public function destroyPeriodReport(PeriodReport $report)
    {
        $divisionId = auth()->user()->division_id;

        // Prevent deleting other division's reports (some legacy rows may have null division_id).
        if ($report->division_id !== null && (int) $report->division_id !== (int) $divisionId) {
            abort(403);
        }

        if (in_array(($report->status ?? 'draft'), ['submitted', 'approved'], true)) {
            return back()->with('error', 'You cannot delete a submitted/approved report.');
        }

        DB::transaction(function () use ($report) {
            // Delete dependent rows to avoid FK issues.
            $report->items()->delete();
            $report->inventories()->delete();

            $report->customTables()->with('cells')->get()->each(function ($t) {
                $t->cells()->delete();
            });
            $report->customTables()->delete();

            $report->delete();
        });

        return back()->with('success', 'Period report deleted.');
    }

 /* ====================================================
   CONSOLIDATED DASHBOARD
==================================================== */
public function dashboard(Request $request)
{
    /* ====================================================
       INITIAL SETUP (MUST BE FIRST)
    ==================================================== */
    $currentCutoff = CutoffPeriod::query()
        ->orderByDesc('start_date')
        ->first();

    if (!$currentCutoff) {
        $currentCutoff = new CutoffPeriod([
            'period_number' => (int) now()->month,
            'start_date' => now()->startOfMonth()->toDateString(),
            'end_date' => now()->endOfMonth()->toDateString(),
            'rt_days' => (int) now()->endOfMonth()->day,
        ]);
    }

    $defaultPeriod = max(1, min(12, (int) ($currentCutoff->period_no ?: now()->month)));

    /* ====================================================
       FORCE DEFAULT FILTERS (ðŸ”¥ THIS FIXES EVERYTHING)
    ==================================================== */
    if (!$request->hasAny(['branch', 'period_from', 'period_to'])) {
        return redirect()->route('admin.consolidated.index', [
            'branch'      => 'All',
            'period_from'=> $defaultPeriod,
            'period_to'  => $defaultPeriod,
        ]);
    }

    /* ====================================================
       NOW SAFE TO USE REQUEST VALUES
    ==================================================== */
    $branch     = $request->branch ?? 'All';
    $divisionId = $request->division_id ?? auth()->user()->division_id;
    $divisions = Division::orderBy('division_name')->get();

    /* ====================================================
       PERIOD RANGE (SOURCE OF TRUTH)
    ==================================================== */
    $periodFrom = $request->filled('period_from')
        ? (int) $request->period_from
        : $defaultPeriod;

    $periodTo = $request->filled('period_to')
        ? (int) $request->period_to
        : $defaultPeriod;

        /* ====================================================
        TARGETS (SINGLE SOURCE OF TRUTH)
        ==================================================== */
        $targetQuery = PeriodTarget::whereBetween('period_no', [$periodFrom, $periodTo])
            ->when($branch !== 'All', fn ($q) => $q->where('branch', $branch))
            ->get();

        // RAW TARGETS
        $coreOnlyTarget = $targetQuery->sum('core_target_sales')   ?? 0;
        $petcsdTarget = $targetQuery->sum('petcsd_target_sales') ?? 0;
        $stillsTarget = $targetQuery->sum('stills_target_sales') ?? 0;
        $coreTarget = $coreOnlyTarget;

        // Business rule: overall target excludes PET CSD.
        $targetSales = $coreOnlyTarget + $stillsTarget;


    /* ====================================================
       TARGETS (SINGLE SOURCE OF TRUTH)
    ==================================================== */
   // $targetRows = PeriodTarget::where('period_no', $periodFrom)->get();

    //if ($branch !== 'All') {
    //$targetRows = $targetRows->where('branch', $branch);
//}



    //$coreTarget   = $targetRows->sum('core_target_sales')   ?? 0;
    //$petcsdTarget = $targetRows->sum('petcsd_target_sales') ?? 0;
    //$stillsTarget = $targetRows->sum('stills_target_sales') ?? 0;

    //$coreTarget   = max(0, $coreTarget);
    //$petcsdTarget = max(0, $petcsdTarget);
    //$stillsTarget = max(0, $stillsTarget);

    //$targetSales = $coreTarget + $petcsdTarget + $stillsTarget;

    $coreTargetForm   = 0;
    $petcsdTargetForm = 0;
    $stillsTargetForm = 0;
    $isLocked = false;

   // if ($targetRows->count() > 0) {
       // $coreTargetForm   = $coreTarget;
       // $petcsdTargetForm = $petcsdTarget;
        //$stillsTargetForm = $stillsTarget;
      //  $isLocked = true;
   // }

    /* ====================================================
       MAP PERIOD â†’ DATE
    ==================================================== */
    $start = $currentCutoff->start_date ?: now()->startOfMonth()->toDateString();
    $end   = $currentCutoff->end_date ?: now()->endOfMonth()->toDateString();

    /* ====================================================
       PERIOD REPORTS
    ==================================================== */
    $reports = PeriodReport::with('items')
    ->when($divisionId, function ($q) use ($divisionId) {
        $q->where(function ($dq) use ($divisionId) {
            $dq->where('division_id', $divisionId)
               ->orWhereNull('division_id'); // Include legacy period reports without division assignment.
        });
    })
    ->when($branch !== 'All', fn ($q) => $q->where('branch', $branch))
    ->whereBetween('period_no', [$periodFrom, $periodTo])
    ->get();

    /* ====================================================
       CATEGORY KPI (ACTUALS)
    ==================================================== */
    $coreActual   = 0;
    $petcsdActual = 0;
    $stillsActual = 0;

    $normalize = fn ($v) => strtolower(str_replace(' ', '', $v ?? ''));

    foreach ($reports as $report) {
        $report->loadMissing('items');

        foreach ($report->items as $item) {
            $cat = $normalize($item->category);
            $amount = ($item->core_total_ucs ?? 0) + ($item->iws_total_ucs ?? 0);

            if ($cat === 'core' || $cat === 'petcsd') {
                $coreActual += $amount;
            }

            if ($cat === 'petcsd') {
                $petcsdActual += $amount;
            }

            if ($cat === 'stills') {
                $stillsActual += $amount;
            }
        }
    }

    /* ====================================================
       OVERALL SALES
    ==================================================== */
   $actualSales = $reports->flatMap->items->sum(fn ($i) =>
    ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
);

    $percentage = $targetSales > 0
        ? round(($actualSales / $targetSales) * 100, 2)
        : 0;

    /* ====================================================
       AVERAGE SALES (ROUTING DAYS BASED)
    ==================================================== */
    // Average section should be based on one period for clarity:
    // use selected Period To (e.g., Period 2), not cumulative period range.
    $averagePeriodNo = $periodTo;

    $targetRowsForAverage = $targetQuery->where('period_no', $averagePeriodNo);
    $averageTargetBasisSales = (float) $targetRowsForAverage->sum(function ($t) {
        return (float) ($t->core_target_sales ?? 0) + (float) ($t->stills_target_sales ?? 0);
    });

    $reportsForAverage = $reports->where('period_no', $averagePeriodNo);
    $averageActualBasisSales = (float) $reportsForAverage->flatMap->items->sum(function ($i) {
        return (float) ($i->core_total_ucs ?? 0) + (float) ($i->iws_total_ucs ?? 0);
    });

    // Routing days from Period Target Effective From/To for the basis period,
    // excluding Sundays. For "All" branch, use one shared period count (max row days).
    $routingDays = (int) $targetRowsForAverage
        ->filter(fn ($t) => !empty($t->start_date) && !empty($t->end_date))
        ->map(fn ($t) => $this->countRoutingDaysFromTargetRange($t->start_date, $t->end_date))
        ->max();

    // Representative basis period dates (for display + remaining days).
    $averagePeriodStart = null;
    $averagePeriodEnd = null;
    if ($targetRowsForAverage->isNotEmpty()) {
        $starts = $targetRowsForAverage
            ->pluck('start_date')
            ->filter()
            ->map(fn ($d) => $d instanceof Carbon ? $d->copy()->startOfDay() : Carbon::parse($d)->startOfDay())
            ->sortBy(fn ($d) => $d->timestamp)
            ->values();
        $ends = $targetRowsForAverage
            ->pluck('end_date')
            ->filter()
            ->map(fn ($d) => $d instanceof Carbon ? $d->copy()->startOfDay() : Carbon::parse($d)->startOfDay())
            ->sortByDesc(fn ($d) => $d->timestamp)
            ->values();

        $averagePeriodStart = $starts->first();
        $averagePeriodEnd = $ends->first();
    }

    if ($routingDays <= 0) {
        $routingDays = (int) ($currentCutoff->rt_days ?? 0);
    }
    if ($routingDays <= 0) {
        try {
            $routingDays = max(
                1,
                Carbon::parse($start)->diffInDays(Carbon::parse($end)) + 1
            );
        } catch (\Throwable $e) {
            $routingDays = max(1, (int) now()->daysInMonth);
        }
    }

    if (!$averagePeriodStart) {
        try {
            $averagePeriodStart = Carbon::parse($start)->startOfDay();
        } catch (\Throwable $e) {
            $averagePeriodStart = now()->startOfMonth();
        }
    }
    if (!$averagePeriodEnd) {
        try {
            $averagePeriodEnd = Carbon::parse($end)->startOfDay();
        } catch (\Throwable $e) {
            $averagePeriodEnd = now()->endOfMonth();
        }
    }

    $today = now()->startOfDay();
    if ($today->gt($averagePeriodEnd)) {
        $routingDaysLeft = 0;
    } else {
        $leftStart = $today->lt($averagePeriodStart) ? $averagePeriodStart->copy() : $today;
        $routingDaysLeft = $this->countRoutingDaysFromTargetRange($leftStart, $averagePeriodEnd);
    }

    $averageTargetSales = $routingDays > 0 ? round($averageTargetBasisSales / $routingDays, 2) : 0;
    $averageActualSales = $routingDays > 0 ? round($averageActualBasisSales / $routingDays, 2) : 0;

    /* ====================================================
       VARIANCE & ACHIEVEMENT
    ==================================================== */
    $coreVariance   = $coreActual   - $coreTarget;
    $petcsdVariance = $petcsdActual - $petcsdTarget;
    $stillsVariance = $stillsActual - $stillsTarget;

    $coreAchievement = $coreTarget > 0
        ? round(($coreActual / $coreTarget) * 100, 2)
        : 0;

    $petcsdAchievement = $petcsdTarget > 0
        ? round(($petcsdActual / $petcsdTarget) * 100, 2)
        : 0;

    $stillsAchievement = $stillsTarget > 0
        ? round(($stillsActual / $stillsTarget) * 100, 2)
        : 0;

    /* ====================================================
       BRANCH PIE CHART DATA (ACTUAL SALES SHARE)
    ==================================================== */
    $branchList = ['Solsona', 'Laoag', 'Batac'];
    $branchReportsAll = PeriodReport::with('items')
        ->when($divisionId, function ($q) use ($divisionId) {
            $q->where(function ($dq) use ($divisionId) {
                $dq->where('division_id', $divisionId)
                   ->orWhereNull('division_id');
            });
        })
        ->whereBetween('period_no', [$periodFrom, $periodTo])
        ->get();

    $branchPieLabels = $branchList;
    $branchPieValues = [];
    foreach ($branchList as $b) {
        $sum = $branchReportsAll
            ->where('branch', $b)
            ->flatMap->items
            ->sum(fn ($i) => ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0));
        $branchPieValues[] = round((float) $sum, 2);
    }

    /* ====================================================
       CHART DATA
    ==================================================== */
    $periodReports = $reports;

    $chartLabels = $periodReports->pluck('period_no')->toArray();

    $chartActual = $periodReports->map(fn ($r) =>
        $r->items->sum(fn ($i) =>
            ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
        )
    )->toArray();

    $chartTarget = $periodReports->map(function ($r) use ($targetQuery) {
    $target = $targetQuery->firstWhere('period_no', $r->period_no);

    if (!$target) return 0;

    return ($target->core_target_sales ?? 0)
         + ($target->stills_target_sales ?? 0);
})->toArray();




    /* ====================================================
       CONSOLIDATED REPORT DATA (DO NOT TOUCH)
    ==================================================== */
    $perPage = (int) $request->get('perPage', 10);

    // "Consolidated Reports Overview" cards should show OVERALL totals (not just the current cutoff range).
    // Admin should see global totals across all divisions on this dashboard card block.
    // Non-admin users remain scoped to their assigned division.
    $overviewStart = '2000-01-01';
    $overviewEnd   = now()->toDateString();
    $overviewDivisionId = auth()->user()?->isAdmin() ? null : $divisionId;
    $totals = $this->computeTotals($overviewDivisionId, $overviewStart, $overviewEnd);

    $totalReceipts    = $totals['totalReceipts'];
    $totalReceiptGrossSales = $totals['totalReceiptGrossSales'];
    $totalRemittance  = $totals['totalRemittance'];
    $totalReceivables = $totals['totalReceivables'];
    $netBorrowed      = $totals['netBorrowed'];
    $totalBorrowed    = $totals['totalBorrowed'];
    $totalReturned    = $totals['totalReturned'];

    $totalAccountReceivables     = $totals['totalAccountReceivables'];
    $totalReceivableCollections  = $totals['totalReceivableCollections'];
    $totalStockTransfers         = $totals['totalStockTransfers'];
    $totalShortageCollections    = $totals['totalShortageCollections'];

    $receipts = Receipt::with(['items','division'])
        ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
        ->whereBetween('report_date', [$start, $end])
        ->paginate($perPage)
        ->withQueryString();

    $remittances = Remittance::with(['items','division'])
        ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
        ->whereBetween('report_date', [$start, $end])
        ->paginate($perPage)
        ->withQueryString();

    $receivables = Receivable::with(['items','division'])
        ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
        ->whereBetween('report_date', [$start, $end])
        ->paginate($perPage)
        ->withQueryString();

    $creditExposureByCustomer = ReceivableItem::selectRaw(
        'customer_id, SUM(amount) as total_balance'
    )
    ->whereNotNull('customer_id')
    ->groupBy('customer_id')
    ->with('customer')
    ->get()
    ->keyBy('customer_id');


    $creditExposureByCustomer = ReceivableItem::selectRaw(
        'customer_id, SUM(amount) as total_balance'
        )
        ->whereHas('receivable', fn ($q) =>
            $divisionId ? $q->where('division_id', $divisionId) : null
        )
        ->groupBy('customer_id')
        ->with('customer')
        ->get()
        ->keyBy('customer_id');

    $borrowers = Borrower::with(['items','division'])
        ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
        ->whereBetween('report_date', [$start, $end])
        ->paginate($perPage)
        ->withQueryString();

    $banks = Bank::where('status', 'active')
        ->orderBy('bank_name')
        ->get();

    return view('admin.consolidated.dashboard', compact(
        'currentCutoff',
        'branch',
        'periodReports',
        'divisions',

        'chartLabels',
        'chartActual',
        'chartTarget',

        'targetSales',
        'actualSales',
        'percentage',
        'averagePeriodNo',
        'averageTargetBasisSales',
        'averageActualBasisSales',
        'averagePeriodStart',
        'averagePeriodEnd',
        'routingDays',
        'routingDaysLeft',
        'averageTargetSales',
        'averageActualSales',

        'totalReceipts',
        'totalReceiptGrossSales',
        'totalRemittance',
        'totalReceivables',
        'netBorrowed',
        'totalBorrowed',
        'totalReturned',

        'totalAccountReceivables',
        'totalReceivableCollections',
        'totalStockTransfers',
        'totalShortageCollections',

        'receipts',
        'remittances',
        'receivables',
        'borrowers',
        'banks',
        'perPage',

        'coreActual',
        'coreTarget',
        'coreVariance',
        'coreAchievement',

        'petcsdActual',
        'petcsdTarget',
        'petcsdVariance',
        'petcsdAchievement',

        'stillsActual',
        'stillsTarget',
        'stillsVariance',
        'stillsAchievement',
        'branchPieLabels',
        'branchPieValues',

    ));
}


public function saveDivisionTargets(Request $request)
{
    // âœ… VALIDATION (MATCHES FORM)
    $request->validate([
        'division_id'    => 'required|exists:divisions,id',
        'target_period'  => 'required|integer',
        'target_branch'  => 'required|string',
        'core_target'    => 'required|numeric|min:0',
        'petcsd_target'  => 'required|numeric|min:0',
        'stills_target'  => 'required|numeric|min:0',
    ]);

    // âœ… SAVE TO PERIOD TARGETS (SET TARGET SALES)
    PeriodTarget::updateOrCreate(
        [
            'division_id' => $request->division_id,
            'period_no'   => $request->target_period,
            'branch'      => $request->target_branch,
        ],
        [
            'core_target_sales'   => $request->core_target,
            'petcsd_target_sales' => $request->petcsd_target,
            'stills_target_sales' => $request->stills_target,
            'is_target_locked'    => 1,
        ]
    );

    // âœ… COMPUTE TOTAL TARGET
    $totalTarget =
        $request->core_target +
        $request->stills_target;

    // âœ… UPDATE PERIOD REPORT (FOR DASHBOARD)
    PeriodReport::updateOrCreate(
        [
            'division_id' => $request->division_id,
            'period_no'   => $request->target_period,
            'branch'      => $request->target_branch,
        ],
        [
            'core_target_sales'   => $request->core_target,
            'petcsd_target_sales' => $request->petcsd_target,
            'stills_target_sales' => $request->stills_target,
            'target_sales'        => $totalTarget,
            'is_target_locked'    => 1,
        ]
    );

    // âœ… REDIRECT BACK SAFELY
    return redirect()->route('admin.consolidated.index', [
        'branch'       => $request->target_branch,
        'period_from'  => $request->target_period,
        'period_to'    => $request->target_period,
        'division_id'  => $request->division_id,
    ])->with('success', 'Targets saved and locked.');
}


public function getDivisionTargets(Request $request)
{
    $report = PeriodReport::where('division_id', $request->division_id)
        ->where('period_no', $request->period_no)
        ->where('branch', $request->branch)
        ->first();

    if (!$report) {
        return response()->json(null);
    }

    return response()->json([
        'core_target'   => $report->core_target_sales,
        'petcsd_target' => $report->petcsd_target_sales,
        'stills_target' => $report->stills_target_sales,
        'total_target'  => $report->target_sales,
        'locked'        => $report->is_target_locked,
    ]);
}



    /* ====================================================
       UPDATE TARGET SALES
    ==================================================== */
   //public function updateTarget(Request $request)
//{
    //$request->validate([
        //'period_no' => 'required|integer',
        //'branch'    => 'required|string',
        //'target'    => 'required|numeric|min:0',
    //]);//

    // get period report
   // $report = PeriodReport::where('period_no', $request->period_no)
        //->where('branch', $request->branch)
        //->firstOrFail();//

   // $actual = $report->actual_sales ?? 0;//

    // update values
    //$report->update([
        //'target_sales'     => $request->target,
        //'actual_sales'     => $actual,
        //'total_variance'   => $actual - $request->target,
        //'achievement_pct'  => $request->target > 0
           // ? round(($actual / $request->target) * 100, 2)
           // : 0,
    //]);//

    //return back()->with('success', 'Target updated successfully!');
//}//


protected function recalcPeriodReportIfNeeded(PeriodReport $report)
{
    // load items once
    $report->loadMissing('items');

    if ($report->items->isEmpty()) {
        return;
    }

    $actual = $report->items->sum(fn ($i) =>
        ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
    );

    // ðŸ”¥ Skip update if already correct
    if ((float)$report->actual_sales === (float)$actual) {
        return;
    }

    $target = $report->target_sales ?? 0;

    $report->update([
        'actual_sales'    => $actual,
        'achievement_pct' => $target > 0
            ? round(($actual / $target) * 100, 2)
            : 0,
        'total_variance'  => $target - $actual,
    ]);
}

private function resolvePeriodReportKeywordDate(string $keyword): ?string
{
    $keyword = trim($keyword);
    if ($keyword === '') {
        return null;
    }

    foreach (['Y-m-d', 'm/d/Y', 'm-d-Y', 'M d, Y', 'F d, Y'] as $format) {
        try {
            $parsed = Carbon::createFromFormat($format, $keyword);
            if ($parsed !== false) {
                return $parsed->toDateString();
            }
        } catch (\Throwable $e) {
            // Try next format.
        }
    }

    // Avoid interpreting plain numbers (e.g., period no) as a date.
    if (!preg_match('/[\\/-]|[A-Za-z]/', $keyword)) {
        return null;
    }

    try {
        return Carbon::parse($keyword)->toDateString();
    } catch (\Throwable $e) {
        return null;
    }
}

private function applyPeriodReportKeywordFilter(Builder $query, ?string $keyword): void
{
    $keyword = trim((string) $keyword);
    if ($keyword === '') {
        return;
    }

    $periodNo = ctype_digit($keyword) ? (int) $keyword : null;
    $parsedDate = $this->resolvePeriodReportKeywordDate($keyword);

    $query->where(function (Builder $q) use ($keyword, $periodNo, $parsedDate) {
        $q->where('shipment_no', 'like', '%' . $keyword . '%')
            ->orWhere('branch', 'like', '%' . $keyword . '%');

        if ($periodNo !== null && $periodNo >= 1 && $periodNo <= 12) {
            $q->orWhere('period_no', $periodNo);
        }

        if ($parsedDate !== null) {
            $q->orWhereDate('report_date', $parsedDate);
        }
    });
}

/* ====================================================
       PERIOD SUMMARY
==================================================== */
public function periodSummary(Request $request)
{
    // ðŸ”¹ Base query (single source of truth)
    $query = PeriodReport::query()->with(['items', 'inventories']);

    if ($request->branch && $request->branch !== 'All') {
        $query->where('branch', $request->branch);
    }

    if ($request->filled('period_no') && $request->period_no !== 'All') {
        $query->where('period_no', $request->period_no);
    }
    if ($request->period_from && $request->period_from !== 'All') {
        $query->where('period_no', '>=', $request->period_from);
    }

    if ($request->period_to && $request->period_to !== 'All') {
        $query->where('period_no', '<=', $request->period_to);
    }

    if ($request->filled('report_date')) {
        $query->whereDate('report_date', $request->report_date);
    }

    // Filter by DATE ENCODED (created_at)
    if ($request->filled('encoded_date')) {
        $query->whereDate('created_at', $request->encoded_date);
    }

    $keyword = $request->input('search', $request->input('shipment_no'));
    $this->applyPeriodReportKeywordFilter($query, $keyword);

    // ðŸ”¹ Table data
    $reports = $query
        ->orderByDesc('report_date')
        ->orderByDesc('id')
        ->get();

    $normalizeKey = fn ($pack, $product) => strtolower(trim((string) $pack) . '|' . trim((string) $product));
    $fallbackSrpByKey = Product::query()
        ->select(['pack_size', 'product_name', 'srp'])
        ->get()
        ->mapWithKeys(function ($p) use ($normalizeKey) {
            return [$normalizeKey($p->pack_size ?? '', $p->product_name ?? '') => (float) ($p->srp ?? 0)];
        })
        ->all();

    /* ===============================
     * CATEGORY KPI TOTALS
     * =============================== */

    // TARGETS (use the set target once, do not multiply by number of reports)
    $kpi['core_target']   = (float) ($reports->max('core_target_sales') ?? 0);
    $kpi['petcsd_target'] = (float) ($reports->max('petcsd_target_sales') ?? 0);
    $kpi['stills_target'] = (float) ($reports->max('stills_target_sales') ?? 0);

    // ACTUALS â€” recompute from items (SOURCE OF TRUTH)
    $normalize = fn ($v) => strtolower(str_replace(' ', '', $v ?? ''));

    $kpi['core_actual']   = 0;
    $kpi['petcsd_actual'] = 0;
    $kpi['stills_actual'] = 0;
    $totalActualPeso = 0;

    foreach ($reports as $report) {
        $inventorySrpByKey = [];
        if ($report->inventories && $report->inventories->isNotEmpty()) {
            foreach ($report->inventories as $inv) {
                $invKey = $normalizeKey($inv->pack ?? '', $inv->product ?? '');
                if ($invKey === '|') {
                    continue;
                }
                $inventorySrpByKey[$invKey] = (float) ($inv->srp ?? 0);
            }
        } elseif (is_array($report->inventory_rows)) {
            foreach ($report->inventory_rows as $row) {
                $row = (array) $row;
                $invKey = $normalizeKey(
                    $row['pack'] ?? $row['pack_size'] ?? $row['packSize'] ?? '',
                    $row['product'] ?? $row['product_name'] ?? $row['productName'] ?? ''
                );
                if ($invKey === '|') {
                    continue;
                }
                $inventorySrpByKey[$invKey] = (float) ($row['srp'] ?? 0);
            }
        } else {
            foreach (json_decode($report->inventory_json ?? '[]', true) ?: [] as $row) {
                $row = (array) $row;
                $invKey = $normalizeKey(
                    $row['pack'] ?? $row['pack_size'] ?? $row['packSize'] ?? '',
                    $row['product'] ?? $row['product_name'] ?? $row['productName'] ?? ''
                );
                if ($invKey === '|') {
                    continue;
                }
                $inventorySrpByKey[$invKey] = (float) ($row['srp'] ?? 0);
            }
        }

        $reportTotalPeso = 0;

        foreach ($report->items as $item) {
            $cat = $normalize($item->category);
            $amount = ($item->core_total_ucs ?? 0) + ($item->iws_total_ucs ?? 0);
            $itemKey = $normalizeKey($item->pack ?? '', $item->product ?? '');
            $itemSrp = (float) ($inventorySrpByKey[$itemKey] ?? $fallbackSrpByKey[$itemKey] ?? 0);
            $itemCases = (float) ($item->core_pcs ?? 0) + (float) ($item->iws_pcs ?? 0);
            $reportTotalPeso += ($itemCases * $itemSrp);

            // CORE includes PET CSD
            if ($cat === 'core' || $cat === 'petcsd') {
                $kpi['core_actual'] += $amount;
            }

            // PET CSD only
            if ($cat === 'petcsd') {
                $kpi['petcsd_actual'] += $amount;
            }

            // STILLS only
            if ($cat === 'stills') {
                $kpi['stills_actual'] += $amount;
            }
        }

        $report->actual_sales_peso_calc = round($reportTotalPeso, 2);
        $totalActualPeso += $report->actual_sales_peso_calc;
    }

    // VARIANCE
    $kpi['core_variance']   = $kpi['core_actual']   - $kpi['core_target'];
    $kpi['petcsd_variance'] = $kpi['petcsd_actual'] - $kpi['petcsd_target'];
    $kpi['stills_variance'] = $kpi['stills_actual'] - $kpi['stills_target'];

    // ACHIEVEMENT (%)
    $kpi['core_achievement'] =
        $kpi['core_target'] > 0
            ? round(($kpi['core_actual'] / $kpi['core_target']) * 100, 2)
            : 0;

    $kpi['petcsd_achievement'] =
        $kpi['petcsd_target'] > 0
            ? round(($kpi['petcsd_actual'] / $kpi['petcsd_target']) * 100, 2)
            : 0;

    $kpi['stills_achievement'] =
        $kpi['stills_target'] > 0
            ? round(($kpi['stills_actual'] / $kpi['stills_target']) * 100, 2)
            : 0;

    /* ===============================
     * OVERALL KPI TOTALS
     * =============================== */

    $totalTarget = (float) ($reports->max('target_sales') ?? 0);

    $totalActual = $reports->sum(fn ($r) =>
        $r->items->sum(fn ($i) =>
            ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
        )
    );

    $totalVariance = $totalTarget - $totalActual;

    $kpi = array_merge($kpi, [
        'total_target'   => $totalTarget,
        'total_sales'    => $totalActual,
        'total_sales_peso' => $totalActualPeso,
        'total_variance' => $totalVariance,
    ]);

    $avgAchievement = $totalTarget > 0
        ? round(($totalActual / $totalTarget) * 100, 2)
        : 0;

    return view('admin.reports.periods.index', compact(
        'reports',
        'kpi',
        'avgAchievement'
    ));
}

    /* ====================================================
       EXPORT ALL REPORTS PDF
    ==================================================== */
    public function exportAllReportsPDF(Request $request)
    {
        $start = $request->date_from;
        $end   = $request->date_to;

        $receipts    = Receipt::whereBetween('report_date', [$start, $end])->get();
        $remittances = Remittance::whereBetween('report_date', [$start, $end])->get();
        $receivables = Receivable::whereBetween('report_date', [$start, $end])->get();
        $borrowers   = Borrower::whereBetween('report_date', [$start, $end])->get();

        $pdf = Pdf::loadView('admin.reports.pdf.all-reports', compact(
            'receipts',
            'remittances',
            'receivables',
            'borrowers',
            'start',
            'end'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Consolidated-All-Reports.pdf');
    }
    /* ====================================================
       EXPORT RANGE (CSV)
    ==================================================== */
public function exportRange(Request $request)
{
    $type = $request->type ?? 'csv';

    $divisionId = auth()->user()->division_id ?? null;
    // Period reports in this module are Coca-Cola reports; avoid showing an unrelated
    // logged-in division label (e.g., Petron) when data comes from shared/legacy rows.
    $divisionName = 'Coca-Cola';

    // Include inventories so PDF/print preview can render Inventory & Days Level.
    // Include legacy rows with NULL division_id so older encoded period reports still appear.
    $query = PeriodReport::with(['items', 'inventories'])
        ->when($divisionId, function ($q) use ($divisionId) {
            $q->where(function ($sub) use ($divisionId) {
                $sub->where('division_id', $divisionId)
                    ->orWhereNull('division_id');
            });
        });

    // Optional date range filter
    if ($request->filled('date_from') && $request->filled('date_to')) {
        $query->whereBetween('report_date', [$request->date_from, $request->date_to]);
    }


    if ($request->branch) {
        $query->where('branch', $request->branch);
    }
    if ($request->period_from) {
        $query->where('period_no', '>=', $request->period_from);
    }
    if ($request->period_to) {
        $query->where('period_no', '<=', $request->period_to);
    }

    $keyword = $request->input('search', $request->input('shipment_no'));
    $this->applyPeriodReportKeywordFilter($query, $keyword);

    $reports = $query
        ->orderBy('report_date')
        ->orderBy('period_no')
        ->orderBy('branch')
        ->orderBy('shipment_no')
        ->orderBy('id')
        ->get();

    // Date Range for the exported set (based on report_date values).
    $minDate = $reports->pluck('report_date')->filter()->min();
    $maxDate = $reports->pluck('report_date')->filter()->max();
    $dateFrom = $request->filled('date_from')
        ? (string) $request->date_from
        : ($minDate ? $minDate->toDateString() : null);
    $dateTo   = $request->filled('date_to')
        ? (string) $request->date_to
        : ($maxDate ? $maxDate->toDateString() : null);

    // Per grouped print block (date|period|branch), resolve the period window and routing days.
    // Prefer PeriodTarget dates; fallback to report dates/request filters when unavailable.
    $headerMetaByGroup = [];
    $targetCache = [];
    foreach ($reports as $r) {
        $reportDateKey = $r->report_date?->toDateString() ?? $r->created_at?->toDateString() ?? '';
        $groupKey = $reportDateKey . '|' . (string) ($r->period_no ?? '') . '|' . (string) ($r->branch ?? '');
        if (isset($headerMetaByGroup[$groupKey])) {
            continue;
        }

        $periodNo = (int) ($r->period_no ?? 0);
        $branch = (string) ($r->branch ?? '');
        $targetKey = $branch . '|' . $periodNo;

        if (!array_key_exists($targetKey, $targetCache)) {
            $targetCache[$targetKey] = PeriodTarget::query()
                ->where('branch', $branch)
                ->where('period_no', $periodNo)
                ->latest('id')
                ->first(['start_date', 'end_date']);
        }

        $target = $targetCache[$targetKey];
        $rangeStart = $target?->start_date?->toDateString()
            ?? $r->date_from?->toDateString()
            ?? $dateFrom
            ?? $reportDateKey;
        $rangeEnd = $target?->end_date?->toDateString()
            ?? $r->date_to?->toDateString()
            ?? $dateTo
            ?? $reportDateKey;

        $routingDays = $this->countRoutingDaysFromTargetRange($rangeStart, $rangeEnd);
        if ($routingDays <= 0 && !empty($rangeStart) && !empty($rangeEnd)) {
            try {
                $routingDays = Carbon::parse($rangeStart)->diffInDays(Carbon::parse($rangeEnd)) + 1;
            } catch (\Throwable $e) {
                $routingDays = 0;
            }
        }

        $headerMetaByGroup[$groupKey] = [
            'range_start' => $rangeStart,
            'range_end' => $rangeEnd,
            'routing_days' => $routingDays > 0 ? $routingDays : null,
        ];
    }

    $getInventoryRows = function ($report) {
        $normalizeInventoryRow = function ($row) {
            $row = (array) $row;
            $pack = trim((string) ($row['pack'] ?? $row['pack_size'] ?? $row['packSize'] ?? ''));
            $product = trim((string) ($row['product'] ?? $row['product_name'] ?? $row['productName'] ?? ''));

            return array_merge($row, [
                'pack' => $pack,
                'product' => $product,
                'srp' => (float) ($row['srp'] ?? 0),
                'actual_inv' => (float) ($row['actual_inv'] ?? 0),
                'ads' => (float) ($row['ads'] ?? 0),
                'booking' => (float) ($row['booking'] ?? 0),
                'deliveries' => (float) ($row['deliveries'] ?? 0),
                'routing_days_p5' => (float) ($row['routing_days_p5'] ?? 5),
                'routing_days_7' => (float) ($row['routing_days_7'] ?? 7),
            ]);
        };

        if ($report->relationLoaded('inventories') ? $report->inventories->count() : $report->inventories()->count()) {
            $inv = $report->relationLoaded('inventories') ? $report->inventories : $report->inventories;
            $sourceRows = $inv->map(function ($r) {
                return [
                    'pack' => $r->pack,
                    'product' => $r->product,
                    'srp' => $r->srp,
                    'actual_inv' => $r->actual_inv,
                    'ads' => $r->ads,
                    'booking' => $r->booking,
                    'deliveries' => $r->deliveries,
                    'routing_days_p5' => $r->routing_days_p5,
                    'routing_days_7' => $r->routing_days_7,
                ];
            })->toArray();
        } elseif (is_array($report->inventory_rows)) {
            $sourceRows = $report->inventory_rows;
        } else {
            $sourceRows = json_decode($report->inventory_json ?? '[]', true) ?: [];
        }

        $inventoryByKey = [];
        foreach ($sourceRows as $rawRow) {
            $row = $normalizeInventoryRow($rawRow);
            $rowKey = strtolower($row['pack'] . '||' . $row['product']);
            if ($rowKey === '||') {
                continue;
            }
            $inventoryByKey[$rowKey] = $row;
        }

        $items = $report->relationLoaded('items') ? $report->items : $report->items;
        $completedRows = [];
        foreach ($items as $item) {
            $pack = trim((string) ($item->pack ?? ''));
            $product = trim((string) ($item->product ?? ''));
            $rowKey = strtolower($pack . '||' . $product);

            if ($rowKey === '||') {
                continue;
            }

            if (isset($inventoryByKey[$rowKey])) {
                $row = $inventoryByKey[$rowKey];
                $row['pack'] = $pack !== '' ? $pack : ($row['pack'] ?? '');
                $row['product'] = $product !== '' ? $product : ($row['product'] ?? '');
                $completedRows[] = $row;
                unset($inventoryByKey[$rowKey]);
                continue;
            }

            $completedRows[] = [
                'pack' => $pack,
                'product' => $product,
                'srp' => 0,
                'actual_inv' => 0,
                'ads' => 0,
                'booking' => 0,
                'deliveries' => 0,
                'routing_days_p5' => 5,
                'routing_days_7' => 7,
            ];
        }

        foreach ($inventoryByKey as $remainingRow) {
            $completedRows[] = $remainingRow;
        }

        return $completedRows;
    };

    $getCustomTables = function ($report) {
        if (is_array($report->custom_tables)) {
            return $report->custom_tables;
        }

        return json_decode($report->custom_tables_json ?? '[]', true);
    };

    $getPerSkuRows = function ($report) {
        if (is_array($report->coke_rows)) {
            return $report->coke_rows;
        }

        return json_decode($report->coke_rows_json ?? '[]', true);
    };

    /* ============================
    COMPUTE ACTUAL / VARIANCE (MATCH WEB)
    ============================ */
        foreach ($reports as $r) {

            // SAME computation used in periodSummary()
            $actual = $r->items->sum(function ($i) {
                return ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0);
            });

            // Normalized computed fields for print + PDF views
            $r->actual_calc = (float) $actual;
            $r->variance_calc = (float) (($r->target_sales ?? 0) - $actual);
            $r->achievement_calc = ($r->target_sales ?? 0) > 0
                ? round(($actual / $r->target_sales) * 100, 2)
                : 0;
        }

        if ($type === 'print') {
            return view('admin.reports.exports.period-report-full-pdf', [
                'reports' => $reports,
                'branch'  => $request->branch,
                'from'    => $request->period_from,
                'to'      => $request->period_to,
                'isPrintPreview' => true,
                'divisionName' => $divisionName,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'headerMetaByGroup' => $headerMetaByGroup,
            ]);
        }

        if ($type === 'pdf') {

    foreach ($reports as $report) {

    $report->load(['items', 'inventories']);

    $actual = $report->items->sum(fn ($i) =>
        ($i->core_total_ucs ?? 0) + ($i->iws_total_ucs ?? 0)
    );

     $target = $report->target_sales ?? 0;

     $report->actual_calc = $actual;
     $report->variance_calc = $target - $actual;
     $report->achievement_calc = $target > 0
         ? round(($actual / $target) * 100, 2)
         : 0;
}

    $pdf = Pdf::loadView(
        'admin.reports.exports.period-report-full-pdf',
        [
            'reports' => $reports,
            'branch'  => $request->branch,
            'from'    => $request->period_from,
            'to'      => $request->period_to,
            'divisionName' => $divisionName,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'headerMetaByGroup' => $headerMetaByGroup,
        ]
    )->setPaper('legal', 'landscape');

    return $pdf->download('Period-Report-Full.pdf');
}

    if ($type === 'xlsx') {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $row = 1;
        $maxCols = 22;
        $lastCol = Coordinate::stringFromColumnIndex($maxCols);

        $titleStyle = [
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sectionStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
        ];
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']],
        ];

        $writeRow = function (array $values, array $style = []) use (&$sheet, &$row, $lastCol) {
            $sheet->fromArray($values, null, "A{$row}");
            $range = "A{$row}:{$lastCol}{$row}";
            if (!empty($style)) {
                $sheet->getStyle($range)->applyFromArray($style);
            }
            $row++;
        };

        $mergeRow = function () use (&$sheet, &$row, $lastCol) {
            $sheet->mergeCells("A{$row}:{$lastCol}{$row}");
        };

        $mergeRow();
        $sheet->setCellValue("A{$row}", 'Gledco Multipurpose Cooperative');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($titleStyle);
        $row++;

        $mergeRow();
        $sheet->setCellValue("A{$row}", 'Brgy. 9 Sta. Angela F.R. Castro cor. Balintawak St.');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray(['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $row++;

        $mergeRow();
        $sheet->setCellValue("A{$row}", 'Laoag City, Ilocos Norte 2900');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray(['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $row++;

        $mergeRow();
        $sheet->setCellValue("A{$row}", 'Registration No. 9520-01001354  |  TIN: 005-511-934');
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray(['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]]);
        $row++;

        $row++;
        $mergeRow();
        $sheet->setCellValue("A{$row}", 'Division: ' . ($divisionName ?? '-') . '  |  Date Range: ' . ($dateFrom ?? '-') . ' to ' . ($dateTo ?? '-'));
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray(['font' => ['bold' => true]]);
        $row += 2;

        $writeRow([
            'Period',
            'Branch',
            'Target Sales',
            'Core Target Sales',
            'PET CSD Target Sales',
            'Stills Target Sales',
            'Actual Sales',
            'Variance',
            'Achievement %',
            'Core Actual Sales',
            'Core Variance',
            'Core Achievement %',
            'PET CSD Actual Sales',
            'PET CSD Variance',
            'PET CSD Achievement %',
            'Stills Actual Sales',
            'Stills Variance',
            'Stills Achievement %',
        ], $headerStyle);

        foreach ($reports as $r) {
            $writeRow([
                'Period ' . $r->period_no,
                $r->branch,
                (float) ($r->target_sales ?? 0),
                (float) ($r->core_target_sales ?? 0),
                (float) ($r->petcsd_target_sales ?? 0),
                (float) ($r->stills_target_sales ?? 0),
                (float) ($r->actual_calc ?? 0),
                (float) ($r->variance_calc ?? 0),
                (float) ($r->achievement_calc ?? 0),
                (float) ($r->core_actual_sales ?? 0),
                (float) ($r->core_variance ?? 0),
                (float) ($r->core_achievement_pct ?? 0),
                (float) ($r->petcsd_actual_sales ?? 0),
                (float) ($r->petcsd_variance ?? 0),
                (float) ($r->petcsd_achievement_pct ?? 0),
                (float) ($r->stills_actual_sales ?? 0),
                (float) ($r->stills_variance ?? 0),
                (float) ($r->stills_achievement_pct ?? 0),
            ]);
        }

        $row += 2;

        foreach ($reports as $report) {
            $coreT   = (float) ($report->core_target_sales ?? 0);
            $petT    = (float) ($report->petcsd_target_sales ?? 0);
            $stillsT = (float) ($report->stills_target_sales ?? 0);
            $targetTotal = (float) ($report->target_sales ?? 0);

            $coreRowTarget = $coreT;
            if (abs(($coreT + $petT + $stillsT) - $targetTotal) < 0.01) {
                $coreRowTarget = $coreT;
            } elseif (abs(($coreT + $stillsT) - $targetTotal) < 0.01) {
                $coreRowTarget = max(0, $coreT - $petT);
            } else {
                $coreRowTarget = max(0, $coreT - $petT);
                if ($coreRowTarget <= 0) {
                    $coreRowTarget = $coreT;
                }
            }

            $coreA   = (float) ($report->core_actual_sales ?? 0);
            $petA    = (float) ($report->petcsd_actual_sales ?? 0);
            $stillsA = (float) ($report->stills_actual_sales ?? 0);
            $coreRowActual = max(0, $coreA - $petA);
            $totalActual = (float) ($report->actual_calc ?? $report->actual_sales ?? 0);

            $pct = function (float $actual, float $target) {
                return $target > 0 ? round(($actual / $target) * 100, 2) : 0;
            };
            $var = function (float $actual, float $target) {
                return round($actual - $target, 2);
            };

            $mergeRow();
            $sheet->setCellValue("A{$row}", 'PERIOD SUMMARY - Period ' . $report->period_no . ' - ' . $report->branch);
            $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($sectionStyle);
            $row++;

            $writeRow(['Category', 'Target', 'Actual', '%', 'Variance'], $headerStyle);
            $writeRow(['CORE', $coreRowTarget, $coreRowActual, $pct($coreRowActual, $coreRowTarget), $var($coreRowActual, $coreRowTarget)]);
            $writeRow(['STILLS', $stillsT, $stillsA, $pct($stillsA, $stillsT), $var($stillsA, $stillsT)]);
            $writeRow(['PET CSD', $petT, $petA, $pct($petA, $petT), $var($petA, $petT)]);
            $writeRow(['TOTAL', $targetTotal, $totalActual, $pct($totalActual, $targetTotal), $var($totalActual, $targetTotal)]);

            $row++;

            if ($report->items->count()) {
                $mergeRow();
                $sheet->setCellValue("A{$row}", 'PRODUCTS');
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($sectionStyle);
                $row++;

                $writeRow(['Pack', 'Product', 'Core PCS', 'Core UCS', 'Core Total UCS', 'IWS PCS', 'IWS UCS', 'IWS Total UCS', 'Total'], $headerStyle);
                foreach ($report->items as $item) {
                    $coreTotal = (float) ($item->core_total_ucs ?? 0);
                    $iwsTotal = (float) ($item->iws_total_ucs ?? 0);
                    $writeRow([
                        $item->pack,
                        $item->product,
                        (float) ($item->core_pcs ?? 0),
                        (float) ($item->core_ucs ?? 0),
                        $coreTotal,
                        (float) ($item->iws_pcs ?? 0),
                        (float) ($item->iws_ucs ?? 0),
                        $iwsTotal,
                        $coreTotal + $iwsTotal,
                    ]);
                }
                $row++;
            }

            $inventoryRows = $getInventoryRows($report);
            if (count($inventoryRows)) {
                $mergeRow();
                $sheet->setCellValue("A{$row}", 'INVENTORY & DAYS LEVEL');
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($sectionStyle);
                $row++;

                $writeRow([
                    'Pack Size', 'Product Name', 'SRP', 'Peso Eq', 'Actual Inv', 'ADS', 'Days Lvl',
                    'Booking', 'Days Lvl', 'Deliveries', 'PTD Total Inv', 'Days Lvl',
                    'Routing Days', 'Est Sales', 'After Cut-off P5 Inv', 'Peso Eq', 'Days Lvl',
                    'Routing Days (7d)', 'Est Sales (7d)', 'After Month-end Inv', 'Days Lvl (Month-end)', 'Peso Eq (Month-end)',
                ], $headerStyle);

                foreach ($inventoryRows as $rowData) {
                    $srp = (float) ($rowData['srp'] ?? 0);
                    $actualInv = (float) ($rowData['actual_inv'] ?? 0);
                    $ads = (float) ($rowData['ads'] ?? 0);
                    $booking = (float) ($rowData['booking'] ?? 0);
                    $deliveries = (float) ($rowData['deliveries'] ?? 0);
                    $pesoEq = $srp * $actualInv;
                    $daysLvl = $ads > 0 ? ($actualInv / $ads) : 0;
                    $daysBooking = $ads > 0 ? ($booking / $ads) : 0;
                    $ptd = $actualInv + $booking + $deliveries;
                    $daysPtd = $ads > 0 ? ($ptd / $ads) : 0;
                    $routingP5 = (float) ($rowData['routing_days_p5'] ?? 5);
                    $routing7 = (float) ($rowData['routing_days_7'] ?? 7);
                    $estP5 = $ads * $routingP5;
                    $afterP5 = $ptd - $estP5;
                    $pesoAfterP5 = $afterP5 * $srp;
                    $daysAfterP5 = $ads > 0 ? ($afterP5 / $ads) : 0;
                    $est7 = $ads * $routing7;
                    $afterMonth = $ptd - $est7;
                    $daysMonth = $ads > 0 ? ($afterMonth / $ads) : 0;
                    $pesoMonth = $afterMonth * $srp;

                    $writeRow([
                        $rowData['pack'] ?? '',
                        $rowData['product'] ?? '',
                        round($srp, 2),
                        round($pesoEq, 2),
                        round($actualInv, 2),
                        round($ads, 2),
                        round($daysLvl, 2),
                        round($booking, 2),
                        round($daysBooking, 2),
                        round($deliveries, 2),
                        round($ptd, 2),
                        round($daysPtd, 2),
                        round($routingP5, 2),
                        round($estP5, 2),
                        round($afterP5, 2),
                        round($pesoAfterP5, 2),
                        round($daysAfterP5, 2),
                        round($routing7, 2),
                        round($est7, 2),
                        round($afterMonth, 2),
                        round($daysMonth, 2),
                        round($pesoMonth, 2),
                    ]);
                }
                $row++;
            }

            $perSkuRows = $getPerSkuRows($report);
            if (count($perSkuRows)) {
                $mergeRow();
                $sheet->setCellValue("A{$row}", 'PER SKU (REFERENCE)');
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($sectionStyle);
                $row++;

                $writeRow(['Product', 'Target PCS', 'Target UCS', 'Actual PCS', 'Actual UCS', 'Variance PCS', 'Variance UCS'], $headerStyle);
                foreach ($perSkuRows as $rowData) {
                    $targetPcs = (float) ($rowData['target_pcs'] ?? 0);
                    $targetUcs = (float) ($rowData['target_ucs'] ?? 0);
                    $actualPcs = (float) ($rowData['actual_pcs'] ?? 0);
                    $actualUcs = (float) ($rowData['actual_ucs'] ?? 0);
                    $varPcs = $targetPcs - $actualPcs;
                    $varUcs = $targetUcs - $actualUcs;
                    $writeRow([
                        trim(($rowData['product'] ?? '') . ' ' . ($rowData['pack'] ?? '')),
                        $targetPcs,
                        $targetUcs,
                        $actualPcs,
                        $actualUcs,
                        $varPcs,
                        $varUcs,
                    ]);
                }
                $row++;
            }

            $customTables = $getCustomTables($report);
            foreach ($customTables as $tbl) {
                $mergeRow();
                $sheet->setCellValue("A{$row}", (string) ($tbl['title'] ?? 'Custom Table'));
                $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray($sectionStyle);
                $row++;

                $headers = $tbl['headers'] ?? [];
                if (!empty($headers)) {
                    $writeRow($headers, $headerStyle);
                }
                $rows = $tbl['rows'] ?? [];
                foreach ($rows as $cells) {
                    $writeRow($cells);
                }
                $row++;
            }

            $row++;
        }

        for ($i = 1; $i <= $maxCols; $i++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Period-Reports.xlsx"',
        ]);
    }



    /* ============================
       CSV EXPORT (DEFAULT)
   ============================ */
    return response()->stream(function () use (
        $reports,
        $divisionName,
        $dateFrom,
        $dateTo,
        $getInventoryRows,
        $getCustomTables,
        $getPerSkuRows
    ) {
        $file = fopen('php://output', 'w');

        // Header block (requested for CSV exports as well).
        fputcsv($file, ['Gledco Multipurpose Cooperative']);
        fputcsv($file, ['Brgy. 9 Sta. Angela F.R. Castro cor. Balintawak St.']);
        fputcsv($file, ['Laoag City, Ilocos Norte 2900']);
        fputcsv($file, ['Registration No. 9520-01001354', 'TIN: 005-511-934']);
        fputcsv($file, []); // blank line
        fputcsv($file, ['Division:', $divisionName, 'Date Range:', ($dateFrom ?? '-') . ' to ' . ($dateTo ?? '-')]);
        fputcsv($file, []); // blank line

        fputcsv($file, ['Report Summary']);
        fputcsv($file, [
            'Period',
            'Branch',
            'Target Sales',
            'Core Target Sales',
            'PET CSD Target Sales',
            'Stills Target Sales',
            'Actual Sales',
            'Variance',
            'Achievement %',
            'Core Actual Sales',
            'Core Variance',
            'Core Achievement %',
            'PET CSD Actual Sales',
            'PET CSD Variance',
            'PET CSD Achievement %',
            'Stills Actual Sales',
            'Stills Variance',
            'Stills Achievement %'
        ]);

        foreach ($reports as $r) {
            fputcsv($file, [
                'Period ' . $r->period_no,
                $r->branch,
                $r->target_sales,
                $r->core_target_sales ?? 0,
                $r->petcsd_target_sales ?? 0,
                $r->stills_target_sales ?? 0,
                $r->actual_calc ?? 0,
                $r->variance_calc ?? 0,
                $r->achievement_calc ?? 0,
                $r->core_actual_sales ?? 0,
                $r->core_variance ?? 0,
                $r->core_achievement_pct ?? 0,
                $r->petcsd_actual_sales ?? 0,
                $r->petcsd_variance ?? 0,
                $r->petcsd_achievement_pct ?? 0,
                $r->stills_actual_sales ?? 0,
                $r->stills_variance ?? 0,
                $r->stills_achievement_pct ?? 0,
            ]);
        }

        fputcsv($file, []);

        foreach ($reports as $report) {
            $coreT   = (float) ($report->core_target_sales ?? 0);
            $petT    = (float) ($report->petcsd_target_sales ?? 0);
            $stillsT = (float) ($report->stills_target_sales ?? 0);
            $targetTotal = (float) ($report->target_sales ?? 0);

            $coreRowTarget = $coreT;
            if (abs(($coreT + $petT + $stillsT) - $targetTotal) < 0.01) {
                $coreRowTarget = $coreT;
            } elseif (abs(($coreT + $stillsT) - $targetTotal) < 0.01) {
                $coreRowTarget = max(0, $coreT - $petT);
            } else {
                $coreRowTarget = max(0, $coreT - $petT);
                if ($coreRowTarget <= 0) {
                    $coreRowTarget = $coreT;
                }
            }

            $coreA   = (float) ($report->core_actual_sales ?? 0);
            $petA    = (float) ($report->petcsd_actual_sales ?? 0);
            $stillsA = (float) ($report->stills_actual_sales ?? 0);
            $coreRowActual = max(0, $coreA - $petA);
            $totalActual = (float) ($report->actual_calc ?? $report->actual_sales ?? 0);

            $pct = function (float $actual, float $target) {
                return $target > 0 ? round(($actual / $target) * 100, 2) : 0;
            };
            $var = function (float $actual, float $target) {
                return round($actual - $target, 2);
            };

            fputcsv($file, ['Period Summary - Period ' . $report->period_no . ' - ' . $report->branch]);
            fputcsv($file, ['Category', 'Target', 'Actual', '%', 'Variance']);
            fputcsv($file, ['CORE', $coreRowTarget, $coreRowActual, $pct($coreRowActual, $coreRowTarget), $var($coreRowActual, $coreRowTarget)]);
            fputcsv($file, ['STILLS', $stillsT, $stillsA, $pct($stillsA, $stillsT), $var($stillsA, $stillsT)]);
            fputcsv($file, ['PET CSD', $petT, $petA, $pct($petA, $petT), $var($petA, $petT)]);
            fputcsv($file, ['TOTAL', $targetTotal, $totalActual, $pct($totalActual, $targetTotal), $var($totalActual, $targetTotal)]);
            fputcsv($file, []);

            if ($report->items->count()) {
                fputcsv($file, ['Products - Period ' . $report->period_no . ' - ' . $report->branch]);
                fputcsv($file, [
                    'Pack',
                    'Product',
                    'Core PCS',
                    'Core UCS',
                    'Core Total UCS',
                    'IWS PCS',
                    'IWS UCS',
                    'IWS Total UCS',
                    'Total',
                ]);
                foreach ($report->items as $item) {
                    $coreTotal = (float) ($item->core_total_ucs ?? 0);
                    $iwsTotal = (float) ($item->iws_total_ucs ?? 0);
                    fputcsv($file, [
                        $item->pack,
                        $item->product,
                        (float) ($item->core_pcs ?? 0),
                        (float) ($item->core_ucs ?? 0),
                        $coreTotal,
                        (float) ($item->iws_pcs ?? 0),
                        (float) ($item->iws_ucs ?? 0),
                        $iwsTotal,
                        $coreTotal + $iwsTotal,
                    ]);
                }
                fputcsv($file, []);
            }

            $inventoryRows = $getInventoryRows($report);
            if (count($inventoryRows)) {
                fputcsv($file, ['Inventory & Days Level - Period ' . $report->period_no . ' - ' . $report->branch]);
                fputcsv($file, [
                    'Pack Size', 'Product Name', 'SRP', 'Peso Eq', 'Actual Inv', 'ADS', 'Days Lvl',
                    'Booking', 'Days Lvl', 'Deliveries', 'PTD Total Inv', 'Days Lvl',
                    'Routing Days', 'Est Sales', 'After Cut-off P5 Inv', 'Peso Eq', 'Days Lvl',
                    'Routing Days (7d)', 'Est Sales (7d)', 'After Month-end Inv', 'Days Lvl (Month-end)', 'Peso Eq (Month-end)',
                ]);

                foreach ($inventoryRows as $rowData) {
                    $srp = (float) ($rowData['srp'] ?? 0);
                    $actualInv = (float) ($rowData['actual_inv'] ?? 0);
                    $ads = (float) ($rowData['ads'] ?? 0);
                    $booking = (float) ($rowData['booking'] ?? 0);
                    $deliveries = (float) ($rowData['deliveries'] ?? 0);
                    $pesoEq = $srp * $actualInv;
                    $daysLvl = $ads > 0 ? ($actualInv / $ads) : 0;
                    $daysBooking = $ads > 0 ? ($booking / $ads) : 0;
                    $ptd = $actualInv + $booking + $deliveries;
                    $daysPtd = $ads > 0 ? ($ptd / $ads) : 0;
                    $routingP5 = (float) ($rowData['routing_days_p5'] ?? 5);
                    $routing7 = (float) ($rowData['routing_days_7'] ?? 7);
                    $estP5 = $ads * $routingP5;
                    $afterP5 = $ptd - $estP5;
                    $pesoAfterP5 = $afterP5 * $srp;
                    $daysAfterP5 = $ads > 0 ? ($afterP5 / $ads) : 0;
                    $est7 = $ads * $routing7;
                    $afterMonth = $ptd - $est7;
                    $daysMonth = $ads > 0 ? ($afterMonth / $ads) : 0;
                    $pesoMonth = $afterMonth * $srp;

                    fputcsv($file, [
                        $rowData['pack'] ?? '',
                        $rowData['product'] ?? '',
                        round($srp, 2),
                        round($pesoEq, 2),
                        round($actualInv, 2),
                        round($ads, 2),
                        round($daysLvl, 2),
                        round($booking, 2),
                        round($daysBooking, 2),
                        round($deliveries, 2),
                        round($ptd, 2),
                        round($daysPtd, 2),
                        round($routingP5, 2),
                        round($estP5, 2),
                        round($afterP5, 2),
                        round($pesoAfterP5, 2),
                        round($daysAfterP5, 2),
                        round($routing7, 2),
                        round($est7, 2),
                        round($afterMonth, 2),
                        round($daysMonth, 2),
                        round($pesoMonth, 2),
                    ]);
                }
                fputcsv($file, []);
            }

            $perSkuRows = $getPerSkuRows($report);
            if (count($perSkuRows)) {
                fputcsv($file, ['Per SKU (Reference) - Period ' . $report->period_no . ' - ' . $report->branch]);
                fputcsv($file, [
                    'Product',
                    'Target PCS',
                    'Target UCS',
                    'Actual PCS',
                    'Actual UCS',
                    'Variance PCS',
                    'Variance UCS',
                ]);

                foreach ($perSkuRows as $rowData) {
                    $targetPcs = (float) ($rowData['target_pcs'] ?? 0);
                    $targetUcs = (float) ($rowData['target_ucs'] ?? 0);
                    $actualPcs = (float) ($rowData['actual_pcs'] ?? 0);
                    $actualUcs = (float) ($rowData['actual_ucs'] ?? 0);
                    $varPcs = $targetPcs - $actualPcs;
                    $varUcs = $targetUcs - $actualUcs;

                    fputcsv($file, [
                        trim(($rowData['product'] ?? '') . ' ' . ($rowData['pack'] ?? '')),
                        $targetPcs,
                        $targetUcs,
                        $actualPcs,
                        $actualUcs,
                        $varPcs,
                        $varUcs,
                    ]);
                }
                fputcsv($file, []);
            }

            $customTables = $getCustomTables($report);
            foreach ($customTables as $tbl) {
                fputcsv($file, [$tbl['title'] ?? 'Custom Table']);

                $headers = $tbl['headers'] ?? [];
                if (!empty($headers)) {
                    fputcsv($file, $headers);
                }

                $rows = $tbl['rows'] ?? [];
                foreach ($rows as $cells) {
                    fputcsv($file, $cells);
                }
                fputcsv($file, []);
            }

            fputcsv($file, []);
        }

        fclose($file);
    }, 200, [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename="Period-Reports.csv"',
    ]);
}

    /**
     * Interactive Print Preview page (choose Branch/Period/Date before printing).
     */
    public function periodReportsPrintPreview(Request $request)
    {
        $branches = ['Solsona', 'Laoag', 'Batac'];

        // Build the iframe URL only when user has chosen a period range (or date range).
        $previewUrl = null;
        $hasAnyFilter =
            $request->filled('branch') ||
            $request->filled('period_from') ||
            $request->filled('period_to') ||
            $request->filled('shipment_no') ||
            ($request->filled('date_from') && $request->filled('date_to'));

        if ($hasAnyFilter) {
            $previewUrl = route('admin.reports.periods.export.range', [
                'type' => 'print',
                'branch' => $request->branch,
                'period_from' => $request->period_from,
                'period_to' => $request->period_to,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'shipment_no' => $request->shipment_no,
            ]);
        }

        return view('admin.reports.periods.print-preview', [
            'branches' => $branches,
            'previewUrl' => $previewUrl,
        ]);
    }
private function buildConsolidatedData(Request $request)
{
    // Filters
    $divisionId = $request->division_id;
    $start = $request->filled('date_from') ? $request->date_from : null;
    $end   = $request->filled('date_to') ? $request->date_to : null;

    // RECEIPTS
    $receipts = Receipt::with('items')
        ->when($divisionId, fn ($q) =>
            $q->where('division_id', $divisionId)
        )
        ->when($start && $end, fn ($q) =>
            $q->whereBetween('report_date', [$start, $end])
        )
        ->orderBy('report_date', 'desc')
        ->get();

    // REMITTANCE
    $remittances = Remittance::with('items')
        ->when($divisionId, fn ($q) =>
            $q->where('division_id', $divisionId)
        )
        ->when($start && $end, fn ($q) =>
            $q->whereBetween('report_date', [$start, $end])
        )
        ->orderBy('report_date', 'desc')
        ->get();

    // RECEIVABLES
    $receivables = Receivable::with('items')
        ->when($divisionId, fn ($q) =>
            $q->where('division_id', $divisionId)
        )
        ->when($start && $end, fn ($q) =>
            $q->whereBetween('report_date', [$start, $end])
        )
        ->orderBy('report_date', 'desc')
        ->get();

    // BORROWERS
    $borrowers = Borrower::with('items')
        ->when($divisionId, fn ($q) =>
            $q->where('division_id', $divisionId)
        )
        ->when($start && $end, fn ($q) =>
            $q->whereBetween('report_date', [$start, $end])
        )
        ->orderBy('report_date', 'desc')
        ->get();

    return compact(
        'receipts',
        'remittances',
        'receivables',
        'borrowers',
        'start',
        'end',
        'divisionId',
        //'totalReceipts',//
        //'totalRemittance'//
    );

}
public function printPreview(Request $request)
{
    $data = $this->buildConsolidatedData($request);
    $divisionId = $request->division_id;
    $filterStart = $request->filled('date_from') ? $request->date_from : null;
    $filterEnd   = $request->filled('date_to')   ? $request->date_to   : null;
    $start = $filterStart ?: '2000-01-01';
    $end   = $filterEnd ?: now()->toDateString();
    $totals = $this->computeTotals($divisionId, $start, $end);
    $data = array_merge($data, $totals);
    $data['reportType'] = $request->get('report_type', '');
    $data['filterStart'] = $filterStart;
    $data['filterEnd'] = $filterEnd;
    $data['divisionName'] = $divisionId
        ? (optional(Division::find($divisionId))->division_name ?: '--')
        : 'All Divisions';
    return view('admin.reports.print.consolidate', $data);
}

private function countRoutingDaysFromTargetRange($startDate, $endDate): int
{
    if (empty($startDate) || empty($endDate)) {
        return 0;
    }

    try {
        $start = $startDate instanceof Carbon
            ? $startDate->copy()->startOfDay()
            : Carbon::parse($startDate)->startOfDay();
        $end = $endDate instanceof Carbon
            ? $endDate->copy()->startOfDay()
            : Carbon::parse($endDate)->startOfDay();
    } catch (\Throwable $e) {
        return 0;
    }

    if ($end->lt($start)) {
        return 0;
    }

    $days = 0;
    foreach (CarbonPeriod::create($start, $end) as $date) {
        if (!$date->isSunday()) {
            $days++;
        }
    }

    return $days;
}

private function computeTotals($divisionId, $start, $end)
{
    // RECEIPTS
    // Receipts totals are safest to compute from receipt_items, because some setups
    // may not persist header-level gross_sales / total_remittance on the receipts table.
    $receiptItemScope = ReceiptItem::query()
        ->whereHas('receipt', function ($q) use ($divisionId, $start, $end) {
            if ($divisionId) {
                $q->where('division_id', $divisionId);
            }
            $q->whereBetween('report_date', [$start, $end]);
        });

    $totalReceipts = (clone $receiptItemScope)->sum('total_remittance');
    $totalReceiptGrossSales = (clone $receiptItemScope)->sum('gross_sales');


    // REMITTANCE (remittance_items.amount)
    $totalRemittance = RemittanceItem::whereHas('remittance', function ($q) use ($divisionId, $start, $end) {
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        $q->whereBetween('report_date', [$start, $end]);
    })->sum('amount');

    // RECEIVABLES (receivable_items.amount)
    $totalReceivables = ReceivableItem::whereHas('receivable', function ($q) use ($divisionId, $start, $end) {
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        $q->whereBetween('report_date', [$start, $end]);
    })->sum('amount');

    $receivableScope = ReceivableItem::whereHas('receivable', function ($q) use ($divisionId, $start, $end) {
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        $q->whereBetween('report_date', [$start, $end]);
    });

    // RECEIVABLES (breakdown by type)
    $totalAccountReceivables = (clone $receivableScope)
        ->where('type', 'ACCOUNT_RECEIVABLES')
        ->sum('amount');

    $totalReceivableCollections = (clone $receivableScope)
        ->where('type', 'RECEIVABLE_COLLECTION')
        ->sum('amount');

    $totalStockTransfers = (clone $receivableScope)
        ->where('type', 'STOCK_TRANSFER_RECEIVABLE')
        ->sum('amount');

    $totalShortageCollections = (clone $receivableScope)
        ->where('type', 'SHORTAGE_COLLECTION')
        ->sum('amount');

    // BORROWERS (borrowed - returned)
    $borrowed = BorrowerItem::whereHas('borrower', function ($q) use ($divisionId, $start, $end) {
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        $q->whereBetween('report_date', [$start, $end]);
    })->sum('borrowed');

    $returned = BorrowerItem::whereHas('borrower', function ($q) use ($divisionId, $start, $end) {
        if ($divisionId) {
            $q->where('division_id', $divisionId);
        }
        $q->whereBetween('report_date', [$start, $end]);
    })->sum('returned');

    return [
        'totalReceipts'    => $totalReceipts,
        'totalReceiptGrossSales' => $totalReceiptGrossSales,
        'totalRemittance'  => $totalRemittance,
        'totalReceivables' => $totalReceivables,
        'netBorrowed'      => $borrowed - $returned,
        'totalBorrowed'    => $borrowed,
        'totalReturned'    => $returned,

        'totalAccountReceivables'    => $totalAccountReceivables,
        'totalReceivableCollections' => $totalReceivableCollections,
        'totalStockTransfers'        => $totalStockTransfers,
        'totalShortageCollections'   => $totalShortageCollections,
    ];
}


public function globalSearch(Request $request)
{
    $rawInput = trim($request->search ?? '');
    $bankId = $request->bank_id;

    // Allow searching by bank only (no keyword/date typed).
    if ($rawInput === '' && empty($bankId)) {
        return redirect()->route('admin.reports.consolidated');
    }

    $divisionId = $request->division_id ?? null;
    $start = '2000-01-01';
    $end = now()->toDateString();

    $selectedBankName = null;
    if (!empty($bankId)) {
        $bank = Bank::find($bankId);
        $selectedBankName = $bank ? trim((string) $bank->bank_name) : null;
    }

    $searchDate = null;
    $textKeyword = $rawInput !== '' ? $rawInput : null;

    if ($rawInput !== '') {
        try {
            $parsed = \Carbon\Carbon::parse($rawInput);
            $searchDate = $parsed->format('Y-m-d');
            $textKeyword = null;
        } catch (\Exception $e) {
        }
    }

    $allowedReportTypes = ['receipts', 'remittance', 'receivables', 'borrowers'];
    $reportTypeAliases = [
        'receipt' => 'receipts',
        'receipts' => 'receipts',
        'remittance' => 'remittance',
        'remittances' => 'remittance',
        'receivable' => 'receivables',
        'receivables' => 'receivables',
        'borrower' => 'borrowers',
        'borrowers' => 'borrowers',
    ];

    $activeReportType = in_array($request->report_type, $allowedReportTypes, true)
        ? $request->report_type
        : null;

    if (!$activeReportType && $textKeyword) {
        $activeReportType = $reportTypeAliases[strtolower($textKeyword)] ?? null;
        if ($activeReportType) {
            // A report-type keyword should open that section without requiring text matches in row fields.
            $textKeyword = null;
        }
    }

    $loadReceipts = !$activeReportType || $activeReportType === 'receipts';
    $loadRemittances = !$activeReportType || $activeReportType === 'remittance';
    $loadReceivables = !$activeReportType || $activeReportType === 'receivables';
    $loadBorrowers = !$activeReportType || $activeReportType === 'borrowers';

    $receipts = $loadReceipts
        ? Receipt::with('items')
            ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
            ->when($searchDate, fn ($q) => $q->whereDate('report_date', $searchDate))
            ->when($textKeyword, function ($q) use ($textKeyword) {
                $q->where(function ($sub) use ($textKeyword) {
                    $sub->where('route', 'LIKE', "%{$textKeyword}%")
                        ->orWhere('leadman', 'LIKE', "%{$textKeyword}%")
                        ->orWhere('report_date', 'LIKE', "%{$textKeyword}%");
                });
            })
            ->whereBetween('report_date', [$start, $end])
            ->orderBy('report_date', 'desc')
            ->get()
        : collect();

    $remittances = $loadRemittances
        ? Remittance::with('items')
            ->when($divisionId, fn ($q) => $q->where('division_id', $divisionId))
            ->when($searchDate, fn ($q) => $q->whereDate('report_date', $searchDate))
            ->when($selectedBankName, function ($q) use ($selectedBankName) {
                // Bank is stored in check item description (e.g. "BDO - Laoag City | ...")
                $q->whereHas('items', function ($qi) use ($selectedBankName) {
                    $qi->where('type', 'check')
                        ->where(function ($w) use ($selectedBankName) {
                            $w->where('description', 'LIKE', "%{$selectedBankName}%");
                        });
                });
            })
            ->when($textKeyword, fn ($q) =>
                $q->whereHas('items', fn ($qi) =>
                    $qi->where('description', 'LIKE', "%{$textKeyword}%")
                )
            )
            ->whereBetween('report_date', [$start, $end])
            ->get()
        : collect();

    // If a bank was selected, keep only the matching check rows (cash rows are kept as-is).
    if ($loadRemittances && $selectedBankName) {
        $remittances->each(function ($r) use ($selectedBankName) {
            $filtered = $r->items->filter(function ($item) use ($selectedBankName) {
                if (($item->type ?? null) !== 'check') {
                    return true; // keep cash
                }

                $haystack = (string) ($item->bank_name ?? $item->description ?? '');
                return stripos($haystack, $selectedBankName) !== false;
            })->values();

            $r->setRelation('items', $filtered);
        });
    }

    $receivables = $loadReceivables
        ? Receivable::with('items')
            ->when($searchDate, fn ($q) => $q->whereDate('report_date', $searchDate))
            ->when($textKeyword, fn ($q) =>
                $q->whereHas('items', fn ($qi) =>
                    $qi->where(function ($w) use ($textKeyword) {
                        $w->where('customer_name', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('remarks', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('reference_no', 'LIKE', "%{$textKeyword}%");
                    })
                )
            )
            ->get()
        : collect();

    $allItems = $receivables->flatMap->items;

    if ($textKeyword) {
        $allItems = $allItems->filter(function ($i) use ($textKeyword) {
            return str_contains(strtolower($i->customer_name ?? ''), strtolower($textKeyword))
                || str_contains(strtolower($i->remarks ?? ''), strtolower($textKeyword))
                || str_contains(strtolower($i->reference_no ?? ''), strtolower($textKeyword));
        })->values();
    }

    $accountReceivables = $allItems->filter(fn ($i) =>
        str_contains(strtolower($i->type), 'account')
    )->values();

    $receivableCollections = $allItems->filter(fn ($i) =>
        str_contains(strtolower($i->type), 'collection')
    )->values();

    $stockTransfers = $allItems->filter(fn ($i) =>
        str_contains(strtolower($i->type), 'stock')
    )->values();

    $shortageCollections = $allItems->filter(fn ($i) =>
        str_contains(strtolower($i->type), 'shortage')
    )->values();

    $collectionsByCustomer = $receivableCollections
        ->groupBy(fn ($i) => strtolower(trim($i->customer_name)))
        ->map(fn ($rows) => $rows->sum('amount'));

    $lastPaymentDateByCustomer = $receivableCollections
        ->groupBy(fn ($i) => strtolower(trim($i->customer_name)))
        ->map(fn ($rows) => $rows->max('created_at'));

    $borrowers = $loadBorrowers
        ? Borrower::with(['items' => function ($q) use ($textKeyword) {
                if ($textKeyword) {
                    $q->where(function ($w) use ($textKeyword) {
                        $w->where('description', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('location', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('item_type', 'LIKE', "%{$textKeyword}%");
                    });
                }
            }])
            ->when($searchDate, fn ($q) => $q->whereDate('report_date', $searchDate))
            ->when($textKeyword, function ($q) use ($textKeyword) {
                $q->where('name', 'LIKE', "%{$textKeyword}%")
                    ->orWhereHas('items', function ($qi) use ($textKeyword) {
                        $qi->where('description', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('location', 'LIKE', "%{$textKeyword}%")
                            ->orWhere('item_type', 'LIKE', "%{$textKeyword}%");
                    });
            })
            ->get()
            // Keep only borrowers that still have matching rows in items.
            ->filter(fn ($b) => $b->items->isNotEmpty())
            ->values()
        : collect();

    $totalRemittance = $receipts->sum('total_remittance');
    $totalReceivables = $allItems->sum('amount');
    $netBorrowed = $borrowers->flatMap->items
        ->sum(fn ($i) => ($i->borrowed ?? 0) - ($i->returned ?? 0));
    $totalRemitted = $remittances->flatMap->items->sum('amount');

    $banks = Bank::where('status', 'active')->orderBy('bank_name')->get();
    $divisions = Division::all();

    return view('admin.reports.consolidated', compact(
        'receipts',
        'remittances',
        'receivables',
        'borrowers',
        'banks',
        'divisions',
        'totalRemittance',
        'totalRemitted',
        'totalReceivables',
        'netBorrowed',
        'accountReceivables',
        'receivableCollections',
        'stockTransfers',
        'shortageCollections',
        'collectionsByCustomer',
        'lastPaymentDateByCustomer',
        'activeReportType'
    ) + [
        'hasFilter' => true,
        'divisionId' => null
    ]);
}
}


