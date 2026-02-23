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
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i data-lucide="map-pin" class="w-5 h-5 text-blue-600"></i>
            Select Division
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-10 max-w-xl mx-auto px-4">

        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">

            <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="building-2" class="w-5 h-5 text-gray-700"></i>
                Choose a Division
            </h3>

            <form action="<?php echo e(route('admin.reports.choose-report-type')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <select name="division_id"
                        required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 mb-4">
                    <option value="">-- Select Division --</option>
                    <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(in_array($division->division_name, [
                            'Gledco Enterprise - Laoag',
                            'Gledco Enterprise - Batac',
                            'Gledco Enterprise - Solsona'
                        ])): ?>
                            <option value="<?php echo e($division->id); ?>">
                                <?php echo e($division->division_name); ?>

                            </option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Continue →
                </button>
            </form>

        </div>
    </div>

    <script>document.addEventListener("DOMContentLoaded",()=>{ lucide.replace(); });</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\add\select-division.blade.php ENDPATH**/ ?>