<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesInput;
use App\Models\Division;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {

        $type = $request->get('type', 'weekly');

        $reportType = $request->get('report_type', 'standard');
        $divisionId = $request->get('division_id');

        $treasuryDivision = \App\Models\Division::where('division_name', 'Treasury GMC Main Office')->first();

        if ($reportType === 'treasury' || ($treasuryDivision && $divisionId == $treasuryDivision->id)) {
            return $this->treasuryReport($request, $treasuryDivision);
        }

        $divisionId = $request->get('division_id');
        $sortField = $request->get('sort_by', 'date');
        $sortOrder = $request->get('order', 'desc');
        $perPage = $request->get('perPage', 50);
        $month = $request->get('month', 'all');
        $week = $request->get('week', 'all');
    
        $query = SalesInput::with(['items', 'division', 'businessLine']);
    
        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }
    
        if ($month !== 'all') {
            $startOfMonth = Carbon::create(now()->year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create(now()->year, $month, 1)->endOfMonth();
    
            if ($week !== 'all') {
                $start = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
            } else {
                $start = $startOfMonth;
                $end = $endOfMonth;
            }
    
            $query->whereBetween('date', [$start, $end]);
        } else {
            $query->whereYear('date', now()->year);
        }
    
        if (in_array($sortField, ['business_line_id', 'date', 'created_at']) && in_array($sortOrder, ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('date', 'desc');
        }
    
        $inputs = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage);
    
        $divisions = Division::all();
    
        $totalCash = $totalIRS = $totalCheque = $totalCredit = $totalOverage = $totalShortage = $totalAR = $totalShortageCollection = 0;
        $overallTotalSales = $overallTotalRemittance = 0;
    
        $itemsCollection = $perPage === 'all' ? $inputs : $inputs->getCollection();
    
        foreach ($itemsCollection as $input) {
            $cash = $irs = $cheque = $credit = $overage = $shortage = $ar = $shortageCollection = 0;
    
            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;
    
                match ($label) {
                    'cash sales' => $cash += $value,
                    'irs sales' => $irs += $value,
                    'cheque sales' => $cheque += $value,
                    'credit sales' => $credit += $value,
                    'cash overage' => $overage += $value,
                    'cash shortage' => $shortage += $value,
                    'ar collections' => $ar += $value,
                    'collection on shortages' => $shortageCollection += $value,
                    default => null
                };
            }
    
            $totalCash += $cash;
            $totalIRS += $irs;
            $totalCheque += $cheque;
            $totalCredit += $credit;
            $totalOverage += $overage;
            $totalShortage += $shortage;
            $totalAR += $ar;
            $totalShortageCollection += $shortageCollection;
    
            $overallTotalSales += ($cash + $irs + $cheque + $credit + $overage - $shortage);
            $overallTotalRemittance += ($cash + $ar + $shortageCollection + $overage - $shortage);
        }
    
        return view('admin.reports.index', compact(
            'inputs', 'divisionId', 'divisions', 'sortField', 'sortOrder', 'perPage',
            'totalCash', 'totalIRS', 'totalCheque', 'totalCredit', 'totalOverage', 'totalShortage',
            'totalAR', 'totalShortageCollection', 'overallTotalSales', 'overallTotalRemittance'
        ));
    }
    
    public function exportCsv(Request $request)
{
    $divisionId = $request->get('division_id');
    $month = $request->get('month', 'all');
    $week = $request->get('week', 'all');

    $query = SalesInput::with(['items', 'division', 'businessLine']);

    if ($divisionId) {
        $query->where('division_id', $divisionId);
    }

    if ($month !== 'all') {
        $startOfMonth = Carbon::create(now()->year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::create(now()->year, $month, 1)->endOfMonth();

        if ($week !== 'all') {
            $start = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek();
            $end = $start->copy()->endOfWeek();
        } else {
            $start = $startOfMonth;
            $end = $endOfMonth;
        }

        $query->whereBetween('date', [$start, $end]);
    } else {
        $query->whereYear('date', now()->year);
    }

    $inputs = $query->orderBy('date', 'desc')->get();

    $csvData = [];
    $csvData[] = ['Particulars', '', '', 'Sales', '', '', '', '', '', '', 'Collections', '', '', '', '', '', 'Validated', '', '', ''];
    $csvData[] = [
        'Division', 'Business Line', 'Date',
        'Cash', 'IRS', 'Cheque', 'Credit', 'Overage', 'Shortage', 'Total Sales',
        'Cash', 'A/R', 'Shortage Collection', 'Overage', 'Shortage', 'Total Remittance',
        'Validated Amount', 'Overage', 'Shortage', 'Remarks'
    ];

    $totalCash = $totalIRS = $totalCheque = $totalCredit = $totalOverage = $totalShortage = $totalAR = $totalShortageCollection = $overallTotalSales = $overallTotalRemittance = 0;

    foreach ($inputs as $input) {
        $cash = $irs = $cheque = $credit = $overage = $shortage = $arCollections = $shortageCollection = 0;

        foreach ($input->items as $item) {
            $label = strtolower($item->field_label);
            $value = is_numeric($item->value) ? floatval($item->value) : 0;

            match ($label) {
                'cash sales' => $cash = $value,
                'irs sales' => $irs = $value,
                'cheque sales' => $cheque = $value,
                'credit sales' => $credit = $value,
                'cash overage' => $overage = $value,
                'cash shortage' => $shortage = $value,
                'ar collections' => $arCollections = $value,
                'collection on shortages' => $shortageCollection = $value,
                default => null
            };
        }

        $totalSales = $cash + $irs + $cheque + $credit + $overage - $shortage;
        $totalRemittance = $cash + $arCollections + $shortageCollection + $overage - $shortage;

        // 🔍 Fetch validated remittance data
        $vr = \App\Models\ValidatedRemittance::where('division_id', $input->division_id)
            ->whereDate('date', \Carbon\Carbon::parse($input->date)->format('Y-m-d'))
            ->first();

        $csvData[] = [
            $input->division->division_name,
            $input->businessLine->name,
            \Carbon\Carbon::parse($input->date)->format('Y-m-d'),

            number_format($cash, 2, '.', ''),
            number_format($irs, 2, '.', ''),
            number_format($cheque, 2, '.', ''),
            number_format($credit, 2, '.', ''),
            number_format($overage, 2, '.', ''),
            number_format($shortage, 2, '.', ''),
            number_format($totalSales, 2, '.', ''),

            number_format($cash, 2, '.', ''),
            number_format($arCollections, 2, '.', ''),
            number_format($shortageCollection, 2, '.', ''),
            number_format($overage, 2, '.', ''),
            number_format($shortage, 2, '.', ''),
            number_format($totalRemittance, 2, '.', ''),

            number_format($vr->validated_amount ?? 0, 2, '.', ''),
            number_format($vr->validated_overage ?? 0, 2, '.', ''),
            number_format($vr->validated_shortage ?? 0, 2, '.', ''),
            $vr->remarks ?? '',
        ];

        $totalCash += $cash;
        $totalIRS += $irs;
        $totalCheque += $cheque;
        $totalCredit += $credit;
        $totalOverage += $overage;
        $totalShortage += $shortage;
        $totalAR += $arCollections;
        $totalShortageCollection += $shortageCollection;
        $overallTotalSales += $totalSales;
        $overallTotalRemittance += $totalRemittance;
    }

    $csvData[] = [];

    $csvData[] = [
        'TOTALS', '', '',
        number_format($totalCash, 2, '.', ''),
        number_format($totalIRS, 2, '.', ''),
        number_format($totalCheque, 2, '.', ''),
        number_format($totalCredit, 2, '.', ''),
        number_format($totalOverage, 2, '.', ''),
        number_format($totalShortage, 2, '.', ''),
        number_format($overallTotalSales, 2, '.', ''),

        number_format($totalCash, 2, '.', ''),
        number_format($totalAR, 2, '.', ''),
        number_format($totalShortageCollection, 2, '.', ''),
        number_format($totalOverage, 2, '.', ''),
        number_format($totalShortage, 2, '.', ''),
        number_format($overallTotalRemittance, 2, '.', ''),

        '', '', '', ''
    ];

    $filename = 'sales_report_' . now()->format('Ymd_His') . '.csv';
    $handle = fopen('php://temp', 'r+');

    foreach ($csvData as $row) {
        fputcsv($handle, $row);
    }

    rewind($handle);
    $content = stream_get_contents($handle);
    fclose($handle);

    return Response::make($content, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename={$filename}",
    ]);
}

    
    public function print(Request $request)
    {
        $type = $request->get('type', 'weekly');
        $divisionId = $request->get('division_id');
        $month = $request->get('month', 'all');
        $week = $request->get('week', 'all');

        $query = SalesInput::with('items');

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        // Filter by date range
        if ($month !== 'all') {
            $startOfMonth = Carbon::create(now()->year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create(now()->year, $month, 1)->endOfMonth();

            if ($week !== 'all') {
                $start = $startOfMonth->copy()->addWeeks($week - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
            } else {
                $start = $startOfMonth;
                $end = $endOfMonth;
            }

            $query->whereBetween('date', [$start, $end]);
        } else {
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();

            $query->whereYear('date', now()->year);
        }

        $dateRange = $start->format('F d, Y') . ' - ' . $end->format('F d, Y');

        $inputs = $query->get();

        // Step 1: Collect all field labels dynamically
        $allLabels = collect();
        foreach ($inputs as $input) {
            foreach ($input->items as $item) {
                $label = trim(ucwords(strtolower($item->field_label)));
                if (in_array($label, ['Credits Sales', 'Credit Sale'])) {
                    $label = 'Credit Sales';
                }
                $allLabels->push($label);
            }
        }
        $uniqueLabels = collect($allLabels)->unique()->values()->all();

        // Step 2: Initialize daily data and totals
        $totals = array_fill_keys(array_merge(['Day', 'Date', 'Total'], $uniqueLabels), 0);
        $dailyData = collect();

        foreach ($inputs as $input) {
            $dateKey = \Carbon\Carbon::parse($input->date)->format('Y-m-d');
            $dayName = \Carbon\Carbon::parse($input->date)->format('l');
            $formattedDate = \Carbon\Carbon::parse($input->date)->format('F j');

            // Initialize row if it doesn't exist
            if (!$dailyData->has($dateKey)) {
                $initial = array_fill_keys(array_merge(['Day', 'Date', 'Total'], $uniqueLabels), 0);
                $initial['Day'] = $dayName;
                $initial['Date'] = $formattedDate;
                $dailyData->put($dateKey, $initial);
            }

            // Safely retrieve and modify the current row
            $current = $dailyData->get($dateKey);

            foreach ($input->items as $item) {
                $label = trim(ucwords(strtolower($item->field_label)));
                if (in_array($label, ['Credits Sales', 'Credit Sale'])) {
                    $label = 'Credit Sales';
                }

                $value = is_numeric($item->value) ? floatval($item->value) : 0;

                $current[$label] += $value;
                $totals[$label] += $value;
            }

            $cash = $current['Cash Sales'] ?? 0;
            $credit = $current['Credit Sales'] ?? 0;
            $current['Total'] = $cash + $credit;
            $totals['Total'] += $cash + $credit;

            // Save updated row back into the collection
            $dailyData->put($dateKey, $current);
        }

        // Sort the data by date (key)
        $dailyData = $dailyData->sortKeys();

        // Get division name
        $divisionName = 'All Divisions';
        if ($divisionId) {
            $division = \App\Models\Division::find($divisionId);
            if ($division) {
                $divisionName = $division->division_name;
            }
        }

        return view('admin.reports.print', [
            'dailyData' => $dailyData,
            'totals' => $totals,
            'labels' => $uniqueLabels,
            'type' => strtoupper($type),
            'divisionName' => $divisionName,
            'dateRange' => $dateRange,
        ]);
    }

    
    public function treasuryReport(Request $request, $treasuryDivision)
    {
        $month = $request->get('month', 'all');
        $week = $request->get('week', 'all');

        $query = SalesInput::with('items')
            ->where('division_id', $treasuryDivision->id);

        if ($month !== 'all') {
            $start = Carbon::create(now()->year, $month, 1)->startOfMonth();
            $end = Carbon::create(now()->year, $month, 1)->endOfMonth();

            if ($week !== 'all') {
                $start = $start->copy()->addWeeks($week - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
            }

            $query->whereBetween('date', [$start, $end]);
        } else {
            $query->whereYear('date', now()->year);
        }

        $inputs = $query->orderBy('date')->get();

        // 💡 Define the field categories (Cash / Check)
        $fields = [
            'Cash Payment',
            'Check Payment',
            'Loans', 'Loans (Check)',
            'ADA', 'ADA (Check)',
            'Loan Availment', 'Loan Availment (Check)',
            'Share Capital', 'Share Capital (Check)',
            'Savings', 'Savings (Check)',
            'Time Deposit', 'Time Deposit (Check)',
            'GSEF', 'GSEF (Check)',
            'Mutual Aid', 'Mutual Aid (Check)',
            'GARI Funds', 'GARI Funds (Check)',
            'Climbs Insurance', 'Climbs Insurance (Check)',
            'Raffle',
            'Other Accounts',
            'Total Collection (Cash)',
            'Total Collection (Check)',
            'Total Collection (Cash + Check)'
        ];

        $dailyData = collect();
        $totals = array_fill_keys($fields, 0);

    foreach ($inputs as $input) {
        $dateKey = Carbon::parse($input->date)->format('Y-m-d');

        if (!$dailyData->has($dateKey)) {
            $initial = array_fill_keys($fields, 0);
            $initial['date'] = Carbon::parse($input->date)->format('F j, Y');
            $dailyData->put($dateKey, $initial);
        }

        foreach ($input->items as $item) {
            $label = trim($item->field_label);
            if (in_array($label, $fields)) {
                $row = $dailyData->get($dateKey);
                $row[$label] += floatval($item->value);
                $totals[$label] += floatval($item->value);
                $dailyData->put($dateKey, $row);
            }
        }

        // ✅ Per-day total calculation happens *after* all items are added
        $row = $dailyData->get($dateKey);

        $cashTotal = collect($row)
            ->filter(fn($v, $k) => !str_contains($k, 'Check') && $k !== 'date')
            ->filter(fn($v) => is_numeric($v))
            ->sum();

        $checkTotal = collect($row)
            ->filter(fn($v, $k) => str_contains($k, 'Check') && $k !== 'date')
            ->filter(fn($v) => is_numeric($v))
            ->sum();

        $row['Total Collection (Cash)'] = $cashTotal;
        $row['Total Collection (Check)'] = $checkTotal;
        $row['Total Collection (Cash + Check)'] = $cashTotal + $checkTotal;
        $totals['Total Collection (Cash)'] += $cashTotal;
        $totals['Total Collection (Check)'] += $checkTotal;
        $totals['Total Collection (Cash + Check)'] += $cashTotal + $checkTotal;

        $dailyData->put($dateKey, $row);
    }

        // Final total collections
        $totalCash = collect($totals)->filter(fn($_, $key) => !str_contains($key, 'Check'))->sum();
        $totalCheck = collect($totals)->filter(fn($_, $key) => str_contains($key, 'Check'))->sum();
        $totalCombined = $totalCash + $totalCheck;

        return view('admin.reports.treasury', [
            'fields' => $fields,
            'dailyData' => $dailyData,
            'totals' => $totals,
            'totalCash' => $totalCash,
            'totalCheck' => $totalCheck,
            'totalCombined' => $totalCombined,

        ]);
    }


    public function exportTreasuryCsv(Request $request)
    {
        $treasuryDivision = \App\Models\Division::where('division_name', 'Treasury GMC Main Office')->first();

        if (!$treasuryDivision) {
            abort(404, 'Treasury Division not found.');
        }

        $month = $request->get('month', 'all');
        $week = $request->get('week', 'all');

        $query = SalesInput::with('items')
            ->where('division_id', $treasuryDivision->id);

        if ($month !== 'all') {
            $start = Carbon::create(now()->year, $month, 1)->startOfMonth();
            $end = Carbon::create(now()->year, $month, 1)->endOfMonth();

            if ($week !== 'all') {
                $start = $start->copy()->addWeeks($week - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
            }

            $query->whereBetween('date', [$start, $end]);
        } else {
            $query->whereYear('date', now()->year);
        }

        $inputs = $query->orderBy('date')->get();

        $fields = [
            'Cash Payment', 'Check Payment', 'Loans', 'Loans (Check)',
            'ADA', 'ADA (Check)', 'Loan Availment', 'Loan Availment (Check)',
            'Share Capital', 'Share Capital (Check)', 'Savings', 'Savings (Check)',
            'Time Deposit', 'Time Deposit (Check)', 'GSEF', 'GSEF (Check)',
            'Mutual Aid', 'Mutual Aid (Check)', 'GARI Funds', 'GARI Funds (Check)',
            'Climbs Insurance', 'Climbs Insurance (Check)', 'Raffle', 'Other Accounts',
            'Total Collection (Cash)', 'Total Collection (Check)', 'Total Collection (Cash + Check)',
        ];

        $csvData[] = ['Date', ...$fields];

        foreach ($inputs as $input) {
            $row = array_fill_keys($fields, 0);
            $date = Carbon::parse($input->date)->format('Y-m-d');

            foreach ($input->items as $item) {
                $label = trim($item->field_label);
                 if ($label === 'ABI Remittance') continue;
                 
                if (array_key_exists($label, $row)) {
                    $row[$label] += floatval($item->value);
                }
            }

            $cashTotal = collect($row)->filter(fn($v, $k) => !str_contains($k, 'Check'))->sum();
            $checkTotal = collect($row)->filter(fn($v, $k) => str_contains($k, 'Check'))->sum();

            $row['Total Collection (Cash)'] = $cashTotal;
            $row['Total Collection (Check)'] = $checkTotal;
            $row['Total Collection (Cash + Check)'] = $cashTotal + $checkTotal;

            $csvData[] = [$date, ...array_map(fn($v) => number_format($v, 2, '.', ''), array_values($row))];
        }

        $filename = 'treasury_collection_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    private function getTreasuryData(Request $request, $treasuryDivision)
    {
        $month = $request->get('month', 'all');
        $week = $request->get('week', 'all');

        $query = SalesInput::with('items')
            ->where('division_id', $treasuryDivision->id);

        if ($month !== 'all') {
            $start = Carbon::create(now()->year, $month, 1)->startOfMonth();
            $end = Carbon::create(now()->year, $month, 1)->endOfMonth();

            if ($week !== 'all') {
                $start = $start->copy()->addWeeks($week - 1)->startOfWeek();
                $end = $start->copy()->endOfWeek();
            }

            $query->whereBetween('date', [$start, $end]);
        } else {
            $query->whereYear('date', now()->year);
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now()->endOfYear();
        }

        $inputs = $query->orderBy('date')->get();

        $fields = [
            'Cash Payment', 'Check Payment', 'Loans', 'Loans (Check)',
            'ADA', 'ADA (Check)', 'Loan Availment', 'Loan Availment (Check)',
            'Share Capital', 'Share Capital (Check)', 'Savings', 'Savings (Check)',
            'Time Deposit', 'Time Deposit (Check)', 'GSEF', 'GSEF (Check)',
            'Mutual Aid', 'Mutual Aid (Check)', 'GARI Funds', 'GARI Funds (Check)',
            'Climbs Insurance', 'Climbs Insurance (Check)', 'Raffle', 'Other Accounts',
            'Total Collection (Cash)', 'Total Collection (Check)', 'Total Collection (Cash + Check)',
        ];

        $dailyData = collect();
        $totals = array_fill_keys($fields, 0);

        foreach ($inputs as $input) {
            $dateKey = Carbon::parse($input->date)->format('Y-m-d');

            if (!$dailyData->has($dateKey)) {
                $initial = array_fill_keys($fields, 0);
                $initial['date'] = Carbon::parse($input->date)->format('F j, Y');
                $dailyData->put($dateKey, $initial);
            }

            foreach ($input->items as $item) {
                $label = trim($item->field_label);
                if ($label === 'ABI Remittance') continue;

                if (in_array($label, $fields)) {
                    $row = $dailyData->get($dateKey);
                    $row[$label] += floatval($item->value);
                    $totals[$label] += floatval($item->value);
                    $dailyData->put($dateKey, $row);
                }
            }

            // Add per-day totals
            $row = $dailyData->get($dateKey);

            $cashTotal = collect($row)->filter(fn($v, $k) => !str_contains($k, 'Check') && $k !== 'date')->sum();
            $checkTotal = collect($row)->filter(fn($v, $k) => str_contains($k, 'Check') && $k !== 'date')->sum();

            $row['Total Collection (Cash)'] = $cashTotal;
            $row['Total Collection (Check)'] = $checkTotal;
            $row['Total Collection (Cash + Check)'] = $cashTotal + $checkTotal;

            $dailyData->put($dateKey, $row);
        }

        $totalCash = collect($totals)->filter(fn($_, $key) => !str_contains($key, 'Check'))->sum();
        $totalCheck = collect($totals)->filter(fn($_, $key) => str_contains($key, 'Check'))->sum();
        $totalCombined = $totalCash + $totalCheck;

        $dateRange = $start->format('F d, Y') . ' - ' . $end->format('F d, Y');

        return [
            'dailyData' => $dailyData,
            'fields' => $fields,
            'totals' => $totals,
            'totalCash' => $totalCash,
            'totalCheck' => $totalCheck,
            'totalCombined' => $totalCombined,
            'dateRange' => $dateRange,
        ];
    }



    public function printTreasury(Request $request)
    {
        $treasuryDivision = Division::where('division_name', 'Treasury GMC Main Office')->firstOrFail();

        // Reuse the same logic from treasuryReport()
        $reportData = $this->getTreasuryData($request, $treasuryDivision); // ⬅️ Make this reusable logic if not yet

        return view('admin.reports.print-treasury', [
            'dailyData' => $reportData['dailyData'],
            'fields' => $reportData['fields'],
            'totals' => $reportData['totals'],
            'totalCash' => $reportData['totalCash'],
            'totalCheck' => $reportData['totalCheck'],
            'totalCombined' => $reportData['totalCombined'],
            'dateRange' => $reportData['dateRange'],
            'divisionName' => $treasuryDivision->division_name,
        ]);
    }

    public function exportCsvConsolidated(Request $request)
{
    $query = SalesInput::with(['items', 'division', 'businessLine'])
        ->orderBy('date', 'asc');

    if ($request->division_id) {
        $query->where('division_id', $request->division_id);
    }

    if ($request->month !== 'all') {
        $start = Carbon::create(now()->year, $request->month, 1)->startOfMonth();
        $end = Carbon::create(now()->year, $request->month, 1)->endOfMonth();

        if ($request->week !== 'all') {
            $start = $start->copy()->addWeeks($request->week - 1)->startOfWeek();
            $end = $start->copy()->endOfWeek();
        }

        $query->whereBetween('date', [$start, $end]);
    }

    $rows = [];
    $rows[] = [
        'Division', 'Business Line', 'Date', 'Cash', 'IRS', 'Cheque',
        'Credit', 'Overage', 'Shortage', 'Total Sales'
    ];

    foreach ($query->get() as $input) {
        $values = [
            'cash sales' => 0, 'irs sales' => 0, 'cheque sales' => 0, 'credit sales' => 0,
            'cash overage' => 0, 'cash shortage' => 0
        ];

        foreach ($input->items as $item) {
            $key = strtolower($item->field_label);
            if (isset($values[$key])) {
                $values[$key] = floatval($item->value);
            }
        }

        $total = $values['cash sales'] + $values['irs sales'] + $values['cheque sales']
                 + $values['credit sales'] + $values['cash overage'] - $values['cash shortage'];

        $rows[] = [
            $input->division->division_name,
            $input->businessLine->name,
            $input->date,
            $values['cash sales'],
            $values['irs sales'],
            $values['cheque sales'],
            $values['credit sales'],
            $values['cash overage'],
            $values['cash shortage'],
            $total
        ];
    }

    $filename = 'consolidated_' . now()->format('Ymd_His') . '.csv';

    $handle = fopen('php://temp', 'r+');
    foreach ($rows as $r) {
        fputcsv($handle, $r);
    }
    rewind($handle);
    $output = stream_get_contents($handle);

    return Response::make($output, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=$filename",
    ]);
}
}
