<?php

namespace Database\Seeders;

use App\Models\PayrollPeriod;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PayrollPeriodSeeder extends Seeder
{
    public function run(): void
    {
        // Gaji Januari 2026
        PayrollPeriod::create([
            'name' => 'Gaji Januari 2026',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'status' => 'Final',
        ]);

        // Gaji Februari 2026
        PayrollPeriod::create([
            'name' => 'Gaji Februari 2026',
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
            'status' => 'Final',
        ]);

        // Gaji Maret 2026
        PayrollPeriod::create([
            'name' => 'Gaji Maret 2026',
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-31',
            'status' => 'Draft',
        ]);
    }
}
