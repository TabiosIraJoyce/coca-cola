<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Period Report</title>

<style>
    body { font-family: sans-serif; font-size: 11px; }
    .title { text-align:center; font-size:18px; font-weight:bold; }
    .section { background:#2563eb; color:#fff; padding:6px; margin-top:15px; }
    .company { text-align:center; margin-bottom: 6px; }
    .company .name { font-size: 18px; font-weight: 800; }
    .company .line { font-size: 11px; margin-top: 2px; }
    .meta { margin-top: 6px; border-top: 1px solid #000; padding-top: 6px; font-size: 12px; text-align: center; }
    .meta b { font-weight: 700; }
    table { width:100%; border-collapse:collapse; margin-top:8px; }
    th, td { border:1px solid #000; padding:5px; text-align:center; }
    th { background:#e5e7eb; }
    .no-print { display: none; }
    .inv-table th, .inv-table td { padding: 3px; font-size: 8px; }
    thead { display: table-header-group; }
    tfoot { display: table-row-group; }
    .excel-header { margin-top: 10px; border: 1px solid #000; }
    .excel-header-top { width: 100%; display: table; table-layout: fixed; border-bottom: 1px solid #000; }
    .excel-header-left, .excel-header-right { display: table-cell; vertical-align: top; }
    .excel-header-left {
        width: 62%;
        border-right: 1px solid #000;
        padding: 12px 10px;
        text-align: center;
        vertical-align: middle;
    }
    .excel-header-left .branch-name { font-size: 34px; font-weight: 900; line-height: 1; }
    .excel-header-left .period-name { margin-top: 8px; font-size: 18px; font-weight: 800; }
    .excel-header-left .date-range { margin-top: 8px; font-size: 15px; font-weight: 700; }
    .excel-header-left .routing-days { margin-top: 4px; font-size: 12px; font-weight: 700; }
    .excel-header-right { width: 38%; padding: 0; vertical-align: middle; text-align: center; }
    .excel-summary { width: 96%; margin: 0 auto; border-collapse: collapse; }
    .excel-summary th, .excel-summary td { border: 1px solid #000; padding: 4px 6px; text-align: center; }
    .excel-summary thead th { background: #efb187; font-weight: 800; }
    .excel-summary .period-cell { background: #c10000; color: #fff; width: 18%; }
    .excel-summary .label { text-align: center; font-weight: 800; background: #fff; }
    .excel-summary .total-label { font-weight: 900; }
    .excel-summary .summary-stills td { background: #dbeafe; color: #1d4ed8; font-weight: 700; }
    .excel-summary .summary-petcsd td { background: #fef3c7; color: #a16207; font-weight: 700; }
    .excel-strip { width: 100%; margin-top: 0; border-collapse: collapse; }
    .excel-strip th, .excel-strip td { border: 1px solid #000; padding: 4px 6px; text-align: center; font-weight: 700; }
    .excel-strip .label { width: 120px; background: #c10000; color: #fff; }
    .excel-strip .value { background: #c10000; color: #fff; }
    .products-table .matrix-top-label,
    .products-table .matrix-top-value {
        background: #c10000;
        color: #fff;
        font-weight: 800;
    }
    .products-table .matrix-pack {
        background: #c10000;
        color: #fff;
        font-weight: 800;
        text-transform: uppercase;
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        letter-spacing: 0.3px;
        width: 36px;
    }
    .products-table .matrix-product {
        text-align: left;
        font-weight: 700;
    }
    .products-table .matrix-product.core { color: #b91c1c; }
    .products-table .matrix-product.stills { color: #1d4ed8; }
    .products-table .matrix-product.petcsd { color: #a16207; }
    .products-table .shipment-col {
        width: 74px;
    }
    .products-table .summary-col {
        width: 82px;
    }
    .products-table .core-group {
        background: #114c8f;
        color: #fff;
        font-weight: 800;
    }
    .products-table .iws-group {
        background: #0f6ea8;
        color: #fff;
        font-weight: 800;
    }
    .products-table .amount-group {
        background: #7c4a1f;
        color: #fff;
        font-weight: 800;
    }
    .products-table .sub-core-pcs {
        background: #f3e8e8;
        font-weight: 700;
    }
    .products-table .sub-core-ucs {
        background: #f0e6e6;
        font-weight: 700;
    }
    .products-table .sub-core-total {
        background: #eadede;
        font-weight: 700;
    }
    .products-table .sub-iws-cases {
        background: #d3dde9;
        font-weight: 700;
    }
    .products-table .sub-iws-total {
        background: #c8d4e4;
        font-weight: 700;
    }
    .products-table .sub-srp {
        background: #f2e8dc;
        font-weight: 700;
    }
    .products-table .sub-total-amount {
        background: #ecdfcf;
        font-weight: 700;
    }
    .products-table .cell-core-pcs {
        background: #f7eded;
        text-align: right;
    }
    .products-table .cell-core-ucs {
        background: #f3ebeb;
        text-align: right;
    }
    .products-table .cell-core-total {
        background: #eee3e3;
        text-align: right;
    }
    .products-table .cell-iws-cases {
        background: #dde6ef;
        text-align: right;
    }
    .products-table .cell-iws-total {
        background: #d3deea;
        text-align: right;
    }
    .products-table .cell-srp {
        background: #f6eee4;
        text-align: right;
    }
    .products-table .cell-total-amount {
        background: #f0e6da;
        text-align: right;
    }
    .products-table .category-divider td {
        font-weight: 800;
        text-align: left;
        padding-left: 8px;
    }
    .products-table .category-divider.core td {
        background: #fee2e2;
        color: #991b1b;
    }
    .products-table .category-divider.petcsd td {
        background: #fef3c7;
        color: #92400e;
    }
    .products-table .category-divider.stills td {
        background: #dbeafe;
        color: #1e3a8a;
    }
    .products-table .overall-total td {
        background: #f3f4f6;
        font-weight: 800;
    }
    .products-table .overall-total .label-cell {
        text-align: left;
        padding-left: 8px;
    }
    @page { size: legal landscape; margin: 10mm; }
    @media print {
        .no-print { display: block; }
        @page { size: legal landscape; margin: 10mm; }
    }
</style>
</head>

<body>

<?php if(!empty($isPrintPreview)): ?>
<div class="no-print" style="margin: 10px 0; display:flex; gap:10px; align-items:center;">
    <button onclick="window.print()" style="padding:8px 12px; border:1px solid #111; background:#111; color:#fff; border-radius:6px; cursor:pointer;">
        Print
    </button>
    <div style="font-size:12px; color:#333;">
        Print Preview (Legal, Landscape)
    </div>
</div>
<?php endif; ?>

<div class="company">
    <div class="name">Gledco Multipurpose Cooperative</div>
    <div class="line">Brgy. 9 Sta. Angela F.R. Castro cor. Balintawak St.</div>
    <div class="line">Laoag City, Ilocos Norte 2900</div>
    <div class="line">Registration No. 9520-01001354 &nbsp; | &nbsp; TIN: 005-511-934</div>
</div>

<div class="meta">
    <b>Division:</b> <?php echo e($divisionName ?? '-'); ?>

    &nbsp; | &nbsp;
    <b>Date Range:</b>
    <?php echo e(($dateFrom ?? '-')); ?> to <?php echo e(($dateTo ?? '-')); ?>

</div>

<?php if($reports->isEmpty()): ?>
<div style="margin-top:12px; padding:10px; border:1px solid #000; background:#f9fafb; font-size:12px;">
    No period reports found for the selected filters.
</div>
<?php endif; ?>

<?php
    $sortedReports = $reports->sortBy(function ($r) {
        $date = $r->report_date?->toDateString() ?? $r->created_at?->toDateString() ?? '';
        $period = (int) ($r->period_no ?? 0);
        $branch = (string) ($r->branch ?? '');
        $shipment = (string) ($r->shipment_no ?? '');
        $id = (int) ($r->id ?? 0);

        return sprintf('%s|%02d|%s|%s|%010d', $date, $period, $branch, $shipment, $id);
    });

    $groupedReports = $sortedReports->groupBy(function ($r) {
        $date = $r->report_date?->toDateString() ?? $r->created_at?->toDateString() ?? '';
        return $date . '|' . (string) ($r->period_no ?? '') . '|' . (string) ($r->branch ?? '');
    });
?>

<?php $__currentLoopData = $groupedReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $groupReports): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $report = $groupReports->first();
    $displayDate = $report->report_date?->format('Y-m-d')
        ?? $report->created_at?->format('Y-m-d')
        ?? '-';

    $shipmentNos = $groupReports
        ->pluck('shipment_no')
        ->map(fn ($v) => trim((string) $v))
        ->filter()
        ->unique()
        ->values();

    $targetTotal = (float) ($groupReports->max('target_sales') ?? 0);
    $coreT   = (float) ($groupReports->max('core_target_sales') ?? 0);
    $petT    = (float) ($groupReports->max('petcsd_target_sales') ?? 0);
    $stillsT = (float) ($groupReports->max('stills_target_sales') ?? 0);

    // CORE target can be either CORE-only or CORE+PET depending on earlier logic.
    $coreRowTarget = $coreT;
    if (abs(($coreT + $petT + $stillsT) - $targetTotal) < 0.01) {
        $coreRowTarget = $coreT;
    } elseif (abs(($coreT + $stillsT) - $targetTotal) < 0.01) {
        $coreRowTarget = max(0, $coreT - $petT);
    } else {
        $coreRowTarget = max(0, $coreT - $petT);
        if ($coreRowTarget <= 0) $coreRowTarget = $coreT;
    }

    $totalActual = (float) $groupReports->sum(fn ($r) => (float) ($r->actual_calc ?? $r->actual_sales ?? 0));
    $coreA   = (float) $groupReports->sum(fn ($r) => (float) ($r->core_actual_sales ?? 0));
    $petA    = (float) $groupReports->sum(fn ($r) => (float) ($r->petcsd_actual_sales ?? 0));
    $stillsA = (float) $groupReports->sum(fn ($r) => (float) ($r->stills_actual_sales ?? 0));

    // CORE actual sales in the system includes PET; for breakdown show CORE-only.
    $coreRowActual = max(0, $coreA - $petA);

    $pct = function (float $actual, float $target) {
        return $target > 0 ? round(($actual / $target) * 100, 0) : 0;
    };

    $var = function (float $actual, float $target) {
        // Sample shows variance as Actual - Target
        return $actual - $target;
    };

    $groupHeaderMeta = $headerMetaByGroup[$groupKey] ?? [];
    $rangeStartRaw = $groupHeaderMeta['range_start'] ?? ($dateFrom ?? $displayDate);
    $rangeEndRaw = $groupHeaderMeta['range_end'] ?? ($dateTo ?? $displayDate);

    $headerDateFrom = !empty($rangeStartRaw) ? date('M d, Y', strtotime($rangeStartRaw)) : $displayDate;
    $headerDateTo = !empty($rangeEndRaw) ? date('M d, Y', strtotime($rangeEndRaw)) : $displayDate;
    $headerDateRange = $headerDateFrom === $headerDateTo
        ? strtoupper($headerDateFrom)
        : strtoupper($headerDateFrom . ' - ' . $headerDateTo);

    $routingDaysCount = $groupHeaderMeta['routing_days'] ?? null;
    if ($routingDaysCount === null && !empty($rangeStartRaw) && !empty($rangeEndRaw)) {
        try {
            $fromDateObj = new \DateTime($rangeStartRaw);
            $toDateObj = new \DateTime($rangeEndRaw);
            $routingDaysCount = ((int) $fromDateObj->diff($toDateObj)->format('%a')) + 1;
        } catch (\Throwable $e) {
            $routingDaysCount = null;
        }
    }

    $headerShipments = $shipmentNos->isNotEmpty() ? $shipmentNos : collect(['-']);
    $headerCellDate = $report->report_date?->format('n/j/Y')
        ?? $report->created_at?->format('n/j/Y')
        ?? $displayDate;
?>

<div class="excel-header">
    <div class="excel-header-top">
        <div class="excel-header-left">
            <div class="branch-name"><?php echo e(strtoupper((string) ($report->branch ?? '-'))); ?></div>
            <div class="period-name">PERIOD <?php echo e($report->period_no); ?></div>
            <div class="date-range"><?php echo e($headerDateRange); ?></div>
            <div class="routing-days">
                (<?php echo e($routingDaysCount !== null ? $routingDaysCount . ' routing days' : 'routing days n/a'); ?>)
            </div>
        </div>
        <div class="excel-header-right">
            <table class="excel-summary">
                <thead>
                    <tr>
                        <th class="period-cell">P<?php echo e($report->period_no); ?></th>
                        <th>TARGET</th>
                        <th>ACTUAL</th>
                        <th>%</th>
                        <th>VARIANCE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="label">CORE</td>
                        <td><?php echo e(number_format($coreRowTarget, 0)); ?></td>
                        <td><?php echo e(number_format($coreRowActual, 0)); ?></td>
                        <td><?php echo e(number_format($pct($coreRowActual, $coreRowTarget), 0)); ?>%</td>
                        <td><?php echo e(number_format($var($coreRowActual, $coreRowTarget), 0)); ?></td>
                    </tr>
                    <tr class="summary-stills">
                        <td class="label">STILLS</td>
                        <td><?php echo e(number_format($stillsT, 0)); ?></td>
                        <td><?php echo e(number_format($stillsA, 0)); ?></td>
                        <td><?php echo e(number_format($pct($stillsA, $stillsT), 0)); ?>%</td>
                        <td><?php echo e(number_format($var($stillsA, $stillsT), 0)); ?></td>
                    </tr>
                    <tr class="summary-petcsd">
                        <td class="label">PET CSD</td>
                        <td><?php echo e(number_format($petT, 0)); ?></td>
                        <td><?php echo e(number_format($petA, 0)); ?></td>
                        <td><?php echo e(number_format($pct($petA, $petT), 0)); ?>%</td>
                        <td><?php echo e(number_format($var($petA, $petT), 0)); ?></td>
                    </tr>
                    <tr>
                        <td class="label total-label">TOTAL</td>
                        <td><?php echo e(number_format($targetTotal, 0)); ?></td>
                        <td><?php echo e(number_format($totalActual, 0)); ?></td>
                        <td><?php echo e(number_format($pct($totalActual, $targetTotal), 0)); ?>%</td>
                        <td><?php echo e(number_format($var($totalActual, $targetTotal), 0)); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="section">PRODUCTS</div>

<?php
    $shipmentColumns = $groupReports->values();
    $headerDateText = $shipmentColumns
        ->map(function ($shipmentReport) use ($displayDate) {
            return $shipmentReport->report_date?->format('n/j/Y')
                ?? $shipmentReport->created_at?->format('n/j/Y')
                ?? $displayDate;
        })
        ->filter()
        ->unique()
        ->values()
        ->implode(' | ');
    if ($headerDateText === '') {
        $headerDateText = $headerCellDate;
    }

    $headerShipmentText = $shipmentColumns
        ->map(fn ($shipmentReport) => trim((string) ($shipmentReport->shipment_no ?? '')))
        ->map(fn ($shipmentNo) => $shipmentNo !== '' ? $shipmentNo : '-')
        ->unique()
        ->values()
        ->implode(' | ');
    if ($headerShipmentText === '') {
        $headerShipmentText = '-';
    }

    $productMap = [];
    $shipmentSrpMaps = [];
    foreach ($shipmentColumns as $shipmentIndex => $shipmentReport) {
        $shipmentSrpMaps[$shipmentIndex] = [];
        if ($shipmentReport->relationLoaded('inventories') ? $shipmentReport->inventories->count() : $shipmentReport->inventories()->count()) {
            $invRows = ($shipmentReport->relationLoaded('inventories') ? $shipmentReport->inventories : $shipmentReport->inventories)
                ->map(function ($r) {
                    return [
                        'pack' => $r->pack,
                        'product' => $r->product,
                        'srp' => $r->srp,
                    ];
                })->toArray();
        } elseif (is_array($shipmentReport->inventory_rows)) {
            $invRows = $shipmentReport->inventory_rows;
        } else {
            $invRows = json_decode($shipmentReport->inventory_json ?? '[]', true) ?: [];
        }

        foreach ($invRows as $invRow) {
            $invRow = (array) $invRow;
            $invPack = trim((string) ($invRow['pack'] ?? $invRow['pack_size'] ?? $invRow['packSize'] ?? ''));
            $invProduct = trim((string) ($invRow['product'] ?? $invRow['product_name'] ?? $invRow['productName'] ?? ''));
            if ($invPack === '' && $invProduct === '') {
                continue;
            }
            $invKey = strtolower($invPack . '||' . $invProduct);
            $shipmentSrpMaps[$shipmentIndex][$invKey] = (float) ($invRow['srp'] ?? 0);
        }

        foreach ($shipmentReport->items as $item) {
            $pack = trim((string) ($item->pack ?? ''));
            $product = trim((string) ($item->product ?? ''));

            if ($pack === '') {
                $pack = '-';
            }
            if ($product === '') {
                $product = '-';
            }

            $rowKey = strtolower($pack . '||' . $product);
            if (!isset($productMap[$rowKey])) {
                $productMap[$rowKey] = [
                    'pack' => $pack,
                    'product' => $product,
                    'category' => strtolower(trim((string) ($item->category ?? ''))),
                    'core_pcs' => 0.0,
                    'ucs' => 0.0,
                    'iws_cases' => 0.0,
                    'core_total' => 0.0,
                    'iws_total' => 0.0,
                    'srp' => 0.0,
                    'total_amount' => 0.0,
                ];
            }

            $corePcs = (float) ($item->core_pcs ?? 0);
            $coreValue = (float) ($item->core_total_ucs ?? 0);
            $iwsValue = (float) ($item->iws_total_ucs ?? 0);
            $iwsCases = (float) ($item->iws_pcs ?? 0);
            $itemSrp = (float) ($shipmentSrpMaps[$shipmentIndex][$rowKey] ?? 0);
            $itemUcs = max((float) ($item->core_ucs ?? 0), (float) ($item->iws_ucs ?? 0));
            if ((float) ($productMap[$rowKey]['srp'] ?? 0) <= 0 && $itemSrp > 0) {
                $productMap[$rowKey]['srp'] = $itemSrp;
            }
            $productMap[$rowKey]['core_pcs'] += $corePcs;
            $productMap[$rowKey]['ucs'] = max((float) ($productMap[$rowKey]['ucs'] ?? 0), $itemUcs);
            $productMap[$rowKey]['core_total'] += $coreValue;
            $productMap[$rowKey]['iws_total'] += $iwsValue;
            $productMap[$rowKey]['iws_cases'] += $iwsCases;
            $productMap[$rowKey]['total_amount'] += ($corePcs + $iwsCases) * $itemSrp;
        }
    }

    $productRows = collect($productMap)->values();
    $groupedByCategory = $productRows->groupBy(function ($row) {
        $cat = strtolower(trim((string) ($row['category'] ?? '')));
        return $cat !== '' ? $cat : 'uncategorized';
    });

    $categoryOrder = ['core', 'petcsd', 'stills'];
    $orderedCategoryKeys = collect($categoryOrder)
        ->filter(fn ($cat) => $groupedByCategory->has($cat))
        ->merge(
            $groupedByCategory->keys()->reject(
                fn ($cat) => in_array((string) $cat, $categoryOrder, true)
            )
        )
        ->values();

    $categoryLabels = [
        'core' => 'CORE',
        'petcsd' => 'PET CSD',
        'stills' => 'STILLS',
        'uncategorized' => 'OTHERS',
    ];

    $overallCorePcs = 0.0;
    $overallCoreTotal = 0.0;
    $overallIwsCases = 0.0;
    $overallIwsTotal = 0.0;
    $overallTotalAmount = 0.0;
    foreach ($productRows as $row) {
        $overallCorePcs += (float) ($row['core_pcs'] ?? 0);
        $overallCoreTotal += (float) ($row['core_total'] ?? 0);
        $overallIwsCases += (float) ($row['iws_cases'] ?? 0);
        $overallIwsTotal += (float) ($row['iws_total'] ?? 0);
        $overallTotalAmount += (float) ($row['total_amount'] ?? 0);
    }
?>

<table class="products-table">
    <thead>
        <tr>
            <th class="matrix-top-label" colspan="2">DATE</th>
            <th class="matrix-top-value" colspan="7"><?php echo e($headerDateText); ?></th>
        </tr>
        <tr>
            <th class="matrix-top-label" colspan="2">SHIPMENT NO.</th>
            <th class="matrix-top-value" colspan="7"><?php echo e($headerShipmentText); ?></th>
        </tr>
        <tr>
            <th colspan="2"></th>
            <th class="core-group" colspan="3">CORE (PRIMARY)</th>
            <th class="iws-group" colspan="2">IWS (SECONDARY)</th>
            <th class="amount-group" colspan="2">AMOUNT</th>
        </tr>
        <tr>
            <th>Pack</th>
            <th>Product</th>
            <th class="summary-col sub-core-pcs">PCS</th>
            <th class="summary-col sub-core-ucs">UCS</th>
            <th class="summary-col sub-core-total">TOTAL UCS</th>
            <th class="summary-col sub-iws-cases">CASES</th>
            <th class="summary-col sub-iws-total">TOTAL UCS</th>
            <th class="summary-col sub-srp">SRP</th>
            <th class="summary-col sub-total-amount">TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $orderedCategoryKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $categoryRows = $groupedByCategory->get($categoryKey, collect());
                $categoryPackGroups = $categoryRows->groupBy('pack');
                $categoryLabel = $categoryLabels[$categoryKey] ?? strtoupper(str_replace('_', ' ', (string) $categoryKey));
            ?>
            <tr class="category-divider <?php echo e($categoryKey); ?>">
                <td colspan="9"><?php echo e($categoryLabel); ?></td>
            </tr>
            <?php $__currentLoopData = $categoryPackGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack => $packRows): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $packRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowIndex => $productRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $rowCorePcs = (float) ($productRow['core_pcs'] ?? 0);
                        $rowCore = (float) ($productRow['core_total'] ?? 0);
                        $rowIws = (float) ($productRow['iws_total'] ?? 0);
                        $rowUcs = (float) ($productRow['ucs'] ?? 0);
                        $rowIwsCases = (float) ($productRow['iws_cases'] ?? 0);
                        $rowSrp = (float) ($productRow['srp'] ?? 0);
                        $rowTotalAmount = (float) ($productRow['total_amount'] ?? 0);
                    ?>
                    <tr>
                        <?php if($rowIndex === 0): ?>
                            <td class="matrix-pack" rowspan="<?php echo e($packRows->count()); ?>"><?php echo e($pack); ?></td>
                        <?php endif; ?>
                        <td class="matrix-product <?php echo e($productRow['category'] ?? ''); ?>"><?php echo e($productRow['product']); ?></td>
                        <td class="cell-core-pcs"><?php echo e(abs($rowCorePcs) > 0.000001 ? number_format($rowCorePcs, 0) : '0'); ?></td>
                        <td class="cell-core-ucs"><?php echo e(abs($rowUcs) > 0.000001 ? number_format($rowUcs, 6) : ''); ?></td>
                        <td class="cell-core-total"><?php echo e(abs($rowCore) > 0.000001 ? number_format($rowCore, 2) : '0.00'); ?></td>
                        <td class="cell-iws-cases"><?php echo e(abs($rowIwsCases) > 0.000001 ? number_format($rowIwsCases, 0) : '0'); ?></td>
                        <td class="cell-iws-total"><?php echo e(abs($rowIws) > 0.000001 ? number_format($rowIws, 2) : '0.00'); ?></td>
                        <td class="cell-srp"><?php echo e(abs($rowSrp) > 0.000001 ? number_format($rowSrp, 2) : '0.00'); ?></td>
                        <td class="cell-total-amount"><?php echo e(abs($rowTotalAmount) > 0.000001 ? number_format($rowTotalAmount, 2) : '0.00'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="9">No product rows found.</td>
            </tr>
        <?php endif; ?>
        <?php if($productRows->isNotEmpty()): ?>
            <tr class="overall-total">
                <td colspan="2" class="label-cell">OVERALL TOTAL</td>
                <td class="cell-core-pcs"><?php echo e(number_format($overallCorePcs, 0)); ?></td>
                <td class="cell-core-ucs">-</td>
                <td class="cell-core-total"><?php echo e(number_format($overallCoreTotal, 2)); ?></td>
                <td class="cell-iws-cases"><?php echo e(number_format($overallIwsCases, 0)); ?></td>
                <td class="cell-iws-total"><?php echo e(number_format($overallIwsTotal, 2)); ?></td>
                <td class="cell-srp">-</td>
                <td class="cell-total-amount"><?php echo e(number_format($overallTotalAmount, 2)); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
    $perSkuRows = $groupReports->flatMap(function ($shipmentReport) {
        $rows = is_array($shipmentReport->coke_rows) ? $shipmentReport->coke_rows : [];
        return collect($rows)->map(function ($row) {
            return (array) $row;
        });
    })->values();
?>
<?php if($perSkuRows->count()): ?>
<div class="section">PER SKU (REFERENCE)</div>
<table class="inv-table">
    <thead>
        <tr>
            <th rowspan="2">Product</th>
            <th colspan="2">Target</th>
            <th colspan="2">Actual</th>
            <th colspan="2">Variance</th>
        </tr>
        <tr>
            <th>In PCS</th>
            <th>In UCS</th>
            <th>In PCS</th>
            <th>In UCS</th>
            <th>In PCS</th>
            <th>In UCS</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $perSkuRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $targetPcs = (float) ($row['target_pcs'] ?? 0);
                $targetUcs = (float) ($row['target_ucs'] ?? 0);
                $actualPcs = (float) ($row['actual_pcs'] ?? 0);
                $actualUcs = (float) ($row['actual_ucs'] ?? 0);
                $varPcs = $targetPcs - $actualPcs;
                $varUcs = $targetUcs - $actualUcs;
            ?>
            <tr>
                <td><?php echo e(($row['product'] ?? '')); ?> <?php echo e(($row['pack'] ?? '')); ?></td>
                <td><?php echo e(number_format($targetPcs, 0)); ?></td>
                <td><?php echo e(number_format($targetUcs, 6)); ?></td>
                <td><?php echo e(number_format($actualPcs, 0)); ?></td>
                <td><?php echo e(number_format($actualUcs, 6)); ?></td>
                <td><?php echo e(number_format($varPcs, 0)); ?></td>
                <td><?php echo e(number_format($varUcs, 6)); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php endif; ?>

<?php $__currentLoopData = $groupReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipmentReport): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $customTables = is_array($shipmentReport->custom_tables)
            ? $shipmentReport->custom_tables
            : json_decode($shipmentReport->custom_tables_json ?? '[]', true);
    ?>

    <?php $__currentLoopData = $customTables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="section"><?php echo e($tbl['title']); ?> (Shipment <?php echo e($shipmentReport->shipment_no ?: '-'); ?>)</div>
    <table>
    <thead>
    <tr>
    <?php $__currentLoopData = $tbl['headers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <th><?php echo e($h); ?></th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $tbl['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td><?php echo e($cell); ?></td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div style="page-break-after:always;"></div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views/admin/reports/exports/period-report-full-pdf.blade.php ENDPATH**/ ?>