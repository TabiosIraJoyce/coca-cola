<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $fields = [
            ['label' => 'Cash Sales',       'type' => 'number'],
            ['label' => 'IRS Sales',        'type' => 'number'],
            ['label' => 'Cheque Sales',     'type' => 'number'],
            ['label' => 'Credit Sales',     'type' => 'number'],
            ['label' => 'Cash Shortage',    'type' => 'number'],
            ['label' => 'Cash Overage',     'type' => 'number'],
            ['label' => 'Discounts',        'type' => 'number'],
            ['label' => 'AR Collections',   'type' => 'number'],
        ];

        // Get all business lines (assuming they have IDs 8-17 as seeded)
        $businessLines = DB::table('business_lines')->whereBetween('id', [8, 17])->get();

        foreach ($businessLines as $businessLine) {
            foreach ($fields as $index => $field) {
                DB::table('sales_templates')->insert([
                    'business_line_id' => $businessLine->id,
                    'field_label'      => $field['label'],
                    'field_type'       => $field['type'],
                    'is_required'      => true,
                    'field_order'      => $index + 1,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]);
            }
        }
    }
}
