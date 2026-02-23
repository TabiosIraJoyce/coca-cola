<x-app-layout>
    <div class="min-h-screen bg-gray-100 py-10">
        <div class="max-w-6xl mx-auto bg-white rounded-lg shadow border border-gray-200 p-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">📋 Sales Targets</h2>

                <a href="{{ route('admin.period-targets.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    ➕ New Target
                </a>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($targets->isEmpty())
                <div class="text-center text-gray-500 py-10">
                    No targets found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-3 py-2 text-left">Branch</th>
                                <th class="border px-3 py-2 text-left">Period</th>

                                <th class="border px-3 py-2 text-right">
                                    Core Target Sales
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Stills Target Sales
                                </th>

                                <th class="border px-3 py-2 text-right">
                                    Target Sales
                                </th>

                                <th class="border px-3 py-2 text-left">Effective From</th>
                                <th class="border px-3 py-2 text-left">Effective To</th>
                                <th class="border px-3 py-2 text-center">Status</th>
                                <th class="border px-3 py-2 text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($targets as $target)
                                <tr class="hover:bg-gray-50">

                                    {{-- Branch --}}
                                    <td class="border px-3 py-2">
                                        {{ $target->branch }}
                                    </td>

                                    {{-- Period --}}
                                    <td class="border px-3 py-2">
                                        Period {{ $target->period_no }}
                                    </td>

                                    {{-- CORE TARGET (CORE ONLY) --}}
                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($target->core_target_sales ?? 0, 2) }}
                                    </td>

                                    {{-- STILLS TARGET --}}
                                    <td class="border px-3 py-2 text-right">
                                        {{ number_format($target->stills_target_sales ?? 0, 2) }}
                                    </td>

                                    {{-- OVERALL TARGET (CORE + STILLS, PET EXCLUDED) --}}
                                    <td class="border px-3 py-2 text-right font-semibold">
                                        {{ number_format(
                                            ($target->core_target_sales ?? 0) +
                                            ($target->stills_target_sales ?? 0),
                                            2
                                        ) }}
                                    </td>

                                    {{-- Dates --}}
                                    <td class="border px-3 py-2">
                                        {{ $target->start_date?->format('M d, Y') }}
                                    </td>

                                    <td class="border px-3 py-2">
                                        {{ $target->end_date?->format('M d, Y') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="border px-3 py-2 text-center">
                                        @if ($target->is_locked)
                                            <span class="text-green-600 font-semibold">
                                                Locked
                                            </span>
                                        @else
                                            <span class="text-yellow-600 font-semibold">
                                                Open
                                            </span>
                                        @endif
                                    </td>

                                    <td class="border px-3 py-2 text-center">
                                        <form method="POST"
                                              action="{{ route('admin.period-targets.destroy', $target) }}"
                                              onsubmit="return confirm('Delete this period target?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700">
                                                Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
