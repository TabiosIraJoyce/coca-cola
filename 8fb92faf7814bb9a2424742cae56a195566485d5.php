<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    
    <style>
    :root {
        --blue-dark: #02045A;
        --blue-deep: #0376B5;
        --blue-main: #05B3D9;
        --blue-soft: #9EE6F2;
        --blue-light: #D7F4FA;

        --coke-red: #C81E1E;
        --coke-red-dark: #A01616;
    }

    #inventoryTable input {
        min-width: 80px;
        height: 32px;
        padding: 4px 6px;
        font-size: 13px;
        text-align: right;
    }

    .coke-brand {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        letter-spacing: .03em;
    }

    /* BLUE TABLE HEADER */
    .blue-header {
        background: linear-gradient(
            90deg,
            var(--blue-dark),
            var(--blue-deep)
        );
        color: white;
    }

    /* CORE / IWS VISUALS */
    .core-bg { background: rgba(200, 30, 30, 0.06); }
    .iws-bg { background: var(--blue-light); }

    /* KPI CARDS */
    .kpi-blue {
        background: var(--blue-light);
        border: 1px solid var(--blue-soft);
        color: var(--blue-dark);
        font-weight: 700;
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        header, nav, .no-print, .no-print * {
            display: none !important;
        }

        .print-area {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>


    
     <?php $__env->slot('header', null, []); ?> 
        <div
            class="flex items-center justify-between bg-gradient-to-r from-blue-600 to-blue-600 shadow-md px-6 py-4 rounded-lg border-b-4 border-0081a7-900 no-print">

            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-full bg-blue/10 flex items-center justify-center border border-white/30 shadow-inner">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 text-white"></i>
                </div>
                
                <div>
                    <p class="text-2xl font-extrabold text-white coke-brand tracking-wide">
                        Coca-Cola Sales Reporting
                    </p>
                    <h2 class="text-xs text-white/80 uppercase tracking-widest">
                        Period Performance Summary
                    </h2>
                </div>

            </div>

            
            <div class="flex items-center gap-2">
                <button type="button"
                        onclick="window.history.back();"
                        class="no-print inline-flex items-center gap-1 px-3 py-1.5 rounded-md border border-white/40 text-white text-sm bg-white/5 hover:bg-white/15 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Back
                </button>

                <?php if(isset($report)): ?>
                    <button type="button"
                            onclick="window.print()"
                            class="no-print inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-black text-white text-sm shadow hover:bg-gray-900 transition">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print Report
                    </button>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="p-6 space-y-6 print-area">

        
        <div class="no-print">
            <a href="<?php echo e(route('admin.reports.periods.index')); ?>"
               class="inline-flex items-center gap-1 text-sm text-white-700 hover:text-white-900">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Period Summary
            </a>
        </div>

        
        <?php if(session('success')): ?>
            <div class="no-print bg-green-50 text-green-800 border border-green-200 px-4 py-2 rounded-md text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        
        <form id="periodForm"
            action="<?php echo e(isset($report)
                    ? route('admin.reports.periods.update', $report->id)
                    : route('admin.reports.periods.store')); ?>"
            method="POST"
            class="space-y-6">

            <?php echo csrf_field(); ?>
            
            <input type="hidden" name="custom_tables" id="custom_tables_json" value="">
            <input type="hidden" name="per_sku_json" id="per_sku_json" value="">

            <?php if(isset($report)): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>
            
            <div class="bg-white shadow-lg rounded-xl border border-red-100 p-5 space-y-4">

                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <span
                            class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-red-600 text-white text-xs font-bold"></span>
                        Sales Report
                    </h3>

                    <?php if(isset($report)): ?>
                        <span class="inline-flex items-center gap-1 text-xs uppercase tracking-wide px-2 py-1 rounded-full bg-black text-white">
                            <i data-lucide="save" class="w-3 h-3"></i>
                            Editing Report #<?php echo e($report->id); ?>

                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Branch *</label>
                        <select name="branch"
                                class="mt-1 w-full border rounded-md px-2 py-1.5 text-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <?php
                                // Add-report screen must be safe when $report is null.
                                $branchValue = old('branch', $report?->branch ?? ($branch ?? ''));
                            ?>
                            <option value="">Select Branch</option>
                            <option value="Solsona" <?php echo e($branchValue === 'Solsona' ? 'selected' : ''); ?>>Solsona</option>
                            <option value="Laoag" <?php echo e($branchValue === 'Laoag' ? 'selected' : ''); ?>>Laoag</option>
                            <option value="Batac" <?php echo e($branchValue === 'Batac' ? 'selected' : ''); ?>>Batac</option>
                        </select>
                    </div>

                    
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Period No. *</label>
                        <select name="period_no" id="period_no"
                                class="mt-1 w-full border rounded-md px-2 py-1.5 text-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <?php
                                $periodNoValue = old('period_no', $report?->period_no ?? ($periodNo ?? 1));
                            ?>
                            <?php for($i = 1; $i <= 12; $i++): ?>
                                <option value="<?php echo e($i); ?>"
                                    <?php echo e((string)$periodNoValue === (string)$i ? 'selected' : ''); ?>>
                                    Period <?php echo e($i); ?>

                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    
                    <div>
                        <label class="text-xs font-semibold text-gray-600 uppercase">Report Date *</label>
                        <input type="date"
                            name="report_date"
                            class="mt-1 w-full border rounded-md px-2 py-1.5 text-sm"
                            value="<?php echo e(old('report_date', $date ?? now()->toDateString())); ?>"
                            required>
                    </div>
                </div>

                <?php if(empty($isEdit) && (($existingReportCount ?? 0) > 0)): ?>
                    <div class="mt-3 p-3 rounded-lg border border-amber-200 bg-amber-50 text-sm text-amber-900 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <?php echo e(($existingReportCount ?? 0) > 1 ? 'Multiple reports already exist' : 'A report already exists'); ?>

                            for this Branch / Period / Date.
                            You can still create another report for a different <span class="font-semibold">Shipment No</span>.
                        </div>
                        <?php if(!empty($existingReportId)): ?>
                            <a href="<?php echo e(route('admin.reports.periods.edit', $existingReportId)); ?>"
                               class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-amber-700 text-white text-xs font-semibold hover:bg-amber-800">
                                Edit Latest Report
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
               <?php
                    // FROM CONTROLLER
                    $coreTarget   = $coreOnlyTarget ?? 0;
                    $petcsdTarget = $petcsdTarget ?? 0;
                    $stillsTarget = $stillsTarget ?? 0;

                    // FINAL TOTAL excludes PET CSD.
                    $target = $coreTarget + $stillsTarget;

                    // Add-report screen must start at 0s unless we are explicitly editing a report.
                    $actual = $report?->actual_sales ?? 0;
                    $ach    = $report?->achievement_pct ?? 0;
                    $var    = $report?->total_variance ?? 0;
                ?>

        
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">

    
    <div class="border rounded-lg p-4 bg-red-50">
        <p class="text-xs font-bold text-red-700 uppercase mb-2">
            Core Target
        </p>

             <input type="text"
             id="core_target"
             class="w-full bg-transparent text-right text-lg font-bold"
             value="<?php echo e(number_format($coreTarget, 2)); ?>"
             readonly>


         <div class="flex justify-between text-xs text-red-700 mt-3">
             <span>Actual</span>
             <span id="core_actual" class="font-semibold"><?php echo e(number_format($report?->core_actual_sales ?? 0, 2)); ?></span>
         </div>
  
         <div class="flex justify-between text-xs text-red-700 mt-1">
             <span>Variance</span>
             <span id="core_variance" class="font-semibold"><?php echo e(number_format($report?->core_variance ?? 0, 2)); ?></span>
         </div>

        <div class="text-xs text-red-700 mt-1 text-right">
            Achievement:
            <span id="core_achievement" class="font-semibold"><?php echo e(number_format($report?->core_achievement_pct ?? 0, 2)); ?>%</span>
        </div>
    </div>

    
    <div class="border rounded-lg p-4 bg-yellow-50">
        <p class="text-xs font-bold text-yellow-700 uppercase mb-2">
            PET CSD Target
        </p>

         <input type="text"
             id="petcsd_target"
             class="w-full bg-transparent text-right text-lg font-bold"
             value="<?php echo e(number_format($petcsdTarget, 2)); ?>"
             readonly>

         <div class="flex justify-between text-xs text-yellow-700 mt-3">
             <span>Actual</span>
             <span id="petcsd_actual" class="font-semibold"><?php echo e(number_format($report?->petcsd_actual_sales ?? 0, 2)); ?></span>
         </div>
  
         <div class="flex justify-between text-xs text-yellow-700 mt-1">
             <span>Variance</span>
             <span id="petcsd_variance" class="font-semibold"><?php echo e(number_format($report?->petcsd_variance ?? 0, 2)); ?></span>
         </div>

        <div class="text-xs text-yellow-700 mt-1 text-right">
            Achievement:
            <span id="petcsd_achievement" class="font-semibold"><?php echo e(number_format($report?->petcsd_achievement_pct ?? 0, 2)); ?>%</span>
        </div>
    </div>

    
    <div class="border rounded-lg p-4 bg-blue-50">
        <p class="text-xs font-bold text-blue-700 uppercase mb-2">
            Stills Target
        </p>

         <input type="text"
             id="stills_target"
             class="w-full bg-transparent text-right text-lg font-bold"
             value="<?php echo e(number_format($stillsTarget, 2)); ?>"
             readonly>


         <div class="flex justify-between text-xs text-blue-700 mt-3">
             <span>Actual</span>
             <span id="stills_actual" class="font-semibold"><?php echo e(number_format($report?->stills_actual_sales ?? 0, 2)); ?></span>
         </div>
  
         <div class="flex justify-between text-xs text-blue-700 mt-1">
             <span>Variance</span>
             <span id="stills_variance" class="font-semibold"><?php echo e(number_format($report?->stills_variance ?? 0, 2)); ?></span>
         </div>

        <div class="text-xs text-blue-700 mt-1 text-right">
            Achievement:
            <span id="stills_achievement" class="font-semibold"><?php echo e(number_format($report?->stills_achievement_pct ?? 0, 2)); ?>%</span>
        </div>
    </div>

</div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">

    
    <div class="border rounded-lg p-4 bg-gray-50">
        <p class="text-xs font-semibold text-gray-600 uppercase mb-1">
            Target Sales
        </p>
         <input
             type="text"
             id="target_sales"
             name="target_sales"
             class="w-full bg-transparent text-right text-xl font-bold focus:outline-none"
             value="<?php echo e(number_format($target, 2)); ?>"
             readonly
 >
    </div>

    
    <div class="border rounded-lg p-4 bg-green-50">
        <p class="text-xs font-semibold text-green-700 uppercase mb-1">
            Actual Sales
        </p>
         <input
             type="text"
             id="actual_sales"
             readonly
             class="w-full bg-transparent text-right text-xl font-bold text-green-800 focus:outline-none"
             value="<?php echo e(number_format($actual, 2)); ?>"
         >
    </div>

    
    <div class="border rounded-lg p-4 bg-blue-50">
        <p class="text-xs font-semibold text-blue-700 uppercase mb-1">
            Achievement
        </p>
        <div
            id="achievement_display"
            class="text-right text-xl font-bold text-blue-800">
            <?php echo e(number_format($ach, 2)); ?>%
        </div>

        <input type="hidden"
               name="achievement_pct"
               id="achievement_pct"
               value="<?php echo e($ach); ?>">
    </div>

    
    <div class="border rounded-lg p-4 bg-orange-50">
        <p class="text-xs font-semibold text-orange-700 uppercase mb-1">
            Variance
        </p>
         <div
             id="variance_display"
             class="text-right text-xl font-bold text-orange-800">
             <?php echo e(number_format($var, 2)); ?>

         </div>

        <input type="hidden"
               name="total_variance"
               id="total_variance"
               value="<?php echo e($var); ?>">
    </div>

</div>

            

            
                
                <div class="bg-white shadow-lg rounded-xl p-4 border border-red-200 space-y-3">

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-lg text-white-700 tracking-wide flex items-center gap-2">
                                <span
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-black-600 text-black text-xs font-bold"></span>
                                Coca-Cola Sales Performance Report
                            </h3>
                        </div>
                    </div>

                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">
                                Shipment No. *
                            </label>
                            <input
                                type="text"
                                name="shipment_no"
                                placeholder="e.g. 30302791"
                                value="<?php echo e(old('shipment_no', $report->shipment_no ?? '')); ?>"
                                class="w-full px-3 py-2 border rounded text-sm focus:ring focus:ring-blue-200"
                                required
                            >
                        </div>

                        <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 no-print">
                            <input
                                type="text"
                                id="core_search_keyword"
                                placeholder="Search pack size or product..."
                                class="w-full px-3 py-2 border rounded text-sm focus:ring focus:ring-blue-200"
                            >

                            <button
                                type="button"
                                id="core_search_btn"
                                class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 font-semibold">
                                Search
                            </button>

                            <button
                                type="button"
                                id="core_search_clear_btn"
                                class="w-full bg-gray-100 text-gray-800 px-3 py-2 rounded text-sm hover:bg-gray-200 border border-gray-300 font-semibold">
                                Clear
                            </button>
                        </div>

                        <p id="core_search_result" class="no-print mt-1 text-xs text-gray-600 hidden"></p>

                    
                    <div class="overflow-auto mt-3">
                        <table class="min-w-full text-xs border border-gray-200 rounded-lg overflow-hidden"
                               id="coreIwsTable">
                            <thead>
                            <tr
                                class="blue-header text-center text-sm md:text-base uppercase font-semibold">
                                <th class="border border-red-700/60 px-4 py-3 w-32">Pack Size</th>
                                <th class="border border-red-700/60 px-4 py-3 w-48">Product</th>
                                <th class="border border-red-700/60 px-4 py-3" colspan="4">Core (Primary)</th>
                                <th class="border border-red-700/60 px-4 py-3" colspan="2">IWS (Secondary)</th>
                            </tr>

                            <tr
                                class="bg-gray-100 text-center text-[11px] md:text-xs uppercase font-semibold tracking-wide">
                                <th class="border px-4 py-2"></th>
                                <th class="border px-4 py-2"></th>
                                <th class="border px-4 py-2 bg-red-50">Cases</th>
                                <th class="border px-4 py-2 bg-red-50">UCS</th>
                                <th class="border px-4 py-2 bg-red-100">Total UCS</th>
                                <th class="border px-4 py-2 bg-red-100">Total</th>
                                <th class="border px-4 py-2 bg-blue-50">Cases</th>
                                <th class="border px-4 py-2 bg-blue-100">Total UCS</th>
                            </tr>
                            </thead>

                            <tbody id="coreIwsBody" class="bg-white">
                                
                            </tbody>

                            <tfoot class="text-right font-semibold">
                            <tr class="bg-gray-50 text-[11px] md:text-xs">
                                <td colspan="2"
                                    class="border px-2 py-1 text-left font-bold text-gray-700 tracking-wide">
                                    TOTAL CASES
                                </td>
                                <td class="border px-2 py-1 bg-red-50" id="total_core_pcs">0</td>
                                <td class="border px-2 py-1 bg-red-50"></td>
                                <td class="border px-2 py-1 bg-red-100" id="total_core_ucs">0.00</td>
                                <td class="border px-2 py-1 bg-red-100" id="total_core_amount">0.00</td>
                                <td class="border px-2 py-1 bg-blue-50" id="total_iws_pcs">0</td>
                                <td class="border px-2 py-1 bg-blue-100" id="total_iws_ucs">0.00</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="grid-3x3" class="w-4 h-4 text-red-700"></i>
                            Per SKU (Reference)
                        </h3>
                    </div>

                    <div class="border rounded-lg bg-white shadow-sm overflow-x-auto">
                        <table class="min-w-full text-[11px] md:text-xs border" id="perSkuTable">
                            <thead>
                            <tr class="bg-gray-300 text-gray-900 uppercase text-[11px] md:text-xs font-semibold">
                                <th class="border px-3 py-2 text-left w-72" rowspan="2">Products</th>
                                <th class="border px-3 py-2 text-center" colspan="2">Target</th>
                                <th class="border px-3 py-2 text-center" colspan="2">Actual</th>
                                <th class="border px-3 py-2 text-center" colspan="2">Variance</th>
                            </tr>
                            <tr class="bg-yellow-300 text-gray-900 uppercase text-[10px] md:text-[11px] font-semibold">
                                <th class="border px-2 py-1 text-center">In PCS</th>
                                <th class="border px-2 py-1 text-center">In UCS</th>
                                <th class="border px-2 py-1 text-center">In PCS</th>
                                <th class="border px-2 py-1 text-center">In UCS</th>
                                <th class="border px-2 py-1 text-center">In PCS</th>
                                <th class="border px-2 py-1 text-center">In UCS</th>
                            </tr>
                            </thead>
                            <tbody id="perSkuBody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white shadow-lg rounded-xl p-4 border border-gray-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-lg flex items-center gap-2 text-gray-800">
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-black text-white text-xs font-bold">3</span>
                            Additional Custom Tables (optional)
                        </h3>
                        <button type="button"
                                onclick="addCustomTable()"
                                class="no-print inline-flex items-center gap-1 px-3 py-1.5 rounded-md border border-gray-400 text-gray-800 text-xs bg-gray-50 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Custom Table
                        </button>
                    </div>

                    <p class="text-xs text-gray-500">
                        Use this if some periods include extra matrices (e.g. incentives, bonus grids).
                        Each table is fully dynamic (add/remove columns & rows) and includes
                        a <span class="font-semibold">TOTAL row at the bottom</span> that auto-sums each column (number format).
                    </p>

                    <div id="customTablesContainer" class="space-y-4">
                        
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        <?php echo e(isset($report) ? 'Update Report' : 'Save Full Report'); ?>

                    </button>

                    <p class="text-xs text-gray-500 mt-2">
                        Only one report per Branch per Period is allowed.
                        Saving again will update the existing report.
                    </p>
                </div>
        </form>
        <script>
            document.getElementById('periodForm')?.addEventListener('submit', function (e) {
                const rows = document.querySelectorAll('#coreIwsBody tr');
                if (!rows.length) {
                    e.preventDefault();
                    alert('Please fill in sales items before saving.');
                    return;
                }

                // Serialize Additional Custom Tables into JSON for saving.
                const tables = [];
                const wrappers = Array.from(document.querySelectorAll('#customTablesContainer .custom-table-wrapper'));
                wrappers.forEach(wrapper => {
                    const title = wrapper.querySelector('.custom-title')?.value ?? '';
                    const table = wrapper.querySelector('table.custom-table');
                    if (!table) return;

                    const headers = Array.from(table.tHead?.rows?.[0]?.cells ?? []).map(th => {
                        return th.querySelector('input')?.value ?? '';
                    });

                    const bodyRows = Array.from(table.tBodies?.[0]?.rows ?? []).map(tr => {
                        return Array.from(tr.cells).map(td => td.querySelector('input')?.value ?? '');
                    });

                    const hasAny =
                        String(title).trim() !== '' ||
                        headers.some(h => String(h ?? '').trim() !== '') ||
                        bodyRows.some(r => r.some(c => String(c ?? '').trim() !== ''));

                    if (!hasAny) return;

                    tables.push({ title, headers, rows: bodyRows });
                });

                const jsonEl = document.getElementById('custom_tables_json');
                if (jsonEl) jsonEl.value = JSON.stringify(tables);

                // Safety: if user added tables but serializer couldn't find any content, warn and block submit.
                if (wrappers.length > 0 && tables.length === 0) {
                    e.preventDefault();
                    alert('Custom table was not saved because its inputs were not detected. Please refresh the page (Ctrl+F5) then try again.');
                    return;
                }

                // Serialize Per SKU into a single field to avoid max_input_vars limits.
                const perSkuRows = [];
                const num = (val) => {
                    if (val === null || val === undefined || val === '') return 0;
                    const cleaned = String(val).replace(/[^0-9.\-]/g, '');
                    const n = parseFloat(cleaned);
                    return Number.isFinite(n) ? n : 0;
                };

                document.querySelectorAll('#perSkuBody tr').forEach(tr => {
                    const pack = tr.dataset.pack || '';
                    const product = tr.dataset.product || '';
                    if (!pack && !product) return;

                    perSkuRows.push({
                        pack,
                        product,
                        target_pcs: num(tr.querySelector('.sku-target-pcs')?.value),
                        target_ucs: num(tr.querySelector('.sku-target-ucs')?.value),
                        actual_pcs: num(tr.querySelector('.sku-actual-pcs')?.value),
                        actual_ucs: num(tr.querySelector('.sku-actual-ucs')?.value),
                    });
                });

                const perSkuJson = document.getElementById('per_sku_json');
                if (perSkuJson) perSkuJson.value = JSON.stringify(perSkuRows);
            });
        </script>


    </div>


<script>
    // This page encodes sales for the selected Report Date.
    // "Yesterday" monitoring is displayed separately (server-rendered).

    const SAVED_ITEMS = <?php echo json_encode(
        isset($items)
            ? collect($items)->mapWithKeys(function ($i) {
                return [
                    $i->pack . '|' . $i->product => [
                        'core_pcs' => (float) $i->core_pcs,
                        'core_ucs' => (float) $i->core_ucs,
                        'iws_pcs'  => (float) $i->iws_pcs,
                        'iws_ucs'  => (float) $i->iws_ucs,
                    ]
                ];
            })
            : []
    ); ?>;

    const SAVED_INVENTORY = <?php echo json_encode(
        isset($inventoryRows)
            ? collect($inventoryRows)->mapWithKeys(function ($r) {
                $key = ($r['pack'] ?? '') . '|' . ($r['product'] ?? '');
                return [
                    $key => [
                        'srp' => (float) ($r['srp'] ?? 0),
                        'actual_inv' => (float) ($r['actual_inv'] ?? 0),
                        'ads' => (float) ($r['ads'] ?? 0),
                        'booking' => (float) ($r['booking'] ?? 0),
                        'deliveries' => (float) ($r['deliveries'] ?? 0),
                        'routing_days_p5' => (float) ($r['routing_days_p5'] ?? 0),
                        'routing_days_7' => (float) ($r['routing_days_7'] ?? 0),
                    ]
                ];
            })
            : []
    ); ?>;

    const SAVED_CUSTOM_TABLES = <?php echo json_encode($customTables ?? []); ?>;

    const SAVED_PER_SKU = <?php echo json_encode(
        collect($perSkuRows ?? [])->mapWithKeys(function ($row) {
            $key = ($row['pack'] ?? '') . '|' . ($row['product'] ?? '');
            return [
                $key => [
                    'target_pcs' => (float) ($row['target_pcs'] ?? 0),
                    'target_ucs' => (float) ($row['target_ucs'] ?? 0),
                    'actual_pcs' => (float) ($row['actual_pcs'] ?? 0),
                    'actual_ucs' => (float) ($row['actual_ucs'] ?? 0),
                ]
            ];
        })
    ); ?>;

    const IS_VIEW = <?php echo e(isset($isView) && $isView ? 'true' : 'false'); ?>;

    const PRODUCTS = <?php echo json_encode(
    $products->map(function ($p) {
        return [
            // ðŸ”¥ map DB fields to JS-expected keys
            'pack'     => $p->pack_size,
            'product'  => $p->product_name,
            'category' => strtolower(preg_replace('/\s+/', '', (string) ($p->category ?? ''))),
            'srp'      => (float) $p->srp,
            'ucs'      => (float) ($p->ucs ?? 0),
            'iws_ucs'  => (float) ($p->iws_ucs ?? $p->ucs ?? 0),
            'unit_ml'  => (float) ($p->unit_ml ?? 0),
            'bottles_per_case' => (int) ($p->bottles_per_case ?? 0),
        ];
    })->values()
); ?>;

</script>


<script>
    /* ---------------------------------------------
     * DEFAULT PRODUCT ROWS (CORE / PETCSD / STILLS)
     * ------------------------------------------- */
    //const DEFAULT_ROWS = [
        // CORE (RED)
        //{ pack: '237ml', product: 'COKE', category: 'core' },
        //{ pack: '237ml', product: 'ROYALTOrange', category: 'core' },
        //{ pack: '237ml', product: 'SPRITE', category: 'core' },
        //{ pack: '237ml', product: 'ROYALTLemon', category: 'core' },
        //{ pack: '750ml KASALO', product: 'COKE', category: 'core' },
        //{ pack: '1 LITER', product: 'COKE', category: 'core' },
        //{ pack: '1 LITER', product: 'ROYALTOrange', category: 'core' },
        //{ pack: '1 LITER', product: 'SPRITE', category: 'core' },
        //{ pack: '290ml MISMO', product: 'COKE', category: 'core' },
        //{ pack: '290ml MISMO', product: 'SPRITE', category: 'core' },
        //{ pack: '290ml MISMO', product: 'ROYALTStrawberry', category: 'core' },
        //{ pack: '250ml MISMO', product: 'ROYALTLemon', category: 'core' },
        //{ pack: '250ml MISMO', product: 'ROYALTOrange', category: 'core' },
        //{ pack: '190ml SWAKTO', product: 'COKE', category: 'core' },
        //{ pack: '190ml SWAKTO', product: 'COKE ZERO', category: 'core' },
        //{ pack: '190ml SWAKTO', product: 'ROYALTOrange', category: 'core' },
        //{ pack: '190ml SWAKTO', product: 'SPRITE', category: 'core' },
        //{ pack: '1.5 LTR x 12', product: 'COKE', category: 'core' },
        //{ pack: '1.5 LTR x 12', product: 'ROYALTOrange', category: 'core' },
        //{ pack: '1.5 LTR x 12', product: 'SPRITE', category: 'core' },
        //{ pack: '1.5 LTR x 12', product: 'COKE ZERO', category: 'core' },
        //{ pack: 'Cans 320ML', product: 'COKE', category: 'core' },
        //{ pack: 'Cans 320ML', product: 'ROYALTOrange', category: 'core' },
        //{ pack: 'Cans 320ML', product: 'SPRITE', category: 'core' },
        //{ pack: 'Cans 320ML', product: 'COKE ZERO', category: 'core' },

        // STILLS (BLUE)
        //{ pack: '180ml TETRA', product: 'MM APPLE', category: 'stills' },
       //{ pack: '180ml TETRA', product: 'MM ORANGE', category: 'stills' },
       //{ pack: '180ml TETRA', product: 'MM PINEAPPLE', category: 'stills' },
        //{ pack: '180ml TETRA', product: 'MM MANGO', category: 'stills' },
        //{ pack: '330ml', product: 'MINUTE MAID', category: 'stills' },
        //{ pack: '250ml', product: '(MM 250ml)', category: 'stills' },
        //{ pack: '800ml', product: '(MM 800ml)', category: 'stills' },
        //{ pack: '1L', product: '(MM 1L)', category: 'stills' },
        //{ pack: '330ml', product: 'WILKINS PURE', category: 'stills' },
        //{ pack: '330ml (12)', product: 'WILKINS PURE (12)', category: 'stills' },
        //{ pack: '500ml', product: 'WILKINS PURE 500ml', category: 'stills' },
       // { pack: '1L', product: 'WILKINS PURE 1L', category: 'stills' },
       // { pack: '330ml', product: 'WILKINS DIST', category: 'stills' },
       // { pack: '500ml', product: 'WILKINS DIST 500ml', category: 'stills' },
       // { pack: '1L', product: 'WILKINS DIST 1L', category: 'stills' },
       // { pack: '1.5 L', product: 'WILKINS DIST 1.5L', category: 'stills' },
        //{ pack: '5L', product: 'WILKINS DIST 5L', category: 'stills' },
        //{ pack: '7L', product: 'WILKINS DIST 7L', category: 'stills' },
        //{ pack: '250ml', product: 'POWERADE', category: 'stills' },
        //{ pack: '500ml', product: 'POWERADE', category: 'stills' },
        //{ pack: '200ML', product: 'Nutri Choco', category: 'stills' },
        //{ pack: '200ML', product: 'Nutri Strawberry', category: 'stills' },
        //{ pack: '110mlx24', product: 'CHOCO', category: 'stills' },
        //{ pack: '110mlx24', product: 'Strawberry', category: 'stills' },
        //{ pack: '480ml REAL LEAF', product: 'LEMON TEA', category: 'stills' },
        //{ pack: '480ml REAL LEAF', product: 'LEMON ICE', category: 'stills' },
        //{ pack: '480ml REAL LEAF', product: 'APPLE', category: 'stills' },
        //{ pack: '480ml REAL LEAF', product: 'HONEY APPLE', category: 'stills' },
        //{ pack: '480ml REAL LEAF', product: 'HONEY LEMON', category: 'stills' },
       // { pack: '480ml REAL LEAF', product: 'HONEY LYCHEE', category: 'stills' },
        //{ pack: '230ml', product: 'FUZE TEA LEMON', category: 'stills' },

        // CORE AGAIN
        //{ pack: '320ml', product: 'JACK DANIEL', category: 'core' },
        //{ pack: '500ml', product: 'COKE', category: 'core' },
        //{ pack: '2L', product: 'COKEX8', category: 'core' },
        //{ pack: '2L', product: 'TRIO PACK 1.5', category: 'core' },
       // { pack: '2L', product: 'TRIO PET 1L', category: 'core' },
       // { pack: '2L', product: '1.5X6', category: 'core' },
       // { pack: 'TALL', product: 'COKE', category: 'core' },
       // { pack: 'TALL', product: 'ROYA', category: 'core' },
       // { pack: 'TALL', product: 'SPRITE', category: 'core' },

        // PET CSD (YELLOW)
       // { pack: '290ml MISMO', product: 'COKE', category: 'petcsd' },
        //{ pack: '290ml MISMO', product: 'SPRITE', category: 'petcsd' },
        //{ pack: '290ml MISMO', product: 'ROYALTStrawberry', category: 'petcsd' },
        //{ pack: '250ml MISMO', product: 'ROYALTLemon', category: 'petcsd' },
        //{ pack: '250ml MISMO', product: 'ROYALTOrange', category: 'petcsd' },
        //{ pack: '1.5 LTR x 12', product: 'COKE', category: 'petcsd' },
        //{ pack: '1.5 LTR x 12', product: 'ROYALTOrange', category: 'petcsd' },
        //{ pack: '1.5 LTR x 12', product: 'SPRITE', category: 'petcsd' },
        //{ pack: '1.5 LTR x 12', product: 'COKE ZERO', category: 'petcsd' },
        //{ pack: '1.5 LTR x 12', product: 'COKE LIGHT', category: 'petcsd' },
        //{ pack: '500 ML', product: 'COKE', category: 'petcsd' },

        // CORE
       // { pack: '330 ML', product: 'LEMON DOU', category: 'core' },
       // { pack: '1.75L', product: 'Coke, Royal, Sprite', category: 'core' },

        // STILLS
        //{ pack: '245 ML', product: 'PREDATOR', category: 'stills' },
        //{ pack: '240 ML', product: 'MINUTE MAID', category: 'stills' },
    //];

function getBodyEl() {
    return document.getElementById('coreIwsBody');
}

function getInventoryBodyEl() {
    return document.getElementById('inventoryBody');
}
let customTableIndex = 0;
const bodyEl = document.getElementById('coreIwsBody');
const inventoryBodyEl = document.getElementById('inventoryBody');
const perSkuBodyEl = document.getElementById('perSkuBody');



    /* ----------------- helpers ----------------- */
    function cleanNumber(value) {
        if (!value) return 0;
        const cleaned = value.toString().replace(/[^0-9.\-]/g, '');
        return parseFloat(cleaned) || 0;
    }

    function formatPeso(num) {
        const n = Number(num) || 0;
        return n.toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /* ---------------------------------------------
     * MAIN PERFORMANCE TABLE ROW
     * ------------------------------------------- */
function createRow(row, index) {
    const { pack, product, category } = row;
    const key = pack + '|' + product;
    const saved = SAVED_ITEMS[key] || {};
    const savedInv = (typeof SAVED_INVENTORY !== 'undefined' && SAVED_INVENTORY && SAVED_INVENTORY[key])
        ? SAVED_INVENTORY[key]
        : {};
    const rowSrp = Number(savedInv.srp ?? row.srp ?? 0);
    const base = 5678;
    let defaultUcs = Number(row.ucs) || 0;
    if (defaultUcs <= 0) {
        const unitMl = Number(row.unit_ml) || 0;
        const bottles = Number(row.bottles_per_case) || 0;
        if (unitMl > 0 && bottles > 0) {
            defaultUcs = (unitMl * bottles) / base;
        }
    }
    const coreUcs = Number(saved.core_ucs) > 0 ? Number(saved.core_ucs) : defaultUcs;

    // TODO: Replace with provided IWS UCS computation formula later.
    const computeIwsUcs = (rowData, fallbackUcs) => {
        const preset = Number(rowData?.iws_ucs) || 0;
        return preset > 0 ? preset : fallbackUcs;
    };

    const iwsUcs  = Number(saved.iws_ucs) > 0 ? Number(saved.iws_ucs) : computeIwsUcs(row, defaultUcs);
    

    const tr = document.createElement('tr');
    tr.dataset.index = index;
    tr.dataset.category = category; // ðŸ”¥ REQUIRED
    tr.dataset.pack = String(pack ?? '');
    tr.dataset.product = String(product ?? '');
    tr.dataset.srp = Number.isFinite(rowSrp) ? String(rowSrp) : '0';
    tr.className = index % 2 === 0
        ? 'bg-white hover:bg-gray-50'
        : 'bg-gray-50 hover:bg-gray-100';

    let productColorClass = 'text-gray-900';
    if (category === 'core') productColorClass = 'text-red-700 font-semibold';
    else if (category === 'stills') productColorClass = 'text-blue-700 font-semibold';
    else if (category === 'petcsd') productColorClass = 'text-yellow-600 font-semibold';

    tr.innerHTML = `
        <td class="border px-2 py-1 whitespace-nowrap">
            ${pack}
            <input type="hidden" name="sales_items[${index}][pack]" value="${pack}">
        </td>

       <td class="border px-2 py-1 ${productColorClass}">
            ${product}

            <input type="hidden"
                name="sales_items[${index}][product]"
                value="${product}">

            <input type="hidden"
                name="sales_items[${index}][category]"
                class="row-category"
                value="${category}">
        </td>

        <td class="border px-1 py-0.5 text-right">
            <input type="number"
                min="0"
                name="sales_items[${index}][core_pcs]"
                class="w-full text-right border-none focus:ring-0 core-pcs input-cell"
                value="${saved.core_pcs ?? 0}">
        </td>

        <td class="border px-1 py-0.5 text-right">
            <input type="number"
                step="0.000001"
                min="0"
                name="sales_items[${index}][core_ucs]"
                readonly
                class="w-full text-right border-none focus:ring-0 core-ucs input-cell bg-gray-100 cursor-not-allowed"
                value="${Number(coreUcs) > 0 ? Number(coreUcs).toFixed(6) : 0}">
        </td>

       <td class="border px-2 py-0.5 text-right bg-red-50">
            <span class="core-total">0.00</span>
            <input type="hidden"
                name="sales_items[${index}][core_total_ucs]"
                class="core-total-hidden"
                value="0">
        </td>

        <td class="border px-2 py-0.5 text-right bg-red-50">
            <span class="core-total-peso">0.00</span>
        </td>

        <td class="border px-1 py-0.5 text-right">
            <input type="number"
                min="0"
                name="sales_items[${index}][iws_pcs]"
                class="w-full text-right border-none focus:ring-0 iws-pcs input-cell"
                value="${saved.iws_pcs ?? 0}">
            <input type="hidden"
                name="sales_items[${index}][iws_ucs]"
                class="iws-ucs"
                value="${Number(iwsUcs) > 0 ? Number(iwsUcs).toFixed(6) : 0}">
        </td>

        <td class="border px-2 py-0.5 text-right bg-blue-50">
            <span class="iws-total">0.00</span>
            <input type="hidden"
                name="sales_items[${index}][iws_total_ucs]"
                class="iws-total-hidden"
                value="0">
        </td>
    `;

    tr.querySelectorAll('.input-cell').forEach(input => {
        if (typeof IS_VIEW !== 'undefined' && IS_VIEW) {
            input.readOnly = true;
        }
        input.addEventListener('input', () => recalcRow(tr));
    });

    return tr;
}


    /* ---------------------------------------------
     * INVENTORY TABLE ROW
     * ------------------------------------------- */
    function createInventoryRow(row, index) {
        const { pack, product, category } = row;
        const savedKey = `${pack}|${product}`;
        const savedInv = (typeof SAVED_INVENTORY !== 'undefined' && SAVED_INVENTORY && SAVED_INVENTORY[savedKey])
            ? SAVED_INVENTORY[savedKey]
            : {};

        const tr = document.createElement('tr');
        tr.dataset.index = index;
        tr.dataset.pack = String(pack ?? '');
        tr.dataset.product = String(product ?? '');
        tr.className = index % 2 === 0 ? 'bg-white hover:bg-gray-50 inventory-row' : 'bg-gray-50 hover:bg-gray-100 inventory-row';

        let productColorClass = 'text-gray-900';
        if (category === 'core') {
            productColorClass = 'text-red-700 font-semibold';
        } else if (category === 'stills') {
            productColorClass = 'text-blue-700 font-semibold';
        } else if (category === 'petcsd') {
            productColorClass = 'text-yellow-600 font-semibold';
        }

        tr.innerHTML = `
            <td class="border px-2 py-1 text-[11px] md:text-xs text-gray-800">
                ${pack}
                <input type="hidden" name="inventories[${index}][pack]" value="${pack}">
            </td>

            <td class="border px-2 py-1 text-[11px] md:text-xs ${productColorClass} sku-label">
                ${product}
                <input type="hidden" name="inventories[${index}][product]" value="${product}">
            </td>

            <!-- SRP -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][srp]"
                    value="${(savedInv.srp ?? row.srp ?? 0)}"
                    readonly
                    class="w-full text-right border border-gray-300 bg-gray-100 cursor-not-allowed focus:ring-0 focus:border-gray-300 rounded text-[11px] md:text-xs px-1 inv-srp">
            </td>

            <td class="border px-2 py-0.5 text-right inv-peso-actual">0.00</td>

            <!-- ACTUAL INV -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][actual_inv]"
                    value="${savedInv.actual_inv ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-actual text-[11px] md:text-xs">
            </td>

            <!-- ADS -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][ads]"
                    value="${savedInv.ads ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-ads text-[11px] md:text-xs">
            </td>

            <td class="border px-2 py-0.5 text-right inv-days-actual">0.00</td>

            <!-- BOOKING -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][booking]"
                    value="${savedInv.booking ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-booking text-[11px] md:text-xs">
            </td>

            <td class="border px-2 py-0.5 text-right inv-days-booking">0.00</td>

            <!-- DELIVERIES -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][deliveries]"
                    value="${savedInv.deliveries ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-deliveries text-[11px] md:text-xs">
            </td>

            <td class="border px-2 py-0.5 text-right inv-ptd-total">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-days-ptd">0.00</td>

            <!-- ROUTING P5 -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][routing_days_p5]"
                    value="${savedInv.routing_days_p5 ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-routing-p5 text-[11px] md:text-xs">
            </td>

            <td class="border px-2 py-0.5 text-right inv-est-p5">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-after-p5">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-peso-p5">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-days-after-p5">0.00</td>

            <!-- ROUTING 7 -->
            <td class="border px-1 py-0.5 text-right">
                <input type="number" step="0.01" min="0"
                    name="inventories[${index}][routing_days_7]"
                    value="${savedInv.routing_days_7 ?? 0}"
                    class="w-full text-right border-none focus:ring-0 inv-routing-7 text-[11px] md:text-xs">
            </td>

            <td class="border px-2 py-0.5 text-right inv-est-7">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-after-month">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-days-month">0.00</td>
            <td class="border px-2 py-0.5 text-right inv-peso-month">0.00</td>
        `;


        const inputsSelectors = [
            '.inv-srp',
            '.inv-actual',
            '.inv-ads',
            '.inv-booking',
            '.inv-deliveries',
            '.inv-routing-p5',
            '.inv-routing-7',
        ];

        inputsSelectors.forEach(sel => {
            const inp = tr.querySelector(sel);
            if (inp) {
                inp.addEventListener('input', () => recalcInventoryRow(tr));
            }
        });

        return tr;
    }

    /* ---------------------------------------------
     * PER SKU ROW (REFERENCE)
     * ------------------------------------------- */
    function createPerSkuRow(row, index) {
        const { pack, product, category } = row;
        const key = `${pack}|${product}`;
        const saved = (typeof SAVED_PER_SKU !== 'undefined' && SAVED_PER_SKU && SAVED_PER_SKU[key])
            ? SAVED_PER_SKU[key]
            : {};
        const tr = document.createElement('tr');
        tr.dataset.index = index;
        tr.dataset.pack = pack;
        tr.dataset.product = product;
        tr.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';

        let productColorClass = 'text-gray-900';
        if (category === 'core') {
            productColorClass = 'text-red-700 font-semibold';
        } else if (category === 'stills') {
            productColorClass = 'text-blue-700 font-semibold';
        } else if (category === 'petcsd') {
            productColorClass = 'text-yellow-600 font-semibold';
        }

        tr.innerHTML = `
            <td class="border px-2 py-1">
                <div class="${productColorClass}">${product}</div>
                <div class="text-[10px] text-gray-500">${pack}</div>
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" min="0" step="1"
                       class="w-full text-right border border-gray-300 rounded px-1 py-0.5 sku-target-pcs"
                       value="${saved.target_pcs ?? ''}">
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" min="0" step="0.000001"
                       class="w-full text-right border border-gray-300 rounded px-1 py-0.5 sku-target-ucs"
                       value="${saved.target_ucs ?? ''}">
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" min="0" step="1"
                       class="w-full text-right border border-gray-300 rounded px-1 py-0.5 sku-actual-pcs"
                       value="${saved.actual_pcs ?? ''}">
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" min="0" step="0.000001"
                       class="w-full text-right border border-gray-300 rounded px-1 py-0.5 sku-actual-ucs"
                       value="${saved.actual_ucs ?? ''}">
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" readonly
                       class="w-full text-right border border-gray-200 rounded px-1 py-0.5 bg-gray-100 sku-variance-pcs"
                       value="0">
            </td>
            <td class="border px-2 py-1 text-right">
                <input type="number" readonly
                       class="w-full text-right border border-gray-200 rounded px-1 py-0.5 bg-gray-100 sku-variance-ucs"
                       value="0">
            </td>
        `;

        tr.querySelectorAll('.sku-target-pcs, .sku-target-ucs, .sku-actual-pcs, .sku-actual-ucs')
            .forEach(input => {
                input.addEventListener('input', () => recalcPerSkuRow(tr));
            });

        return tr;
    }

    /* ---------------------------------------------
     * BUILD TABLES FROM DEFAULT_ROWS
     * ------------------------------------------- */
    function buildDefaultTable() {
    if (!bodyEl && !inventoryBodyEl && !perSkuBodyEl) return;

    PRODUCTS.forEach((row, idx) => {
        if (bodyEl) {
            bodyEl.appendChild(createRow(row, idx));
        }
        if (inventoryBodyEl) {
            inventoryBodyEl.appendChild(createInventoryRow(row, idx));
        }
        if (perSkuBodyEl) {
            perSkuBodyEl.appendChild(createPerSkuRow(row, idx));
        }
        
    });
    document.querySelectorAll('#inventoryBody tr').forEach(tr => {
    recalcInventoryRow(tr);
});
    if (perSkuBodyEl) {
        perSkuBodyEl.querySelectorAll('tr').forEach(tr => recalcPerSkuRow(tr));
    }

    applyCoreRowFilter();
}

    function applyCoreRowFilter() {
        const keyword = (document.getElementById('core_search_keyword')?.value || '').trim().toLowerCase();

        const filterBodyRows = (tableBodyEl) => {
            if (!tableBodyEl) {
                return { visibleCount: 0, totalCount: 0 };
            }

            let visibleCount = 0;
            const rows = tableBodyEl.querySelectorAll('tr');

            rows.forEach(tr => {
                const packValue = (
                    tr.dataset.pack ||
                    tr.querySelector('input[name*="[pack]"]')?.value ||
                    ''
                ).toLowerCase();
                const productValue = (
                    tr.dataset.product ||
                    tr.querySelector('input[name*="[product]"]')?.value ||
                    ''
                ).toLowerCase();

                const matches = keyword === '' || packValue.includes(keyword) || productValue.includes(keyword);

                tr.classList.toggle('hidden', !matches);
                if (matches) visibleCount++;
            });

            return { visibleCount, totalCount: rows.length };
        };

        const coreStats = filterBodyRows(bodyEl);
        filterBodyRows(perSkuBodyEl);

        const resultEl = document.getElementById('core_search_result');
        if (!resultEl) return;

        if (keyword === '') {
            resultEl.classList.add('hidden');
            resultEl.innerText = '';
            return;
        }

        resultEl.classList.remove('hidden');
        resultEl.innerText = `${coreStats.visibleCount} of ${coreStats.totalCount} row(s) matched. (Applied to Products and Per SKU)`;
    }

    function clearCoreRowFilter() {
        const keywordInput = document.getElementById('core_search_keyword');
        if (keywordInput) keywordInput.value = '';

        applyCoreRowFilter();
        keywordInput?.focus();
    }

    function recalcPerSkuRow(tr) {
        const targetPcs = cleanNumber(tr.querySelector('.sku-target-pcs')?.value);
        const targetUcs = cleanNumber(tr.querySelector('.sku-target-ucs')?.value);
        const actualPcs = cleanNumber(tr.querySelector('.sku-actual-pcs')?.value);
        const actualUcs = cleanNumber(tr.querySelector('.sku-actual-ucs')?.value);

        const variancePcs = targetPcs - actualPcs;
        const varianceUcs = targetUcs - actualUcs;

        const varPcsEl = tr.querySelector('.sku-variance-pcs');
        const varUcsEl = tr.querySelector('.sku-variance-ucs');

        if (varPcsEl) varPcsEl.value = Number.isFinite(variancePcs) ? variancePcs.toFixed(0) : '0';
        if (varUcsEl) varUcsEl.value = Number.isFinite(varianceUcs) ? varianceUcs.toFixed(6) : '0';
    }



    /* ---------------------------------------------
     * PERFORMANCE TABLE CALCULATIONS
     * ------------------------------------------- */
    function recalcRow(tr) {
        const corePcs = cleanNumber(tr.querySelector('.core-pcs').value);
        const coreUcs = cleanNumber(tr.querySelector('.core-ucs').value);
        const iwsPcs  = cleanNumber(tr.querySelector('.iws-pcs').value);
        const iwsUcs  = cleanNumber(tr.querySelector('.iws-ucs').value);
        const srp     = cleanNumber(tr.dataset.srp || 0);

        const coreTotal = corePcs * coreUcs;
        const iwsTotal  = iwsPcs  * iwsUcs;
        const rowTotalAmount = (corePcs + iwsPcs) * srp;

        // Keep totals as 2 decimals, but do NOT round UCS inputs (they can be 6 decimals).
        tr.querySelector('.core-total').innerText = (isFinite(coreTotal) ? coreTotal : 0).toFixed(2);
        tr.querySelector('.iws-total').innerText  = (isFinite(iwsTotal) ? iwsTotal : 0).toFixed(2);
        tr.querySelector('.core-total-peso').innerText = (isFinite(rowTotalAmount) ? rowTotalAmount : 0).toFixed(2);
        tr.querySelector('.core-total-hidden').value = (isFinite(coreTotal) ? coreTotal : 0).toFixed(6);
        tr.querySelector('.iws-total-hidden').value  = (isFinite(iwsTotal) ? iwsTotal : 0).toFixed(6);


        recalcTotals(); // update footer + actual + achievement
    }

//recalcTotals//
function recalcTotals() {
    if (!bodyEl) return;

    let totalActual = 0;
    let core = 0, petcsd = 0, stills = 0;
    let totalCorePcs = 0, totalIwsPcs = 0;
    let totalCoreUcs = 0, totalIwsUcs = 0;
    let totalCoreAmount = 0;

    bodyEl.querySelectorAll('tr').forEach(tr => {
        const corePcs = cleanNumber(tr.querySelector('.core-pcs')?.value);
        const iwsPcs  = cleanNumber(tr.querySelector('.iws-pcs')?.value);
        const coreVal = cleanNumber(tr.querySelector('.core-total')?.innerText);
        const iwsVal  = cleanNumber(tr.querySelector('.iws-total')?.innerText);
        const amountVal = cleanNumber(tr.querySelector('.core-total-peso')?.innerText);
        const rowTotal = coreVal + iwsVal;

        totalCorePcs += corePcs;
        totalIwsPcs  += iwsPcs;
        totalCoreUcs += coreVal;
        totalIwsUcs  += iwsVal;
        totalCoreAmount += amountVal;

        totalActual += rowTotal;

        const category =
            tr.dataset.category ||
            tr.querySelector('.row-category')?.value ||
            '';

        if (category === 'core') core += rowTotal;
        else if (category === 'petcsd') petcsd += rowTotal;
        else if (category === 'stills') stills += rowTotal;
    });

    /* ==============================
       FOOTER TOTALS (TABLE)
    ============================== */
    const setFooterValue = (id, value, decimals = 2) => {
        const el = document.getElementById(id);
        if (!el) return;
        const safe = Number.isFinite(value) ? value : 0;
        el.innerText = safe.toLocaleString('en-PH', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    };

    setFooterValue('total_core_pcs', totalCorePcs, 0);
    setFooterValue('total_core_ucs', totalCoreUcs, 2);
    setFooterValue('total_core_amount', totalCoreAmount, 2);
    setFooterValue('total_iws_pcs', totalIwsPcs, 0);
    setFooterValue('total_iws_ucs', totalIwsUcs, 2);

    /* ==============================
       WRITE ACTUALS TO UI
    ============================== */
    // CORE ACTUAL includes PET CSD actual.
    document.getElementById('core_actual').innerText   = formatPeso(core + petcsd);
    document.getElementById('petcsd_actual').innerText = formatPeso(petcsd);
    document.getElementById('stills_actual').innerText = formatPeso(stills);
/* ==============================
   CATEGORY COMPUTATIONS (FIXED)
   RULES:
   - Core target is core-only
   - PET CSD = ACTUAL ONLY (NO TARGET)
   - Stills = normal target comparison
============================== */

// âœ… CORE = core actual vs core target
 const coreTarget = cleanNumber(document.getElementById('core_target')?.value);
  const coreActualCombined = core + petcsd;
 
 const coreVariance = coreTarget - coreActualCombined;
 const coreAchievement = coreTarget > 0
     ? (coreActualCombined / coreTarget) * 100
     : 0;

 document.getElementById('core_variance').innerText =
     formatPeso(coreVariance);

 document.getElementById('core_achievement').innerText =
     coreAchievement.toFixed(2) + '%';

// ðŸŸ¡ PET CSD = actual vs petcsd target
 const petcsdTarget = cleanNumber(document.getElementById('petcsd_target')?.value);
 
  const petcsdVariance = petcsdTarget - petcsd;
  const petcsdAchievement = petcsdTarget > 0
      ? (petcsd / petcsdTarget) * 100
      : 0;

 document.getElementById('petcsd_variance').innerText =
     formatPeso(petcsdVariance);

 document.getElementById('petcsd_achievement').innerText =
     petcsdAchievement.toFixed(2) + '%';

// âœ… STILLS = actual vs stills target
 const stillsTarget = cleanNumber(document.getElementById('stills_target')?.value);
  const stillsVariance = stillsTarget - stills;
  const stillsAchievement = stillsTarget > 0
      ? (stills / stillsTarget) * 100
      : 0;

 document.getElementById('stills_variance').innerText =
     formatPeso(stillsVariance);

 document.getElementById('stills_achievement').innerText =
     stillsAchievement.toFixed(2) + '%';


    /* ==============================
       OVERALL ACTUAL
    ============================== */
    const actualEl = document.getElementById('actual_sales');
    if (actualEl) actualEl.value = formatPeso(totalActual);

    /* ==============================
       HEADER KPI
    ============================== */
    const target = cleanNumber(document.getElementById('target_sales')?.value);
    const achievement = target > 0 ? (totalActual / target) * 100 : 0;
    const variance = target - totalActual;

    document.getElementById('achievement_display').innerText =
        achievement.toFixed(2) + '%';

    document.getElementById('variance_display').innerText =
        formatPeso(variance);
}

    /* ---------------------------------------------
     * INVENTORY TABLE CALCULATIONS
     * ------------------------------------------- */
    function recalcInventoryRow(tr) {
        const v = sel => parseFloat(tr.querySelector(sel)?.value) || 0;

        const srp        = v('.inv-srp');
        const actualInv  = v('.inv-actual');
        const ads        = v('.inv-ads');
        const booking    = v('.inv-booking');
        const deliveries = v('.inv-deliveries');
        const routingP5  = v('.inv-routing-p5');
        const routing7   = v('.inv-routing-7');

        const pesoActual   = srp * actualInv;
        const daysActual   = ads > 0 ? (actualInv / ads) : 0;
        const daysBooking  = ads > 0 ? (booking / ads) : 0;
        const ptdTotal     = actualInv + booking + deliveries;
        const daysPtd      = ads > 0 ? (ptdTotal / ads) : 0;
        const estP5        = ads * routingP5;
        const afterP5      = ptdTotal - estP5;
        const pesoP5       = srp * afterP5;
        const daysAfterP5  = ads > 0 ? (ptdTotal / ads) : 0;
        const est7         = ads * routing7;
        const afterMonth   = afterP5 - est7;
        const daysMonth    = ads > 0 ? (afterMonth / ads) : 0;
        const pesoMonth    = srp * afterMonth;

        const setText = (sel, val, peso = false) => {
            const el = tr.querySelector(sel);
            if (!el) return;

            const safeVal = isFinite(val) ? val : 0;

            el.innerText = peso
                ? formatPeso(safeVal)
                : safeVal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        setText('.inv-peso-actual',  pesoActual, true);
        setText('.inv-days-actual',  daysActual);
        setText('.inv-days-booking', daysBooking);
        setText('.inv-ptd-total',    ptdTotal);
        setText('.inv-days-ptd',     daysPtd);
        setText('.inv-est-p5',       estP5, true);
        setText('.inv-after-p5',     afterP5);
        setText('.inv-peso-p5',      pesoP5);
        setText('.inv-days-after-p5',daysAfterP5);
        setText('.inv-est-7',        est7);
        setText('.inv-after-month',  afterMonth, true);
        setText('.inv-days-month',   daysMonth);
        setText('.inv-peso-month',   pesoMonth);

        recalcInventoryTotals();
    }

    function recalcInventoryTotals() {
        if (!inventoryBodyEl) return;

        let totalPesoActual = 0;
        let totalActual = 0;
        let totalBooking = 0;
        let totalDeliveries = 0;
        let totalPtd = 0;
        let totalEstP5 = 0;
        let totalAfterP5 = 0;
        let totalPesoP5 = 0;
        let totalEst7 = 0;
        let totalAfterMonth = 0;
        let totalPesoMonth = 0;

        inventoryBodyEl.querySelectorAll('tr').forEach(tr => {
            const gv = sel => parseFloat(tr.querySelector(sel)?.value) || 0;
            const gt = sel => parseFloat(tr.querySelector(sel)?.innerText) || 0;

            totalPesoActual  += gt('.inv-peso-actual');
            totalActual      += gv('.inv-actual');
            totalBooking     += gv('.inv-booking');
            totalDeliveries  += gv('.inv-deliveries');
            totalPtd         += gt('.inv-ptd-total');
            totalEstP5       += gt('.inv-est-p5');
            totalAfterP5     += gt('.inv-after-p5');
            totalPesoP5      += gt('.inv-peso-p5');
            totalEst7        += gt('.inv-est-7');
            totalAfterMonth  += gt('.inv-after-month');
            totalPesoMonth   += gt('.inv-peso-month');
        });

        const set = (id, val, peso = false) => {
            const el = document.getElementById(id);
            if (!el) return;

            const safeVal = isFinite(val) ? val : 0;

            el.innerText = peso
                ? formatPeso(safeVal)
                : safeVal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        set('inv_total_peso_actual',  totalPesoActual);
        set('inv_total_actual',       totalActual);
        set('inv_total_booking',      totalBooking);
        set('inv_total_deliveries',   totalDeliveries);
        set('inv_total_ptd',          totalPtd);
        set('inv_total_est_p5',       totalEstP5);
        set('inv_total_after_p5',     totalAfterP5);
        set('inv_total_peso_p5',      totalPesoP5);
        set('inv_total_est_7',        totalEst7, true);
        set('inv_total_after_month',  totalAfterMonth, true);
        set('inv_total_peso_month',   totalPesoMonth, true);
    }

    /* ---------------------------------------------
     * HEADER SUMMARY (Target / Actual / Variance)
     * ------------------------------------------- */
function recalcHeaderSummary() {
        const target   = cleanNumber(document.getElementById('target_sales')?.value);
        const actual   = cleanNumber(document.getElementById('actual_sales')?.value);
    

        const achievement = target > 0 ? (actual / target) * 100 : 0;
       const variance    = target - actual;

       const achDisp   = document.getElementById('achievement_display');
        const varDisp   = document.getElementById('variance_display');
        const achHidden = document.getElementById('achievement_pct');//
        const varHidden = document.getElementById('total_variance');//

       if (achDisp)   achDisp.innerText   = achievement.toFixed(2) + '%';
       if (varDisp)   varDisp.innerText   = formatPeso(variance);
       if (achHidden) achHidden.value     = achievement.toFixed(2);//
       if(varHidden) varHidden.value     = variance.toFixed(2);//
   }

   ['target_sales','actual_sales'].forEach(id => {
    const el = document.getElementById(id);
       if (el) {
            el.addEventListener('input', () => {
         recalcHeaderSummary();
        });
     }
    });

    /* ---------------------------------------------
     * CUSTOM TABLES
     * ------------------------------------------- */
    function addCustomTableFromData(tableData) {
        const container = document.getElementById('customTablesContainer');
        const idx = customTableIndex++;

        const card = document.createElement('div');
        card.className = "custom-table-wrapper border rounded-lg p-3 shadow-sm space-y-2";
        card.dataset.index = idx;

        const title = tableData?.title || '';

        card.innerHTML = `
            <div class="flex justify-between items-center">
                <input type="text"
                       class="custom-title border-b border-gray-300 text-sm font-semibold flex-1 mr-2 focus:ring-0"
                       placeholder="Table title (e.g. NSR Incentive)"
                       value="${title}">
                <button type="button"
                        class="text-xs text-red-600 hover:underline no-print"
                        onclick="this.closest('[data-index]').remove()">
                    Remove Table
                </button>
            </div>

            <div class="flex gap-2 mb-2 no-print">
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
                    <thead>
                        <tr></tr>
                    </thead>
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
                input.value = v;
                input.addEventListener('input', () => updateCustomTableFooter(table));
                td.appendChild(input);
                tr.appendChild(td);
            });
            table.tBodies[0].appendChild(tr);
        });

        updateCustomTableFooter(table);
    }

    function addCustomTable() {
        addCustomTableFromData(null);
    }

    function addCustomColumn(idx) {
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
    }

    function removeCustomColumn(idx) {
        const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
        if (!table) return;
        const headerRow = table.tHead.rows[0];
        if (headerRow.cells.length <= 1) return;

        headerRow.deleteCell(headerRow.cells.length - 1);
        Array.from(table.tBodies[0].rows).forEach(tr => {
            tr.deleteCell(tr.cells.length - 1);
        });

        updateCustomTableFooter(table);
    }

    function addCustomRow(idx) {
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
    }

    function removeCustomRow(idx) {
        const table = document.querySelector(`table.custom-table[data-table-index="${idx}"]`);
        if (!table) return;
        const body = table.tBodies[0];
        if (body.rows.length <= 1) return;
        body.deleteRow(body.rows.length - 1);

        updateCustomTableFooter(table);
    }

    function updateCustomTableFooter(table) {
        if (!table) return;
        const headerRow = table.tHead.rows[0];
        const colCount = headerRow ? headerRow.cells.length : 0;
        if (!colCount) return;

        let tfoot = table.tFoot;
        if (!tfoot) {
            tfoot = table.createTFoot();
        }
        let row = tfoot.rows[0];
        if (!row) {
            row = tfoot.insertRow();
            row.className = 'bg-black text-white text-[11px] md:text-xs';
        }

        while (row.cells.length > colCount) {
            row.deleteCell(-1);
        }
        while (row.cells.length < colCount) {
            const cell = row.insertCell();
            cell.className = 'border px-2 py-1 text-right font-semibold';
        }

        const tbody = table.tBodies[0];

        for (let col = 0; col < colCount; col++) {
            let sum = 0;
            Array.from(tbody.rows).forEach(r => {
                const input = r.cells[col]?.querySelector('input');
                if (input) {
                    sum += cleanNumber(input.value);
                }
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
    // 🔥 PAGE IS ALREADY LOADED — RUN IMMEDIATELY
buildDefaultTable();

const coreSearchBtn = document.getElementById('core_search_btn');
const coreSearchClearBtn = document.getElementById('core_search_clear_btn');
const coreSearchKeywordInput = document.getElementById('core_search_keyword');

coreSearchBtn?.addEventListener('click', applyCoreRowFilter);
coreSearchClearBtn?.addEventListener('click', clearCoreRowFilter);

coreSearchKeywordInput?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        applyCoreRowFilter();
    }
});

    // Populate saved custom tables (edit mode).
    if (typeof SAVED_CUSTOM_TABLES !== 'undefined' && Array.isArray(SAVED_CUSTOM_TABLES) && SAVED_CUSTOM_TABLES.length) {
        SAVED_CUSTOM_TABLES.forEach(t => addCustomTableFromData(t));
    }


 //document.addEventListener('DOMContentLoaded', () => {

        // ðŸ”¥ FORCE COMPUTE FOR EDIT MODE
        //document.querySelectorAll('#coreIwsBody tr').forEach(tr => {
            //recalcRow(tr);
        //});

    //});

// ðŸ”¥ FORCE INITIAL CALCULATION
document.querySelectorAll('#coreIwsBody tr').forEach(tr => {
    recalcRow(tr);
});
function forceRecomputeHeader() {
    let actual = 0;

    document.querySelectorAll('#coreIwsBody tr').forEach(tr => {
        actual += cleanNumber(tr.querySelector('.core-total')?.innerText);
        actual += cleanNumber(tr.querySelector('.iws-total')?.innerText);
    });

    // FORCE actual sales
    const actualField = document.getElementById('actual_sales');
    if (actualField) actualField.value = formatPeso(actual);

    const target = cleanNumber(document.getElementById('target_sales')?.value);
    const achievement = target > 0 ? (actual / target) * 100 : 0;
    const variance = target - actual;

    document.getElementById('achievement_display').innerText =
        achievement.toFixed(2) + '%';

    document.getElementById('variance_display').innerText =
        formatPeso(variance);
}

</script>
<script>
/* =========================================
   ðŸ”¥ HARD OBSERVER â€” SINGLE SOURCE OF TRUTH
========================================= */

function recomputeFromTable() {
    let actual = 0;

    document.querySelectorAll('#coreIwsBody tr').forEach(tr => {
        actual += cleanNumber(tr.querySelector('.core-total')?.innerText);
        actual += cleanNumber(tr.querySelector('.iws-total')?.innerText);
    });

    const actualEl = document.getElementById('actual_sales');
    if (actualEl) actualEl.value = formatPeso(actual);

    const target = cleanNumber(document.getElementById('target_sales')?.value);
    const achievement = target > 0 ? (actual / target) * 100 : 0;
    const variance = target - actual;

    document.getElementById('achievement_display').innerText =
        achievement.toFixed(2) + '%';

    document.getElementById('variance_display').innerText =
        formatPeso(variance);
}

function recalcTargetSales() {
    const core   = num(document.getElementById('core_target')?.value);
    const stills = num(document.getElementById('stills_target')?.value);

    const total = core + stills;

    const totalEl = document.getElementById('target_sales');
    if (totalEl) totalEl.value = formatPeso(total);

    // recompute header KPIs immediately
    recalcHeaderSummary();
}

['core_target','petcsd_target','stills_target'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', recalcTargetSales);
});


/* ðŸ”¥ WATCH TOTAL UCS CELLS DIRECTLY */
//const observer = new MutationObserver(recomputeFromTable);

//function attachObservers() {
    //document.querySelectorAll('.core-total, .iws-total').forEach(cell => {
        //observer.observe(cell, {
            //childList: true,
            //characterData: true,
            //subtree: true
        //});
    //});
//}

/* ðŸ”¥ INITIALIZE */
//document.addEventListener('DOMContentLoaded', () => {
    //attachObservers();
    //recomputeFromTable();
//});


function num(v) {
    // Input values are formatted like "20,000.00" so we must strip commas first.
    if (typeof cleanNumber === 'function') return cleanNumber(v);
    return parseFloat(String(v ?? '').replace(/,/g, '')) || 0;
}

function recomputeTargetSales() {
    const core   = num(document.getElementById('core_target')?.value);
    const stills = num(document.getElementById('stills_target')?.value);

    // Overall target excludes PET CSD.
    const total = core + stills;

    const targetEl = document.getElementById('target_sales');
    if (targetEl) {
        targetEl.value = formatPeso(total);
    }

    recalcHeaderSummary();
}


// ðŸ”¥ listen to all 3 target inputs
['core_target', 'petcsd_target', 'stills_target'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', recomputeTargetSales);
});

// ðŸ”¥ run once on load (edit mode / refresh)
recomputeTargetSales();

</script>
<script>
function fetchTargets() {
    const branch     = document.querySelector('select[name="branch"]')?.value;
    const periodNo   = document.getElementById('period_no')?.value;
    const reportDate = document.querySelector('input[name="report_date"]')?.value;

    if (!branch || !periodNo || !reportDate) return;

    fetch(`/admin/period-targets/show?branch=${branch}&period_no=${periodNo}&report_date=${reportDate}`)
        .then(res => res.json())
        .then(data => {
            if (!data) {
                // reset if no target found
                setTargetValues(0, 0, 0);
                return;
            }

           setTargetValues(
                data.core_only_target,
                data.petcsd_target,
                data.stills_target
            );

        })
        .catch(err => console.error('Target load failed', err));
}

function setTargetValues(coreTotal, petcsd, stills) {
    // Core target is core-only.
    document.getElementById('core_target').value   = formatPeso(coreTotal);
    document.getElementById('petcsd_target').value = formatPeso(petcsd);
    document.getElementById('stills_target').value = formatPeso(stills);

    const total = Number(coreTotal) + Number(stills);
    document.getElementById('target_sales').value = formatPeso(total);

    recalcTotals();
}


/* ============================
   ðŸ”¥ AUTO-LOAD TRIGGERS
============================ */
document.addEventListener('DOMContentLoaded', fetchTargets);

function reloadReportContext() {
    const branch   = document.querySelector('select[name="branch"]')?.value;
    const periodNo = document.getElementById('period_no')?.value;
    const reportDate = document.querySelector('input[name="report_date"]')?.value;

    if (!branch || !periodNo || !reportDate) return;

    const url = new URL(window.location.href);
    url.searchParams.set('branch', branch);
    url.searchParams.set('period_no', periodNo);
    url.searchParams.set('report_date', reportDate);
    url.searchParams.delete('report_id');

    window.location.href = url.toString();
}

// When the user changes Branch/Period, reload the page so we can load the saved report
// (items + computed Actual/Variance/Achievement) instead of resetting to zero.
document.querySelector('select[name="branch"]')
    ?.addEventListener('change', reloadReportContext);

document.getElementById('period_no')
    ?.addEventListener('change', reloadReportContext);

document.querySelector('input[name="report_date"]')
    ?.addEventListener('change', reloadReportContext);

// Keep the products editable for today's encoding.
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views/admin/reports/periods/create.blade.php ENDPATH**/ ?>