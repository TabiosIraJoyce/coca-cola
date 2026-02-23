<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesInput;
use App\Models\SalesInputItem;
use App\Models\Division;
use App\Models\SalesTemplate;
use App\Models\ValidatedRemittance;
use App\Models\ValidatedRemittanceReceipt;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SalesInputController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesInput::with('division', 'businessLine', 'items');

        // Sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        if (in_array($sortField, ['created_at', 'date', 'business_line_id']) && in_array($sortOrder, ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->latest();
        }

        // Filters
        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        if ($request->filled('filter_date')) {
            $query->whereDate('date', $request->filter_date);
        }
        if ($request->filled('summary_field')) {
            $query->whereHas('items', function($q) use ($request) {
                $q->where('field_label', 'like', '%' . $request->summary_field . '%');
            });
        }

        // Pagination
        $perPage = $request->get('perPage', 50);
        $inputs = $perPage === 'all' ? $query->get() : $query->paginate((int)$perPage);

        // Divisions for filter dropdown
        $divisions = Division::all();
        $divisionId = $request->division_id;

        return view('admin.sales-inputs.index', compact('inputs', 'divisions', 'divisionId', 'sortField', 'sortOrder', 'perPage'));
    }

    public function create(Request $request)
    {
        $divisions = Division::with('businessLine')->get();
        $selectedDivision = null;
        $templateFields = [];

        if ($request->has('division_id')) {
            $selectedDivision = Division::with('businessLine')->find($request->division_id);
            $templateFields = SalesTemplate::where('business_line_id', $selectedDivision->business_line_id)
                                           ->orderBy('field_order')
                                           ->get();
        }

        return view('admin.sales-inputs.create', compact('divisions', 'selectedDivision', 'templateFields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'date' => 'required|date',
            'data' => 'required|array',
        ]);

        $division = Division::findOrFail($request->division_id);

        $input = SalesInput::create([
            'division_id' => $division->id,
            'business_line_id' => $division->business_line_id,
            'date' => $request->date,
            'data' => json_encode($request->data),
        ]);

        foreach ($request->data as $label => $value) {
            SalesInputItem::create([
                'sales_input_id' => $input->id,
                'field_label' => $label,
                'field_type' => 'number',
                'value' => $value,
            ]);
        }

        return redirect()->route('admin.sales-inputs.index')->with('success', '✅ Sales input saved.');
    }

    public function edit(SalesInput $salesInput)
    {
        $salesInput->load('items');
        $divisions = Division::with('businessLine')->get();

        return view('admin.sales-inputs.edit', compact('salesInput', 'divisions'));
    }

    public function update(Request $request, SalesInput $salesInput)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'date' => 'required|date',
            'data' => 'required|array',
        ]);

        $division = Division::findOrFail($request->division_id);

        $salesInput->update([
            'division_id' => $division->id,
            'business_line_id' => $division->business_line_id,
            'date' => $request->date,
            'data' => json_encode($request->data),
        ]);

        $salesInput->items()->delete();
        foreach ($request->data as $label => $value) {
            $salesInput->items()->create([
                'field_label' => $label,
                'field_type' => 'number',
                'value' => $value,
            ]);
        }

        return redirect()->route('admin.sales-inputs.index')->with('success', '✅ Sales input updated!');
    }

    public function destroy(SalesInput $salesInput)
    {
        $salesInput->delete();
        return redirect()->route('admin.sales-inputs.index')->with('success', '🗑️ Sales input deleted!');
    }

    public function updateValidatedRemittance(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'date' => 'required|date',
            'validated_amount' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:255',
            'control_number' => 'nullable|string|max:255',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'remarks' => 'nullable|string|max:255',
        ]);

        $input = SalesInput::where('division_id', $request->division_id)
            ->whereDate('date', $request->date)
            ->with('items')
            ->first();

        if (!$input) {
            return response()->json(['error' => 'Sales input not found.'], 404);
        }

        $cash = $overage = $arCollections = $shortageCollection = $shortage = 0;
        foreach ($input->items as $item) {
            $label = strtolower($item->field_label);
            $value = floatval(str_replace(',', '', $item->value));
            match ($label) {
                'cash sales' => $cash = $value,
                'cash overage' => $overage = $value,
                'ar collections' => $arCollections = $value,
                'collection on shortages' => $shortageCollection = $value,
                'cash shortage' => $shortage = $value,
                default => null
            };
        }

        $totalRemittance = $cash + $overage + $arCollections + $shortageCollection - $shortage;
        $validatedAmount = floatval($request->validated_amount);
        $validatedOverage = max(0, $validatedAmount - $totalRemittance);
        $validatedShortage = max(0, $totalRemittance - $validatedAmount);

        $validatedRemittance = ValidatedRemittance::updateOrCreate(
            ['division_id' => $request->division_id, 'date' => $request->date],
            [
                'validated_amount' => $validatedAmount,
                'validated_overage' => $validatedOverage,
                'validated_shortage' => $validatedShortage,
                'account_number' => $request->account_number,
                'control_number' => $request->control_number,
                'remarks' => $request->remarks,
            ]
        );

        if ($request->hasFile('receipt_image')) {
            $files = is_array($request->file('receipt_image')) ? $request->file('receipt_image') : [$request->file('receipt_image')];
            foreach ($files as $file) {
                $filename = uniqid('receipt_') . '.jpg';
                $image = Image::make($file)
                    ->orientate()
                    ->resize(2048, null, fn($constraint) => $constraint->aspectRatio()->upsize())
                    ->encode('jpg', 80);
                Storage::put("public/validation_receipts/{$filename}", $image);
                ValidatedRemittanceReceipt::create([
                    'validated_remittance_id' => $validatedRemittance->id,
                    'file_path' => "validation_receipts/{$filename}",
                ]);
            }
        }

        return response()->json([
            'message' => '✅ Saved successfully',
            'validated_amount' => $validatedAmount,
            'validated_overage' => $validatedOverage,
            'validated_shortage' => $validatedShortage,
            'total_remittance' => $totalRemittance,
        ]);
    }

    public function deleteReceipt($id)
    {
        $receipt = ValidatedRemittanceReceipt::findOrFail($id);
        if ($receipt->file_path && Storage::exists('public/' . $receipt->file_path)) {
            Storage::delete('public/' . $receipt->file_path);
        }
        $receipt->delete();
        return response()->json(['success' => true, 'message' => 'Receipt Deleted']);
    }
}
