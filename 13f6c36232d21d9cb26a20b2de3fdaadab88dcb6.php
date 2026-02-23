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
        <h2 class="text-2xl font-semibold text-facebookBlue">📊 Sales Reports</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow">

        <!-- Expand/Collapse Filters -->
        <div x-data="{ open: true }" class="mb-4">
            <button @click="open = !open" type="button" class="text-sm text-blue-600 underline mb-2">
                <template x-if="open">🔽 Hide Filters</template>
                <template x-if="!open">▶️ Show Filters</template>
            </button>

            <form x-show="open" x-data="{ loading: false }" @submit="loading = true"
                  method="GET" action="<?php echo e(route('admin.reports.index')); ?>"
                  class="flex flex-wrap gap-2 items-end">
                <!-- Month -->
                <div>
                    <label for="month" class="block text-sm font-medium">Month</label>
                    <select name="month" id="month" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                        <option value="all" <?php echo e(request('month') == 'all' ? 'selected' : ''); ?>>All Months</option>
                        <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                                <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Week -->
                <div>
                    <label for="week" class="block text-sm font-medium">Week</label>
                    <select name="week" id="week" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                        <option value="all" <?php echo e(request('week') == 'all' ? 'selected' : ''); ?>>All Weeks</option>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(request('week') == $i ? 'selected' : ''); ?>>Week <?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Division -->
                <div>
                    <label for="division_id" class="block text-sm font-medium">Division</label>
                    <select name="division_id" id="division_id" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm">
                        <option value="">All Divisions</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($division->id); ?>" <?php echo e(request('division_id') == $division->id ? 'selected' : ''); ?>>
                                <?php echo e($division->division_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Report Type -->
                <div>
                    <label for="report_type" class="block text-sm font-medium">Report Type</label>
                    <select name="report_type" id="report_type" class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm" onchange="this.form.submit()">
                        <option value="standard" <?php echo e(request('report_type') === 'standard' ? 'selected' : ''); ?>>📊 Daily Sales Report</option>
                        <option value="treasury" <?php echo e(request('report_type') === 'treasury' ? 'selected' : ''); ?>>💰 Collection Report</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label for="perPage" class="block text-sm font-medium">Show</label>
                    <select name="perPage" id="perPage" onchange="this.form.submit()"class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm">
                        <option value="50" <?php echo e(request('perPage') == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(request('perPage') == 100 ? 'selected' : ''); ?>>100</option>
                        <option value="300" <?php echo e(request('perPage') == 300 ? 'selected' : ''); ?>>300</option>
                        <option value="all" <?php echo e(request('perPage') == 'all' ? 'selected' : ''); ?>>All</option>
                    </select>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" class="relative bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-700">
                        🔍 Filter
                        <div x-show="loading" class="absolute right-0 top-0 mt-1 mr-1 animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></div>
                    </button>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
<div class="mt-4 mb-6 flex justify-end space-x-3 print:hidden">
    
    <?php if(auth()->user()->role === 'admin'): ?>

<a href="<?php echo e(route('admin.reports.consolidated')); ?>"
   title="Open consolidated report summary"
   class="inline-flex items-center gap-3 
          bg-gradient-to-r from-purple-600 to-indigo-600
          hover:from-purple-700 hover:to-indigo-700 
          text-black font-semibold px-6 py-3 rounded-xl 
          transition-all duration-200 border border-white/20">

    
    <svg xmlns="http://www.w3.org/2000/svg" 
         class="w-6 h-6"
         viewBox="0 0 24 24" 
         fill="none" 
         stroke="currentColor" 
         stroke-width="2" 
         stroke-linecap="round" 
         stroke-linejoin="round">
        <path d="M3 3v18h18"></path>
        <path d="m19 9-5 5-4-4-3 3"></path>
    </svg>

    <span class="text-lg tracking-wide">Consolidated Reports</span>
</a>

        
        <a href="<?php echo e(route('admin.reports.export.csv', request()->query())); ?>"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M12 5v14"></path>
                <path d="m19 12-7 7-7-7"></path>
            </svg>
            Export CSV
        </a>

    <?php endif; ?>

    
    <a href="<?php echo e(route('admin.reports.print', request()->query())); ?>"
       target="_blank"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="M6 9V2h12v7"></path>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <path d="M6 14h12v8H6z"></path>
        </svg>
        Print Preview
    </a>

</div>


        <!-- Period Message -->
        <?php if(request('month') && request('month') !== 'all'): ?>
            <div class="text-sm text-gray-700 mb-2">
                Showing results for: <strong><?php echo e(\Carbon\Carbon::create()->month(request('month'))->format('F')); ?></strong>
                <?php if(request('week') && request('week') !== 'all'): ?>
                    - <strong>Week <?php echo e(request('week')); ?></strong>
                <?php endif; ?>
            </div>
        <?php elseif(request('month') === 'all'): ?>
            <div class="text-sm text-gray-700 mb-2">
                Showing results for: <strong>Entire Year</strong>
            </div>
        <?php endif; ?>
        <!-- Table -->
        <div class="overflow-auto <?php echo e($inputs->count() > 20 ? 'max-h-[750px]' : ''); ?> border rounded">
            <table class="min-w-full table-auto border border-black text-sm">
                <?php
                    function sortLink($label, $field, $sortField, $sortOrder) {
                        $newOrder = ($sortField === $field && $sortOrder === 'asc') ? 'desc' : 'asc';
                        $arrow = $sortField === $field ? ($sortOrder === 'asc' ? ' ▲' : ' ▼') : '';
                        $url = request()->fullUrlWithQuery(['sort_by' => $field, 'order' => $newOrder]);
                        return "<a href=\"$url\" class=\"hover:underline\" style=\"color:#0047AB\">$label$arrow</a>";
                    }

                    $sortField = request('sort_by') ?? '';
                    $sortOrder = request('order') ?? '';
                    $overallTotalSales = $overallTotalRemittance = 0;
                    $totalCash = $totalIRS = $totalCheque = $totalCredit = $totalOverage = $totalShortage = $totalAR = $totalShortageCollection = 0;

                    $validatedTotal = $validatedOver = $validatedShort = 0;
                ?>

                <thead class="border-collapse">
                    <!-- ░ Row 1 – big group labels  ░ -->
                    <tr class="sticky top-0 z-40" style="height:44px; background:#cfe2ff;">
                        <th colspan="3"  class="border border-black px-3 py-2 text-center bg-[#cfe2ff]">
                            📌 Particulars
                        </th>

                        <th colspan="7"  class="border border-black px-3 py-2 text-center bg-[#d1e7dd]">
                            💵 Sales
                        </th>

                        <th colspan="6"  class="border border-black px-3 py-2 text-center bg-[#e2e3f3]">
                            💰 Collections
                        </th>

                        <th colspan="5" class="border border-black px-3 py-2 text-center bg-[#cff4fc]">
                            📋 Validation
                        </th>
                    </tr>

                    <!-- ░ Row 2 – actual column headers ░ -->
                    <tr class="sticky top-[44px] z-30" style="height:44px; background:#f8f9fa;">
                        <!-- frozen left column #1 -->
                        <th class="sticky left-0 z-30 border border-black px-3 py-2 text-left bg-white">
                            Division
                        </th>

                        <!-- frozen left column #2 -->
                        <th class="sticky left-[180px] z-20 border border-black px-3 py-2 text-left bg-white">
                            <?php echo sortLink('Business Line', 'business_line_id', $sortField, $sortOrder); ?>

                        </th>

                        <th class="border border-black px-3 py-2 text-left bg-[#f8f9fa]">
                            <?php echo sortLink('Date', 'date', $sortField, $sortOrder); ?>

                        </th>

                        <th class="border border-black px-2 py-1 text-right">Cash</th>
                        <th class="border border-black px-2 py-1 text-right">IRS</th>
                        <th class="border border-black px-2 py-1 text-right">Cheque</th>
                        <th class="border border-black px-2 py-1 text-right">Credit</th>
                        <th class="border border-black px-2 py-1 text-right">Overage</th>
                        <th class="border border-black px-2 py-1 text-right">Shortage</th>

                        <th class="border border-black px-2 py-1 text-right bg-[#d1e7dd] text-[#0f5132]">
                            Total Sales
                        </th>

                        <th class="border border-black px-2 py-1 text-right">Cash</th>
                        <th class="border border-black px-2 py-1 text-right">A/R</th>
                        <th class="border border-black px-2 py-1 text-right">Shortage Collection</th>
                        <th class="border border-black px-2 py-1 text-right">Overage</th>
                        <th class="border border-black px-2 py-1 text-right">Shortage</th>

                        <th class="border border-black px-2 py-1 text-right bg-[#e2e3f3] text-[#3f3f7f]">
                            Total Remittance
                        </th>
                        <th class="border border-black px-2 py-1 text-right">Validated Total</th>
                        <th class="border border-black px-2 py-1 text-right">Overage</th>
                        <th class="border border-black px-2 py-1 text-right">Shortage</th>
                        <th class="border border-black px-2 py-1 text-left">Remarks</th>
                        <th class="border border-black px-2 py-1 text-center">Receipt</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $columnTitles = [
                            'division' => 'Division',
                            'business_line' => 'Business Line',
                            'date' => 'Date',
                            'cash_sales' => 'Cash Sales',
                            'irs_sales' => 'IRS Sales',
                            'cheque_sales' => 'Cheque Sales',
                            'credit_sales' => 'Credit Sales',
                            'overage' => 'Overage',
                            'shortage' => 'Shortage',
                            'total_sales' => 'Total Sales',
                            'cash_collection' => 'Cash Collection',
                            'ar_collection' => 'A/R Collection',
                            'shortage_collection' => 'Shortage Collection',
                            'remittance_overage' => 'Remittance Overage',
                            'remittance_shortage' => 'Remittance Shortage',
                            'total_remittance' => 'Total Remittance',
                            'validated_total' => 'Validated Total',
                            'validated_overage' => 'Validated Overage',
                            'validated_shortage' => 'Validated Shortage',
                            'remarks' => 'Remarks',
                            'receipt' => 'Validation Receipt',
                        ];
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $cash = $irs = $cheque = $credit = $overage = $shortage = $arCollections = $shortageCollection = 0;
                            foreach ($input->items as $item) {
                                $label = strtolower($item->field_label);
                                $value = is_numeric($item->value) ? $item->value : 0;
                                match($label) {
                                    'cash sales' => $cash += $value,
                                    'irs sales' => $irs += $value,
                                    'cheque sales' => $cheque += $value,
                                    'credit sales' => $credit += $value,
                                    'cash overage' => $overage += $value,
                                    'cash shortage' => $shortage += $value,
                                    'ar collections' => $arCollections += $value,
                                    'collection on shortages' => $shortageCollection += $value,
                                    default => null
                                };
                            }
                            $totalSales = $cash + $irs + $cheque + $credit + $overage - $shortage;
                            $totalRemittance = $cash + $arCollections + $shortageCollection + $overage - $shortage;
                            $overallTotalSales += $totalSales;
                            $overallTotalRemittance += $totalRemittance;
                            $totalCash += $cash;
                            $totalIRS += $irs;
                            $totalCheque += $cheque;
                            $totalCredit += $credit;
                            $totalOverage += $overage;
                            $totalShortage += $shortage;
                            $totalAR += $arCollections;
                            $totalShortageCollection += $shortageCollection;
                        ?>
                        <tr x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" :style="hover ? 'background-color: #fff3cd;' : ''" class="text-right">
                                <td class="sticky left-0 z-20 border border-black px-3 py-2 text-left" 
                                    :style="hover ? 'background-color: #fff3cd;' : 'background-color: #ffffff;'" 
                                    title="<?php echo e($columnTitles['division']); ?>">
                                    <?php echo e($input->division->division_name); ?>

                                </td>

                                <td class="sticky left-[150px] z-20 border border-black px-3 py-2 text-left" 
                                    :style="hover ? 'background-color: #fff3cd;' : 'background-color: #ffffff;'" 
                                    title="<?php echo e($columnTitles['business_line']); ?>">
                                    <?php echo e($input->businessLine->name); ?>

                                </td>

                                <td class="border border-black px-3 py-2 text-left" title="<?php echo e($columnTitles['date']); ?>">
                                    <?php echo e(\Carbon\Carbon::parse($input->date)->format('F j, Y')); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['cash_sales']); ?>">
                                    <?php echo e(number_format($cash, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['irs_sales']); ?>">
                                    <?php echo e(number_format($irs, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['cheque_sales']); ?>">
                                    <?php echo e(number_format($cheque, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['credit_sales']); ?>">
                                    <?php echo e(number_format($credit, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['overage']); ?>">
                                    <?php echo e(number_format($overage, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['shortage']); ?>">
                                    <?php echo e(number_format($shortage, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right text-green-700 font-semibold" 
                                    title="<?php echo e($columnTitles['total_sales']); ?>">
                                    <?php echo e(number_format($totalSales, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['cash_collection']); ?>">
                                    <?php echo e(number_format($cash, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['ar_collection']); ?>">
                                    <?php echo e(number_format($arCollections, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['shortage_collection']); ?>">
                                    <?php echo e(number_format($shortageCollection, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['remittance_overage']); ?>">
                                    <?php echo e(number_format($overage, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right" title="<?php echo e($columnTitles['remittance_shortage']); ?>">
                                    <?php echo e(number_format($shortage, 2)); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-right text-indigo-700 font-semibold" 
                                    title="<?php echo e($columnTitles['total_remittance']); ?>">
                                    <?php echo e(number_format($totalRemittance, 2)); ?>

                                </td>

                            <?php
                                $vr = \App\Models\ValidatedRemittance::where('division_id', $input->division_id)
                                    ->whereDate('date', \Carbon\Carbon::parse($input->date)->format('Y-m-d'))
                                    ->first();
                            ?>
                            <?php
                                if ($vr) {
                                    $validatedTotal += $vr->validated_amount ?? 0;
                                    $validatedOver  += $vr->validated_overage ?? 0;
                                    $validatedShort += $vr->validated_shortage ?? 0;
                                }
                            ?>

                                <td class="border border-black px-2 py-1 text-blue-800 font-medium" title="<?php echo e($columnTitles['validated_total']); ?>">
                                    <?php echo e($vr ? number_format($vr->validated_amount, 2) : '-'); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['validated_overage']); ?>">
                                    <?php echo e($vr && $vr->validated_overage > 0 ? number_format($vr->validated_overage, 2) : '-'); ?>

                                </td>

                                <td class="border border-black px-2 py-1 text-red-700" title="<?php echo e($columnTitles['validated_shortage']); ?>">
                                    <?php echo e($vr && $vr->validated_shortage > 0 ? number_format($vr->validated_shortage, 2) : '-'); ?>

                                </td>

                                <td class="border border-black px-2 py-1" title="<?php echo e($columnTitles['remarks']); ?>">
                                    <?php echo e($vr?->remarks ?? '-'); ?>

                                </td>
                            <td class="border border-black px-2 py-1 text-center">
                                <?php if($vr && $vr->validation_receipt): ?>
                                    <button onclick="showReceiptModal('<?php echo e($vr->id); ?>')" class="text-blue-600 underline">
                                        📎 View
                                    </button>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="16" class="border border-black px-4 py-4 text-center text-gray-600">No data found for this period.</td>
                        </tr>
                    <?php endif; ?>

                    <!-- Totals Row -->
                    <tr class="bg-gray-100 font-bold text-right">
                        <td colspan="3" class="border border-black px-3 py-2 text-left">🔢 Column Totals</td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['cash_sales']); ?>">
                            <?php echo e(number_format($totalCash, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['irs_sales']); ?>">
                            <?php echo e(number_format($totalIRS, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['cheque_sales']); ?>">
                            <?php echo e(number_format($totalCheque, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['credit_sales']); ?>">
                            <?php echo e(number_format($totalCredit, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['overage']); ?>">
                            <?php echo e(number_format($totalOverage, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['shortage']); ?>">
                            <?php echo e(number_format($totalShortage, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['total_sales']); ?>">
                            <?php echo e(number_format($overallTotalSales, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['cash_collection']); ?>">
                            <?php echo e(number_format($totalCash, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['ar_collection']); ?>">
                            <?php echo e(number_format($totalAR, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['shortage_collection']); ?>">
                            <?php echo e(number_format($totalShortageCollection, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['remittance_overage']); ?>">
                            <?php echo e(number_format($totalOverage, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['remittance_shortage']); ?>">
                            <?php echo e(number_format($totalShortage, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-indigo-700" title="<?php echo e($columnTitles['total_remittance']); ?>">
                            <?php echo e(number_format($overallTotalRemittance, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-blue-800" title="<?php echo e($columnTitles['validated_total']); ?>">
                            <?php echo e(number_format($validatedTotal, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-green-700" title="<?php echo e($columnTitles['validated_overage']); ?>">
                            <?php echo e(number_format($validatedOver, 2)); ?>

                        </td>

                        <td class="border border-black px-2 py-1 text-red-700" title="<?php echo e($columnTitles['validated_shortage']); ?>">
                            <?php echo e(number_format($validatedShort, 2)); ?>

                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($inputs instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
            <div class="mt-4">
                <?php echo e($inputs->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Scroll-to-Top Button -->
    <div x-data="{ visible: false }" x-init="window.addEventListener('scroll', () => { visible = window.scrollY > 200 })"
         class="fixed bottom-6 right-6 z-50">
        <button x-show="visible" @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="bg-blue-600 text-white rounded-full p-3 shadow-lg hover:bg-blue-700 transition">
            ⬆️
        </button>
    </div>

    <!-- 📎 Receipt Modals -->
    <?php $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $vr = \App\Models\ValidatedRemittance::where('division_id', $input->division_id)
                ->whereDate('date', \Carbon\Carbon::parse($input->date)->format('Y-m-d'))
                ->first();
        ?>

        <?php if($vr && $vr->validation_receipt): ?>
            <div id="modal-<?php echo e($vr->id); ?>" 
                class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden z-50"
                onclick="handleBackdropClick(event, '<?php echo e($vr->id); ?>')">

                <div class="relative bg-white p-4 rounded shadow-lg w-1/2 h-1/2 flex items-center justify-center"
                    onclick="event.stopPropagation()">

                    <button onclick="closeModal('<?php echo e($vr->id); ?>')" 
                            class="absolute top-2 right-2 text-red-600 text-xl font-bold hover:text-red-800">
                        ✖
                    </button>

                    <img src="<?php echo e(asset($vr->validation_receipt)); ?>"
                        alt="Validation Receipt"
                        class="max-h-full max-w-full object-contain mx-auto" />
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<script>
function showReceiptModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
}

function handleBackdropClick(event, id) {
    const modal = document.getElementById('modal-' + id);
    if (event.target === modal) {
        closeModal(id);
    }
}
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\index.blade.php ENDPATH**/ ?>