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
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="text-xl font-bold text-red-700 flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="w-6 h-6 text-red-700"></i>
                Period Summary Dashboard
            </h2>

            
            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-800">Branch:</label>
                <select name="branch"
                        class="border rounded px-2 py-1 text-sm focus:ring-red-600"
                        onchange="this.form.submit()">

                    <option value="">All Branches</option>
                    <option value="Solsona" <?php echo e(request('branch')=='Solsona'?'selected':''); ?>>Solsona</option>
                    <option value="Laoag"   <?php echo e(request('branch')=='Laoag'  ?'selected':''); ?>>Laoag</option>
                    <option value="Batac"   <?php echo e(request('branch')=='Batac'  ?'selected':''); ?>>Batac</option>
                </select>

                
                <input type="hidden" name="period_from" value="<?php echo e(request('period_from')); ?>">
                <input type="hidden" name="period_to"   value="<?php echo e(request('period_to')); ?>">
            </form>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="p-6 space-y-6">

        
        <div class="bg-white shadow-md rounded-lg p-5 border-t-4 border-red-600">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                
                <div>
                    <label class="text-sm font-semibold">Branch</label>
                    <select name="branch" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <option value="Solsona" <?php echo e(request('branch')=='Solsona'?'selected':''); ?>>Solsona</option>
                        <option value="Laoag"   <?php echo e(request('branch')=='Laoag'  ?'selected':''); ?>>Laoag</option>
                        <option value="Batac"   <?php echo e(request('branch')=='Batac'  ?'selected':''); ?>>Batac</option>
                    </select>
                </div>

                
                <div>
                    <label class="text-sm font-semibold">Period From</label>
                    <select name="period_from" class="w-full border rounded p-2">
                        <option value="">All</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e(request('period_from') == $i ? 'selected' : ''); ?>>
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
                        <option value="<?php echo e($i); ?>" <?php echo e(request('period_to') == $i ? 'selected' : ''); ?>>
                            Period <?php echo e($i); ?>

                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="flex items-end">
                    <button class="w-full bg-red-700 text-white p-2 rounded hover:bg-red-800 font-semibold">
                        🔍 Filter
                    </button>
                </div>

            </form>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded-lg p-4 border-l-4 border-red-600">
                <p class="text-xs font-semibold text-gray-600 uppercase">Total Actual Sales</p>
                <h3 class="text-2xl font-bold text-gray-900">
                    ₱<?php echo e(number_format($kpi['total_sales'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-white shadow rounded-lg p-4 border-l-4 border-gray-700">
                <p class="text-xs font-semibold text-gray-600 uppercase">Total Variance</p>
                <h3 class="text-2xl font-bold text-gray-900">
                    ₱<?php echo e(number_format($kpi['total_variance'] ?? 0, 2)); ?>

                </h3>
            </div>

            <div class="bg-white shadow rounded-lg p-4 border-l-4 border-black">
                <p class="text-xs font-semibold text-gray-600 uppercase">Ending Receivables</p>
                <h3 class="text-2xl font-bold text-gray-900">
                    ₱<?php echo e(number_format($kpi['ending_receivables'] ?? 0, 2)); ?>

                </h3>
            </div>
        </div>

        
<div class="flex justify-end items-center gap-3">

    
    <a href="<?php echo e(route('admin.reports.periods.create')); ?>"
       class="bg-red-700 text-black px-4 py-2 rounded-lg hover:bg-red-800 flex items-center gap-2 font-semibold">
        <i data-lucide="plus-circle" class="w-5 h-5"></i>
        Add Period Report
    </a>

    
    <div x-data="{ openExport: false }" class="relative">

        
        <button @click="openExport = !openExport"
                class="bg-blue-700 text-black px-4 py-2 rounded-lg hover:bg-blue-800 flex items-center gap-2 font-semibold">
            <i data-lucide="download" class="w-5 h-5"></i>
            Download
            <i data-lucide="chevron-down" class="w-4 h-4"></i>
        </button>

        
        <div x-show="openExport"
             @click.away="openExport = false"
             class="absolute right-0 mt-2 w-72 bg-white shadow-lg border rounded-lg p-4 z-30">
            <form action="<?php echo e(route('admin.reports.periods.export.range')); ?>" method="GET" class="space-y-3">

                
                <div>
                    <label class="text-xs font-semibold">Export As</label>
                    <select name="type" class="w-full border rounded p-2 text-sm" required>
                        <option value="" disabled selected>Select Type...</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
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

                
                <button class="w-full bg-blue-700 text-black px-4 py-2 rounded hover:bg-blue-800 flex justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download
                </button>

            </form>
        </div>
    </div>

</div>


        
        <div class="bg-white shadow-lg rounded-lg p-4">
            <?php if($reports->isEmpty()): ?>
                <p class="text-center text-gray-500 py-4">No reports found.</p>
            <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="p-2 border">Period</th>
                            <th class="p-2 border">Date</th>
                            <th class="p-2 border">Branch</th>
                            <th class="p-2 border text-right">Target</th>
                            <th class="p-2 border text-right">Actual</th>
                            <th class="p-2 border text-right">% Achieved</th>
                            <th class="p-2 border text-right">Variance</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $target = $r->target_sales ?? 0;
                                $actual = $r->actual_sales ?? 0;
                                $ach    = $r->achievement_pct ?? 0;
                                $var    = $r->total_variance ?? 0;
                            ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-2 border text-center">Period <?php echo e($r->period_no); ?></td>
                                <td class="p-2 border text-center">
                                    <?php echo e($r->report_date 
                                        ? \Carbon\Carbon::parse($r->report_date)->format('Y-m-d')
                                        : '—'); ?>

                                </td>
                                <td class="p-2 border"><?php echo e($r->branch); ?></td>
                                <td class="p-2 border text-right">₱<?php echo e(number_format($target, 2)); ?></td>
                                <td class="p-2 border text-right">₱<?php echo e(number_format($actual, 2)); ?></td>
                                <td class="p-2 border text-right"><?php echo e(number_format($ach, 2)); ?>%</td>
                                <td class="p-2 border text-right">₱<?php echo e(number_format($var, 2)); ?></td>

                                
                                <td class="p-2 border text-center">
                                    <?php if($r->status === 'pending'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-semibold">
                                            PENDING
                                        </span>
                                    <?php elseif($r->status === 'draft'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-gray-200 text-gray-800 font-semibold">
                                            DRAFT
                                        </span>
                                    <?php elseif($r->status === 'submitted'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 font-semibold">
                                            SUBMITTED
                                        </span>
                                    <?php elseif($r->status === 'approved'): ?>
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-semibold">
                                            APPROVED
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600 font-semibold">
                                            UNKNOWN
                                        </span>
                                    <?php endif; ?>
                                </td>

                                
                                <td class="p-2 border">
                                    <div class="flex items-center justify-center gap-3">

                                        
                                        <a href="<?php echo e(route('admin.reports.periods.show',$r->id)); ?>"
                                        class="text-black hover:text-gray-700" title="View">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>

                                        
                                        <a href="<?php echo e(route('admin.reports.periods.edit', $r->id)); ?>"
                                        class="text-red-700 hover:text-red-900"
                                        title="Edit (Quick)">
                                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\consolidated\period-summary.blade.php ENDPATH**/ ?>