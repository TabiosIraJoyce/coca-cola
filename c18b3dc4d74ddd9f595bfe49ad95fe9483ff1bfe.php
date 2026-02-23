<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-700">Bank Management</h2>
                <p class="text-sm text-gray-500">Search, update, and maintain all bank accounts.</p>
            </div>
            <a href="<?php echo e(route('admin.banks.create')); ?>"
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">
                + Add Bank
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-5">

        <?php if(session('success')): ?>
            <div class="px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 font-medium">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 font-medium">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <form method="GET" action="<?php echo e(route('admin.banks.index')); ?>"
                  class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-semibold text-gray-700 mb-1">Search</label>
                    <input id="q"
                           name="q"
                           type="text"
                           value="<?php echo e(request('q', $q ?? '')); ?>"
                           placeholder="Bank, branch, holder, account, or routing"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select id="status"
                            name="status"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="active" <?php echo e(request('status', $status ?? '') === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e(request('status', $status ?? '') === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="w-full md:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Search
                    </button>
                    <a href="<?php echo e(route('admin.banks.index')); ?>"
                       class="w-full md:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-gray-200 text-sm text-gray-600">
                Showing <?php echo e($banks->firstItem() ?? 0); ?>-<?php echo e($banks->lastItem() ?? 0); ?> of <?php echo e($banks->total()); ?> banks
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#eef4ff] text-[#1e3a8a]">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Bank</th>
                            <th class="px-4 py-3 text-left font-semibold">Branch</th>
                            <th class="px-4 py-3 text-left font-semibold">Account Holder</th>
                            <th class="px-4 py-3 text-left font-semibold">Account #</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/40">
                                <td class="px-4 py-3 font-medium text-gray-900"><?php echo e($bank->bank_name); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($bank->branch_name); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($bank->account_holder_name); ?></td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($bank->account_number); ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold <?php echo e($bank->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700'); ?>">
                                        <?php echo e(ucfirst($bank->status)); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?php echo e(route('admin.banks.edit', $bank)); ?>"
                                           class="px-3 py-1.5 rounded-md bg-amber-100 text-amber-800 hover:bg-amber-200 font-medium">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="<?php echo e(route('admin.banks.destroy', $bank)); ?>"
                                              onsubmit="return confirm('Delete this bank record?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-md bg-red-100 text-red-700 hover:bg-red-200 font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    No banks found for your filters.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200 bg-white">
                <?php echo e($banks->links()); ?>

            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\bank-management\index.blade.php ENDPATH**/ ?>