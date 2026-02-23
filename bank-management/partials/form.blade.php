@php $bank = $bank ?? null; @endphp

<div>
    <label class="block text-sm font-semibold">Bank Name</label>
    <input name="bank_name"
           value="{{ old('bank_name', $bank->bank_name ?? '') }}"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Branch Name</label>
    <input name="branch_name"
           value="{{ old('branch_name', $bank->branch_name ?? '') }}"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Account Holder Name</label>
    <input name="account_holder_name"
           value="{{ old('account_holder_name', $bank->account_holder_name ?? '') }}"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Account Number</label>
    <input name="account_number"
           value="{{ old('account_number', $bank->account_number ?? '') }}"
           class="w-full border rounded px-3 py-2"
           required>
</div>

<div>
    <label class="block text-sm font-semibold">Routing Number</label>
    <input name="routing_number"
           value="{{ old('routing_number', $bank->routing_number ?? '') }}"
           class="w-full border rounded px-3 py-2"
           required>
</div>

@if($bank)
<div>
    <label class="block text-sm font-semibold">Status</label>
    <select name="status" class="w-full border rounded px-3 py-2">
        <option value="active" {{ $bank->status === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $bank->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
@endif
