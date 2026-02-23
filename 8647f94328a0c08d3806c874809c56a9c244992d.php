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
        <h2 class="text-2xl font-semibold text-facebookBlue">💰 Collection Report - Treasury</h2>
     <?php $__env->endSlot(); ?>

   <div class="bg-white p-6 rounded shadow" x-data="treasuryColumnToggle()" x-init="init()">

        <!-- 🔽 Filter Section (copied from index.blade.php) -->
        <div x-data="{ open: true }" class="mb-4">
            <button @click="open = !open" type="button" class="text-sm text-blue-600 underline mb-2">
                <template x-if="open">🔽 Hide Filters</template>
                <template x-if="!open">▶️ Show Filters</template>
            </button>

            <form x-show="open" method="GET" action="<?php echo e(route('admin.reports.index')); ?>"
                class="flex flex-wrap gap-2 items-end">
                
                <!-- Month -->
                <div>
                    <label for="month" class="block text-sm font-medium">Month</label>
                    <select name="month" id="month" class="border rounded p-2" onchange="this.form.submit()">
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
                    <select name="week" id="week" class="border rounded p-2" onchange="this.form.submit()">
                        <option value="all" <?php echo e(request('week') == 'all' ? 'selected' : ''); ?>>All Weeks</option>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(request('week') == $i ? 'selected' : ''); ?>>Week <?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Report Type -->
                <div>
                    <label for="report_type" class="block text-sm font-medium">Report Type</label>
                    <select name="report_type" id="report_type" class="border rounded p-2" onchange="this.form.submit()">
                        <option value="standard" <?php echo e(request('report_type') === 'standard' ? 'selected' : ''); ?>>📊 Daily Sales Report</option>
                        <option value="treasury" <?php echo e(request('report_type') === 'treasury' ? 'selected' : ''); ?>>💰 Collection Report</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label for="perPage" class="block text-sm font-medium">Show</label>
                    <select name="perPage" id="perPage" onchange="this.form.submit()" class="border rounded p-2">
                        <option value="50" <?php echo e(request('perPage') == 50 ? 'selected' : ''); ?>>50</option>
                        <option value="100" <?php echo e(request('perPage') == 100 ? 'selected' : ''); ?>>100</option>
                        <option value="300" <?php echo e(request('perPage') == 300 ? 'selected' : ''); ?>>300</option>
                        <option value="all" <?php echo e(request('perPage') == 'all' ? 'selected' : ''); ?>>All</option>
                    </select>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-700">
                        🔍 Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="mb-4 flex justify-end gap-2">
            <div>
                <a href="<?php echo e(route('admin.reports.treasury.export.csv', request()->query())); ?>"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-medium inline-block">
                    📤 Export Treasury CSV
                </a>
            </div>

            <div>
                <a href="<?php echo e(route('admin.reports.treasury.print', request()->query())); ?>"
                target="_blank"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 text-sm font-medium inline-block">
                    🖨️ Print View
                </a>
            </div>
        </div>


    <!-- 🧩 Column Toggle -->
     <div class="mb-4 p-2 border rounded bg-gray-50">
        <div class="text-sm font-medium mb-2">🧩 Toggle Columns:</div>
        <div class="flex flex-wrap gap-2 items-center">
            <template x-for="field in fields" :key="field">
                <label class="flex items-center space-x-1">
                    <input type="checkbox" :checked="columns[field]" @change="toggle(field)" class="rounded">
                    <span x-text="field"></span>
                </label>
            </template>
            <button @click="toggleAll(true)" class="ml-4 px-2 py-1 text-xs bg-blue-600 text-white rounded">Show All</button>
            <button @click="toggleAll(false)" class="px-2 py-1 text-xs bg-red-600 text-white rounded">Hide All</button>
        </div>
    </div>


        <!-- 📋 Table Section -->
        <div class="mb-4 p-2 border rounded bg-gray-50">
            <table class="min-w-full table-auto border border-black text-sm">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-black px-4 py-2 sticky left-0 bg-gray-200">📅 Date</th>
                        <?php
                            $displayFields = array_filter($fields, fn($f) => $f !== 'ABI Remittance');
                        ?>
                        <?php $__currentLoopData = $displayFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="border border-black px-4 py-2 text-center" x-show="isVisible(`<?php echo e($field); ?>`)">
                                <?php echo e($field); ?>

                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $dailyData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false" :style="hover ? 'background-color: #fff3cd;' : ''">
                            <td class="border border-black px-4 py-2" :style="hover ? 'background-color: #fff3cd;' : ''">
                                <?php echo e($data['date']); ?>

                            </td>
                            <?php
                                $displayFields = array_filter($fields, fn($f) => $f !== 'ABI Remittance');
                            ?>

                            <?php $__currentLoopData = $displayFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="border border-black px-2 py-1 text-right"
                                    title="<?php echo e($field); ?>"
                                    x-show="isVisible(`<?php echo e($field); ?>`)">
                                    <?php echo e(number_format($data[$field] ?? 0, 2)); ?>

                                </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Totals Row -->
                    <tr class="bg-gray-100 font-bold text-right">
                        <td class="border border-black px-4 py-2 text-left">🔢 Total per Column</td>
                        <?php
                            $displayFields = array_filter($fields, fn($f) => $f !== 'ABI Remittance');
                        ?>

                        <?php $__currentLoopData = $displayFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="border border-black px-2 py-1 text-right"
                                title="<?php echo e($field); ?>"
                                x-show="isVisible(`<?php echo e($field); ?>`)">
                                <?php echo e(number_format($totals[$field] ?? 0, 2)); ?>

                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Summary Totals -->
        <div class="mt-6 text-sm font-medium space-y-1">
            <p>💵 <strong>Total Collection (Cash):</strong> ₱<?php echo e(number_format($totalCash, 2)); ?></p>
            <p>🏦 <strong>Total Collection (Check):</strong> ₱<?php echo e(number_format($totalCheck, 2)); ?></p>
            <p>🧾 <strong>Total Collection (Cash + Check):</strong> ₱<?php echo e(number_format($totalCombined, 2)); ?></p>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<script>
    function treasuryColumnToggle() {
        return {
            columns: {},
            fields: <?php echo json_encode(array_values(array_filter($fields, fn($f) => $f !== 'ABI Remittance')), 512) ?>,
            init() {
                const saved = JSON.parse(localStorage.getItem('treasury_columns') || '{}');
                this.fields.forEach(f => {
                    this.columns[f] = saved[f] !== undefined ? saved[f] : true;
                });
                localStorage.setItem('treasury_columns', JSON.stringify(this.columns));
            },
            toggle(field) {
                this.columns[field] = !this.columns[field];
                localStorage.setItem('treasury_columns', JSON.stringify(this.columns));
            },
            isVisible(field) {
                return this.columns[field];
            },
            toggleAll(state) {
                this.fields.forEach(f => this.columns[f] = state);
                localStorage.setItem('treasury_columns', JSON.stringify(this.columns));
            }
        }
    }
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\reports\treasury.blade.php ENDPATH**/ ?>