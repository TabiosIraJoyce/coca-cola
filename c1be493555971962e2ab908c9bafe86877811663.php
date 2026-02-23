
<script>
    const IS_VIEW = <?php echo e(isset($isView) && $isView ? 'true' : 'false'); ?>;

    const SAVED_ITEMS = <?php echo json_encode(
        isset($items)
            ? collect($items)->mapWithKeys(fn($i) => [
                $i->pack.'|'.$i->product => [
                    'core_pcs' => (float) $i->core_pcs,
                    'core_ucs' => (float) $i->core_ucs,
                    'iws_pcs'  => (float) $i->iws_pcs,
                    'iws_ucs'  => (float) $i->iws_ucs,
                ]
            ])
            : []
    ); ?>;

    const SAVED_INVENTORY = <?php echo json_encode($inventoryRows ?? []); ?>;
    const SAVED_CUSTOM_TABLES = <?php echo json_encode($customTables ?? []); ?>;
</script>

<script>
/* ===============================
   HELPERS
================================ */
function cleanNumber(val) {
    if (!val) return 0;
    return parseFloat(val.toString().replace(/[^0-9.-]/g, '')) || 0;
}

function formatPeso(num) {
    return '₱' + (Number(num) || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/* ===============================
   DEFAULT PRODUCT LIST
================================ */
const DEFAULT_ROWS = <?php echo json_encode($defaultRows ?? [], 15, 512) ?>;

/* ===============================
   CORE + IWS TABLE
================================ */
const coreBody = document.getElementById('coreIwsBody');

function createCoreRow(row, index) {
    const key = row.pack + '|' + row.product;
    const saved = SAVED_ITEMS[key] || {};

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-2">${row.pack}
            <input type="hidden" name="items[${index}][pack]" value="${row.pack}">
        </td>
        <td class="border px-2">${row.product}
            <input type="hidden" name="items[${index}][product]" value="${row.product}">
        </td>
        <td class="border px-1"><input type="number" name="items[${index}][core_pcs]" value="${saved.core_pcs ?? 0}" class="core-pcs w-full"></td>
        <td class="border px-1"><input type="number" step="0.0001" name="items[${index}][core_ucs]" value="${saved.core_ucs ?? 0}" class="core-ucs w-full"></td>
        <td class="border px-1 core-total text-right">0.00</td>
        <td class="border px-1"><input type="number" name="items[${index}][iws_pcs]" value="${saved.iws_pcs ?? 0}" class="iws-pcs w-full"></td>
        <td class="border px-1"><input type="number" step="0.0001" name="items[${index}][iws_ucs]" value="${saved.iws_ucs ?? 0}" class="iws-ucs w-full"></td>
        <td class="border px-1 iws-total text-right">0.00</td>
    `;

    tr.querySelectorAll('input').forEach(inp => {
        if (IS_VIEW) inp.readOnly = true;
        inp.addEventListener('input', () => recalcCoreRow(tr));
    });

    recalcCoreRow(tr);
    return tr;
}

function recalcCoreRow(tr) {
    const coreTotal = cleanNumber(tr.querySelector('.core-pcs').value) *
                      cleanNumber(tr.querySelector('.core-ucs').value);
    const iwsTotal  = cleanNumber(tr.querySelector('.iws-pcs').value) *
                      cleanNumber(tr.querySelector('.iws-ucs').value);

    tr.querySelector('.core-total').innerText = coreTotal.toFixed(2);
    tr.querySelector('.iws-total').innerText  = iwsTotal.toFixed(2);

    recalcCoreTotals();
}

function recalcCoreTotals() {
    let total = 0;
    coreBody.querySelectorAll('tr').forEach(tr => {
        total += cleanNumber(tr.querySelector('.core-total').innerText);
        total += cleanNumber(tr.querySelector('.iws-total').innerText);
    });

    const actual = document.getElementById('actual_sales');
    if (actual) actual.value = total.toFixed(2);

    recalcHeader();
}

/* ===============================
   INVENTORY TABLE
================================ */
const invBody = document.getElementById('inventoryBody');

function createInventoryRow(row, index) {
    const saved = SAVED_INVENTORY.find(r => r.pack === row.pack && r.product === row.product) || {};

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="border px-2">${row.pack}</td>
        <td class="border px-2">${row.product}</td>
        <td class="border px-1"><input type="number" value="${saved.srp ?? 0}" class="srp w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.actual_inv ?? 0}" class="actual w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.ads ?? 0}" class="ads w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.booking ?? 0}" class="booking w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.deliveries ?? 0}" class="deliveries w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.routing_days_p5 ?? 0}" class="p5 w-full"></td>
        <td class="border px-1"><input type="number" value="${saved.routing_days_7 ?? 0}" class="d7 w-full"></td>
    `;

    tr.querySelectorAll('input').forEach(inp => {
        if (IS_VIEW) inp.readOnly = true;
    });

    return tr;
}

/* ===============================
   HEADER COMPUTATION
================================ */
function recalcHeader() {
    const target = cleanNumber(document.getElementById('target_sales')?.value);
    const actual = cleanNumber(document.getElementById('actual_sales')?.value);

    const achievement = target > 0 ? (actual / target) * 100 : 0;
    // Variance = remaining target (Target - Actual)
    const variance = target - actual;

    document.getElementById('achievement_display').innerText = achievement.toFixed(2) + '%';
    document.getElementById('variance_display').innerText = formatPeso(variance);
    document.getElementById('achievement_pct').value = achievement.toFixed(2);
    document.getElementById('total_variance').value = variance.toFixed(2);
}

/* ===============================
   CUSTOM TABLES
================================ */
let customIndex = 0;

function addCustomTableFromData(tbl) {
    const container = document.getElementById('customTablesContainer');
    const idx = customIndex++;

    const card = document.createElement('div');
    card.className = 'border rounded p-3';

    card.innerHTML = `
        <h4 class="font-semibold mb-2">${tbl.title ?? 'Custom Table'}</h4>
        <table class="w-full border text-sm">
            <thead><tr>${(tbl.headers || []).map(h => `<th class="border p-2">${h}</th>`).join('')}</tr></thead>
            <tbody>
                ${(tbl.rows || []).map(r =>
                    `<tr>${r.map(c => `<td class="border p-2">${c}</td>`).join('')}</tr>`
                ).join('')}
            </tbody>
        </table>
    `;

    container.appendChild(card);
}

/* ===============================
   BOOTSTRAP
================================ */
document.addEventListener('DOMContentLoaded', () => {
    if (coreBody) {
        DEFAULT_ROWS.forEach((r, i) => coreBody.appendChild(createCoreRow(r, i)));
    }

    if (invBody) {
        DEFAULT_ROWS.forEach((r, i) => invBody.appendChild(createInventoryRow(r, i)));
    }

    SAVED_CUSTOM_TABLES.forEach(t => addCustomTableFromData(t));
    recalcHeader();
});
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\periods\_scripts.blade.php ENDPATH**/ ?>