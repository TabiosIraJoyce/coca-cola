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
        <h1 class="text-2xl font-bold text-blue-700">
            Products & Pricing
        </h1>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow">

        <!-- ➕ ADD PRODUCT BUTTON -->
        <div class="flex justify-between mb-4">
            <button
                type="button"
                onclick="deleteSelected()"
                class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                Delete Selected
            </button>

            <a href="<?php echo e(route('admin.products.create')); ?>"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded
                      hover:bg-blue-700 transition">
                <span class="mr-2">➕</span> Add Product
            </a>
        </div>

        <!-- 📋 PRODUCTS TABLE -->
        <table class="min-w-full border border-gray-300 text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-2 py-2 border border-gray-300 text-center">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Category</th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Pack Size</th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Product</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">Bottles</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">UCS</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">SRP</th>
                    <th class="px-2 py-2 border border-gray-300 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    
                    <?php
                        $normalizedCategory = strtolower(str_replace(' ', '', $p->category));

                        $rowClass = '';
                        $productTextClass = '';

                        if ($normalizedCategory === 'core') {
                            $rowClass = 'bg-red-50';
                            $productTextClass = 'text-red-700 font-semibold';
                        } elseif ($normalizedCategory === 'stills') {
                            $rowClass = 'bg-blue-50';
                            $productTextClass = 'text-blue-700 font-semibold';
                        } elseif ($normalizedCategory === 'petcsd') {
                            $rowClass = 'bg-yellow-100';
                            $productTextClass = 'text-yellow-700 font-semibold';
                        }
                    ?>

                    <tr class="hover:bg-gray-50 <?php echo e($rowClass); ?>">
                        <td class="px-2 py-2 border border-gray-300 text-center">
                            <input type="checkbox"
                                   class="row-checkbox"
                                   value="<?php echo e($p->id); ?>">
                        </td>

                        <td class="px-3 py-2 border border-gray-300 font-semibold">
                            <?php echo e(strtoupper($p->category)); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300">
                            <?php echo e($p->pack_size); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300 <?php echo e($productTextClass); ?>">
                            <?php echo e($p->product_name); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right">
                            <?php echo e($p->bottles_per_case !== null ? $p->bottles_per_case : '—'); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right font-semibold">
                            <?php echo e($p->ucs !== null ? number_format($p->ucs, 6) : '—'); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right">
                            ₱ <?php echo e(number_format($p->srp ?? 0, 2)); ?>

                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-center">
                            <a href="<?php echo e(route('admin.products.edit', $p->id)); ?>"
                               class="text-blue-600 hover:underline mr-2">
                                Edit
                            </a>

                            <form action="<?php echo e(route('admin.products.destroy', $p->id)); ?>"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Delete this product?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- IWS TABLE -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-blue-700 mb-3">IWS UCS Table</h2>
            <table class="min-w-full border border-gray-300 text-sm border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 border border-gray-300 text-left">Category</th>
                        <th class="px-2 py-2 border border-gray-300 text-left">Pack Size</th>
                        <th class="px-2 py-2 border border-gray-300 text-left">Product</th>
                        <th class="px-2 py-2 border border-gray-300 text-right">Bottles</th>
                        <th class="px-2 py-2 border border-gray-300 text-right">IWS UCS</th>
                        <th class="px-2 py-2 border border-gray-300 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $normalizedCategory = strtolower(str_replace(' ', '', $p->category));

                            $rowClass = '';
                            $productTextClass = '';

                            if ($normalizedCategory === 'core') {
                                $rowClass = 'bg-red-50';
                                $productTextClass = 'text-red-700 font-semibold';
                            } elseif ($normalizedCategory === 'stills') {
                                $rowClass = 'bg-blue-50';
                                $productTextClass = 'text-blue-700 font-semibold';
                            } elseif ($normalizedCategory === 'petcsd') {
                                $rowClass = 'bg-yellow-100';
                                $productTextClass = 'text-yellow-700 font-semibold';
                            }
                        ?>

                        <tr class="hover:bg-gray-50 <?php echo e($rowClass); ?>">
                            <td class="px-3 py-2 border border-gray-300 font-semibold">
                                <?php echo e(strtoupper($p->category)); ?>

                            </td>
                            <td class="px-3 py-2 border border-gray-300">
                                <?php echo e($p->pack_size); ?>

                            </td>
                            <td class="px-3 py-2 border border-gray-300 <?php echo e($productTextClass); ?>">
                                <?php echo e($p->product_name); ?>

                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-right">
                                <?php echo e($p->bottles_per_case !== null ? $p->bottles_per_case : '-'); ?>

                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-right font-semibold">
                                <?php echo e('-'); ?>

                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-center">
                                <a href="<?php echo e(route('admin.products.edit', $p->id)); ?>"
                                   class="text-blue-600 hover:underline mr-2">
                                    Edit
                                </a>

                                <form action="<?php echo e(route('admin.products.destroy', $p->id)); ?>"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this product?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- 🔥 HIDDEN DELETE FORMS -->
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <form id="delete-form-<?php echo e($p->id); ?>"
                  action="<?php echo e(route('admin.products.destroy', $p->id)); ?>"
                  method="POST"
                  class="hidden">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            </form>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <!-- ✅ SCRIPT -->
    <script>
    document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');

        if (selected.length === 0) {
            alert('Please select at least one product.');
            return;
        }

        if (!confirm('Delete selected product(s)?')) return;

        // ✅ delete ONLY the first selected item
        const firstId = selected[0].value;
        document.getElementById('delete-form-' + firstId).submit();
    }
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\products\index.blade.php ENDPATH**/ ?>