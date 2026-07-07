<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['name' => 'Manager', 'basic_salary' => 15000000],
            ['name' => 'Supervisor', 'basic_salary' => 10000000],
            ['name' => 'Staff', 'basic_salary' => 5000000],
            ['name' => 'Operator', 'basic_salary' => 4500000],
            ['name' => 'Intern', 'basic_salary' => 2000000],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(['name' => $pos['name']], $pos);
        }
    }
}
