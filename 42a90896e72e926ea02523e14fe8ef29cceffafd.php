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
        <h2 class="text-2xl font-semibold text-facebookBlue">✏️ Edit Sales Input</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow max-w-4xl mx-auto">
        <form method="POST" action="<?php echo e(route('admin.sales-inputs.update', $salesInput->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Division Dropdown -->
            <div class="mb-4">
                <label for="division_id" class="block font-semibold mb-1">Division</label>
                <select name="division_id" id="division_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Select Division --</option>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division->id); ?>"
                            <?php echo e($division->id == $salesInput->division_id ? 'selected' : ''); ?>>
                            <?php echo e($division->division_name); ?> (<?php echo e($division->businessLine->name ?? 'N/A'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label for="date" class="block font-semibold mb-1">Date</label>
                <input type="date" name="date" id="date" value="<?php echo e($salesInput->date); ?>" class="w-full border p-2 rounded" required>
            </div>

            <!-- Dynamic Fields -->
            <div class="mb-4">
                <h3 class="font-semibold mb-2">Edit Values</h3>
                <?php $__currentLoopData = $salesInput->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-2">
                        <label class="block font-medium"><?php echo e($item->field_label); ?></label>
                        <input type="text" name="data[<?php echo e($item->field_label); ?>]" value="<?php echo e($item->value); ?>" class="w-full border p-2 rounded">
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <a href="<?php echo e(route('admin.sales-inputs.index')); ?>" class="text-gray-600 hover:underline">← Back</a>
                <button type="submit" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-700">
                    💾 Update Input
                </button>
            </div>
        </form>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\sales-inputs\edit.blade.php ENDPATH**/ ?>