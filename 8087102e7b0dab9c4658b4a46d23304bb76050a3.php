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

    
    <div style="text-align:center;">
        <img src="<?php echo e(public_path('logo.png')); ?>" alt="Logo" style="width:80px; height:auto;">
    </div>

    
    <div class="title">CONSOLIDATED PERIOD REPORT</div>
    <div class="subtitle">GLEDCO ENTERPRISES</div>

    
    <div class="info">
        <strong>Branch:</strong> <?php echo e(ucfirst($report->branch)); ?> &nbsp; |
        <strong>Period:</strong> <?php echo e($report->period_no); ?> &nbsp; |
        <strong>Status:</strong>
        <span class="status <?php echo e(strtolower($report->status)); ?>">
            <?php echo e(strtoupper($report->status)); ?>

        </span>
        <br>
        <strong>Date Range:</strong>
        <?php echo e($report->date_from); ?> → <?php echo e($report->date_to); ?>

    </div>


    
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
            <?php $__currentLoopData = [
                'Core' => ['core_target','core_actual'],
                'Stills' => ['stills_target','stills_actual'],
                'PET' => ['pet_target','pet_actual'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $target = $report->{$fields[0]};
                $actual = $report->{$fields[1]};
                $variance = $actual - $target;
            ?>
            <tr>
                <td><?php echo e($label); ?></td>
                <td><?php echo e(number_format($target,2)); ?></td>
                <td><?php echo e(number_format($actual,2)); ?></td>
                <td><?php echo e(number_format($variance,2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>


    
    <div class="section-title">REMITTANCE & COLLECTIONS</div>
    <table>
        <tbody>
            <tr>
                <th>Cash Proceeds</th>
                <td>₱<?php echo e(number_format($report->cash_proceeds,2)); ?></td>
            </tr>
            <tr>
                <th>Cash Remitted</th>
                <td>₱<?php echo e(number_format($report->cash_remitted,2)); ?></td>
            </tr>
            <tr>
                <th>Cheque Remitted</th>
                <td>₱<?php echo e(number_format($report->cheque_remitted,2)); ?></td>
            </tr>
            <tr>
                <th>Total Remitted</th>
                <td>₱<?php echo e(number_format($report->total_remitted,2)); ?></td>
            </tr>
            <tr>
                <th>Shortage / Overage</th>
                <td><?php echo e(number_format($report->shortage_overage,2)); ?></td>
            </tr>
        </tbody>
    </table>


    
    <div class="section-title">RECEIVABLES SUMMARY</div>
    <table>
        <tbody>
            <tr>
                <th>Beginning Receivables</th>
                <td>₱<?php echo e(number_format($report->receivables_begin,2)); ?></td>
            </tr>
            <tr>
                <th>Collections</th>
                <td>₱<?php echo e(number_format($report->receivables_collected,2)); ?></td>
            </tr>
            <tr>
                <th>Ending Receivables</th>
                <td>₱<?php echo e(number_format($report->receivables_end,2)); ?></td>
            </tr>
            <tr>
                <th>Uncollected</th>
                <td>₱<?php echo e(number_format($report->uncollected,2)); ?></td>
            </tr>
        </tbody>
    </table>


    
    <div class="section-title">DETAILS</div>
    <table>
        <thead>
            <tr>
                <?php $__currentLoopData = $detail->columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($col); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $detail->rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><?php echo e($cell); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\periods\pdf.blade.php ENDPATH**/ ?>