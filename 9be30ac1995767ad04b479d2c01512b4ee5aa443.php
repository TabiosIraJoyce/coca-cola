<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="p-6 bg-gray-100">

        <h1 class="text-xl font-bold mb-6">Edit Customer</h1>

        <div class="bg-white rounded shadow p-6 max-w-xl">

            <form method="POST" action="<?php echo e(route('admin.customers.update', $customer)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Delivery Route</label>
                    <input type="text"
                           name="delivery_route"
                           value="<?php echo e(old('delivery_route', $customer->delivery_route)); ?>"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Sub Route</label>
                    <input type="text"
                           name="sub_route"
                           value="<?php echo e(old('sub_route', $customer->sub_route)); ?>"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Owner Name</label>
                    <input type="text"
                           name="customer"
                           value="<?php echo e(old('customer', $customer->customer)); ?>"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Store Name</label>
                    <input type="text"
                           name="store_name"
                           value="<?php echo e(old('store_name', $customer->store_name)); ?>"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <textarea name="address"
                              class="w-full border p-2 rounded"
                              rows="3"><?php echo e(old('address', $customer->address)); ?></textarea>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Contact Number</label>
                    <input type="text"
                           name="contact_number"
                           value="<?php echo e(old('contact_number', $customer->contact_number)); ?>"
                           class="w-full border p-2 rounded">
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Credit Limit</label>
                    <input type="number"
                           step="0.01"
                           name="credit_limit"
                           value="<?php echo e(old('credit_limit', $customer->credit_limit)); ?>"
                           class="w-full border p-2 rounded">
                </div>

                
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Remarks</label>
                    <select name="remarks" class="w-full border p-2 rounded">
                        <option value="ACTIVE" <?php echo e($customer->remarks === 'ACTIVE' ? 'selected' : ''); ?>>
                            ACTIVE
                        </option>
                        <option value="CLOSED" <?php echo e($customer->remarks === 'CLOSED' ? 'selected' : ''); ?>>
                            CLOSED
                        </option>
                    </select>
                </div>

                
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:opacity-90">
                        💾 Update
                    </button>

                    <a href="<?php echo e(route('admin.customers.index')); ?>"
                       class="px-4 py-2 border rounded hover:bg-gray-100">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\customers\edit.blade.php ENDPATH**/ ?>