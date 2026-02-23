<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection Report - Treasury</title>
    <style>
        body {
            font-family: "Arial Narrow", Arial, sans-serif;
            color: #000;
            padding: 40px;
            font-size: 17px;
        }

        h1, h2, h3 {
            text-align: center;
            margin: 0;
        }

        .coop-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .report-title {
            margin-top: 10px;
            font-size: 24px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 16px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 12px 10px;
            text-align: center;
            font-family: "Arial Narrow", Arial, sans-serif;
        }

        th {
            background: #f0f0f0;
            font-size: 16px;
        }

        .total-row {
            font-weight: bold;
            background: #fff9c4;
        }

        @media print {
            .no-print {
                display: none;
            }

            @page {
                size: 8.5in 13in landscape;
                margin: 1in 0.25in;
            }

            body {
                font-size: 17px;
                font-family: "Arial Narrow", Arial, sans-serif;
            }

            table {
                font-size: 16px;
            }

            th, td {
                font-size: 16px;
                padding: 12px 10px;
                font-family: "Arial Narrow", Arial, sans-serif;
            }
        }
    </style>
</head>
<body>

    <div class="coop-info">
        <h2>Gledco Multipurpose Cooperative</h2>
        <p>
            Brgy. 9, Sta. Angela, F.R. Castro cor. Balintawak St.,<br>
            Laoag City, Ilocos Norte<br>
            Registration Number: 9520-01001354<br>
            TIN: 005-511-934
        </p>

        <h3 class="report-title">Collection Report - Treasury</h3>

        <p style="text-align: center; font-size: 16px; margin-top: 5px;">
            Coverage: <strong><?php echo e($dateRange); ?></strong>
        </p>

        <p style="text-align: center; font-size: 16px;">
            Division: <strong><?php echo e($divisionName); ?></strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e(strtoupper($field)); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>Total (Cash)</th>
                <th>Total (Check)</th>
                <th>Total (Cash + Check)</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $dailyData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($row['date']); ?></td>
                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><?php echo e(number_format($row[$field] ?? 0, 2)); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e(number_format($row['Total Collection (Cash)'] ?? 0, 2)); ?></td>
                    <td><?php echo e(number_format($row['Total Collection (Check)'] ?? 0, 2)); ?></td>
                    <td><?php echo e(number_format($row['Total Collection (Cash + Check)'] ?? 0, 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <tr class="total-row">
                <td>Total</td>
                <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e(number_format($totals[$field] ?? 0, 2)); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e(number_format($totalCash, 2)); ?></td>
                <td><?php echo e(number_format($totalCheck, 2)); ?></td>
                <td><?php echo e(number_format($totalCombined, 2)); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 30px;">
        <form id="signatoriesForm" onsubmit="applySignatories(event)">
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div style="width: 48%;">
                    <label><strong>Prepared by:</strong></label><br>
                    <input type="text" id="preparedByName" placeholder="Name" style="width: 100%; padding: 6px;"><br>
                    <input type="text" id="preparedByPosition" placeholder="Position" style="width: 100%; padding: 6px;">
                </div>
                <div style="width: 48%;">
                    <label><strong>Approved by:</strong></label><br>
                    <input type="text" id="approvedByName" placeholder="Name" style="width: 100%; padding: 6px;"><br>
                    <input type="text" id="approvedByPosition" placeholder="Position" style="width: 100%; padding: 6px;">
                </div>
            </div>
            <button type="submit" style="padding: 8px 20px; background: green; color: white; border: none; border-radius: 4px;">
                ✅ Apply Signatories
            </button>
        </form>
    </div>

    <div id="signatoriesSection" style="margin-top: 60px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <strong>Prepared by:</strong><br><br><br>
                <span id="preparedByOutputName" style="display: inline-block; border-top: 1px solid #000; padding-top: 2px;"></span><br>
                <em id="preparedByOutputPosition"></em>
            </div>
            <div>
                <strong>Approved by:</strong><br><br><br>
                <span id="approvedByOutputName" style="display: inline-block; border-top: 1px solid #000; padding-top: 2px;"></span><br>
                <em id="approvedByOutputPosition"></em>
            </div>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 40px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">🖨️ Print Now</button>
    </div>

    <script>
        function applySignatories(event) {
            event.preventDefault();
            document.getElementById('preparedByOutputName').textContent = document.getElementById('preparedByName').value;
            document.getElementById('preparedByOutputPosition').textContent = document.getElementById('preparedByPosition').value;
            document.getElementById('approvedByOutputName').textContent = document.getElementById('approvedByName').value;
            document.getElementById('approvedByOutputPosition').textContent = document.getElementById('approvedByPosition').value;
            alert('✅ Signatories applied. You may now print.');
            document.getElementById('signatoriesSection').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\print-treasury.blade.php ENDPATH**/ ?>