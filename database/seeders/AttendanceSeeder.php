<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $periods = PayrollPeriod::all();

        foreach ($periods as $period) {
            foreach ($employees as $employee) {
                // Jangan buat absensi jika tanggal bergabung karyawan setelah periode berakhir
                if ($employee->join_date > $period->end_date) {
                    continue;
                }

                $present_days = rand(18, 22);
                $sick_days = rand(0, 2);
                $leave_days = rand(0, 1);
                $absent_days = 22 - ($present_days + $sick_days + $leave_days);
                if ($absent_days < 0) $absent_days = 0;

                Attendance::create([
                    'employee_id' => $employee->id,
                    'payroll_period_id' => $period->id,
                    'present_days' => $present_days,
                    'sick_days' => $sick_days,
                    'leave_days' => $leave_days,
                    'absent_days' => $absent_days,
                    'overtime_hours' => rand(0, 10),
                ]);
            }
        }
    }
}
