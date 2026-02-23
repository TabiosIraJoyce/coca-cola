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
        <h2 class="text-2xl font-semibold text-[#1877F2]">➕ Sales Input</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow max-w-4xl mx-auto">

        <?php if(session('success')): ?>
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-4 p-4 bg-green-100 text-green-800 border rounded">
                <?php echo e(session('success')); ?>

                <button type="button" @click="show = false" class="ml-4 text-sm underline">Dismiss</button>
            </div>
        <?php endif; ?>

        <form method="GET" action="<?php echo e(route('admin.sales-inputs.create')); ?>" class="mb-6" x-data="{ loading: false }" @submit="loading = true">
            <div class="flex items-end space-x-4">
                <div class="flex-1">
                    <label for="division_id" class="block font-semibold">Select Division</label>
                    <select name="division_id" id="division_id" class="w-full border border-gray-300 p-2 rounded" required>
                        <option value="">-- Choose Division --</option>
                        <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($division->id); ?>" <?php echo e(request('division_id') == $division->id ? 'selected' : ''); ?>>
                                <?php echo e($division->division_name); ?> (<?php echo e($division->businessLine->name); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit"
                        class="text-white px-4 py-2 rounded"
                        style="background-color: #1877F2;"
                        onmouseenter="this.style.backgroundColor='#1558B0'"
                        onmouseleave="this.style.backgroundColor='#1877F2'">
                    Load Fields
                </button>
            </div>
        </form>

        <?php if($selectedDivision && count($templateFields)): ?>
                <?php
                    $isCreditGMC = $selectedDivision->division_name === 'Credit GMC Main Office';

                    $fieldsRight = collect($templateFields)->filter(function ($f) {
                        return str_contains(strtolower($f->field_label), 'check')
                            && strtolower(trim($f->field_label)) !== 'other accounts';
                    })->values();

                    $fieldsLeft = collect($templateFields)->reject(function ($f) use ($fieldsRight) {
                        return $fieldsRight->contains('field_label', $f->field_label);
                    })->values();
                ?>

            <div x-data="{ showForm: true }">
                <button @click="showForm = !showForm" type="button" class="mb-4 text-sm text-[#1877F2] underline">
                    <template x-if="showForm">🔽 Hide Sales Form</template>
                    <template x-if="!showForm">▶️ Show Sales Form</template>
                </button>

                <div x-show="showForm" x-transition>
                    <form action="<?php echo e(route('admin.sales-inputs.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <!-- 📆 Date Input -->
                        <div x-data="{ date: '<?php echo e(old('date', date('Y-m-d'))); ?>' }" class="mb-4">
                            <label for="date" class="block font-semibold">Sales Date</label>
                            <input type="date" name="date" id="date" x-model="date" class="w-full border border-gray-300 p-2 rounded" required>
                            <div class="mt-2 flex gap-2 text-sm">
                                <button type="button" @click="date = '<?php echo e(date('Y-m-d')); ?>'" class="px-2 py-1 border rounded hover:bg-gray-100">Today</button>
                                <button type="button" @click="date = '<?php echo e(date('Y-m-d', strtotime('-1 day'))); ?>'" class="px-2 py-1 border rounded hover:bg-gray-100">Yesterday</button>
                            </div>
                        </div>

                        <input type="hidden" name="division_id" value="<?php echo e($selectedDivision->id); ?>">


                        <?php if($isCreditGMC): ?>
                            <div class="flex flex-col md:flex-row gap-6">
                                <!-- Left Column -->
                                <div class="w-full md:w-1/2 space-y-4">
                                    <?php $__currentLoopData = $fieldsLeft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $val = old('data.' . $field->field_label, $field->field_type === 'number' ? '0' : '') ?>
                                        <div x-data="{ val: '<?php echo e($val); ?>' }" x-show="true" x-transition.opacity x-transition.duration.500ms>
                                            <label class="block font-semibold">
                                                <?php echo e($field->field_label); ?>

                                                <?php if($field->is_required): ?>
                                                    <span class="text-red-500">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <input
                                                type="<?php echo e($field->field_type); ?>"
                                                name="data[<?php echo e($field->field_label); ?>]"
                                                x-model="val"
                                                class="w-full border p-2 rounded transition-all duration-300"
                                                :class="{
                                                    'border-red-500': '<?php echo e($field->is_required); ?>' && !val,
                                                    'border-green-500': val && '<?php echo e($field->is_required); ?>',
                                                    'border-gray-300': !val && '<?php echo e(!$field->is_required); ?>'
                                                }"
                                                <?php echo e($field->is_required ? 'required' : ''); ?>

                                                :value="val"
                                                <?php if($field->field_type === 'number'): ?> step="any" <?php endif; ?>
                                            >
                                            <?php $__errorArgs = ['data.' . $field->field_label];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <!-- Right Column -->
                                <div class="w-full md:w-1/2 space-y-4">
                                    <?php $__currentLoopData = $fieldsRight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $val = old('data.' . $field->field_label, $field->field_type === 'number' ? '0' : '') ?>
                                        <div x-data="{ val: '<?php echo e($val); ?>' }" x-show="true" x-transition.opacity x-transition.duration.500ms" class="bg-green-50 border border-green-300 p-2 rounded">
                                            <label class="block font-semibold text-green-800">
                                                <?php echo e($field->field_label); ?>

                                                <?php if($field->is_required): ?>
                                                    <span class="text-red-500">*</span>
                                                <?php endif; ?>
                                            </label>
                                            <input
                                                type="<?php echo e($field->field_type); ?>"
                                                name="data[<?php echo e($field->field_label); ?>]"
                                                x-model="val"
                                                class="w-full border p-2 rounded transition-all duration-300 bg-white"
                                                :class="{
                                                    'border-red-500': '<?php echo e($field->is_required); ?>' && !val,
                                                    'border-green-500': val && '<?php echo e($field->is_required); ?>',
                                                    'border-gray-300': !val && '<?php echo e(!$field->is_required); ?>'
                                                }"
                                                <?php echo e($field->is_required ? 'required' : ''); ?>

                                                :value="val"
                                                <?php if($field->field_type === 'number'): ?> step="any" <?php endif; ?>
                                            >
                                            <?php $__errorArgs = ['data.' . $field->field_label];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $templateFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $val = old('data.' . $field->field_label, $field->field_type === 'number' ? '0' : '') ?>
                                    <div x-data="{ val: '<?php echo e($val); ?>' }" x-show="true" x-transition.opacity x-transition.duration.500ms>
                                        <label class="block font-semibold">
                                            <?php echo e($field->field_label); ?>

                                            <?php if($field->is_required): ?>
                                                <span class="text-red-500">*</span>
                                            <?php endif; ?>
                                        </label>

                                        <input
                                            type="<?php echo e($field->field_type); ?>"
                                            name="data[<?php echo e($field->field_label); ?>]"
                                            x-model="val"
                                            class="w-full border p-2 rounded transition-all duration-300"
                                            :class="{
                                                'border-red-500': '<?php echo e($field->is_required); ?>' && !val,
                                                'border-green-500': val && '<?php echo e($field->is_required); ?>',
                                                'border-gray-300': !val && '<?php echo e(!$field->is_required); ?>'
                                            }"
                                            <?php echo e($field->is_required ? 'required' : ''); ?>

                                            :value="val"
                                            <?php if($field->field_type === 'number'): ?> step="any" <?php endif; ?>
                                        >

                                        <?php $__errorArgs = ['data.' . $field->field_label];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-red-500 text-sm"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-6 text-right">
                            <button type="submit"
                                    class="text-white px-6 py-2 rounded"
                                    style="background-color: #1877F2;"
                                    @mouseenter="$el.style.backgroundColor = '#1558B0'"
                                    @mouseleave="$el.style.backgroundColor = '#1877F2'">
                                💾 Submit Sales Input
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php elseif(request('division_id')): ?>
            <div class="text-center text-gray-600 mt-6">
                ⚠️ No fields defined for this business line yet.
            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\sales-inputs\create.blade.php ENDPATH**/ ?>