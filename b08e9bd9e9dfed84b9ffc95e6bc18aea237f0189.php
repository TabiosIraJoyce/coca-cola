<h3 class="text-lg font-semibold mb-3">💵 Remittance Details</h3>

<?php if(isset($remittances) && $remittances->count()): ?>
<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Bank</th>
            <th class="border p-2">Account #</th>
            <th class="border p-2">Account Name</th>
            <th class="border p-2">Check Date</th>
            <th class="border p-2">Remarks</th>
            <th class="border p-2">Amount</th>
            <th class="border p-2">Validated Total</th>
            <th class="border p-2">Shortage</th>
            <th class="border p-2">Overage</th>
        </tr>
    </thead>

    <tbody>
        <?php $__currentLoopData = $remittances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="border p-2"><?php echo e($rm->bank_name ?? '—'); ?></td>
            <td class="border p-2"><?php echo e($rm->account_number ?? '—'); ?></td>
            <td class="border p-2"><?php echo e($rm->account_name ?? '—'); ?></td>
            <td class="border p-2"><?php echo e($rm->check_date ?? '—'); ?></td>
            <td class="border p-2"><?php echo e($rm->remarks ?? '—'); ?></td>

            <td class="border p-2 text-right">
                ₱ <?php echo e(number_format($rm->check_amount ?? 0, 2)); ?>

            </td>

            <td class="border p-2 text-right">
                ₱ <?php echo e(number_format($rm->validated_total ?? 0, 2)); ?>

            </td>

            <td class="border p-2">
                <?php echo e($rm->validated_shortage ?? 0); ?>

            </td>

            <td class="border p-2">
                <?php echo e($rm->validated_overage ?? 0); ?>

            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<p class="text-center text-gray-500 italic py-4">
    No remittance records available.
</p>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\consolidated\partials\remittance.blade.php ENDPATH**/ ?>