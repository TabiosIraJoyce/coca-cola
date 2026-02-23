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
        <h2 class="text-2xl font-bold text-facebookBlue">User Management</h2>
     <?php $__env->endSlot(); ?>

    <div class="bg-white p-6 rounded shadow" x-data="{ search: '' }">
        
        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-300 text-green-800 p-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php elseif(session('error')): ?>
            <div class="bg-red-100 border border-red-300 text-red-800 p-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <div class="flex flex-wrap justify-between items-center gap-2 mb-6">
            <a href="<?php echo e(route('admin.users.create')); ?>"
               class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Add New User
            </a>

            <div class="w-full sm:w-auto sm:ml-auto">
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search name, email, role, or division..."
                    class="border border-gray-300 rounded px-4 py-2 w-full sm:w-80 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                />
            </div>
        </div>

        
        <div class="overflow-x-auto">
            <table class="min-w-full border text-sm shadow-sm rounded overflow-hidden">
                <thead class="bg-gray-100 text-gray-700 text-left">
                    <tr>
                        <th class="px-4 py-3 border-b">#</th>
                        <th class="px-4 py-3 border-b">Name</th>
                        <th class="px-4 py-3 border-b">Email</th>
                        <th class="px-4 py-3 border-b">Role</th>
                        <th class="px-4 py-3 border-b">Division</th>
                        <th class="px-4 py-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr
                            x-show="search === '' || 
                                    '<?php echo e(strtolower($user->name)); ?>'.includes(search.toLowerCase()) || 
                                    '<?php echo e(strtolower($user->email)); ?>'.includes(search.toLowerCase()) || 
                                    '<?php echo e(strtolower($user->role)); ?>'.includes(search.toLowerCase()) || 
                                    '<?php echo e(strtolower($user->division?->division_name ?? '')); ?>'.includes(search.toLowerCase())"
                            class="hover:bg-gray-50 transition"
                        >
                            <td class="px-4 py-2"><?php echo e($user->id); ?></td>
                            <td class="px-4 py-2 font-medium text-gray-900"><?php echo e($user->name); ?></td>
                            <td class="px-4 py-2 text-gray-700"><?php echo e($user->email); ?></td>
                            <td class="px-4 py-2">
                                <?php if($user->role === 'admin'): ?>
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                        Admin
                                    </span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded">
                                        User
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 text-gray-700"><?php echo e($user->division?->division_name ?? '-'); ?></td>
                            <td class="px-4 py-2 text-center space-x-2">
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>"
                                   class="text-blue-600 hover:text-blue-800 font-medium transition"
                                   title="Edit user">
                                    Edit
                                </a>
                                <button type="button"
                                        onclick="openModal(<?php echo e($user->id); ?>)"
                                        class="text-red-600 hover:text-red-800 font-medium transition"
                                        title="Delete user">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500 italic">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div id="deleteModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full animate-fade-in">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Confirm Deletion</h2>
            <p class="mb-4 text-sm text-gray-600">Are you sure you want to delete this user?</p>
            <form id="deleteForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <script>
        function openModal(userId) {
            const form = document.getElementById('deleteForm');
            form.action = `/users/${userId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da)): ?>
<?php $component = $__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da; ?>
<?php unset($__componentOriginal8e2ce59650f81721f93fef32250174d77c3531da); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\admin\users\index.blade.php ENDPATH**/ ?>