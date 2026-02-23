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
        <div class="flex justify-between items-center">

            <h2 class="text-2xl font-semibold text-facebookBlue">
                📊 Sales Dashboard
            </h2>
            <!-- 🔽 Go to Treasury -->
            <div class="rounded-md bg-green-500 hover:bg-green-600 shadow-md transition">
                <a href="<?php echo e(route('admin.dashboard.treasury')); ?>"
                    onclick="return confirm('Are you sure you want to go to the Treasury Dashboard?')"
                    style="background-color: #22c55e; color: white; padding: 8px 16px; border-radius: 6px; font-weight: bold; display: inline-block;">
                    💰 Go to Treasury Dashboard
                </a>
            </div>
        </div>
 <?php $__env->endSlot(); ?>

    <!-- Filter -->
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label for="filter" class="font-medium text-gray-700">Filter:</label>
            <select name="filter" id="filter"onchange="toggleMonthDropdown(); this.form.submit()"class="mt-1 block w-40 rounded-md border-gray-300 shadow-sm">
                <option value="daily" <?php echo e($filter === 'daily' ? 'selected' : ''); ?>>Per Day</option>
                <option value="weekly" <?php echo e($filter === 'weekly' ? 'selected' : ''); ?>>This Week</option>
                <option value="monthly" <?php echo e($filter === 'monthly' ? 'selected' : ''); ?>>This Month</option>
                <option value="yearly" <?php echo e($filter === 'yearly' ? 'selected' : ''); ?>>This Year</option>
            </select>
        </div>

        <!-- 🗓 Specific Month Selector -->
        <div id="month-select-wrapper" style="display: none;">
            <label for="month" class="font-medium text-gray-700">Month:</label>
            <select name="month" id="month" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2">
                <option value="">Current Month</option>
                <?php for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo e($m); ?>" <?php echo e(request('month') == $m ? 'selected' : ''); ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($m)->format('F')); ?>

                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- 📂 Division Filter -->
        <div>
            <label for="division_id" class="font-medium text-gray-700">Division:</label>
            <select name="division_id" id="division_id" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2">
                <option value="">All Divisions</option>
                <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($division->id); ?>" <?php echo e(request('division_id') == $division->id ? 'selected' : ''); ?>>
                        <?php echo e($division->division_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </form>

    <!-- Side Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <!-- Cash Sales -->
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Cash Sales</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($cashSales, 2)); ?></p>
        </div>

        <!-- IRS Sales -->
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">IRS Sales</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($irsSales, 2)); ?></p>
        </div>

        <!-- Cheque Sales -->
        <div class="bg-indigo-100 border-l-4 border-indigo-500 text-indigo-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Cheque Sales</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($chequeSales, 2)); ?></p>
        </div>

        <!-- Credit Sales -->
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Credit Sales</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($creditSales, 2)); ?></p>
        </div>

        <!-- Cash Overage -->
        <div class="bg-purple-100 border-l-4 border-purple-500 text-purple-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Cash Overage</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($cashOverage, 2)); ?></p>
        </div>

        <!-- AR Collections -->
        <div class="bg-teal-100 border-l-4 border-teal-500 text-teal-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">AR Collections</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($arCollections, 2)); ?></p>
        </div>

        <!-- Cash Shortage -->
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Cash Shortage</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($cashShortage, 2)); ?></p>
        </div>

        <!-- Total Sales -->
        <div class="bg-gray-100 border-l-4 border-gray-500 text-gray-800 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Total Sales</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($totalSales, 2)); ?></p>
        </div>

        <!-- Total Remittance -->
        <div class="bg-pink-100 border-l-4 border-pink-500 text-pink-700 p-4 rounded shadow">
            <h4 class="text-lg font-semibold">Total Remittance</h4>
            <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($totalRemittance, 2)); ?></p>
        </div>
    </div>




    <!-- 📊 Charts Section (Line + Pie Side by Side) -->
    <div class="bg-white rounded shadow p-6 mt-10">
    <h3 class="text-lg font-bold mb-6">📈 Sales Charts (<?php echo e(ucfirst($filter)); ?>)</h3>  
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- 📉 Line Chart (70%) -->
            <div class="md:col-span-8 h-[300px]">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">📉 Sales Progression</h4>
                <canvas id="salesProgressChart" class="w-full h-full"></canvas>
            </div>
            <!-- 💰 Pie Chart (30%) -->
            <div class="md:col-span-4 h-[300px]">
                <h4 class="text-sm font-semibold text-gray-700 mb-2 text-center">💰 Sales Distribution</h4>
                <canvas id="cashCreditPieChart" class="mx-auto"></canvas>
            </div>
        </div>
    </div>


    <!-- 🏢 Top Divisions Section -->
    <div class="bg-white rounded shadow p-6 mt-10">
        <h3 class="text-lg font-bold mb-6">🏢 Division Sales Overview (<?php echo e(ucfirst($filter)); ?>)</h3>
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- 📊 Bar Representation -->
            <div class="md:col-span-8 space-y-3">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">🏆 Top Divisions by Total Sales</h4>
                <div class="bg-white p-4 rounded shadow space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $divisionSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $percentage = $totalSales > 0 ? ($amount / $totalSales) * 100 : 0;
                        ?>
                        <div>
                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                                <span><?php echo e($division); ?></span>
                                <span>₱<?php echo e(number_format($amount, 2)); ?></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                                <div class="bg-facebookBlue h-4" style="width: <?php echo e($percentage); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center text-gray-500">No data available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 🧩 Pie Chart -->
            <div class="md:col-span-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2 text-center">📊 Division Sales Pie Chart</h4>
                <canvas id="divisionSalesPieChart" class="mx-auto max-w-full h-[300px]"></canvas>
            </div>
        </div>
    </div>


    <!-- Top Business Lines -->
    <div class="mt-10">
        <h3 class="text-lg font-bold mb-3">📦 Top Business Lines by Total Sales</h3>
        <div class="bg-white p-4 rounded shadow space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $businessLineSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $percentage = $totalSales > 0 ? ($amount / $totalSales) * 100 : 0;
                ?>
                <div class="flex items-center gap-3">
                    <div class="w-32 font-medium text-sm text-gray-700"><?php echo e($line); ?></div>
                    <div class="flex-1 bg-gray-200 rounded h-4 relative">
                        <div class="bg-green-600 h-4 rounded" style="width: <?php echo e($percentage); ?>%"></div>
                    </div>
                    <div class="w-24 text-right text-sm text-gray-600">₱<?php echo e(number_format($amount, 2)); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center">No data available.</p>
            <?php endif; ?>
        </div>
    </div>


    <!-- 💸 Cash Shortage by Division -->
    <?php $maxShortage = collect($cashShortagePerDivision)->max(); ?>

    <div class="mt-10">
        <h3 class="text-lg font-bold mb-3">💸 Cash Shortage by Division (<?php echo e(ucfirst($filter)); ?>)</h3>
        <div class="bg-white p-4 rounded shadow space-y-4" id="shortage-container">
            <?php $__empty_1 = true; $__currentLoopData = $cashShortagePerDivision; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $percentage = $maxShortage > 0 ? ($amount / $maxShortage) * 100 : 0;
                ?>

                <div class="flex items-center gap-3 relative group">
                    <!-- Division Name -->
                    <div class="w-32 font-medium text-sm text-gray-700 truncate"><?php echo e($division); ?></div>

                    <!-- Bar -->
                    <div class="flex-1 bg-gray-200 rounded h-4 overflow-hidden relative">
                        <div 
                            class="shortage-bar h-4 rounded transition-all duration-1000" 
                            data-width="<?php echo e($percentage); ?>"
                            style="width: 0; background-color: #dc2626;" 
                            title="<?php echo e(number_format($percentage, 1)); ?>%"
                        ></div>
                    </div>

                    <!-- Amount -->
                    <div class="w-24 text-right text-sm text-gray-600 whitespace-nowrap">
                        ₱<?php echo e(number_format($amount, 2)); ?>

                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-center">No data available.</p>
            <?php endif; ?>
        </div>
    </div>


    <!-- 📊 Collection on Shortages Chart -->
    <div class="bg-white rounded shadow p-6 mt-10">
        <h3 class="text-lg font-bold mb-6">📊 Collection on Shortages by Division (<?php echo e(ucfirst($filter)); ?>)</h3>

        <div class="space-y-3">
            <?php $maxCollected = collect($shortageCollectionPerDivision)->max(); ?>

            <?php $__empty_1 = true; $__currentLoopData = $shortageCollectionPerDivision; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $percentage = $maxCollected > 0 ? ($amount / $maxCollected) * 100 : 0;
                ?>
                <div>
                    <div class="flex justify-between text-sm font-medium text-gray-700 mb-1">
                        <span><?php echo e($division); ?></span>
                        <span style="color: #15803d; font-weight: 600;">₱<?php echo e(number_format($amount, 2)); ?></span> 
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="h-4 transition-all duration-1000 ease-out"
                            style="width: <?php echo e($percentage); ?>%; background-color: #22c55e;"> 
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-center text-gray-500">No data available.</p>
            <?php endif; ?>
        </div>
    </div>




    <!-- Complied Compliance  -->
    <div class="mt-10">
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">📋 Division Submission Status (Today)</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-left px-4 py-2">#</th>
                            <th class="text-left px-4 py-2">Division</th>
                            <th class="text-left px-4 py-2">Submission Time</th>
                            <th class="text-left px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>

                        <?php $__currentLoopData = $complied; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($i++); ?></td>
                                <td class="px-4 py-2"><?php echo e($item['division']->division_name); ?></td>
                                <td class="px-4 py-2 text-green-600"><?php echo e(\Carbon\Carbon::parse($item['time'])->format('h:i A')); ?></td>
                                <td class="px-4 py-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">✅ Complied</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php $__currentLoopData = $late; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($i++); ?></td>
                                <td class="px-4 py-2"><?php echo e($item['division']->division_name); ?></td>
                                <td class="px-4 py-2 text-yellow-600"><?php echo e(\Carbon\Carbon::parse($item['time'])->format('h:i A')); ?></td>
                                <td class="px-4 py-2">
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded">⚠️ Late</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php $__currentLoopData = $notSubmitted; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?php echo e($i++); ?></td>
                                <td class="px-4 py-2"><?php echo e($division->division_name); ?></td>
                                <td class="px-4 py-2 text-red-500 italic">—</td>
                                <td class="px-4 py-2">
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">❌ Not Submitted</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 📊 KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

        <!-- 📈 % Increase Card -->
        <div class="bg-white border-l-4 border-blue-500 p-4 rounded shadow text-blue-700">
            <h4 class="text-lg font-semibold">📈 % Increase from Previous <?php echo e(ucfirst($filter)); ?></h4>
            <p class="text-2xl font-bold mt-2">
                <?php echo e($salesIncrease > 0 ? '+' : ''); ?><?php echo e(number_format($salesIncrease, 2)); ?>%
            </p>
        </div>

        <!-- 🔻 Lowest Division Card -->
        <div class="bg-white border-l-4 border-red-500 p-4 rounded shadow text-red-700">
            <h4 class="text-lg font-semibold">🔻 Lowest Performing Division</h4>
            <p class="text-xl font-bold mt-2">
                <?php echo e($lowestDivision ?? 'N/A'); ?>

            </p>
        </div>

        <!-- 💼 Top Business Line -->
        <div class="bg-white border-l-4 border-green-500 p-4 rounded shadow text-green-700">
                <h4 class="text-lg font-semibold">💼 Top Business Line (Share)</h4>
                <p class="text-xl font-bold mt-2">
                    <?php echo e($topBusinessLine ?? 'N/A'); ?> — <?php echo e(number_format($topBusinessLinePercent, 2)); ?>%
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

                <!-- 📊 Average Sales KPI -->
                <div class="bg-white border-l-4 border-indigo-500 p-4 rounded shadow text-indigo-700">
                    <h4 class="text-lg font-semibold">📊 Average Sales (<?php echo e(ucfirst($filter)); ?>)</h4>
                    <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($averageSales, 2)); ?></p>
                </div>

                <!-- (Optional) Add 3rd Card Here -->
                
            </div>
        </div>
    </div>
    <?php if($currentCutoff): ?>
<script>
Swal.fire({
    icon: "<?php echo e($cutoffDaysLeft <= 2 ? 'error' : ($cutoffDaysLeft <= 5 ? 'warning' : 'info')); ?>",
    title: "Cut-Off Reminder",
    html: `
        <b>Period <?php echo e($currentCutoff->period_number); ?></b><br>
        <?php echo e($currentCutoff->start_date); ?> → <?php echo e($currentCutoff->end_date); ?><br><br>
        <b><?php echo e($cutoffDaysLeft); ?></b> day(s) left before cut-off.
    `,
    timer: 3500,
    showConfirmButton: false
});
</script>
<?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<!-- ✅ Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- 📊 Line Chart Script -->

<?php
    $salesProgressionUrl = route('admin.dashboard.sales-progression');
?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const salesChartCanvas = document.getElementById('salesProgressChart');
    let salesChartInstance = null;

    const salesProgressionUrl = <?php echo json_encode($salesProgressionUrl, 15, 512) ?>;

    if (!salesProgressionUrl) {
        console.warn('⚠️ Sales Progression route not available.');
        return;
    }

    fetch(`${salesProgressionUrl}?filter=<?php echo e($filter); ?>&division_id=<?php echo e($divisionId ?? ''); ?>&month=<?php echo e(request('month')); ?>`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(({ labels, data }) => {
            console.log('📊 Labels:', labels);
            console.log('📈 Data:', data);

            if (!labels.length || !data.length) {
                console.warn('⚠️ No sales data available to plot.');
                return;
            }

            if (salesChartInstance) {
                salesChartInstance.destroy();
            }

            const numericData = data.map(val => parseFloat(val));
            const averageSales = <?php echo e($averageSales); ?>;

            salesChartInstance = new Chart(salesChartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Total Sales (₱)',
                            data: numericData,
                            borderColor: '#1877f2',
                            backgroundColor: 'rgba(24, 119, 242, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointBackgroundColor: '#1877f2',
                        },
                        {
                            label: 'Average Sales (₱)',
                            data: Array(labels.length).fill(averageSales),
                            borderColor: '#a855f7',
                            borderDash: [5, 5],
                            borderWidth: 2,
                            pointRadius: 0,
                            fill: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: value => '₱' + Number(value).toLocaleString(),
                            },
                        },
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: { size: 14 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: context => '₱' + Number(context.raw).toLocaleString()
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('❌ Failed to fetch Sales Progression data:', error.message);
        });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const pieCtx = document.getElementById('cashCreditPieChart').getContext('2d');

    const cashSales = <?php echo e($cashSales); ?>;
    const irsSales = <?php echo e($irsSales); ?>;
    const chequeSales = <?php echo e($chequeSales); ?>;
    const creditSales = <?php echo e($creditSales); ?>;
    const cashOverage = <?php echo e($cashOverage); ?>;

    const dataValues = [cashSales, irsSales, chequeSales, creditSales, cashOverage];
    const dataLabels = ['Cash Sales', 'IRS Sales', 'Cheque Sales', 'Credit Sales', 'Cash Overage'];

    const total = dataValues.reduce((sum, val) => sum + val, 0);

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: dataLabels,
            datasets: [{
                data: dataValues,
                backgroundColor: [
                    '#34d399', // Cash Sales - Green
                    '#facc15', // IRS Sales - Yellow
                    '#60a5fa', // Cheque Sales - Blue
                    '#a78bfa', // Credit Sales - Purple
                    '#fb923c'  // Cash Overage - Orange
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14 },
                        generateLabels: (chart) => {
                            const dataset = chart.data.datasets[0];
                            return chart.data.labels.map((label, i) => {
                                const value = dataset.data[i];
                                const percent = ((value / total) * 100).toFixed(1);
                                return {
                                    text: `${label} (${percent}%)`,
                                    fillStyle: dataset.backgroundColor[i],
                                    strokeStyle: dataset.borderColor,
                                    lineWidth: dataset.borderWidth,
                                    hidden: false,
                                    index: i
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ₱${Number(value).toLocaleString()} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('divisionSalesPieChart').getContext('2d');

    const divisionLabels = <?php echo json_encode(array_keys($divisionSales)); ?>;
    const divisionValues = <?php echo json_encode(array_values($divisionSales)); ?>;

    const total = divisionValues.reduce((sum, val) => sum + val, 0);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: divisionLabels,
            datasets: [{
                data: divisionValues,
                backgroundColor: [
                    '#60a5fa', '#34d399', '#fbbf24', '#f87171',
                    '#a78bfa', '#f472b6', '#10b981', '#6366f1',
                    '#facc15', '#fb923c'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14 },
                        generateLabels: (chart) => {
                            const dataset = chart.data.datasets[0];
                            return chart.data.labels.map((label, i) => {
                                const value = dataset.data[i];
                                const percent = ((value / total) * 100).toFixed(1);
                                return {
                                    text: `${label} (${percent}%)`,
                                    fillStyle: dataset.backgroundColor[i % dataset.backgroundColor.length],
                                    strokeStyle: dataset.borderColor,
                                    lineWidth: dataset.borderWidth,
                                    hidden: false,
                                    index: i
                                };
                            });
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const percent = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ₱${Number(value).toLocaleString()} (${percent}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>

<script>
    window.onload = function () {
        document.querySelectorAll('.shortage-bar').forEach(bar => {
            const width = bar.dataset.width;
            bar.style.transition = 'width 1s ease';
            bar.style.width = `${width}%`;
        });
    };
</script>

<script>
    function toggleMonthDropdown() {
        const filter = document.getElementById('filter').value;
        const monthWrapper = document.getElementById('month-select-wrapper');
        monthWrapper.style.display = (filter === 'monthly') ? 'block' : 'none';
    }

    // Run on page load to apply state
    document.addEventListener('DOMContentLoaded', toggleMonthDropdown);
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\dashboard\index.blade.php ENDPATH**/ ?>