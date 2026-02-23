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
        <h2 class="text-xl font-bold text-blue-700">
            Edit Product
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
        <form method="POST" action="<?php echo e(route('admin.products.update', $product->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Category</label>

                <?php
                    $categories = $categories ?? [
                        'core'   => 'CORE',
                        'petcsd' => 'PET CSD',
                        'stills' => 'STILLS',
                    ];
                    $selectedCategory = strtolower(preg_replace('/\s+/', '', (string) ($product->category ?? '')));
                ?>

                <select name="category" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select category</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e($selectedCategory === $value ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Pack Size</label>
                <input type="text" id="pack_size" name="pack_size"
                       value="<?php echo e($product->pack_size); ?>"
                       class="w-full border rounded px-3 py-2">
            </div>

            
            <div class="mb-4 border border-gray-200 rounded p-3 bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-gray-800">UCS Computation</p>
                    <p class="text-[11px] text-gray-500">Formula: (ml × bottles) ÷ 5678</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-gray-300 bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1 text-left">Bottle Size (ml)</th>
                                <th class="border px-2 py-1 text-center w-10">×</th>
                                <th class="border px-2 py-1 text-left">No. of Bottles</th>
                                <th class="border px-2 py-1 text-center w-10">÷</th>
                                <th class="border px-2 py-1 text-center w-20">5678</th>
                                <th class="border px-2 py-1 text-center w-10">=</th>
                                <th class="border px-2 py-1 text-left">UCS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-2 py-1">
                                    <input
                                        id="unit_ml"
                                        name="unit_ml"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        value="<?php echo e(old('unit_ml', $product->unit_ml)); ?>"
                                        class="w-full border border-gray-300 p-2 rounded text-right"
                                        placeholder="e.g. 237"
                                    >
                                </td>
                                <td class="border px-2 py-1 text-center font-semibold">×</td>
                                <td class="border px-2 py-1">
                                    <input
                                        id="bottles_per_case"
                                        name="bottles_per_case"
                                        type="number"
                                        min="0"
                                        step="1"
                                        value="<?php echo e(old('bottles_per_case', $product->bottles_per_case)); ?>"
                                        class="w-full border border-gray-300 p-2 rounded text-right"
                                        placeholder="e.g. 24"
                                    >
                                </td>
                                <td class="border px-2 py-1 text-center font-semibold">÷</td>
                                <td class="border px-2 py-1 text-center font-semibold">5678</td>
                                <td class="border px-2 py-1 text-center font-semibold">=</td>
                                <td class="border px-2 py-1">
                                    <input
                                        id="computed_ucs"
                                        type="text"
                                        class="w-full bg-gray-100 border border-gray-300 p-2 rounded text-right font-semibold"
                                        readonly
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <?php $__errorArgs = ['unit_ml'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php $__errorArgs = ['bottles_per_case'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <p class="text-[11px] text-gray-500 mt-2">
                    The computed UCS will auto-fill in Coca-Cola Sales Reporting.
                </p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Product Name</label>
                <input type="text" name="product_name"
                       value="<?php echo e($product->product_name); ?>"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">SRP</label>
                <input type="number" step="0.01" name="srp"
                       value="<?php echo e($product->srp); ?>"
                       class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold">Status</label>

                <?php
                    $statuses = $statuses ?? ['active' => 'Active', 'inactive' => 'Inactive'];
                ?>

                <select name="status" class="w-full border rounded px-3 py-2">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(($product->status ?? 'active') === $value ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="<?php echo e(route('admin.products.index')); ?>"
                   class="px-4 py-2 bg-gray-300 rounded">
                    Cancel
                </a>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Save
                </button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const BASE = 5678;
            const packEl = document.getElementById('pack_size');
            const mlEl = document.getElementById('unit_ml');
            const bottlesEl = document.getElementById('bottles_per_case');
            const outEl = document.getElementById('computed_ucs');
            let lastAutoMl = null;

            function num(v) {
                const n = parseFloat(String(v ?? '').replace(/[^0-9.\-]/g, ''));
                return Number.isFinite(n) ? n : 0;
            }

            function parseBottleMl(packSize) {
                const s = String(packSize ?? '').trim().toLowerCase().replace(/,/g, '');
                if (!s) return null;

                // Prefer explicit "ml"
                let m = s.match(/(\d+(?:\.\d+)?)\s*ml\b/);
                if (m) {
                    const v = parseFloat(m[1]);
                    return Number.isFinite(v) && v > 0 ? v : null;
                }

                // Liters: "1 LITER", "1.5 LTR", "2L", "1.75L"
                m = s.match(/(\d+(?:\.\d+)?)\s*(?:liters?|litres?|ltr|l)\b/);
                if (m) {
                    const v = parseFloat(m[1]);
                    const ml = Number.isFinite(v) && v > 0 ? (v * 1000) : null;
                    return ml;
                }

                return null;
            }

            function recalc() {
                const ml = num(mlEl?.value);
                const bottles = num(bottlesEl?.value);

                if (!outEl) return;
                if (ml > 0 && bottles > 0) {
                    outEl.value = ((ml * bottles) / BASE).toFixed(6);
                } else {
                    outEl.value = '';
                }
            }

            function maybeAutofillMlFromPack() {
                if (!packEl || !mlEl) return;

                const parsed = parseBottleMl(packEl.value);
                if (!parsed) return;

                const current = num(mlEl.value);
                const shouldOverwrite =
                    current <= 0 ||
                    (lastAutoMl !== null && Math.abs(current - lastAutoMl) < 0.000001);

                if (shouldOverwrite) {
                    mlEl.value = Number.isInteger(parsed) ? String(parsed) : String(parsed.toFixed(2));
                    lastAutoMl = parsed;
                    recalc();
                }
            }

            mlEl?.addEventListener('input', recalc);
            bottlesEl?.addEventListener('input', recalc);
            packEl?.addEventListener('input', maybeAutofillMlFromPack);
            document.addEventListener('DOMContentLoaded', () => {
                maybeAutofillMlFromPack();
                recalc();
            });
        })();
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\products\edit.blade.php ENDPATH**/ ?>