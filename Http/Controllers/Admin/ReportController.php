<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\Division;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\Remittance;
use App\Models\RemittanceItem;
use App\Models\Receivable;
use App\Models\ReceivableItem;
use App\Models\Borrower;
use App\Models\BorrowerItem;
use App\Models\PeriodReport;
use App\Models\Bank;
use App\Models\Customer;


use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{

    private function pesoToNumber($value)
    {
        if ($value === null) return 0;
        return floatval(str_replace(['₱', ',', ' '], '', $value));
    }

    /* ===========================================================
       STEP 1 — SELECT DIVISION
    =========================================================== */
    public function selectDivision()
    {
        $divisions = Division::whereIn('division_name', [
            'Gledco Enterprise - Laoag',
            'Gledco Enterprise - Batac',
            'Gledco Enterprise - Solsona',
        ])->orderBy('division_name')->get();
        return view('admin.reports.add.select-division', compact('divisions'));
    }

    /* ===========================================================
       STEP 2 — CHOOSE REPORT TYPE
    =========================================================== */
    public function chooseReportType(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id'
        ]);

        $division = Division::findOrFail($request->division_id);

        return view('admin.reports.add.choose-report-type', [
            'division'    => $division,
            'division_id' => $division->id,
        ]);
    }

    /* ===========================================================
       STEP 3 — REDIRECT TO ADD FORM
    =========================================================== */
    public function addReportType(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'report_type' => 'required|in:receipts,remittance,receivables,borrowers',
        ]);

        return redirect()->route('admin.reports.add.form', [
            'division'    => $request->division_id,
            'report_type' => $request->report_type
        ]);
    }

    /* ===========================================================
       STEP 4 — LOAD ADD FORM
    =========================================================== */
    public function addReport($division, $report_type)
{
    $bankAccounts = Bank::where('status', 'active')
        ->orderBy('bank_name')
        ->orderBy('branch_name')
        ->get();

    // Route dropdown options (union of defaults + existing route targets + existing receipt routes).
    $defaultRoutes = collect([
        'BODEGA',
        'CRS 1',
        'CRS 2',
        'CRS 3',
        'OUTSIDE TOWN',
        'WATER ROUTE',
    ]);

    $dbRoutes = collect();
    if (Schema::hasTable('route_targets')) {
        $dbRoutes = $dbRoutes->merge(
            DB::table('route_targets')->distinct()->pluck('route')->filter()
        );
    }

    if (Schema::hasTable('receipts')) {
        $dbRoutes = $dbRoutes->merge(
            DB::table('receipts')->distinct()->pluck('route')->filter()
        );
    }

    $routesList = $defaultRoutes
        ->merge($dbRoutes)
        ->map(fn ($r) => trim((string) $r))
        ->filter()
        ->unique(fn ($r) => mb_strtolower($r))
        ->sort()
        ->values();

   $customers = Customer::with('receivableItems')
    ->get()
    ->map(function ($customer) {

        // ✅ Total Account Receivables only
        $totalReceivable = $customer->receivableItems
            ->where('type', 'ACCOUNT_RECEIVABLES')
            ->sum('amount');

        $customer->total_receivable = $totalReceivable;

        // ✅ Remaining credit
        $customer->remaining_credit =
            $customer->credit_limit - $totalReceivable;

        // ✅ Credit status
        $customer->credit_status =
            $customer->remaining_credit < 0
                ? 'OVER LIMIT'
                : 'WITHIN LIMIT';

        return $customer;
    });


    return view('admin.reports.add.add-report', [
        'division'     => Division::findOrFail($division),
        'reportType'   => $report_type,
        'divisions'    => Division::orderBy('division_name')->get(),
        'bankAccounts'=> $bankAccounts,
        'customers'   => $customers,
        'routesList'  => $routesList,
    ]);
}


    /* ===========================================================
       STORE REPORTS
    =========================================================== */
    public function storeReport(Request $request, string $report_type)
    {

        $report_type = strtolower($report_type);
        if (str_contains($report_type, 'borrow')) {
            $report_type = 'borrowers';
        }

        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'report_date' => 'required|date',
        ]);

// 🔹 Receivables-only validation
        if ($report_type === 'receivables' && $request->has('ar_amount')) {
            $request->validate([
                'ar_customer_id.*' => 'nullable|exists:customers,id',
                'ar_amount.*'   => 'nullable|numeric|min:0',
                'ar_terms.*'    => 'nullable|integer|min:1',
                'ar_due_date.*' => 'nullable|date',
                'ar_leadman.*'  => 'nullable|string|max:255',
                'sh_name.*'     => 'nullable|string|max:255',
                'sh_date.*'     => 'nullable|date',
                'sh_collection.*' => 'nullable|date',
                'sh_amount.*'   => 'nullable|numeric|min:0',
            ]);
        }

        /* ================= RECEIPTS ================= */
        if ($report_type === 'receipts') {
            DB::transaction(function () use ($request) {
                $routes = $request->route ?? [];
                $leadmen = $request->leadman ?? [];
                $grossSales = $request->gross_sales ?? [];

                $rowCount = max(count($routes), count($leadmen), count($grossSales));

                for ($i = 0; $i < $rowCount; $i++) {
                    $route = trim((string) ($routes[$i] ?? ''));
                    $leadman = trim((string) ($leadmen[$i] ?? ''));

                    $full = (int) ($request->full_case[$i] ?? 0);
                    $half = (int) ($request->half_case[$i] ?? 0);
                    $box  = (int) ($request->box[$i] ?? 0);
                    $totalCases = $full + $half + $box;

                    $totalUcs = $this->pesoToNumber($request->total_ucs[$i] ?? 0);
                    $noOfReceipts = (int) ($request->no_of_receipts[$i] ?? 0);
                    $customerCount = (int) ($request->customer_count[$i] ?? 0);

                    $gross = $this->pesoToNumber($request->gross_sales[$i] ?? 0);
                    $salesDiscount = $this->pesoToNumber($request->sales_discount[$i] ?? 0);
                    $couponDiscount = $this->pesoToNumber($request->coupon_discount[$i] ?? 0);
                    $netSales = $gross - $salesDiscount - $couponDiscount;

                    $containersDeposit = $this->pesoToNumber($request->containers_deposit[$i] ?? 0);
                    $purchasedRefund = $this->pesoToNumber($request->purchased_refund[$i] ?? 0);
                    $stockTransfer = $this->pesoToNumber($request->stock_transfer[$i] ?? 0);
                    $netCreditSales = $this->pesoToNumber($request->net_credit_sales[$i] ?? 0);
                    $shortageCollections = $this->pesoToNumber($request->shortage_collections[$i] ?? 0);
                    $arCollections = $this->pesoToNumber($request->ar_collections[$i] ?? 0);
                    $otherIncome = $this->pesoToNumber($request->other_income[$i] ?? 0);

                    // Match the JS computation on the receipts table.
                    $cashProceeds =
                        $netSales
                        + $containersDeposit
                        - $purchasedRefund
                        + $stockTransfer
                        - $netCreditSales
                        + $shortageCollections
                        + $arCollections
                        + $otherIncome;

                    $cash = $this->pesoToNumber($request->cash[$i] ?? 0);
                    $check = $this->pesoToNumber($request->check[$i] ?? 0);
                    $totalRemittance = $cash + $check;

                    // Shortage/Overage = total remittance - cash proceeds (per JS)
                    $shortageOverage = $totalRemittance - $cashProceeds;

                    $hasAnyValue =
                        $route !== '' ||
                        $leadman !== '' ||
                        $full !== 0 ||
                        $half !== 0 ||
                        $box !== 0 ||
                        $totalUcs !== 0.0 ||
                        $noOfReceipts !== 0 ||
                        $customerCount !== 0 ||
                        $gross !== 0.0 ||
                        $salesDiscount !== 0.0 ||
                        $couponDiscount !== 0.0 ||
                        $containersDeposit !== 0.0 ||
                        $purchasedRefund !== 0.0 ||
                        $stockTransfer !== 0.0 ||
                        $netCreditSales !== 0.0 ||
                        $shortageCollections !== 0.0 ||
                        $arCollections !== 0.0 ||
                        $otherIncome !== 0.0 ||
                        $cash !== 0.0 ||
                        $check !== 0.0;

                    if (!$hasAnyValue) {
                        continue;
                    }

                    // Enforce: Route + Leadman are required for every saved receipt row.
                    if ($route === '' || $leadman === '') {
                        throw ValidationException::withMessages([
                            'route.' . $i => 'Route is required.',
                            'leadman.' . $i => 'Leadman is required.',
                        ]);
                    }

                    $receipt = Receipt::create([
                        'division_id' => $request->division_id,
                        'report_date' => $request->report_date,
                        'route'       => $route !== '' ? $route : null,
                        'leadman'     => $leadman !== '' ? $leadman : null,
                    ]);

                    ReceiptItem::create([
                        'receipt_id'         => $receipt->id,
                        'full_case'          => $full,
                        'half_case'          => $half,
                        'box'                => $box,
                        'total_cases'        => $totalCases,
                        'total_ucs'          => $totalUcs,
                        'number_of_receipts' => $noOfReceipts,
                        'customer_count'     => $customerCount,

                        'gross_sales'     => $gross,
                        'sales_discounts' => $salesDiscount,
                        'coupon_discount' => $couponDiscount,
                        'net_sales'       => $netSales,

                        'containers_deposit'   => $containersDeposit,
                        'purchased_refund'     => $purchasedRefund,
                        'stock_transfer'       => $stockTransfer,
                        'net_credit_sales'     => $netCreditSales,
                        'shortage_collections' => $shortageCollections,
                        'ar_collections'       => $arCollections,
                        'other_income'         => $otherIncome,

                        'cash_proceeds'    => $cashProceeds,
                        'remittance_cash'  => $cash,
                        'remittance_check' => $check,
                        'total_remittance' => $totalRemittance,
                        'shortage_overage' => $shortageOverage,
                    ]);
                }
            });

            return redirect()
                ->route('admin.reports.consolidated')
                ->with('success', 'Receipts report saved successfully.');
        }

   
/* ================= REMITTANCE ================= */
if ($report_type === 'remittance') {
    // ✅ UPDATED VALIDATION (MATCHES FORM: check[…])
    $request->validate([
        'division_id' => 'required|exists:divisions,id',
        'report_date' => 'required|date',
        'checks.*.bank_branch' => 'nullable|string|max:255',
        'checks.*.account_holder_name' => 'nullable|string|max:255',
        'checks.*.account_number' => 'nullable|string|max:255',
        'checks.*.check_date' => 'nullable|date',
        'checks.*.remarks' => 'nullable|string|max:255',
        'checks.*.amount' => 'nullable|numeric|min:0',
        'cash.*.denomination' => 'nullable|numeric|min:0',
        'cash.*.pcs' => 'nullable|integer|min:0',
    ]);

    DB::transaction(function () use ($request) {
        $totalChecks = 0;
        $totalCash = 0;

        // ✅ CHECK TOTALS (FIXED)
        //foreach ($request->check ?? [] as $check) {
            ///if (!empty($check['amount'])) {
                //$total += floatval($check['amount']);
            //}
        //}

        // cash
        //foreach ($request->cash ?? [] as $cash) {
            //if (!empty($cash['pcs']) && !empty($cash['denomination'])) {
                //$total += floatval($cash['pcs']) * floatval($cash['denomination']);
            //}
        //}

        $remittance = Remittance::create([
            'division_id' => $request->division_id,
            'report_date' => $request->report_date,
        ]);

        // ===== CHECKS =====
//foreach ($request->checks ?? [] as $check) {

    // skip totally empty rows
    //if (empty($check['amount'])) {
        //continue;
    //}

    //RemittanceItem::create([
        //'remittance_id' => $remittance->id,
        //'type'          => 'check',

        // ✅ SAVE TO REAL COLUMNS
        //'bank_name'      => $check['bank_branch'] ?? null,
        //'account_name'   => $check['account_holder_name'] ?? null,
        //'account_number' => $check['account_number'] ?? null,

        // (optional, keep description if you want)
        //'description' =>
            //trim($check['bank_branch'] ?? '-') . ' | ' .
            //trim($check['account_holder_name'] ?? '-') . ' | ' .
            //trim($check['account_number'] ?? '-'),

        //'amount' => floatval($check['amount'] ?? 0),
    //]);
//}

// ===== CHECKS =====
foreach ($request->checks ?? [] as $check) {

    if (empty($check['amount'])) {
        continue;
    }

    $amount = floatval($check['amount']);   // ✅ normalize once
    $totalChecks += $amount;

    $bankName = trim((string) ($check['bank_branch'] ?? ''));
    $accountName = trim((string) ($check['account_holder_name'] ?? ''));
    $accountNumber = trim((string) ($check['account_number'] ?? ''));

    $descriptionParts = array_values(array_filter([
        $bankName,
        $accountName,
        $accountNumber,
    ], fn ($value) => $value !== ''));

    $description = !empty($descriptionParts) ? implode(' | ', $descriptionParts) : null;

    RemittanceItem::create([
        'remittance_id' => $remittance->id,
        'type'          => 'check',

        'bank_name'      => $bankName !== '' ? $bankName : null,
        'account_name'   => $accountName !== '' ? $accountName : null,
        'account_number' => $accountNumber !== '' ? $accountNumber : null,
        'check_date'     => !empty($check['check_date']) ? $check['check_date'] : null,
        'remarks'        => $check['remarks'] ?? null,

        'description' => $description,

        'amount' => $amount,
    ]);
}

        // ===== CASH ===== (UNCHANGED)
        foreach ($request->cash ?? [] as $cash) {
            if (!empty($cash['pcs']) && !empty($cash['denomination'])) {
                $pcs = (int) $cash['pcs'];
                $denomination = floatval($cash['denomination']);
                $amount = $pcs * $denomination;
                $totalCash += $amount;

                RemittanceItem::create([
                    'remittance_id' => $remittance->id,
                    'type'          => 'cash',
                    'denomination'  => $denomination,
                    'pcs'           => $pcs,
                    'amount'        => $amount,
                ]);
            }
        }

        // Persist total for quick reporting (optional; dashboard still uses items sum).
        $remittance->total = $totalChecks + $totalCash;
        $remittance->save();
    });

    return redirect()
        ->route('admin.reports.consolidated')
        ->with('success', 'Remittance report saved successfully.');
}



/* ================= RECEIVABLES ================= */

if ($report_type === 'receivables') {

    // ✅ FIX: VALIDATE division_id BEFORE SAVING
    $request->validate([
        'division_id' => 'required|exists:divisions,id',
        'report_date' => 'required|date',
    ]);

    DB::transaction(function () use ($request) {

        // ✅ CLEAN HELPER
        $clean = fn ($v) => floatval(str_replace(['₱', ',', ' '], '', $v));

        // ================= RECEIVABLE HEADER =================
        $receivable = Receivable::create([
            'division_id' => $request->division_id,
            'report_date' => $request->report_date,
        ]);

        // ================= ACCOUNT RECEIVABLES =================
        // If the entered credit exceeds the customer's remaining credit,
        // the excess is automatically transferred to SHORTAGE_COLLECTION under the Leadman.
        $arCustomerIds = collect($request->ar_customer_id ?? [])
            ->filter(fn ($v) => !empty($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values();

        $customersById = Customer::whereIn('id', $arCustomerIds)->get()->keyBy('id');

        $usedCreditByCustomer = ReceivableItem::query()
            ->selectRaw('customer_id, COALESCE(SUM(amount), 0) as used')
            ->where('type', 'ACCOUNT_RECEIVABLES')
            ->whereIn('customer_id', $arCustomerIds)
            ->groupBy('customer_id')
            ->pluck('used', 'customer_id');

        $availableCreditByCustomer = [];
        foreach ($arCustomerIds as $customerId) {
            $limit = (float) ($customersById[$customerId]->credit_limit ?? 0);
            $used  = (float) ($usedCreditByCustomer[$customerId] ?? 0);
            $availableCreditByCustomer[$customerId] = max(0, $limit - $used);
        }

        foreach ($request->ar_amount ?? [] as $i => $amount) {
            $cleanAmount = $clean($amount);

            // ✅ skip totally empty rows
            if ($cleanAmount <= 0) {
                continue;
            }

            $customerIdRaw = $request->ar_customer_id[$i] ?? null;
            if (empty($customerIdRaw)) {
                continue;
            }

            $customerId = (int) $customerIdRaw;
            $siNo = trim((string) ($request->ar_si[$i] ?? '')) ?: null;
            $leadman = trim((string) ($request->ar_leadman[$i] ?? ''));

            $available = (float) ($availableCreditByCustomer[$customerId] ?? 0);
            $chargeToCustomer = min($cleanAmount, $available);
            $excess = $cleanAmount - $chargeToCustomer;

            // 1) Save the portion still covered by the customer's remaining credit.
            if ($chargeToCustomer > 0) {
                ReceivableItem::create([
                    'receivable_id' => $receivable->id,
                    'type'          => 'ACCOUNT_RECEIVABLES',
                    'reference_no'  => $siNo,
                    'customer_id'   => $customerId,
                    'description'   => $leadman ?: null, // store leadman for traceability

                    // ✅ OPTIONAL FIELDS
                    'terms'    => $request->ar_terms[$i] ?? null,
                    'due_date' => $request->ar_due_date[$i] ?? null,

                    'amount'   => $chargeToCustomer,
                ]);

                $availableCreditByCustomer[$customerId] = max(0, $available - $chargeToCustomer);
            }

            // 2) Save the excess portion under shortage collections charged to the leadman.
            if ($excess > 0) {
                $customer = $customersById[$customerId] ?? null;
                $customerLabel = $customer?->store_name
                    ?: $customer?->customer
                    ?: 'UNKNOWN CUSTOMER';

                $shortageName = $leadman !== '' ? $leadman : 'UNASSIGNED LEADMAN';

                ReceivableItem::create([
                    'receivable_id'  => $receivable->id,
                    'type'           => 'SHORTAGE_COLLECTION',
                    'customer_name'  => $shortageName, // shown as "Name" in shortage collections
                    'reference_no'   => $siNo,
                    'description'    => 'Excess credit from: ' . $customerLabel,
                    'remarks'        => 'AUTO (credit limit exceeded)',
                    'amount'         => $excess,
                ]);
            }
        }

        // ================= COLLECTION OF RECEIVABLES =================
        foreach ($request->cr_amount ?? [] as $i => $amount) {

            $cleanAmount = $clean($amount);

            if ($cleanAmount > 0) {
                ReceivableItem::create([
                        'receivable_id' => $receivable->id,
                        'type'          => 'RECEIVABLE_COLLECTION',
                        'customer_id'   => $request->cr_customer_id[$i] ?? null,

                        // ✅ SI BEING PAID
                        'reference_no'  => $request->cr_si_no[$i] ?? null,

                        // CR NUMBER (optional, for audit)
                        'remarks'       => $request->cr_remarks[$i] ?? null,
                        'amount'        => $cleanAmount,
                    ]);
            }
        }

        // ================= STOCK TRANSFER =================
        if ($request->filled('st_amount')) {
            foreach ($request->st_amount as $i => $amount) {
                if (!$amount) continue;

                ReceivableItem::create([
                    'receivable_id' => $receivable->id,
                    'type'          => 'STOCK_TRANSFER_RECEIVABLE',
                    'reference_no'  => $request->st_ref[$i] ?? null,
                    'remarks'       => $request->st_desc[$i] ?? null,
                    'amount'        => $amount,
                ]);
            }
        }

        // ================= SHORTAGE COLLECTIONS =================
        if ($request->filled('sh_amount')) {
            foreach ($request->sh_amount as $i => $amount) {
                $cleanAmount = $clean($amount);
                if ($cleanAmount <= 0) continue;

                $name = trim((string) ($request->sh_name[$i] ?? '')) ?: null;
                $shortageDate = $request->sh_date[$i] ?? null;
                $collectionDate = $request->sh_collection[$i] ?? null;

                $meta = [];
                if (!empty($shortageDate)) $meta[] = 'Shortage Date: ' . $shortageDate;
                if (!empty($collectionDate)) $meta[] = 'Collection Date: ' . $collectionDate;

                ReceivableItem::create([
                    'receivable_id' => $receivable->id,
                    'type'          => 'SHORTAGE_COLLECTION',
                    'customer_name' => $name,
                    'description'   => $meta ? implode(' | ', $meta) : null,
                    'amount'        => $cleanAmount,
                ]);
            }
        }
    });

    return redirect()
        ->route('admin.reports.consolidated')
        ->with('success', 'Receivables report saved successfully.');
}

        /* ================= BORROWERS ================= */
      if ($report_type === 'borrowers') {

    // ✅ FIX: VALIDATE division_id BEFORE SAVING
    $request->validate([
        'division_id' => 'required|exists:divisions,id',
        'report_date' => 'required|date',
    ]);

    $borrower = Borrower::create([
        'division_id' => $request->division_id,
        'report_date' => $request->report_date,
    ]);

    foreach (['plastic','kasalo','litro'] as $item) {
        foreach (['bodega','crs1','crs2','crs3','outside','water'] as $area) {

            $borrowed = array_sum($request->input("borrowed_{$area}_{$item}", []));
            $returned = array_sum($request->input("returned_{$area}_{$item}", []));

            if ($borrowed > 0 || $returned > 0) {
                BorrowerItem::create([
                    'borrower_id' => $borrower->id,
                    'item_type'   => ucfirst($item),
                    'location'    => ucfirst($area),
                    'borrowed'    => $borrowed,
                    'returned'    => $returned,
                ]);
            }
        }
    }

    // total net borrowed (borrowed - returned)
    $netBorrowed = BorrowerItem::where('borrower_id', $borrower->id)
        ->selectRaw('SUM(borrowed - returned) as total')
        ->value('total') ?? 0;

    // If period context is provided, update the matching period report.
    // Keep shipment support optional for setups with multiple shipment reports per day.
    if ($request->filled('period_no') && $request->filled('branch')) {
        $periodReportQuery = PeriodReport::query()
            ->where('division_id', $request->division_id)
            ->where('period_no', $request->period_no)
            ->where('branch', $request->branch)
            ->whereDate('report_date', $request->report_date);

        if ($request->filled('shipment_no')) {
            $periodReportQuery->where('shipment_no', trim((string) $request->shipment_no));
        }

        $periodReport = $periodReportQuery
            ->orderByDesc('id')
            ->first();

        if ($periodReport) {
            $periodReport->actual_sales += $netBorrowed;
            $periodReport->total_variance =
                ($periodReport->target_sales ?? 0) - ($periodReport->actual_sales ?? 0);

            $periodReport->achievement_pct =
                $periodReport->target_sales > 0
                    ? round(($periodReport->actual_sales / $periodReport->target_sales) * 100, 2)
                    : 0;

            $periodReport->save();
        }
    }

    return redirect()
        ->route('admin.reports.consolidated')
        ->with('success', 'Borrowers report saved successfully.');
}
    }

    /* ===========================================================
       CONSOLIDATED DASHBOARD
    =========================================================== */
    public function consolidated(Request $request)
    {
        $data = $this->buildConsolidatedData($request);

        $data['hasFilter'] =
            $request->filled('division_id') ||
            $request->filled('report_type') ||
            $request->filled('date_from') ||
            $request->filled('date_to');

        return view('admin.reports.consolidated', $data);
    }

    /* ===========================================================
       SHARED DATA BUILDER (BRACKET FIX ONLY)
    =========================================================== */
    private function buildConsolidatedData(Request $request)
    {
        $divisionId = $request->division_id;
        $start = $request->filled('date_from')
            ? $request->date_from
            : '2000-01-01';

        $end = $request->filled('date_to')
            ? $request->date_to
            : now()->toDateString();


        $receipts = Receipt::with('items')
            ->when($divisionId, fn($q)=>$q->where('division_id',$divisionId))
            ->whereBetween('report_date', [$start,$end])
            ->orderBy('report_date', 'desc') // ✅ NEWEST FIRST
            ->get();

        // "Show entries per section" affects how many rows we show in each section.
        // Keep the allowed sizes in sync with the Blade dropdown.
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 30, 50, 100], true) ? $perPage : 10;

            // ================= TOP 10 ROUTES & LEADMEN =================
        $topRoutes = $receipts
            ->flatMap->items
            ->groupBy(fn ($item) =>
                ($item->receipt->route ?? '—') . '|' .
                ($item->receipt->leadman ?? '—')
            )
            ->map(function ($items, $key) {
                [$route, $leadman] = explode('|', $key);

                return [
                    'route'       => $route,
                    'leadman'     => $leadman,
                    'total_sales' => $items->sum('gross_sales'),
                    'total_cases' => $items->sum('total_cases'),
                ];
            })
            ->sortByDesc('total_sales')
            ->take($perPage)
            ->values();

        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        $daysPassed = Carbon::now()->day;
        $totalDays  = Carbon::now()->daysInMonth;

        $routePerformance = $receipts
    ->flatMap->items
    ->groupBy(fn ($item) =>
        ($item->receipt->route ?? '—') . '|' .
        ($item->receipt->leadman ?? '—')
    )
    ->map(function ($items, $key) use ($daysPassed, $totalDays) {

        [$route, $leadman] = explode('|', $key);

        $actualSales = $items->sum('gross_sales');

        // ===== NORMALIZE STRINGS =====
       $routeNorm   = preg_replace('/\s+/', ' ', trim(strtolower($route)));
        $leadmanNorm = preg_replace('/\s+/', ' ', trim(strtolower($leadman)));

        $targetRow = DB::table('route_targets')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get()
            ->first(function ($row) use ($routeNorm, $leadmanNorm) {

                $dbRoute   = preg_replace('/\s+/', ' ', trim(strtolower($row->route)));
                $dbLeadman = preg_replace('/\s+/', ' ', trim(strtolower($row->leadman)));

                return $dbRoute === $routeNorm
                    && $dbLeadman === $leadmanNorm;
            });

        $targetSales = $targetRow ? (float) $targetRow->target_sales : 0;
        $daysLevel   = $targetRow && !empty($targetRow->days_level)
            ? max(1, (int) $targetRow->days_level)
            : $totalDays;

        $effectiveDaysPassed = min($daysPassed, $daysLevel);

        // ===== ACHIEVEMENT =====
        $achievement = $targetSales > 0
            ? round(($actualSales / $targetSales) * 100, 2)
            : 0;

        // ===== EXPECTED TODAY (BASED ON DAYS LEVEL) =====
        $expectedToday = $targetSales > 0
            ? ($targetSales / $daysLevel) * $effectiveDaysPassed
            : 0;

        // ===== REMARKS =====
        if ($targetSales <= 0) {
            $remarks = 'NO TARGET';
            $achievement = 0;
        } else {
            $achievement = round(($actualSales / $targetSales) * 100, 2);

            $expectedToday = ($targetSales / $daysLevel) * $effectiveDaysPassed;

            $remarks = $actualSales >= $expectedToday
                ? 'ON TRACK'
                : 'OFF TRACK';
        }

        // Variance here means remaining to hit the full target.
        // Positive = still needed, negative = exceeded target.
        $variance = $targetSales - $actualSales;

        // Remaining target per day until the Days Level is reached (inclusive).
        // Example: days_level=28, today=4 => days_left=25 (days 4..28).
         $daysLeft = max(1, ($daysLevel - $effectiveDaysPassed + 1));
         $remainingPerDay = $variance > 0 ? ($variance / $daysLeft) : 0;

         // ✅ THIS RETURN IS CRITICAL
         return [
             'route'        => $route,
             'leadman'      => $leadman,
             'target_sales' => $targetSales,
             'actual_sales' => $actualSales,
             'achievement'  => $achievement,
             'variance'     => $variance,
             'remaining_target' => $remainingPerDay,
             'remaining_days'   => $daysLeft,
             'days_level'   => $daysLevel,
             'remarks'      => $remarks,
         ];
     })
     ->values();

        $remittances = Remittance::with('items')
            ->when($divisionId, fn($q)=>$q->where('division_id',$divisionId))
            ->whereBetween('report_date', [$start,$end])
            ->orderBy('report_date', 'desc')
            ->get();

        $receivables = Receivable::with('items.customer')
            ->when($divisionId, fn($q)=>$q->where('division_id',$divisionId))
            ->whereBetween('report_date', [$start,$end])
            ->orderBy('report_date', 'desc')
            ->get();


        $borrowers = Borrower::with('items')
            ->when($divisionId, fn($q)=>$q->where('division_id',$divisionId))
            ->whereBetween('report_date', [$start,$end])
            ->orderBy('report_date', 'desc')
            ->get();

        // ================= RECEIVABLES MONITORING GROUPING =================
        $items = $receivables->flatMap->items;

// group raw items
$accountReceivables = $items->where('type', 'ACCOUNT_RECEIVABLES');
$receivableCollections = $items->where('type', 'RECEIVABLE_COLLECTION');
$stockTransfers = $items->where('type', 'STOCK_TRANSFER_RECEIVABLE');
$shortageCollections = $items->where('type', 'SHORTAGE_COLLECTION');

// total payments per customer
$collectionsByCustomer = $receivableCollections
    ->groupBy('customer_id')
    ->map(fn ($items) => $items->sum('amount'))
    ->toArray();

// last payment date per customer
$lastPaymentDateByCustomer = $receivableCollections
    ->groupBy('customer_id')
    ->map(fn ($items) => $items->max('created_at'))
    ->toArray();


// ================= RECEIVABLE STATUS (NO AUTO PAYMENT) =================

// ================= FIFO PAYMENT DISTRIBUTION =================

// 1️⃣ Group collections per customer (sorted by date)
$collectionsByCustomer = $receivableCollections
    ->groupBy('customer_id')
    ->map(function ($items) {
        return $items
            ->sortBy('created_at')
            ->map(function ($item) {
                $item->original_amount = $item->amount; // 👈 SAVE ORIGINAL
                return $item;
            })
            ->values();
    });


// 2️⃣ Sort account receivables FIFO (oldest first)
$accountReceivables = $accountReceivables
    ->sortBy(function ($item) {
        return $item->due_date ?? $item->created_at;
    })
    ->values();

// 3️⃣ Apply FIFO per customer
$accountReceivables = $accountReceivables->map(function ($ar) use (&$collectionsByCustomer) {

    $customerId = $ar->customer_id;

    $ar->paid = 0;
    $ar->balance = $ar->amount;
    $ar->last_paid_date = null;

    if (!isset($collectionsByCustomer[$customerId])) {
        // no collections for this customer
        $ar->status = ($ar->due_date && now()->gt($ar->due_date))
            ? 'OVERDUE'
            : 'ACTIVE';

        return $ar;
    }

    foreach ($collectionsByCustomer[$customerId] as $index => $collection) {

        if ($ar->balance <= 0) break;

        if ($collection->amount <= 0) continue;

        $applied = min($ar->balance, $collection->amount);

        // apply payment
        $ar->paid += $applied;
        $ar->balance -= $applied;
        $ar->last_paid_date = $collection->created_at;

        // reduce remaining collection amount
        $collection->amount -= $applied;

        // remove exhausted collection
        if ($collection->amount <= 0) {
            unset($collectionsByCustomer[$customerId][$index]);
        }
    }

    // status
    if ($ar->balance <= 0) {
        $ar->status = 'PAID';
    } elseif ($ar->paid > 0) {
        $ar->status = 'PARTIAL';
    } elseif ($ar->due_date && now()->gt($ar->due_date)) {
        $ar->status = 'OVERDUE';
    } else {
        $ar->status = 'ACTIVE';
    }

    return $ar;
});

            logger([
                'AR' => $accountReceivables->count(),
                'COLL' => $receivableCollections->count(),
                'STOCK' => $stockTransfers->count(),
                'SHORT' => $shortageCollections->count(),
            ]);

            $banks = Bank::where('status', 'active')
            ->orderBy('bank_name')
            ->get();

        return compact(
            'divisionId','start','end',
            'receipts','remittances','receivables','borrowers',
            'accountReceivables',
            'receivableCollections',
            'stockTransfers',
            'shortageCollections',
            'collectionsByCustomer',
            'lastPaymentDateByCustomer',
            'banks',
            'routePerformance',
            'topRoutes'
        ) + [
            // ===== SUMMARY TOTALS (FIXED) =====
            'totalGrossSales' => $receipts->flatMap->items->sum('gross_sales'),
            'totalReceipts' => $receipts->sum('total_remittance'),
            'totalRemitted' => RemittanceItem::whereHas('remittance', function ($q) use ($divisionId, $start, $end) {
                if ($divisionId) {
                    $q->where('division_id', $divisionId);
                }
                $q->whereBetween('report_date', [$start, $end]);
            })->sum('amount'),

            'totalReceivables' => ReceivableItem::whereHas('receivable', function ($q) use ($divisionId, $start, $end) {
                if ($divisionId) {
                    $q->where('division_id', $divisionId);
                }
                $q->whereBetween('report_date', [$start, $end]);
            })->sum('amount'),

            'totalBorrowed' => BorrowerItem::sum('borrowed'),
            'totalReturned' => BorrowerItem::sum('returned'),
            'netBorrowed'   => BorrowerItem::sum('borrowed') - BorrowerItem::sum('returned'),
            'divisions' => Division::orderBy('division_name')->get()
        ];
    }

    /* ===========================================================
       EXPORTS
    =========================================================== */
    public function exportPdf(Request $request)
{
    Pdf::setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
    ]);

    return Pdf::loadView(
        'admin.reports.pdf.all-reports',
        $this->buildConsolidatedData($request)
    )->download('consolidated-report.pdf');
}


    public function exportCsv(Request $request)
    {
        $data = $this->buildConsolidatedData($request);

        return response()->stream(function () use ($data) {
            $file = fopen('php://output', 'w');

            if ($data['receipts']->isNotEmpty()) {
                fputcsv($file, ['RECEIPTS']);
                foreach ($data['receipts'] as $r) {
                    foreach ($r->items as $item) {
                        fputcsv($file, [$r->report_date, $item->item_type, $item->amount]);
                    }
                }
                fputcsv($file, []);
            }

            if ($data['remittances']->isNotEmpty()) {
                fputcsv($file, ['REMITTANCE']);
                foreach ($data['remittances'] as $r) {
                    foreach ($r->items as $item) {
                        fputcsv($file, [$r->report_date, $item->type, $item->amount]);
                    }
                }
                fputcsv($file, []);
            }

            if ($data['receivables']->isNotEmpty()) {
                fputcsv($file, ['RECEIVABLES']);
                foreach ($data['receivables'] as $r) {
                    foreach ($r->items as $item) {
                        fputcsv($file, [
                        optional($item->customer)->store_name,
                        $item->amount,
                        $item->terms,
                        $item->due_date,
                    ]);
                    }
                }
                fputcsv($file, []);
            }

            if ($data['borrowers']->isNotEmpty()) {
                fputcsv($file, ['BORROWERS']);
                foreach ($data['borrowers'] as $b) {
                    foreach ($b->items as $item) {
                        fputcsv($file, [
                            $item->item_type,
                            $item->location,
                            $item->borrowed,
                            $item->returned
                        ]);
                    }
                }
            }

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=consolidated-report.csv',
        ]);
    }



}
