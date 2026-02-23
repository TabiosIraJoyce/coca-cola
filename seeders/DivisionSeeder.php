<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $divisions = [
            ['Fuel - Laoag', 'John Doe', 'Jane Dela Cruz', 'Laoag City', '09171234567', '077-600-0001', 8],
            ['Fuel - Batac', 'Ana Santos', 'Marco Reyes', 'Batac City', '09181234567', '077-600-0002', 8],
            ['Bottlers - Laoag', 'Lester Chua', 'Marian Cruz', 'Laoag City', '09191234567', '077-600-0003', 9],
            ['Bottlers - Pasuquin', 'Alex Uy', 'Rona Mateo', 'Pasuquin', '09181231234', '077-600-0004', 9],
            ['Grocery - Centro', 'Mon David', 'Tina Dizon', 'Laoag Centro', '09201231234', '077-600-0005', 10],
            ['Grocery - San Nicolas', 'Jun Lee', 'Nina Cabangon', 'San Nicolas', '09211234567', '077-600-0006', 10],
            ['Hotel - Fort Ilocandia', 'Eric Ramos', 'Jen B.', 'Paoay Road', '09171239876', '077-600-0007', 11],
            ['Hotel - Gledco Inn', 'Teresita Velasco', 'Ella Cruz', 'Laoag Proper', '09192221234', '077-600-0008', 11],
            ['Water - Laoag Plant', 'Carl Lim', 'Daisy V.', 'Balintawak, Laoag', '09173334455', '077-600-0009', 12],
            ['Water - Piddig Plant', 'Louie Y.', 'Faye A.', 'Piddig', '09176667788', '077-600-0010', 12],
            ['Food - Coop Canteen', 'Tina L.', 'Mark R.', 'Main Branch', '09228889900', '077-600-0011', 13],
            ['Food - Catering Services', 'Ryan C.', 'Karen T.', 'Barangay 4, Laoag', '09331112233', '077-600-0012', 13],
            ['Meat - Five Star 1', 'Junie V.', 'Mina A.', 'Brgy. 5, Laoag', '09221234567', '077-600-0013', 14],
            ['Meat - Five Star 2', 'Ken S.', 'Rosa D.', 'Paoay Town', '09224445566', '077-600-0014', 14],
            ['Printing - Main Office', 'Alfred M.', 'Bea G.', 'Balintawak Office', '09178889900', '077-600-0015', 15],
            ['Printing - IT Services', 'Nico S.', 'Carlene R.', 'San Nicolas', '09179998888', '077-600-0016', 15],
            ['Pharmacy - Coop Meds', 'Claire B.', 'Neil T.', 'Laoag Highway', '09334445566', '077-600-0017', 16],
            ['Pharmacy - Batac', 'Ben S.', 'Lois E.', 'Batac Crossing', '09225556677', '077-600-0018', 16],
            ['Loans - Treasury Office', 'Rose D.', 'Tristan C.', 'Main Building', '09170000001', '077-600-0019', 17],
            ['Loans - Mobile Finance', 'Yna A.', 'Greg V.', 'Across Branches', '09171112222', '077-600-0020', 17],
        ];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'division_name'              => $division[0],
                'supervisor_name'            => $division[1],
                'oic_name'                   => $division[2],
                'division_address'           => $division[3],
                'division_contact_number'    => $division[4],
                'division_telephone_number'  => $division[5],
                'business_line_id'           => $division[6],
                'created_at'                 => $now,
                'updated_at'                 => $now,
            ]);
        }
    }
}
