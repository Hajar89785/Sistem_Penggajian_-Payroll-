<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'app_name' => 'NiceAdmin Laravel',
            'copyright' => 'Tamus Tahir | 2026',
            'login_title' => 'Halaman Login',
            'keywords' => 'admin, dashboard, laravel, niceadmin, bootstrap',
            'description' => 'Aplikasi dashboard admin menggunakan Laravel dan NiceAdmin template.',
            'company_address' => 'Jl. Kebon Kacang No. 99, Jakarta Pusat',
            'signatory_name' => 'John Doe',
            'signatory_position' => 'HR Director',
        ]);
    }
}
