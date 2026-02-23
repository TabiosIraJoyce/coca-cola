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
        <h2 class="font-semibold text-xl text-gray-800">
            Create Period Target
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-[1400px] 2xl:max-w-[1600px] w-full mx-auto bg-white shadow rounded p-6 lg:p-8">

            
            <?php if(session('success')): ?>
                <div class="mb-4 text-green-600 font-semibold">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            
            <?php if($errors->any()): ?>
                <div class="mb-4 text-red-600">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.period-targets.store')); ?>">
                <?php echo csrf_field(); ?>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-semibold mb-1">Branch</label>
                        <select name="branch"
                            class="w-full border rounded px-3 py-2" required>
                            <option value="">Select branch</option>
                            <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($branch); ?>"
                                    <?php echo e(old('branch') == $branch ? 'selected' : ''); ?>>
                                    <?php echo e($branch); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Period No</label>
                        <select name="period_no"
                            class="w-full border rounded px-3 py-2" required>
                            <option value="">Select period</option>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>"
                                    <?php echo e(old('period_no') == $i ? 'selected' : ''); ?>>
                                    Period <?php echo e($i); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block font-semibold mb-1">Start Date</label>
                        <input type="date" name="start_date"
                            class="w-full border rounded px-3 py-2"
                            value="<?php echo e(old('start_date')); ?>" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">End Date</label>
                        <input type="date" name="end_date"
                            class="w-full border rounded px-3 py-2"
                            value="<?php echo e(old('end_date')); ?>" required>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

                    
                    <div>
                        <label class="block font-semibold mb-1">
                            Core Target Sales (Core Only)
                        </label>
                        <input type="number" step="0.01" min="0"
                            name="core_target_sales"
                            class="w-full border rounded px-3 py-2"
                            value="<?php echo e(old('core_target_sales')); ?>" required>
                    </div>

                    
                    <div>
                        <label class="block font-semibold mb-1">
                            PET CSD Target Sales
                        </label>
                        <input type="number" step="0.01" min="0"
                            name="petcsd_target_sales"
                            class="w-full border rounded px-3 py-2"
                            value="<?php echo e(old('petcsd_target_sales')); ?>" required>
                    </div>

                    
                    <div>
                        <label class="block font-semibold mb-1">
                            Stills Target Sales
                        </label>
                        <input type="number" step="0.01" min="0"
                            name="stills_target_sales"
                            class="w-full border rounded px-3 py-2"
                            value="<?php echo e(old('stills_target_sales')); ?>" required>
                    </div>

                </div>

                

                
                <div class="mb-6">
                    <label class="block font-semibold mb-1">
                        Overall Total Target Sales
                    </label>
                    <input type="text"
                        id="overall_target"
                        class="w-full border rounded px-3 py-2 bg-gray-100 font-semibold"
                        value="0.00"
                        readonly>
                </div>

                
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                        Save Target
                    </button>
                </div>

            </form>
        </div>
    </div>

    
    <script>
        function formatAmount(amount) {
            return amount.toLocaleString('en-PH', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function calculateOverallTarget() {
            let core   = parseFloat(document.querySelector('[name="core_target_sales"]')?.value) || 0;
            let stills = parseFloat(document.querySelector('[name="stills_target_sales"]')?.value) || 0;

            const overallEl = document.getElementById('overall_target');
            if (overallEl) {
                overallEl.value = formatAmount(core + stills);
            }
        }

        document.querySelectorAll(
            '[name="core_target_sales"], [name="petcsd_target_sales"], [name="stills_target_sales"]'
        ).forEach(el => el.addEventListener('input', calculateOverallTarget));

        function parseNum(val) {
            if (val === null || val === undefined || val === '') return 0;
            const n = parseFloat(String(val).replace(/[^0-9.\-]/g, ''));
            return Number.isFinite(n) ? n : 0;
        }

        function recalcSkuTargets() {
            const body = document.getElementById('perSkuBody');
            if (!body) {
                return;
            }

            body.querySelectorAll('tr').forEach(tr => {
                const pcs = parseNum(tr.querySelector('.sku-target-pcs')?.value);
                const ucs = parseNum(tr.querySelector('.sku-target-ucs')?.value);

                const actualPcs = parseNum(tr.querySelector('.sku-actual-pcs')?.value);
                const actualUcs = parseNum(tr.querySelector('.sku-actual-ucs')?.value);

                const variancePcs = pcs - actualPcs;
                const varianceUcs = ucs - actualUcs;

                const varPcsEl = tr.querySelector('.sku-variance-pcs');
                const varUcsEl = tr.querySelector('.sku-variance-ucs');

                if (varPcsEl) varPcsEl.value = variancePcs.toFixed(0);
                if (varUcsEl) varUcsEl.value = varianceUcs.toFixed(6);
            });
        }

        document.addEventListener('input', function (e) {
            if (!e.target) return;
            if (
                e.target.classList.contains('sku-target-pcs') ||
                e.target.classList.contains('sku-target-ucs')
            ) {
                recalcSkuTargets();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            recalcSkuTargets(); // handles old() values after validation errors
            calculateOverallTarget();
        });

        (function setupPerSkuToggle() {
            const btn = document.getElementById('togglePerSkuBtn');
            const section = document.getElementById('perSkuSection');
            if (!btn || !section) return;

            const updateLabel = () => {
                btn.textContent = section.classList.contains('hidden') ? 'Show Per SKU' : 'Hide Per SKU';
            };

            btn.addEventListener('click', () => {
                section.classList.toggle('hidden');
                updateLabel();
            });

            updateLabel();
        })();
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>


<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\period_targets\create.blade.php ENDPATH**/ ?>