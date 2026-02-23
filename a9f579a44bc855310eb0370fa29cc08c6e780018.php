<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($type); ?> Sales Report - Gledco</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            padding: 40px;
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
            font-size: 20px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 6px 10px;
            text-align: center;
        }
        th {
            background: #f0f0f0;
        }
        .total-row {
            font-weight: bold;
            background: #fff9c4;
        }
        @media print {
            .no-print {
                display: none;
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

        <h3 class="report-title"><?php echo e($type); ?> Sales Report</h3>

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
            <th>Day</th>
            <th>Date</th>
            <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th><?php echo e(strtoupper($label)); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $dailyData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($row['Day']); ?></td>
                <td><?php echo e($row['Date']); ?></td>
                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <?php
                            $value = $row[$label] ?? '';
                        ?>
                        <?php echo e(is_numeric($value) ? number_format($value, 2) : $value); ?>

                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <tr class="total-row">
            <td colspan="2">TOTAL</td>
            <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td>
                    <?php
                        $total = $totals[$label] ?? '';
                    ?>
                    <?php echo e(is_numeric($total) ? number_format($total, 2) : $total); ?>

                </td>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
    </tbody>
</table>


    <div class="no-print mb-8">
        <form id="signatoriesForm" onsubmit="applySignatories(event)">
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Prepared by:</label>
                    <input type="text" id="preparedByName" placeholder="Name" class="border p-2 rounded w-full mb-1">
                    <input type="text" id="preparedByPosition" placeholder="Position" class="border p-2 rounded w-full">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Approved by:</label>
                    <input type="text" id="approvedByName" placeholder="Name" class="border p-2 rounded w-full mb-1">
                    <input type="text" id="approvedByPosition" placeholder="Position" class="border p-2 rounded w-full">
                </div>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow">
                ✅ Apply Signatories
            </button>
        </form>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px;">🖨️ Print Now</button>
    </div>

    <div id="signatoriesSection" style="margin-top: 60px;">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <strong>Prepared by:</strong><br>
                <span id="preparedByOutputName"></span><br>
                <em id="preparedByOutputPosition"></em>
            </div>
            <div>
                <strong>Approved by:</strong><br>
                <span id="approvedByOutputName"></span><br>
                <em id="approvedByOutputPosition"></em>
            </div>
        </div>
    </div>

</body>

<script>
    function applySignatories(event) {
        event.preventDefault();

        document.getElementById('preparedByOutputName').textContent = document.getElementById('preparedByName').value;
        document.getElementById('preparedByOutputPosition').textContent = document.getElementById('preparedByPosition').value;

        document.getElementById('approvedByOutputName').textContent = document.getElementById('approvedByName').value;
        document.getElementById('approvedByOutputPosition').textContent = document.getElementById('approvedByPosition').value;

        alert('✅ Signatories applied. You may now print.');

        // Optionally scroll to signatory section
        document.getElementById('signatoriesSection').scrollIntoView({ behavior: 'smooth' });
    }
</script>

</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\print.blade.php ENDPATH**/ ?>