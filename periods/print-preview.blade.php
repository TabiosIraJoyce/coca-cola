<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i data-lucide="printer" class="w-6 h-6 text-gray-900"></i>
            Period Reports - Print Preview
        </h2>
    </x-slot>

    <div class="p-6 space-y-4">

        <div class="bg-white shadow rounded-lg p-5">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Branch</label>
                    <select name="branch" class="w-full border rounded p-2">
                        <option value="">All</option>
                        @foreach($branches as $b)
                            <option value="{{ $b }}" {{ request('branch') === $b ? 'selected' : '' }}>
                                {{ $b }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Period From</label>
                    <select name="period_from" class="w-full border rounded p-2">
                        <option value="">All</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (string) request('period_from') === (string) $i ? 'selected' : '' }}>
                                Period {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Period To</label>
                    <select name="period_to" class="w-full border rounded p-2">
                        <option value="">All</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ (string) request('period_to') === (string) $i ? 'selected' : '' }}>
                                Period {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border rounded p-2">
                </div>

                <div class="md:col-span-6 flex gap-2 justify-end">
                    <a href="{{ route('admin.reports.periods.index') }}"
                       class="px-4 py-2 rounded border hover:bg-gray-50">
                        Back
                    </a>
                    <button type="submit"
                            class="px-5 py-2 rounded bg-gray-900 text-white hover:bg-black font-semibold">
                        Generate Preview
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-3 border-b flex items-center justify-between gap-2">
                <div class="text-sm font-semibold text-gray-700">
                    Preview
                </div>

                <button type="button"
                        id="printBtn"
                        class="px-4 py-2 rounded bg-blue-700 text-white hover:bg-blue-800 font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ empty($previewUrl) ? 'disabled' : '' }}>
                    Print
                </button>
            </div>

            @if (empty($previewUrl))
                <div class="p-6 text-sm text-gray-600">
                    Select Branch/Period/Date, then click <b>Generate Preview</b>.
                </div>
            @else
                <iframe id="previewFrame"
                        src="{{ $previewUrl }}"
                        class="w-full"
                        style="height: 78vh; border: 0;"></iframe>
            @endif
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            const btn = document.getElementById('printBtn');
            const frame = document.getElementById('previewFrame');
            if (!btn || !frame) return;

            btn.addEventListener('click', () => {
                try {
                    frame.contentWindow.focus();
                    frame.contentWindow.print();
                } catch (e) {
                    // fallback
                    window.open(frame.src, '_blank');
                }
            });
        });
    </script>
</x-app-layout>

