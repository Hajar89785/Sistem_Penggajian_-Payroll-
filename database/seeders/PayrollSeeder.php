<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $attendances = Attendance::with(['employee.allowances', 'employee.deductions'])->get();

        foreach ($attendances as $attendance) {
            $employee = $attendance->employee;
            
            $basic_salary = $employee->basic_salary;
            $total_allowance = 0;
            $total_deduction = 0;
            
            $details = [];

            // 1. Hitung Allowances
            foreach ($employee->allowances as $allowance) {
                $amount = $allowance->pivot->amount;
                $total_allowance += $amount;
                $details[] = [
                    'component_name' => $allowance->name,
                    'component_type' => 'Allowance',
                    'amount' => $amount
                ];
            }

            // 2. Hitung Lembur (Asumsi 20.000 / jam)
            if ($attendance->overtime_hours > 0) {
                $overtime_amount = $attendance->overtime_hours * 20000;
                $total_allowance += $overtime_amount;
                $details[] = [
                    'component_name' => 'Lembur (' . $attendance->overtime_hours . ' jam)',
                    'component_type' => 'Allowance',
                    'amount' => $overtime_amount
                ];
            }

            // 3. Hitung Deductions
            foreach ($employee->deductions as $deduction) {
                $amount = $deduction->pivot->amount;
                $total_deduction += $amount;
                $details[] = [
                    'component_name' => $deduction->name,
                    'component_type' => 'Deduction',
                    'amount' => $amount
                ];
            }

            // 4. Hitung Denda Alpa (Gaji Pokok / 22 * Hari Alpa)
            if ($attendance->absent_days > 0) {
                $absent_deduction = ($basic_salary / 22) * $attendance->absent_days;
                $absent_deduction = round($absent_deduction);
                $total_deduction += $absent_deduction;
                $details[] = [
                    'component_name' => 'Potongan Alpa (' . $attendance->absent_days . ' hari)',
                    'component_type' => 'Deduction',
                    'amount' => $absent_deduction
                ];
            }

            $net_salary = $basic_salary + $total_allowance - $total_deduction;

            // Simpan transaksi utama
            $payroll = Payroll::create([
                'employee_id' => $employee->id,
                'payroll_period_id' => $attendance->payroll_period_id,
                'basic_salary' => $basic_salary,
                'total_allowances' => $total_allowance,
                'total_deductions' => $total_deduction,
                'net_salary' => $net_salary,
                'status' => 'Pending' // atau 'Paid' untuk dummy
            ]);

            // Simpan rincian
            foreach ($details as $d) {
                PayrollDetail::create([
                    'payroll_id' => $payroll->id,
                    'component_name' => $d['component_name'],
                    'component_type' => $d['component_type'],
                    'amount' => $d['amount']
                ]);
            }
        }
    }
}
