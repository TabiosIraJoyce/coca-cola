<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-facebookBlue">📈 Sales Inputs</h2>
    </x-slot>

    <div class="mb-4">
        <a href="{{ route('sales-inputs.create') }}" class="bg-facebookBlue text-white px-4 py-2 rounded hover:bg-blue-600">
            ➕ Add Sales Input
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-4 rounded shadow">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 text-left text-sm uppercase">
                    <th class="p-2">Date</th>
                    <th class="p-2">Division</th>
                    <th class="p-2">Business Line</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inputs as $input)
                    <tr class="border-t">
                        <td class="p-2">{{ $input->date }}</td>
                        <td class="p-2">{{ $input->division->division_name ?? '-' }}</td>
                        <td class="p-2">{{ $input->businessLine->name ?? '-' }}</td>
                        <td class="p-2 text-sm text-gray-500 italic">View/Edit coming soon</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-2 text-center text-gray-500">No sales inputs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
