<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RouteTargetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'route'        => 'required|string',
            'leadman'      => 'required|string',
            'month'        => 'required|integer',
            'year'         => 'required|integer',
            'target_sales' => 'required|numeric|min:0',
            'days_level'   => 'required|integer|min:1',
        ]);

        DB::table('route_targets')->updateOrInsert(
            [
                'route'   => trim($request->route),
                'leadman' => trim($request->leadman),
                'month'   => $request->month,
                'year'    => $request->year,
            ],
            [
                'target_sales' => $request->target_sales,
                'days_level'   => $request->days_level,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        );

        return back()->with('success', 'Target saved successfully.');
    }
}
