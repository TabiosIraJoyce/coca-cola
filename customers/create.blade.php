<x-app-layout>
    <div class="flex min-h-screen">

        {{-- Main Content --}}
        <main class="flex-1 p-6 bg-gray-100">

            <h1 class="text-xl font-bold mb-4">Add Customer</h1>

            <div class="bg-white rounded shadow p-6 max-w-3xl mx-auto">

                {{-- CUSTOMER FORM --}}
                <form method="POST" action="{{ route('admin.customers.store') }}" id="customerForm">
                    @csrf

                    {{-- Delivery Info --}}
                    <div class="mb-6 border-b pb-4">
                        <div class="grid grid-cols-2 gap-4">

                            {{-- Delivery Route --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Delivery Route (Day)
                                </label>
                                <select name="delivery_route"
                                        class="w-full border border-gray-300 p-2 rounded"
                                        required>
                                    <option value="">Select Day</option>
                                    <option value="MONDAY">Monday</option>
                                    <option value="TUESDAY">Tuesday</option>
                                    <option value="WEDNESDAY">Wednesday</option>
                                    <option value="THURSDAY">Thursday</option>
                                    <option value="FRIDAY">Friday</option>
                                    <option value="SATURDAY">Saturday</option>
                                </select>
                            </div>

                            {{-- Sub Route --}}
                            <div>
                                <label class="block text-sm font-medium mb-1">
                                    Sub Route
                                </label>
                                <select name="sub_route"
                                        class="w-full border border-gray-300 p-2 rounded"
                                        required>
                                    <option value="">Select Sub Route</option>
                                    <option value="CRS 1">CRS 1</option>
                                    <option value="CRS 2">CRS 2</option>
                                    <option value="CRS 3">CRS 3</option>
                                    <option value="WATER">WATER</option>
                                    <option value="PRE SELLER">PRE SELLER</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    {{-- Customer Details --}}
                    <div class="grid grid-cols-3 gap-4">

                        {{-- Customer --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Customer
                            </label>
                            <input type="text"
                                   name="customer"
                                   class="w-full border border-gray-300 p-2 rounded"
                                   required>
                        </div>

                        {{-- Store Name --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Store Name
                            </label>
                            <input type="text"
                                   name="store_name"
                                   class="w-full border border-gray-300 p-2 rounded"
                                   required>
                        </div>

                        {{-- Contact Number --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Contact Number
                            </label>
                            <input type="text"
                                   name="contact_number"
                                   class="w-full border border-gray-300 p-2 rounded">
                        </div>

                        {{-- Credit Limit --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Credit Limit
                            </label>
                            <input type="number"
                                   name="credit_limit"
                                   step="0.01"
                                   class="w-full border border-gray-300 p-2 rounded">
                        </div>

                        {{-- Address --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-1">
                                Address
                            </label>
                            <input type="text"
                                   name="address"
                                   class="w-full border border-gray-300 p-2 rounded">
                        </div>

                        {{-- Remarks --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">
                                Remarks
                            </label>
                            <select name="remarks"
                                    class="w-full border border-gray-300 p-2 rounded"
                                    required>
                                <option value="">Select</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="CLOSED">CLOSED</option>
                            </select>
                        </div>

                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 mt-6">
                        <button type="submit"
                                class="bg-facebookBlue text-white px-4 py-2 rounded hover:opacity-90">
                            💾 Save
                        </button>

                        <a href="{{ route('admin.customers.index') }}"
                           class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

        </main>
    </div>
</x-app-layout>
