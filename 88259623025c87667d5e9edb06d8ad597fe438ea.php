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
        <h2 class="text-2xl font-semibold text-facebookBlue">Division Management</h2>
     <?php $__env->endSlot(); ?>

    <div class="mb-4">
        <a href="<?php echo e(route('admin.divisions.create')); ?>" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-600">
            Add Division
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded shadow">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2">Division</th>
                    <th class="px-4 py-2">Line of Business</th>
                    <th class="px-4 py-2">Supervisor</th>
                    <th class="px-4 py-2">OIC</th>
                    <th class="px-4 py-2">Address</th>
                    <th class="px-4 py-2">Contact</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo e($division->division_name); ?></td>
                        <td class="px-4 py-2">
                            <?php echo e($division->businessLine?->name ?? 'N/A'); ?>

                        </td>
                        <td class="px-4 py-2"><?php echo e($division->supervisor_name); ?></td>
                        <td class="px-4 py-2"><?php echo e($division->oic_name); ?></td>
                        <td class="px-4 py-2"><?php echo e($division->division_address); ?></td>
                        <td class="px-4 py-2"><?php echo e($division->division_contact_number); ?></td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="<?php echo e(route('admin.divisions.edit', $division->id)); ?>" class="text-blue-600 hover:underline">Edit</a>
                            <form action="<?php echo e(route('admin.divisions.destroy', $division->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($divisions->isEmpty()): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No divisions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>


<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\divisions\index.blade.php ENDPATH**/ ?>