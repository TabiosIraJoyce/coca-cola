<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusinessLineSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('business_lines')->insert([
            [
                'name' => 'Fuel Line',
                'description' => 'Includes gas stations and LPG distribution',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Bottlers',
                'description' => 'Soft drinks and beverage dealers like Coke and Asia Brewery',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Grocery',
                'description' => 'Retail grocery and convenience store operations',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Hotel & Lodging',
                'description' => 'Accommodation, hotel, and lodging services',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Water Refilling',
                'description' => 'Purified water refilling stations',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Food Services',
                'description' => 'Catering, cafeteria, and food service lines',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Poultry & Meat',
                'description' => 'Chicken outlets and meat distribution',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'IT & Printing',
                'description' => 'Ticket printing, IT services, and document solutions',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pharmacy',
                'description' => 'Pharmaceutical and medical supplies distribution',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Loans & Treasury',
                'description' => 'Financial services including loans and remittance',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
