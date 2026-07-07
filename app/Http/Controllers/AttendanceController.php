<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $period_id = $request->get('period_id');
        
        $attendances = Attendance::with(['employee', 'payrollPeriod'])
            ->when($period_id, function($q) use ($period_id) {
                return $q->where('payroll_period_id', $period_id);
            })
            ->latest()
            ->get();
            
        return view('attendance.index', [
            'title' => 'Data Kehadiran',
            'attendances' => $attendances,
            'periods' => PayrollPeriod::orderBy('start_date', 'desc')->get(),
            'selected_period' => $period_id,
        ]);
    }

    public function create(Request $request)
    {
        $period_id = $request->get('period_id');
        $period = null;
        $employees = [];

        if ($period_id) {
            $period = PayrollPeriod::find($period_id);
            // Ambil employee yang belum punya absensi di periode ini
            $employees = Employee::whereDoesntHave('attendances', function($q) use ($period_id) {
                $q->where('payroll_period_id', $period_id);
            })->get();
        }

        return view('attendance.create', [
            'title' => 'Input Kehadiran Massal',
            'periods' => PayrollPeriod::orderBy('start_date', 'desc')->get(),
            'selected_period' => $period_id,
            'period' => $period,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'payroll_period_id' => 'required|exists:payroll_periods,id',
            'attendances' => 'required|array',
            'attendances.*.employee_id' => 'required|exists:employees,id',
            'attendances.*.present_days' => 'required|integer|min:0',
            'attendances.*.sick_days' => 'required|integer|min:0',
            'attendances.*.leave_days' => 'required|integer|min:0',
            'attendances.*.absent_days' => 'required|integer|min:0',
            'attendances.*.overtime_hours' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $period_id = $validate['payroll_period_id'];

            foreach ($validate['attendances'] as $data) {
                // Pastikan tidak ada duplikasi
                $exists = Attendance::where('employee_id', $data['employee_id'])
                    ->where('payroll_period_id', $period_id)
                    ->exists();

                if (!$exists) {
                    Attendance::create([
                        'employee_id' => $data['employee_id'],
                        'payroll_period_id' => $period_id,
                        'present_days' => $data['present_days'],
                        'sick_days' => $data['sick_days'],
                        'leave_days' => $data['leave_days'],
                        'absent_days' => $data['absent_days'],
                        'overtime_hours' => $data['overtime_hours'],
                    ]);
                }
            }

            DB::commit();
            return to_route('attendance.index', ['period_id' => $period_id])->withSuccess('Data kehadiran berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menyimpan kehadiran: ' . $e->getMessage());
        }
    }

    public function destroy(Attendance $attendance)
    {
        DB::beginTransaction();
        try {
            $attendance->delete();
            DB::commit();
            return back()->withSuccess('Data kehadiran berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus kehadiran: ' . $e->getMessage());
        }
    }
}
