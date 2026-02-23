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
        <?php echo e(request('division_id') ? \App\Models\Division::find(request('division_id'))->division_name : 'All Divisions'); ?>

        <br>
        <strong>Date Range:</strong>
        <?php echo e(request('date_from') ? request('date_from') : '—'); ?> 
        to 
        <?php echo e(request('date_to') ? request('date_to') : '—'); ?>

        <br>
        <strong>Generated At:</strong> <?php echo e(now()); ?>

    </p>

    
    <div class="section">
        <h3>🧾 Receipts Breakdown</h3>
        <?php echo $__env->make('admin.consolidated.partials.receipt', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="page-break"></div>

    
    <div class="section">
        <h3>💵 Remittance Details</h3>
        <?php echo $__env->make('admin.consolidated.partials.remittance', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="page-break"></div>

    
    <div class="section">
        <h3>📘 Receivables Monitoring</h3>
        <?php echo $__env->make('admin.consolidated.partials.receivable', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <div class="page-break"></div>

    
    <div class="section">
        <h3>📦 Borrower's Monitoring Agreement</h3>
        <?php echo $__env->make('admin.consolidated.partials.borrower', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\consolidated\print.blade.php ENDPATH**/ ?>