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

<?php $__currentLoopData = $parsedReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $report        = $data['report'];
        $salesRows     = $data['coreRows'];
        $inventoryRows = $data['inventoryRows'];
        $customTables  = $data['customTables'];
    ?>


    <!-- HEADER -->
    <h2 class="title">Period <?php echo e($report->period_no); ?> — <?php echo e($report->branch); ?></h2>
    <p class="subtitle">Date: <?php echo e($report->display_date); ?></p>


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
                <td>₱<?php echo e(number_format($report->target_sales,2)); ?></td>
                <td>₱<?php echo e(number_format($report->actual_sales_calc,2)); ?></td>
                <td>₱<?php echo e(number_format($report->variance_calc,2)); ?></td>
                <td><?php echo e(number_format($report->achievement_pct_calc,2)); ?>%</td>
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
            <?php if(count($salesRows) === 0): ?>
                <tr>
                    <td colspan="10" style="text-align:center; font-style:italic;">
                        No sales data recorded for this period.
                    </td>
                </tr>
            <?php else: ?>
            <?php $__currentLoopData = $salesRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $coreCases = $row['core_pcs'] ?? 0;
                    $coreUcs   = $row['core_ucs'] ?? 0;
                    $coreTotal = $row['core_total_ucs'] ?? ($coreCases + $coreUcs);

                    $iwsCases  = $row['iws_pcs'] ?? 0;
                    $iwsUcs    = $row['iws_ucs'] ?? 0;
                    $iwsTotal  = $row['iws_total_ucs'] ?? ($iwsCases + $iwsUcs);

                    $grand     = $coreTotal + $iwsTotal;
                ?>

                <tr>
                    <td><?php echo e($row['movement_date'] ?? ''); ?></td>
                    <td><?php echo e($row['pack']); ?></td>
                    <td><?php echo e($row['product']); ?></td>

                    <td><?php echo e($coreCases); ?></td>
                    <td><?php echo e($coreUcs); ?></td>
                    <td><?php echo e($coreTotal); ?></td>

                    <td><?php echo e($iwsCases); ?></td>
                    <td><?php echo e($iwsUcs); ?></td>
                    <td><?php echo e($iwsTotal); ?></td>

                    <td><?php echo e($grand); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </tbody>
    </table>


    <!-- INVENTORY -->
    <?php if(count($inventoryRows) > 0): ?>
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
            <?php $__currentLoopData = $inventoryRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row['pack']); ?></td>
                    <td><?php echo e($row['product']); ?></td>
                    <td><?php echo e($row['srp']); ?></td>
                    <td><?php echo e($row['actual_inv']); ?></td>
                    <td><?php echo e($row['ads']); ?></td>
                    <td><?php echo e($row['booking']); ?></td>
                    <td><?php echo e($row['deliveries']); ?></td>
                    <td><?php echo e($row['routing_days_p5']); ?></td>
                    <td><?php echo e($row['routing_days_7']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>


    <!-- CUSTOM TABLES -->
    <?php $__currentLoopData = $customTables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="section-title"><?php echo e($tbl['title']); ?></div>

        <table>
            <thead>
                <tr>
                    <?php $__currentLoopData = $tbl['headers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="col-blue"><?php echo e($header); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $tbl['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td><?php echo e($cell); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


    <div class="page-break"></div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\exports\period-report-range-pdf.blade.php ENDPATH**/ ?>