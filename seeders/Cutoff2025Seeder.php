<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CutoffPeriod;

class Cutoff2025Seeder extends Seeder
{
    public function run(): void
    {
        CutoffPeriod::create([
            'period_number' => 1,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-24',
            'rt_days' => 20,
        ]);

        CutoffPeriod::create([
            'period_number' => 2,
            'start_date' => '2025-01-25',
            'end_date' => '2025-02-21',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 3,
            'start_date' => '2025-02-22',
            'end_date' => '2025-03-28',
            'rt_days' => 30,
        ]);

        CutoffPeriod::create([
            'period_number' => 4,
            'start_date' => '2025-03-29',
            'end_date' => '2025-04-25',
            'rt_days' => 23,
        ]);

        CutoffPeriod::create([
            'period_number' => 5,
            'start_date' => '2025-04-26',
            'end_date' => '2025-05-23',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 6,
            'start_date' => '2025-05-24',
            'end_date' => '2025-06-27',
            'rt_days' => 30,
        ]);

        CutoffPeriod::create([
            'period_number' => 7,
            'start_date' => '2025-06-28',
            'end_date' => '2025-07-25',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 8,
            'start_date' => '2025-07-26',
            'end_date' => '2025-08-22',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 9,
            'start_date' => '2025-08-23',
            'end_date' => '2025-09-26',
            'rt_days' => 30,
        ]);

        CutoffPeriod::create([
            'period_number' => 10,
            'start_date' => '2025-09-27',
            'end_date' => '2025-10-24',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 11,
            'start_date' => '2025-10-25',
            'end_date' => '2025-11-21',
            'rt_days' => 24,
        ]);

        CutoffPeriod::create([
            'period_number' => 12,
            'start_date' => '2025-11-22',
            'end_date' => '2025-12-31',
            'rt_days' => 33,
        ]);
    }
}
