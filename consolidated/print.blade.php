<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consolidated Print Preview</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 30px;
        }

        h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        h3 {
            font-size: 16px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        table th, table td {
            border: 1px solid #444;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }

        table thead {
            background: #e8e8e8;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Page break for printing */
        .page-break {
            page-break-after: always;
        }
    </style>

</head>
<body>

    <h2>📘 Gledco Consolidated Report</h2>

    <p>
        <strong>Division:</strong> 
        {{ request('division_id') ? \App\Models\Division::find(request('division_id'))->division_name : 'All Divisions' }}
        <br>
        <strong>Date Range:</strong>
        {{ request('date_from') ? request('date_from') : '—' }} 
        to 
        {{ request('date_to') ? request('date_to') : '—' }}
        <br>
        <strong>Generated At:</strong> {{ now() }}
    </p>

    {{-- ============================
            RECEIPTS BREAKDOWN
    ============================= --}}
    <div class="section">
        <h3>🧾 Receipts Breakdown</h3>
        @include('admin.consolidated.partials.receipt')
    </div>

    <div class="page-break"></div>

    {{-- ============================
            REMITTANCE DETAILS
    ============================= --}}
    <div class="section">
        <h3>💵 Remittance Details</h3>
        @include('admin.consolidated.partials.remittance')
    </div>

    <div class="page-break"></div>

    {{-- ============================
            RECEIVABLE
    ============================= --}}
    <div class="section">
        <h3>📘 Receivables Monitoring</h3>
        @include('admin.consolidated.partials.receivable')
    </div>

    <div class="page-break"></div>

    {{-- ============================
            BORROWER
    ============================= --}}
    <div class="section">
        <h3>📦 Borrower's Monitoring Agreement</h3>
        @include('admin.consolidated.partials.borrower')
    </div>

</body>
</html>
