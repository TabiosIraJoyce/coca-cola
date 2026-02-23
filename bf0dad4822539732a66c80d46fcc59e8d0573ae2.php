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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
            <i data-lucide="file-plus" class="w-6 h-6"></i>
            Add Report — <?php echo e($division->division_name); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <script src="https://unpkg.com/lucide@0.257.0/dist/lucide.min.js" defer></script>

    
    <script src="//unpkg.com/alpinejs" defer></script>

    <div class="py-6">
        <div class="max-w-full mx-auto px-6">
            <div class="bg-white shadow p-8 rounded-xl">

                
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm text-black-600">

                        Report Type:

                        <span class="font-bold text-gray-900 capitalize"><?php echo e($reportType); ?></span>
                    </div>
                </div>

               
<form action="<?php echo e(route('admin.reports.store', $reportType)); ?>" method="POST" class="space-y-6">
<?php echo csrf_field(); ?>
    <div x-data>
        <input type="hidden" name="division_id" value="<?php echo e($division->id); ?>">
        <input type="hidden" name="report_type" value="<?php echo e($reportType); ?>">

            
        <div>
        <label class="block text-sm font-bold text-gray-700">Report Date</label>
        <input type="date"
            name="report_date"
            value="<?php echo e(now()->format('Y-m-d')); ?>"
            required
            class="mt-1 block rounded border-gray-300">
    </div>


<div class="receipt-landscape"
    x-cloak
     x-data="{
        cols: {
            route: true,
            cases: true,
            sales: true,
            collections: true,
            remittance: true
        }
     }"
     x-show="'<?php echo e($reportType); ?>' === 'receipts'">

    <h3 class="font-semibold mb-3">Receipts Details</h3>

    
    <div class="mb-3 p-3 border rounded bg-gray-50 text-sm">
        <p class="font-semibold mb-2">Show / Hide Columns</p>

        <div class="flex flex-wrap gap-4">
            <label><input type="checkbox" x-model="cols.route"> Route / Leadman</label>
            <label><input type="checkbox" x-model="cols.cases"> Cases</label>
            <label><input type="checkbox" x-model="cols.sales"> Sales</label>
            <label><input type="checkbox" x-model="cols.collections"> Collections</label>
            <label><input type="checkbox" x-model="cols.remittance"> Remittance</label>
        </div>
    </div>

    <div class="w-full overflow-x-auto">
        <table id="receiptsTable" class="border text-[12px]">

            
            <thead class="bg-gray-100 text-center sticky top-0 z-10">
                <tr class="font-semibold">

                    <th x-show="cols.route">Route</th>
                    <th x-show="cols.route">Leadman</th>

                    <th x-show="cols.cases">Full Case</th>
                    <th x-show="cols.cases">Half Case</th>
                    <th x-show="cols.cases">Box</th>
                    <th x-show="cols.cases">Total Cases</th>
                    <th x-show="cols.cases">Total UCS</th>
                    <th x-show="cols.cases">Receipts</th>
                    <th x-show="cols.cases">Customers</th>

                    <th x-show="cols.sales">Gross Sales</th>
                    <th x-show="cols.sales">Sales Discount</th>
                    <th x-show="cols.sales">Coupon Discount</th>
                    <th x-show="cols.sales">Net Sales</th>

                    <th x-show="cols.collections">Containers Deposit</th>
                    <th x-show="cols.collections">Purchased Refund</th>
                    <th x-show="cols.collections">Stock Transfer</th>
                    <th x-show="cols.collections">Net Credit Sales</th>
                    <th x-show="cols.collections">Shortage Collections</th>
                    <th x-show="cols.collections">AR Collections</th>
                    <th x-show="cols.collections">Other Income</th>

                    <th x-show="cols.remittance">Cash Proceeds</th>
                    <th x-show="cols.remittance">Cash</th>
                    <th x-show="cols.remittance">Check</th>
                    <th x-show="cols.remittance">Total Remittance</th>
                    <th x-show="cols.remittance">Short / Over</th>
                    <th x-show="cols.remittance">±</th>

                    <th></th>
                </tr>
            </thead>

            
            <tbody>
                <tr>

                    <td x-show="cols.route">
                        <select name="route[]" class="input-wide" required>
                            <option value="" selected>Select route</option>
                            <?php $__currentLoopData = ($routesList ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($rt); ?>"><?php echo e($rt); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </td>
                    <td x-show="cols.route"><input name="leadman[]" class="input-wide" required></td>

                    <td x-show="cols.cases"><input name="full_case[]" type="number"></td>
                    <td x-show="cols.cases"><input name="half_case[]" type="number"></td>
                    <td x-show="cols.cases"><input name="box[]" type="number"></td>
                    <td x-show="cols.cases"><input name="total_cases[]" readonly></td>
                    <td x-show="cols.cases"><input name="total_ucs[]" type="number"></td>
                    <td x-show="cols.cases"><input name="no_of_receipts[]" type="number"></td>
                    <td x-show="cols.cases"><input name="customer_count[]" type="number"></td>

                    <td x-show="cols.sales"><input name="gross_sales[]" class="peso"></td>
                    <td x-show="cols.sales"><input name="sales_discount[]" class="peso"></td>
                    <td x-show="cols.sales"><input name="coupon_discount[]" class="peso"></td>
                    <td x-show="cols.sales"><input name="net_sales[]" readonly class="peso"></td>

                    <td x-show="cols.collections"><input name="containers_deposit[]" type="number"></td>
                    <td x-show="cols.collections"><input name="purchased_refund[]" type="number"></td>
                    <td x-show="cols.collections"><input name="stock_transfer[]" type="number"></td>
                    <td x-show="cols.collections"><input name="net_credit_sales[]" type="number"></td>
                    <td x-show="cols.collections"><input name="shortage_collections[]" type="number"></td>
                    <td x-show="cols.collections"><input name="ar_collections[]" type="number"></td>
                    <td x-show="cols.collections"><input name="other_income[]" type="number"></td>

                    <td x-show="cols.remittance"><input name="cash_proceeds[]" readonly></td>
                    <td x-show="cols.remittance"><input name="cash[]" type="number"></td>
                    <td x-show="cols.remittance"><input name="check[]" type="number"></td>
                    <td x-show="cols.remittance"><input name="total_remittance[]" readonly></td>
                    <td x-show="cols.remittance"><input name="short_over[]" readonly></td>

                    <td>
                        <button type="button" onclick="removeRow(this)" class="text-red-600">✖</button>
                    </td>

                </tr>
            </tbody>

            
           <tfoot class="bg-gray-100 font-semibold text-right">
            <tr>

                
                <td x-show="cols.route" class="text-center font-bold">TOTAL</td>
                <td x-show="cols.route"></td>

                
                <td x-show="cols.cases"><input readonly name="sum_full_case" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_half_case" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_box" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_total_cases" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_total_ucs" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_no_of_receipts" class="input-readonly"></td>
                <td x-show="cols.cases"><input readonly name="sum_customer_count" class="input-readonly"></td>

                
                <td x-show="cols.sales"><input readonly name="sum_gross_sales" class="peso text-right"></td>
                <td x-show="cols.sales"><input readonly name="sum_sales_discount" class="peso text-right"></td>
                <td x-show="cols.sales"><input readonly name="sum_coupon_discount" class="peso text-right"></td>
                <td x-show="cols.sales"><input readonly name="sum_net_sales" class="peso text-right"></td>

                
                <td x-show="cols.collections"><input readonly name="sum_containers_deposit" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_purchased_refund" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_stock_transfer" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_net_credit_sales" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_shortage_collections" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_ar_collections" class="input-readonly"></td>
                <td x-show="cols.collections"><input readonly name="sum_other_income" class="input-readonly"></td>

                
                <td x-show="cols.remittance"><input readonly name="sum_cash_proceeds" class="input-readonly"></td>
                <td x-show="cols.remittance"><input readonly name="sum_cash" class="input-readonly"></td>
                <td x-show="cols.remittance"><input readonly name="sum_check" class="input-readonly"></td>
                <td x-show="cols.remittance"><input readonly name="sum_total_remittance" class="input-readonly"></td>
                <td x-show="cols.remittance"><input readonly name="sum_short_over" class="input-readonly"></td>

                
                <td></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <button type="button"
            onclick="addRow('receiptsTable')"
            class="mt-2 text-blue-600 text-sm">
        + Add Row
    </button>
</div>

<style>
/* ===============================
   REMOVE NUMBER ARROWS
=============================== */
#receiptsTable input[type="number"]::-webkit-inner-spin-button,
#receiptsTable input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

#receiptsTable input[type="number"] {
    -moz-appearance: textfield;
}

/* ===============================
   PAGE & TABLE LAYOUT
=============================== */
.receipt-landscape {
    width: 100%;
    overflow-x: auto;     /* allow horizontal scroll */
    padding: 12px;
}

/* TABLE — LET IT BE WIDE */
#receiptsTable {
    min-width: 2600px;    /* 🔥 VERY WIDE — MILLIONS SAFE */
    border-collapse: collapse;
    table-layout: auto;  /* 🔥 DO NOT FIX COLUMN SIZE */
}

/* HEADERS */
#receiptsTable th {
    font-size: 12px;
    padding: 6px;
    text-align: center;
    white-space: normal;
    border: 1px solid #ccc;
}

/* CELLS */
#receiptsTable td {
    padding: 6px;
    min-width: 180px;    /* 🔥 LONG CELLS */
    border: 1px solid #ddd;
}

/* ===============================
   INPUTS — PLAIN, LONG, READABLE
=============================== */
#receiptsTable input {
    width: 100%;
    height: 32px;
    padding: 4px;
    font-size: 14px;
    border: 1px solid #999;
    border-radius: 0;    /* ❌ no design */
    background: #fff;
    box-sizing: border-box;
}

/* READONLY (TOTALS) */
#receiptsTable input[readonly] {
    background-color: #f2f2f2;
    font-weight: bold;
}

/* NUMBER ALIGNMENT */
#receiptsTable input[type="number"],
#receiptsTable .peso {
    text-align: right;
    font-variant-numeric: tabular-nums;
}

/* ROUTE & LEADMAN — A BIT WIDER */
#receiptsTable th:nth-child(1),
#receiptsTable td:nth-child(1),
#receiptsTable th:nth-child(2),
#receiptsTable td:nth-child(2) {
    min-width: 220px;
}

/* REMOVE BUTTON */
#receiptsTable button {
    height: 32px;
    font-size: 16px;
}
</style>


      
<div x-data x-show="'<?php echo e($reportType); ?>' === 'remittance'">
    <div class="bg-white shadow-md rounded-lg p-6">

        <h3 class="font-semibold mb-3 flex items-center gap-2">
            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
            Remittance Details
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            
<div>
    <h4 class="font-semibold mb-2 flex items-center justify-between">
        Check Payments
    </h4>

    <table class="w-full text-sm border">
        <thead class="bg-gray-100 text-center">
            <tr>
                <th class="border p-2">#</th>
                <th class="border p-2">Bank</th>
                <th class="border p-2">Account Name</th>
                <th class="border p-2">Account #</th>
                <th class="border p-2">Check Date</th>
                <th class="border p-2">Remarks</th>
                <th class="border p-2">Amount</th>
            </tr>
        </thead>

        <tbody>
        <?php for($i = 1; $i <= 10; $i++): ?>
        <tr data-row="<?php echo e($i); ?>">
            <td class="border p-1 text-center"><?php echo e($i); ?></td>

            
            <td class="border p-1">
                <?php $bankListId = "banksList_$i"; ?>

                <input
                    list="<?php echo e($bankListId); ?>"
                    name="checks[<?php echo e($i); ?>][bank_branch]"
                    class="bank-input w-full border rounded px-1 py-0.5"
                    placeholder="Type bank or branch"
                    autocomplete="off"
                >

                <datalist id="<?php echo e($bankListId); ?>">
                    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($account->bank_name); ?> - <?php echo e($account->branch_name); ?>">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </datalist>
            </td>

            
            <td class="border p-1">
                <select
                    class="account-name-select w-full border rounded px-1 py-0.5"
                    name="checks[<?php echo e($i); ?>][account_holder_name]"
                >
                    <option value="">Select Account Name</option>
                </select>
            </td>

            
            <td class="border p-1">
                <select
                    class="account-number-select w-full border rounded px-1 py-0.5"
                    name="checks[<?php echo e($i); ?>][account_number]"
                    disabled
                >
                    <option value="">Select Account #</option>
                </select>
            </td>

            <td class="border p-1">
                <input type="date"
                       name="checks[<?php echo e($i); ?>][check_date]"
                       class="w-full border-gray-300 rounded px-1 py-0.5">
            </td>

            <td class="border p-1">
                <input type="text"
                       name="checks[<?php echo e($i); ?>][remarks]"
                       class="w-full border-gray-300 rounded px-1 py-0.5">
            </td>

            <td class="border p-1">
                <input type="number" step="0.01"
                       name="checks[<?php echo e($i); ?>][amount]"
                       class="check-amount w-full border-gray-300 rounded text-right px-1 py-0.5">
            </td>
        </tr>
        <?php endfor; ?>
        </tbody>
    </table>
</div>



<select id="all-account-names" class="hidden">
    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option
            data-bank="<?php echo e(trim($acc->bank_name)); ?>"
            data-branch="<?php echo e(trim($acc->branch_name)); ?>"
            value="<?php echo e($acc->account_holder_name); ?>"
        >
            <?php echo e($acc->account_holder_name); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>

<select id="all-account-numbers" class="hidden">
    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option
            data-bank="<?php echo e(trim($acc->bank_name)); ?>"
            data-branch="<?php echo e(trim($acc->branch_name)); ?>"
            data-name="<?php echo e(trim($acc->account_holder_name)); ?>"
            value="<?php echo e($acc->account_number); ?>"
        >
            <?php echo e($acc->account_number); ?>

        </option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<style>
/* ===== REMITTANCE INPUT VISIBILITY FIX ===== */
.remittance-table input,
.remittance-table select {
    width: 100%;
    min-width: 140px;
    height: 42px;              /* taller inputs */
    padding: 8px 10px;
    font-size: 14px;           /* clearer text */
    border-radius: 6px;
}

/* Remarks need more space */
.remittance-table .remarks-input {
    min-width: 220px;
}

/* Amount fields clearer */
.remittance-table .amount-input {
    text-align: right;
    font-weight: 500;
}

/* Better focus */
.remittance-table input:focus,
.remittance-table select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
}

/* Improve table spacing */
.remittance-table th,
.remittance-table td {
    padding: 8px;
    vertical-align: middle;
}
</style>

<script>
document.addEventListener('change', function (e) {

    const row = e.target.closest('tr');
    if (!row) return;

    const bankInput    = row.querySelector('[name*="[bank_branch]"]');
    const nameSelect   = row.querySelector('.account-name-select');
    const numberSelect = row.querySelector('.account-number-select');

    /* ===============================
       BANK → ACCOUNT NAMES
    =============================== */
    if (e.target === bankInput) {

        // reset
        nameSelect.innerHTML   = '<option value="">Select Account Name</option>';
        numberSelect.innerHTML = '<option value="">Select Account #</option>';

        nameSelect.disabled   = false; // ✅ MUST BE CLICKABLE
        numberSelect.disabled = true;

        if (!bankInput.value) return;

        const [bankName, branchName] = bankInput.value.split(' - ').map(v => v.trim());

        const allNames = document.querySelectorAll('#all-account-names option');

        allNames.forEach(opt => {
            if (
                opt.dataset.bank === bankName &&
                (!branchName || opt.dataset.branch === branchName)
            ) {
                nameSelect.appendChild(opt.cloneNode(true));
            }
        });
    }

    /* ===============================
       ACCOUNT NAME → ACCOUNT NUMBERS
    =============================== */
    if (e.target.classList.contains('account-name-select')) {

        numberSelect.innerHTML = '<option value="">Select Account #</option>';
        numberSelect.disabled = true;

        if (!nameSelect.value || !bankInput.value) return;

        const [bankName, branchName] = bankInput.value.split(' - ').map(v => v.trim());

        const allNumbers = document.querySelectorAll('#all-account-numbers option');

        allNumbers.forEach(opt => {
            if (
                opt.dataset.bank === bankName &&
                opt.dataset.name === nameSelect.value &&
                (!branchName || opt.dataset.branch === branchName)
            ) {
                numberSelect.appendChild(opt.cloneNode(true));
            }
        });

        // ✅ ACCOUNT NUMBER NOW CLICKABLE
        numberSelect.disabled = false;
    }

    /* ===============================
       LOCK AFTER ACCOUNT #
    =============================== */
    if (e.target.classList.contains('account-number-select') && e.target.value) {
        // Keep selects enabled so values are submitted with the form.
        // Disabled form controls are not included in POST payload.
    }
});

/* ===============================
   CLEAR BUTTON
=============================== */
document.addEventListener('click', function (e) {
    if (!e.target.classList.contains('clear-account')) return;

    const row = e.target.closest('tr');

    const nameSelect   = row.querySelector('.account-name-select');
    const numberSelect = row.querySelector('.account-number-select');

    nameSelect.innerHTML   = '<option value="">Select Account Name</option>';
    numberSelect.innerHTML = '<option value="">Select Account #</option>';

    nameSelect.disabled   = false;
    numberSelect.disabled = true;

    nameSelect.value   = '';
    numberSelect.value = '';
});
</script>




            
            <div>
                <h4 class="font-semibold mb-2 text-center">
                    Cash Details (Denominations)
                </h4>

                <table class="w-full border cash-table">

                    <thead class="bg-gray-100 text-center">
                        <tr>
                            <th class="border p-2">#</th>
                            <th class="border p-2">Denomination</th>
                            <th class="border p-2">PCS</th>
                            <th class="border p-2">Amount</th>
                        </tr>
                    </thead>


                    <tbody>
                        <?php $denoms = [1000,500,200,100,50,20,10,5,1,0.25]; ?>
                        <?php $__currentLoopData = $denoms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $den): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="border p-1 text-center"><?php echo e($i + 1); ?></td>

                            <td class="border p-1 text-right">
                                <?php echo e(number_format($den, 2)); ?>

                                <input type="hidden"
                                    name="cash[<?php echo e($i); ?>][denomination]"
                                    value="<?php echo e($den); ?>">
                            </td>

                            <td class="border p-1">
                                <input type="number" min="0"
                                    name="cash[<?php echo e($i); ?>][pcs]"
                                    data-denom="<?php echo e($den); ?>"
                                    class="w-full text-right px-1 py-0.5 border-gray-300 rounded cash-pcs">
                            </td>

                            <td class="border p-1">
                                <input type="text" readonly
                                    class="w-full bg-gray-100 border-gray-300 rounded text-right peso cash-amount">
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tfoot>
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="3" class="border p-2 text-right">
                                    Total Cash
                                </td>
                                <td class="border p-2">
                                    <input type="text"
                                        id="total_cash"
                                        readonly
                                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                                </td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
            </div>

        </div>
<style>
/* ===== CASH DETAILS (COMPACT INPUTS) ===== */
.cash-table input {
    width: 100%;
    height: 32px;         
    padding: 4px 6px;
    font-size: 13px;
    border-radius: 4px;
}

/* PCS input */
.cash-table .pcs-input {
    max-width: 70px;
    text-align: center;
}

/* Amount input */
.cash-table .amount-input {
    max-width: 130px;
    text-align: right;
    font-weight: 500;
}

/* Denomination column */
.cash-table .denomination {
    font-weight: 500;
    white-space: nowrap;
}

/* Tighter row spacing */
.cash-table td,
.cash-table th {
    padding: 4px 6px;
}
</style>
        
        <div class="mt-6 border bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                
                <div>
                    <label>Total Checks</label>
                    <input type="text" id="total_checks" readonly
                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                </div>

                
                <div>
                    <label>Total Cash</label>
                    <input type="text" id="total_cash_summary" readonly
                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                </div>

                
                <div>
                    <label>Total Cash & Checks</label>
                    <input type="text" id="total_cash_checks" readonly
                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                </div>

                
                <div>
                    <label>Total Remitted</label>
                    <input type="text" id="total_remitted"
                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                </div>

                
                <div>
                    <label>Overage / Shortage</label>
                    <input type="text" id="over_short" readonly
                        class="w-full bg-gray-100 text-right border rounded px-2 peso">
                </div>

            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('account-holder')) return;

    const input = e.target;
    const row = input.closest('tr');
    if (!row) return;

    const list = document.getElementById('account-holders-list');
    const option = Array.from(list.options)
        .find(o => o.value === input.value);

    if (!option) return;

    const accountNo = option.dataset.account || '';

    const accountInput = row.querySelector('.account-number');

    if (accountInput && !accountInput.value) {
        accountInput.value = accountNo;
    }
});

/* ================= CHECK PAYMENTS================= */
document.addEventListener('input', async function (e) {

    /* ================= BANK SEARCH ================= */
    if (e.target.classList.contains('bank-search')) {
        const input = e.target;
        const row = input.closest('tr');
        const box = row.querySelector('.bank-results');
        const query = input.value.trim();

        if (query.length < 2) {
            box.classList.add('hidden');
            box.innerHTML = '';
            return;
        }

        const res = await fetch(`/admin/banks/search?q=${query}`);
        const banks = await res.json();

        box.innerHTML = '';
        box.classList.remove('hidden');

        banks.forEach(bank => {
            const div = document.createElement('div');
            div.className = 'px-2 py-1 hover:bg-blue-100 cursor-pointer text-sm';
            div.innerHTML = `<strong>${bank.bank_name}</strong><br><span class="text-xs">${bank.branch_name}</span>`;

            div.onclick = () => {
                input.value = `${bank.bank_name} — ${bank.branch_name}`;
                row.querySelector('.bank-name').value = bank.bank_name;
                row.querySelector('.branch-name').value = bank.branch_name;
                row.querySelector('.account-number').value = bank.account_number ?? '';
                row.querySelector('.account-search').value = bank.account_holder_name ?? '';
                box.classList.add('hidden');
            };

            box.appendChild(div);
        });
    }

    /* ================= ACCOUNT NAME SEARCH ================= */
    if (e.target.classList.contains('account-search')) {
        const input = e.target;
        const row = input.closest('tr');
        const box = row.querySelector('.account-results');
        const query = input.value.trim();

        if (query.length < 2) {
            box.classList.add('hidden');
            box.innerHTML = '';
            return;
        }

        const res = await fetch(`/admin/banks/search?q=${query}`);
        const banks = await res.json();

        box.innerHTML = '';
        box.classList.remove('hidden');

        banks.forEach(bank => {
            const div = document.createElement('div');
            div.className = 'px-2 py-1 hover:bg-blue-100 cursor-pointer text-sm';
            div.textContent = `${bank.account_holder_name} (${bank.bank_name})`;

            div.onclick = () => {
                input.value = bank.account_holder_name;
                row.querySelector('.bank-search').value = `${bank.bank_name} — ${bank.branch_name}`;
                row.querySelector('.bank-name').value = bank.bank_name;
                row.querySelector('.branch-name').value = bank.branch_name;
                row.querySelector('.account-number').value = bank.account_number ?? '';
                box.classList.add('hidden');
            };

            box.appendChild(div);
        });
    }
});
</script>


<?php if($reportType === 'receivables'): ?>

        <div class="space-y-8">

        
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-2">ACCOUNT RECEIVABLES</h3>

                <table class="w-full text-sm border" id="accountReceivables">
                    <thead class="bg-gray-100">
                    <tr>
                        <th>SI No</th>
                        <th>Customer</th>
                        <th>Leadman</th>
                        <th>Credit Limit</th>
                        <th>Remaining Credit</th>
                        <th>Terms (Days)</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            
                            <td>
                                <input name="ar_si[]" class="border px-2 py-1 w-full">
                            </td>

                            
                            <td>
                                <select name="ar_customer_id[]" class="customer-select border px-2 py-1 w-full">
                                    <option value="">Select customer</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option
                                            value="<?php echo e($customer->id); ?>"
                                            data-credit="<?php echo e($customer->credit_limit); ?>"
                                            data-remaining="<?php echo e($customer->remaining_credit); ?>"
                                        >
                                            <?php echo e($customer->store_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>

                            
                            <td>
                                <input name="ar_leadman[]"
                                       class="border px-2 py-1 w-full"
                                       placeholder="Leadman">
                            </td>

                            
                            <td>
                                <input
                                    type="text"
                                    class="credit-limit bg-gray-100 w-full text-right"
                                    readonly
                                >
                            </td>

                            
                            <td>
                                <input
                                    type="text"
                                    class="remaining-credit bg-gray-100 w-full text-right font-bold"
                                    readonly
                                >
                            </td>

                            
                            <td>
                                <input
                                    type="number"
                                    name="ar_terms[]"
                                    class="border px-2 py-1 w-full text-right terms-input"
                                >
                            </td>

                            
                            <td>
                                <input
                                    type="date"
                                    name="ar_due_date[]"
                                    class="border px-2 py-1 w-full"
                                >
                            </td>

                            
                            <td>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="ar_amount[]"
                                   class="border px-2 py-1 w-full text-right ar-amount"
                                >
                            </td>

                            <td>
                                <button type="button" onclick="removeRow(this)">✖</button>
                            </td>
                        </tr>
                        </tbody>

                </table>

                <button type="button" onclick="addRow('accountReceivables')" class="text-blue-600 text-sm mt-2">
                    + Add Row
                </button>
            </div>
            <div class="mt-4 p-3 bg-gray-50 border rounded flex justify-end">
                <div class="text-right">
                    <div class="text-sm text-gray-600">Total Remaining Credit Exposure</div>
                    <div
                        id="total-exposure"
                        class="text-xl font-bold text-blue-700"
                    >
                        ₱ 0.00
                    </div>
                </div>
            </div>
<div id="credit-warning"
     class="hidden mt-3 p-3 rounded bg-red-100 text-red-700 font-semibold">
    The entered amount exceeds the customer's remaining credit. Excess will be charged to the Leadman (Shortage Collections) when you save.
</div>

<script>
document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('terms-input')) return;

    const row = e.target.closest('tr');
    const terms = parseInt(e.target.value, 10);
    const reportDate = document.querySelector('input[name="report_date"]')?.value;
    const dueDateInput = row.querySelector('input[name="ar_due_date[]"]');

    if (!reportDate || !terms || terms <= 0) {
        dueDateInput.value = '';
        return;
    }

    const base = new Date(reportDate);
    base.setDate(base.getDate() + terms);

    dueDateInput.value = base.toISOString().split('T')[0];
});
</script>


            
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-2">COLLECTION OF RECEIVABLES</h3>

                <table class="w-full text-sm border" id="collections">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>CR No</th>
                            <th>SI No</th>
                            <th>Customer Name</th>
                            <th>Remarks</th>
                            <th>Check Details</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input name="cr_no[]" class="border px-2 py-1 w-full"></td>
                            <td>
                                <input
                                        name="cr_si_no[]"
                                        class="border px-2 py-1 w-full"
                                        placeholder="SI No"
                                    >
                                </td>
                            <td><select name="cr_customer_id[]" class="border px-2 py-1 w-full"><option value="">Select customer</option>
                                    <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>">
                                            <?php echo e($customer->store_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td><input name="cr_remarks[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="cr_check[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="cr_amount[]" type="number" step="0.01" class="border px-2 py-1 w-full"></td>
                            <td><button type="button" onclick="removeRow(this)">✖</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" onclick="addRow('collections')" class="text-blue-600 text-sm mt-2">
                    + Add Row
                </button>
            </div>

            
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-2">STOCK TRANSFER</h3>

                <table class="w-full text-sm border" id="stockTransfer">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>Reference</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input name="st_ref[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="st_desc[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="st_amount[]" type="number" step="0.01" class="border px-2 py-1 w-full"></td>
                            <td><button type="button" onclick="removeRow(this)">✖</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" onclick="addRow('stockTransfer')" class="text-blue-600 text-sm mt-2">
                    + Add Row
                </button>
            </div>

            
            <div class="border rounded p-4">
                <h3 class="font-semibold mb-2">SHORTAGE COLLECTIONS</h3>

                <table class="w-full text-sm border" id="shortages">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>Shortage Date</th>
                            <th>Name</th>
                            <th>Collection Date</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="date" name="sh_date[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="sh_name[]" class="border px-2 py-1 w-full"></td>
                            <td><input type="date" name="sh_collection[]" class="border px-2 py-1 w-full"></td>
                            <td><input name="sh_amount[]" type="number" step="0.01" class="border px-2 py-1 w-full"></td>
                            <td><button type="button" onclick="removeRow(this)">✖</button></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" onclick="addRow('shortages')" class="text-blue-600 text-sm mt-2">
                    + Add Row
                </button>
            </div>

        </div>
    


<script>
function addRow(tableId) {
    const table = document.getElementById(tableId).querySelector('tbody');
    const row = table.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => i.value = '');
    row.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    row.querySelectorAll('textarea').forEach(t => t.value = '');
    table.appendChild(row);
}

function removeRow(btn) {
    const row = btn.closest('tr');
    const table = row.parentNode;
    if (table.rows.length > 1) row.remove();
}
</script>

<script>
document.addEventListener('change', function (e) {
    if (!e.target.classList.contains('customer-select')) return;

    const row = e.target.closest('tr');
    const selected = e.target.selectedOptions[0];

    if (!selected) return;

    const credit = parseFloat(selected.dataset.credit || 0);
    const remaining = parseFloat(selected.dataset.remaining || 0);

    row.querySelector('.credit-limit').value =
        credit.toLocaleString('en-US', { minimumFractionDigits: 2 });

    row.querySelector('.remaining-credit').value =
        remaining.toLocaleString('en-US', { minimumFractionDigits: 2 });

    // 🔴 warning if zero or negative
    if (remaining <= 0) {
        row.querySelector('.remaining-credit').style.background = '#fee2e2';
    }
});
</script>
<script>
function parseMoney(val) {
    return Number(String(val || '').replace(/[,₱\s]/g, '')) || 0;
}

function formatMoney(val) {
    return val.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('ar-amount')) return;

    const row = e.target.closest('tr');
    const remainingEl = row.querySelector('.remaining-credit');

    const selected = row.querySelector('.customer-select')?.selectedOptions?.[0];
    const baseRemaining = parseFloat(selected?.dataset?.remaining || 0);
    const amount = parseMoney(e.target.value);

    const remaining = baseRemaining - amount;

    // 🔄 auto-deduct
    remainingEl.value = formatMoney(Math.max(remaining, 0));

    // 🔴 highlight if over limit
    if (remaining < 0) {
        row.style.background = '#fee2e2'; // red
        remainingEl.style.background = '#fecaca';
    } else {
        row.style.background = '';
        remainingEl.style.background = '#f3f4f6';
    }

    updateTotalExposure();
});
</script>
<script>
document.querySelector('form').addEventListener('submit', function (e) {

    let hasError = false;
    let message = '';

    document.querySelectorAll('.ar-amount').forEach(input => {
        const row = input.closest('tr');
        const remaining = parseMoney(
            row.querySelector('.remaining-credit').value
        );

        if (remaining < 0) {
            hasError = true;
            message = '❌ One or more customers exceeded their credit limit.';
        }
    });

    if (hasError) {
        e.preventDefault();
        alert(message);
    }
});
</script>

<script>
function updateTotalExposure() {
    let total = 0;

    document.querySelectorAll('.remaining-credit').forEach(input => {
        total += parseMoney(input.value);
    });

    document.getElementById('total-exposure').textContent =
        '₱ ' + formatMoney(total);
}

// run once on load
updateTotalExposure();
</script>
<script>
function parseMoney(v) {
    return Number(String(v || '').replace(/[,₱\s]/g, '')) || 0;
}

function formatMoney(v) {
    return v.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function updateTotalExposure() {
    let total = 0;
    document.querySelectorAll('.remaining-credit').forEach(i => {
        total += parseMoney(i.value);
    });
    document.getElementById('total-exposure').textContent =
        '₱ ' + formatMoney(total);
}

document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('ar-amount')) return;

    const row = e.target.closest('tr');
    const selected = row.querySelector('.customer-select')?.selectedOptions?.[0];
    const baseRemaining = parseFloat(selected?.dataset?.remaining || 0);
    const amount = parseMoney(e.target.value);

    const remaining = baseRemaining - amount;
    const remainingEl = row.querySelector('.remaining-credit');
    const warning = document.getElementById('credit-warning');

    remainingEl.value = formatMoney(Math.max(remaining, 0));

    if (remaining < 0) {
        row.style.background = '#fee2e2';          // 🔴 row red
        remainingEl.style.background = '#fecaca';
        warning?.classList.remove('hidden');        // 🔴 show message
    } else {
        row.style.background = '';
        remainingEl.style.background = '#f3f4f6';
        warning?.classList.add('hidden');
    }

    updateTotalExposure();
});
</script>
<script>
document.querySelector('form').addEventListener('submit', function (e) {
    let exceeded = false;

    document.querySelectorAll('.ar-amount').forEach(input => {
        const row = input.closest('tr');
        const remaining = parseMoney(
            row.querySelector('.remaining-credit').value
        );

        if (remaining < 0) exceeded = true;
    });

    if (exceeded) {
        e.preventDefault();
        alert('❌ Cannot save. One or more customers exceeded their remaining credit.');
    }
});
</script>



<?php endif; ?>


<div 
    x-data="{
        cols: {
            bodega: true,
            crs1: true,
            crs2: true,
            crs3: true,
            outside: true,
            water: true
        }
    }"
    class="<?php echo e($reportType !== 'borrowers' ? 'hidden' : ''); ?>"
>

    <h3 class="font-semibold mb-3 flex items-center gap-2">
        <i data-lucide="users" class="w-4 h-4"></i>
        Borrower's Agreement Monitoring
    </h3>

    <div class="mb-3 p-3 border rounded bg-gray-50 text-sm">
        <p class="font-semibold mb-2">Show / Hide Columns</p>

        <div class="flex flex-wrap gap-4">
            <label><input type="checkbox" x-model="cols.bodega"> Bodega</label>
            <label><input type="checkbox" x-model="cols.crs1"> CRS 1</label>
            <label><input type="checkbox" x-model="cols.crs2"> CRS 2</label>
            <label><input type="checkbox" x-model="cols.crs3"> CRS 3</label>
            <label><input type="checkbox" x-model="cols.outside"> Outside Town</label>
            <label><input type="checkbox" x-model="cols.water"> Water</label>
        </div>
    </div>

    <div class="overflow-x-auto">
        <div class="flex gap-2 mb-2">
            <button type="button" id="add-borrower-row"
                class="px-3 py-1 bg-blue-600 text-white rounded">+ Add Row</button>

            <button type="button" id="remove-borrower-row"
                class="px-3 py-1 bg-red-600 text-white rounded">Remove Row</button>
        </div>

        <table class="w-full text-sm border-collapse">

            
            <thead>
                <tr class="bg-gray-100 font-semibold text-center">
                    <th rowspan="2" class="border p-2 w-28">Empties</th>

                    <th x-show="cols.bodega" colspan="2" class="border p-2">Bodega</th>
                    <th x-show="cols.crs1" colspan="2" class="border p-2">CRS 1</th>
                    <th x-show="cols.crs2" colspan="2" class="border p-2">CRS 2</th>
                    <th x-show="cols.crs3" colspan="2" class="border p-2">CRS 3</th>
                    <th x-show="cols.outside" colspan="2" class="border p-2">Outside Town</th>
                    <th x-show="cols.water" colspan="2" class="border p-2">Water</th>
                </tr>

                
                <tr class="bg-gray-50 text-center">
                    <th x-show="cols.bodega" class="border p-1">Borrowed</th>
                    <th x-show="cols.bodega" class="border p-1">Returned</th>

                    <th x-show="cols.crs1" class="border p-1">Borrowed</th>
                    <th x-show="cols.crs1" class="border p-1">Returned</th>

                    <th x-show="cols.crs2" class="border p-1">Borrowed</th>
                    <th x-show="cols.crs2" class="border p-1">Returned</th>

                    <th x-show="cols.crs3" class="border p-1">Borrowed</th>
                    <th x-show="cols.crs3" class="border p-1">Returned</th>

                    <th x-show="cols.outside" class="border p-1">Borrowed</th>
                    <th x-show="cols.outside" class="border p-1">Returned</th>

                    <th x-show="cols.water" class="border p-1">Borrowed</th>
                    <th x-show="cols.water" class="border p-1">Returned</th>
                </tr>
            </thead>

            
            <tbody id="borrowers-body">
                <?php $items = ['Plastic','Kasalo','Litro']; ?>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="borrower-row">
                    <td class="border p-2 font-semibold item-name"><?php echo e($item); ?></td>

                    <td x-show="cols.bodega" class="border p-1">
                        <input type="number" name="borrowed_bodega_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.bodega" class="border p-1">
                        <input type="number" name="returned_bodega_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>

                    <td x-show="cols.crs1" class="border p-1">
                        <input type="number" name="borrowed_crs1_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.crs1" class="border p-1">
                        <input type="number" name="returned_crs1_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>

                    <td x-show="cols.crs2" class="border p-1">
                        <input type="number" name="borrowed_crs2_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.crs2" class="border p-1">
                        <input type="number" name="returned_crs2_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>

                    <td x-show="cols.crs3" class="border p-1">
                        <input type="number" name="borrowed_crs3_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.crs3" class="border p-1">
                        <input type="number" name="returned_crs3_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>

                    <td x-show="cols.outside" class="border p-1">
                        <input type="number" name="borrowed_outside_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.outside" class="border p-1">
                        <input type="number" name="returned_outside_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>

                    <td x-show="cols.water" class="border p-1">
                        <input type="number" name="borrowed_water_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                    <td x-show="cols.water" class="border p-1">
                        <input type="number" name="returned_water_<?php echo e(strtolower($item)); ?>[]" class="w-full px-1 py-0.5">
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>

            
            <tfoot>
                <tr class="bg-gray-100 font-semibold text-center">
                    <td class="border p-2">TOTAL</td>

                    <td x-show="cols.bodega" class="border p-2">
                        <input id="total_bodega" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.bodega" class="border p-2"></td>

                    <td x-show="cols.crs1" class="border p-2">
                        <input id="total_crs1" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.crs1" class="border p-2"></td>

                    <td x-show="cols.crs2" class="border p-2">
                        <input id="total_crs2" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.crs2" class="border p-2"></td>

                    <td x-show="cols.crs3" class="border p-2">
                        <input id="total_crs3" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.crs3" class="border p-2"></td>

                    <td x-show="cols.outside" class="border p-2">
                        <input id="total_outside" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.outside" class="border p-2"></td>

                    <td x-show="cols.water" class="border p-2">
                        <input id="total_water" readonly class="w-full bg-gray-100 text-right">
                    </td>
                    <td x-show="cols.water" class="border p-2"></td>
                </tr>
            </tfoot>

        </table>
    </div>
</div>

     
    <div class="flex items-center gap-4 mt-10">
        <button type="submit"
            class="inline-flex items-center gap-3 px-6 py-3 bg-blue-600 text-white text-lg
                   rounded-lg shadow hover:bg-blue-700 transition">
            <i data-lucide="save" class="w-5 h-5"></i>
            Save <?php echo e(ucfirst($reportType)); ?>

        </button>

        <a href="<?php echo e(route('admin.reports.consolidated')); ?>"
           class="inline-flex items-center gap-2 px-5 py-3 bg-gray-100 text-gray-700
                  rounded-lg shadow hover:bg-gray-200 transition">
            <i data-lucide="corner-down-left" class="w-5 h-5"></i>
            Back to consolidated
        </a>
    </div>

</form>


<script>
function addRow(tableId) {
    const tbody = document.getElementById(tableId).querySelector('tbody');
    const row = tbody.rows[0].cloneNode(true);
    row.querySelectorAll('input').forEach(i => i.value = '');
    tbody.appendChild(row);
}

function removeRow(btn) {
    const row = btn.closest('tr');
    const tbody = row.parentNode;
    if (tbody.rows.length > 1) row.remove();
}

function numberVal(v) {
    return Number(String(v || '').replace(/[₱,\s]/g, '')) || 0;
}

/* ==========================================
   FOOTER TOTALS — FIXED (ALL COLUMNS)
========================================== */

function updateFooterTotals() {

    const sumColumns = [
        'full_case',
        'half_case',
        'box',
        'total_cases',
        'total_ucs',
        'no_of_receipts',
        'customer_count',
        
        'gross_sales',
        'sales_discount',
        'coupon_discount',
        'net_sales',

        'containers_deposit',
        'purchased_refund',
        'stock_transfer',
        'net_credit_sales',
        'shortage_collections',
        'ar_collections',
        'other_income',

        'cash_proceeds',
        'cash',
        'check'
    ];

    const totals = {};

    // 1️⃣ SUM ROW VALUES
    sumColumns.forEach(name => {
        let total = 0;

        document
            .querySelectorAll(`#receiptsTable tbody input[name="${name}[]"]`)
            .forEach(input => {
                total += parsePeso(input.value);
            });

        totals[name] = total;

        const footer = document.querySelector(
            `#receiptsTable tfoot input[name="sum_${name}"]`
        );

        if (!footer) return;

        // money vs count
        if (
            name.includes('sales') ||
            name.includes('deposit') ||
            name.includes('refund') ||
            name.includes('income') ||
            name.includes('cash')
        ) {
            footer.value = formatPeso(total);
        } else {
            footer.value = total;
        }
    });

    // 2️⃣ TOTAL REMITTANCE FOOTER
    const totalRemittance =
        (totals.cash || 0) + (totals.check || 0);

    const remFooter = document.querySelector(
        `#receiptsTable tfoot input[name="sum_total_remittance"]`
    );
    if (remFooter) remFooter.value = formatPeso(totalRemittance);

    // 3️⃣ SHORTAGE / OVERAGE FOOTER (CORRECT FORMULA)
    const shortOver =
        totalRemittance -
        parsePeso(
            document.querySelector(
                '#receiptsTable tfoot input[name="sum_cash_proceeds"]'
            )?.value
        );

    const shortFooter = document.querySelector(
        `#receiptsTable tfoot input[name="sum_short_over"]`
    );
    if (shortFooter) shortFooter.value = formatPeso(shortOver);
}

// Recalculate when table changes
document.addEventListener('input', e => {
    if (e.target.closest('#receiptsTable')) {
        updateFooterTotals();
    }
});

// Initial run
updateFooterTotals();
</script>
   
<script>
/*
  Consolidated fixed script for Remittance / Receivables / Borrowers
  - Keeps all functionality from your original script
  - Removes duplicate definitions and nested DOMContentLoaded handlers
  - Adds a few small helper adaptors to support multiple name variants
*/
function parseNumber(str) {
  if (str === null || typeof str === 'undefined') return 0;
  return parseFloat(String(str).replace(/[₱,\s]/g, '')) || 0;
}

function parsePeso(val) {
    return parseFloat(
        String(val || '').replace(/[₱,\s]/g, '')
    ) || 0;
}

function formatPeso(num) {
    return '₱ ' + Number(num).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// PESO INPUT BEHAVIOR
document.addEventListener('focusin', function (e) {
    if (!e.target.classList.contains('peso')) return;
    e.target.value = parsePeso(e.target.value) || '';
});

document.addEventListener('focusout', function (e) {
    if (!e.target.classList.contains('peso')) return;
    const raw = parsePeso(e.target.value);
    e.target.value = raw ? formatPeso(raw) : '';
});

(function () {
  // ---------- Helpers ----------
  function q(selector) { return document.querySelector(selector); }
  function qAll(selector) { return Array.from(document.querySelectorAll(selector)); }

  function getByNameOrId(nameOrId) {
    // support either input[name="x"] or id="#x"
    return q(`input[name="${nameOrId}"]`) || q(`#${nameOrId}`);
  }


  function parseIntSafe(v) {
    if (v === null || v === undefined || v === '') return 0;
    const n = parseInt(String(v).replace(/[^\d-]/g, ''), 10);
    return Number.isFinite(n) ? n : 0;
  }

  function formatPesoForDisplay(num) {
    if (num === '' || num === null || typeof num === 'undefined') return '';
    return '₱ ' + Number(num).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  function formatPesoRaw(num) {
    return Number(num).toFixed(2);
  }

  function setPesoValue(el, val) {
    if (!el) return;
    if (el.classList.contains('peso')) {
      el.value = formatPesoForDisplay(val);
    } else {
      // fallback: set formatted string if element looks like money input (name contains 'total' or 'cash' etc.)
      el.value = formatPesoForDisplay(val);
    }
  }

  function sumInputsBySelector(selector) {
    let total = 0;
    qAll(selector).forEach(inp => {
      total += parseNumber(inp.value);
    });
    return total;
  }

  // support multiple name variants
  function getTotalRemittedEl() {
    return getByNameOrId('total_remitted') || getByNameOrId('total_remittance') || q('#total_remitted');
  }

  function getOverShortEl() {
    return getByNameOrId('over_short') || getByNameOrId('summary_overage_shortage') || q('#over_short');
  }

  // Use a single ready handler
  document.addEventListener('DOMContentLoaded', () => {

    // Replace lucide icons if present
    if (window.lucide) try { lucide.replace(); } catch (e) {}

    // ---------------- Peso input behaviors ----------------
    qAll('.peso').forEach(field => {
      // initialize display if server rendered numeric
      if (field.value !== '') {
        const raw = parseNumber(field.value);
        field.value = raw === 0 && String(field.value).trim() === '' ? '' : formatPesoForDisplay(raw);
      }

      field.addEventListener('focus', () => {
        const raw = parseNumber(field.value);
        // show raw for editing (no trailing .00)
        field.value = (raw === 0 && String(field.value).trim() === '') ? '' : (Number(raw).toFixed(2).replace(/\.00$/, ''));
        setTimeout(() => {
          field.selectionStart = field.selectionEnd = field.value.length;
        }, 0);
      });

      field.addEventListener('blur', () => {
        const raw = parseNumber(field.value);
        if (field.getAttribute('data-no-format') === 'true') {
          field.value = (raw === 0 && String(field.value).trim() === '') ? '' : raw.toFixed(2);
        } else {
          field.value = (raw === 0 && String(field.value).trim() === '') ? '' : formatPesoForDisplay(raw);
        }
      });

      field.addEventListener('input', () => {
        // only allow digits, dot, comma, whitespace while typing
        field.value = field.value.replace(/[^0-9.,\s-]/g, '');
      });
    });

    /* ===============================
   Helper Functions
================================ */

function set(el, val) {
    if (!el) return;
    el.value = val.toFixed(2);
}

/* =====================================================
   RECEIPTS ONLY — CLEAN & FINAL
   (Peso + comma, correct math, no duplicates)
===================================================== */

/* ---------- PESO HELPERS ---------- */
function parsePeso(val) {
    return parseFloat(String(val || '').replace(/[₱,\s]/g, '')) || 0;
}

function formatPeso(num) {
    return '₱ ' + Number(num).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

/* ---------- PESO INPUT UX ---------- */
document.addEventListener('focusin', e => {
    if (!e.target.classList.contains('peso')) return;
    e.target.value = parsePeso(e.target.value) || '';
});

document.addEventListener('focusout', e => {
    if (!e.target.classList.contains('peso')) return;
    const raw = parsePeso(e.target.value);
    e.target.value = raw ? formatPeso(raw) : '';
});

/* =====================================================
   RECEIPTS ROW CALCULATION
===================================================== */
document.addEventListener('input', function (e) {

    const row = e.target.closest('#receiptsTable tbody tr');
    if (!row) return;

    const val = name =>
        parsePeso(row.querySelector(`[name="${name}[]"]`)?.value);

    const setPeso = (name, value) => {
        const el = row.querySelector(`[name="${name}[]"]`);
        if (el) el.value = formatPeso(value);
    };

    const setNumber = (name, value) => {
        const el = row.querySelector(`[name="${name}[]"]`);
        if (el) el.value = value;
    };

    /* ---------- CASES ---------- */
    const full = val('full_case');
    const half = val('half_case');
    const box  = val('box');

    setNumber('total_cases', full + half + box);
    // Total UCS is MANUAL — DO NOT AUTO-CALCULATE

    /* ---------- NET SALES ----------
       gross - sales discount - coupon discount
    -------------------------------- */
    const netSales =
        val('gross_sales')
        - val('sales_discount')
        - val('coupon_discount');

    setPeso('net_sales', netSales);

    /* ---------- CASH PROCEEDS ----------
       net sales
     + containers deposit
     - purchased refund
     + stock transfer
     - net credit sales
     + shortage collections
     + AR collections
     + other income
    -------------------------------- */
    const cashProceeds =
        netSales
        + val('containers_deposit')
        - val('purchased_refund')
        + val('stock_transfer')
        - val('net_credit_sales')
        + val('shortage_collections')
        + val('ar_collections')
        + val('other_income');

    setPeso('cash_proceeds', cashProceeds);

    /* ---------- TOTAL REMITTANCE ----------
       cash + check (manual)
    -------------------------------- */
    const totalRemittance =
        val('cash') + val('check');

    setPeso('total_remittance', totalRemittance);

    /* ---------- SHORTAGE / OVERAGE ----------
       total remittance - cash proceeds
    -------------------------------- */
    setPeso('short_over', totalRemittance - cashProceeds);

});


// ---------- REMITTANCE: CHECKS TOTAL ----------
    function updateCheckTotals() {
        let totalChecks = 0;

        document
            .querySelectorAll('input[name^="checks"][name$="[amount]"]')
            .forEach(input => {
            totalChecks += parseNumber(input.value);
            });

        const totalField = getByNameOrId('total_checks');
        if (totalField) setPesoValue(totalField, totalChecks);

        updateTotalCashChecks();
        }

        // event delegation for CHECKS
        document.addEventListener('input', (e) => {
        if (
            e.target &&
            e.target.name &&
            e.target.name.startsWith('checks') &&
            e.target.name.endsWith('[amount]')
        ) {
            updateCheckTotals();
        }
        });

    // ---------- CASH: denom × pcs ----------
    function updateCashRow(pcsInput) {
      const denom = parseFloat(pcsInput.dataset.denom || pcsInput.getAttribute('data-denom')) || 0;
      const pcs = parseFloat(pcsInput.value) || 0;
      const row = pcsInput.closest('tr');
      if (!row) return;
      const amountField = row.querySelector('.cash-amount');
      const amount = denom * pcs;
      if (amountField) setPesoValue(amountField, amount);
      updateTotalCash();
    }

    function updateTotalCash() {
      const totalCash = sumInputsBySelector('.cash-amount');
      const totalCashField = getByNameOrId('total_cash');
      if (totalCashField) setPesoValue(totalCashField, totalCash);
      const totalCashSummaryField = getByNameOrId('total_cash_summary');
      if (totalCashSummaryField) setPesoValue(totalCashSummaryField, totalCash);
      updateTotalCashChecks();
    }

    // delegate cash pcs input changes
    document.addEventListener('input', (e) => {
      if (e.target && e.target.matches('.cash-pcs')) updateCashRow(e.target);
    });

    // ---------- TOTAL CASH + CHECKS ----------
    function updateTotalCashChecks() {
      const cash = parseNumber(getByNameOrId('total_cash')?.value);
      const checks = parseNumber(getByNameOrId('total_checks')?.value);
      const final = (cash || 0) + (checks || 0);
      const el = getByNameOrId('total_cash_checks');
      if (el) setPesoValue(el, final);
      updateOverageShortage();
    }

    // ---------- OVERAGE / SHORTAGE ----------
    // formula: over_short = total_cash_checks − total_remitted
    function updateOverageShortage() {
      const totalCC = parseNumber(getByNameOrId('total_cash_checks')?.value);
      // try both possible names for remitted field
      const remEl = getTotalRemittedEl();
      const rem = remEl ? parseNumber(remEl.value) : 0;
      const diff = (totalCC || 0) - (rem || 0);

      const outEl = getOverShortEl();
      if (outEl) {
        // some blades expect formatted peso, others plain; choose peso formatted
        outEl.value = formatPesoForDisplay(diff);
      }
    }

    // auto-copy behaviour: when total_cash_checks changes, optionally sync into remitted input (only if present and empty)
    function syncTotalCashChecksToRemitted() {
      const totalEl = getByNameOrId('total_cash_checks');
      const remEl = getTotalRemittedEl();
      if (!totalEl || !remEl) return;
      const totalVal = parseNumber(totalEl.value);
      // if remitted empty, copy; otherwise leave user input
      const remVal = parseNumber(remEl.value);
      if (!remVal) remEl.value = formatPesoForDisplay(totalVal);
      updateOverageShortage();
    }

    // listen for changes on total_cash_checks (support both name variants and id)
    const totalCashChecksListen = getByNameOrId('total_cash_checks') || q('#total_cash_checks');
    if (totalCashChecksListen) {
      totalCashChecksListen.addEventListener('input', syncTotalCashChecksToRemitted);
    }

    // if user edits remitted input directly, recalc overage/shortage
    document.addEventListener('input', (e) => {
      if (!e.target) return;
      const name = e.target.getAttribute && e.target.getAttribute('name');
      if (name === 'total_remitted' || name === 'total_remittance' || e.target.id === 'total_remitted' || e.target.id === 'total_remittance') {
        updateOverageShortage();
      }
      if (e.target.classList && e.target.classList.contains('peso')) {
        updateOverageShortage();
    }

    });

function updateTotals() {
    let arTotal=0, collTotal=0, stockTotal=0, shortTotal=0;

    document.querySelectorAll(".ar-amount").forEach(i => arTotal += parsePeso(i.value));
    document.querySelectorAll(".coll-amount").forEach(i => collTotal += parsePeso(i.value));
    document.querySelectorAll(".stock-amount").forEach(i => stockTotal += parsePeso(i.value));
    document.querySelectorAll(".short-amount").forEach(i => shortTotal += parsePeso(i.value));

    document.querySelector("input[name='ar_total']").value = arTotal ? formatPeso(arTotal) : "";
    document.querySelector("input[name='coll_total']").value = collTotal ? formatPeso(collTotal) : "";
    document.querySelector("input[name='stock_total']").value = stockTotal ? formatPeso(stockTotal) : "";
    document.querySelector("input[name='short_total']").value = shortTotal ? formatPeso(shortTotal) : "";
}


/* ============ ADD / REMOVE ROWS ============ */

function addRow(tableSelector, html) {
    const tbody = document.querySelector(`${tableSelector} tbody`);
    const tr = document.createElement("tr");
    tr.innerHTML = html;
    tbody.appendChild(tr);
    renumberRows(tableSelector);
    updateTotals();
}

function removeRow(tableSelector) {
    const tbody = document.querySelector(`${tableSelector} tbody`);
    const rows = tbody.querySelectorAll("tr");
    if (rows.length > 1) rows[rows.length - 1].remove();
    renumberRows(tableSelector);
    updateTotals();
}


document.getElementById("remove-shortage-row").onclick = () =>
    removeRow("#shortage-table");

document.addEventListener('input', function (e) {
    if (!e.target.classList.contains('terms-input')) return;

    const row = e.target.closest('tr');
    const terms = parseInt(e.target.value);
    const reportDateInput = document.querySelector('input[name="report_date"]');
    const dueDateInput = row.querySelector('input[name="ar_due_date[]"]');

    if (!reportDateInput || !terms || terms <= 0) {
        dueDateInput.value = '';
        return;
    }

    const baseDate = new Date(reportDateInput.value);
    baseDate.setDate(baseDate.getDate() + terms);

    const yyyy = baseDate.getFullYear();
    const mm = String(baseDate.getMonth() + 1).padStart(2, '0');
    const dd = String(baseDate.getDate()).padStart(2, '0');

    dueDateInput.value = `${yyyy}-${mm}-${dd}`;
});

/* ============ AUTO CALC WHEN EDITING AMOUNTS ============ */
document.addEventListener("input", e => {
    if (
        e.target.matches(".ar-amount") ||
        e.target.matches(".coll-amount") ||
        e.target.matches(".stock-amount") ||
        e.target.matches(".short-amount")
    ) updateTotals();
});

// ---------- Borrowers calculation & highlight ----------


// ---------- Calculate all totals helper ----------
function calculateAllTotals() {
  try { updateCheckTotals(); } catch(e) {}
  try { updateTotalCash(); } catch(e) {}
  try { updateReceivablesTotals(); } catch(e) {}
  try { updateOverageShortage(); } catch(e) {}
  try { calcCases(); } catch(e) {}
  try { calcNet(); } catch(e) {}
  try { calcCashProceeds(); } catch(e) {}
}

// run once on load
calculateAllTotals();

    // ---------- Dynamic rows handlers (checks, cash, ar, collection, stock, shortage, borrowers) ----------
    // renumber helper
    function renumberTable(tableSelector) {
      const table = document.querySelector(tableSelector);
      if (!table) return;
      table.querySelectorAll('tbody tr').forEach((tr, idx) => {
        const firstCell = tr.querySelector('td:first-child');
        if (firstCell) firstCell.textContent = idx + 1;
      });
    }

    // checks add/remove
    const checksTableBody = document.querySelector('#checks-table tbody');
    document.getElementById('add-check-row')?.addEventListener('click', () => {
      if (!checksTableBody) return;
      const rowCount = checksTableBody.querySelectorAll('tr').length;
      const tr = document.createElement('tr');
      tr.classList.add('check-row');
      tr.innerHTML = `
            <td class="border p-1 text-center align-middle">${rowCount + 1}</td>
            <td class="border p-1"><input type="text" name="checks[${rowCount}][bank_branch]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="checks[${rowCount}][account_number]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="checks[${rowCount}][account_holder_name]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="date" name="checks[${rowCount}][check_date]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="checks[${rowCount}][remarks]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="checks[${rowCount}][amount]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso check-amount"></td>
        `;
      checksTableBody.appendChild(tr);
      if (window.lucide) try { lucide.replace(); } catch (e) {}
    });

    document.getElementById('remove-check-row')?.addEventListener('click', () => {
      if (!checksTableBody) return;
      const rows = checksTableBody.querySelectorAll('tr');
      if (rows.length > 0) {
        rows[rows.length - 1].remove();
        renumberTable('#checks-table');
        calculateAllTotals();
      }
    });

    // cash denom add/remove
    const cashTableBody = document.querySelector('#cash-table tbody');
    document.getElementById('add-cash-row')?.addEventListener('click', () => {
      if (!cashTableBody) return;
      const denom = prompt('Enter denomination (numeric), e.g. 100.00', '100');
      if (!denom) return;
      const index = cashTableBody.querySelectorAll('tr').length;
      const tr = document.createElement('tr');
      tr.innerHTML = `
            <td class="border p-1 text-center">${index + 1}</td>
            <td class="border p-1 text-right">${parseFloat(denom).toFixed(2)}</td>
            <td class="border p-1"><input type="number" min="0" name="pcs[]" data-denom="${parseFloat(denom)}" class="w-full border-gray-300 rounded px-1 py-0.5 cash-pcs"></td>
            <td class="border p-1"><input type="text" readonly name="cash_amount[]" class="w-full bg-gray-100 border-gray-300 rounded text-right peso cash-amount"></td>
        `;
      cashTableBody.appendChild(tr);
      if (window.lucide) try { lucide.replace(); } catch (e) {}
    });

    document.getElementById('remove-cash-row')?.addEventListener('click', () => {
      if (!cashTableBody) return;
      const rows = cashTableBody.querySelectorAll('tr');
      if (rows.length > 0) {
        rows[rows.length - 1].remove();
        calculateAllTotals();
      }
    });

    // AR rows
    const arTableBody = document.querySelector('#ar-table tbody');
    document.getElementById('add-ar-row')?.addEventListener('click', () => {
      if (!arTableBody) return;
      const row = document.createElement('tr');
      row.innerHTML = `
            <td class="border p-1"><input type="text" name="ar_si[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="ar_customer[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="ar_amount[]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso ar-amount"></td>
        `;
      arTableBody.appendChild(row);
      renumberTable('#ar-table');
    });
    document.getElementById('remove-ar-row')?.addEventListener('click', () => {
      if (!arTableBody) return;
      const rows = arTableBody.querySelectorAll('tr');
      if (rows.length > 0) rows[rows.length - 1].remove();
      updateReceivablesTotals();
    });

    // collection rows
    const collectionBody = document.querySelector('#collection-table tbody');
    document.getElementById('add-collection-row')?.addEventListener('click', () => {
      if (!collectionBody) return;
      const row = document.createElement('tr');
      row.innerHTML = `
            <td class="border p-1"><input type="text" name="coll_cr[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="coll_customer[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="coll_remarks[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="coll_checkdetails[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="coll_amount[]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso coll-amount"></td>
        `;
      collectionBody.appendChild(row);
    });
    document.getElementById('remove-collection-row')?.addEventListener('click', () => {
      if (!collectionBody) return;
      const rows = collectionBody.querySelectorAll('tr');
      if (rows.length > 0) rows[rows.length - 1].remove();
      updateReceivablesTotals();
    });

    // stock rows
    const stockBody = document.querySelector('#stock-table tbody');
    document.getElementById('add-stock-row')?.addEventListener('click', () => {
      if (!stockBody) return;
      const row = document.createElement('tr');
      row.innerHTML = `
            <td class="border p-1"><input type="text" name="stock_si[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="stock_customer[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="stock_amount[]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso stock-amount"></td>
            <td class="border p-1 text-center"><select name="stock_yesno[]" class="border-gray-300 rounded px-1 py-0.5"><option value="">-</option><option value="yes">Yes</option><option value="no">No</option></select></td>
        `;
      stockBody.appendChild(row);
    });
    document.getElementById('remove-stock-row')?.addEventListener('click', () => {
      if (!stockBody) return;
      const rows = stockBody.querySelectorAll('tr');
      if (rows.length > 0) rows[rows.length - 1].remove();
      updateReceivablesTotals();
    });

    // shortage rows
    const shortBody = document.querySelector('#shortage-table tbody');
    document.getElementById('add-shortage-row')?.addEventListener('click', () => {
      if (!shortBody) return;
      const row = document.createElement('tr');
      row.innerHTML = `
            <td class="border p-1"><input type="text" name="short_cr[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="short_customer[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="date" name="short_date[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="short_amount[]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso short-amount"></td>
        `;
      shortBody.appendChild(row);
    });
    document.getElementById('remove-shortage-row')?.addEventListener('click', () => {
      if (!shortBody) return;
      const rows = shortBody.querySelectorAll('tr');
      if (rows.length > 0) rows[rows.length - 1].remove();
      updateReceivablesTotals();
    });

    // borrowers simple add/remove (keeps original behavior)
    const borrowersTable = document.querySelector('#borrowers-table tbody');
    document.getElementById('add-borrower-row')?.addEventListener('click', () => {
      if (!borrowersTable) return;
      const tr = document.createElement('tr');
      tr.innerHTML = `
            <td class="border p-1"><input type="text" name="borrow_item[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="number" min="0" name="borrowed[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="number" min="0" name="returned[]" class="w-full border-gray-300 rounded px-1 py-0.5"></td>
            <td class="border p-1"><input type="text" name="borrow_amount[]" class="w-full border-gray-300 rounded px-1 py-0.5 text-right peso borrower-amount"></td>
        `;
      borrowersTable.appendChild(tr);
    });
    document.getElementById('remove-borrower-row')?.addEventListener('click', () => {
      if (!borrowersTable) return;
      const rows = borrowersTable.querySelectorAll('tr');
      if (rows.length > 0) rows[rows.length - 1].remove();
      updateBorrowersTotal();
    });

    // ---------- Before submit: convert peso fields to raw numeric strings ----------
const form = document.querySelector('form');

if (form) {
  form.addEventListener('submit', () => {

    /* 1️⃣ Copy FOOTER total_remittance → FIRST ROW */
    const footerTotal = document.querySelector(
      '#receiptsTable tfoot input[name="sum_total_remittance"]'
    );

    const rowTotal = document.querySelector(
      '#receiptsTable tbody input[name="total_remittance[]"]'
    );

    if (footerTotal && rowTotal) {
      rowTotal.value = parseNumber(footerTotal.value).toFixed(2);
    }

    /* 2️⃣ Strip peso formatting (SAFE) */
    document.querySelectorAll('.peso').forEach(field => {
      const raw = parseNumber(field.value);
      field.value = raw.toFixed(2);
    });
  });
}
    // ---------- Safety interval recalculation ----------
    setInterval(calculateAllTotals, 1500);

  }); // end DOMContentLoaded

})(); // end wrapper IIFE
// =========================
// ADD BORROWER ROW (Option A)
// =========================
document.getElementById("add-borrower-row")?.addEventListener("click", () => {
    let itemName = prompt("Enter new item name (e.g. Glass, Drum, Gallon):");

    if (!itemName || itemName.trim() === "") return;

    itemName = itemName.trim();
    const lower = itemName.toLowerCase();

    const tbody = document.getElementById("borrowers-body");
    const tr = document.createElement("tr");
    tr.classList.add("borrower-row");

    tr.innerHTML = `
    <td class="border p-2 font-semibold item-name">${itemName}</td>

    <td class="border p-1">
        <input type="number" name="borrowed_bodega_${lower}[]" 
               class="borrower-input bodega-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_bodega_${lower}[]" 
               class="borrower-input bodega-return w-full px-1 py-0.5">
    </td>

    <td class="border p-1">
        <input type="number" name="borrowed_crs1_${lower}[]" 
               class="borrower-input crs1-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_crs1_${lower}[]" 
               class="borrower-input crs1-return w-full px-1 py-0.5">
    </td>

    <td class="border p-1">
        <input type="number" name="borrowed_crs2_${lower}[]" 
               class="borrower-input crs2-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_crs2_${lower}[]" 
               class="borrower-input crs2-return w-full px-1 py-0.5">
    </td>

    <td class="border p-1">
        <input type="number" name="borrowed_crs3_${lower}[]" 
               class="borrower-input crs3-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_crs3_${lower}[]" 
               class="borrower-input crs3-return w-full px-1 py-0.5">
    </td>

    <td class="border p-1">
        <input type="number" name="borrowed_outside_${lower}[]" 
               class="borrower-input outside-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_outside_${lower}[]" 
               class="borrower-input outside-return w-full px-1 py-0.5">
    </td>

    <td class="border p-1">
        <input type="number" name="borrowed_water_${lower}[]" 
               class="borrower-input water-borrow w-full px-1 py-0.5">
    </td>
    <td class="border p-1">
        <input type="number" name="returned_water_${lower}[]" 
               class="borrower-input water-return w-full px-1 py-0.5">
    </td>
`;

    tbody.appendChild(tr);
    calculateBorrowers(); // refresh totals
});

// =========================
// REMOVE BORROWER ROW
// =========================
document.getElementById("remove-borrower-row")?.addEventListener("click", () => {
    const rows = document.querySelectorAll("#borrowers-body .borrower-row");

    if (rows.length > 3) { // keep Plastic/Kasalo/Litro
        rows[rows.length - 1].remove();
        calculateBorrowers();
    }
});
// ===============================
// BORROWERS AUTO CALC (GLOBAL FIX)
// ===============================
function calculateBorrowers() {
    const areas = ["bodega","crs1","crs2","crs3","outside","water"];

    areas.forEach(area => {
        let borrowed = 0;
        let returned = 0;

        document
            .querySelectorAll(`input[name^="borrowed_${area}_"]`)
            .forEach(i => borrowed += Number(i.value || 0));

        document
            .querySelectorAll(`input[name^="returned_${area}_"]`)
            .forEach(i => returned += Number(i.value || 0));

        const total = borrowed - returned;
        const totalInput = document.getElementById(`total_${area}`);

        if (totalInput) {
            totalInput.value = total;

            if (total < 0) {
                totalInput.style.background = "#dc2626";
                totalInput.style.color = "#fff";
                totalInput.style.fontWeight = "bold";
            } else {
                totalInput.style.background = "";
                totalInput.style.color = "";
                totalInput.style.fontWeight = "";
            }
        }
    });
}

// LISTEN TO ALL BORROWER INPUTS (DYNAMIC SAFE)
document.addEventListener("input", e => {
    if (
        e.target.name &&
        (e.target.name.startsWith("borrowed_") ||
         e.target.name.startsWith("returned_"))
    ) {
        calculateBorrowers();
    }
});

</script>
<style>
[x-cloak] { display: none !important; }
</style>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\add\add-report.blade.php ENDPATH**/ ?>