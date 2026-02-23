<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gledco Enterprises Report</title>

    <style>
        @page {
            size: 8.5in 13in landscape;
            margin: 10mm;
        }
        .section {
            page-break-before: always;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
        }

        h2, h3 {
            margin: 6px 0;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        th, td {
            border: 1px solid #0e0d0dff;
            padding: 4px;
            vertical-align: middle;
        }

        thead th {
            background: #db8d8dff;
        }

        .section-title {
            margin-top: 16px;
            font-weight: bold;
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body { margin: 0; }
        }
    </style>
</head>

<body>
<!-- ============================
     OFFICIAL HEADER
=============================== -->
<table width="100%" style="border:none; margin-bottom:15px;">
    <tr>
        <td style="text-align:center; border:none;">
            <div style="font-size:18px; font-weight:bold;">
                Gledco Multipurpose Cooperative
            </div>

            <div style="font-size:12px; margin-top:2px;">
                Brgy. 9 Sta. Angela F.R. Castro cor. Balintawak St.<br>
                Laoag City, Ilocos Norte 2900
            </div>

            <div style="font-size:11px; margin-top:4px;">
                <strong>Registration No.</strong> 9520-01001354
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>TIN:</strong> 005-511-934
            </div>
        </td>
    </tr>
</table>


<hr>

<div class="center" style="margin:10px 0 12px 0; font-size:12px;">
    <span class="bold">Division:</span> {{ $divisionName ?? 'All Divisions' }}
    @if(!empty($filterStart) || !empty($filterEnd))
        &nbsp;&nbsp;|&nbsp;&nbsp;
        <span class="bold">Date Range:</span> {{ $filterStart ?? 'All' }} to {{ $filterEnd ?? now()->toDateString() }}
    @endif
</div>

@php
    $selectedType = strtolower(trim($reportType ?? request('report_type', '')));
    $selectedType = $selectedType !== '' ? $selectedType : 'all';
    $allowedTypes = ['all', 'receipts', 'remittance', 'receivables', 'borrowers'];
    if (!in_array($selectedType, $allowedTypes, true)) {
        $selectedType = 'all';
    }

    $printAll = $selectedType === 'all';
    $hasReceipts = $printAll || $selectedType === 'receipts';
    $hasRemittance = $printAll || $selectedType === 'remittance';
    $hasReceivables = $printAll || $selectedType === 'receivables';
    $hasBorrowers = $printAll || $selectedType === 'borrowers';
@endphp

{{-- ========================================================= --}}
{{-- ======================= RECEIPTS ======================== --}}
{{-- ========================================================= --}}

@if($hasReceipts)
<div class="section-title">RECEIPTS</div>

<table>
    <thead>
        <tr>
            <th rowspan="2">Route</th>
            <th rowspan="2">Leadman</th>

            <th colspan="7">Cases Information</th>
            <th colspan="4">Sales Information</th>
            <th colspan="7">Collections Information</th>
            <th colspan="5">Remittance Information</th>
        </tr>
        <tr>
            {{-- CASES --}}
            <th>Full</th>
            <th>Half</th>
            <th>Box</th>
            <th>Total Cases</th>
            <th>Total UCS</th>
            <th># Receipts</th>
            <th>Customers</th>

            {{-- SALES --}}
            <th>Gross</th>
            <th>Discount</th>
            <th>Coupon</th>
            <th>Net</th>

            {{-- COLLECTIONS --}}
            <th>Container Dep.</th>
            <th>Purchased Ref.</th>
            <th>Stock Transfer</th>
            <th>Net Credit</th>
            <th>Shortage Coll.</th>
            <th>AR Coll.</th>
            <th>Other Income</th>

            {{-- REMITTANCE --}}
            <th>Cash Proceeds</th>
            <th>Cash</th>
            <th>Check</th>
            <th>Total Remit</th>
            <th>Over/Short</th>
        </tr>
    </thead>

    <tbody>
        @php
            $tFull = 0; $tHalf = 0; $tBox = 0; $tTotalCases = 0; $tTotalUcs = 0;
            $tReceipts = 0; $tCustomers = 0;
            $tGross = 0; $tSalesDisc = 0; $tCouponDisc = 0; $tNet = 0;
            $tContainers = 0; $tPurchased = 0; $tStock = 0; $tNetCredit = 0;
            $tShortageColl = 0; $tArColl = 0; $tOtherIncome = 0;
            $tCashProceeds = 0; $tCash = 0; $tCheck = 0; $tTotalRemit = 0; $tOverShort = 0;
        @endphp

        @foreach($receipts as $r)
            @php
                $items = $r->items ?? collect();

                $full = (int) $items->sum('full_case');
                $half = (int) $items->sum('half_case');
                $box  = (int) $items->sum('box');
                $totalCases = $full + $half + $box;

                $totalUcs = (float) $items->sum('total_ucs');
                $noOfReceipts = (int) $items->sum('number_of_receipts');
                $customerCount = (int) $items->sum('customer_count');

                $gross = (float) $items->sum('gross_sales');
                $salesDisc = (float) $items->sum('sales_discounts');
                $couponDisc = (float) $items->sum('coupon_discount');

                $netStored = (float) $items->sum('net_sales');
                $cashProceedsStored = (float) $items->sum('cash_proceeds');
                $isLegacyBug = $gross >= 100
                    && abs($netStored - 1.0) < 0.00001
                    && abs($cashProceedsStored - 1.0) < 0.00001;

                $containers = (float) $items->sum('containers_deposit');
                $purchased = (float) $items->sum('purchased_refund');
                $stock = (float) $items->sum('stock_transfer');
                $netCredit = (float) $items->sum('net_credit_sales');
                $shortageColl = (float) $items->sum('shortage_collections');
                $arColl = (float) $items->sum('ar_collections');
                $otherIncome = (float) $items->sum('other_income');

                // Legacy bug: array-to-float cast made many fields become 1.00.
                if ($isLegacyBug) {
                    if (abs($couponDisc - 1.0) < 0.00001) $couponDisc = 0;
                    if (abs($containers - 1.0) < 0.00001) $containers = 0;
                    if (abs($purchased - 1.0) < 0.00001) $purchased = 0;
                    if (abs($stock - 1.0) < 0.00001) $stock = 0;
                    if (abs($netCredit - 1.0) < 0.00001) $netCredit = 0;
                    if (abs($shortageColl - 1.0) < 0.00001) $shortageColl = 0;
                    if (abs($arColl - 1.0) < 0.00001) $arColl = 0;
                    if (abs($otherIncome - 1.0) < 0.00001) $otherIncome = 0;
                }

                // Always compute these using the same formula as the Receipts input table JS.
                $net = $gross - $salesDisc - $couponDisc;
                $cashProceeds =
                    $net
                    + $containers
                    - $purchased
                    + $stock
                    - $netCredit
                    + $shortageColl
                    + $arColl
                    + $otherIncome;

                $cash = (float) $items->sum('remittance_cash');
                $check = (float) $items->sum('remittance_check');
                $totalRemitStored = (float) $items->sum('total_remittance');

                $totalRemit = (abs($cash) > 0.00001 || abs($check) > 0.00001)
                    ? ($cash + $check)
                    : $totalRemitStored;

                // Legacy data sometimes has cash/check stored as 0 but total remittance saved.
                $cashDisplay = $cash;
                $checkDisplay = $check;
                if (abs($cashDisplay) < 0.00001 && abs($checkDisplay) < 0.00001 && $totalRemit > 0) {
                    $cashDisplay = $totalRemit;
                    $checkDisplay = 0;
                }

                $overShort = $totalRemit - $cashProceeds;

                // Totals
                $tFull += $full; $tHalf += $half; $tBox += $box; $tTotalCases += $totalCases; $tTotalUcs += $totalUcs;
                $tReceipts += $noOfReceipts; $tCustomers += $customerCount;
                $tGross += $gross; $tSalesDisc += $salesDisc; $tCouponDisc += $couponDisc; $tNet += $net;
                $tContainers += $containers; $tPurchased += $purchased; $tStock += $stock; $tNetCredit += $netCredit;
                $tShortageColl += $shortageColl; $tArColl += $arColl; $tOtherIncome += $otherIncome;
                $tCashProceeds += $cashProceeds; $tCash += $cashDisplay; $tCheck += $checkDisplay; $tTotalRemit += $totalRemit; $tOverShort += $overShort;
            @endphp

            <tr>
                <td>{{ $r->route }}</td>
                <td>{{ $r->leadman }}</td>

                {{-- CASES --}}
                <td>{{ $full }}</td>
                <td>{{ $half }}</td>
                <td>{{ $box }}</td>
                <td>{{ $totalCases }}</td>
                <td>{{ number_format($totalUcs, 2) }}</td>
                <td>{{ $noOfReceipts }}</td>
                <td>{{ $customerCount }}</td>

                {{-- SALES --}}
                <td class="right">₱ {{ number_format($gross, 2) }}</td>
                <td class="right">₱ {{ number_format($salesDisc, 2) }}</td>
                <td class="right">₱ {{ number_format($couponDisc, 2) }}</td>
                <td class="right">₱ {{ number_format($net, 2) }}</td>

                {{-- COLLECTIONS --}}
                <td class="right">₱ {{ number_format($containers, 2) }}</td>
                <td class="right">₱ {{ number_format($purchased, 2) }}</td>
                <td class="right">₱ {{ number_format($stock, 2) }}</td>
                <td class="right">₱ {{ number_format($netCredit, 2) }}</td>
                <td class="right">₱ {{ number_format($shortageColl, 2) }}</td>
                <td class="right">₱ {{ number_format($arColl, 2) }}</td>
                <td class="right">₱ {{ number_format($otherIncome, 2) }}</td>

                {{-- REMITTANCE --}}
                <td class="right">₱ {{ number_format($cashProceeds, 2) }}</td>
                <td class="right">₱ {{ number_format($cashDisplay, 2) }}</td>
                <td class="right">₱ {{ number_format($checkDisplay, 2) }}</td>
                <td class="right">₱ {{ number_format($totalRemit, 2) }}</td>
                <td class="right">₱ {{ number_format($overShort, 2) }}</td>
            </tr>
        @endforeach

        <tr class="bold">
            <td class="right" colspan="2">TOTAL</td>
            <td>{{ $tFull }}</td>
            <td>{{ $tHalf }}</td>
            <td>{{ $tBox }}</td>
            <td>{{ $tTotalCases }}</td>
            <td>{{ number_format($tTotalUcs, 2) }}</td>
            <td>{{ $tReceipts }}</td>
            <td>{{ $tCustomers }}</td>
            <td class="right">₱ {{ number_format($tGross, 2) }}</td>
            <td class="right">₱ {{ number_format($tSalesDisc, 2) }}</td>
            <td class="right">₱ {{ number_format($tCouponDisc, 2) }}</td>
            <td class="right">₱ {{ number_format($tNet, 2) }}</td>
            <td class="right">₱ {{ number_format($tContainers, 2) }}</td>
            <td class="right">₱ {{ number_format($tPurchased, 2) }}</td>
            <td class="right">₱ {{ number_format($tStock, 2) }}</td>
            <td class="right">₱ {{ number_format($tNetCredit, 2) }}</td>
            <td class="right">₱ {{ number_format($tShortageColl, 2) }}</td>
            <td class="right">₱ {{ number_format($tArColl, 2) }}</td>
            <td class="right">₱ {{ number_format($tOtherIncome, 2) }}</td>
            <td class="right">₱ {{ number_format($tCashProceeds, 2) }}</td>
            <td class="right">₱ {{ number_format($tCash, 2) }}</td>
            <td class="right">₱ {{ number_format($tCheck, 2) }}</td>
            <td class="right">₱ {{ number_format($tTotalRemit, 2) }}</td>
            <td class="right">₱ {{ number_format($tOverShort, 2) }}</td>
        </tr>
    </tbody>
</table>

@if($hasRemittance || $hasReceivables || $hasBorrowers)
    <div class="page-break"></div>
@endif
@endif

{{-- ========================================================= --}}
{{-- ======================= REMITTANCE ====================== --}}
{{-- ========================================================= --}}

@if($hasRemittance)
<div class="section-title">REMITTANCE – CHECK PAYMENTS</div>
<table>
    <thead>
        <tr>
            <th>Bank</th>
            <th>Account #</th>
            <th>Account Name</th>
            <th>Check Date</th>
            <th>Remarks</th>
            <th class="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $totalChecks = 0; @endphp
        @foreach($remittances as $r)
            @foreach($r->items->where('type','check') as $i)
                @php
                    $totalChecks += (float) ($i->amount ?? 0);
                    $parts = array_map('trim', explode('|', $i->description ?? ''));
                    $bankName = $i->bank_name ?: ($parts[0] ?? '');
                    $accountName = $i->account_name ?: ($parts[1] ?? '');
                    $accountNumber = $i->account_number ?: ($parts[2] ?? '');
                @endphp

                <tr>
                    <td>{{ $bankName ?: '—' }}</td>
                    <td>{{ $accountNumber ?: '—' }}</td>
                    <td>{{ $accountName ?: '—' }}</td>
                    <td>{{ $i->check_date ? $i->check_date->format('Y-m-d') : '' }}</td>
                    <td>{{ $i->remarks ?? '' }}</td>
                    <td class="right">₱ {{ number_format((float) ($i->amount ?? 0), 2) }}</td>
                </tr>
            @endforeach
        @endforeach

        <tr class="bold">
            <td colspan="5" class="right">TOTAL CHECKS</td>
            <td class="right">₱ {{ number_format($totalChecks, 2) }}</td>
        </tr>
    </tbody>
</table>

<br>

<div class="section-title">REMITTANCE – CASH DENOMINATIONS</div>

<table>
    <thead>
        <tr>
            <th>Denomination</th>
            <th>PCS</th>
            <th class="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $totalCash = 0; @endphp
        @foreach($remittances as $r)
            @foreach($r->items->where('type','cash') as $i)
                @php $totalCash += (float) ($i->amount ?? 0); @endphp
                <tr>
                    <td class="right">{{ $i->denomination !== null ? number_format((float) $i->denomination, 2) : '—' }}</td>
                    <td class="right">{{ $i->pcs !== null ? (int) $i->pcs : '—' }}</td>
                    <td class="right">₱ {{ number_format((float) ($i->amount ?? 0), 2) }}</td>
                </tr>
            @endforeach
        @endforeach

        <tr class="bold">
            <td colspan="2" class="right">TOTAL CASH</td>
            <td class="right">₱ {{ number_format($totalCash, 2) }}</td>
        </tr>
    </tbody>
</table>

@if($hasReceivables || $hasBorrowers)
    <div class="page-break"></div>
@endif
@endif

{{-- ========================================================= --}}
{{-- ====================== RECEIVABLES ====================== --}}
{{-- ========================================================= --}}

@if($hasReceivables)
<div class="section-title">RECEIVABLES – ALL DETAILS</div>

@foreach($receivables as $r)
<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Reference</th>
            <th>Customer / Description</th>
            <th>Terms</th>
            <th>Due Date</th>
            <th class="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($r->items as $i)
        <tr>
            <td>{{ $i->type }}</td>
            <td>{{ $i->reference_no }}</td>
            <td>{{ $i->customer_name ?? $i->remarks }}</td>
            <td>{{ $i->terms }}</td>
            <td>{{ $i->due_date }}</td>
            <td class="right">{{ number_format($i->amount,2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<br>
@endforeach

@if($hasBorrowers)
    <div class="page-break"></div>
@endif
@endif

{{-- ========================================================= --}}
{{-- ======================= BORROWERS ======================= --}}
{{-- ========================================================= --}}

@if($hasBorrowers)
<div class="section-title">BORROWERS – AGREEMENT MONITORING</div>

<table>
    <thead>
        <tr>
            <th rowspan="2">Empties</th>
            <th colspan="2">Bodega</th>
            <th colspan="2">CRS 1</th>
            <th colspan="2">CRS 2</th>
            <th colspan="2">CRS 3</th>
            <th colspan="2">Outside</th>
            <th colspan="2">Water</th>
        </tr>
        <tr>
            @for($i=0;$i<6;$i++)
                <th>Borrowed</th>
                <th>Returned</th>
            @endfor
        </tr>
    </thead>

    <tbody>
        @foreach($borrowers as $b)
            @foreach($b->items as $i)
            <tr>
                <td>{{ $i->item_type }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
                <td>{{ $i->borrowed }}</td>
                <td>{{ $i->returned }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
@endif

<script>
    window.onload = function () {
        window.print();
    }
</script>

</body>
</html>
