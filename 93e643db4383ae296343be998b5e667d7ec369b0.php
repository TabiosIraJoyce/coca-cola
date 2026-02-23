<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="flex min-h-screen">
        <main class="flex-1 p-6 bg-gray-100">
            <div class="max-w-3xl">
                <h1 class="text-xl font-bold mb-2">Upload Customers (Excel/CSV)</h1>
                <p class="text-sm text-gray-600 mb-6">
                    Upload an <b>.xlsx</b> or <b>.csv</b> file to add/update customers in bulk.
                </p>

                <?php if(session('success')): ?>
                    <div class="mb-4 p-3 rounded border border-green-200 bg-green-50 text-green-800 text-sm">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-4 p-3 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                        <div class="font-semibold mb-1">Please fix the following:</div>
                        <ul class="list-disc pl-5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($err); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                        <div class="text-sm font-semibold text-gray-700">
                            Template Columns (Row 1 headers)
                        </div>
                        <a href="<?php echo e(route('admin.customers.import.template')); ?>"
                           class="text-sm text-blue-700 hover:underline">
                            Download CSV Template
                        </a>
                    </div>

                    <div class="text-xs text-gray-600 mb-5">
                        Required: <b>delivery_route</b>, <b>sub_route</b>, <b>customer</b>, <b>store_name</b>, <b>remarks</b>.
                        Remarks must be <b>ACTIVE</b> or <b>CLOSED</b>.
                    </div>

                    <form method="POST"
                          action="<?php echo e(route('admin.customers.import.store')); ?>"
                          enctype="multipart/form-data"
                          class="space-y-4">
                        <?php echo csrf_field(); ?>

                        <div>
                            <label class="block text-sm font-medium mb-1">Excel/CSV File</label>
                            <input type="file"
                                   name="file"
                                   accept=".xlsx,.xls,.csv"
                                   required
                                   class="block w-full border border-gray-300 rounded p-2 bg-white">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="bg-facebookBlue text-white px-4 py-2 rounded hover:opacity-90">
                                Upload & Import
                            </button>
                            <a href="<?php echo e(route('admin.customers.index')); ?>"
                               class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                                Back to Customer List
                            </a>
                        </div>
                    </form>
                </div>

                <?php if(session('import_preview') && is_array(session('import_preview')) && count(session('import_preview'))): ?>
                    <?php
                        $preview = session('import_preview');
                        $limit = (int) (session('import_preview_limit') ?? 0);
                    ?>

                    <div class="mt-6 bg-white rounded-xl shadow p-6 border border-gray-200">
                        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                            <h2 class="text-sm font-bold tracking-wide text-gray-800">
                                Uploaded Rows Preview
                            </h2>
                            <?php if($limit > 0 && count($preview) >= $limit): ?>
                                <div class="text-xs text-gray-500">
                                    Showing first <?php echo e($limit); ?> rows
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="overflow-auto">
                            <table class="min-w-full border text-xs">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2">Action</th>
                                        <th class="border p-2">Delivery Route</th>
                                        <th class="border p-2">Sub Route</th>
                                        <th class="border p-2">Owner/Customer</th>
                                        <th class="border p-2">Store Name</th>
                                        <th class="border p-2">Address</th>
                                        <th class="border p-2">Contact</th>
                                        <th class="border p-2">Credit Limit</th>
                                        <th class="border p-2">Remarks</th>
                                        <th class="border p-2">Sheet</th>
                                        <th class="border p-2">Row</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $preview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="border p-2 font-bold <?php echo e(($r['_action'] ?? '') === 'ADDED' ? 'text-green-700' : 'text-blue-700'); ?>">
                                                <?php echo e($r['_action'] ?? ''); ?>

                                            </td>
                                            <td class="border p-2"><?php echo e($r['delivery_route'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['sub_route'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['customer'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['store_name'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['address'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['contact_number'] ?? ''); ?></td>
                                            <td class="border p-2 text-right"><?php echo e(number_format((float) ($r['credit_limit'] ?? 0), 2)); ?></td>
                                            <td class="border p-2"><?php echo e($r['remarks'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($r['_sheet'] ?? '—'); ?></td>
                                            <td class="border p-2 text-right"><?php echo e($r['_row'] ?? '—'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('import_duplicates') && is_array(session('import_duplicates')) && count(session('import_duplicates'))): ?>
                    <?php
                        $dups = session('import_duplicates');
                        $dupLimit = (int) (session('import_duplicates_limit') ?? 0);
                    ?>

                    <div class="mt-6 bg-white rounded-xl shadow p-6 border border-yellow-200">
                        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                            <h2 class="text-sm font-bold tracking-wide text-yellow-800">
                                Possible Duplicates (Skipped)
                            </h2>
                            <?php if($dupLimit > 0 && count($dups) >= $dupLimit): ?>
                                <div class="text-xs text-gray-500">
                                    Showing first <?php echo e($dupLimit); ?> duplicates
                                </div>
                            <?php endif; ?>
                        </div>

                        <p class="text-xs text-gray-600 mb-3">
                            We detected that some uploaded <b>NAME</b> values already exist in your Customers list.
                            To avoid creating duplicates, those rows were skipped.
                        </p>

                        <div class="overflow-auto">
                            <table class="min-w-full border text-xs">
                                <thead class="bg-yellow-50">
                                    <tr>
                                        <th class="border p-2">Uploaded Name</th>
                                        <th class="border p-2">Uploaded Store</th>
                                        <th class="border p-2">Uploaded Route</th>
                                        <th class="border p-2">Uploaded Sub Route</th>
                                        <th class="border p-2">Sheet</th>
                                        <th class="border p-2">Row</th>
                                        <th class="border p-2">Existing Matches</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $dups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="border p-2 font-semibold"><?php echo e($d['customer'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($d['store_name'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($d['delivery_route'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($d['sub_route'] ?? ''); ?></td>
                                            <td class="border p-2"><?php echo e($d['_sheet'] ?? '—'); ?></td>
                                            <td class="border p-2 text-right"><?php echo e($d['_row'] ?? '—'); ?></td>
                                            <td class="border p-2">
                                                <?php if(!empty($d['_existing']) && is_array($d['_existing'])): ?>
                                                    <?php $__currentLoopData = $d['_existing']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="mb-1">
                                                            <span class="font-semibold">#<?php echo e($ex['id']); ?></span>
                                                            — <?php echo e($ex['delivery_route']); ?> / <?php echo e($ex['sub_route']); ?>

                                                            — <?php echo e($ex['store_name']); ?>

                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\customers\import.blade.php ENDPATH**/ ?>