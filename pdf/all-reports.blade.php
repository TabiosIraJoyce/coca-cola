<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consolidated Cash Flow Report</title>
    <style>
        body {font-family: DejaVu Sans, sans-serif; color:#000; font-size:10px; line-height:1.2;}
        .center {text-align:center;}
        .bold {font-weight:bold;}
        .header-line {margin-bottom:4px;}
        .small{font-size:8px;}
        .section-grid {display:grid; grid-template-columns: repeat(auto-fit,minmax(320px,1fr)); gap:10px; margin-top:8px;}
        .section {border:1px solid #000; padding:6px; background:#fff;}
        .section-title {font-size:10px; font-weight:bold; text-transform:uppercase; margin-bottom:6px;}
        .detail-table {width:100%; border-collapse:collapse; font-size:8px; table-layout:fixed; word-break:break-word;}
        .detail-table th, .detail-table td {border:1px solid #000; padding:3px 4px; white-space:normal;}
        .detail-table th {background:#f3f3f3; font-size:7px;}
        .detail-table tbody tr:nth-child(odd) {background:#fffdf5;}
        .signature-page {margin-top:20px;}
        .signature-table {width:100%; border-collapse:collapse; font-size:8px; margin-top:8px;}
        .signature-table th, .signature-table td {border:1px solid #000; padding:4px;}
        .signature-table th {background:#f5f5f5; font-size:8px; text-transform:uppercase;}
        .signature-cell {min-height:40px; vertical-align:middle;}
        .page-break {page-break-before:always;}
        @page {size: A3 landscape; margin:0.4in;}
        @media print {body {margin:0;} .section-grid {page-break-inside:avoid;} .signature-page {page-break-inside:avoid;}}
    </style>
</head>
<body onload="window.print()">
@php
    $formatDecimal = fn($value) => number_format((float) ($value ?? 0), 2, '.', '');
    $formatInteger = fn($value) => number_format((int) ($value ?? 0), 0, '.', '');
    $formatDate = fn($value) => $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '-';
    $divisionName = $divisionId ? optional($divisions->firstWhere('id', $divisionId))->division_name ?? 'Selected Division' : 'All Divisions';
    $supervisorByDivision = [
        'Gledco Enterprise - Laoag' => 'Jose Calulot',
        'Gledco Enterprise - Solsona' => 'Philip Salvado',
        'Gledco Enterprise - Batac' => 'Kristian Czar Reslin',
    ];
    $todayDate = \Carbon\Carbon::now()->format('Y-m-d');
    $currentTime = \Carbon\Carbon::now()->format('H:i');
    $signatureRows = [
        ['role' => 'Site Supervisor / Leadman', 'particulars' => 'Oversees the route team on the ground', 'auto_name' => $supervisorByDivision[$divisionName] ?? '', 'auto_date' => true, 'auto_time' => true],
        ['role' => 'Admin / Cash Recorder', 'particulars' => 'Captures receipts, remittance, and shortages'],
        ['role' => 'Accounting Representative', 'particulars' => 'Verifies totals and documents'],
        ['role' => 'Division Head / Treasurer', 'particulars' => 'Approves, receives, and files the cash'],
    ];
@endphp
<div class="center" style="margin-bottom:8px;">
    <img src="file:///{{ public_path('gledco-logo.png') }}" style="height:60px; margin-bottom:6px;"><br>
    <strong>Gledco Multipurpose Cooperative</strong><br>
    Brgy. 9 Sto. Angel F.R. Castro cor. Balintawak St.<br>
    Laoag City, Ilocos Norte 2900<br>
    <span class="small">TIN: 005-511-934</span>
</div>
<hr>
<div class="center header-line">
    <strong>Division:</strong> {{ $divisionName }} |
    <strong>Date Range:</strong> {{ $start }} to {{ $end }}
</div>
<br>
<div class="section-grid">
    @if($receipts->isNotEmpty())
    <div class="section">
        <div class="section-title">Receipts Input</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Division</th>
                    <th>Route</th>
                    <th>Leadman</th>
                    <th>Type</th>
                    <th>Full Case</th>
                    <th>Half Case</th>
                    <th>Box</th>
                    <th>Total Cases</th>
                    <th>Total UCS</th>
                    <th>No. Receipts</th>
                    <th>Customer Count</th>
                    <th>Gross Sales</th>
                    <th>Sales Discount</th>
                    <th>Coupon Discount</th>
                    <th>Net Sales</th>
                    <th>Containers Deposit</th>
                    <th>Purchased Refund</th>
                    <th>Stock Transfer</th>
                    <th>Net Credit Sales</th>
                    <th>Shortage Coll.</th>
                    <th>A/R Coll.</th>
                    <th>Other Income</th>
                    <th>Cash Proceeds</th>
                    <th>Cash</th>
                    <th>Check</th>
                    <th>Total Remittance</th>
                    <th>Shortage/Ov.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipts as $receipt)
                    @foreach($receipt->items as $item)
                        <tr>
                            <td>{{ $formatDate($receipt->report_date) }}</td>
                            <td>{{ $receipt->division->division_name ?? 'N/A' }}</td>
                            <td>{{ $receipt->route ?? '—' }}</td>
                            <td>{{ $receipt->leadman ?? '—' }}</td>
                            <td>{{ ucfirst($item->item_type ?? '–') }}</td>
                            <td>{{ $formatInteger($item->full_case) }}</td>
                            <td>{{ $formatInteger($item->half_case) }}</td>
                            <td>{{ $formatInteger($item->box) }}</td>
                            <td>{{ $formatInteger($item->total_cases) }}</td>
                            <td>{{ $formatDecimal($item->total_ucs) }}</td>
                            <td>{{ $formatInteger($item->number_of_receipts) }}</td>
                            <td>{{ $formatInteger($item->customer_count) }}</td>
                            <td>{{ $formatDecimal($item->gross_sales) }}</td>
                            <td>{{ $formatDecimal($item->sales_discounts) }}</td>
                            <td>{{ $formatDecimal($item->coupon_discount) }}</td>
                            <td>{{ $formatDecimal($item->net_sales) }}</td>
                            <td>{{ $formatDecimal($item->containers_deposit) }}</td>
                            <td>{{ $formatDecimal($item->purchased_refund) }}</td>
                            <td>{{ $formatDecimal($item->stock_transfer) }}</td>
                            <td>{{ $formatDecimal($item->net_credit_sales) }}</td>
                            <td>{{ $formatDecimal($item->shortage_collections) }}</td>
                            <td>{{ $formatDecimal($item->ar_collections) }}</td>
                            <td>{{ $formatDecimal($item->other_income) }}</td>
                            <td>{{ $formatDecimal($item->cash_proceeds) }}</td>
                            <td>{{ $formatDecimal($item->remittance_cash) }}</td>
                            <td>{{ $formatDecimal($item->remittance_check) }}</td>
                            <td>{{ $formatDecimal($item->total_remittance) }}</td>
                            <td>{{ $formatDecimal($item->shortage_overage) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @if($remittances->isNotEmpty())
    <div class="section">
        <div class="section-title">Remittance</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Division</th>
                    <th>Type</th>
                    <th>Bank</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Check Date</th>
                    <th>Denom</th>
                    <th>PCS</th>
                    <th>Details</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($remittances as $remittance)
                    @foreach($remittance->items as $item)
                        @php
                            $parts = [];
                            if (!empty($item->description)) {
                                $parts[] = $item->description;
                            }
                            if (!empty($item->remarks)) {
                                $parts[] = $item->remarks;
                            }
                            $details = implode(' | ', $parts);
                        @endphp
                        <tr>
                            <td>{{ $formatDate($remittance->report_date) }}</td>
                            <td>{{ $remittance->division->division_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($item->type) }}</td>
                            <td>{{ $item->bank_name ?? '-' }}</td>
                            <td>{{ $item->account_name ?? '-' }}</td>
                            <td>{{ $item->account_number ?? '-' }}</td>
                            <td>{{ $formatDate($item->check_date) }}</td>
                            <td>{{ $item->denomination ? $formatDecimal($item->denomination) : '-' }}</td>
                            <td>{{ $item->pcs ? $formatInteger($item->pcs) : '-' }}</td>
                            <td>{{ $details ?: '-' }}</td>
                            <td>{{ $formatDecimal($item->amount) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
<div class="section-grid">
    @if($receivables->isNotEmpty())
    <div class="section">
        <div class="section-title">Receivables Monitoring</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Division</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th>Customer</th>
                    <th>Store</th>
                    <th>Terms</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receivables as $receivable)
                    @foreach($receivable->items as $item)
                        <tr>
                            <td>{{ $formatDate($receivable->report_date) }}</td>
                            <td>{{ $receivable->division->division_name ?? 'N/A' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', strtolower($item->type ?? ''))) }}</td>
                            <td>{{ $item->reference_no ?? '-' }}</td>
                            <td>{{ $item->customer->customer ?? ($item->customer_name ?? '-') }}</td>
                            <td>{{ $item->customer->store_name ?? '' }}</td>
                            <td>{{ $item->terms ?? '-' }}</td>
                            <td>{{ $formatDate($item->due_date) }}</td>
                            <td>{{ $formatDecimal($item->amount) }}</td>
                            <td>{{ isset($item->balance) ? $formatDecimal($item->balance) : '-' }}</td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>{{ $item->remarks ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @if($borrowers->isNotEmpty())
    <div class="section">
        <div class="section-title">Borrowers</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Division</th>
                    <th>Item</th>
                    <th>Location</th>
                    <th>Borrowed</th>
                    <th>Returned</th>
                    <th>Net</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrowers as $borrower)
                    @foreach($borrower->items as $item)
                        <tr>
                            <td>{{ $formatDate($borrower->report_date) }}</td>
                            <td>{{ $borrower->division->division_name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($item->item_type) }}</td>
                            <td>{{ ucfirst($item->location) }}</td>
                            <td>{{ $formatDecimal($item->borrowed) }}</td>
                            <td>{{ $formatDecimal($item->returned) }}</td>
                            <td>{{ $formatDecimal(($item->borrowed ?? 0) - ($item->returned ?? 0)) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
<div class="page-break"></div>
<div class="signature-page">
    <div><strong>Signature Log</strong><br><span class="small">Complete this section after printing and before filing.</span></div>
    <p class="small" style="margin:4px 0 12px;">Each division should fill in its own names (site supervisor, admin/cash recorder, etc.) before filing.</p>
    <table class="signature-table">
        <thead>
            <tr>
                <th>Division</th>
                <th>Role</th>
                <th class="particulars">Particulars</th>
                <th>Name</th>
                <th>Signature</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($signatureRows as $row)
            <tr>
                <td class="signature-cell">{{ $divisionName }}</td>
                <td class="signature-cell">{{ data_get($row, 'role', '') }}</td>
                <td class="signature-cell particulars">{{ data_get($row, 'particulars', '') }}</td>
                <td class="signature-cell">{{ data_get($row, 'auto_name', '') }}</td>
                <td class="signature-cell">&nbsp;</td>
                <td class="signature-cell">{{ data_get($row, 'auto_date') ? $todayDate : '' }}</td>
                <td class="signature-cell">{{ data_get($row, 'auto_time') ? $currentTime : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
