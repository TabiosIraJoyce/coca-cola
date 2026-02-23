<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function productCategories(): array
    {
        // Keep category values normalized (lowercase, no spaces) since
        // downstream reports/grouping depend on exact category keys.
        return [
            'core'   => 'CORE',
            'petcsd' => 'PET CSD',
            'stills' => 'STILLS',
        ];
    }

    private function productStatuses(): array
    {
        return [
            'active'   => 'Active',
            'inactive' => 'Inactive',
        ];
    }

    private function computeUcsFactor(?float $unitMl, ?int $bottlesPerCase): ?float
    {
        // Formula: (ml * bottles) / 5678 = UCS
        $unitMl = (float) ($unitMl ?? 0);
        $bottlesPerCase = (int) ($bottlesPerCase ?? 0);

        if ($unitMl <= 0 || $bottlesPerCase <= 0) {
            return null;
        }

        return round(($unitMl * $bottlesPerCase) / 5678, 6);
    }

    private function parseUnitMlFromPackSize(?string $packSize): ?float
    {
        $s = strtolower(str_replace(',', '', (string) ($packSize ?? '')));
        $s = trim($s);

        if ($s === '') {
            return null;
        }

        // Prefer explicit ML first (e.g. "237ml", "330 ML", "Cans 320ML")
        if (preg_match('/(\d+(?:\.\d+)?)\s*ml/i', $s, $m)) {
            $ml = (float) $m[1];
            return $ml > 0 ? $ml : null;
        }

        // Liters (e.g. "1 LITER", "1.5 LTR x 12", "2L", "1.75L")
        if (preg_match('/(\d+(?:\.\d+)?)\s*(?:liters?|litres?|ltr|l)/i', $s, $m)) {
            $liters = (float) $m[1];
            if ($liters <= 0) {
                return null;
            }

            return $liters * 1000;
        }

        return null;
    }

    public function create()
    {
        $categories = $this->productCategories();
        $statuses   = $this->productStatuses();

        return view('admin.products.create', compact('categories', 'statuses'));
    }

    public function index()
    {
        $products = Product::orderBy('category')
            ->orderBy('pack_size')
            ->orderBy('product_name')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'     => 'required|string',
            'pack_size'    => 'nullable|string|max:50',
            'product_name' => 'required|string',
            'srp'          => 'nullable|numeric|min:0',
            'status'       => 'nullable|in:active,inactive',
            'unit_ml'           => 'nullable|numeric|min:0',
            'bottles_per_case'  => 'nullable|integer|min:0',
        ]);

        $category = strtolower(preg_replace('/\s+/', '', (string) $request->category));
        if (!array_key_exists($category, $this->productCategories())) {
            return back()
                ->withErrors(['category' => 'Category must be CORE, PET CSD, or STILLS.'])
                ->withInput();
        }

        $unitMl = $request->filled('unit_ml') ? (float) $request->unit_ml : null;
        if ($unitMl === null) {
            $unitMl = $this->parseUnitMlFromPackSize($request->pack_size);
        }
        $bottlesPerCase = $request->filled('bottles_per_case') ? (int) $request->bottles_per_case : null;
        $ucs = $this->computeUcsFactor($unitMl, $bottlesPerCase);

        Product::create([
            'category'     => $category,
            'pack_size'    => $request->pack_size ?: null, // ✅ SAFE FIX
            'product_name' => $request->product_name,
            'srp'          => $request->srp,
            'status'       => $request->status ?: 'active',
            'unit_ml'          => $unitMl,
            'bottles_per_case' => $bottlesPerCase,
            'ucs'              => $ucs,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product added successfully.');
    }

    public function edit(Product $product)
    {
        $categories = $this->productCategories();
        $statuses   = $this->productStatuses();

        return view('admin.products.edit', compact('product', 'categories', 'statuses'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category'     => 'required|string',
            'pack_size'    => 'nullable|string|max:50',
            'product_name' => 'required|string',
            'srp'          => 'nullable|numeric|min:0',
            'status'       => 'nullable|in:active,inactive',
            'unit_ml'           => 'nullable|numeric|min:0',
            'bottles_per_case'  => 'nullable|integer|min:0',
        ]);

        $category = strtolower(preg_replace('/\s+/', '', (string) $request->category));
        if (!array_key_exists($category, $this->productCategories())) {
            return back()
                ->withErrors(['category' => 'Category must be CORE, PET CSD, or STILLS.'])
                ->withInput();
        }

        $unitMl = $request->filled('unit_ml') ? (float) $request->unit_ml : null;
        if ($unitMl === null) {
            $unitMl = $this->parseUnitMlFromPackSize($request->pack_size);
        }
        $bottlesPerCase = $request->filled('bottles_per_case') ? (int) $request->bottles_per_case : null;
        $ucs = $this->computeUcsFactor($unitMl, $bottlesPerCase);

        $product->update([
            'category'     => $category,
            'pack_size'    => $request->pack_size ?: null, // ✅ SAFE FIX
            'product_name' => $request->product_name,
            'srp'          => $request->srp,
            'status'       => $request->status ?: $product->status ?: 'active',
            'unit_ml'          => $unitMl,
            'bottles_per_case' => $bottlesPerCase,
            'ucs'              => $ucs,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Selected products deleted successfully.');
    }
}
