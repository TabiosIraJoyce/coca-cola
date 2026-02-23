<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipts Report</title>
   <style>
    @page {
        size: A3 landscape;
        margin: 10mm;
    }

    body {
        font-family: "DejaVu Sans", Arial, sans-serif;
        margin: 0;
        padding: 0;
    }

    .section-title {
        font-size: 22px; /* reduced from 28–32 */
        font-weight: 800;
        margin-bottom: 6px;
    }

    .outer-box {
        border: 2px solid #2B144C;
        padding: 8px;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    thead th {
        background: #7A1812;
        color: #fff;
        font-size: 7.5px;      /* ↓ smaller */
        padding: 4px 2px;      /* ↓ tighter spacing */
        border: 1.5px solid #2B144C;
        line-height: 1.1;
    }

    tbody td {
        background: #FCEBCC;
        border: 1px solid #000;
        font-size: 7.2px;      /* ↓ smaller than before */
        padding: 3px 2px;      /* ↓ tighter */
        height: 24px;          /* ↓ shorter rows */
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* SALES TABLE — even smaller for many columns */
    .sales-table th {
        font-size: 6.8px;      /* ↓ smaller */
        padding: 3px 2px;
    }

    .sales-table td {
        font-size: 6.8px;      /* ↓ smaller */
        padding: 3px 2px;
        height: 22px;
    }

    /* Column width hints – keep but they now fit better */
    .receipts-table th:nth-child(1), .receipts-table td:nth-child(1) { width: 8%; }
    .receipts-table th:nth-child(2), .receipts-table td:nth-child(2) { width: 14%; }
    .receipts-table th:nth-child(3), .receipts-table td:nth-child(3) { width: 6%; }
    .receipts-table th:nth-child(4), .receipts-table td:nth-child(4) { width: 6%; }
    .receipts-table th:nth-child(5), .receipts-table td:nth-child(5) { width: 7%; }
    .receipts-table th:nth-child(6), .receipts-table td:nth-child(6) { width: 14%; }
    .receipts-table th:nth-child(7), .receipts-table td:nth-child(7) { width: 14%; }
    .receipts-table th:nth-child(8), .receipts-table td:nth-child(8) { width: 10%; }
    .receipts-table th:nth-child(9), .receipts-table td:nth-child(9) { width: 11%; }
</style>

</head>
<body>

    <div class="pdf-header">
        <img src="<?php echo e(public_path('images/gledco-logo.png')); ?>" class="logo" alt="Gledco Logo">
        <div class="pdf-meta">
            <div style="font-weight:800; font-size:14px">GLEDCO SALES REPORT SYSTEM</div>
            <div>Division: <?php echo e($data['meta']['divisionName'] ?? 'All Divisions'); ?></div>
            <div>Report: Receipts | Date Range: <?php echo e($data['meta']['start'] ?? '—'); ?> to <?php echo e($data['meta']['end'] ?? '—'); ?></div>
            <div>Generated: <?php echo e($data['meta']['generated_at'] ?? now()->format('Y-m-d H:i')); ?></div>
        </div>
    </div>

    <div class="outer-box">
        <div class="section-title">Receipts Breakdown</div>

        <table>
            <thead>
                <tr>
                    <th class="small">DATE</th>
                    <th class="small">LEADMAN</th>
                    <th class="small">FC</th>
                    <th class="small">HC</th>
                    <th class="small">BOX</th>
                    <th class="small">TOTAL CASES</th>
                    <th class="small">TOTAL UCS</th>
                    <th class="small">NO. OF RECEIPTS</th>
                    <th class="small">CUSTOMER COUNTS</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['records'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($r->date ?? ''); ?></td>
                        <td><?php echo e($r->leadman ?? ''); ?></td>
                        <td><?php echo e($r->fc ?? ''); ?></td>
                        <td><?php echo e($r->hc ?? ''); ?></td>
                        <td class="numeric"><?php echo e($r->box ?? 0); ?></td>
                        <td class="numeric"><?php echo e(number_format($r->total_cases ?? 0,2)); ?></td>
                        <td class="numeric"><?php echo e(number_format($r->total_ucs ?? 0,2)); ?></td>
                        <td class="numeric"><?php echo e($r->number_of_receipts ?? 0); ?></td>
                        <td class="numeric"><?php echo e($r->customer_counts ?? 0); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php for($i = 0; $i < max(0, 12 - count($data['records'] ?? [])); $i++): ?>
                    <tr>
                        <?php for($c=0;$c<9;$c++): ?><td>&nbsp;</td><?php endfor; ?>
                    </tr>
                <?php endfor; ?>

            </tbody>
        </table>
    </div>

    <div class="outer-box">
        <div class="section-title">Sales Information</div>

        <table>
            <thead>
                <tr>
                    <th>GROSS SALES</th><th>SALES DISCOUNTS</th><th>COUPON DISCOUNTS</th><th>NET SALES</th>
                    <th>CONTAINERS DEPOSIT</th><th>PURCHASED REFUND</th><th>STOCK TRANSFER</th><th>NET CREDIT SALES</th>
                    <th>SHORTAGE COLLECTIONS</th><th>AR COLLECTIONS</th><th>OTHER INCOME</th><th>CASH PROCEEDS</th>
                    <th>REMITTANCE (CASH)</th><th>REMITTANCE (CHECK)</th><th>TOTAL REMITTANCE</th><th>SHORTAGE/OVERAGE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="numeric"><?php echo e(number_format($data['totals']['gross_sales'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['sales_discounts'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['coupon_discounts'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['net_sales'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['containers_deposit'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['purchased_refund'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['stock_transfer'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['net_credit_sales'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['shortage_collections'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['ar_collections'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['other_income'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['cash_proceeds'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['remit_cash'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['remit_check'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['total_remit'] ?? 0,2)); ?></td>
                    <td class="numeric"><?php echo e(number_format($data['totals']['shortage_overage'] ?? 0,2)); ?></td>
                </tr>

                
                <?php for($r=0;$r<6;$r++): ?>
                    <tr><?php for($c=0;$c<16;$c++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <?php endfor; ?>

            </tbody>
        </table>
    </div>

    <div class="pdf-footer">Generated by Gledco Sales Report System</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\exports\receipts-pdf.blade.php ENDPATH**/ ?>