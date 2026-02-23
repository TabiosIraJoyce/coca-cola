<x-app-layout>
    <div class="p-6 bg-gray-100">

        <h1 class="text-xl font-bold mb-6">Edit Customer</h1>

        <div class="bg-white rounded shadow p-6 max-w-xl">

            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')

                {{-- Delivery Route --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Delivery Route</label>
                    <input type="text"
                           name="delivery_route"
                           value="{{ old('delivery_route', $customer->delivery_route) }}"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                {{-- Sub Route --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Sub Route</label>
                    <input type="text"
                           name="sub_route"
                           value="{{ old('sub_route', $customer->sub_route) }}"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                {{-- Owner Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Owner Name</label>
                    <input type="text"
                           name="customer"
                           value="{{ old('customer', $customer->customer) }}"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                {{-- Store Name --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Store Name</label>
                    <input type="text"
                           name="store_name"
                           value="{{ old('store_name', $customer->store_name) }}"
                           class="w-full border p-2 rounded"
                           required>
                </div>

                {{-- Address --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Address</label>
                    <textarea name="address"
                              class="w-full border p-2 rounded"
                              rows="3">{{ old('address', $customer->address) }}</textarea>
                </div>

                {{-- Contact Number --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Contact Number</label>
                    <input type="text"
                           name="contact_number"
                           value="{{ old('contact_number', $customer->contact_number) }}"
                           class="w-full border p-2 rounded">
                </div>

                {{-- Credit Limit --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Credit Limit</label>
                    <input type="number"
                           step="0.01"
                           name="credit_limit"
                           value="{{ old('credit_limit', $customer->credit_limit) }}"
                           class="w-full border p-2 rounded">
                </div>

                {{-- Remarks --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Remarks</label>
                    <select name="remarks" class="w-full border p-2 rounded">
                        <option value="ACTIVE" {{ $customer->remarks === 'ACTIVE' ? 'selected' : '' }}>
                            ACTIVE
                        </option>
                        <option value="CLOSED" {{ $customer->remarks === 'CLOSED' ? 'selected' : '' }}>
                            CLOSED
                        </option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:opacity-90">
                        💾 Update
                    </button>

                    <a href="{{ route('admin.customers.index') }}"
                       class="px-4 py-2 border rounded hover:bg-gray-100">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
