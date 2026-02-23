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
    <h2 class="text-2xl font-extrabold text-[#5F6472] flex items-center gap-2">
        <i data-lucide="layout-dashboard" class="w-6 h-6 text-[#FFA69E]"></i>
        Gledco Enterprises Dashboard
    </h2>
 <?php $__env->endSlot(); ?>


<div class="flex gap-8 border-b mb-8 text-lg font-semibold">
    <a href="<?php echo e(route('admin.consolidated.index')); ?>"
       class="pb-3 <?php echo e(request()->routeIs('admin.consolidated.index')
            ? 'text-red-600 border-b-4 border-red-600'
            : 'text-gray-500 hover:text-red-600'); ?>">
        Daily Dashboard
    </a>

    <a href="<?php echo e(route('admin.reports.periods.index')); ?>"
       class="pb-3 <?php echo e(request()->is('admin/reports/periods*')
            ? 'text-red-600 border-b-4 border-red-600'
            : 'text-gray-500 hover:text-red-600'); ?>">
        Period Summary
    </a>
</div>


<div class="bg-[#FFF6E5] rounded-xl shadow p-6 mb-8 border border-[#FFA69E]/40">
    <form method="GET"
          action="<?php echo e(route('admin.consolidated.index')); ?>"
          class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <label class="text-sm font-semibold">Branch</label>
            <select name="branch" class="w-full border rounded p-2">
                <option value="All" <?php echo e(request('branch', $branch) === 'All' ? 'selected' : ''); ?>>All</option>
                <option value="Solsona" <?php echo e(request('branch', $branch) === 'Solsona' ? 'selected' : ''); ?>>Solsona</option>
                <option value="Laoag" <?php echo e(request('branch', $branch) === 'Laoag' ? 'selected' : ''); ?>>Laoag</option>
                <option value="Batac" <?php echo e(request('branch', $branch) === 'Batac' ? 'selected' : ''); ?>>Batac</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Period From</label>
            <select name="period_from" class="w-full border rounded p-2">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>"
                        <?php echo e(request('period_from', $currentCutoff->period_no ?? $currentCutoff->period_number ?? now()->month) == $i ? 'selected' : ''); ?>>
                        Period <?php echo e($i); ?>

                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div>
            <label class="text-sm font-semibold">Period To</label>
            <select name="period_to" class="w-full border rounded p-2">
                <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>"
                        <?php echo e(request('period_to', $currentCutoff->period_no ?? $currentCutoff->period_number ?? now()->month) == $i ? 'selected' : ''); ?>>
                        Period <?php echo e($i); ?>

                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="flex items-end">
            <button class="w-full bg-blue-600 text-white rounded px-4 py-2">
                Filter
            </button>
        </div>

    </form>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 shadow">
        <h4 class="text-sm font-extrabold text-red-700 uppercase mb-3">
            CORE Sales (Core Target Only)
        </h4>

        <div class="space-y-1 text-sm">
            <p>Actual:
                <span class="font-bold">
                    <?php echo e(number_format($coreActual, 2)); ?>

                </span>
            </p>
            <p>Target:
                <span class="font-bold">
                    <?php echo e(number_format($coreTarget, 2)); ?>

                </span>
            </p>
            <p>Achievement:
                <span class="font-bold">
                    <?php echo e(number_format($coreAchievement, 2)); ?>%
                </span>
            </p>
            <p class="font-bold <?php echo e($coreVariance < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                Variance:
                <?php echo e($coreVariance < 0 ? '-' : '+'); ?>

                <?php echo e(number_format(abs($coreVariance), 2)); ?>

            </p>
        </div>
    </div>

    
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 shadow">
        <h4 class="text-sm font-extrabold text-yellow-700 uppercase mb-3">
            PET CSD Sales
        </h4>

        <div class="space-y-1 text-sm">
            <p>Actual:
                <span class="font-bold">
                    <?php echo e(number_format($petcsdActual, 2)); ?>

                </span>
            </p>
            <p>Target:
                <span class="font-bold">
                    <?php echo e(number_format($petcsdTarget, 2)); ?>

                </span>
            </p>
            <p>Achievement:
                <span class="font-bold">
                    <?php echo e(number_format($petcsdAchievement, 2)); ?>%
                </span>
            </p>
            <p class="font-bold <?php echo e($petcsdVariance < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                Variance:
                <?php echo e($petcsdVariance < 0 ? '-' : '+'); ?>

                <?php echo e(number_format(abs($petcsdVariance), 2)); ?>

            </p>
        </div>
    </div>

    
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow">
        <h4 class="text-sm font-extrabold text-blue-700 uppercase mb-3">
            STILLS Sales
        </h4>

        <div class="space-y-1 text-sm">
            <p>Actual:
                <span class="font-bold">
                    <?php echo e(number_format($stillsActual, 2)); ?>

                </span>
            </p>
            <p>Target:
                <span class="font-bold">
                    <?php echo e(number_format($stillsTarget, 2)); ?>

                </span>
            </p>
            <p>Achievement:
                <span class="font-bold">
                    <?php echo e(number_format($stillsAchievement, 2)); ?>%
                </span>
            </p>
            <p class="font-bold <?php echo e($stillsVariance < 0 ? 'text-red-600' : 'text-green-600'); ?>">
                Variance:
                <?php echo e($stillsVariance < 0 ? '-' : '+'); ?>

                <?php echo e(number_format(abs($stillsVariance), 2)); ?>

            </p>
        </div>
    </div>

</div>


<div class="bg-[#B6DDE3]/40 rounded-2xl p-8 mb-10 border border-[#B6DDE3]">



<?php
    $target = $targetSales ?? 0;
    $actual = $actualSales ?? 0;
    $percent = $target > 0 ? min(100, round(($actual / $target) * 100, 2)) : 0;
    $variance = $actual - $target;
?>


<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">

    <div class="bg-blue-100 p-4 rounded shadow">
        <p class="text-xs text-gray-600">Total Target</p>
        <h3 class="text-xl font-bold">
            <?php echo e(number_format($targetSales, 2)); ?>

        </h3>
    </div>

    <div class="bg-green-100 p-4 rounded shadow">
        <p class="text-xs text-gray-600">Total Actual</p>
        <h3 class="text-xl font-bold">
            <?php echo e(number_format($actualSales, 2)); ?>

        </h3>
    </div>

    <div class="bg-orange-100 p-4 rounded shadow">
        <p class="text-xs text-gray-600">Total Variance</p>
        <h3 class="text-xl font-bold">
            <?php echo e(number_format($actualSales - $targetSales, 2)); ?>

        </h3>
    </div>

    <div class="bg-purple-100 p-4 rounded shadow">
        <p class="text-xs text-gray-600">Avg Achievement</p>
        <h3 class="text-xl font-bold">
            <?php echo e(number_format($percentage, 2)); ?>%
        </h3>
    </div>

</div>



<div class="bg-white rounded-xl p-6 shadow mb-6">

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-semibold text-[#002b3d] text-lg">
            Sales Performance
        </h4>

        <span class="text-sm font-semibold px-3 py-1 rounded-full
            <?php echo e($percent >= 100
                ? 'bg-[#4FBDBA] text-white'
                : ($percent >= 80
                    ? 'bg-[#BFF2EA] text-[#5F6472]'
                    : 'bg-[#FFA69E] text-white')); ?>">
            <?php echo e($percent >= 100 ? 'Achieved' : ($percent >= 80 ? 'On Track' : 'Needs Attention')); ?>

        </span>
    </div>

    <div class="w-full bg-[#FFF6E5] rounded-full h-4 overflow-hidden">
        <div
            class="h-4 rounded-full transition-all duration-500"
            style="
                width: <?php echo e($percent); ?>%;
                background-color:
                <?php echo e($percent >= 100 ? '#1f9bb6' : ($percent >= 80 ? '#fbbc05' : '#ff8a00')); ?>;
            "
        ></div>
    </div>

    <div class="flex justify-between text-sm mt-2 text-gray-600">
        <span><?php echo e($percent); ?>% achieved</span>
        <span>
            <?php echo e($variance >= 0 ? '+' : '-'); ?>

            <?php echo e(number_format(abs($variance), 2)); ?>

        </span>
    </div>

</div>


<div class="bg-white rounded-xl p-6 shadow mb-6">
    <h4 class="font-semibold text-[#002b3d] mb-3">
        Actual Sales Share by Branch
    </h4>
    <div style="height:260px;">
        <canvas id="branchPieChart"></canvas>
    </div>
</div>

</div> 


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

    
    <div class="bg-white rounded-xl p-6 shadow">
        <h4 class="font-semibold text-[#002b3d] mb-3">
            Actual vs Target Sales
        </h4>

        <div style="height:280px;">
            <canvas id="actualTargetChart"></canvas>
        </div>
    </div>

    
    <div class="bg-white rounded-xl p-6 shadow">
        <h4 class="font-semibold text-[#002b3d] mb-3">
            Average Sales Per Routing Day
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-3">
                <p class="text-[11px] text-blue-800 uppercase font-semibold">Period</p>
                <p class="text-sm font-bold text-blue-900">
                    Basis: Period <?php echo e($averagePeriodNo ?? request('period_to', $currentCutoff->period_no ?? $currentCutoff->period_number ?? now()->month)); ?>

                </p>
                <p class="text-xs text-blue-700 mt-1">
                    Selected Range:
                    P<?php echo e(request('period_from', $currentCutoff->period_no ?? $currentCutoff->period_number ?? now()->month)); ?>

                    to
                    P<?php echo e(request('period_to', $currentCutoff->period_no ?? $currentCutoff->period_number ?? now()->month)); ?>

                </p>
                <p class="text-xs text-blue-700 mt-1">
                    <?php echo e(optional($averagePeriodStart)->format('M d, Y')); ?> to <?php echo e(optional($averagePeriodEnd)->format('M d, Y')); ?>

                </p>
            </div>
            <div class="rounded-lg border border-indigo-200 bg-indigo-50 p-3">
                <p class="text-[11px] text-indigo-800 uppercase font-semibold">Routing Days</p>
                <p class="text-sm font-bold text-indigo-900"><?php echo e(number_format($routingDays ?? 0)); ?> days</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                <p class="text-[11px] text-gray-700 uppercase font-semibold">Routing Days Left</p>
                <p class="text-sm font-bold text-gray-900"><?php echo e(number_format($routingDaysLeft ?? 0)); ?> days</p>
                <p class="text-xs text-gray-700 mt-1">
                    As of <?php echo e(now()->format('M d, Y')); ?>

                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
            <div class="rounded-lg border border-orange-200 bg-orange-50 p-3">
                <p class="text-[11px] text-orange-800 uppercase font-semibold">Target Avg / Day</p>
                <p class="text-lg font-bold text-orange-900">&#8369;<?php echo e(number_format($averageTargetSales ?? 0, 2)); ?></p>
            </div>
            <div class="rounded-lg border border-cyan-200 bg-cyan-50 p-3">
                <p class="text-[11px] text-cyan-800 uppercase font-semibold">Actual Avg / Day</p>
                <p class="text-lg font-bold text-cyan-900">&#8369;<?php echo e(number_format($averageActualSales ?? 0, 2)); ?></p>
            </div>
        </div>
        <div style="height:280px;">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">

    
    <div class="bg-white rounded-xl p-6 shadow">
        <h4 class="font-semibold text-[#002b3d] mb-3">
            Actual vs Target by Category
        </h4>

        <div style="height:300px;">
            <canvas id="categoryActualTargetChart"></canvas>
        </div>
    </div>

    
    <div class="bg-white rounded-xl p-6 shadow">
        <h4 class="font-semibold text-[#002b3d] mb-3">
            Achievement % by Category
        </h4>

        <div style="height:300px;">
            <canvas id="categoryAchievementChart"></canvas>
        </div>
    </div>

 </div>
 
 
 <details class="mt-10 bg-white rounded-xl shadow p-6 border border-[#8ECAE6]">
     <summary class="font-semibold cursor-pointer text-lg">
         Consolidated Reports Overview
     </summary>

    <div class="mt-6 space-y-6">

        
        <p class="text-sm text-gray-600">
            Quick access to summarized financial and operational data.
        </p>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            
            <div class="border border-[#219EBC]/40 rounded-lg p-4 bg-[#8ECAE6]/20">
                <p class="text-sm font-semibold text-blue-700">Receipts</p>
                <p class="text-xs text-gray-500">Overall Total Gross Sales</p>
                <p class="text-sm font-bold text-blue-700 mb-2">
                    <?php echo e(number_format($totalReceiptGrossSales ?? 0, 2)); ?>

                </p>

                <p class="text-xs text-gray-500 mb-2">Total Remittance</p>

                <p class="text-lg font-bold text-blue-700">
                    <?php echo e(number_format($totalReceipts ?? 0, 2)); ?>

                </p>

                <p class="text-xs text-gray-400">
                    Based on receipt items
                </p>
            </div>

            

            
            <div class="border border-[#219EBC] rounded-lg p-4 bg-[#8ECAE6]/30">
                <p class="text-sm font-semibold text-green-700">Remittance</p>
                <p class="text-xs text-gray-500 mb-2">Total Remitted</p>

                <p class="text-lg font-bold text-green-700">
                    <?php echo e(number_format($totalRemittance ?? 0, 2)); ?>

                </p>

                <p class="text-xs text-gray-400">
                    Based on remittance items
                </p>
            </div>

            
            <div class="border rounded-lg p-4">
                <p class="text-sm font-semibold text-[#FB8500]">Receivables</p>
                <p class="text-xs text-gray-500 mb-2">Outstanding balances</p>

                <p class="text-lg font-bold">
                    <?php echo e(number_format($totalReceivables ?? 0, 2)); ?>

                </p>

                <div class="mt-2 text-xs text-gray-600 space-y-1">
                    <div>Account Receivables: <span class="font-semibold"><?php echo e(number_format($totalAccountReceivables ?? 0, 2)); ?></span></div>
                    <div>Receivable Collections: <span class="font-semibold"><?php echo e(number_format($totalReceivableCollections ?? 0, 2)); ?></span></div>
                    <div>Stock Transfer: <span class="font-semibold"><?php echo e(number_format($totalStockTransfers ?? 0, 2)); ?></span></div>
                    <div>Shortage Collections: <span class="font-semibold"><?php echo e(number_format($totalShortageCollections ?? 0, 2)); ?></span></div>
                </div>

                <p class="text-xs text-gray-400">
                    Based on receivable items
                </p>
            </div>

            
            <div class="border rounded-lg p-4">
                <p class="text-sm font-semibold text-[#023047]">Borrowers</p>
                <p class="text-xs text-gray-500 mb-2">Inventory movement</p>

                <p class="text-lg font-bold">
                    <?php echo e(number_format($netBorrowed ?? 0)); ?>

                </p>

                <div class="mt-2 text-xs text-gray-600 space-y-1">
                    <div>Total Borrowed: <span class="font-semibold"><?php echo e(number_format($totalBorrowed ?? 0)); ?></span></div>
                    <div>Total Returned: <span class="font-semibold"><?php echo e(number_format($totalReturned ?? 0)); ?></span></div>
                </div>

                <p class="text-xs text-gray-400">
                    Net borrowed (borrowed - returned)
                </p>
            </div>


        </div>

        


        </div>

        
        <div class="flex justify-end">
            <a href="<?php echo e(route('admin.reports.consolidated')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                View Full Consolidated Report &rarr;
            </a>
        </div>

    </div>
</details>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('consolidatedChart');

    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo e(Js::from($chartLabels)); ?>,
            datasets: [
                {
                    label: 'Actual Sales',
                    data: <?php echo e(Js::from($chartActual)); ?>,
                    borderWidth: 2
                },
                {
                    label: 'Target Sales',
                    data: <?php echo e(Js::from($chartTarget)); ?>,
                    borderWidth: 2
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ================= BAR: ACTUAL VS TARGET ================= */
    const barCtx = document.getElementById('actualTargetChart');

    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Actual Sales', 'Target Sales'],
                datasets: [{
                    data: [
                        <?php echo e($actual ?? 0); ?>,
                        <?php echo e($target ?? 0); ?>

                    ],
                    backgroundColor: ['#219EBC', '#FB8500'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.raw.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v.toLocaleString()
                        }
                    }
                }
            }
        });
    }

    /* ================= BAR: AVERAGE SALES (ROUTING DAYS) ================= */
    const lineCtx = document.getElementById('dailySalesChart');

    if (lineCtx) {
        const routingDays = Number(<?php echo e((int) ($routingDays ?? 0)); ?>);
        const averagePeriodNo = Number(<?php echo e((int) ($averagePeriodNo ?? request('period_to', 0))); ?>);

        new Chart(lineCtx, {
            type: 'bar',
            data: {
                labels: ['Target / Day', 'Actual / Day'],
                datasets: [
                    {
                        label: 'Average Sales Per Routing Day',
                        data: [
                            <?php echo e((float) ($averageTargetSales ?? 0)); ?>,
                            <?php echo e((float) ($averageActualSales ?? 0)); ?>

                        ],
                        backgroundColor: ['#FB8500', '#219EBC'],
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: `Basis Period ${averagePeriodNo} • Routing Days Used: ${routingDays}`,
                        color: '#475569',
                        font: { size: 12, weight: '600' },
                        padding: { bottom: 8 }
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `Average: ₱${Number(ctx.raw).toLocaleString(undefined, {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Peso Per Routing Day'
                        },
                        ticks: {
                            callback: v => Number(v).toLocaleString(undefined, {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 2
                            })
                        }
                    }
                }
            }
        });
    }

    /* ================= PIE: BRANCH SHARE ================= */
    const pieCtx = document.getElementById('branchPieChart');

    if (pieCtx) {
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: <?php echo e(Js::from($branchPieLabels ?? [])); ?>,
                datasets: [{
                    data: <?php echo e(Js::from($branchPieValues ?? [])); ?>,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.raw.toLocaleString()}`
                        }
                    }
                }
            }
        });
    }

});
</script>
<style>
.kpi {
    background: white;
    border-left-width: 4px;
    padding: 1rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}
.kpi p {
    font-size: 0.875rem;
    color: #6b7280;
}
.kpi h4 {
    font-size: 1.25rem;
    font-weight: 700;
}
.insight {
    background: white;
    padding: 1rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    font-size: 0.875rem;
    color: #374151;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ===============================
       BAR: ACTUAL vs TARGET (CATEGORY)
    =============================== */
    const catAT = document.getElementById('categoryActualTargetChart');

    if (catAT) {
        new Chart(catAT, {
            type: 'bar',
            data: {
                labels: ['CORE', 'PET CSD', 'STILLS'],
                datasets: [
                    {
                        label: 'Actual Sales',
                        data: [
                            <?php echo e($coreActual ?? 0); ?>,
                            <?php echo e($petcsdActual ?? 0); ?>,
                            <?php echo e($stillsActual ?? 0); ?>

                        ],
                        backgroundColor: ['#e63946', '#f4a261', '#219ebc'],
                        borderRadius: 6
                    },
                    {
                        label: 'Target Sales',
                        data: [
                            <?php echo e($coreTarget ?? 0); ?>,
                            <?php echo e($petcsdTarget ?? 0); ?>,
                            <?php echo e($stillsTarget ?? 0); ?>

                        ],
                        backgroundColor: ['#f1aeb5', '#fde2b3', '#bde0fe'],
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.raw.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v.toLocaleString()
                        }
                    }
                }
            }
        });
    }

    /* ===============================
       BAR: ACHIEVEMENT %
    =============================== */
    const catAch = document.getElementById('categoryAchievementChart');

    if (catAch) {
        new Chart(catAch, {
            type: 'bar',
            data: {
                labels: ['CORE', 'PET CSD', 'STILLS'],
                datasets: [{
                    label: 'Achievement %',
                    data: [
                        <?php echo e($coreAchievement ?? 0); ?>,
                        <?php echo e($petcsdAchievement ?? 0); ?>,
                        <?php echo e($stillsAchievement ?? 0); ?>

                    ],
                    backgroundColor: ['#e63946', '#f4a261', '#219ebc'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.raw + '%'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 120,
                        ticks: {
                            callback: v => v + '%'
                        }
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






<?php /**PATH C:\xampp\htdocs\sales-report\resources\views/admin/consolidated/dashboard.blade.php ENDPATH**/ ?>