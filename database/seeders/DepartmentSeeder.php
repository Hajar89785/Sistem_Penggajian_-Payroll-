<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Manajemen kepegawaian'],
            ['name' => 'Finance', 'description' => 'Keuangan dan penggajian'],
            ['name' => 'IT Support', 'description' => 'Teknologi Informasi'],
            ['name' => 'Marketing', 'description' => 'Pemasaran dan promosi'],
            ['name' => 'Operations', 'description' => 'Operasional harian'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept['name']], $dept);
        }
    }
}
