<h3 class="text-lg font-semibold mb-3">🧾 Receipts Breakdown</h3>

@if(isset($receipts) && $receipts->count())
<table class="w-full text-sm border mb-6">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">Route</th>
            <th class="border p-2">Leadman</th>
            <th class="border p-2">FC</th>
            <th class="border p-2">HC</th>
            <th class="border p-2">Gross Sales</th>
            <th class="border p-2">Net Sales</th>
            <th class="border p-2">Cash Proceeds</th>
            <th class="border p-2">Total Remittance</th>
            <th class="border p-2">Shortage / Overage</th>
            <th class="border p-2">Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($receipts as $r)
        <tr>
            <td class="border p-2">{{ $r->route ?? '—' }}</td>
            <td class="border p-2">{{ $r->leadman ?? '—' }}</td>
            <td class="border p-2">{{ $r->fc ?? 0 }}</td>
            <td class="border p-2">{{ $r->hc ?? 0 }}</td>

            <td class="border p-2 text-right">
                ₱ {{ number_format($r->gross_sales ?? 0, 2) }}
            </td>
            <td class="border p-2 text-right">
                ₱ {{ number_format($r->net_sales ?? 0, 2) }}
            </td>
            <td class="border p-2 text-right">
                ₱ {{ number_format($r->cash_proceeds ?? 0, 2) }}
            </td>
            <td class="border p-2 text-right">
                ₱ {{ number_format($r->total_remittance ?? 0, 2) }}
            </td>
            <td class="border p-2 text-right">
                ₱ {{ number_format($r->shortage_overage ?? 0, 2) }}
            </td>
            <td class="border p-2">
                {{ $r->report_date ?? '—' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-center text-gray-500 italic py-4">
    No receipt records available.
</p>
@endif
