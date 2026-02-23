
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Remittance Details PDF</title>
    <?php echo $__env->make('admin.reports.exports._styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>

    <div class="pdf-header">
        <img src="<?php echo e(public_path('images/gledco-logo.png')); ?>" class="logo" alt="Gledco Logo">
        <div class="pdf-meta">
            <div style="font-weight:800; font-size:14px">GLEDCO SALES REPORT SYSTEM</div>
            <div>Division: <?php echo e($data['meta']['divisionName'] ?? 'All Divisions'); ?></div>
            <div>Report: Remittance | Date Range: <?php echo e($data['meta']['start'] ?? '—'); ?> to <?php echo e($data['meta']['end'] ?? '—'); ?></div>
            <div>Generated: <?php echo e($data['meta']['generated_at'] ?? now()->format('Y-m-d H:i')); ?></div>
        </div>
    </div>

    
    <div class="outer-box">
        <div class="section-title">Remittance Details — Check Payments</div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name of Bank</th>
                    <th>Account #</th>
                    <th>Account Name</th>
                    <th>Check Date</th>
                    <th>Remarks</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['records']['checks'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e($c['bank'] ?? ''); ?></td>
                        <td><?php echo e($c['account_no'] ?? ''); ?></td>
                        <td><?php echo e($c['account_name'] ?? ''); ?></td>
                        <td><?php echo e($c['check_date'] ?? ''); ?></td>
                        <td><?php echo e($c['remarks'] ?? ''); ?></td>
                        <td class="numeric"><?php echo e(number_format($c['amount'] ?? 0,2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php for($r=0;$r<8;$r++): ?>
                    <tr><?php for($c=0;$c<7;$c++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    
    <div class="outer-box">
        <div class="section-title">Cash Details (Denominations) & Totals</div>

        <table>
            <thead>
                <tr>
                    <th>#</th><th>Denomination</th><th>PCS</th><th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['records']['denominations'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td class="numeric"><?php echo e(number_format($d['denomination'] ?? 0,2)); ?></td>
                        <td class="numeric"><?php echo e($d['pcs'] ?? ''); ?></td>
                        <td class="numeric"><?php echo e(number_format($d['amount'] ?? 0,2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php for($r=0;$r<6;$r++): ?>
                    <tr><?php for($c=0;$c<4;$c++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <?php endfor; ?>
            </tbody>
        </table>

        
        <table style="margin-top:8px;">
            <tbody>
                <tr>
                    <td style="width:65%"></td>
                    <td style="width:35%">
                        <table style="width:100%">
                            <tr>
                                <td style="background:#fff;border:none;padding:6px">Total Cash & Checks</td>
                                <td class="numeric"><?php echo e(number_format($data['totals']['total_cash_checks'] ?? 0,2)); ?></td>
                            </tr>
                            <tr>
                                <td style="background:#fff;border:none;padding:6px">Total Remitted</td>
                                <td class="numeric"><?php echo e(number_format($data['totals']['total_remit'] ?? 0,2)); ?></td>
                            </tr>
                            <tr>
                                <td style="background:#fff;border:none;padding:6px">Overage/Shortage</td>
                                <td class="numeric"><?php echo e(number_format($data['totals']['shortage_overage'] ?? 0,2)); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="pdf-footer">Generated by Gledco Sales Report System</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\exports\remittance-pdf.blade.php ENDPATH**/ ?>