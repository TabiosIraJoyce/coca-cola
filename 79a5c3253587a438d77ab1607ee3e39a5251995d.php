<?php if (isset($component)) { $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da = $component; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
   <?php
    $totalRemittance   = $totalRemittance   ?? 0;
    $totalReceivables  = $totalReceivables  ?? 0;
    $netBorrowed       = $netBorrowed       ?? 0;

    $collectionsByCustomer     = $collectionsByCustomer     ?? [];
    $lastPaymentDateByCustomer = $lastPaymentDateByCustomer ?? [];
?>

<?php
    $accountReceivables     = $accountReceivables     ?? collect();
    $receivableCollections = $receivableCollections ?? collect();
    $stockTransfers        = $stockTransfers        ?? collect();
    $shortageCollections   = $shortageCollections   ?? collect();

    $receipts    = $receipts    ?? collect();
    $remittances = $remittances ?? collect();
    $receivables = $receivables ?? collect();
    $borrowers   = $borrowers   ?? collect();

    $banks     = $banks     ?? collect();
    $divisions = $divisions ?? collect();

    $hasFilter  = $hasFilter  ?? false;
    $divisionId = $divisionId ?? null;
    $activeReportType = $activeReportType ?? request('report_type');

    // "Show entries per section" (details tables only)
    $perPage = (int) request('per_page', 10);
    $perPage = in_array($perPage, [10, 20, 30, 50, 100], true) ? $perPage : 10;

    // âœ… FIX: compute totals safely
    $totalBorrowed = $borrowers
        ->flatMap->items
        ->sum('borrowed');

    $totalReturned = $borrowers
        ->flatMap->items
        ->sum('returned');
?>
<?php
    $totalGrossSales = $receipts
        ->flatMap->items
        ->sum('gross_sales');
?>


<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

    <?php if(session('success')): ?>
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 3500)"
            x-show="show"
            x-transition
            class="fixed top-6 right-6 z-50 max-w-sm w-full"
        >
            <div class="flex items-center gap-3 rounded-xl bg-green-600 px-5 py-4 text-white shadow-xl">
                <i data-lucide="check-circle" class="w-6 h-6"></i>

                <div class="flex-1 text-sm font-medium">
                    <?php echo e(session('success')); ?>

                </div>

                <button @click="show = false" class="hover:opacity-80">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        />

     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-blue-700">
                GMC Enterprises Report
            </h1>

            <div class="flex items-center gap-3">

                
                <a href="<?php echo e(route('admin.reports.add.select-division')); ?>"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 flex items-center gap-2">
                    <i data-lucide="file-plus" class="w-4 h-4"></i>
                    Add Report
                </a>


                
                <?php if($hasFilter): ?>
                <div class="flex items-center gap-2">

                    
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 flex items-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            Export
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow text-sm z-50">

                            <a href="<?php echo e(route('admin.reports.consolidated.export.pdf', request()->all())); ?>"
                            class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                PDF
                            </a>

                            <a href="<?php echo e(route('admin.reports.consolidated.export.csv', request()->all())); ?>"
                            class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                                CSV
                            </a>
                        </div>
                    </div>

                    
                    <a href="<?php echo e(route('admin.reports.consolidated.print', request()->all())); ?>"
                    target="_blank"
                    class="px-4 py-2 bg-blue-700 text-white rounded-lg flex items-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        Print Preview
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
 <?php $__env->endSlot(); ?>


<div class="mt-6 bg-gray-300 rounded-xl p-6">


<div class="bg-white rounded-lg p-6">


<form method="GET" 
      action="<?php echo e(route('admin.reports.consolidated')); ?>"
      class="d-flex align-items-center justify-content-center my-4 gap-2 flex-wrap">

    
    <div>
        <label class="block text-sm font-semibold text-gray-600">Division</label>
        <select name="division_id" class="mt-1 w-full rounded border-gray-300">
            <option value="">All</option>

            <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(in_array($division->division_name, [
                    'Gledco Enterprise - Batac',
                    'Gledco Enterprise - Laoag',
                    'Gledco Enterprise - Solsona'
                ])): ?>
                    <option value="<?php echo e($division->id); ?>"
                        <?php echo e((int)$divisionId === (int)$division->id ? 'selected' : ''); ?>>
                        <?php echo e($division->division_name); ?>

                    </option>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    
    <div>
        <label class="block text-sm font-semibold text-gray-600">Report Type</label>
        <select name="report_type" class="mt-1 w-full rounded border-gray-300">
            <option value="">All Types</option>
            <option value="receipts" <?php echo e($activeReportType === 'receipts' ? 'selected' : ''); ?>>Receipts</option>
            <option value="remittance" <?php echo e($activeReportType === 'remittance' ? 'selected' : ''); ?>>Remittance</option>
            <option value="receivables" <?php echo e($activeReportType === 'receivables' ? 'selected' : ''); ?>>Receivables</option>
            <option value="borrowers" <?php echo e($activeReportType === 'borrowers' ? 'selected' : ''); ?>>Borrowers</option>
        </select>
    </div>

    
    <div>
        <label class="block text-sm font-semibold text-gray-600">Date From</label>
        <input type="date"
               name="date_from"
               value="<?php echo e(request('date_from')); ?>"
               class="mt-1 w-full rounded border-gray-300">
    </div>

    
    <div>
        <label class="block text-sm font-semibold text-gray-600">Date To</label>
        <input type="date"
               name="date_to"
               value="<?php echo e(request('date_to')); ?>"
               class="mt-1 w-full rounded border-gray-300">
    </div>

    
    <div>
        <label class="block text-sm font-semibold text-gray-600">
            Show entries per section
        </label>
       <select name="per_page" class="mt-1 w-full rounded border-gray-300">
        <?php $__currentLoopData = [10,20,30,50,100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($size); ?>"
                <?php echo e(request('per_page', 10) == $size ? 'selected' : ''); ?>>
                <?php echo e($size); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    </div>

    
    <div class="flex items-end">
        <button type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i>
            Filter
        </button>
    </div>

</form>


<div class="grid grid-cols-1 md:grid-cols-5 gap-6 text-center">

    <!-- Total Remittance (Cream) -->
    <div class="p-4 rounded shadow" style="background-color:#F5F1E1;">
        <div class="text-sm text-gray-600">Total Remittance</div>
        <div class="text-xl font-bold text-blue-700">
            <?php echo e(number_format($receipts->sum(fn ($r) => $r->items->sum('total_remittance')), 2)); ?>

        </div>
    </div>
    <!-- Total Gross Sales (Green) -->
<div class="p-4 rounded shadow" style="background-color:#DFF3E3;">
    <div class="text-sm text-gray-600">Total Gross Sales</div>
    <div class="text-xl font-bold text-green-700">
        <?php echo e(number_format($totalGrossSales, 2)); ?>

    </div>
</div>

<!-- TOTAL CASES -->
<div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
    <p class="text-sm text-purple-600 font-medium">Total Cases</p>
    <p class="text-3xl font-bold text-purple-700">
        <?php echo e(number_format($receipts->sum(fn ($r) => $r->items->sum('total_cases')))); ?>

    </p>
</div>


    <!-- Total Remitted (Coral) -->
    <div class="p-4 rounded shadow" style="background-color:#E67F63;">
        <div class="text-sm text-white">Total Remitted</div>
        <div class="text-xl font-bold text-white">
            <?php
                $remitChecks = $remittances->sum(fn ($r) => $r->items->where('type', 'check')->sum('amount'));
                $remitCash   = $remittances->sum(fn ($r) => $r->items->where('type', 'cash')->sum('amount'));
            ?>
            <?php echo e(number_format($remitChecks + $remitCash, 2)); ?>

        </div>
        <div class="text-xs text-white/90 mt-1">
            Cash:<?php echo e(number_format($remitCash, 2)); ?>

        </div>
        <div class="text-xs text-white/90">
            Checks:<?php echo e(number_format($remitChecks, 2)); ?>

        </div>
    </div>

    <!-- Total Receivables (Navy) -->
    <div class="p-4 rounded shadow" style="background-color:#3E415E;">
        <div class="text-sm text-gray-200">Total Receivables</div>
        <div class="text-xl font-bold text-white">
            <?php echo e(number_format($totalReceivables ?? 0, 2)); ?>

        </div>
    </div>

    <!-- Borrowers (Sage + Sand container) -->
    <div class="h-40 rounded shadow flex flex-col items-center justify-center mb-4"
         style="background-color:#8DB9A3;">
        <div class="grid grid-cols-2 gap-6 text-center">
            <div>
                <p class="text-sm text-gray-700">Total Borrowed</p>
                <p class="text-2xl font-bold text-[#3E415E]">
                    <?php echo e(number_format($totalBorrowed)); ?>

                </p>
            </div>

            <div>
                <p class="text-sm text-gray-700">Total Returned</p>
                <p class="text-2xl font-bold text-[#3E415E]">
                    <?php echo e(number_format($totalReturned)); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<?php
    $topRoutesSorted = collect($topRoutes ?? [])
        ->sortByDesc('total_sales')
        ->take($perPage)
        ->values();

    $routeLabels = $topRoutesSorted->pluck('route');
    $routeSales  = $topRoutesSorted->pluck('total_sales');
?>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

    
    <div class="bg-white p-5 rounded-lg shadow border">
        <h3 class="text-lg font-semibold text-blue-700 mb-3">
            Sales Trend Over Time
        </h3>
        <canvas id="salesTrendChart" height="120"></canvas>
    </div>

    
    <div class="bg-white p-5 rounded-lg shadow border">
    <h3 class="text-lg font-semibold text-emerald-700 mb-3">
        Top <?php echo e($topRoutesSorted->count()); ?> Routes by Sales
    </h3>

    <!-- FIX: give the chart a height -->
    <div style="height:300px;">
        <canvas id="topRoutesChart"></canvas>
    </div>
</div>


    
    <div class="bg-white p-5 rounded-lg shadow border lg:col-span-2">
        <h3 class="text-lg font-semibold text-orange-700 mb-3">
            Receivables Distribution
        </h3>
        <div class="flex justify-center">
            <div style="height:280px; width:280px;">
                <canvas id="receivablesPieChart"></canvas>
            </div>
        </div>
    </div>
</div>



<!-- ================= SEARCH BAR ================= -->
<form method="GET"
      action="<?php echo e(route('admin.consolidated.global.search')); ?>"
      class="d-flex justify-content-center my-4 gap-2">

    <input type="hidden" name="per_page" value="<?php echo e(request('per_page', 10)); ?>">

    
    <select name="bank_id"
            class="form-select"
            style="max-width:220px;">
        <option value="">All Banks</option>

        <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($bank->id); ?>"
                <?php echo e(request('bank_id') == $bank->id ? 'selected' : ''); ?>>
                <?php echo e($bank->bank_name); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    
    <input type="text"
           name="search"
           class="form-control text-center"
           style="max-width:320px;"
           value="<?php echo e(request('search')); ?>"
           placeholder="Type name, route, date, or report type">

    <button class="btn btn-primary" type="submit">
         Search
    </button>
    <?php if(request()->anyFilled(['search','bank_id'])): ?>
        <a href="<?php echo e(route('admin.reports.consolidated')); ?>"
           class="btn btn-outline-secondary">
            Clear
        </a>
    <?php endif; ?>

</form>



<!-- ================= SEARCH RESULTS ================= -->
<div id="searchResults" class="mt-4"></div>


<div class="bg-blue-50 border rounded-lg p-5 mb-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">
        Set Monthly Route Target
    </h3>

    <form method="POST" action="<?php echo e(route('admin.route-targets.store')); ?>"
          class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <?php echo csrf_field(); ?>

        <select name="route"
                class="border rounded px-3 py-2"
                required>
            <option value="">Select Route</option>
            <option value="BODEGA">Bodega</option>
            <option value="CRS 1">CRS 1</option>
            <option value="CRS 2">CRS 2</option>
            <option value="CRS 3">CRS 3</option>
            <option value="OUTSIDE TOWN">Outside Town</option>
            <option value="WATER ROUTE">Water Route</option>
        </select>

        <input name="leadman"
               class="border rounded px-3 py-2"
               placeholder="Leadman"
               required>

        <select name="month" class="border rounded px-3 py-2">
            <?php for($p=1;$p<=12;$p++): ?>
                <option value="<?php echo e($p); ?>" <?php echo e($p == now()->month ? 'selected' : ''); ?>>
                    Period <?php echo e($p); ?>

                </option>
            <?php endfor; ?>
        </select>

        <input name="year"
               value="<?php echo e(now()->year); ?>"
               class="border rounded px-3 py-2">

        <input name="target_sales"
               class="border rounded px-3 py-2"
               placeholder="Target Sales"
               required>

        <input name="days_level"
               type="number"
               min="1"
               class="border rounded px-3 py-2"
               placeholder="Days Level"
               value="<?php echo e(old('days_level', 28)); ?>"
               required>

        <div class="md:col-span-6 text-right">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                Save Target
            </button>
        </div>
    </form>
</div>

<hr class="my-4">
<?php if(isset($routePerformance) && $routePerformance->isNotEmpty()): ?>
<div class="bg-fec3a6 p-5 rounded-lg shadow border mt-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-indigo-700">
            Route Performance (Monthly)
        </h3>
        <span class="text-sm text-gray-500">
            Based on current month progress
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm table-fixed report-table">
            <thead class="bg-orange-100">
                <tr>
                    <th class="px-3 py-2 text-left w-[180px]">Route</th>
                    <th class="px-3 py-2 text-left w-[220px]">Leadman</th>
                    <th class="px-3 py-2 text-right w-[160px]">Target Sales</th>
                    <th class="px-3 py-2 text-right w-[160px]">Actual Sales</th>
                    <th class="px-3 py-2 text-center w-[140px]">Achievement</th>
                     <th class="px-3 py-2 text-right w-[160px]">Variance</th>
                     <th class="px-3 py-2 text-right w-[180px]">Remaining Target/Day</th>
                     <th class="px-3 py-2 text-center w-[140px]">Remaining Days</th>
                     <th class="px-3 py-2 text-center w-[120px]">Days Level</th>
                     <th class="px-3 py-2 text-center w-[140px]">Remarks</th>
                 </tr>
             </thead>

            <tbody>
                <?php $__currentLoopData = $routePerformance->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-t">
                        
                        <td class="px-3 py-2 font-medium text-gray-800">
                            <?php echo e($row['route']); ?>

                        </td>

                        
                        <td class="px-3 py-2 text-gray-700">
                            <?php echo e($row['leadman']); ?>

                        </td>

                        
                        <td class="px-3 py-2 text-right font-semibold">
                           <?php if($row['target_sales'] > 0): ?>
                              <?php echo e(number_format($row['target_sales'], 2)); ?>

                            <?php else: ?>
                                <span class="text-gray-400 italic">No target</span>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-3 py-2 text-right font-semibold">
                            <?php echo e(number_format($row['actual_sales'], 2)); ?>

                        </td>

                        
                        <td class="px-3 py-2 text-center font-semibold">
                            <?php if($row['target_sales'] > 0): ?>
                                <?php echo e(number_format($row['achievement'], 2)); ?>%
                            <?php else: ?>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-3 py-2 text-right font-semibold">
                            <?php if($row['target_sales'] > 0): ?>
                               <?php echo e(number_format($row['variance'], 2)); ?>

                            <?php else: ?>
                            <?php endif; ?>
                        </td>

                        
                        <td class="px-3 py-2 text-right font-semibold">
                            <?php if($row['target_sales'] > 0): ?>
                                <?php echo e(number_format($row['remaining_target'], 2)); ?>

                            <?php else: ?>
                               
                            <?php endif; ?>
                         </td>
 
                        
                        <td class="px-3 py-2 text-center">
                            <?php if($row['target_sales'] > 0): ?>
                                <?php echo e($row['remaining_days'] ?? ''); ?>

                            <?php else: ?>
                                
                            <?php endif; ?>
                        </td>

                         
                         <td class="px-3 py-2 text-center">
                             <?php if($row['target_sales'] > 0): ?>
                                 <?php echo e($row['days_level']); ?>

                             <?php else: ?>
                                
                            <?php endif; ?>
                        </td>
                        
                        <td class="px-3 py-2 text-center">
                            <?php if($row['remarks'] === 'ON TRACK'): ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    ON TRACK
                                </span>
                                <?php elseif($row['remarks'] === 'NO TARGET'): ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">
                                    NO TARGET
                                </span>

                            <?php else: ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    OFF TRACK
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php if(isset($topRoutes) && $topRoutes->isNotEmpty()): ?>
<div class="bg-white p-5 rounded-lg shadow border mt-6">
    <h3 class="text-lg font-semibold text-emerald-700 mb-4">
        Top <?php echo e($topRoutes->take($perPage)->count()); ?> Routes & Leadmen (Highest Sales)
    </h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm report-table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2 text-left">Rank</th>
                    <th class="px-3 py-2 text-left">Route</th>
                    <th class="px-3 py-2 text-left">Leadman</th>
                    <th class="px-3 py-2 text-right">Total Cases</th>
                    <th class="px-3 py-2 text-right">Total Sales</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $topRoutes->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-t">
                    <td class="px-3 py-2 font-semibold">
                        <?php echo e($index + 1); ?>

                    </td>
                    <td class="px-3 py-2">
                        <?php echo e($row['route']); ?>

                    </td>
                    <td class="px-3 py-2">
                        <?php echo e($row['leadman']); ?>

                    </td>
                    <td class="px-3 py-2 text-right">
                        <?php echo e(number_format($row['total_cases'])); ?>

                    </td>
                    <td class="px-3 py-2 text-right font-semibold text-emerald-700">
                         <?php echo e(number_format($row['total_sales'], 2)); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>




<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (window.lucide) lucide.createIcons();
    });
</script>


<div id="defaultReportContent"
     class="space-y-6 mt-6"
     x-data="{ open: { receipts: true, remittance: true, receivables: true, borrowers: true } }">

     
<?php if(!$activeReportType || $activeReportType === 'receipts'): ?>

<div class="bg-white p-5 rounded-lg shadow border">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-lg text-blue-700">RECEIPTS</h3>
        <button @click="open.receipts = !open.receipts" class="text-sm font-semibold text-blue-600 hover:underline">
            <span x-text="open.receipts ? 'Hide Details' : 'View Details'"></span>
        </button>
    </div>

    
    <div class="h-40 bg-gray-100 flex flex-col items-center justify-center mb-4">
        <p class="text-sm text-gray-500">Total Remittance</p>
        <p class="text-2xl font-bold text-blue-700">
            <?php echo e(number_format($receipts->sum(fn ($r) => $r->items->sum('total_remittance')), 2)); ?>

        </p>
    </div>

    <div x-show="open.receipts">
        <?php if($receipts->isEmpty()): ?>
            <p class="text-center text-gray-500 italic py-6">
                No receipt records found for the selected filters.
            </p>
        <?php else: ?>
            <table class="min-w-full text-sm table-fixed report-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 text-left w-[200px]">Division</th>
                        <th class="px-2 py-2 text-left w-[120px]">Date</th>
                        <th class="px-2 py-2 text-left w-[160px]">Route</th>
                        <th class="px-2 py-2 text-left w-[220px]">Leadman</th>
                        <th class="px-2 py-2 text-right w-[140px]">Total Cases</th>
                        <th class="px-2 py-2 text-right w-[160px]">Total Gross Sales</th>
                        <th class="px-2 py-2 text-right w-[160px]">Total Remittance</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__currentLoopData = $receipts->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <?php
                            $divisionName = $r->division->division_name ?? 'UNASSIGNED DIVISION';
                        ?>

                        <tr>
                            <td class="px-2 py-2 w-[200px] font-semibold text-gray-700">
                                <?php echo e($divisionName); ?>

                            </td>
                            <td class="px-2 py-2 w-[120px]">
                                <?php echo e($r->report_date); ?>

                            </td>
                            <td class="px-2 py-2 w-[160px]">
                                <?php echo e($r->route); ?>

                            </td>
                            <td class="px-2 py-2 w-[220px] truncate">
                                <?php echo e($r->leadman ?? '-'); ?>

                            </td>
                            <td class="px-2 py-2 text-right w-[140px]">
                                <?php echo e(number_format($r->items->sum('total_cases'))); ?>

                            </td>
                            <td class="px-2 py-2 text-right w-[160px]">
                                <?php echo e(number_format($r->items->sum('gross_sales'), 2)); ?>

                            </td>
                            <td class="px-2 py-2 text-right w-[160px]">
                                <?php echo e(number_format($r->items->sum('total_remittance'), 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php if($receipts instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-sm text-muted">
                        Showing <?php echo e($receipts->firstItem()); ?> to <?php echo e($receipts->lastItem()); ?>

                        of <?php echo e($receipts->total()); ?> entries
                    </div>

                    <?php echo e($receipts->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>


<?php if(!$activeReportType || $activeReportType === 'remittance'): ?>

<div class="bg-white p-5 rounded-lg shadow border">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-lg text-green-700">REMITTANCE</h3>
        <button @click="open.remittance = !open.remittance" class="text-sm font-semibold text-green-600 hover:underline">
            <span x-text="open.remittance ? 'Hide Details' : 'View Details'"></span>
        </button>
    </div>

    <?php
        $totalRemittanceChecks = $remittances->sum(fn ($r) => $r->items->where('type', 'check')->sum('amount'));
        $totalRemittanceCash   = $remittances->sum(fn ($r) => $r->items->where('type', 'cash')->sum('amount'));
        $totalRemittanceAll    = $totalRemittanceChecks + $totalRemittanceCash;
    ?>

    <div class="bg-gray-100 rounded-lg p-6 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div>
                <p class="text-sm text-gray-500">Total Checks</p>
                <p class="text-2xl font-bold text-green-700">
                    &#8369; <?php echo e(number_format($totalRemittanceChecks, 2)); ?>

                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Cash</p>
                <p class="text-2xl font-bold text-green-700">
                    &#8369; <?php echo e(number_format($totalRemittanceCash, 2)); ?>

                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Remittance</p>
                <p class="text-2xl font-bold text-green-700">
                    &#8369; <?php echo e(number_format($totalRemittanceAll, 2)); ?>

                </p>
            </div>
        </div>
    </div>

    <div x-show="open.remittance">
        <?php if($remittances->isEmpty()): ?>
            <p class="text-center text-gray-500 italic py-6">
                No remittance records found for the selected filters.
            </p>
        <?php else: ?>
            <table class="min-w-full text-sm report-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th>Division</th>
                        <th>Date</th>
                        <th>Bank (Checks)</th>
                        <th class="text-right">Checks</th>
                        <th class="text-right">Cash</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
            
                <tbody>
                <?php $__currentLoopData = $remittances->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <?php
                        $divisionName = $r->division->division_name ?? 'UNASSIGNED DIVISION';
                    ?>

                <tr>
                    
                    <td class="font-semibold text-gray-700">
                        <?php echo e($divisionName); ?>

                    </td>

                    <td><?php echo e($r->report_date); ?></td>

                    <td>
                        <?php
                            $text = $r->items
                                ->where('type', 'check')
                                ->map(function ($item) {
                                    $parts = array_map('trim', explode('|', (string) ($item->description ?? '')));

                                    $bank = trim((string) ($item->bank_name ?? ($parts[0] ?? '')));
                                    $accountName = trim((string) ($item->account_name ?? ($parts[1] ?? '')));
                                    $accountNo = trim((string) ($item->account_number ?? ($parts[2] ?? '')));

                                    $clean = collect([$bank, $accountName, $accountNo])
                                        ->map(fn ($v) => trim((string) $v))
                                        ->filter(fn ($v) => $v !== '' && $v !== '-')
                                        ->values()
                                        ->all();

                                    return implode(' | ', $clean);
                                })
                                ->filter()
                                ->unique()
                                ->implode(', ');
                        ?>

                        <?php echo e($text ?: '-'); ?>

                    </td>

                    <?php
                        $checksAmt = (float) $r->items->where('type', 'check')->sum('amount');
                        $cashAmt   = (float) $r->items->where('type', 'cash')->sum('amount');
                        $totalAmt  = $checksAmt + $cashAmt;
                    ?>

                    <td class="text-right">
                       &#8369; <?php echo e(number_format($checksAmt, 2)); ?>

                    </td>

                    <td class="text-right">
                       &#8369; <?php echo e(number_format($cashAmt, 2)); ?>

                    </td>

                    <td class="text-right font-semibold">
                       &#8369; <?php echo e(number_format($totalAmt, 2)); ?>

                    </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

<?php if(!$activeReportType || $activeReportType === 'receivables'): ?>

<div class="bg-white p-5 rounded-lg shadow border">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-lg text-orange-700">RECEIVABLES</h3>
        <button @click="open.receivables = !open.receivables" class="text-sm font-semibold text-orange-600 hover:underline">
            <span x-text="open.receivables ? 'Hide Details' : 'View Details'"></span>
        </button>
    </div>

    <div class="h-40 bg-gray-100 flex flex-col items-center justify-center mb-4">
        <p class="text-sm text-gray-500">Total Receivables</p>
        <p class="text-2xl font-bold text-orange-700">
            <?php
            $receivablesTotal =
                $accountReceivables->sum('amount')
                + $receivableCollections->sum('amount')
                + $stockTransfers->sum('amount')
                + $shortageCollections->sum('amount');
            ?>

             <?php echo e(number_format($receivablesTotal, 2)); ?>

        </p>
    </div>

    <div x-show="open.receivables">
        <table class="min-w-full text-sm report-table">
            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="w-[16%] px-2 py-2 text-left">Division</th>
                    <th class="w-[28%] px-2 py-2 text-left">Customer</th>
                    <th class="w-[17%] px-2 py-2 text-right">Amounts</th>
                    <th class="w-[8%] px-2 py-2 text-center">Terms</th>
                    <th class="w-[13%] px-2 py-2 text-center">Due Date</th>
                    <th class="w-[12%] px-2 py-2 text-center">Last Paid</th>
                    <th class="w-[12%] px-2 py-2 text-center">Days / Status</th>
                </tr>
            </thead>

            <tbody>
                
<tr class="bg-blue-50 font-bold">
    <td colspan="7" class="px-2 py-2">ACCOUNT RECEIVABLES</td>
</tr>

<?php $__empty_1 = true; $__currentLoopData = $accountReceivables->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

    <?php
        $divisionName = $item->division->division_name ?? 'UNASSIGNED DIVISION';
    ?>

    <tr class="border-b
        <?php echo e($item->balance == 0
            ? 'bg-green-50'
            : ($item->due_date && \Carbon\Carbon::parse($item->due_date)->isPast()
                ? 'bg-red-50'
                : '')); ?>">

        
        <td class="px-2 py-2 font-semibold text-gray-700">
            <?php echo e($divisionName); ?>

        </td>

        
        <td class="px-2 py-2 font-medium truncate">
            <div><?php echo e($item->customer->customer ?? '-'); ?></div>
            <div class="text-xs text-gray-500">
                <?php echo e($item->customer->store_name ?? ''); ?>

            </div>
            <?php if(!empty($item->description)): ?>
                <div class="text-xs text-gray-500">
                    Leadman: <?php echo e($item->description); ?>

                </div>
            <?php endif; ?>
        </td>

        
        <td class="px-2 py-2 text-right font-medium">
            <div>&#8369; <?php echo e(number_format($item->amount, 2)); ?></div>

            <?php if($item->paid > 0): ?>
                <div class="text-xs text-green-700">
                    Paid: &#8369; <?php echo e(number_format($item->paid, 2)); ?>

                </div>
                <div class="text-xs text-red-700">
                    Balance: &#8369; <?php echo e(number_format($item->balance, 2)); ?>

                </div>
            <?php endif; ?>
        </td>

        
        <td class="px-2 py-2 text-center">
            <?php echo e($item->terms ?? '-'); ?>

        </td>

        
        <td class="px-2 py-2 text-center">
            <?php echo e($item->due_date
                ? \Carbon\Carbon::parse($item->due_date)->format('m/d/Y')
                : '-'); ?>

        </td>

        
        <td class="px-2 py-2 text-center">
            <?php echo e($item->last_paid_date
                ? \Carbon\Carbon::parse($item->last_paid_date)->format('m/d/Y')
                : ''); ?>

        </td>

        
        <td class="px-2 py-2 text-center">

            <?php if($item->balance == 0): ?>
                <div class="text-green-600 font-semibold">Paid</div>

            <?php elseif($item->paid > 0): ?>
                <div class="text-yellow-600 font-semibold">Partial</div>

            <?php elseif($item->due_date && \Carbon\Carbon::parse($item->due_date)->isPast()): ?>
                <div class="text-red-600 font-semibold">Overdue</div>

            <?php else: ?>
                <div class="text-blue-600 font-semibold">Active</div>
            <?php endif; ?>

        </td>

    </tr>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="7" class="text-center italic py-2">
            No account receivables
        </td>
    </tr>
<?php endif; ?>


                
                <tr class="bg-blue-50 font-bold">
                    <td colspan="7" class="px-2 py-2">RECEIVABLE COLLECTIONS</td>
                </tr>

                <?php $__empty_1 = true; $__currentLoopData = $receivableCollections->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <?php
                        $divisionName = $item->division->division_name ?? 'UNASSIGNED DIVISION';
                    ?>

                    <tr class="border-b">
                        <td class="px-2 py-2 font-semibold text-gray-700">
                            <?php echo e($divisionName); ?>

                        </td>
                        <td class="px-2 py-2 font-medium truncate">
                            
                            <div class="text-xs font-semibold text-gray-600">
                                SI #: <?php echo e($item->reference_no ?? '-'); ?>

                            </div>

                            
                            <div><?php echo e($item->customer->customer ?? '-'); ?></div>

                            
                            <div class="text-xs text-gray-500">
                                <?php echo e($item->customer->store_name ?? ''); ?>

                            </div>
                        </td>
                        <td class="px-2 py-2 text-right font-medium">
                            &#8369; <?php echo e(number_format($item->original_amount ?? $item->amount, 2)); ?>

                        </td>
                        <td class="px-2 py-2 text-center" colspan="3">
                            <?php echo e($item->remarks ?? '-'); ?>

                        </td>
                        <td class="px-2 py-2 text-center">
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                Collected
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center italic py-2">
                            No receivable collections
                        </td>
                    </tr>
                <?php endif; ?>

                
                <tr class="bg-slate-50 font-bold">
                    <td colspan="7" class="px-3 py-2 text-left">
                        STOCK TRANSFER RECEIVABLES
                    </td>
                </tr>

                <?php $__empty_1 = true; $__currentLoopData = $stockTransfers->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <?php
                        $divisionName = $item->division->division_name ?? 'UNASSIGNED DIVISION';
                    ?>

                    <tr>
                        <td class="px-3 py-2 font-semibold text-gray-700">
                            <?php echo e($divisionName); ?>

                        </td>
                        <td class="px-3 py-2">
                            <?php echo e($item->reference_no ?? '-'); ?>

                        </td>
                        <td class="px-3 py-2 text-right font-medium">
                            &#8369; <?php echo e(number_format($item->amount, 2)); ?>

                        </td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-block px-2 py-1 text-xs rounded bg-slate-200 text-slate-800">
                                Stock Transfer
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center italic py-3 text-gray-500">
                            No stock transfer receivables
                        </td>
                    </tr>
                <?php endif; ?>

                
                <tr class="bg-amber-50 font-bold">
                    <td colspan="7" class="px-3 py-2 text-left">
                        SHORTAGE COLLECTIONS
                    </td>
                </tr>

                <?php $__empty_1 = true; $__currentLoopData = $shortageCollections->take($perPage); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <?php
                        $divisionName = $item->division->division_name ?? 'UNASSIGNED DIVISION';
                    ?>

                    <tr>
                        <td class="px-3 py-2 font-semibold text-gray-700">
                            <?php echo e($divisionName); ?>

                        </td>
                        <td class="px-3 py-2">
                            <?php echo e($item->customer_name ?? '-'); ?>

                        </td>
                        <td class="px-3 py-2 text-right font-medium">
                            &#8369; <?php echo e(number_format($item->amount, 2)); ?>

                        </td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">-</td>
                        <td class="px-3 py-2 text-center">
                            <span class="inline-block px-2 py-1 text-xs rounded bg-amber-200 text-amber-800">
                                Collected
                            </span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center italic py-3 text-gray-500">
                            No shortage collections
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if(!$activeReportType || $activeReportType === 'borrowers'): ?>

<div class="bg-white p-5 rounded-lg shadow border">
    <div class="flex justify-between items-center mb-3">
        <h3 class="font-semibold text-lg text-purple-700">BORROWERS</h3>
        <button @click="open.borrowers = !open.borrowers" class="text-sm font-semibold text-purple-600 hover:underline">
            <span x-text="open.borrowers ? 'Hide Details' : 'View Details'"></span>
        </button>
    </div>

    
    <div class="h-40 bg-gray-100 flex flex-col items-center justify-center mb-4">
        <div class="grid grid-cols-2 gap-6 text-center">
            <div>
                <p class="text-sm text-gray-500">Total Borrowed</p>
                <p class="text-2xl font-bold text-blue-600">
                    <?php echo e($totalBorrowed); ?>

                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Total Returned</p>
                <p class="text-2xl font-bold text-green-600">
                    <?php echo e($totalReturned); ?>

                </p>
            </div>
        </div>
    </div>

    <div x-show="open.borrowers">

        <?php if($borrowers->isEmpty()): ?>
            <p class="text-center text-gray-500 italic py-6">
                No borrower records found for the selected filters.
            </p>
        <?php else: ?>

            <?php $__currentLoopData = $borrowers->take($perPage)->groupBy('report_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dailyBorrowers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                
                <?php
                    $divisionName = $dailyBorrowers->first()->division->division_name ?? 'UNASSIGNED DIVISION';
                ?>

                <div class="mt-6 mb-2 flex items-center gap-3">
                    <i data-lucide="calendar" class="w-4 h-4 text-purple-600"></i>
                    <h4 class="font-semibold text-purple-700">
                        <?php echo e(\Carbon\Carbon::parse($date)->format('F d, Y')); ?>

                    </h4>
                    <span class="text-sm text-gray-500">
                        &bull; <?php echo e($divisionName); ?>

                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm table-fixed report-table">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-2 py-2 text-left">Item</th>
                                <th class="px-2 py-2 text-left">Location</th>
                                <th class="px-2 py-2 text-right">Borrowed</th>
                                <th class="px-2 py-2 text-right">Returned</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $hasRows = false; ?>

                            <?php $__currentLoopData = $dailyBorrowers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $b->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <?php if($item->borrowed > 0 || $item->returned > 0): ?>
                                        <?php $hasRows = true; ?>

                                        <tr>
                                            <td class="px-2 py-2">
                                                <?php echo e(ucfirst($item->item_type)); ?>

                                            </td>
                                            <td class="px-2 py-2">
                                                <?php echo e(ucfirst($item->location)); ?>

                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <?php echo e(number_format($item->borrowed)); ?>

                                            </td>
                                            <td class="px-2 py-2 text-right">
                                                <?php echo e(number_format($item->returned)); ?>

                                            </td>
                                        </tr>
                                    <?php endif; ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <?php if(!$hasRows): ?>
                                <tr>
                                    <td colspan="4" class="px-2 py-4 text-center text-gray-400 italic">
                                        No borrowed or returned items recorded.
                                    </td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<style>
.fade-in {
    animation: fadeInUp 0.3s ease-in-out;
}
a {
    text-decoration: none;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.result-card {
    padding: 12px;
    border-radius: 8px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
}

#globalSearch {
    font-size: 15px;
    padding: 10px;
}

.input-group-text {
    font-size: 16px;
}

.btn-primary {
    padding-left: 20px;
    padding-right: 20px;
}
.input-group-text {
    font-size: 16px;
}

#globalSearch {
    font-size: 15px;
    padding: 10px;
}

.btn-link {
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}
.receivables-table td,
.receivables-table th {
    vertical-align: middle;
}

.receivables-table td {
    white-space: nowrap;
}

.receivables-table td:first-child {
    width: 30%;
}

.receivables-table td:nth-child(2) {
    width: 15%;
}

.receivables-table td:nth-child(3),
.receivables-table td:nth-child(4),
.receivables-table td:nth-child(5) {
    width: 10%;
}

.receivables-table td:last-child {
    width: 15%;
}
/* Highlight ONLY the matched text */
.highlight-text {
    background-color: #fff7cc; /* soft yellow */
    font-weight: bold;
    color: #1d4ed8; /* blue */
    padding: 2px 4px;
    border-radius: 3px;
}
.report-table {
    border-collapse: collapse;
    width: 100%;
}

.report-table th,
.report-table td {
    border: 1px solid #d1d5db; /* light gray */
    padding: 8px;
}

.report-table thead {
    background-color: #fde7d9; /* light peach (your preference) */
}

</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const search = params.get('search');

    if (!search) return;

    const keyword = search.trim();
    const regex = new RegExp(`(${keyword})`, 'gi');

    document.querySelectorAll('table td').forEach(td => {
        if (td.children.length) return;

        if (!td.querySelector('.highlight-text') && td.textContent.match(regex)) {
            td.innerHTML = td.textContent.replace(
                regex,
                '<span class="highlight-text">$1</span>'
            );
        }
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= LINE: SALES TREND ================= */
    const salesTrendCtx = document.getElementById('salesTrendChart');
    if (salesTrendCtx) {
        new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(
                    $receipts->groupBy('report_date')->keys()
                ); ?>,
                datasets: [{
                    label: 'Gross Sales',
                    data: <?php echo json_encode(
                        $receipts->groupBy('report_date')
                            ->map(fn($r) => $r->flatMap->items->sum('gross_sales'))
                            ->values()
                    ); ?>,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            }
        });
    }

    
    /* ================= BAR: TOP 10 ROUTES (NO LOOP) ================= */
const topRoutesCtx = document.getElementById('topRoutesChart');

if (topRoutesCtx) {
    new Chart(topRoutesCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($routeLabels); ?>,
            datasets: [{
                label: 'Sales Amount',
                data: <?php echo json_encode($routeSales); ?>,
                backgroundColor: '#10b981'
            }]
        },
        options: {
            animation: false,               // âœ… STOP LOOPING
            responsive: true,
            maintainAspectRatio: false,     // âœ… use container height
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales Amount'
                    }
                }
            }
        }
    });
}

    /* ================= PIE: RECEIVABLES ================= */
    /* ================= PIE: RECEIVABLES ================= */
const receivablesCtx = document.getElementById('receivablesPieChart');
if (receivablesCtx) {
    new Chart(receivablesCtx, {
        type: 'pie',
        data: {
            labels: [
                'Account Receivables',
                'Receivable Collections',
                'Stock Transfers',
                'Shortage Collections'
            ],
            datasets: [{
                data: [
                    <?php echo e($accountReceivables->sum('amount')); ?>,
                    <?php echo e($receivableCollections->sum('amount')); ?>,
                    <?php echo e($stockTransfers->sum('amount')); ?>,
                    <?php echo e($shortageCollections->sum('amount')); ?>

                ],
                backgroundColor: [
                    '#3b82f6',
                    '#22c55e',
                    '#f59e0b',
                    '#ef4444'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,   // âœ… KEY FIX
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
}


});
</script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\consolidated.blade.php ENDPATH**/ ?>