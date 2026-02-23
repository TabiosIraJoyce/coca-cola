<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesInputSeeder extends Seeder
{
    public function run(): void
    {
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 4, 25);
        $targetBusinessLineIds = [8, 9, 10]; // Fuel, Bottlers, Grocery

        $divisions = DB::table('divisions')
            ->whereIn('business_line_id', $targetBusinessLineIds)
            ->get();

        foreach ($divisions as $division) {
            $templates = DB::table('sales_templates')
                ->where('business_line_id', $division->business_line_id)
                ->orderBy('field_order')
                ->get();

            $date = $startDate->copy();

            while ($date->lte($endDate)) {
                $salesInputId = DB::table('sales_inputs')->insertGetId([
                    'division_id'      => $division->id,
                    'business_line_id' => $division->business_line_id,
                    'date'             => $date->format('Y-m-d'),
                    'data'             => null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);

                foreach ($templates as $template) {
                    DB::table('sales_input_items')->insert([
                        'sales_input_id' => $salesInputId,
                        'field_label'    => $template->field_label,
                        'field_type'     => $template->field_type,
                        'value'          => rand(1000, 50000),
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                $date->addDay();
            }
        }
    }
}
