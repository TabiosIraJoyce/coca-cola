<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesTemplate;
use App\Models\BusinessLine;
use Illuminate\Http\Request;

class SalesTemplateController extends Controller
{
    // 📋 Show all fields
    public function index(Request $request)
    {
        $query = SalesTemplate::with('businessLine')->orderBy('field_order');
    
        if ($request->filled('business_line_id')) {
            $query->where('business_line_id', $request->business_line_id);
        }
    
        $templates = $query->get();
        $businessLines = \App\Models\BusinessLine::all(); // Make sure to import
    
        return view('admin.sales-templates.index', compact('templates', 'businessLines'));
    }
    

    // ➕ Show create form
    public function create()
    {
        $businessLines = BusinessLine::all();
        return view('admin.sales-templates.create', compact('businessLines'));
    }

    // 💾 Store new template field
    public function store(Request $request)
    {
        $request->validate([
            'business_line_id' => 'required|exists:business_lines,id',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|string',
            'is_required' => 'nullable|boolean',
            'field_order' => 'nullable|integer',
        ]);

        SalesTemplate::create([
            'business_line_id' => $request->business_line_id,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'is_required' => $request->has('is_required'),
            'field_order' => $request->field_order ?? 0,
        ]);

        return redirect()->route('sales-templates.index')->with('success', '✅ Field added.');
    }

    // ✏️ Edit field
    public function edit(SalesTemplate $salesTemplate)
    {
        $businessLines = BusinessLine::all();
        return view('admin.sales-templates.edit', compact('salesTemplate', 'businessLines'));
    }

    // 🔁 Update field
    public function update(Request $request, SalesTemplate $salesTemplate)
    {
        $request->validate([
            'business_line_id' => 'required|exists:business_lines,id',
            'field_label' => 'required|string|max:255',
            'field_type' => 'required|string',
            'is_required' => 'nullable|boolean',
            'field_order' => 'nullable|integer',
        ]);

        $salesTemplate->update([
            'business_line_id' => $request->business_line_id,
            'field_label' => $request->field_label,
            'field_type' => $request->field_type,
            'is_required' => $request->has('is_required'),
            'field_order' => $request->field_order ?? 0,
        ]);

        return redirect()->route('sales-templates.index')->with('success', '✅ Field updated.');
    }

    // ❌ Delete field
    public function destroy(SalesTemplate $salesTemplate)
    {
        $salesTemplate->delete();
        return redirect()->route('sales-templates.index')->with('success', '🗑️ Field deleted.');
    }

}
