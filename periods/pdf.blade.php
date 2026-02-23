<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a; /* Navy Blue */
            margin-top: 5px;
            margin-bottom: 2px;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #111;
            margin-bottom: 8px;
        }
        .info {
            text-align: center;
            color: #444;
            margin-bottom: 15px;
        }
        .section-title {
            background: #2563eb; /* Blue */
            color: white;
            padding: 5px;
            font-size: 13px;
            font-weight: bold;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
        }
        th {
            background: #e5e7eb; /* Light Gray */
            font-weight: bold;
        }
        .status {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .approved { background-color: #16a34a; color: #fff; }
        .submitted { background-color: #facc15; color: #000; }
        .draft { background-color: #9ca3af; color: #000; }
    </style>
</head>
<body>

    {{-- LOGO --}}
    <div style="text-align:center;">
        <img src="{{ public_path('logo.png') }}" alt="Logo" style="width:80px; height:auto;">
    </div>

    {{-- TITLE --}}
    <div class="title">CONSOLIDATED PERIOD REPORT</div>
    <div class="subtitle">GLEDCO ENTERPRISES</div>

    {{-- INFO BLOCK --}}
    <div class="info">
        <strong>Branch:</strong> {{ ucfirst($report->branch) }} &nbsp; |
        <strong>Period:</strong> {{ $report->period_no }} &nbsp; |
        <strong>Status:</strong>
        <span class="status {{ strtolower($report->status) }}">
            {{ strtoupper($report->status) }}
        </span>
        <br>
        <strong>Date Range:</strong>
        {{ $report->date_from }} → {{ $report->date_to }}
    </div>


    {{-- SALES SUMMARY --}}
    <div class="section-title">SALES SUMMARY</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Target</th>
                <th>Actual</th>
                <th>Variance</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
                'Core' => ['core_target','core_actual'],
                'Stills' => ['stills_target','stills_actual'],
                'PET' => ['pet_target','pet_actual'],
            ] as $label => $fields)
            @php
                $target = $report->{$fields[0]};
                $actual = $report->{$fields[1]};
                $variance = $actual - $target;
            @endphp
            <tr>
                <td>{{ $label }}</td>
                <td>{{ number_format($target,2) }}</td>
                <td>{{ number_format($actual,2) }}</td>
                <td>{{ number_format($variance,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    {{-- REMITTANCE SUMMARY --}}
    <div class="section-title">REMITTANCE & COLLECTIONS</div>
    <table>
        <tbody>
            <tr>
                <th>Cash Proceeds</th>
                <td>₱{{ number_format($report->cash_proceeds,2) }}</td>
            </tr>
            <tr>
                <th>Cash Remitted</th>
                <td>₱{{ number_format($report->cash_remitted,2) }}</td>
            </tr>
            <tr>
                <th>Cheque Remitted</th>
                <td>₱{{ number_format($report->cheque_remitted,2) }}</td>
            </tr>
            <tr>
                <th>Total Remitted</th>
                <td>₱{{ number_format($report->total_remitted,2) }}</td>
            </tr>
            <tr>
                <th>Shortage / Overage</th>
                <td>{{ number_format($report->shortage_overage,2) }}</td>
            </tr>
        </tbody>
    </table>


    {{-- RECEIVABLES --}}
    <div class="section-title">RECEIVABLES SUMMARY</div>
    <table>
        <tbody>
            <tr>
                <th>Beginning Receivables</th>
                <td>₱{{ number_format($report->receivables_begin,2) }}</td>
            </tr>
            <tr>
                <th>Collections</th>
                <td>₱{{ number_format($report->receivables_collected,2) }}</td>
            </tr>
            <tr>
                <th>Ending Receivables</th>
                <td>₱{{ number_format($report->receivables_end,2) }}</td>
            </tr>
            <tr>
                <th>Uncollected</th>
                <td>₱{{ number_format($report->uncollected,2) }}</td>
            </tr>
        </tbody>
    </table>


    {{-- DYNAMIC DETAIL TABLE --}}
    <div class="section-title">DETAILS</div>
    <table>
        <thead>
            <tr>
                @foreach($detail->columns as $col)
                    <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($detail->rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
