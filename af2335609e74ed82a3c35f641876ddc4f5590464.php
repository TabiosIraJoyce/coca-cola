<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="min-h-screen bg-gray-100 py-10">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">📋 Sales Targets</h2>

                <a href="<?php echo e(route('admin.period-targets.create')); ?>"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ➕ New Target
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-4 rounded border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($targets->isEmpty()): ?>
                <div class="text-center text-gray-500 py-10">
                    No targets found.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2 text-left">Branch</th>
                                <th class="border px-3 py-2 text-left">Period</th>

                                <th class="border px-3 py-2 text-right">
                                    Core Target Sales
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Stills Target Sales
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Target Sales
                                </th>

                                <th class="border px-3 py-2 text-left">Effective From</th>
                                <th class="border px-3 py-2 text-left">Effective To</th>
                                <th class="border px-3 py-2 text-center">Status</th>
                                <th class="border px-3 py-2 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">

                                    
                                    <td class="border px-3 py-2">
                                        <?php echo e($target->branch); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2">
                                        Period <?php echo e($target->period_no); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2 text-right">
                                        <?php echo e(number_format($target->core_target_sales ?? 0, 2)); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2 text-right">
                                        <?php echo e(number_format($target->stills_target_sales ?? 0, 2)); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2 text-right font-semibold">
                                        <?php echo e(number_format(
                                            ($target->core_target_sales ?? 0) +
                                            ($target->stills_target_sales ?? 0),
                                            2
                                        )); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2">
                                        <?php echo e($target->start_date?->format('M d, Y')); ?>

                                    </td>

                                    <td class="border px-3 py-2">
                                        <?php echo e($target->end_date?->format('M d, Y')); ?>

                                    </td>

                                    
                                    <td class="border px-3 py-2 text-center">
                                        <?php if($target->is_locked): ?>
                                            <span class="text-green-600 font-semibold">
                                                Locked
                                            </span>
                                        <?php else: ?>
                                            <span class="text-yellow-600 font-semibold">
                                                Open
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="border px-3 py-2 text-center">
                                        <form method="POST"
                                              action="<?php echo e(route('admin.period-targets.destroy', $target)); ?>"
                                              onsubmit="return confirm('Delete this period target?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                    class="inline-flex items-center rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\period_targets\index.blade.php ENDPATH**/ ?>