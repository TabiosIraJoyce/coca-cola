<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-blue-700">
            Products & Pricing
        </h1>
    </x-slot>

    <div class="bg-white p-6 rounded shadow">

        <!-- ➕ ADD PRODUCT BUTTON -->
        <div class="flex justify-between mb-4">
            <button
                type="button"
                onclick="deleteSelected()"
                class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                Delete Selected
            </button>

            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded
                      hover:bg-blue-700 transition">
                <span class="mr-2">➕</span> Add Product
            </a>
        </div>

        <!-- 📋 PRODUCTS TABLE -->
        <table class="min-w-full border border-gray-300 text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-2 py-2 border border-gray-300 text-center">
                        <input type="checkbox" id="selectAll">
                    </th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Category</th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Pack Size</th>
                    <th class="px-2 py-2 border border-gray-300 text-left">Product</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">Bottles</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">UCS</th>
                    <th class="px-2 py-2 border border-gray-300 text-right">SRP</th>
                    <th class="px-2 py-2 border border-gray-300 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $p)

                    {{-- ✅ ADDED ONLY: normalize category + color mapping --}}
                    @php
                        $normalizedCategory = strtolower(str_replace(' ', '', $p->category));

                        $rowClass = '';
                        $productTextClass = '';

                        if ($normalizedCategory === 'core') {
                            $rowClass = 'bg-red-50';
                            $productTextClass = 'text-red-700 font-semibold';
                        } elseif ($normalizedCategory === 'stills') {
                            $rowClass = 'bg-blue-50';
                            $productTextClass = 'text-blue-700 font-semibold';
                        } elseif ($normalizedCategory === 'petcsd') {
                            $rowClass = 'bg-yellow-100';
                            $productTextClass = 'text-yellow-700 font-semibold';
                        }
                    @endphp

                    <tr class="hover:bg-gray-50 {{ $rowClass }}">
                        <td class="px-2 py-2 border border-gray-300 text-center">
                            <input type="checkbox"
                                   class="row-checkbox"
                                   value="{{ $p->id }}">
                        </td>

                        <td class="px-3 py-2 border border-gray-300 font-semibold">
                            {{ strtoupper($p->category) }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300">
                            {{ $p->pack_size }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300 {{ $productTextClass }}">
                            {{ $p->product_name }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right">
                            {{ $p->bottles_per_case !== null ? $p->bottles_per_case : '—' }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right font-semibold">
                            {{ $p->ucs !== null ? number_format($p->ucs, 6) : '—' }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-right">
                            ₱ {{ number_format($p->srp ?? 0, 2) }}
                        </td>

                        <td class="px-3 py-2 border border-gray-300 text-center">
                            <a href="{{ route('admin.products.edit', $p->id) }}"
                               class="text-blue-600 hover:underline mr-2">
                                Edit
                            </a>

                            <form action="{{ route('admin.products.destroy', $p->id) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- IWS TABLE -->
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-blue-700 mb-3">IWS UCS Table</h2>
            <table class="min-w-full border border-gray-300 text-sm border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 border border-gray-300 text-left">Category</th>
                        <th class="px-2 py-2 border border-gray-300 text-left">Pack Size</th>
                        <th class="px-2 py-2 border border-gray-300 text-left">Product</th>
                        <th class="px-2 py-2 border border-gray-300 text-right">Bottles</th>
                        <th class="px-2 py-2 border border-gray-300 text-right">IWS UCS</th>
                        <th class="px-2 py-2 border border-gray-300 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $p)
                        @php
                            $normalizedCategory = strtolower(str_replace(' ', '', $p->category));

                            $rowClass = '';
                            $productTextClass = '';

                            if ($normalizedCategory === 'core') {
                                $rowClass = 'bg-red-50';
                                $productTextClass = 'text-red-700 font-semibold';
                            } elseif ($normalizedCategory === 'stills') {
                                $rowClass = 'bg-blue-50';
                                $productTextClass = 'text-blue-700 font-semibold';
                            } elseif ($normalizedCategory === 'petcsd') {
                                $rowClass = 'bg-yellow-100';
                                $productTextClass = 'text-yellow-700 font-semibold';
                            }
                        @endphp

                        <tr class="hover:bg-gray-50 {{ $rowClass }}">
                            <td class="px-3 py-2 border border-gray-300 font-semibold">
                                {{ strtoupper($p->category) }}
                            </td>
                            <td class="px-3 py-2 border border-gray-300">
                                {{ $p->pack_size }}
                            </td>
                            <td class="px-3 py-2 border border-gray-300 {{ $productTextClass }}">
                                {{ $p->product_name }}
                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-right">
                                {{ $p->bottles_per_case !== null ? $p->bottles_per_case : '-' }}
                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-right font-semibold">
                                {{ '-' }}
                            </td>
                            <td class="px-3 py-2 border border-gray-300 text-center">
                                <a href="{{ route('admin.products.edit', $p->id) }}"
                                   class="text-blue-600 hover:underline mr-2">
                                    Edit
                                </a>

                                <form action="{{ route('admin.products.destroy', $p->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 🔥 HIDDEN DELETE FORMS -->
        @foreach($products as $p)
            <form id="delete-form-{{ $p->id }}"
                  action="{{ route('admin.products.destroy', $p->id) }}"
                  method="POST"
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach

    </div>

    <!-- ✅ SCRIPT -->
    <script>
    document.getElementById('selectAll')?.addEventListener('change', function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    function deleteSelected() {
        const selected = document.querySelectorAll('.row-checkbox:checked');

        if (selected.length === 0) {
            alert('Please select at least one product.');
            return;
        }

        if (!confirm('Delete selected product(s)?')) return;

        // ✅ delete ONLY the first selected item
        const firstId = selected[0].value;
        document.getElementById('delete-form-' + firstId).submit();
    }
    </script>

</x-app-layout>
