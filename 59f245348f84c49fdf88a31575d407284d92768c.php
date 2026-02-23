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
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="printer" class="w-6 h-6 text-gray-900"></i>
            Period Reports - Print Preview
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="p-6 space-y-4">

        <div class="bg-white shadow rounded-lg p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Branch</label>
                    <select name="branch" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($b); ?>" <?php echo e(request('branch') === $b ? 'selected' : ''); ?>>
                                <?php echo e($b); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Period From</label>
                    <select name="period_from" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e((string) request('period_from') === (string) $i ? 'selected' : ''); ?>>
                                Period <?php echo e($i); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Period To</label>
                    <select name="period_to" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e((string) request('period_to') === (string) $i ? 'selected' : ''); ?>>
                                Period <?php echo e($i); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Date From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Date To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="w-full border rounded p-2">
                </div>

                <div class="md:col-span-6 flex gap-2 justify-end">
                    <a href="<?php echo e(route('admin.reports.periods.index')); ?>"
                       class="px-4 py-2 rounded border hover:bg-gray-50">
                        Back
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded bg-gray-900 text-white hover:bg-black font-semibold">
                        Generate Preview
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-3 border-b flex items-center justify-between gap-2">
                <div class="text-sm font-semibold text-gray-700">
                    Preview
                </div>

                <button type="button"
                        id="printBtn"
                        class="px-4 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        <?php echo e(empty($previewUrl) ? 'disabled' : ''); ?>>
                    Print
                </button>
            </div>

            <?php if(empty($previewUrl)): ?>
                <div class="p-6 text-sm text-gray-600">
                    Select Branch/Period/Date, then click <b>Generate Preview</b>.
                </div>
            <?php else: ?>
                <iframe id="previewFrame"
                        src="<?php echo e($previewUrl); ?>"
                        class="w-full"
                        style="height: 78vh; border: 0;"></iframe>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            const btn = document.getElementById('printBtn');
            const frame = document.getElementById('previewFrame');
            if (!btn || !frame) return;

            btn.addEventListener('click', () => {
                try {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                } catch (e) {
                    // fallback
                    window.open(frame.src, '_blank');
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views/admin/reports/periods/print-preview.blade.php ENDPATH**/ ?>