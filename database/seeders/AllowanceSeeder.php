<?php

namespace Database\Seeders;

use App\Models\Allowance;
use Illuminate\Database\Seeder;

class AllowanceSeeder extends Seeder
{
    public function run(): void
    {
        $allowances = [
            ['name' => 'Tunjangan Transportasi', 'type' => 'Fixed'],
            ['name' => 'Tunjangan Makan', 'type' => 'Fixed'],
            ['name' => 'Tunjangan Kinerja', 'type' => 'Variable'],
            ['name' => 'Bonus Proyek', 'type' => 'Variable'],
            ['name' => 'Tunjangan Hari Raya (THR)', 'type' => 'Fixed'],
        ];

        foreach ($allowances as $all) {
            Allowance::firstOrCreate(['name' => $all['name']], $all);
        }
    }
}
