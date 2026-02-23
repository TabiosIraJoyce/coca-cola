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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-2xl font-semibold text-facebookBlue">💰 Treasury Dashboard</h2>
            <div>
                <a href="<?php echo e(route('admin.dashboard')); ?>"
                   style="background-color: #007BFF; color: white; padding: 8px 16px; font-size: 14px; border-radius: 5px; text-decoration: none; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: inline-block;"
                   onmouseover="this.style.backgroundColor='#0056b3'"
                   onmouseout="this.style.backgroundColor='#007BFF'">
                    📊 Go to General Dashboard
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="w-full max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filter -->
        <form method="GET" class="mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label for="filter" class="font-medium text-gray-700">Filter:</label>
                <select name="filter" id="filter" onchange="toggleMonthDropdown(); this.form.submit()" class="border border-gray-300 rounded px-3 py-2">
                    <option value="daily" <?php echo e(request('filter') === 'daily' ? 'selected' : ''); ?>>Per Day</option>
                    <option value="weekly" <?php echo e(request('filter') === 'weekly' ? 'selected' : ''); ?>>This Week</option>
                    <option value="monthly" <?php echo e(request('filter') === 'monthly' ? 'selected' : ''); ?>>This Month</option>
                    <option value="yearly" <?php echo e(request('filter') === 'yearly' ? 'selected' : ''); ?>>This Year</option>
                </select>
            </div>

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
        </form>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php
                $collections = [
                    'Time Deposit' => $timeDeposit,
                    'Share Capital' => $shareCapital,
                    'GSEF' => $gsef,
                    'GARI FUNDS' => $gariFunds,
                    'Mutual Aid' => $mutualAid,
                    'Climbs Insurance' => $climbsInsurance,
                    'Others' => $others,
                    'AR Collection' => $arCollections,
                    'Advances' => $advances,
                    'Cash Payment' => $cashPayment,
                    'Check Payment' => $checkPayment,
                    'Total Collections' => $totalCollections,
                ];
            ?>

            <?php $__currentLoopData = $collections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gray-100 border-l-4 border-green-500 p-4 rounded shadow">
                    <h4 class="text-md font-semibold text-gray-700"><?php echo e($label); ?></h4>
                    <p class="text-2xl font-bold text-green-700 mt-2">₱<?php echo e(number_format($amount, 2)); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Charts -->
        <div class="bg-white rounded shadow p-6 mt-10">
            <h3 class="text-lg font-bold mb-6">📈 Collections Charts (<?php echo e(ucfirst(request('filter') ?? 'weekly')); ?>)</h3>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-8 h-[300px]">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">📉 Total Collections Progression</h4>
                    <canvas id="collectionsProgressChart" class="w-full h-full"></canvas>
                </div>
                <div class="md:col-span-4 h-[300px]">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2 text-center">💰 Collections Distribution</h4>
                    <canvas id="collectionsPieChart" class="w-full mx-auto"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            <div class="bg-white border-l-4 border-green-500 p-4 rounded shadow text-green-700">
                <h4 class="text-lg font-semibold">💵 Total Cash Payment</h4>
                <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($cashPayment, 2)); ?></p>
            </div>
            <div class="bg-white border-l-4 border-indigo-500 p-4 rounded shadow text-indigo-700">
                <h4 class="text-lg font-semibold">🏦 Total Check Payment</h4>
                <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($checkPayment, 2)); ?></p>
            </div>
            <div class="bg-white border-l-4 border-pink-500 p-4 rounded shadow text-pink-700">
                <h4 class="text-lg font-semibold">💰 Total Collections</h4>
                <p class="text-2xl font-bold mt-2">₱<?php echo e(number_format($totalCollections, 2)); ?></p>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php $treasuryProgressionUrl = route('admin.dashboard.treasury-progression'); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('collectionsProgressChart').getContext('2d');
    fetch(`<?php echo e($treasuryProgressionUrl); ?>?filter=<?php echo e(request('filter')); ?>&month=<?php echo e(request('month') ?? ''); ?>`)
        .then(response => response.json())
        .then(({ labels, data }) => {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Total Collections (₱)',
                        data,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            ticks: {
                                callback: value => '₱' + Number(value).toLocaleString()
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: context => '₱' + Number(context.raw).toLocaleString()
                            }
                        }
                    }
                }
            });
        });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctxPie = document.getElementById('collectionsPieChart').getContext('2d');
    const data = [
        <?php echo e($cashPayment ?? 0); ?>,
        <?php echo e($checkPayment ?? 0); ?>,
        <?php echo e($timeDeposit ?? 0); ?>,
        <?php echo e($shareCapital ?? 0); ?>,
        <?php echo e($others ?? 0); ?>

    ];

    const total = data.reduce((sum, val) => sum + val, 0);
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Cash Payment', 'Check Payment', 'Time Deposit', 'Share Capital', 'Others'],
            datasets: [{
                data: data,
                backgroundColor: ['#4ade80', '#60a5fa', '#fbbf24', '#f472b6', '#a78bfa'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
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
function toggleMonthDropdown() {
    const filter = document.getElementById('filter').value;
    const monthSelect = document.getElementById('month-select-wrapper');
    monthSelect.style.display = (filter === 'monthly') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', toggleMonthDropdown);
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\dashboard\treasury.blade.php ENDPATH**/ ?>