<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\BusinessLine;

class DivisionController extends Controller
{
    // 📄 List all divisions
    public function index()
    {
        $divisions = Division::latest()->get();
        return view('admin.divisions.index', compact('divisions'));
    }

    // ➕ Show form to create new division
    public function create()
    {
        $businessLines = BusinessLine::all();
        return view('admin.divisions.create', compact('businessLines'));
    }

    // 💾 Store new division
    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'business_line_id' => 'required|exists:business_lines,id',
            'supervisor_name' => 'required|string|max:255',
            'oic_name' => 'required|string|max:255',
            'division_address' => 'required|string|max:255',
            'division_contact_number' => 'required|string|max:20',
            'division_telephone_number' => 'nullable|string|max:20',
        ]);

        Division::create($request->only([
            'division_name',
            'business_line_id',
            'supervisor_name',
            'oic_name',
            'division_address',
            'division_contact_number',
            'division_telephone_number',
        ]));

        return redirect()->route('divisions.index')
                         ->with('success', '✅ Division created successfully.');
    }

    // 🔍 View a single division
    public function show(Division $division)
    {
        return view('admin.divisions.show', compact('division'));
    }

    // ✏️ Show form to edit existing division
    public function edit(Division $division)
    {
        $businessLines = BusinessLine::all();
        return view('admin.divisions.edit', compact('division', 'businessLines'));
    }

    // 🔁 Update existing division
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'business_line_id' => 'required|exists:business_lines,id',
            'supervisor_name' => 'required|string|max:255',
            'oic_name' => 'required|string|max:255',
            'division_address' => 'required|string|max:255',
            'division_contact_number' => 'required|string|max:20',
            'division_telephone_number' => 'nullable|string|max:20',
        ]);

        $division->update($request->only([
            'division_name',
            'business_line_id',
            'supervisor_name',
            'oic_name',
            'division_address',
            'division_contact_number',
            'division_telephone_number',
        ]));

        return redirect()->route('divisions.index')
                         ->with('success', '✅ Division updated successfully.');
    }

    // ❌ Delete a division
    public function destroy(Division $division)
    {
        $division->delete();

        return redirect()->route('divisions.index')
                         ->with('success', '🗑️ Division deleted successfully.');
    }
}
