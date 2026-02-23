<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CustomerController extends Controller
{
    /**
     * Show customer list
     */
    public function index()
    {
        $dayAliases = [
            '1' => 'MONDAY',
            '2' => 'TUESDAY',
            '3' => 'WEDNESDAY',
            '4' => 'THURSDAY',
            '5' => 'FRIDAY',
            '6' => 'SATURDAY',
            'MON' => 'MONDAY',
            'TUE' => 'TUESDAY',
            'WED' => 'WEDNESDAY',
            'THU' => 'THURSDAY',
            'FRI' => 'FRIDAY',
            'SAT' => 'SATURDAY',
            'MONDAY' => 'MONDAY',
            'TUESDAY' => 'TUESDAY',
            'WEDNESDAY' => 'WEDNESDAY',
            'THURSDAY' => 'THURSDAY',
            'FRIDAY' => 'FRIDAY',
            'SATURDAY' => 'SATURDAY',
        ];

        $dayOrder = ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];

        $normalizeDeliveryRoute = function ($value) use ($dayAliases): string {
            $route = strtoupper(trim((string) $value));
            $route = preg_replace('/\s+/', ' ', $route);

            if ($route === '') {
                return '';
            }

            if (preg_match('/^([1-6])(?:\\.0)?$/', $route, $m)) {
                return $dayAliases[$m[1]] ?? $route;
            }

            return $dayAliases[$route] ?? $route;
        };

        $normalizeSubRoute = function ($value): string {
            $subRoute = strtoupper(trim((string) $value));
            $subRoute = preg_replace('/\s+/', ' ', $subRoute);

            if ($subRoute === '') {
                return '';
            }

            return match ($subRoute) {
                'PS', 'PRESELLER', 'PRE-SELLER' => 'PRE SELLER',
                default => $subRoute,
            };
        };

        $customers = Customer::query()
            ->whereNotNull('delivery_route')
            ->where('delivery_route', '!=', '')
            ->whereNotNull('sub_route')
            ->where('sub_route', '!=', '')
            ->get()
            ->map(function ($item) use ($normalizeDeliveryRoute, $normalizeSubRoute) {
                $item->delivery_route = $normalizeDeliveryRoute($item->delivery_route);
                $item->sub_route = $normalizeSubRoute($item->sub_route);
                return $item;
            })
            ->sortBy(function ($item) use ($dayOrder) {
                $route = strtoupper(trim((string) $item->delivery_route));
                $idx = array_search($route, $dayOrder, true);
                $order = $idx === false ? 99 : $idx;

                $subRoute = strtoupper(trim((string) $item->sub_route));
                $customer = strtoupper(trim((string) $item->customer));

                return sprintf('%02d|%s|%s', $order, $subRoute, $customer);
            })
            ->groupBy([
                fn ($item) => strtoupper(trim($item->delivery_route)),
                fn ($item) => strtoupper(trim($item->sub_route)),
            ]);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show add customer form
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Show import form (Excel/CSV)
     */
    public function importForm()
    {
        return view('admin.customers.import');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'delivery_route',
            'sub_route',
            'customer',
            'store_name',
            'contact_number',
            'address',
            'credit_limit',
            'remarks',
        ];

        $csv = implode(',', $headers) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers-template.csv"',
        ]);
    }

    /**
     * Import customers from Excel/CSV
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:xlsx,xls,csv',
        ]);

        // Build a fast lookup of existing customer names to detect possible duplicates.
        // Rule: If an uploaded row's NAME already exists in customers table (case-insensitive),
        // and the row is NOT an exact match (delivery_route + sub_route + customer + store_name),
        // we treat it as a possible duplicate and skip it (to prevent accidental duplicates).
        $nameNorm = function (string $v): string {
            $v = preg_replace('/\s+/', ' ', trim($v));
            return strtolower($v);
        };

        $existingByName = [];
        foreach (Customer::query()
            ->select(['id', 'delivery_route', 'sub_route', 'customer', 'store_name'])
            ->get() as $c) {
            $key = $nameNorm((string) ($c->customer ?? ''));
            if ($key === '') continue;
            $existingByName[$key][] = [
                'id' => (int) $c->id,
                'delivery_route' => (string) ($c->delivery_route ?? ''),
                'sub_route' => (string) ($c->sub_route ?? ''),
                'customer' => (string) ($c->customer ?? ''),
                'store_name' => (string) ($c->store_name ?? ''),
            ];
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());

        $rows = [];
        if ($ext === 'csv') {
            $handle = fopen($file->getRealPath(), 'r');
            if ($handle === false) {
                return back()->withErrors(['file' => 'Unable to read the uploaded CSV file.']);
            }
            while (($data = fgetcsv($handle)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        } else {
            // XLSX/XLS via PhpSpreadsheet (supports your "Delivery Route / Sub Route" template).
            $spreadsheet = IOFactory::load($file->getRealPath());

            // If this is the "Delivery Route: MONDAY" multi-sheet template, we import per sheet below.
            // We'll still support the "header row" template in the first sheet too.
            $rows = array_values($spreadsheet->getActiveSheet()->toArray(null, true, true, true));
        }

        // Normalize any array keys to prevent "Undefined array key 0" issues.
        $rows = array_values($rows);

        if (count($rows) < 2) {
            return back()->withErrors(['file' => 'The file is empty or missing data rows.']);
        }

        $norm = function ($v) {
            return strtolower(trim(preg_replace('/\s+/', '_', (string) $v)));
        };

        $parseLabelValue = function (?string $cell, string $label): ?string {
            if ($cell === null) return null;
            $cell = trim((string) $cell);
            if ($cell === '') return null;
            // Accept: "Delivery Route : MONDAY", "Delivery Route: MONDAY"
            $pattern = '/^' . preg_quote($label, '/') . '\s*:\s*(.+)$/i';
            if (preg_match($pattern, $cell, $m)) {
                return trim((string) ($m[1] ?? ''));
            }
            return null;
        };

        $hasHeaderTemplate = function (array $headerRow) use ($norm): bool {
            $headers = array_map($norm, $headerRow);
            $idx = array_flip($headers);
            return isset($idx['delivery_route']) && isset($idx['sub_route']) && isset($idx['customer']);
        };

        // If XLSX/XLS doesn't use the header-based template, try the "Delivery Route / Sub Route" template.
        if ($ext !== 'csv' && !$hasHeaderTemplate(array_values($rows[0] ?? []))) {
            $imported = 0;
            $updated = 0;
            $skipped = 0;
            $preview = [];
            $previewLimit = 200;
            $duplicates = [];
            $dupLimit = 200;
            $sheetsScanned = 0;
            $sheetsMatched = 0;

            DB::transaction(function () use (
                $spreadsheet,
                $parseLabelValue,
                $existingByName,
                $nameNorm,
                &$imported,
                &$updated,
                &$skipped,
                &$preview,
                $previewLimit,
                &$duplicates,
                $dupLimit,
                &$sheetsScanned,
                &$sheetsMatched
            ) {
                foreach ($spreadsheet->getWorksheetIterator() as $sheet) {
                    $sheetsScanned++;
                    $sheetRows = array_values($sheet->toArray(null, true, true, true));
                    if (count($sheetRows) < 3) continue;

                    // Find Delivery Route + Sub Route from top rows (usually row 1 and 2, col A)
                    $deliveryRoute = null;
                    $subRoute = null;
                    for ($r = 0; $r < min(30, count($sheetRows)); $r++) {
                        foreach (($sheetRows[$r] ?? []) as $cell) {
                            $deliveryRoute = $deliveryRoute ?: $parseLabelValue($cell, 'Delivery Route');
                            $subRoute = $subRoute ?: $parseLabelValue($cell, 'Sub Route');
                        }
                    }

                    $deliveryRoute = strtoupper(trim((string) ($deliveryRoute ?? $sheet->getTitle())));
                    $subRoute = strtoupper(trim((string) ($subRoute ?? '')));

                    // Find header row ("OWNERS NAME", "STORE NAME", "ADDRESS", "CONTACT NUMBER", "REMARKS")
                    $headerIndex = null;
                    $colMap = [
                        'owner' => null,
                        'store' => null,
                        'address' => null,
                        'contact' => null,
                        'remarks' => null,
                    ];
                    for ($r = 0; $r < min(30, count($sheetRows)); $r++) {
                        $row = $sheetRows[$r];

                        // Detect columns by scanning the entire row (handles shifted columns / merged cells).
                        $tmpMap = [
                            'owner' => null,
                            'store' => null,
                            'address' => null,
                            'contact' => null,
                            'remarks' => null,
                        ];
                        foreach ($row as $colKey => $cell) {
                            $v = strtoupper(trim((string) ($cell ?? '')));
                            if ($v === '') continue;

                            // OWNER/NAME column (your template sometimes uses NAME only)
                            if ($tmpMap['owner'] === null && (
                                str_contains($v, 'OWNER') ||
                                $v === 'NAME' ||
                                str_contains($v, 'CUSTOMER')
                            )) $tmpMap['owner'] = $colKey;

                            // STORE column (optional in some templates)
                            if ($tmpMap['store'] === null && str_contains($v, 'STORE')) $tmpMap['store'] = $colKey;

                            if ($tmpMap['address'] === null && str_contains($v, 'ADDRESS')) $tmpMap['address'] = $colKey;
                            // CONTACT column (CONTACT # / CONTACT NUMBER / PHONE)
                            if ($tmpMap['contact'] === null && (
                                str_contains($v, 'CONTACT') ||
                                str_contains($v, 'PHONE') ||
                                str_contains($v, 'NUMBER') ||
                                str_contains($v, '#')
                            )) $tmpMap['contact'] = $colKey;
                            if ($tmpMap['remarks'] === null && str_contains($v, 'REMARK')) $tmpMap['remarks'] = $colKey;
                        }

                        // Require at least owner + address to consider it a header row.
                        // (Some sheets don't have STORE NAME column.)
                        if ($tmpMap['owner'] !== null && $tmpMap['address'] !== null) {
                            $headerIndex = $r;
                            $colMap = $tmpMap;
                            break;
                        }
                    }

                    if ($headerIndex === null) {
                        continue;
                    }
                    $sheetsMatched++;

                    // Import data rows below header until empty
                    for ($r = $headerIndex + 1; $r < count($sheetRows); $r++) {
                        $row = $sheetRows[$r];
                        $owner = trim((string) (($colMap['owner'] ? ($row[$colMap['owner']] ?? '') : ($row['A'] ?? ''))));
                        $store = trim((string) (($colMap['store'] ? ($row[$colMap['store']] ?? '') : ($row['B'] ?? ''))));
                        $address = trim((string) (($colMap['address'] ? ($row[$colMap['address']] ?? '') : ($row['C'] ?? ''))));
                        $contact = trim((string) (($colMap['contact'] ? ($row[$colMap['contact']] ?? '') : ($row['D'] ?? ''))));
                        $remarks = strtoupper(trim((string) (($colMap['remarks'] ? ($row[$colMap['remarks']] ?? '') : ($row['E'] ?? '')))));

                        // stop on fully blank row
                        if ($owner === '' && $store === '' && $address === '' && $contact === '' && $remarks === '') {
                            continue;
                        }

                        // STORE NAME is optional in some sheets; if blank, use the owner name.
                        if ($store === '') {
                            $store = $owner;
                        }

                        if ($owner === '' || $store === '') {
                            $skipped++;
                            continue;
                        }

                        // Default blank remarks to ACTIVE (common in your template)
                        if ($remarks === '') $remarks = 'ACTIVE';
                        if (!in_array($remarks, ['ACTIVE', 'CLOSED'], true)) {
                            $skipped++;
                            continue;
                        }

                        if ($deliveryRoute === '' || $subRoute === '') {
                            $skipped++;
                            continue;
                        }

                        $payload = [
                            'delivery_route' => $deliveryRoute,
                            'sub_route'      => $subRoute,
                            'customer'       => $owner,
                            'store_name'     => $store,
                            'contact_number' => $contact !== '' ? $contact : null,
                            'address'        => $address !== '' ? $address : null,
                            'credit_limit'   => 0,
                            'remarks'        => $remarks,
                        ];

                        $existing = Customer::where('delivery_route', $deliveryRoute)
                            ->where('sub_route', $subRoute)
                            ->where('customer', $owner)
                            ->where('store_name', $store)
                            ->first();

                        if ($existing) {
                            $existing->update($payload);
                            $updated++;
                            if (count($preview) < $previewLimit) {
                                $preview[] = array_merge($payload, [
                                    '_action' => 'UPDATED',
                                    '_sheet'  => (string) $sheet->getTitle(),
                                    '_row'    => $r + 1, // human-friendly row number
                                ]);
                            }
                        } else {
                            $dupKey = $nameNorm($owner);
                            if ($dupKey !== '' && isset($existingByName[$dupKey])) {
                                // If name exists already but not exact match, flag as possible duplicate and skip.
                                $skipped++;
                                if (count($duplicates) < $dupLimit) {
                                    $duplicates[] = [
                                        'delivery_route' => $deliveryRoute,
                                        'sub_route' => $subRoute,
                                        'customer' => $owner,
                                        'store_name' => $store,
                                        '_sheet' => (string) $sheet->getTitle(),
                                        '_row' => $r + 1,
                                        '_existing' => $existingByName[$dupKey],
                                    ];
                                }
                                continue;
                            }

                            Customer::create($payload);
                            $imported++;
                            if (count($preview) < $previewLimit) {
                                $preview[] = array_merge($payload, [
                                    '_action' => 'ADDED',
                                    '_sheet'  => (string) $sheet->getTitle(),
                                    '_row'    => $r + 1,
                                ]);
                            }
                        }
                    }
                }
            });

            // If nothing imported/updated/skipped, the template likely didn't match (merged headers, different layout, etc).
            if (($imported + $updated + $skipped) === 0) {
                return back()->withErrors([
                    'file' => "No rows were detected for import. Sheets scanned: {$sheetsScanned}, sheets matched: {$sheetsMatched}. Please make sure the sheet has the header row (NAME/OWNERS NAME, ADDRESS, CONTACT #/CONTACT NUMBER, REMARKS). STORE NAME is optional.",
                ]);
            }

            return redirect()
                ->route('admin.customers.import.form')
                ->with('success', "Import complete. Added: {$imported}, Updated: {$updated}, Skipped: {$skipped}.")
                ->with('import_preview', $preview)
                ->with('import_preview_limit', $previewLimit)
                ->with('import_duplicates', $duplicates)
                ->with('import_duplicates_limit', $dupLimit);
        }

        // Otherwise: header-based CSV/XLSX template
        $headerRow = $ext === 'csv' ? $rows[0] : array_values($rows[0]);

        $norm = function ($v) {
            return strtolower(trim(preg_replace('/\s+/', '_', (string) $v)));
        };

        $headers = array_map($norm, $headerRow);

        $idx = array_flip($headers);

        // Accept some common header variants
        $getIndex = function (array $idx, array $aliases) {
            foreach ($aliases as $a) {
                $a = strtolower($a);
                if (isset($idx[$a])) return $idx[$a];
            }
            return null;
        };

        $col = [
            'delivery_route' => $getIndex($idx, ['delivery_route', 'delivery_route_(day)', 'delivery_day', 'day']),
            'sub_route'      => $getIndex($idx, ['sub_route', 'subroute', 'route', 'sub_route_name']),
            'customer'       => $getIndex($idx, ['customer', 'customer_name', 'name']),
            'store_name'     => $getIndex($idx, ['store_name', 'store', 'store_name/shop', 'shop']),
            'contact_number' => $getIndex($idx, ['contact_number', 'contact', 'phone']),
            'address'        => $getIndex($idx, ['address', 'addr']),
            'credit_limit'   => $getIndex($idx, ['credit_limit', 'credit', 'limit']),
            'remarks'        => $getIndex($idx, ['remarks', 'status']),
        ];

        // Required columns must exist in headers
        foreach (['delivery_route', 'sub_route', 'customer', 'store_name', 'remarks'] as $reqCol) {
            if ($col[$reqCol] === null) {
                return back()->withErrors([
                    'file' => "Missing required column header: {$reqCol}",
                ]);
            }
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $preview = [];
        $previewLimit = 200;
        $duplicates = [];
        $dupLimit = 200;

        DB::transaction(function () use (
            $rows,
            $ext,
            $col,
            $existingByName,
            $nameNorm,
            &$imported,
            &$updated,
            &$skipped,
            &$preview,
            $previewLimit,
            &$duplicates,
            $dupLimit
        ) {
            $startIndex = 1; // after header

            for ($i = $startIndex; $i < count($rows); $i++) {
                $row = $ext === 'csv' ? $rows[$i] : array_values($rows[$i]);

                // Helper to get cell safely
                $cell = function ($key) use ($row, $col) {
                    $j = $col[$key];
                    if ($j === null) return null;
                    return $row[$j] ?? null;
                };

                $deliveryRoute = strtoupper(trim((string) ($cell('delivery_route') ?? '')));
                $subRoute      = strtoupper(trim((string) ($cell('sub_route') ?? '')));
                $customer      = trim((string) ($cell('customer') ?? ''));
                $storeName     = trim((string) ($cell('store_name') ?? ''));
                $contactNumber = trim((string) ($cell('contact_number') ?? ''));
                $address       = trim((string) ($cell('address') ?? ''));
                $creditLimitRaw = $cell('credit_limit');
                $remarks       = strtoupper(trim((string) ($cell('remarks') ?? '')));

                // Skip totally blank lines
                $hasAny = $deliveryRoute !== '' || $subRoute !== '' || $customer !== '' || $storeName !== '';
                if (!$hasAny) {
                    continue;
                }

                // Required fields
                if ($deliveryRoute === '' || $subRoute === '' || $customer === '' || $storeName === '' || $remarks === '') {
                    $skipped++;
                    continue;
                }

                if (!in_array($remarks, ['ACTIVE', 'CLOSED'], true)) {
                    $skipped++;
                    continue;
                }

                $creditLimit = is_numeric($creditLimitRaw)
                    ? (float) $creditLimitRaw
                    : (float) preg_replace('/[^0-9.\-]/', '', (string) $creditLimitRaw);

                // Upsert rule: same day + sub route + customer + store name
                $existing = Customer::where('delivery_route', $deliveryRoute)
                    ->where('sub_route', $subRoute)
                    ->where('customer', $customer)
                    ->where('store_name', $storeName)
                    ->first();

                $payload = [
                    'delivery_route' => $deliveryRoute,
                    'sub_route'      => $subRoute,
                    'customer'       => $customer,
                    'store_name'     => $storeName,
                    'contact_number' => $contactNumber !== '' ? $contactNumber : null,
                    'address'        => $address !== '' ? $address : null,
                    'credit_limit'   => max(0, $creditLimit),
                    'remarks'        => $remarks,
                ];

                if ($existing) {
                    $existing->update($payload);
                    $updated++;
                    if (count($preview) < $previewLimit) {
                        $preview[] = array_merge($payload, [
                            '_action' => 'UPDATED',
                            '_row'    => $i + 1,
                        ]);
                    }
                } else {
                    $dupKey = $nameNorm($customer);
                    if ($dupKey !== '' && isset($existingByName[$dupKey])) {
                        $skipped++;
                        if (count($duplicates) < $dupLimit) {
                            $duplicates[] = array_merge($payload, [
                                '_row' => $i + 1,
                                '_existing' => $existingByName[$dupKey],
                            ]);
                        }
                        continue;
                    }

                    Customer::create($payload);
                    $imported++;
                    if (count($preview) < $previewLimit) {
                        $preview[] = array_merge($payload, [
                            '_action' => 'ADDED',
                            '_row'    => $i + 1,
                        ]);
                    }
                }
            }
        });

        return redirect()
            ->route('admin.customers.import.form')
            ->with('success', "Import complete. Added: {$imported}, Updated: {$updated}, Skipped: {$skipped}.")
            ->with('import_preview', $preview)
            ->with('import_preview_limit', $previewLimit)
            ->with('import_duplicates', $duplicates)
            ->with('import_duplicates_limit', $dupLimit);
    }

    /**
     * Store single customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'delivery_route' => 'required|string|max:20',
            'sub_route'      => 'required|string|max:50',
            'customer'       => 'required|string|max:255',
            'store_name'     => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'credit_limit'   => 'nullable|numeric|min:0',
            'remarks'        => 'required|in:ACTIVE,CLOSED',
        ]);

        Customer::create([
            'delivery_route' => $validated['delivery_route'],
            'sub_route'      => $validated['sub_route'],
            'customer'       => $validated['customer'],
            'store_name'     => $validated['store_name'],
            'address'        => $validated['address'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'credit_limit'   => $validated['credit_limit'] ?? 0,
            'remarks'        => $validated['remarks'],
        ]);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'delivery_route' => 'required|string|max:20',
            'sub_route'      => 'required|string|max:50',
            'customer'       => 'required|string|max:255',
            'store_name'     => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'credit_limit'   => 'nullable|numeric|min:0',
            'remarks'        => 'required|in:ACTIVE,CLOSED',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Delete customer
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Bulk delete customers
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:customers,id',
        ]);

        $ids = $validated['ids'];

        $deleted = Customer::whereIn('id', $ids)->delete();

        return back()->with('success', "Deleted {$deleted} customer(s).");
    }
}
