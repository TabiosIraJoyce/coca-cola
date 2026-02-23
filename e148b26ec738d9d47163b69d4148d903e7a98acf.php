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
        Edit Period <?php echo e($report->period_no); ?> - <?php echo e($report->branch); ?>

    </h2>
 <?php $__env->endSlot(); ?>

<form id="periodEditForm" method="POST" action="<?php echo e(route('admin.reports.periods.update', $report->id)); ?>">
<?php echo csrf_field(); ?>
<?php echo method_field('PUT'); ?>

<div class="p-6 space-y-6">


<div class="bg-white p-4 rounded shadow grid grid-cols-4 gap-4">
    <div>
        <p class="text-sm text-gray-500">Target Sales</p>
        <input type="text"
               id="target_sales"
               readonly
               value="&#8369;<?php echo e(number_format($report->target_sales,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">
    </div>
    <div>
        <p class="text-sm text-gray-500">Core Target Sales</p>
        <input type="text"
               readonly
               value="&#8369;<?php echo e(number_format($report->core_target_sales ?? 0,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">
    </div>
    <div>
        <p class="text-sm text-gray-500">PET CSD Target Sales</p>
        <input type="text"
               readonly
               value="&#8369;<?php echo e(number_format($report->petcsd_target_sales ?? 0,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">
    </div>
    <div>
        <p class="text-sm text-gray-500">Stills Target Sales</p>
        <input type="text"
               readonly
               value="&#8369;<?php echo e(number_format($report->stills_target_sales ?? 0,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">
    </div>

    <div>
        <p class="text-sm text-gray-500">Actual Sales</p>
        <input type="text"
               id="actual_sales"
               readonly
               value="&#8369;<?php echo e(number_format($report->actual_sales,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">
    </div>

    <div>
        <p class="text-sm text-gray-500">Variance</p>
        <input type="text"
               id="variance_display"
               readonly
               value="&#8369;<?php echo e(number_format($report->total_variance,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">

        <input type="hidden"
               id="total_variance"
               name="total_variance"
               value="<?php echo e($report->total_variance); ?>">
    </div>

    <div>
        <p class="text-sm text-gray-500">Achievement (%)</p>
        <input type="text"
               id="achievement_display"
               readonly
               value="<?php echo e(number_format($report->achievement_pct,2)); ?>"
               class="w-full bg-gray-100 border rounded px-2 py-1 text-right">

        <input type="hidden"
               id="achievement_pct"
               name="achievement_pct"
               value="<?php echo e($report->achievement_pct); ?>">
    </div>
</div>


<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold text-lg mb-3 text-red-700">
        Coca-Cola Sales Performance Report
    </h3>

    <table class="w-full border text-xs">
        <thead class="bg-red-700 text-white">
        <tr>
            <th class="border p-2">Pack</th>
            <th class="border p-2">Product</th>
            <th class="border p-2">Core PCS</th>
            <th class="border p-2">Core UCS</th>
            <th class="border p-2">Core Total</th>
            <th class="border p-2">IWS PCS</th>
            <th class="border p-2">IWS UCS</th>
            <th class="border p-2">IWS Total</th>
        </tr>
        </thead>

        <tbody id="coreIwsBody">
        <?php $__currentLoopData = $report->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $coreTotal = ($item->core_pcs ?? 0) * ($item->core_ucs ?? 0);
                $iwsTotal  = ($item->iws_pcs ?? 0) * ($item->iws_ucs ?? 0);
            ?>
            <tr>
                <td class="border p-2">
                    <?php echo e($item->pack); ?>

                    <input type="hidden" name="items[<?php echo e($i); ?>][pack]" value="<?php echo e($item->pack); ?>">
                </td>

                <td class="border p-2">
                    <?php echo e($item->product); ?>

                    <input type="hidden" name="items[<?php echo e($i); ?>][product]" value="<?php echo e($item->product); ?>">
                </td>

                <td class="border p-2">
                    <input type="number"
                           name="items[<?php echo e($i); ?>][core_pcs]"
                           value="<?php echo e($item->core_pcs); ?>"
                           class="w-full border px-1 text-right core-pcs">
                </td>

                <td class="border p-2">
                    <input type="number"
                           name="items[<?php echo e($i); ?>][core_ucs]"
                           value="<?php echo e($item->core_ucs); ?>"
                           class="w-full border px-1 text-right core-ucs">
                </td>

                <td class="border p-2 text-right bg-red-50 core-total">
                    <?php echo e(number_format($coreTotal,2)); ?>

                </td>

                <td class="border p-2">
                    <input type="number"
                           name="items[<?php echo e($i); ?>][iws_pcs]"
                           value="<?php echo e($item->iws_pcs); ?>"
                           class="w-full border px-1 text-right iws-pcs">
                </td>

                <td class="border p-2">
                    <input type="number"
                           name="items[<?php echo e($i); ?>][iws_ucs]"
                           value="<?php echo e($item->iws_ucs); ?>"
                           class="w-full border px-1 text-right iws-ucs">
                </td>

                <td class="border p-2 text-right bg-blue-50 iws-total">
                    <?php echo e(number_format($iwsTotal,2)); ?>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<?php
    $invMap = $report->inventories
        ? $report->inventories->mapWithKeys(function ($inv) {
            $key = (string) ($inv->pack ?? '') . '|' . (string) ($inv->product ?? '');
            return [$key => $inv];
        })
        : collect();
?>


<div class="bg-white shadow rounded p-4">
    <h3 class="font-semibold text-lg mb-3">Inventory &amp; Days Level</h3>

    <p class="text-xs text-gray-500 mb-3">
        Tip: Fill in SRP, Actual, ADS, Booking, Deliveries, and the routing days. These values will show in the View page.
    </p>

    <div class="overflow-auto">
        <table class="min-w-full border text-xs">
            <thead class="bg-gray-900 text-white">
            <tr>
                <th class="border p-2">Pack</th>
                <th class="border p-2">Product</th>
                <th class="border p-2">SRP</th>
                <th class="border p-2">Actual</th>
                <th class="border p-2">ADS</th>
                <th class="border p-2">Booking</th>
                <th class="border p-2">Deliveries</th>
                <th class="border p-2">Routing P5 Days</th>
                <th class="border p-2">Routing 7 Days</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $report->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $k = $item->pack . '|' . $item->product;
                    $inv = $invMap->get($k);
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 whitespace-nowrap">
                        <?php echo e($item->pack); ?>

                        <input type="hidden" name="inventories[<?php echo e($i); ?>][pack]" value="<?php echo e($item->pack); ?>">
                    </td>
                    <td class="border p-2 whitespace-nowrap">
                        <?php echo e($item->product); ?>

                        <input type="hidden" name="inventories[<?php echo e($i); ?>][product]" value="<?php echo e($item->product); ?>">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][srp]"
                               value="<?php echo e($inv->srp ?? 0); ?>"
                               class="w-28 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][actual_inv]"
                               value="<?php echo e($inv->actual_inv ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][ads]"
                               value="<?php echo e($inv->ads ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][booking]"
                               value="<?php echo e($inv->booking ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][deliveries]"
                               value="<?php echo e($inv->deliveries ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][routing_days_p5]"
                               value="<?php echo e($inv->routing_days_p5 ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                    <td class="border p-2">
                        <input type="number" step="0.01" min="0"
                               name="inventories[<?php echo e($i); ?>][routing_days_7]"
                               value="<?php echo e($inv->routing_days_7 ?? 0); ?>"
                               class="w-24 border rounded px-2 py-1 text-right">
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="border p-3 text-center text-gray-500">No sales items found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
    $customTables = is_array($report->custom_tables) ? $report->custom_tables : [];
?>


<div class="bg-white shadow rounded p-4 space-y-3">
    <div class="flex items-center justify-between">
        <h3 class="font-semibold text-lg">Additional Custom Tables</h3>
        <button type="button"
                onclick="addCustomTable()"
                class="px-3 py-1.5 text-xs rounded bg-gray-100 border">
            + Add Custom Table
        </button>
    </div>

    <input type="hidden" name="custom_tables" id="custom_tables_json" value="">
    <div id="customTablesContainer" class="space-y-4"></div>
</div>

<div class="flex justify-end gap-3">
    <a href="<?php echo e(route('admin.reports.periods.show',$report->id)); ?>"
       class="border px-4 py-2 rounded">Cancel</a>

    <button type="submit"
            class="bg-red-600 text-white px-6 py-2 rounded">
        Save Changes
    </button>
</div>

</div>
</form>


<script>
function cleanNumber(val) {
    if (!val) return 0;
    return parseFloat(val.toString().replace(/[^0-9.-]/g, '')) || 0;
}

function formatPeso(num) {
    return '\u20B1' + (Number(num) || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Additional Custom Tables are stored as JSON in `period_reports.custom_tables`.
const SAVED_CUSTOM_TABLES = <?php echo json_encode($customTables ?? [], 15, 512) ?>;
let customTableIndex = 0;

function updateCustomTableFooter(table) {
    if (!table) return;
    const headerRow = table.tHead?.rows?.[0];
    const colCount = headerRow ? headerRow.cells.length : 0;
    if (!colCount) return;

    let tfoot = table.tFoot;
    if (!tfoot) tfoot = table.createTFoot();

    let row = tfoot.rows[0];
    if (!row) {
        row = tfoot.insertRow();
        row.className = 'bg-black text-white text-[11px]';
    }

    while (row.cells.length > colCount) row.deleteCell(-1);
    while (row.cells.length < colCount) {
        const cell = row.insertCell();
        cell.className = 'border px-2 py-1 text-right font-semibold';
    }

    const tbody = table.tBodies[0];
    for (let col = 0; col < colCount; col++) {
        let sum = 0;
        Array.from(tbody.rows).forEach(r => {
            const input = r.cells[col]?.querySelector('input');
            if (input) sum += cleanNumber(input.value);
        });

        if (col === 0) {
            row.cells[col].innerText = 'TOTAL';
            row.cells[col].className = 'border px-2 py-1 text-left font-semibold';
        } else {
            row.cells[col].innerText = formatPeso(sum);
            row.cells[col].className = 'border px-2 py-1 text-right font-semibold';
        }
    }
}

function addCustomTableFromData(tableData) {
    const container = document.getElementById('customTablesContainer');
    if (!container) return;

    const idx = customTableIndex++;

    const card = document.createElement('div');
    card.className = "custom-table-wrapper border rounded-lg p-3 shadow-sm space-y-2";
    card.dataset.index = String(idx);

    const title = tableData?.title || '';

    card.innerHTML = `
        <div class="flex justify-between items-center gap-2">
            <input type="text"
                   class="custom-title border-b border-gray-300 text-sm font-semibold flex-1 focus:ring-0"
                   placeholder="Table title (e.g. NSR Incentive)"
                   value="${title}">
            <button type="button"
                    class="text-xs text-red-600 hover:underline"
                    onclick="this.closest('.custom-table-wrapper')?.remove()">
                Remove Table
            </button>
        </div>

        <div class="flex gap-2 mb-2">
            <button type="button" class="px-2 py-1 bg-blue-600 text-white text-xs rounded"
                    onclick="addCustomColumn(${idx})">+ Col</button>
            <button type="button" class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded"
                    onclick="removeCustomColumn(${idx})">- Col</button>
            <button type="button" class="px-2 py-1 bg-green-600 text-white text-xs rounded"
                    onclick="addCustomRow(${idx})">+ Row</button>
            <button type="button" class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded"
                    onclick="removeCustomRow(${idx})">- Row</button>
        </div>

        <div class="overflow-auto">
            <table class="min-w-full text-xs border custom-table" data-table-index="${idx}">
                <thead><tr></tr></thead>
                <tbody></tbody>
                <tfoot></tfoot>
            </table>
        </div>
    `;

    container.appendChild(card);

    const table = card.querySelector('table.custom-table');
    const headerRow = table.tHead.rows[0];
    const headers = tableData?.headers || ['Header 1'];

    headers.forEach(h => {
        const th = document.createElement('th');
        th.className = 'border px-2 py-1 bg-gray-100';
        th.innerHTML = `<input type="text" class="w-full border-none text-xs bg-transparent focus:ring-0" value="${h}" placeholder="Header">`;
        headerRow.appendChild(th);
    });

    const rows = tableData?.rows || [['']];
    rows.forEach(rowVals => {
        const tr = document.createElement('tr');
        rowVals.forEach(v => {
            const td = document.createElement('td');
            td.className = 'border px-2 py-1';
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'w-full border-none text-xs focus:ring-0';
            input.value = v ?? '';
            input.addEventListener('input', () => updateCustomTableFooter(table));
            td.appendChild(input);
            tr.appendChild(td);
        });
        table.tBodies[0].appendChild(tr);
    });

    updateCustomTableFooter(table);
}

// Button handlers (used by inline onclick).
window.addCustomTable = function () { addCustomTableFromData(null); };
window.addCustomColumn = function (idx) {
    const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
    if (!table) return;
    const headerRow = table.tHead.rows[0];

    const th = document.createElement('th');
    th.className = 'border px-2 py-1 bg-gray-100';
    th.innerHTML = '<input type="text" class="w-full border-none text-xs bg-transparent focus:ring-0" placeholder="Header">';
    headerRow.appendChild(th);

    Array.from(table.tBodies[0].rows).forEach(tr => {
        const td = document.createElement('td');
        td.className = 'border px-2 py-1';
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'w-full border-none text-xs focus:ring-0';
        input.addEventListener('input', () => updateCustomTableFooter(table));
        td.appendChild(input);
        tr.appendChild(td);
    });

    updateCustomTableFooter(table);
};
window.removeCustomColumn = function (idx) {
    const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
    if (!table) return;
    const headerRow = table.tHead.rows[0];
    if (headerRow.cells.length <= 1) return;

    headerRow.deleteCell(headerRow.cells.length - 1);
    Array.from(table.tBodies[0].rows).forEach(tr => tr.deleteCell(tr.cells.length - 1));
    updateCustomTableFooter(table);
};
window.addCustomRow = function (idx) {
    const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
    if (!table) return;
    const body = table.tBodies[0];
    const colCount = table.tHead.rows[0].cells.length;

    const tr = document.createElement('tr');
    for (let i = 0; i < colCount; i++) {
        const td = document.createElement('td');
        td.className = 'border px-2 py-1';
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'w-full border-none text-xs focus:ring-0';
        input.addEventListener('input', () => updateCustomTableFooter(table));
        td.appendChild(input);
        tr.appendChild(td);
    }
    body.appendChild(tr);
    updateCustomTableFooter(table);
};
window.removeCustomRow = function (idx) {
    const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
    if (!table) return;
    const body = table.tBodies[0];
    if (body.rows.length <= 1) return;
    body.deleteRow(body.rows.length - 1);
    updateCustomTableFooter(table);
};

function recalcRow(tr) {
    const n = v => parseFloat(v) || 0;

    const corePcs = n(tr.querySelector('.core-pcs')?.value);
    const coreUcs = n(tr.querySelector('.core-ucs')?.value);
    const iwsPcs  = n(tr.querySelector('.iws-pcs')?.value);
    const iwsUcs  = n(tr.querySelector('.iws-ucs')?.value);

    const coreTotal = corePcs * coreUcs;
    const iwsTotal  = iwsPcs * iwsUcs;

    tr.querySelector('.core-total').innerText = coreTotal.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    tr.querySelector('.iws-total').innerText = iwsTotal.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    recalcTotals();
}

function recalcTotals() {
    let actual = 0;

    document.querySelectorAll('.core-total, .iws-total').forEach(el => {
        actual += parseFloat(el.innerText.replace(/,/g, '')) || 0;
    });

    const target = cleanNumber(document.getElementById('target_sales')?.value);
    const variance = target - actual;
    const achievement = target > 0 ? (actual / target) * 100 : 0;

    document.getElementById('actual_sales').value = formatPeso(actual);
    document.getElementById('variance_display').value = formatPeso(variance);

    document.getElementById('achievement_display').value =
        achievement.toFixed(2);

    document.getElementById('total_variance').value = variance.toFixed(2);
    document.getElementById('achievement_pct').value = achievement.toFixed(2);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#coreIwsBody tr').forEach(tr => {
        tr.querySelectorAll('.core-pcs, .core-ucs, .iws-pcs, .iws-ucs')
            .forEach(inp => {
                ['input', 'change'].forEach(evt => {
                    inp.addEventListener(evt, () => recalcRow(tr));
                });
            });
    });

    recalcTotals();

    // Render saved custom tables for edit mode.
    if (Array.isArray(SAVED_CUSTOM_TABLES) && SAVED_CUSTOM_TABLES.length) {
        SAVED_CUSTOM_TABLES.forEach(t => addCustomTableFromData(t));
    }

    // Ensure we don't accidentally wipe saved custom tables if JS fails later.
    const jsonEl = document.getElementById('custom_tables_json');
    if (jsonEl && !jsonEl.value) {
        jsonEl.value = JSON.stringify(Array.isArray(SAVED_CUSTOM_TABLES) ? SAVED_CUSTOM_TABLES : []);
    }
});

// Serialize custom tables to hidden input on submit.
document.getElementById('periodEditForm')?.addEventListener('submit', () => {
    const tables = [];
    const wrappers = Array.from(document.querySelectorAll('#customTablesContainer .custom-table-wrapper'));

    wrappers.forEach(wrapper => {
        const title = wrapper.querySelector('.custom-title')?.value ?? '';
        const table = wrapper.querySelector('table.custom-table');
        if (!table) return;

        const headers = Array.from(table.tHead?.rows?.[0]?.cells ?? []).map(th => th.querySelector('input')?.value ?? '');
        const bodyRows = Array.from(table.tBodies?.[0]?.rows ?? []).map(tr => Array.from(tr.cells).map(td => td.querySelector('input')?.value ?? ''));

        const hasAny =
            String(title).trim() !== '' ||
            headers.some(h => String(h ?? '').trim() !== '') ||
            bodyRows.some(r => r.some(c => String(c ?? '').trim() !== ''));

        if (!hasAny) return;
        tables.push({ title, headers, rows: bodyRows });
    });

    const jsonEl = document.getElementById('custom_tables_json');
    if (jsonEl) jsonEl.value = JSON.stringify(tables);
});
</script>


 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\periods\edit.blade.php ENDPATH**/ ?>