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
        <h2 class="text-2xl font-semibold text-facebookBlue">âž• Create New User</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white max-w-xl mx-auto p-6 rounded shadow">
        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Role</label>
                <select name="role" class="w-full border p-2 rounded">
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role); ?>" <?php echo e(old('role') === $role ? 'selected' : ''); ?>>
                            <?php echo e(ucfirst($role)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Division</label>
                <select name="division_id" class="w-full border p-2 rounded">
                    <option value="">â€” None â€”</option>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($division->id); ?>" <?php echo e(old('division_id') == $division->id ? 'selected' : ''); ?>>
                            <?php echo e($division->division_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex justify-between">
                <a href="<?php echo e(route('admin.users.index')); ?>" class="text-gray-600 hover:underline">Cancel</a>
                <button type="submit" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-600">
                    Save User
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


<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\users\create.blade.php ENDPATH**/ ?>