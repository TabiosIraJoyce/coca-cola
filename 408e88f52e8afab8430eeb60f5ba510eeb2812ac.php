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
        <h2 class="text-2xl font-semibold text-facebookBlue">Add Sales Template Field</h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-[1200px] 2xl:max-w-[1400px] w-full mx-auto p-6">
        <div class="bg-white p-6 rounded shadow">
        <form action="<?php echo e(route('admin.sales-templates.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="business_line_id" class="block font-semibold">Business Line</label>
                <select name="business_line_id" id="business_line_id" required class="w-full border border-gray-300 p-2 rounded">
                    <option value="">-- Select Business Line --</option>
                    <?php $__currentLoopData = $businessLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($line->id); ?>" <?php echo e(old('business_line_id') == $line->id ? 'selected' : ''); ?>>
                            <?php echo e($line->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['business_line_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label for="field_label" class="block font-semibold">Field Label</label>
                <input type="text" name="field_label" id="field_label" value="<?php echo e(old('field_label')); ?>"
                       class="w-full border border-gray-300 p-2 rounded" required>
                <?php $__errorArgs = ['field_label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4">
                <label for="field_type" class="block font-semibold">Field Type</label>
                <select name="field_type" id="field_type" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="number" <?php echo e(old('field_type') == 'number' ? 'selected' : ''); ?>>Number</option>
                    <option value="text" <?php echo e(old('field_type') == 'text' ? 'selected' : ''); ?>>Text</option>
                    <option value="date" <?php echo e(old('field_type') == 'date' ? 'selected' : ''); ?>>Date</option>
                </select>
                <?php $__errorArgs = ['field_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_required" id="is_required" value="1"
                       <?php echo e(old('is_required') ? 'checked' : ''); ?>

                       class="mr-2">
                <label for="is_required" class="font-medium">Required Field</label>
            </div>

            <div class="mb-6">
                <label for="field_order" class="block font-semibold">Display Order</label>
                <input type="number" name="field_order" id="field_order" value="<?php echo e(old('field_order', 0)); ?>"
                       class="w-full border border-gray-300 p-2 rounded">
                <?php $__errorArgs = ['field_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-facebookBlue text-white px-6 py-2 rounded hover:bg-blue-600">
                    Save Field
                </button>
            </div>
        </form>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\sales-templates\create.blade.php ENDPATH**/ ?>