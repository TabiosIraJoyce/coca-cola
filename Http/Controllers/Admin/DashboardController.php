<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SalesInput;
use App\Models\Division;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'weekly');
        $divisionId = $request->get('division_id');

        $query = SalesInput::with(['items', 'division', 'businessLine']);

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        switch ($filter) {
            case 'daily':
                $start = now()->startOfDay(); $end = now()->endOfDay(); break;
            case 'weekly':
                $start = now()->startOfWeek(); $end = now()->endOfWeek(); break;
            case 'monthly':
                $selectedMonth = $request->get('month');
                $baseDate = now();
            
                if ($selectedMonth && is_numeric($selectedMonth)) {
                    $baseDate = $baseDate->copy()->month($selectedMonth)->startOfMonth();
                }
            
                $start = $baseDate->copy()->startOfMonth();
                $end = $baseDate->copy()->endOfMonth();
                    break;
            case 'yearly':
                $start = now()->startOfYear(); $end = now()->endOfYear(); break;
            default:
                $start = now()->startOfWeek(); $end = now()->endOfWeek();
        }

        $currentInputs = (clone $query)->whereBetween('date', [$start, $end])->get();

        $cashSales = $irsSales = $chequeSales = $creditSales = $cashOverage = $cashShortage = $arCollections = 0;
        $cashShortagePerDivision = [];

        foreach ($currentInputs as $input) {
            $divisionName = $input->division->division_name ?? 'Unknown';

            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;

                if ($label === 'cash sales') $cashSales += $value;
                if ($label === 'irs sales') $irsSales += $value;
                if ($label === 'cheque sales') $chequeSales += $value;
                if ($label === 'credit sales') $creditSales += $value;
                if ($label === 'cash overage') $cashOverage += $value;
                if ($label === 'ar collections') $arCollections += $value;
                if ($label === 'cash shortage') {
                    $cashShortage += $value;
                    $cashShortagePerDivision[$divisionName] = ($cashShortagePerDivision[$divisionName] ?? 0) + $value;
                }
            }
        }

        $cashShortagePerDivision = array_filter($cashShortagePerDivision, fn($val) => $val > 0);
        arsort($cashShortagePerDivision);

        $totalSales = $cashSales + $irsSales + $chequeSales + $creditSales + $cashOverage - $cashShortage;
        $totalRemittance = $cashSales + $cashOverage + $arCollections - $cashShortage;

        $periodCount = match($filter) {
            'daily' => 1,
            'weekly' => 7,
            'monthly' => $start->diffInDays($end) + 1,
            'yearly' => 12,
            default => 7
        };

        $averageSales = $periodCount > 0 ? $totalSales / $periodCount : 0;

        $previousInputs = SalesInput::with('items')
            ->when($divisionId, fn($q) => $q->where('division_id', $divisionId))
            ->whereBetween('date', [
                match($filter) {
                    'daily' => now()->subDay()->startOfDay(),
                    'weekly' => now()->subWeek()->startOfWeek(),
                    'monthly' => now()->subMonth()->startOfMonth(),
                    'yearly' => now()->subYear()->startOfYear(),
                    default => now()->subWeek()->startOfWeek()
                },
                match($filter) {
                    'daily' => now()->subDay()->endOfDay(),
                    'weekly' => now()->subWeek()->endOfWeek(),
                    'monthly' => now()->subMonth()->endOfMonth(),
                    'yearly' => now()->subYear()->endOfYear(),
                    default => now()->subWeek()->endOfWeek()
                }
            ])
            ->get();

        $previousTotalSales = 0;
        foreach ($previousInputs as $input) {
            $tempCash = $tempIRS = $tempCheque = $tempCredit = $tempOverage = $tempShortage = 0;
            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;

                if ($label === 'cash sales') $tempCash += $value;
                if ($label === 'irs sales') $tempIRS += $value;
                if ($label === 'cheque sales') $tempCheque += $value;
                if ($label === 'credit sales') $tempCredit += $value;
                if ($label === 'cash overage') $tempOverage += $value;
                if ($label === 'cash shortage') $tempShortage += $value;
            }
            $previousTotalSales += $tempCash + $tempIRS + $tempCheque + $tempOverage - $tempShortage;
        }

        $salesIncrease = ($previousTotalSales > 0) ? (($totalSales - $previousTotalSales) / $previousTotalSales) * 100 : 0;

        $divisionSales = [];
        foreach ($currentInputs as $input) {
            $divisionName = $input->division->division_name ?? 'Unknown';
            if (in_array($divisionName, ['Treasury GMC Main Office', 'Credit GMC Main Office'])) continue;  // this exclude treasury
            $divisionSales[$divisionName] = ($divisionSales[$divisionName] ?? 0);
          
            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;
        
                if (in_array($label, ['cash sales', 'irs sales', 'cheque sales', 'credit sales', 'cash overage', 'cash shortage'])) {
                    if ($label === 'cash shortage') {
                        $divisionSales[$divisionName] -= $value;
                    } else {
                        $divisionSales[$divisionName] += $value;
                    }
                }
            }
        }

        // Sort full divisionSales DESC for use elsewhere (e.g., charts)
        arsort($divisionSales);

        // Filter for lowest division calculation (excluding Loans - Treasury Office), ASC
        $filteredDivisionSales = collect($divisionSales)
            ->filter(fn($value, $key) => $key !== 'Treasury GMC Main Office','Credit GMC Main Office')
            ->sort(); // ASCENDING, so lowest first

        $lowestDivision = $filteredDivisionSales->keys()->first();
        

        $businessLineSales = $currentInputs
        ->filter(fn($input) => optional($input->division)->division_name !== 'Treasury GMC Main Office','Credit GMC Main Office') // ✅ filter before groupBy
        ->groupBy('business_line_id')
        ->mapWithKeys(function ($group, $lineId) {
            $name = optional($group->first()->businessLine)->name ?? 'Unknown';
            $total = 0;
    
            foreach ($group as $input) {
                foreach ($input->items as $item) {
                    $label = strtolower(trim($item->field_label));
                    $value = is_numeric($item->value) ? floatval($item->value) : 0;
    
                    if (in_array($label, ['cash sales', 'irs sales', 'cheque sales', 'credit sales', 'cash overage', 'cash shortage'])) {
                        if ($label === 'cash shortage') {
                            $total -= $value;
                        } else {
                            $total += $value;
                        }
                    }
                }
            }
    
            return [$name => $total];
        })->sortDesc();
        

        $topBusinessLine = $businessLineSales->keys()->first();
        $topBusinessLinePercent = $totalSales > 0 ? ($businessLineSales[$topBusinessLine] / $totalSales) * 100 : 0;

        $manilaNow = now('Asia/Manila');
        $yesterday = $manilaNow->copy()->subDay()->startOfDay();
        $targetDate = $yesterday->toDateString(); // this is the date divisions are submitting for
        
        $complied = [];
        $late = [];
        $notSubmitted = [];
        
        $excludedDivisions = ['Treasury GMC Main Office', 'Shrimp Farm', '	Five Star Chicken Nalbo','Credit GMC Main Office'];
        $allDivisions = Division::whereNotIn('division_name', $excludedDivisions)->get();
        
        // Determine expected submission window
        $submissionStart = $manilaNow->copy()->subDay()->setTime(16, 0); // 4PM day before
        $submissionDeadline = $manilaNow->copy()->setTime(11, 0);        // 11AM today
        
        // Adjust for Saturday/Sunday
        $targetCarbon = \Carbon\Carbon::parse($targetDate);
        $isWeekend = $targetCarbon->isSaturday() || $targetCarbon->isSunday();
        
        if ($isWeekend) {
            // Allow submission until next Monday 11AM
            $submissionStart = $targetCarbon->copy()->startOfDay()->setTime(0, 0);
            $submissionDeadline = $targetCarbon->copy()->next('Monday')->setTime(11, 0);
        }
        
        // Fetch all submissions that claim they are for $targetDate
        $submittedInputs = SalesInput::whereDate('date', $targetDate)->get();
        
        foreach ($allDivisions as $division) {
            $submission = $submittedInputs->firstWhere('division_id', $division->id);
        
            if ($submission) {
                $submissionTime = $submission->created_at->copy()->timezone('Asia/Manila');
        
                if ($submissionTime->between($submissionStart, $submissionDeadline)) {
                    $complied[] = ['division' => $division, 'time' => $submissionTime];
                } else {
                    $late[] = ['division' => $division, 'time' => $submissionTime];
                }
            } else {
                $notSubmitted[] = $division;
            }
        }

        // 📊 Collection on Shortages per Division
        $shortageCollectionPerDivision = [];

        foreach ($currentInputs as $input) {
            $divisionName = $input->division->division_name ?? 'Unknown';

            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;

                if ($label === 'collection on shortages') {
                    $shortageCollectionPerDivision[$divisionName] = ($shortageCollectionPerDivision[$divisionName] ?? 0) + $value;
                }
            }
        }

        $shortageCollectionPerDivision = array_filter($shortageCollectionPerDivision, fn($val) => $val > 0);
        arsort($shortageCollectionPerDivision);

        return view('admin.dashboard.index', [
            'cashSales' => $cashSales,
            'irsSales' => $irsSales,
            'chequeSales' => $chequeSales,
            'creditSales' => $creditSales,
            'cashOverage' => $cashOverage,
            'cashShortage' => $cashShortage,
            'totalSales' => round($totalSales, 2),
            'totalRemittance' => round($totalRemittance, 2),
            'filter' => $filter,
            'divisionSales' => $divisionSales,
            'businessLineSales' => $businessLineSales,
            'salesIncrease' => round($salesIncrease, 2),
            'lowestDivision' => $lowestDivision,
            'topBusinessLine' => $topBusinessLine,
            'topBusinessLinePercent' => round($topBusinessLinePercent, 2),
            'totalCashShortage' => round($cashShortage, 2),
            'cashShortagePerDivision' => $cashShortagePerDivision,
            'complied' => $complied,
            'late' => $late,
            'notSubmitted' => $notSubmitted,
            'divisions' => $allDivisions,
            'divisionId' => $divisionId,
            'averageSales' => round($averageSales, 2),
            'arCollections' => $arCollections,
            'shortageCollectionPerDivision' => $shortageCollectionPerDivision,
        ]);
    }

    public function treasury(Request $request)
    {
        $filter = $request->get('filter', 'weekly');
        $selectedMonth = $request->get('month');
    
        // 🗓 Set date range based on filter
        switch ($filter) {
            case 'daily':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'weekly':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                break;
            case 'monthly':
                $baseDate = now();
                if ($selectedMonth && is_numeric($selectedMonth)) {
                    $baseDate = $baseDate->copy()->month($selectedMonth)->startOfMonth();
                }
                $start = $baseDate->copy()->startOfMonth();
                $end = $baseDate->copy()->endOfMonth();
                break;
            case 'yearly':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                break;
            default:
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
        }
    
        // Fetch filtered sales inputs with items
        $currentInputs = SalesInput::with('items')->whereBetween('date', [$start, $end])->get();
    
        // Initialize all fields
        $timeDeposit = $shareCapital = $gsef = $gariFunds = $mutualAid = $climbsInsurance = $others = $arCollections = $advances = $cashPayment = $checkPayment = 0;
    
        // Loop through all inputs and their items
        foreach ($currentInputs as $input) {
            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;
    
                if ($label === 'time deposit') $timeDeposit += $value;
                if ($label === 'share capital') $shareCapital += $value;
                if ($label === 'gsef') $gsef += $value;
                if ($label === 'gari funds') $gariFunds += $value;
                if ($label === 'mutual aid') $mutualAid += $value;
                if ($label === 'climbs insurance') $climbsInsurance += $value;
                if ($label === 'others') $others += $value;
                if ($label === 'ar collection') $arCollections += $value;
                if ($label === 'advances') $advances += $value;
                if ($label === 'cash payment') $cashPayment += $value;
                if ($label === 'check payment') $checkPayment += $value;
            }
        }
    
        $totalCollections = $cashPayment + $checkPayment;
    
        return view('admin.dashboard.treasury', [
            'timeDeposit' => $timeDeposit,
            'shareCapital' => $shareCapital,
            'gsef' => $gsef,
            'gariFunds' => $gariFunds,
            'mutualAid' => $mutualAid,
            'climbsInsurance' => $climbsInsurance,
            'others' => $others,
            'arCollections' => $arCollections,
            'advances' => $advances,
            'cashPayment' => $cashPayment,
            'checkPayment' => $checkPayment,
            'totalCollections' => $totalCollections,
            'filter' => $filter,
            'month' => $selectedMonth,
        ]);
    }
    
    

// 📈 Sales Progression for Chart
    public function getSalesProgression(Request $request)
    {
        $filter = $request->get('filter', 'weekly');
        $divisionId = $request->get('division_id');
        $query = SalesInput::with('items');

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        if ($filter === 'daily') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $groupFormat = 'H:i';
        } elseif ($filter === 'weekly') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
            $groupFormat = 'Y-m-d';
        } elseif ($filter === 'monthly') {
            $selectedMonth = $request->get('month');
            $baseDate = now();
        
            if ($selectedMonth && is_numeric($selectedMonth)) {
                $baseDate = $baseDate->copy()->month($selectedMonth)->startOfMonth();
            }
        
            $start = $baseDate->copy()->startOfMonth();
            $end = $baseDate->copy()->endOfMonth();
            $groupFormat = 'Y-m-d';
        } else {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
            $groupFormat = 'Y-m';
        }
        

        $query->whereBetween('date', [$start, $end]);
        $salesInputs = $query->get();

        $progression = [];

        foreach ($salesInputs as $input) {
            if (!$input->date) {
                continue;
            }

            $key = \Carbon\Carbon::parse($input->date)->format($groupFormat);

            // ✅ FIX: Dapat may $credit initialization dito
            $cash = $irs = $cheque = $credit = $cashOverage = $cashShortage = 0;

            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value ?? null) ? floatval($item->value) : 0;

                if ($label === 'cash sales') $cash += $value;
                if ($label === 'irs sales') $irs += $value;
                if ($label === 'cheque sales') $cheque += $value;
                if ($label === 'credit sales') $credit += $value;
                if ($label === 'cash overage') $cashOverage += $value;
                if ($label === 'cash shortage') $cashShortage += $value;
            }

            $totalSales = $cash + $irs + $cheque + $credit + $cashOverage - $cashShortage;

            $progression[$key] = ($progression[$key] ?? 0) + $totalSales;
        }

        ksort($progression);

        return response()->json([
            'labels' => array_keys($progression),
            'data' => array_values($progression),
        ]);
    }


    // 📉 Cash Shortage Chart
    public function getCashShortage(Request $request)
    {
        $filter = $request->get('filter', 'weekly');
        $query = SalesInput::with('items');

        if ($filter === 'daily') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $groupFormat = 'H:i';
        } elseif ($filter === 'weekly' || $filter === 'monthly') {
            $start = $filter === 'weekly' ? now()->startOfWeek() : now()->startOfMonth();
            $end = $filter === 'weekly' ? now()->endOfWeek() : now()->endOfMonth();
            $groupFormat = 'Y-m-d';
        } else {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
            $groupFormat = 'Y-m';
        }

        $query->whereBetween('date', [$start, $end]);
        $salesInputs = $query->get();

        $shortageData = [];

        foreach ($salesInputs as $input) {
            $key = \Carbon\Carbon::parse($input->date)->format($groupFormat);

            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value) ? floatval($item->value) : 0;

                if ($label === 'cash shortage') {
                    $shortageData[$key] = ($shortageData[$key] ?? 0) + $value;
                }
            }
        }

        ksort($shortageData);

        return response()->json([
            'labels' => array_keys($shortageData),
            'data' => array_values($shortageData),
        ]);
    }

    public function getTreasuryProgression(Request $request)
    {
        $filter = $request->get('filter', 'weekly');
        $selectedMonth = $request->get('month');
        $query = SalesInput::with('items');

        // ⏱ Define date range
        if ($filter === 'daily') {
            $start = now()->startOfDay();
            $end = now()->endOfDay();
            $groupFormat = 'H:i';
        } elseif ($filter === 'weekly') {
            $start = now()->startOfWeek();
            $end = now()->endOfWeek();
            $groupFormat = 'Y-m-d';
        } elseif ($filter === 'monthly') {
            $baseDate = now();
            if ($selectedMonth && is_numeric($selectedMonth)) {
                $baseDate = $baseDate->copy()->month($selectedMonth)->startOfMonth();
            }
            $start = $baseDate->copy()->startOfMonth();
            $end = $baseDate->copy()->endOfMonth();
            $groupFormat = 'Y-m-d';
        } else {
            $start = now()->startOfYear();
            $end = now()->endOfYear();
            $groupFormat = 'Y-m';
        }

        $query->whereBetween('date', [$start, $end]);
        $salesInputs = $query->get();

        $progression = [];

        foreach ($salesInputs as $input) {
            if (!$input->date) continue;

            $key = \Carbon\Carbon::parse($input->date)->format($groupFormat);

            $cashPayment = $checkPayment = 0;

            foreach ($input->items as $item) {
                $label = strtolower(trim($item->field_label));
                $value = is_numeric($item->value ?? null) ? floatval($item->value) : 0;

                if ($label === 'cash payment') $cashPayment += $value;
                if ($label === 'check payment') $checkPayment += $value;
            }

            $totalCollections = $cashPayment + $checkPayment;
            $progression[$key] = ($progression[$key] ?? 0) + $totalCollections;
        }

        ksort($progression);

        return response()->json([
            'labels' => array_keys($progression),
            'data' => array_values($progression),
        ]);
    }


}
