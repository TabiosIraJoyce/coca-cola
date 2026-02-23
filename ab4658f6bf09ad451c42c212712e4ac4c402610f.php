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
        <h2 class="text-2xl font-semibold text-facebookBlue">âž• Add Division</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow max-w-3xl mx-auto">
        <form action="<?php echo e(route('admin.divisions.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <!--Business Line Dropdown -->
            <div class="mb-4">
                <label for="business_line_id" class="block font-semibold">Line of Business</label>
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

            <!--Other Division Fields -->
            <?php $__currentLoopData = [
                'division_name' => 'Division Name',
                'supervisor_name' => 'Supervisor Name',
                'oic_name' => 'OIC Name',
                'division_address' => 'Address',
                'division_contact_number' => 'Contact Number',
                'division_telephone_number' => 'Telephone Number',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-4">
                    <label for="<?php echo e($field); ?>" class="block font-semibold"><?php echo e($label); ?></label>
                    <input
                        type="text"
                        name="<?php echo e($field); ?>"
                        id="<?php echo e($field); ?>"
                        value="<?php echo e(old($field)); ?>"
                        class="w-full border border-gray-300 p-2 rounded"
                        <?php echo e($field !== 'division_telephone_number' ? 'required' : ''); ?>

                    >
                    <?php $__errorArgs = [$field];
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="mt-6">
                <button type="submit" class="bg-facebookBlue text-white px-6 py-2 rounded hover:bg-blue-600">
                    ðŸ’¾ Save Division
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

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\divisions\create.blade.php ENDPATH**/ ?>