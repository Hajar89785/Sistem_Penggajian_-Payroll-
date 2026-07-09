<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Allowance;
use App\Models\Deduction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();
        $positions = Position::all();
        $allowances = Allowance::all();
        $deductions = Deduction::all();

        // 1. Employee linked to existing user 'Employee' from UserSeeder
        $employeeUser = User::where('role', 'Employee')->first();
        if ($employeeUser) {
            $emp1 = Employee::create([
                'user_id' => $employeeUser->id,
                'department_id' => $departments->first()->id,
                'position_id' => $positions->first()->id,
                'employee_code' => 'EMP-001',
                'full_name' => $employeeUser->name,
                'email' => $employeeUser->email,
                'phone' => '081234567890',
                'join_date' => now()->subMonths(12)->format('Y-m-d'),
                'basic_salary' => $positions->first()->basic_salary,
                'bank_name' => 'Bank BCA',
                'bank_account' => '1234567890',
            ]);

            // Assign some allowances
            $emp1->allowances()->sync([
                $allowances->first()->id => ['amount' => 500000],
                $allowances->last()->id => ['amount' => 200000],
            ]);

            // Assign some deductions
            $emp1->deductions()->sync([
                $deductions->first()->id => ['amount' => 150000],
            ]);
        }

        $faker = \Faker\Factory::create('id_ID');

        // 2. Generate 9 more employees with natural local names
        for ($i = 2; $i <= 10; $i++) {
            $randomPosition = $positions->random();
            $gender = $faker->randomElement(['male', 'female']);
            $fullName = $faker->name($gender);
            
            $emp = Employee::create([
                'user_id' => null, // No user account
                'department_id' => $departments->random()->id,
                'position_id' => $randomPosition->id,
                'employee_code' => 'EMP-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'full_name' => $fullName,
                'email' => strtolower(str_replace(' ', '.', $fullName)) . '@example.com',
                'phone' => '081234567' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'join_date' => now()->subMonths(rand(1, 24))->format('Y-m-d'),
                'basic_salary' => $randomPosition->basic_salary,
                'bank_name' => 'Bank Mandiri',
                'bank_account' => '09876543' . $i,
            ]);

            // Assign random allowances
            $empAllowances = $allowances->random(rand(1, 3));
            $syncDataAllowances = [];
            foreach ($empAllowances as $al) {
                $syncDataAllowances[$al->id] = ['amount' => rand(100, 1000) * 1000];
            }
            $emp->allowances()->sync($syncDataAllowances);

            // Assign random deductions
            $empDeductions = $deductions->random(rand(1, 2));
            $syncDataDeductions = [];
            foreach ($empDeductions as $ded) {
                $syncDataDeductions[$ded->id] = ['amount' => rand(50, 300) * 1000];
            }
            $emp->deductions()->sync($syncDataDeductions);
        }
    }
}
