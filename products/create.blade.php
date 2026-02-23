<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-blue-700">
            Add Product
        </h1>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">

        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            {{-- CATEGORY --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Category</label>

                @php
                    $categories = $categories ?? [
                        'core'   => 'CORE',
                        'petcsd' => 'PET CSD',
                        'stills' => 'STILLS',
                    ];
                @endphp

                <select
                    name="category"
                    class="w-full border border-gray-300 p-2 rounded"
                    required
                >
                    <option value="">Select category</option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ old('category') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PACK SIZE --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Pack Size</label>
                <input
                    id="pack_size"
                    name="pack_size"
                    type="text"
                    value="{{ old('pack_size') }}"
                    class="w-full border border-gray-300 p-2 rounded"
                    placeholder="e.g. 237ml, 1 LITER, 1.5 LTR x 12"
                    
                >
                @error('pack_size')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- UCS COMPUTATION (ML * BOTTLES / 5678) --}}
            <div class="mb-4 border border-gray-200 rounded p-3 bg-gray-50">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-gray-800">UCS Computation</p>
                    <p class="text-[11px] text-gray-500">Formula: (ml × bottles) ÷ 5678</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs border border-gray-300 bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1 text-left">Bottle Size (ml)</th>
                                <th class="border px-2 py-1 text-center w-10">×</th>
                                <th class="border px-2 py-1 text-left">No. of Bottles</th>
                                <th class="border px-2 py-1 text-center w-10">÷</th>
                                <th class="border px-2 py-1 text-center w-20">5678</th>
                                <th class="border px-2 py-1 text-center w-10">=</th>
                                <th class="border px-2 py-1 text-left">UCS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border px-2 py-1">
                                    <input
                                        id="unit_ml"
                                        name="unit_ml"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        value="{{ old('unit_ml') }}"
                                        class="w-full border border-gray-300 p-2 rounded text-right"
                                        placeholder="e.g. 237"
                                    >
                                </td>
                                <td class="border px-2 py-1 text-center font-semibold">×</td>
                                <td class="border px-2 py-1">
                                    <input
                                        id="bottles_per_case"
                                        name="bottles_per_case"
                                        type="number"
                                        min="0"
                                        step="1"
                                        value="{{ old('bottles_per_case') }}"
                                        class="w-full border border-gray-300 p-2 rounded text-right"
                                        placeholder="e.g. 24"
                                    >
                                </td>
                                <td class="border px-2 py-1 text-center font-semibold">÷</td>
                                <td class="border px-2 py-1 text-center font-semibold">5678</td>
                                <td class="border px-2 py-1 text-center font-semibold">=</td>
                                <td class="border px-2 py-1">
                                    <input
                                        id="computed_ucs"
                                        type="text"
                                        class="w-full bg-gray-100 border border-gray-300 p-2 rounded text-right font-semibold"
                                        readonly
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @error('unit_ml')
                    <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                @enderror
                @error('bottles_per_case')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror

                <p class="text-[11px] text-gray-500 mt-2">
                    The computed UCS will auto-fill in Coca-Cola Sales Reporting.
                </p>
            </div>

            {{-- PRODUCT NAME --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Product Name</label>
                <input
                    name="product_name"
                    type="text"
                    value="{{ old('product_name') }}"
                    class="w-full border border-gray-300 p-2 rounded"
                    placeholder="e.g. COKE, SPRITE, ROYAL Orange"
                    required
                >
                @error('product_name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- SRP --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">SRP</label>
                <input
                    name="srp"
                    type="number"
                    step="0.01"
                    value="{{ old('srp', 0) }}"
                    class="w-full border border-gray-300 p-2 rounded"
                    placeholder="0.00"
                >
                @error('srp')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- STATUS --}}
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1">Status</label>

                @php
                    $statuses = $statuses ?? ['active' => 'Active', 'inactive' => 'Inactive'];
                @endphp

                <select name="status" class="w-full border border-gray-300 p-2 rounded">
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ old('status', 'active') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Product
                </button>
            </div>

        </form>

    </div>

    <script>
        (function () {
            const BASE = 5678;
            const packEl = document.getElementById('pack_size');
            const mlEl = document.getElementById('unit_ml');
            const bottlesEl = document.getElementById('bottles_per_case');
            const outEl = document.getElementById('computed_ucs');
            let lastAutoMl = null;

            function num(v) {
                const n = parseFloat(String(v ?? '').replace(/[^0-9.\-]/g, ''));
                return Number.isFinite(n) ? n : 0;
            }

            function parseBottleMl(packSize) {
                const s = String(packSize ?? '').trim().toLowerCase().replace(/,/g, '');
                if (!s) return null;

                // Prefer explicit "ml"
                let m = s.match(/(\d+(?:\.\d+)?)\s*ml\b/);
                if (m) {
                    const v = parseFloat(m[1]);
                    return Number.isFinite(v) && v > 0 ? v : null;
                }

                // Liters: "1 LITER", "1.5 LTR", "2L", "1.75L"
                m = s.match(/(\d+(?:\.\d+)?)\s*(?:liters?|litres?|ltr|l)\b/);
                if (m) {
                    const v = parseFloat(m[1]);
                    const ml = Number.isFinite(v) && v > 0 ? (v * 1000) : null;
                    return ml;
                }

                return null;
            }

            function recalc() {
                const ml = num(mlEl?.value);
                const bottles = num(bottlesEl?.value);

                if (!outEl) return;
                if (ml > 0 && bottles > 0) {
                    outEl.value = ((ml * bottles) / BASE).toFixed(6);
                } else {
                    outEl.value = '';
                }
            }

            function maybeAutofillMlFromPack() {
                if (!packEl || !mlEl) return;

                const parsed = parseBottleMl(packEl.value);
                if (!parsed) return;

                const current = num(mlEl.value);
                const shouldOverwrite =
                    current <= 0 ||
                    (lastAutoMl !== null && Math.abs(current - lastAutoMl) < 0.000001);

                if (shouldOverwrite) {
                    mlEl.value = Number.isInteger(parsed) ? String(parsed) : String(parsed.toFixed(2));
                    lastAutoMl = parsed;
                    recalc();
                }
            }

            mlEl?.addEventListener('input', recalc);
            bottlesEl?.addEventListener('input', recalc);
            packEl?.addEventListener('input', maybeAutofillMlFromPack);
            document.addEventListener('DOMContentLoaded', () => {
                maybeAutofillMlFromPack();
                recalc();
            });
        })();
    </script>
</x-app-layout>
