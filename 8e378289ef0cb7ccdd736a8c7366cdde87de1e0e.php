<!-- Sidebar Component -->
<?php
    $isConsolidatedOpen = request()->is('admin/consolidated*')
        || request()->is('admin/period-targets*')
        || request()->is('admin/reports/consolidated*');
    $isCustomersOpen = request()->is('admin/customers*');
?>
<aside class="w-64 min-w-[16rem] shrink-0 bg-white text-gray-800 border-r border-gray-300 flex flex-col justify-between">
    <div>
        <!-- 🔷 App Name -->
        <div class="p-4 text-lg font-bold text-facebookBlue border-b border-gray-300">
            Gledco Sales Report System
        </div>

        <!-- 👤 User Name -->
        <div class="p-4 text-sm text-gray-600 border-b border-gray-300">
            👋 Hello, <span class="font-semibold text-facebookBlue">
                <?php echo e(Auth::user()->name ?? 'Guest'); ?>

            </span>
        </div>

        <!-- 📂 Menu -->
        <nav class="mt-2">
            <ul>

                <!-- Dashboard -->
                <li>
                    <a href="<?php echo e(route('admin.dashboard')); ?>"
                       class="block px-4 py-2 cursor-pointer hover:bg-facebookGray <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-facebookGray font-bold' : ''); ?>">
                        🏠 Dashboard
                    </a>
                </li>

                <!-- ⭐ Consolidated Dashboard -->
                    <li>
                        <!-- MAIN CLICKABLE -->
                        <div
                            class="px-4 py-2 cursor-pointer hover:bg-facebookGray font-medium flex items-center justify-between"
                            onclick="toggleConsolidatedMenu()">

                            <span class="inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-sky-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M3 3.75A1.75 1.75 0 0 1 4.75 2h10.5A1.75 1.75 0 0 1 17 3.75v12.5A1.75 1.75 0 0 1 15.25 18H4.75A1.75 1.75 0 0 1 3 16.25V3.75Zm3 2a.75.75 0 0 0-.75.75v7a.75.75 0 0 0 1.5 0v-7A.75.75 0 0 0 6 5.75Zm4 2a.75.75 0 0 0-.75.75v5a.75.75 0 0 0 1.5 0v-5A.75.75 0 0 0 10 7.75Zm4-3a.75.75 0 0 0-.75.75v8a.75.75 0 0 0 1.5 0v-8A.75.75 0 0 0 14 4.75Z" />
                                </svg>
                                <span>Consolidated Dashboard</span>
                            </span>

                            <span id="consolidatedArrow"
                                class="inline-flex items-center justify-center text-facebookBlue transition-transform duration-200 <?php echo e($isConsolidatedOpen ? 'rotate-90' : ''); ?>">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.22 4.97a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06L11.19 10 7.22 6.03a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>

                        <!-- SUB MENU -->
                        <ul id="consolidatedMenu"
                            class="ml-6
                            <?php echo e($isConsolidatedOpen ? '' : 'hidden'); ?>">

                            <li>
                                <a href="<?php echo e(route('admin.consolidated.index')); ?>"
                                class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                                <?php echo e(request()->routeIs('admin.consolidated.index') ? 'bg-facebookGray font-bold' : ''); ?>">
                                    📊 Overview
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('admin.period-targets.create')); ?>"
                                class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                                <?php echo e(request()->routeIs('admin.period-targets.create') ? 'bg-facebookGray font-bold' : ''); ?>">
                                    🎯 Set Targets
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('admin.period-targets.index')); ?>"
                                class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                                <?php echo e(request()->routeIs('admin.period-targets.index') ? 'bg-facebookGray font-bold' : ''); ?>">
                                    📋 Target List
                                </a>
                            </li>
                        </ul>
                    </li>
                    <script>
                        function toggleConsolidatedMenu() {
                            const menu = document.getElementById('consolidatedMenu');
                            const arrow = document.getElementById('consolidatedArrow');

                            if (!menu || !arrow) return;

                            menu.classList.toggle('hidden');
                            arrow.classList.toggle('rotate-90');
                        }
                    </script>

               <!-- Customers -->
                <li>
                    <!-- MAIN CLICKABLE -->
                    <div
                        class="px-4 py-2 cursor-pointer hover:bg-facebookGray font-medium flex items-center justify-between"
                        onclick="toggleCustomersMenu()">

                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-700" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10 10a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm-6 7a6 6 0 1 1 12 0H4Zm12.5-8.25a2.75 2.75 0 1 0-2.2-4.4 5.03 5.03 0 0 1 1.22 4.57c.31-.1.64-.17.98-.17Zm-1.83 3.43A6.96 6.96 0 0 1 16.75 17H20a4.5 4.5 0 0 0-5.33-4.82Z" />
                            </svg>
                            <span>Customers</span>
                        </span>

                        <span id="customersArrow"
                            class="inline-flex items-center justify-center text-facebookBlue transition-transform duration-200 <?php echo e($isCustomersOpen ? 'rotate-90' : ''); ?>">
                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.22 4.97a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1 0 1.06l-4.5 4.5a.75.75 0 1 1-1.06-1.06L11.19 10 7.22 6.03a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>

                    <!-- SUB MENU -->
                    <ul id="customersMenu"
                        class="ml-6
                        <?php echo e($isCustomersOpen ? '' : 'hidden'); ?>">

                        <li>
                            <a href="<?php echo e(route('admin.customers.index')); ?>"
                            class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                            <?php echo e(request()->routeIs('admin.customers.index') ? 'bg-facebookGray font-bold' : ''); ?>">
                                📋 Customer List
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.customers.create')); ?>"
                            class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                            <?php echo e(request()->routeIs('admin.customers.create') ? 'bg-facebookGray font-bold' : ''); ?>">
                                ➕ Add Customer
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.customers.import.form')); ?>"
                            class="block px-4 py-2 text-sm hover:bg-facebookGray cursor-pointer
                            <?php echo e(request()->routeIs('admin.customers.import.*') ? 'bg-facebookGray font-bold' : ''); ?>">
                                ⬆️ Upload Excel/CSV
                            </a>
                        </li>
                    </ul>
                </li>
                <script>
                    function toggleCustomersMenu() {
                        const menu = document.getElementById('customersMenu');
                        const arrow = document.getElementById('customersArrow');

                        if (!menu || !arrow) return;

                        menu.classList.toggle('hidden');
                        arrow.classList.toggle('rotate-90');
                    }
                    </script>

                <li>
                    <a href="<?php echo e(route('admin.banks.index')); ?>"
                       class="block px-4 py-2 hover:bg-facebookGray
                              <?php echo e(request()->routeIs('admin.banks.*') ? 'bg-facebookGray font-bold' : ''); ?>">
                        🏦 Bank Management
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('admin.products.index')); ?>"
                    class="block px-4 py-2 hover:bg-facebookGray cursor-pointer
                            <?php echo e(request()->routeIs('admin.products.*') ? 'bg-facebookGray font-bold' : ''); ?>">
                        📦 Products & Pricing
                    </a>
                </li>


                <!-- Sales Inputs -->
                <li>
                    <a href="<?php echo e(route('admin.sales-inputs.index')); ?>"
                       class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                        📈 Sales Inputs
                    </a>
                </li>

                <!-- Reports -->
                <li>
                    <a href="<?php echo e(route('admin.reports.index')); ?>"
                       class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                        💸 Reports
                    </a>
                </li>
        


                <!-- Admin-Only Items -->
                        <?php if(auth()->user()->role === 'admin'): ?>

                        <li>
                            <a href="<?php echo e(route('admin.sales-templates.index')); ?>"
                            class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                                📄 Sales Template
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.business-lines.index')); ?>"
                            class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                                📊 Business Lines Management
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.divisions.index')); ?>"
                            class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                                🏢 Division Management
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('admin.users.index')); ?>"
                            class="block px-4 py-2 hover:bg-facebookGray cursor-pointer">
                                👤 User Management
                            </a>
                        </li>
                        <?php endif; ?>


                <!-- 🚪 Logout Button -->
                <div class="p-4 border-t border-gray-300">
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100 rounded">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </ul>
        </nav>
    </div>
</aside>
<?php /**PATH C:\xampp\htdocs\sales-report\resources\views\components\sidebar.blade.php ENDPATH**/ ?>