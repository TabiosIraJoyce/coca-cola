<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Business Lines</h2>
            <a href="<?php echo e(route('admin.business-lines.create')); ?>" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-700">
                Add New
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <table class="min-w-full bg-white border border-gray-300 rounded shadow">
            <thead>
                <tr class="bg-gray-100 text-left text-sm uppercase">
                    <th class="p-3 border-b">#</th>
                    <th class="p-3 border-b">Name</th>
                    <th class="p-3 border-b">Description</th>
                    <th class="p-3 border-b text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $businessLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="p-3 border-b"><?php echo e($loop->iteration); ?></td>
                        <td class="p-3 border-b"><?php echo e($line->name); ?></td>
                        <td class="p-3 border-b"><?php echo e($line->description); ?></td>
                        <td class="p-3 border-b text-center">
                            <a href="<?php echo e(route('admin.business-lines.edit', $line)); ?>" class="text-blue-600 hover:underline mr-2">Edit</a>
                            <form action="<?php echo e(route('admin.business-lines.destroy', $line)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this business line?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="p-3 text-center text-gray-500">No business lines found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4">
            <?php echo e($businessLines->links()); ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\business-lines\index.blade.php ENDPATH**/ ?>