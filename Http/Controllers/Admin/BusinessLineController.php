<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessLine;

class BusinessLineController extends Controller
{
    // 📄 List all business lines
    public function index()
    {
        $businessLines = BusinessLine::latest()->paginate(10);
        return view('admin.business-lines.index', compact('businessLines'));
    }

    // ➕ Show create form
    public function create()
    {
        return view('admin.business-lines.create');
    }

    // 💾 Store new business line
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:business_lines,name',
            'description' => 'nullable|string',
        ]);

        BusinessLine::create($request->only('name', 'description'));

        return redirect()->route('business-lines.index')
                         ->with('success', '✅ Business Line created successfully.');
    }

    // 🖊 Show edit form
    public function edit(BusinessLine $businessLine)
    {
        return view('admin.business-lines.edit', compact('businessLine'));
    }

    // 🔁 Update a business line
    public function update(Request $request, BusinessLine $businessLine)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:business_lines,name,' . $businessLine->id,
            'description' => 'nullable|string',
        ]);

        $businessLine->update($request->only('name', 'description'));

        return redirect()->route('business-lines.index')
                         ->with('success', '✅ Business Line updated successfully.');
    }

    // ❌ Delete a business line
    public function destroy(BusinessLine $businessLine)
    {
        $businessLine->delete();

        return redirect()->route('business-lines.index')
                         ->with('success', '🗑️ Business Line deleted successfully.');
    }
}
