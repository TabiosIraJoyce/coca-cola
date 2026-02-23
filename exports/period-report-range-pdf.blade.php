<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Period Reports</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 25px;
            font-size: 12px;
            color: #333;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #444;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 12px;
            color: #777;
            margin-bottom: 15px;
        }

        /* Section Title */
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: white;
            background: #FF6B00;
            padding: 8px 12px;
            border-radius: 6px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 18px;
        }

        /* Header row with colored blocks */
        thead th {
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            border-right: 1px solid #fff;
        }

        /* Column color themes */
        .col-blue   { background: #4A90E2; }
        .col-green  { background: #2ECC71; }
        .col-orange { background: #FF8C00; }
        .col-purple { background: #9B59B6; }
        .col-grey   { background: #7F8C8D; }

        /* Row styling */
        tbody td {
            padding: 8px;
            background: #F7F7F7;
            border-bottom: 1px solid #E0E0E0;
            text-align: center;
        }

        tbody tr:nth-child(even) td {
            background: #F0F0F0;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

@foreach ($parsedReports as $data)
    @php
        $report        = $data['report'];
        $salesRows     = $data['coreRows'];
        $inventoryRows = $data['inventoryRows'];
        $customTables  = $data['customTables'];
    @endphp


    <!-- HEADER -->
    <h2 class="title">Period {{ $report->period_no }} — {{ $report->branch }}</h2>
    <p class="subtitle">Date: {{ $report->display_date }}</p>


    <!-- PERIOD SUMMARY -->
    <div class="section-title">PERIOD SUMMARY</div>

    <table>
        <thead>
            <tr>
                <th class="col-blue">Target Sales</th>
                <th class="col-green">Actual Sales</th>
                <th class="col-orange">Variance</th>
                <th class="col-purple">Achievement %</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>₱{{ number_format($report->target_sales,2) }}</td>
                <td>₱{{ number_format($report->actual_sales_calc,2) }}</td>
                <td>₱{{ number_format($report->variance_calc,2) }}</td>
                <td>{{ number_format($report->achievement_pct_calc,2) }}%</td>
            </tr>
        </tbody>
    </table>


    <!-- DAILY SALES -->
    <div class="section-title">DAILY COKE SALES</div>

    <table>
        <thead>
            <tr>
                <th class="col-grey">Date</th>
                <th class="col-blue">Pack</th>
                <th class="col-green">Product</th>
                <th class="col-orange">Core Cases</th>
                <th class="col-purple">Core UCS</th>
                <th class="col-grey">Core Total</th>
                <th class="col-blue">IWS Cases</th>
                <th class="col-green">IWS UCS</th>
                <th class="col-orange">IWS Total</th>
                <th class="col-purple">Grand Total</th>
            </tr>
        </thead>

        <tbody>
            @if(count($salesRows) === 0)
                <tr>
                    <td colspan="10" style="text-align:center; font-style:italic;">
                        No sales data recorded for this period.
                    </td>
                </tr>
            @else
            @foreach($salesRows as $row)
                @php
                    $coreCases = $row['core_pcs'] ?? 0;
                    $coreUcs   = $row['core_ucs'] ?? 0;
                    $coreTotal = $row['core_total_ucs'] ?? ($coreCases + $coreUcs);

                    $iwsCases  = $row['iws_pcs'] ?? 0;
                    $iwsUcs    = $row['iws_ucs'] ?? 0;
                    $iwsTotal  = $row['iws_total_ucs'] ?? ($iwsCases + $iwsUcs);

                    $grand     = $coreTotal + $iwsTotal;
                @endphp

                <tr>
                    <td>{{ $row['movement_date'] ?? '' }}</td>
                    <td>{{ $row['pack'] }}</td>
                    <td>{{ $row['product'] }}</td>

                    <td>{{ $coreCases }}</td>
                    <td>{{ $coreUcs }}</td>
                    <td>{{ $coreTotal }}</td>

                    <td>{{ $iwsCases }}</td>
                    <td>{{ $iwsUcs }}</td>
                    <td>{{ $iwsTotal }}</td>

                    <td>{{ $grand }}</td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>


    <!-- INVENTORY -->
    @if(count($inventoryRows) > 0)
        <div class="section-title">INVENTORY & DAYS LEVEL</div>

        <table>
            <thead>
            <tr>
                <th class="col-blue">Pack</th>
                <th class="col-green">Product</th>
                <th class="col-orange">SRP</th>
                <th class="col-purple">Actual Inv</th>
                <th class="col-blue">ADS</th>
                <th class="col-green">Booking</th>
                <th class="col-orange">Deliveries</th>
                <th class="col-purple">P5</th>
                <th class="col-grey">7 Days</th>
            </tr>
            </thead>

            <tbody>
            @foreach($inventoryRows as $row)
                <tr>
                    <td>{{ $row['pack'] }}</td>
                    <td>{{ $row['product'] }}</td>
                    <td>{{ $row['srp'] }}</td>
                    <td>{{ $row['actual_inv'] }}</td>
                    <td>{{ $row['ads'] }}</td>
                    <td>{{ $row['booking'] }}</td>
                    <td>{{ $row['deliveries'] }}</td>
                    <td>{{ $row['routing_days_p5'] }}</td>
                    <td>{{ $row['routing_days_7'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif


    <!-- CUSTOM TABLES -->
    @foreach($customTables as $tbl)
        <div class="section-title">{{ $tbl['title'] }}</div>

        <table>
            <thead>
                <tr>
                    @foreach($tbl['headers'] as $i => $header)
                        <th class="col-blue">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($tbl['rows'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach


    <div class="page-break"></div>

@endforeach

</body>
</html>
