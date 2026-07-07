<?php

namespace Database\Seeders;

use App\Models\Deduction;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    public function run(): void
    {
        $deductions = [
            ['name' => 'BPJS Kesehatan', 'type' => 'Fixed'],
            ['name' => 'BPJS Ketenagakerjaan', 'type' => 'Fixed'],
            ['name' => 'Pajak Penghasilan (PPh 21)', 'type' => 'Variable'],
            ['name' => 'Potongan Keterlambatan', 'type' => 'Variable'],
            ['name' => 'Pinjaman Karyawan', 'type' => 'Fixed'],
        ];

        foreach ($deductions as $ded) {
            Deduction::firstOrCreate(['name' => $ded['name']], $ded);
        }
    }
}
