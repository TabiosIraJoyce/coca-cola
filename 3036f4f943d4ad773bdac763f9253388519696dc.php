<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Consolidated Cash Flow Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #000;
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
            border: 1px solid #000;
            padding: 4px;
        }

        .section {
            background: #cfe8cf;
            font-weight: bold;
        }

        .subsection {
            background: #fff3cd;
        }

        .total {
            background: #b6d7a8;
            font-weight: bold;
        }

        .header-line {
            margin-bottom: 4px;
        }

        .small {
            font-size: 9px;
        }

        /* ✅ ADDED — PRINT PREVIEW SUPPORT */
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>


<body onload="window.print()">


<div class="center" style="margin-bottom:8px;">
    <img src="file:///<?php echo e(public_path('gledco-logo.png')); ?>" style="height:60px; margin-bottom:6px;">
    <br>
    <strong>Gledco Multipurpose Cooperative</strong><br>
    Brgy. 9 Sto. Angel F.R. Castro cor. Balintawak St.<br>
    Laoag City, Ilocos Norte 2900<br>
    <span class="small">TIN: 005-511-934</span>
</div>

<hr>

<div class="center header-line">
    <strong>Division:</strong> <?php echo e($divisionId ? 'Selected Division' : 'All Divisions'); ?> |
    <strong>Date Range:</strong> <?php echo e($start); ?> to <?php echo e($end); ?>

</div>


<br>

<?php if($receipts->isNotEmpty()): ?>
<strong>RECEIPTS DETAILS</strong>
<table>
    <tr class="subsection">
        <th>Date</th>
        <th>Route</th>
        <th class="right">Amount</th>
    </tr>
    <?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($r->report_date); ?></td>
        <td><?php echo e($r->route); ?></td>

        
        <td class="right">
            <?php echo e(number_format($r->items->sum('total_remittance'), 2)); ?>

        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

<?php if($remittances->isNotEmpty()): ?>
<br>
<strong>REMITTANCE DETAILS</strong>
<table>
    <tr class="subsection">
        <th>Date</th>
        <th class="right">Amount</th>
    </tr>
    <?php $__currentLoopData = $remittances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($r->report_date); ?></td>
        <td class="right"><?php echo e(number_format($r->items->sum('amount'),2)); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

<?php if($receivables->isNotEmpty()): ?>
<br>
<strong>RECEIVABLES DETAILS</strong>
<table>
    <tr class="subsection">
        <th>Date</th>
        <th>Customer</th>
        <th class="right">Amount</th>
    </tr>
    <?php $__currentLoopData = $receivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $__currentLoopData = $r->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($r->report_date); ?></td>
            <td><?php echo e($i->customer_name); ?></td>
            <td class="right"><?php echo e(number_format($i->amount,2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\pdf\all-reports.blade.php ENDPATH**/ ?>