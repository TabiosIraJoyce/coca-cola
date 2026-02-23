<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bank;

class BankController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    /* =========================
       INDEX — LIST BANKS
    ========================= */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', '');

        $banks = Bank::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('bank_name', 'LIKE', "%{$q}%")
                      ->orWhere('branch_name', 'LIKE', "%{$q}%")
                      ->orWhere('account_holder_name', 'LIKE', "%{$q}%")
                      ->orWhere('account_number', 'LIKE', "%{$q}%")
                      ->orWhere('routing_number', 'LIKE', "%{$q}%");
                });
            })
            ->when(in_array($status, ['active', 'inactive'], true), fn ($query) => $query->where('status', $status))
            ->orderBy('bank_name')
            ->orderBy('branch_name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bank-management.index', compact('banks', 'q', 'status'));
    }

    /* =========================
       CREATE — FORM
    ========================= */
    public function create()
    {
        return view('admin.bank-management.create');
    }
    
    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        return Bank::query()
            ->where('status', 'active')
            ->when($query !== '', function ($q) use ($query) {
                $q->where(function ($w) use ($query) {
                    $w->where('bank_name', 'LIKE', "%{$query}%")
                      ->orWhere('branch_name', 'LIKE', "%{$query}%");
                });
            })
            ->orderBy('bank_name')
            ->orderBy('branch_name')
            ->limit(10)
            ->get([
                'id',
                'bank_name',
                'branch_name',
                'account_holder_name',
                'account_number',
                'routing_number',
                'status',
            ]);
    }

    /* =========================
       STORE — SAVE BANK
    ========================= */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name'           => 'required|string|max:255',
            'branch_name'         => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:100|unique:banks,account_number',
            'routing_number'      => 'required|string|max:100',
        ]);

        Bank::create([
            'bank_name'           => $request->bank_name,
            'branch_name'         => $request->branch_name,
            'account_holder_name' => $request->account_holder_name,
            'account_number'      => $request->account_number,
            'routing_number'      => $request->routing_number,
            'status'              => 'active',
        ]);

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank successfully added.');
    }

    /* =========================
       EDIT — FORM
    ========================= */
    public function edit(Bank $bank)
    {
        return view('admin.bank-management.edit', compact('bank'));
    }

    /* =========================
       UPDATE — SAVE CHANGES
    ========================= */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'bank_name'           => 'required|string|max:255',
            'branch_name'         => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number'      => 'required|string|max:100|unique:banks,account_number,' . $bank->id,
            'routing_number'      => 'required|string|max:100',
            'status'              => 'required|in:active,inactive',
        ]);

        $bank->update($request->only([
            'bank_name',
            'branch_name',
            'account_holder_name',
            'account_number',
            'routing_number',
            'status',
        ]));

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank updated successfully.');
    }

    /* =========================
       TOGGLE STATUS
    ========================= */
    public function toggleStatus(Bank $bank)
    {
        $bank->update([
            'status' => $bank->status === 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank status updated.');
    }

    /* =========================
       DELETE
    ========================= */
    public function destroy(Bank $bank)
    {
        try {
            $bank->delete();

            return redirect()
                ->route('admin.banks.index')
                ->with('success', 'Bank deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.banks.index')
                ->with('error', 'Unable to delete bank. It may be used by existing records.');
        }
    }

}
