<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-blue-700">Edit Bank</h2>
    </x-slot>

    <div class="p-6 bg-white rounded-lg shadow max-w-2xl mx-auto">

        <form method="POST"
              action="{{ route('admin.banks.update', $bank) }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            @include('admin.bank-management.partials.form', ['bank' => $bank])

            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.banks.index') }}"
                   class="px-4 py-2 border rounded">
                    Cancel
                </a>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update Bank
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
