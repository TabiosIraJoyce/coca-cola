<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-blue-700">Bank Management</h2>
                <p class="text-sm text-gray-500">Search, update, and maintain all bank accounts.</p>
            </div>
            <a href="{{ route('admin.banks.create') }}"
               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-sm">
                + Add Bank
            </a>
        </div>
    </x-slot>

    <div class="space-y-5">

        @if (session('success'))
            <div class="px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 font-medium">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-5 bg-white rounded-xl border border-gray-200 shadow-sm">
            <form method="GET" action="{{ route('admin.banks.index') }}"
                  class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label for="q" class="block text-sm font-semibold text-gray-700 mb-1">Search</label>
                    <input id="q"
                           name="q"
                           type="text"
                           value="{{ request('q', $q ?? '') }}"
                           placeholder="Bank, branch, holder, account, or routing"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select id="status"
                            name="status"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="active" {{ request('status', $status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status', $status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="w-full md:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                        Search
                    </button>
                    <a href="{{ route('admin.banks.index') }}"
                       class="w-full md:w-auto px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-slate-50 border-b border-gray-200 text-sm text-gray-600">
                Showing {{ $banks->firstItem() ?? 0 }}-{{ $banks->lastItem() ?? 0 }} of {{ $banks->total() }} banks
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-[#eef4ff] text-[#1e3a8a]">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Bank</th>
                            <th class="px-4 py-3 text-left font-semibold">Branch</th>
                            <th class="px-4 py-3 text-left font-semibold">Account Holder</th>
                            <th class="px-4 py-3 text-left font-semibold">Account #</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($banks as $bank)
                            <tr class="hover:bg-blue-50/40">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $bank->bank_name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $bank->branch_name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $bank->account_holder_name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $bank->account_number }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $bank->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                        {{ ucfirst($bank->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.banks.edit', $bank) }}"
                                           class="px-3 py-1.5 rounded-md bg-amber-100 text-amber-800 hover:bg-amber-200 font-medium">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.banks.destroy', $bank) }}"
                                              onsubmit="return confirm('Delete this bank record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-md bg-red-100 text-red-700 hover:bg-red-200 font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
                                    No banks found for your filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200 bg-white">
                {{ $banks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
