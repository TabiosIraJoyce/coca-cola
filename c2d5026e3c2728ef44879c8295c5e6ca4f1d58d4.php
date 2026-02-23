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
        <h2 class="text-xl font-semibold text-gray-800">
            <span class="inline-flex items-center gap-2">
                <i data-lucide="files" class="w-5 h-5 text-blue-600"></i>
                Select Report Type
            </span>
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-10 max-w-3xl mx-auto px-4">

        <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-200">

            <h3 class="text-lg font-semibold mb-2 flex items-center gap-2">
                <i data-lucide="building" class="w-5 h-5 text-gray-700"></i>
                Division Selected:
                <span class="text-blue-700"><?php echo e($division->division_name); ?></span>
            </h3>

            
            <form action="<?php echo e(route('admin.reports.add.report-type')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                
                <input type="hidden" name="division_id" value="<?php echo e($division_id); ?>">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Report Type
                </label>

                <select name="report_type"
                        class="block w-full rounded-md border-gray-300 shadow-sm 
                               focus:ring-blue-500 focus:border-blue-500 mb-4"
                        required>
                    <option value="">-- Select Report Type --</option>
                    <option value="receipts">📄 Receipts Breakdown</option>
                    <option value="remittance">💵 Remittance Details</option>
                    <option value="receivables">📊 Receivables Monitoring</option>
                    <option value="borrowers">📘 Borrower Agreement</option>
                </select>

                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Continue →
                </button>

            </form>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.replace();
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\add\choose-report-type.blade.php ENDPATH**/ ?>