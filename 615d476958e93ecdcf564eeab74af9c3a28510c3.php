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
        <h2 class="text-2xl font-semibold text-facebookBlue">Sales Template Fields</h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-[1400px] 2xl:max-w-[1600px] w-full mx-auto p-6 space-y-4">
        <div class="flex items-center justify-between">
            <a href="<?php echo e(route('admin.sales-templates.create')); ?>" class="bg-facebookBlue text-white px-5 py-2.5 rounded hover:bg-blue-600 font-semibold">
                Add Field
            </a>
        </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded shadow">

        <div class="mb-4">
                <form method="GET" action="<?php echo e(route('admin.sales-templates.index')); ?>" class="flex items-center gap-2">
                    <label for="business_line_id" class="text-sm font-medium">Filter by Business Line:</label>
                    <select name="business_line_id" id="business_line_id" class="border rounded p-2" onchange="this.form.submit()">
                        <option value="">All Business Lines</option>
                        <?php $__currentLoopData = $businessLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($line->id); ?>" <?php echo e(request('business_line_id') == $line->id ? 'selected' : ''); ?>>
                                <?php echo e($line->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <a href="<?php echo e(route('admin.sales-templates.index')); ?>" class="text-sm text-gray-600 hover:underline ml-2">Clear</a>
                </form>
            </div>

        <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full table-auto border-collapse text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3">Business Line</th>
                    <th class="px-4 py-3">Field Label</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Required</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="border-t">
                        <td class="px-4 py-3"><?php echo e($template->businessLine->name); ?></td>
                        <td class="px-4 py-3"><?php echo e($template->field_label); ?></td>
                        <td class="px-4 py-3"><?php echo e($template->field_type); ?></td>
                        <td class="px-4 py-3"><?php echo e($template->is_required ? 'Yes' : 'No'); ?></td>
                        <td class="px-4 py-3"><?php echo e($template->field_order); ?></td>
                        <td class="px-4 py-3 space-x-3">
                            <a href="<?php echo e(route('admin.sales-templates.edit', $template->id)); ?>" class="text-blue-600 hover:underline">Edit</a>
                            <form action="<?php echo e(route('admin.sales-templates.destroy', $template->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No fields defined.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\sales-templates\index.blade.php ENDPATH**/ ?>