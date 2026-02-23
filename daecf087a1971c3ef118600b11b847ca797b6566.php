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
            <i data-lucide="file-text" class="w-6 h-6 text-blue-700"></i>
            Period Performance Summary
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="p-6 space-y-6">

        
        <div class="bg-white shadow rounded-lg p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                <div>
                    <label class="text-sm font-semibold">Branch</label>
                    <select name="branch" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="Solsona" <?php echo e(request('branch')=='Solsona' ? 'selected' : ''); ?>>Solsona</option>
                        <option value="Laoag"   <?php echo e(request('branch')=='Laoag'   ? 'selected' : ''); ?>>Laoag</option>
                        <option value="Batac"   <?php echo e(request('branch')=='Batac'   ? 'selected' : ''); ?>>Batac</option>
                    </select>
                </div>

                
                <div>
                    <label class="text-sm font-semibold">Period No</label>
                    <select name="period_no" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(request('period_no') == $i ? 'selected' : ''); ?>>
                                Period <?php echo e($i); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                
                <div>
                    <label class="text-sm font-semibold">Report Date</label>
                    <input
                        type="date"
                        name="report_date"
                        value="<?php echo e(request('report_date')); ?>"
                        class="w-full border rounded p-2">
                </div>

                
                <div>
                    <label class="text-sm font-semibold">Search</label>
                    <input
                        type="text"
                        name="shipment_no"
                        value="<?php echo e(request('shipment_no')); ?>"
                        class="w-full border rounded p-2">
                </div>

                
                <div>
                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 font-semibold">
                            Search
                        </button>

                        <a
                            href="<?php echo e(route('admin.reports.periods.index')); ?>"
                            class="w-full bg-gray-100 text-gray-800 p-2 rounded hover:bg-gray-200 font-semibold text-center border border-gray-300">
                            Clear
                        </a>

                        
                        <a
                            href="<?php echo e(route('admin.reports.periods.print-preview', [
                                'branch' => request('branch'),
                                'period_from' => request('period_no') ?: request('period_from'),
                                'period_to' => request('period_no') ?: request('period_to'),
                                'date_from' => request('report_date') ?: request('date_from'),
                                'date_to' => request('report_date') ?: request('date_to'),
                                'shipment_no' => request('shipment_no'),
                            ])); ?>"
                            target="_blank"
                            class="w-full bg-gray-900 text-white p-2 rounded hover:bg-black font-semibold text-center">
                            Print Preview
                        </a>
                    </div>
                </div>

            </form>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            <div class="bg-blue-100 p-4 rounded shadow">
                <p class="text-xs text-gray-600">Total Target</p>
                <h3 class="text-xl font-bold">
                    <?php echo e(number_format($kpi['total_target'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-green-100 p-4 rounded shadow">
                <p class="text-xs text-gray-600">Total Actual</p>
                <h3 class="text-xl font-bold">
                    <?php echo e(number_format($kpi['total_sales'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-emerald-100 p-4 rounded shadow">
                <p class="text-xs text-gray-600">Total Actual (Peso)</p>
                <h3 class="text-xl font-bold">
                    <?php echo e(number_format($kpi['total_sales_peso'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-orange-100 p-4 rounded shadow">
                <p class="text-xs text-gray-600">Total Variance</p>
                <h3 class="text-xl font-bold">
                    <?php echo e(number_format($kpi['total_variance'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-purple-100 p-4 rounded shadow">
                <p class="text-xs text-gray-600">Avg Achievement</p>
                <h3 class="text-xl font-bold">
                    <?php echo e(number_format($avgAchievement ?? 0, 2)); ?>%
                </h3>
            </div>

        </div>


</div>
        
        <div class="flex justify-end">
            <a href="<?php echo e(route('admin.reports.periods.create')); ?>"
               class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                Add Period Report
            </a>
        </div>

        
        <div class="bg-white shadow rounded-lg p-4">

            <?php if($reports->isEmpty()): ?>
                <p class="text-center text-gray-500 py-4">No reports found.</p>
            <?php else: ?>
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">Report Date</th>
                            <th class="border p-2">Shipment No</th>
                            <th class="border p-2">Period</th>
                            <th class="border p-2">Branch</th>
                            <th class="border p-2 text-right">Target</th>
                            <th class="border p-2 text-right">Actual</th>
                            <th class="border p-2 text-right">Total (Peso)</th>
                            <th class="border p-2 text-right">Variance</th>
                            <th class="border p-2 text-right">% Achieved</th>
                            <th class="border p-2">Status</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $target = $r->target_sales ?? 0;
                            $actual = $r->actual_sales ?? 0;
                            $actualPeso = $r->actual_sales_peso_calc ?? 0;
                            $var    = $r->total_variance ?? 0;
                            $ach    = $r->achievement_pct ?? 0;
                            $status = strtolower((string) ($r->status ?? 'draft'));
                            $isDraft = $status === 'draft';
                            $isSubmitted = $status === 'submitted';
                            $isApproved = $status === 'approved';
                            $isLocked = $isSubmitted || $isApproved;
                        ?>

                        <tr class="hover:bg-gray-50 text-center">

                            
                            <td class="border p-2">
                                <?php echo e($r->report_date?->format('M d, Y') ?? $r->created_at?->format('M d, Y')); ?>

                            </td>

                            
                            <td class="border p-2">
                                <?php echo e($r->shipment_no ?? '-'); ?>

                            </td>

                            
                            <td class="border p-2">
                                Period <?php echo e($r->period_no); ?>

                            </td>

                            
                            <td class="border p-2">
                                <?php echo e($r->branch); ?>

                            </td>

                            <td class="border p-2 text-right"><?php echo e(number_format($target,2)); ?></td>
                            <td class="border p-2 text-right"><?php echo e(number_format($actual,2)); ?></td>
                            <td class="border p-2 text-right"><?php echo e(number_format($actualPeso,2)); ?></td>

                            <td class="border p-2 text-right">
                                <span class="<?php echo e($var <= 0 ? 'text-green-700' : 'text-red-700'); ?>">
                                    <?php echo e(number_format($var,2)); ?>

                                </span>
                            </td>

                            <td class="border p-2 text-right">
                                <?php echo e(number_format($ach,2)); ?>%
                            </td>

                            <td class="border p-2">
                                <span class="px-2 py-1 text-xs rounded
                                    <?php echo e($r->status === 'approved' ? 'bg-green-600 text-white' :
                                       ($r->status === 'submitted' ? 'bg-yellow-500 text-black' :
                                        'bg-gray-300 text-gray-700')); ?>">
                                    <?php echo e(strtoupper($r->status ?? 'draft')); ?>

                                </span>
                            </td>

                            <td class="border p-2 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.reports.periods.show',$r->id)); ?>"
                                   class="text-blue-600 hover:underline">View</a>

                                <?php if(!$isLocked): ?>
                                    <a href="<?php echo e(route('admin.reports.periods.edit',$r->id)); ?>"
                                       class="text-green-600 hover:underline ml-2">Edit</a>
                                <?php endif; ?>

                                <?php if($isDraft): ?>
                                    <form action="<?php echo e(route('admin.reports.periods.submit', $r->id)); ?>"
                                          method="POST"
                                          class="inline ml-2"
                                          onsubmit="return confirm('Submit this draft report? You will no longer be able to edit it.');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-indigo-700 hover:underline">
                                            Submit
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if($isSubmitted): ?>
                                    <form action="<?php echo e(route('admin.reports.periods.approve', $r->id)); ?>"
                                          method="POST"
                                          class="inline ml-2"
                                          onsubmit="return confirm('Approve this submitted report?');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-emerald-700 hover:underline">
                                            Approve
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if(!$isLocked): ?>
                                    <form action="<?php echo e(route('admin.reports.periods.destroy', $r->id)); ?>"
                                          method="POST"
                                          class="inline ml-2"
                                          onsubmit="return confirm('Delete this report for <?php echo e($r->branch); ?> (Period <?php echo e($r->period_no); ?>) dated <?php echo e($r->report_date?->format('M d, Y') ?? $r->created_at?->format('M d, Y')); ?>?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div class="flex justify-end mt-4">
                    <div x-data="{ openExport: false }" class="relative">

                        <button
                            type="button"
                            @click="openExport = !openExport"
                            class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 flex items-center gap-2 font-semibold">
                            <i data-lucide="download" class="w-5 h-5"></i>
                            Download
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>

                        <div
                            x-show="openExport"
                            @click.away="openExport = false"
                            x-transition
                            class="absolute right-0 mt-2 w-72 bg-white shadow-lg border rounded-lg p-4 z-30">

                            <form action="<?php echo e(route('admin.reports.periods.export.range')); ?>"
                                  method="GET"
                                  target="_blank"
                                  class="space-y-3">

                                <div>
                                    <label class="text-xs font-semibold">Export As</label>
                                    <select name="type" class="w-full border rounded p-2 text-sm" required>
                                        <option value="" disabled selected>Select Type...</option>
                                        <option value="pdf">PDF</option>
                                        <option value="csv">CSV</option>
                                        <option value="xlsx">Excel (XLSX)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold">Branch</label>
                                    <select name="branch" class="w-full border rounded p-2 text-sm">
                                        <option value="">All Branches</option>
                                        <option value="Solsona">Solsona</option>
                                        <option value="Laoag">Laoag</option>
                                        <option value="Batac">Batac</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold">Period From</label>
                                    <select name="period_from" class="w-full border rounded p-2 text-sm" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php for($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo e($i); ?>">Period <?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-xs font-semibold">Period To</label>
                                    <select name="period_to" class="w-full border rounded p-2 text-sm" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php for($i = 1; $i <= 12; $i++): ?>
                                            <option value="<?php echo e($i); ?>">Period <?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 flex justify-center gap-2 font-semibold">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                    Download
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views/admin/reports/periods/index.blade.php ENDPATH**/ ?>