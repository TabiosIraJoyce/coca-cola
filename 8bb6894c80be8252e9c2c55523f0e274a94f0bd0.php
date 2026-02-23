<h3 class="text-lg font-semibold mb-3">📘 Receivables Monitoring</h3>

<?php if(isset($receivables) && $receivables->count()): ?>
<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Customer Name</th>
            <th class="border p-2 text-right">Amount</th>
            <th class="border p-2">Due Date</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Remarks</th>

        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $receivables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="border p-2"><?php echo e($rc->client_name ?? '—'); ?></td>

            <td class="border p-2 text-right">
                ₱ <?php echo e(number_format($rc->amount_due ?? 0, 2)); ?>

            </td>

            <<td class="border p-2"><?php echo e($rc->due_date ?? '—'); ?></td>

<td class="border p-2">
    <?php
        $today = \Carbon\Carbon::today();
        $due   = $rc->due_date ? \Carbon\Carbon::parse($rc->due_date) : null;
    ?>

    <?php if(!$due): ?>
        <span class="text-gray-400 text-xs">—</span>
    <?php elseif($today->gt($due)): ?>
        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">
            Overdue
        </span>
    <?php elseif($today->eq($due)): ?>
        <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-700">
            Due Today
        </span>
    <?php else: ?>
        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-700">
            Upcoming
        </span>
    <?php endif; ?>
</td>

<td class="border p-2"><?php echo e($rc->receivable_remarks ?? '—'); ?></td>

        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<p class="text-center text-gray-500 italic py-4">
    No receivable records available.
</p>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\consolidated\partials\receivable.blade.php ENDPATH**/ ?>