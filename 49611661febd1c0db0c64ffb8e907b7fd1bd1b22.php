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
        <h2 class="text-2xl font-bold text-blue-700">
            Add Bank
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="w-full bg-white rounded-lg shadow p-8">

        
        <?php if($errors->any()): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.banks.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-2 gap-6">

                
                <div class="relative">
                    <label class="block text-sm font-semibold mb-1">Bank Name</label>

                    <input type="text"
                        id="bank_search"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Type bank or branch name"
                        autocomplete="off">

                    
                    <input type="hidden" name="bank_name" id="bank_name">

                    <div id="bank_results"
                        class="absolute z-50 w-full bg-white border rounded shadow hidden max-h-48 overflow-y-auto">
                    </div>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold mb-1">Branch Name</label>
                    <input type="text"
                        name="branch_name"
                        id="branch_name"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold mb-1">Account Holder Name</label>
                    <input type="text"
                        name="account_holder_name"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

                
                <div>
                    <label class="block text-sm font-semibold mb-1">Account Number</label>
                    <input type="text"
                        name="account_number"
                        id="account_number"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

                
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Routing Number</label>
                    <input type="text"
                        name="routing_number"
                        id="routing_number"
                        class="w-full border rounded px-3 py-2"
                        required>
                </div>

            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="<?php echo e(route('admin.banks.index')); ?>"
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Save Bank
                </button>
            </div>
        </form>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('bank_search');
            const resultsBox  = document.getElementById('bank_results');

            searchInput.addEventListener('input', async () => {
                const query = searchInput.value.trim();

                if (query.length < 1) {
                    resultsBox.classList.add('hidden');
                    resultsBox.innerHTML = '';
                    return;
                }

                try {
                    const res = await fetch(`<?php echo e(route('admin.banks.search')); ?>?q=${encodeURIComponent(query)}`);
                    const banks = await res.json();

                    resultsBox.innerHTML = '';
                    resultsBox.classList.remove('hidden');

                    if (!banks.length) {
                        resultsBox.innerHTML =
                            `<div class="px-3 py-2 text-gray-500 text-sm">No matching banks</div>`;
                        return;
                    }

                    banks.forEach(bank => {
                        const div = document.createElement('div');
                        div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm';
                        div.innerHTML = `
                            <strong>${bank.bank_name}</strong><br>
                            <span class="text-xs text-gray-500">${bank.branch_name}</span>
                        `;

                        div.onclick = () => {
                            searchInput.value = `${bank.bank_name} — ${bank.branch_name}`;
                            document.getElementById('bank_name').value = bank.bank_name;
                            document.getElementById('branch_name').value = bank.branch_name;
                            document.getElementById('account_number').value = bank.account_number ?? '';
                            document.getElementById('routing_number').value = bank.routing_number ?? '';
                            resultsBox.classList.add('hidden');
                        };

                        resultsBox.appendChild(div);
                    });

                } catch (e) {
                    console.error('Bank search failed', e);
                }
            });

            document.addEventListener('click', e => {
                if (!searchInput.contains(e.target)) {
                    resultsBox.classList.add('hidden');
                }
            });
        });
    </script>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\bank-management\create.blade.php ENDPATH**/ ?>