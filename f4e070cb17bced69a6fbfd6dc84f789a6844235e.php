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
    <h2 class="text-xl font-bold">
        View Period <?php echo e($report->period_no); ?> - <?php echo e($report->branch); ?>

    </h2>
 <?php $__env->endSlot(); ?>

<div class="p-6 space-y-6">


<div class="bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="rounded border border-gray-200 bg-gray-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-600">Target Sales</p>
        <p class="text-xl font-bold text-gray-900"><?php echo e(number_format($report->target_sales,2)); ?></p>
    </div>
    <div class="rounded border border-red-200 bg-red-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Core Target Sales</p>
        <p class="text-xl font-bold text-red-900"><?php echo e(number_format($report->core_target_sales ?? 0,2)); ?></p>
    </div>
    <div class="rounded border border-amber-200 bg-amber-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">PET CSD Target Sales</p>
        <p class="text-xl font-bold text-amber-900"><?php echo e(number_format($report->petcsd_target_sales ?? 0,2)); ?></p>
    </div>
    <div class="rounded border border-blue-200 bg-blue-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Stills Target Sales</p>
        <p class="text-xl font-bold text-blue-900"><?php echo e(number_format($report->stills_target_sales ?? 0,2)); ?></p>
    </div>
    <div class="rounded border border-red-200 bg-red-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Core Actual Sales (Incl PET CSD)</p>
        <p class="text-xl font-bold text-red-900"><?php echo e(number_format((float) ($report->core_actual_sales ?? 0),2)); ?></p>
    </div>
    <div class="rounded border border-amber-200 bg-amber-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">PET CSD Actual Sales (Separate)</p>
        <p class="text-xl font-bold text-amber-900"><?php echo e(number_format((float) ($report->petcsd_actual_sales ?? 0),2)); ?></p>
    </div>
    <div class="rounded border border-blue-200 bg-blue-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Stills Actual Sales</p>
        <p class="text-xl font-bold text-blue-900"><?php echo e(number_format((float) ($report->stills_actual_sales ?? 0),2)); ?></p>
    </div>
    <div class="rounded border border-green-200 bg-green-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Overall Actual Sales</p>
        <p class="text-xl font-bold text-green-900"><?php echo e(number_format((float) ($actual ?? $report->actual_sales ?? 0),2)); ?></p>
    </div>
    <div class="rounded border border-orange-200 bg-orange-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-orange-700">Variance</p>
        <p class="text-xl font-bold text-orange-900"><?php echo e(number_format((float) ($variance ?? $report->total_variance ?? 0),2)); ?></p>
    </div>
    <div class="rounded border border-purple-200 bg-purple-50 px-3 py-2">
        <p class="text-xs font-semibold uppercase tracking-wide text-purple-700">Achievement</p>
        <p class="text-xl font-bold text-purple-900"><?php echo e(number_format((float) ($achievement ?? $report->achievement_pct ?? 0),2)); ?>%</p>
    </div>
</div>


<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold text-lg mb-3 text-red-700">
        Coca-Cola Sales Performance Report
    </h3>

    <table class="w-full border text-xs">
        <thead>
        <tr>
            <th class="border p-2 bg-red-700 text-white">Pack</th>
            <th class="border p-2 bg-red-700 text-white">Product</th>
            <th class="border p-2 bg-red-700 text-white">Core PCS</th>
            <th class="border p-2 bg-red-700 text-white">Core UCS</th>
            <th class="border p-2 bg-red-700 text-white">Core Total</th>
            <th class="border p-2 bg-blue-700 text-white">IWS PCS</th>
            <th class="border p-2 bg-blue-700 text-white">IWS UCS</th>
            <th class="border p-2 bg-blue-700 text-white">IWS Total</th>
        </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $report->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $coreTotal = (float) ($item->core_total_ucs ?? (($item->core_pcs ?? 0) * ($item->core_ucs ?? 0)));
                $iwsTotal  = (float) ($item->iws_total_ucs  ?? (($item->iws_pcs  ?? 0) * ($item->iws_ucs  ?? 0)));
            ?>

            <tr class="hover:bg-gray-50">
                <td class="border p-2 bg-red-50 font-medium text-red-900"><?php echo e($item->pack); ?></td>
                <td class="border p-2 bg-red-50 font-medium text-red-900"><?php echo e($item->product); ?></td>

                <td class="border p-2 text-right"><?php echo e((int) ($item->core_pcs ?? 0)); ?></td>
                <td class="border p-2 text-right"><?php echo e(number_format((float) ($item->core_ucs ?? 0), 6)); ?></td>
                <td class="border p-2 text-right bg-red-50 font-semibold"><?php echo e(number_format($coreTotal, 2)); ?></td>

                <td class="border p-2 text-right"><?php echo e((int) ($item->iws_pcs ?? 0)); ?></td>
                <td class="border p-2 text-right"><?php echo e(number_format((float) ($item->iws_ucs ?? 0), 6)); ?></td>
                <td class="border p-2 text-right bg-blue-50 font-semibold"><?php echo e(number_format($iwsTotal, 2)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="border p-3 text-center text-gray-500">No sales items</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold text-lg mb-3">Inventory & Days Level</h3>

    <table class="w-full border text-[10px]">
        <thead class="bg-gray-900 text-white">
        <tr>
            <th class="border px-2 py-1">Pack</th>
            <th class="border px-2 py-1">Product</th>
            <th class="border px-2 py-1">SRP</th>
            <th class="border px-2 py-1">Peso Eq</th>
            <th class="border px-2 py-1">Actual</th>
            <th class="border px-2 py-1">ADS</th>
            <th class="border px-2 py-1">Days</th>
            <th class="border px-2 py-1">Booking</th>
            <th class="border px-2 py-1">Days</th>
            <th class="border px-2 py-1">Deliveries</th>
            <th class="border px-2 py-1">PTD</th>
            <th class="border px-2 py-1">Days</th>
            <th class="border px-2 py-1">Routing</th>
            <th class="border px-2 py-1">Est Sales</th>
            <th class="border px-2 py-1">After P5</th>
            <th class="border px-2 py-1">Peso Eq</th>
            <th class="border px-2 py-1">Days</th>
            <th class="border px-2 py-1">Routing 7</th>
            <th class="border px-2 py-1">Est 7</th>
            <th class="border px-2 py-1">After Month</th>
            <th class="border px-2 py-1">Days</th>
            <th class="border px-2 py-1">Peso Eq</th>
        </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = ($inventoryRows ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $srp = (float) ($row['srp'] ?? 0);
            $actualInv = (float) ($row['actual_inv'] ?? 0);
            $ads = (float) ($row['ads'] ?? 0);
            $booking = (float) ($row['booking'] ?? 0);
            $deliveries = (float) ($row['deliveries'] ?? 0);
            $routingP5 = (float) ($row['routing_days_p5'] ?? 0);
            $routing7 = (float) ($row['routing_days_7'] ?? 0);

            $pesoActual = $srp * $actualInv;
            $daysActual = $ads > 0 ? ($actualInv / $ads) : 0;
            $daysBooking = $ads > 0 ? ($booking / $ads) : 0;
            $ptdTotal = $actualInv + $booking + $deliveries;
            $daysPtd = $ads > 0 ? ($ptdTotal / $ads) : 0;
            $estP5 = $ads * $routingP5;
            $afterP5 = $ptdTotal - $estP5;
            $pesoP5 = $srp * $afterP5;
            $daysAfterP5 = $ads > 0 ? ($ptdTotal / $ads) : 0;
            $est7 = $ads * $routing7;
            $afterMonth = $afterP5 - $est7;
            $daysMonth = $ads > 0 ? ($afterMonth / $ads) : 0;
            $pesoMonth = $srp * $afterMonth;
        ?>
        <tr>
            <td class="border px-2 py-1"><?php echo e($row['pack']); ?></td>
            <td class="border px-2 py-1"><?php echo e($row['product']); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($srp,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($pesoActual,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($actualInv,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($ads,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($daysActual,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($booking,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($daysBooking,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($deliveries,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($ptdTotal,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($daysPtd,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($routingP5,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($estP5,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($afterP5,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($pesoP5,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($daysAfterP5,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($routing7,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($est7,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($afterMonth,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($daysMonth,2)); ?></td>
            <td class="border px-2 py-1 text-right"><?php echo e(number_format($pesoMonth,2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr>
            <td colspan="22" class="border p-3 text-center text-gray-500">No inventory data</td>
        </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>


<div class="bg-white shadow rounded p-4 space-y-3">
    <h3 class="font-semibold text-lg">Additional Custom Tables</h3>

    <?php $__empty_1 = true; $__currentLoopData = ($customTables ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="border rounded p-3">
            <h4 class="font-semibold mb-2"><?php echo e($tbl['title']); ?></h4>
            <table class="w-full border text-sm">
                <thead>
                <tr>
                    <?php $__currentLoopData = $tbl['headers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="border p-2"><?php echo e($h); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $tbl['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="border p-2"><?php echo e($cell); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-gray-500 italic">No custom tables</p>
    <?php endif; ?>
</div>


<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold text-lg mb-3">Per SKU (Reference)</h3>

    <table class="w-full border text-[11px]">
        <thead>
        <tr class="bg-gray-300 text-gray-900 uppercase text-[11px] font-semibold">
            <th class="border px-2 py-1 text-left" rowspan="2">Products</th>
            <th class="border px-2 py-1 text-center" colspan="2">Target</th>
            <th class="border px-2 py-1 text-center" colspan="2">Actual</th>
            <th class="border px-2 py-1 text-center" colspan="2">Variance</th>
        </tr>
        <tr class="bg-yellow-300 text-gray-900 uppercase text-[10px] font-semibold">
            <th class="border px-2 py-1 text-center">In PCS</th>
            <th class="border px-2 py-1 text-center">In UCS</th>
            <th class="border px-2 py-1 text-center">In PCS</th>
            <th class="border px-2 py-1 text-center">In UCS</th>
            <th class="border px-2 py-1 text-center">In PCS</th>
            <th class="border px-2 py-1 text-center">In UCS</th>
        </tr>
        </thead>
        <tbody>
        <?php $__empty_1 = true; $__currentLoopData = ($perSkuRows ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $targetPcs = (float) ($row['target_pcs'] ?? 0);
                $targetUcs = (float) ($row['target_ucs'] ?? 0);
                $actualPcs = (float) ($row['actual_pcs'] ?? 0);
                $actualUcs = (float) ($row['actual_ucs'] ?? 0);
                $varPcs = $targetPcs - $actualPcs;
                $varUcs = $targetUcs - $actualUcs;
            ?>
            <tr>
                <td class="border px-2 py-1">
                    <div class="font-semibold"><?php echo e($row['product'] ?? ''); ?></div>
                    <div class="text-[10px] text-gray-500"><?php echo e($row['pack'] ?? ''); ?></div>
                </td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($targetPcs, 0)); ?></td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($targetUcs, 6)); ?></td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($actualPcs, 0)); ?></td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($actualUcs, 6)); ?></td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($varPcs, 0)); ?></td>
                <td class="border px-2 py-1 text-right"><?php echo e(number_format($varUcs, 6)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="border p-3 text-center text-gray-500">No per SKU data</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="flex justify-end gap-3">
    <a href="<?php echo e(route('admin.reports.periods.index')); ?>" class="border px-4 py-2 rounded">Back</a>
    <a href="<?php echo e(route('admin.reports.periods.edit',$report->id)); ?>" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
</div>

</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\periods\show.blade.php ENDPATH**/ ?>