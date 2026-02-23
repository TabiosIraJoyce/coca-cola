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
        ➖ Deduction Management
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 shadow rounded">
        <a href="<?php echo e(route('deductions.create')); ?>" class="mb-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ➕ Add Deduction
        </a>

        <?php if(session('success')): ?>
            <div class="mb-4 text-green-600 font-semibold">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <table class="w-full table-auto text-sm text-left border">
            <thead class="border-b font-semibold bg-gray-100">
                <tr>
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Frequency</th>
                    <th class="py-2 px-4">Active</th>
                    <th class="py-2 px-4">Remarks</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="py-2 px-4"><?php echo e($d->deduction_name); ?></td>
                    <td class="py-2 px-4">₱<?php echo e(number_format($d->amount, 2)); ?></td>
                    <td class="py-2 px-4 capitalize"><?php echo e($d->frequency); ?></td>
                    <td class="py-2 px-4">
                        <span class="<?php echo e($d->active ? 'text-green-600' : 'text-gray-400'); ?>">
                            <?php echo e($d->active ? '✔' : '✘'); ?>

                        </span>
                    </td>
                    <td class="py-2 px-4"><?php echo e($d->remarks ?? '—'); ?></td>
                    <td class="py-2 px-4">
                        <a href="<?php echo e(route('deductions.edit', $d)); ?>" class="text-blue-600 hover:underline">Edit</a>
                        <form action="<?php echo e(route('deductions.destroy', $d)); ?>" method="POST" class="inline-block ml-2" onsubmit="return confirm('Delete this deduction?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\deductions\index.blade.php ENDPATH**/ ?>