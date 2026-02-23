<x-app-layout>
    <div class="p-6 bg-gray-100">

        <h1 class="text-xl font-bold mb-4">Customers</h1>

        {{-- SUB ROUTE TABS --}}
        @php
            $defaultTabs = ['CRS 1', 'CRS 2', 'CRS 3', 'WATER', 'PRE SELLER'];
            $discoveredSubRoutes = collect($customers ?? [])
                ->flatMap(fn ($subRoutes) => collect($subRoutes)->keys())
                ->map(fn ($subRoute) => strtoupper(trim((string) $subRoute)))
                ->filter()
                ->unique()
                ->values();

            $orderedKnownTabs = collect($defaultTabs)
                ->filter(fn ($tab) => $discoveredSubRoutes->contains($tab))
                ->values();

            $otherTabs = $discoveredSubRoutes
                ->reject(fn ($subRoute) => $orderedKnownTabs->contains($subRoute))
                ->sort()
                ->values();

            $subRouteTabs = $orderedKnownTabs->concat($otherTabs);
            if ($subRouteTabs->isEmpty()) {
                $subRouteTabs = collect($defaultTabs);
            }
        @endphp

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-6">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="text-sm font-semibold text-gray-700">
                    Sub Route Filter
                </div>

                <div id="activeSubrouteTitle"
                     class="text-sm font-bold tracking-wide text-gray-900">
                    SUB ROUTE: <span class="text-blue-700">—</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-3">
            @foreach ($subRouteTabs as $tab)
                <button
                    type="button"
                    class="subroute-tab subroute-tab-base px-4 py-2 rounded-lg border bg-white text-xs font-bold tracking-wider uppercase transition-all duration-200"
                    data-target="{{ $tab }}">
                    {{ strtoupper($tab) }}
                </button>
            @endforeach
            </div>
        </div>

        {{-- CUSTOMERS --}}
        @if ($customers->isEmpty())
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 text-sm text-gray-700">
                No customers found. Add customers manually or upload an Excel/CSV file.
            </div>
        @endif

        @foreach ($customers as $route => $subRoutes)

            <div class="mb-10">
                <h2 class="text-lg font-bold mb-4">
                    Delivery Route : {{ ucwords(strtolower($route)) }}
                </h2>

                @foreach ($subRoutes as $subRoute => $items)

                    {{-- SUB ROUTE SECTION --}}
                    <div class="subroute-section"
                         data-subroute="{{ $subRoute }}"
                         style="display: none;">

                        <div class="bg-white shadow rounded mb-6 p-4">

                            <div class="font-semibold mb-3 text-gray-700">
                                Sub Route : {{ $subRoute }}
                            </div>

                            <div class="overflow-x-auto">
                                <form method="POST"
                                      action="{{ route('admin.customers.bulk-destroy') }}"
                                      class="bulk-delete-form"
                                      onsubmit="return confirm('Delete selected customers?')">
                                    @csrf
                                    @method('DELETE')

                                    <div class="flex items-center justify-between gap-3 mb-3">
                                        <div class="text-xs text-gray-600">
                                            Select customers below to delete in bulk.
                                        </div>
                                        <button type="submit"
                                                class="bulk-delete-btn px-3 py-1.5 rounded bg-red-600 text-white text-xs font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                                                disabled>
                                            Delete Selected (<span class="bulk-delete-count">0</span>)
                                        </button>
                                    </div>

                                    <table class="w-full border border-gray-300 text-sm">
                                    <thead class="bg-gray-200">
                                        <tr>
                                            <th class="border px-2 py-1 text-center w-10">
                                                <input type="checkbox" class="bulk-select-all">
                                            </th>
                                            <th class="border px-2 py-1">CUSTOMER</th>
                                            <th class="border px-2 py-1">STORE NAME</th>
                                            <th class="border px-2 py-1">ADDRESS</th>
                                            <th class="border px-2 py-1">CONTACT NUMBER</th>
                                            <th class="border px-2 py-1 text-right">CREDIT LIMIT</th>
                                            <th class="border px-2 py-1">REMARKS</th>
                                            <th class="border px-2 py-1 text-center">ACTIONS</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($items as $customer)
                                            <tr class="hover:bg-gray-50">
                                                <td class="border px-2 py-1 text-center">
                                                    <input type="checkbox"
                                                           class="bulk-row"
                                                           name="ids[]"
                                                           value="{{ $customer->id }}">
                                                </td>
                                                <td class="border px-2 py-1">{{  $customer->customer  }}</td>
                                                <td class="border px-2 py-1">{{ $customer->store_name }}</td>
                                                <td class="border px-2 py-1">{{ $customer->address }}</td>
                                                <td class="border px-2 py-1">{{ $customer->contact_number }}</td>
                                                <td class="border px-2 py-1 text-right">
                                                    {{ number_format($customer->credit_limit, 2) }}
                                                </td>
                                                <td class="border px-2 py-1 font-semibold
                                                    {{ $customer->remarks === 'CLOSED'
                                                        ? 'text-red-600'
                                                        : 'text-green-600' }}">
                                                    {{ $customer->remarks }}
                                                </td>
                                                <td class="border px-2 py-1 text-center">
                                                    <div class="flex gap-2 justify-center">
                                                        <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                                           class="px-2 py-1 text-xs bg-blue-600 text-white rounded">
                                                            Edit
                                                        </a>

                                                        <form action="{{ route('admin.customers.destroy', $customer->id) }}"
                                                              method="POST"
                                                              onsubmit="return confirm('Delete this customer?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </form>
                            </div>

                        </div>
                    </div>

                @endforeach
            </div>

        @endforeach
    </div>

    <style>
        /* Small "press" animation for tabs */
        @keyframes tabPop {
            0% { transform: scale(1); }
            40% { transform: scale(0.96); }
            100% { transform: scale(1); }
        }

        .subroute-tab-base:hover {
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            transform: translateY(-1px);
        }

        .subroute-tab-active {
            background: #2563eb; /* blue-600 */
            border-color: #1d4ed8; /* blue-700 */
            color: #fff;
            box-shadow: 0 10px 25px rgba(37,99,235,0.25);
        }

        .subroute-tab-click {
            animation: tabPop 220ms ease-out;
        }
    </style>

    {{-- TAB SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.subroute-tab');
            const sections = document.querySelectorAll('.subroute-section');
            const activeTitle = document.querySelector('#activeSubrouteTitle span');

            // Bulk delete behavior (per table)
            function wireBulkDelete(form) {
                const selectAll = form.querySelector('.bulk-select-all');
                const rows = Array.from(form.querySelectorAll('.bulk-row'));
                const btn = form.querySelector('.bulk-delete-btn');
                const countEl = form.querySelector('.bulk-delete-count');

                if (!rows.length) return;

                const refresh = () => {
                    const checked = rows.filter(r => r.checked).length;
                    if (countEl) countEl.textContent = String(checked);
                    if (btn) btn.disabled = checked === 0;

                    if (selectAll) {
                        selectAll.checked = checked > 0 && checked === rows.length;
                        selectAll.indeterminate = checked > 0 && checked < rows.length;
                    }
                };

                if (selectAll) {
                    selectAll.addEventListener('change', () => {
                        rows.forEach(r => r.checked = selectAll.checked);
                        refresh();
                    });
                }

                rows.forEach(r => r.addEventListener('change', refresh));
                refresh();
            }

            document.querySelectorAll('form.bulk-delete-form').forEach(wireBulkDelete);

            // Normalize so "crs1", "CRS 1", "  CRS   1 " all match.
            function norm(val) {
                return String(val || '')
                    .toUpperCase()
                    .replace(/\s+/g, ' ')
                    .replace(/[^A-Z0-9 ]/g, '')
                    .trim();
            }

            function showSubRoute(name) {
                const target = norm(name);

                sections.forEach(section => {
                    section.style.display = norm(section.dataset.subroute) === target
                        ? 'block'
                        : 'none';
                });

                tabs.forEach(tab => {
                    const isActive = norm(tab.dataset.target) === target;
                    tab.classList.toggle('subroute-tab-active', isActive);
                });

                if (activeTitle) {
                    activeTitle.textContent = target || '—';
                }
            }

            // Default: show CRS 1 if present, else show first tab.
            const defaultTab = Array.from(tabs).find(t => norm(t.dataset.target) === 'CRS 1') || tabs[0];
            if (defaultTab) showSubRoute(defaultTab.dataset.target);

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // click animation
                    tab.classList.remove('subroute-tab-click');
                    // force reflow so animation re-triggers even if you click same tab again
                    void tab.offsetWidth;
                    tab.classList.add('subroute-tab-click');
                    setTimeout(() => tab.classList.remove('subroute-tab-click'), 260);

                    showSubRoute(tab.dataset.target);
                });
            });
        });
    </script>
</x-app-layout>
