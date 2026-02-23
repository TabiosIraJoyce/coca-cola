<h3 class="text-lg font-semibold mb-3">📦 Borrower’s Monitoring Agreement</h3>

@if(isset($borrowers) && $borrowers->count())

<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2 text-left">Division</th>
            <th class="border p-2 text-left">Report Date</th>
            <th class="border p-2 text-right">Total Borrowed</th>
            <th class="border p-2 text-right">Total Returned</th>
            <th class="border p-2 text-right">Net Borrowed</th>
        </tr>
    </thead>

    <tbody>
        @foreach($borrowers as $b)
            @php
                $net = ($b->total_borrowed ?? 0) - ($b->total_returned ?? 0);
            @endphp

            <tr class="hover:bg-gray-50">
                {{-- ✅ DIVISION (RELATIONAL) --}}
                <td class="border p-2 font-medium">
                    {{ $b->division->division_name ?? '—' }}
                </td>

                {{-- REPORT DATE --}}
                <td class="border p-2">
                    {{ optional($b->report_date)->format('Y-m-d') ?? '—' }}
                </td>

                {{-- TOTAL BORROWED --}}
                <td class="border p-2 text-right">
                    {{ number_format($b->total_borrowed ?? 0, 2) }}
                </td>

                {{-- TOTAL RETURNED --}}
                <td class="border p-2 text-right">
                    {{ number_format($b->total_returned ?? 0, 2) }}
                </td>

                {{-- NET BORROWED --}}
                <td class="border p-2 text-right font-semibold
                    {{ $net > 0 ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($net, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@else
<p class="text-center text-gray-500 italic py-4">
    No borrower records available.
</p>
@endif
