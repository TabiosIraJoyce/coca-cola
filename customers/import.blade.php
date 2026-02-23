<x-app-layout>
    <div class="flex min-h-screen">
        <main class="flex-1 p-6 bg-gray-100">
            <div class="max-w-3xl">
                <h1 class="text-xl font-bold mb-2">Upload Customers (Excel/CSV)</h1>
                <p class="text-sm text-gray-600 mb-6">
                    Upload an <b>.xlsx</b> or <b>.csv</b> file to add/update customers in bulk.
                </p>

                @if (session('success'))
                    <div class="mb-4 p-3 rounded border border-green-200 bg-green-50 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded border border-red-200 bg-red-50 text-red-800 text-sm">
                        <div class="font-semibold mb-1">Please fix the following:</div>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
                        <div class="text-sm font-semibold text-gray-700">
                            Template Columns (Row 1 headers)
                        </div>
                        <a href="{{ route('admin.customers.import.template') }}"
                           class="text-sm text-blue-700 hover:underline">
                            Download CSV Template
                        </a>
                    </div>

                    <div class="text-xs text-gray-600 mb-5">
                        Required: <b>delivery_route</b>, <b>sub_route</b>, <b>customer</b>, <b>store_name</b>, <b>remarks</b>.
                        Remarks must be <b>ACTIVE</b> or <b>CLOSED</b>.
                    </div>

                    <form method="POST"
                          action="{{ route('admin.customers.import.store') }}"
                          enctype="multipart/form-data"
                          class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">Excel/CSV File</label>
                            <input type="file"
                                   name="file"
                                   accept=".xlsx,.xls,.csv"
                                   required
                                   class="block w-full border border-gray-300 rounded p-2 bg-white">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit"
                                    class="bg-facebookBlue text-white px-4 py-2 rounded hover:opacity-90">
                                Upload & Import
                            </button>
                            <a href="{{ route('admin.customers.index') }}"
                               class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                                Back to Customer List
                            </a>
                        </div>
                    </form>
                </div>

                @if (session('import_preview') && is_array(session('import_preview')) && count(session('import_preview')))
                    @php
                        $preview = session('import_preview');
                        $limit = (int) (session('import_preview_limit') ?? 0);
                    @endphp

                    <div class="mt-6 bg-white rounded-xl shadow p-6 border border-gray-200">
                        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                            <h2 class="text-sm font-bold tracking-wide text-gray-800">
                                Uploaded Rows Preview
                            </h2>
                            @if ($limit > 0 && count($preview) >= $limit)
                                <div class="text-xs text-gray-500">
                                    Showing first {{ $limit }} rows
                                </div>
                            @endif
                        </div>

                        <div class="overflow-auto">
                            <table class="min-w-full border text-xs">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2">Action</th>
                                        <th class="border p-2">Delivery Route</th>
                                        <th class="border p-2">Sub Route</th>
                                        <th class="border p-2">Owner/Customer</th>
                                        <th class="border p-2">Store Name</th>
                                        <th class="border p-2">Address</th>
                                        <th class="border p-2">Contact</th>
                                        <th class="border p-2">Credit Limit</th>
                                        <th class="border p-2">Remarks</th>
                                        <th class="border p-2">Sheet</th>
                                        <th class="border p-2">Row</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($preview as $r)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border p-2 font-bold {{ ($r['_action'] ?? '') === 'ADDED' ? 'text-green-700' : 'text-blue-700' }}">
                                                {{ $r['_action'] ?? '' }}
                                            </td>
                                            <td class="border p-2">{{ $r['delivery_route'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['sub_route'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['customer'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['store_name'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['address'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['contact_number'] ?? '' }}</td>
                                            <td class="border p-2 text-right">{{ number_format((float) ($r['credit_limit'] ?? 0), 2) }}</td>
                                            <td class="border p-2">{{ $r['remarks'] ?? '' }}</td>
                                            <td class="border p-2">{{ $r['_sheet'] ?? '—' }}</td>
                                            <td class="border p-2 text-right">{{ $r['_row'] ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if (session('import_duplicates') && is_array(session('import_duplicates')) && count(session('import_duplicates')))
                    @php
                        $dups = session('import_duplicates');
                        $dupLimit = (int) (session('import_duplicates_limit') ?? 0);
                    @endphp

                    <div class="mt-6 bg-white rounded-xl shadow p-6 border border-yellow-200">
                        <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                            <h2 class="text-sm font-bold tracking-wide text-yellow-800">
                                Possible Duplicates (Skipped)
                            </h2>
                            @if ($dupLimit > 0 && count($dups) >= $dupLimit)
                                <div class="text-xs text-gray-500">
                                    Showing first {{ $dupLimit }} duplicates
                                </div>
                            @endif
                        </div>

                        <p class="text-xs text-gray-600 mb-3">
                            We detected that some uploaded <b>NAME</b> values already exist in your Customers list.
                            To avoid creating duplicates, those rows were skipped.
                        </p>

                        <div class="overflow-auto">
                            <table class="min-w-full border text-xs">
                                <thead class="bg-yellow-50">
                                    <tr>
                                        <th class="border p-2">Uploaded Name</th>
                                        <th class="border p-2">Uploaded Store</th>
                                        <th class="border p-2">Uploaded Route</th>
                                        <th class="border p-2">Uploaded Sub Route</th>
                                        <th class="border p-2">Sheet</th>
                                        <th class="border p-2">Row</th>
                                        <th class="border p-2">Existing Matches</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dups as $d)
                                        <tr class="hover:bg-gray-50">
                                            <td class="border p-2 font-semibold">{{ $d['customer'] ?? '' }}</td>
                                            <td class="border p-2">{{ $d['store_name'] ?? '' }}</td>
                                            <td class="border p-2">{{ $d['delivery_route'] ?? '' }}</td>
                                            <td class="border p-2">{{ $d['sub_route'] ?? '' }}</td>
                                            <td class="border p-2">{{ $d['_sheet'] ?? '—' }}</td>
                                            <td class="border p-2 text-right">{{ $d['_row'] ?? '—' }}</td>
                                            <td class="border p-2">
                                                @if (!empty($d['_existing']) && is_array($d['_existing']))
                                                    @foreach ($d['_existing'] as $ex)
                                                        <div class="mb-1">
                                                            <span class="font-semibold">#{{ $ex['id'] }}</span>
                                                            — {{ $ex['delivery_route'] }} / {{ $ex['sub_route'] }}
                                                            — {{ $ex['store_name'] }}
                                                        </div>
                                                    @endforeach
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</x-app-layout>
