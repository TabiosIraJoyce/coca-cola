<h3 class="text-lg font-semibold mb-3">📦 Borrower’s Monitoring Agreement</h3>

<?php if(isset($borrowers) && $borrowers->count()): ?>

<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">Division</th>
            <th class="border p-2 text-left">Report Date</th>
            <th class="border p-2 text-right">Total Borrowed</th>
            <th class="border p-2 text-right">Total Returned</th>
            <th class="border p-2 text-right">Net Borrowed</th>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $borrowers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $net = ($b->total_borrowed ?? 0) - ($b->total_returned ?? 0);
            ?>

            <tr class="hover:bg-gray-50">
                
                <td class="border p-2 font-medium">
                    <?php echo e($b->division->division_name ?? '—'); ?>

                </td>

                
                <td class="border p-2">
                    <?php echo e(optional($b->report_date)->format('Y-m-d') ?? '—'); ?>

                </td>

                
                <td class="border p-2 text-right">
                    <?php echo e(number_format($b->total_borrowed ?? 0, 2)); ?>

                </td>

                
                <td class="border p-2 text-right">
                    <?php echo e(number_format($b->total_returned ?? 0, 2)); ?>

                </td>

                
                <td class="border p-2 text-right font-semibold
                    <?php echo e($net > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                    <?php echo e(number_format($net, 2)); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php else: ?>
<p class="text-center text-gray-500 italic py-4">
    No borrower records available.
</p>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\consolidated\partials\borrower.blade.php ENDPATH**/ ?>