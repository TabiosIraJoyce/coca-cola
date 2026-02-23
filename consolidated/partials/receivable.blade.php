<h3 class="text-lg font-semibold mb-3">📘 Receivables Monitoring</h3>

@if(isset($receivables) && $receivables->count())
<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Customer Name</th>
            <th class="border p-2 text-right">Amount</th>
            <th class="border p-2">Due Date</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Remarks</th>

        </tr>
    </thead>

    <tbody>
        @foreach($receivables as $rc)
        <tr>
            <td class="border p-2">{{ $rc->client_name ?? '—' }}</td>

            <td class="border p-2 text-right">
                ₱ {{ number_format($rc->amount_due ?? 0, 2) }}
            </td>

            <<td class="border p-2">{{ $rc->due_date ?? '—' }}</td>

<td class="border p-2">
    @php
        $today = \Carbon\Carbon::today();
        $due   = $rc->due_date ? \Carbon\Carbon::parse($rc->due_date) : null;
    @endphp

    @if(!$due)
        <span class="text-gray-400 text-xs">—</span>
    @elseif($today->gt($due))
        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-700">
            Overdue
        </span>
    @elseif($today->eq($due))
        <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-700">
            Due Today
        </span>
    @else
        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-700">
            Upcoming
        </span>
    @endif
</td>

<td class="border p-2">{{ $rc->receivable_remarks ?? '—' }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-center text-gray-500 italic py-4">
    No receivable records available.
</p>
@endif
