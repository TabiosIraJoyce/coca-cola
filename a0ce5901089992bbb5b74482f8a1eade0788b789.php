<?php $bank = $bank ?? null; ?>

<div>
    <label class="block text-sm font-semibold">Bank Name</label>
    <input name="bank_name"
           value="<?php echo e(old('bank_name', $bank->bank_name ?? '')); ?>"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Branch Name</label>
    <input name="branch_name"
           value="<?php echo e(old('branch_name', $bank->branch_name ?? '')); ?>"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Account Holder Name</label>
    <input name="account_holder_name"
           value="<?php echo e(old('account_holder_name', $bank->account_holder_name ?? '')); ?>"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Account Number</label>
    <input name="account_number"
           value="<?php echo e(old('account_number', $bank->account_number ?? '')); ?>"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Routing Number</label>
    <input name="routing_number"
           value="<?php echo e(old('routing_number', $bank->routing_number ?? '')); ?>"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<?php if($bank): ?>
<div>
    <label class="block text-sm font-semibold">Status</label>
    <select name="status" class="w-full border rounded px-3 py-2">
        <option value="active" <?php echo e($bank->status === 'active' ? 'selected' : ''); ?>>Active</option>
        <option value="inactive" <?php echo e($bank->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
    </select>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\bank-management\partials\form.blade.php ENDPATH**/ ?>