
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
        <h2 class="text-2xl font-semibold text-facebookBlue">📈 Sales Inputs</h2>
     <?php $__env->endSlot(); ?>

    
    <div class="mb-4 flex justify-between items-center flex-wrap gap-2">
        <a href="<?php echo e(route('admin.sales-inputs.create')); ?>"
           class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-600">
            ➕ Add Sales Input
        </a>

        <form method="GET" action="<?php echo e(route('admin.sales-inputs.index')); ?>"
              class="flex flex-wrap items-center gap-2">
            <label for="division_id" class="text-sm font-medium">Division:</label>
            <select name="division_id" id="division_id" class="border rounded p-2">
                <option value="">All Divisions</option>
                <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($division->id); ?>"
                        <?php echo e(request('division_id') == $division->id ? 'selected' : ''); ?>>
                        <?php echo e($division->division_name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <label for="filter_date" class="text-sm font-medium">Date:</label>
            <input type="date" name="filter_date" id="filter_date"
                   value="<?php echo e(request('filter_date')); ?>" class="border rounded p-2">

            <label for="summary_field" class="text-sm font-medium">Field:</label>
            <input type="text" name="summary_field" placeholder="e.g. Gross"
                   value="<?php echo e(request('summary_field')); ?>" class="border rounded p-2">

            <label for="perPage" class="text-sm font-medium">Show:</label>
            <select name="perPage" id="perPage" onchange="this.form.submit()"
                    class="border rounded p-2">
                <option value="50"  <?php echo e(request('perPage') == 50  ? 'selected' : ''); ?>>50</option>
                <option value="100" <?php echo e(request('perPage') == 100 ? 'selected' : ''); ?>>100</option>
                <option value="300" <?php echo e(request('perPage') == 300 ? 'selected' : ''); ?>>300</option>
                <option value="all" <?php echo e(request('perPage') == 'all' ? 'selected' : ''); ?>>All</option>
            </select>

            <button type="submit"
                    class="bg-facebookBlue text-white px-3 py-2 rounded hover:bg-blue-700">
                🔍 Filter
            </button>
            <a href="<?php echo e(route('admin.sales-inputs.index')); ?>"
               class="text-sm text-gray-600 hover:underline ml-2">Clear</a>
        </form>
    </div>

    
    <?php if(session('success')): ?>
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white p-6 rounded shadow overflow-x-auto" 
        x-data="{
            showCols: JSON.parse(localStorage.getItem('showCols')) || {
                division: true,
                date: true,
                submitted_at: true,
                summary: true,
                sales: true,
                remittance: true,
                cash: true,
                check: true,
                collection: true,
                validated: true,
                overage: true,
                shortage: true,
                account: true,
                control: true,
                receipt: true,
                remarks: true,
                actions: true
            },
            open: false
        }"
        x-init="$watch('showCols', val => localStorage.setItem('showCols', JSON.stringify(val)))"
        >

     <div class="mb-4 relative z-20">
        <button @click="open = !open" class="bg-gray-200 px-3 py-1 rounded shadow hover:bg-gray-300">
            ⚙️ Toggle Columns
        </button>

        <div x-show="open" @click.away="open = false" 
            class="absolute mt-2 bg-white border rounded shadow p-3 space-y-1 z-50 max-h-96 overflow-auto text-sm w-64">

            <!-- Master Toggle -->
            <div class="pb-2 mb-2 border-b">
                <label class="flex items-center space-x-2 font-semibold">
                    <input type="checkbox"
                        @change="Object.keys(showCols).forEach(key => showCols[key] = $event.target.checked)"
                        :checked="Object.values(showCols).every(v => v)"
                        class="rounded">
                    <span>Select All</span>
                </label>
            </div>

            <!-- Individual Toggles -->
            <template x-for="[key, value] in Object.entries(showCols)" :key="key">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" x-model="showCols[key]" class="rounded">
                    <span x-text="key.replaceAll('_', ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                </label>
            </template>
        </div>

        <?php
            // decide heading layout from very first row (safe even if $inputs empty)
            $firstInput = $inputs->first();
            $isTreasuryHead = strtolower(optional($firstInput?->division)->division_name ?? '') === 'treasury gmc main office';

            // helper for column sorting links
            function sortLink($label, $field, $sortField, $sortOrder) {
                $newOrder = ($sortField === $field && $sortOrder === 'asc') ? 'desc' : 'asc';
                $arrow    = $sortField === $field ? ($sortOrder === 'asc' ? ' ▲' : ' ▼') : '';
                $url      = request()->fullUrlWithQuery(['sort_by' => $field, 'order' => $newOrder]);
                return "<a href=\"$url\" class=\"hover:underline text-blue-600\">$label$arrow</a>";
            }

            // classify cash / check labels
            $cashLabels  = [
                'cash payment','loans','ada','loan availment','share capital','savings',
                'time deposit','gsef','mutual aid','gari funds','climbs insurance','raffle','other accounts',
            ];
            $checkLabels = [
                'check payment','loans (check)','ada (check)','loan availment (check)','share capital (check)',
                'savings (check)','time deposit (check)','gsef (check)','mutual aid (check)','gari funds (check)',
                'climbs insurance (check)',
            ];
        ?>

        <?php if($inputs instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
            <div class="mb-4">
                <?php echo e($inputs->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>

        <table class="w-full table-auto border-collapse">

            
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="px-4 py-2" x-show="showCols.division">Division</th>
                    <th class="px-4 py-2 whitespace-nowrap w-auto" x-show="showCols.date"><?php echo sortLink('Date', 'date', $sortField ?? '', $sortOrder ?? ''); ?></th>
                    <th class="px-4 py-2" x-show="showCols.submitted_at"><?php echo sortLink('Submitted At', 'created_at', $sortField ?? '', $sortOrder ?? ''); ?></th>
                    <th class="px-4 py-2 whitespace-nowrap w-auto" x-show="showCols.summary">Input Summary</th>

                    <?php if($isTreasuryHead): ?>
                        <th class="px-4 py-2" x-show="showCols.cash">Total Collection (Cash)<br><span class="text-xs text-gray-500">(for treasury)</span></th>
                        <th class="px-4 py-2" x-show="showCols.check">Total Collection (Check)<br><span class="text-xs text-gray-500">(for treasury)</span></th>
                        <th class="px-4 py-2" x-show="showCols.collection">Total Collection<br><span class="text-xs text-gray-500">(Cash + Check)</span></th>
                    <?php else: ?>
                        <th class="px-4 py-2" x-show="showCols.sales">Total Sales<br><span class="text-xs text-gray-500">(for sales)</span></th>
                        <th class="px-4 py-2" x-show="showCols.remittance">Total Remittance<br><span class="text-xs text-gray-500">(for sales)</span></th>
                    <?php endif; ?>

                    <?php if(!$isTreasuryHead): ?>
                        <th class="px-4 py-2" x-show="showCols.validated">✅ Validated Remittance</th>
                        <th class="px-4 py-2 text-green-700" x-show="showCols.overage">Validated Overage</th>
                        <th class="px-4 py-2 text-red-700" x-show="showCols.shortage">Validated Shortage</th>
                        <th class="px-4 py-2 text-center" x-show="showCols.account">Account #</th>
                        <th class="px-4 py-2 text-center" x-show="showCols.control">Control #</th>
                        <th class="px-4 py-2 text-center" x-show="showCols.receipt">📷 Receipt</th>
                        <th class="px-4 py-2 text-center" x-show="showCols.remarks">Remarks</th>
                    <?php endif; ?>

                    <th class="px-4 py-2" x-show="showCols.actions">Actions</th>
                </tr>
            </thead>



            
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $inputs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $userRole = auth()->user()->role;
                        $userDivision = strtolower(optional(auth()->user()->division)->division_name ?? '');
                        $canDeleteReceipt = $userRole === 'admin' || ($userRole === 'user' && $userDivision === 'treasury gmc main office');
                    ?>
                    <?php
                        $isTreasury = strtolower(optional($input->division)->division_name ?? '') === 'treasury gmc main office';

                        $cash=$irs=$cheque=$credit=$overage=$shortage=$arCollections=$shortageCollection=0;
                        $totalCash=$totalCheck=0;

                        foreach ($input->items as $item) {
                            $label = strtolower($item->field_label);
                            $value = floatval(str_replace(',', '', $item->value));

                            match ($label) {
                                'cash sales'             => $cash             = $value,
                                'irs sales'              => $irs              = $value,
                                'cheque sales'           => $cheque           = $value,
                                'credit sales'           => $credit           = $value,
                                'cash overage'           => $overage          = $value,
                                'cash shortage'          => $shortage         = $value,
                                'ar collections'         => $arCollections    = $value,
                                'collection on shortages'=> $shortageCollection = $value,
                                default => null
                            };

                            if (in_array($label,$cashLabels))  $totalCash  += $value;
                            if (in_array($label,$checkLabels)) $totalCheck += $value;
                        }

                        $totalSales      = $cash + $irs + $cheque + $credit + $overage - $shortage;
                        $totalRemittance = $cash + $overage + $arCollections + $shortageCollection - $shortage;
                        $totalCollection = $totalCash + $totalCheck;

                        $vr = \App\Models\ValidatedRemittance::where('division_id', $input->division_id)
                            ->whereDate('date', $input->date)
                            ->first();
                    ?>

                    <tr class="border-t">
                        <td class="px-4 py-2" x-show="showCols.division"> <?php echo e(optional($input->division)->division_name ?? '-'); ?>


                        <td class="px-4 py-2 whitespace-nowrap w-auto" x-show="showCols.date">
                            <?php echo e(\Carbon\Carbon::parse($input->date)->format('F j, Y')); ?>

                        </td>

                        <td class="px-4 py-2 text-sm text-gray-600" x-show="showCols.submitted_at">
                            <?php echo e($input->created_at->timezone('Asia/Manila')->format('M d, Y - h:i A')); ?>

                        </td>

                        <td class="px-4 py-2 whitespace-nowrap w-auto" x-show="showCols.summary">
                            <ul class="text-sm list-disc list-inside">
                                <?php $__currentLoopData = $input->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(request('summary_field') === null ||
                                        str_contains(strtolower($item->field_label), strtolower(request('summary_field')))): ?>
                                        <li>
                                            <strong><?php echo e($item->field_label); ?>:</strong>
                                            <?php echo e(is_numeric($item->value) ? number_format($item->value, 2) : $item->value); ?>

                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>

                        <?php if($isTreasury): ?>
                            <td class="px-4 py-2 font-semibold text-blue-600" x-show="showCols.cash"><?php echo e(number_format($totalCash, 2)); ?></td>
                            <td class="px-4 py-2 font-semibold text-blue-600" x-show="showCols.check"><?php echo e(number_format($totalCheck, 2)); ?></td>
                            <td class="px-4 py-2 font-semibold text-purple-700" x-show="showCols.collection"><?php echo e(number_format($totalCollection, 2)); ?></td>
                        <?php else: ?>
                            <td class="px-4 py-2 font-semibold text-green-600" x-show="showCols.sales"><?php echo e(number_format($totalSales, 2)); ?></td>
                            <td class="px-4 py-2 font-semibold text-indigo-600" x-show="showCols.remittance"><?php echo e(number_format($totalRemittance, 2)); ?></td>
                        <?php endif; ?>

                        <?php if(!$isTreasury): ?>
                        <?php
                            $userRole = auth()->user()->role;
                            $userDivision = strtolower(auth()->user()->division->division_name ?? '');
                            $canEdit = $userRole === 'admin' || ($userRole === 'user' && $userDivision === 'treasury gmc main office');
                        ?>

                            <td class="px-4 py-2" x-show="showCols.validated">
                                <?php if($canEdit): ?>
                                    <div class="flex items-center space-x-2">
                                        <input type="text"
                                            value="<?php echo e(number_format(optional($vr)->validated_amount ?: 0, 2, '.', '')); ?>"
                                            data-raw="<?php echo e(optional($vr)->validated_amount ?: '0.00'); ?>"
                                            class="validated-input border rounded px-2 py-1 w-32 text-right"
                                            data-division="<?php echo e($input->division_id); ?>"
                                            data-date="<?php echo e($input->date); ?>" />

                                        <label class="bg-gray-100 text-sm px-2 py-1 rounded border cursor-pointer hover:bg-gray-200">
                                            📎
                                        <input 
                                            type="file" 
                                            accept="image/*" 
                                            multiple
                                            class="hidden receipt-upload"
                                            data-division="<?php echo e($input->division_id); ?>"
                                            data-date="<?php echo e($input->date); ?>"
                                        />
                                        </label>
                                    </div>
                                <?php else: ?>
                                    <?php echo e(number_format(optional($vr)->validated_amount ?: 0, 2)); ?>

                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-2 text-green-700 text-sm validated-overage" 
                                data-division="<?php echo e($input->division_id); ?>" 
                                data-date="<?php echo e($input->date); ?>" 
                                x-show="showCols.overage">
                                <?php echo e($vr && $vr->validated_overage > 0 ? number_format($vr->validated_overage, 2) : '-'); ?>

                            </td>

                            <td class="px-4 py-2 text-red-700 text-sm validated-shortage" 
                                data-division="<?php echo e($input->division_id); ?>" 
                                data-date="<?php echo e($input->date); ?>" 
                                x-show="showCols.shortage">
                                <?php echo e($vr && $vr->validated_shortage > 0 ? number_format($vr->validated_shortage, 2) : '-'); ?>

                            </td>

                            <td class="px-4 py-2 text-center" x-show="showCols.account">
                                <?php if($canEdit): ?>
                                    <input type="text"
                                        value="<?php echo e(optional($vr)->account_number); ?>"
                                        class="account-input border rounded px-2 py-1 text-center w-28"
                                        data-division="<?php echo e($input->division_id); ?>"
                                        data-date="<?php echo e($input->date); ?>"
                                        data-field="account_number" />
                                <?php else: ?>
                                    <span class="text-gray-600"><?php echo e(optional($vr)->account_number ?: '-'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-2 text-center" x-show="showCols.control">
                                <?php if($canEdit): ?>
                                    <input type="text"
                                        value="<?php echo e(optional($vr)->control_number); ?>"
                                        class="account-input border rounded px-2 py-1 text-center w-28"
                                        data-division="<?php echo e($input->division_id); ?>"
                                        data-date="<?php echo e($input->date); ?>"
                                        data-field="control_number" />
                                <?php else: ?>
                                    <span class="text-gray-600"><?php echo e(optional($vr)->control_number ?: '-'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-2 text-center" x-show="showCols.receipt">
                            <?php if($vr && $vr->receipts && $vr->receipts->count()): ?>
                                <div class="flex flex-col items-center space-y-1">
                                    <?php $__currentLoopData = $vr->receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between w-full space-x-2">
                                            <button 
                                                onclick="showReceiptModal('<?php echo e($receipt->id); ?>')" 
                                                class="text-blue-600 underline text-sm">
                                                📎 View
                                            </button>
                                            <?php if($canDeleteReceipt): ?>
                                                <button 
                                                    onclick="deleteReceipt('<?php echo e($receipt->id); ?>')" 
                                                    class="text-red-600 hover:underline text-sm">
                                                    🗑️
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <div id="modal-<?php echo e($receipt->id); ?>" 
                                            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50"
                                            onclick="handleBackdropClick(event, '<?php echo e($receipt->id); ?>')">
                                            <div class="relative bg-white p-4 rounded shadow-lg" onclick="event.stopPropagation()">
                                                <button onclick="closeModal('<?php echo e($receipt->id); ?>')" 
                                                        class="absolute top-2 right-2 text-gray-700 hover:text-red-600 text-2xl font-bold leading-none">
                                                    &times;
                                                </button>
                                                <div x-data="imageViewer()" class="flex flex-col items-center space-y-4">
                                                    <div class="flex space-x-2">
                                                        <button @click="rotate += 90" class="px-2 py-1 bg-yellow-100 rounded hover:bg-yellow-200">🔁 Rotate</button>
                                                        <button @click="scale = 1; rotate = 0; offsetX = 0; offsetY = 0" class="px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">🔄 Reset</button>
                                                    </div>
                                                    <div
                                                        class="overflow-hidden border rounded bg-gray-50 cursor-move"
                                                        style="max-height: 70vh; max-width: 90vw;"
                                                        @wheel.prevent="onWheel"
                                                        @mousedown="startDrag"
                                                        @mousemove="onDrag"
                                                        @mouseup="endDrag"
                                                        @mouseleave="endDrag"
                                                    >
                                                        <img
                                                            src="<?php echo e(asset('storage/' . $receipt->file_path)); ?>"
                                                            alt="Receipt"
                                                            class="transition-transform duration-200 select-none"
                                                            :style="`transform: translate(${offsetX}px, ${offsetY}px) scale(${scale}) rotate(${rotate}deg); transform-origin: center;`"
                                                            draggable="false"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 italic">None</span>
                            <?php endif; ?>

                            </td>

                            <td class="px-4 py-2 text-center" x-show="showCols.remarks">
                                <?php if($canEdit): ?>
                                    <input type="text"
                                        value="<?php echo e(optional($vr)->remarks); ?>"
                                        class="account-input border rounded px-2 py-1 text-center w-36"
                                        data-division="<?php echo e($input->division_id); ?>"
                                        data-date="<?php echo e($input->date); ?>"
                                        data-field="remarks" />
                                <?php else: ?>
                                    <span class="text-gray-600"><?php echo e(optional($vr)->remarks ?: '-'); ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>

                        <td class="px-4 py-2 text-sm space-x-2" x-show="showCols.actions">
                            <a href="<?php echo e(route('admin.sales-inputs.edit', $input->id)); ?>" class="text-blue-600 hover:underline">✏️ Edit</a>
                            <?php if(auth()->user()->role === 'admin'): ?>
                                <form action="<?php echo e(route('admin.sales-inputs.destroy', $input->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this sales input?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:underline">🗑️ Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="px-4 py-4 text-center text-gray-500">
                            No sales inputs found.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>

        
        <?php if($inputs instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
            <div class="mt-4">
                <?php echo e($inputs->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
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

<script>
document.querySelectorAll('.validated-input').forEach(input => {
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });

    input.addEventListener('focus', function () {
        const raw = this.dataset.raw || this.value.replace(/[₱,]/g, '');
        this.value = raw;
    });

    input.addEventListener('blur', function () {
        const raw = this.value.replace(/[₱,]/g, '');
        const number = parseFloat(raw);
        if (!isNaN(number)) {
            this.dataset.raw = number.toFixed(2);
            this.value = '₱' + number.toLocaleString('en-US', { minimumFractionDigits: 2 });
        } else {
            this.dataset.raw = '0.00';
            this.value = '';
        }
        this.dispatchEvent(new Event('change'));
    });

    input.addEventListener('change', function () {
        const divisionId = this.dataset.division;
        const date = this.dataset.date;

        const raw = this.value.replace(/[₱,]/g, '');
        const clean = parseFloat(raw);
        this.dataset.raw = isNaN(clean) ? '0.00' : clean.toFixed(2);
        this.value = '₱' + Number(this.dataset.raw).toLocaleString('en-US', { minimumFractionDigits: 2 });

        const validatedAmount = this.dataset.raw;

        fetch('/admin/validated-remittance/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            },
            body: JSON.stringify({
                division_id: divisionId,
                date: date,
                validated_amount: validatedAmount
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log('✅ Saved:', data);

            const overageCell = document.querySelector(
                `.validated-overage[data-division="${divisionId}"][data-date="${date}"]`
            );
            const shortageCell = document.querySelector(
                `.validated-shortage[data-division="${divisionId}"][data-date="${date}"]`
            );

            if (overageCell) {
                overageCell.textContent =
                    data.validated_overage > 0
                        ? Number(data.validated_overage).toLocaleString('en-US', { minimumFractionDigits: 2 })
                        : '-';
            }

            if (shortageCell) {
                shortageCell.textContent =
                    data.validated_shortage > 0
                        ? Number(data.validated_shortage).toLocaleString('en-US', { minimumFractionDigits: 2 })
                        : '-';
            }
        })
        .catch(err => {
            alert('❌ Error saving value.');
            console.error(err);
        });
    });

    const rawInitial = input.value.replace(/[₱,]/g, '');
    const numberInitial = parseFloat(rawInitial);
    if (!isNaN(numberInitial)) {
        input.dataset.raw = numberInitial.toFixed(2);
        input.value = '₱' + numberInitial.toLocaleString('en-US', { minimumFractionDigits: 2 });
    }
});

// ✅ Inline editing for Account and Control Number
document.querySelectorAll('.account-input').forEach(input => {
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });

    input.addEventListener('blur', function () {
        const divisionId = this.dataset.division;
        const date = this.dataset.date;
        const field = this.dataset.field;
        const value = this.value;

        fetch('/admin/validated-remittance/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            },
            body: JSON.stringify({
                division_id: divisionId,
                date: date,
                [field]: value
            })
        })
        .then(res => res.json())
        .then(data => {
            console.log(`✅ ${field} saved:`, data);
        })
        .catch(err => {
            alert(`❌ Failed to save ${field}`);
            console.error(err);
        });
    });
});

// ✅ Receipt Upload Handling
document.querySelectorAll('.receipt-upload').forEach(input => {
    input.addEventListener('change', function () {
        const file = this.files[0];
        const divisionId = this.dataset.division;
        const date = this.dataset.date;

        if (!file) return;

        const formData = new FormData();
        formData.append('division_id', divisionId);
        formData.append('date', date);
        formData.append('validated_amount', 0); // required, placeholder
        formData.append('receipt_image', file);

        fetch('/admin/validated-remittance/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert('✅ Receipt uploaded!');
            location.reload();
        })
        .catch(err => {
            alert('❌ Upload failed.');
            console.error(err);
        });
    });
});

// ✅ Modal open/close handling
function showReceiptModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
}
</script>

<script>
    
function deleteReceipt(id) {
    if (!confirm('Receipt to be deleted?')) return;

    fetch(`/admin/validated-remittance/delete-receipt/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message || 'Receipt Deleted');
        location.reload();
    })
    .catch(err => {
        alert('❌ Error clearing receipt.');
        console.error(err);
    });
}
</script>

<script>
function imageViewer() {
    return {
        scale: 1,
        rotate: 0,
        offsetX: 0,
        offsetY: 0,
        isDragging: false,
        startX: 0,
        startY: 0,

        onWheel(e) {
            const direction = e.deltaY < 0 ? 1 : -1;
            const factor = 0.1 * direction;
            this.scale = Math.min(Math.max(this.scale + factor, 0.2), 5);
        },

        startDrag(e) {
            this.isDragging = true;
            this.startX = e.clientX - this.offsetX;
            this.startY = e.clientY - this.offsetY;
        },

        onDrag(e) {
            if (!this.isDragging) return;
            this.offsetX = e.clientX - this.startX;
            this.offsetY = e.clientY - this.startY;
        },

        endDrag() {
            this.isDragging = false;
        }
    };
}
</script>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\sales-inputs\index.blade.php ENDPATH**/ ?>