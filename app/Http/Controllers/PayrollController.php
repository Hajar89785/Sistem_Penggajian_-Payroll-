<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\PayrollPeriod;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $period_id = $request->get('period_id');
        
        $payrolls = Payroll::with(['employee', 'payrollPeriod'])
            ->when($period_id, function($q) use ($period_id) {
                return $q->where('payroll_period_id', $period_id);
            })
            ->latest()
            ->get();
            
        return view('payroll.index', [
            'title' => 'Proses Penggajian',
            'payrolls' => $payrolls,
            'periods' => PayrollPeriod::orderBy('start_date', 'desc')->get(),
            'selected_period' => $period_id,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:payroll_periods,id'
        ]);

        $period_id = $request->period_id;

        // Ambil semua data absensi di periode ini yang belum digenerate gajinya
        $attendances = Attendance::with(['employee.allowances', 'employee.deductions'])
            ->where('payroll_period_id', $period_id)
            ->whereDoesntHave('employee.payrolls', function($q) use ($period_id) {
                $q->where('payroll_period_id', $period_id);
            })
            ->get();

        if ($attendances->isEmpty()) {
            return back()->withError('Tidak ada data gaji yang perlu di-generate pada periode ini (Mungkin sudah di-generate semua atau data absensi kosong).');
        }

        DB::beginTransaction();
        try {
            $generated_count = 0;
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
                    // Bulatkan
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
                    'payroll_period_id' => $period_id,
                    'basic_salary' => $basic_salary,
                    'total_allowances' => $total_allowance,
                    'total_deductions' => $total_deduction,
                    'net_salary' => $net_salary,
                    'status' => 'Pending'
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
                
                $generated_count++;
            }

            DB::commit();
            return back()->withSuccess("Berhasil memproses penggajian untuk $generated_count karyawan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal memproses penggajian: ' . $e->getMessage());
        }
    }

    public function show(Payroll $payroll)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $payroll);
        $payroll->load(['employee.department', 'employee.position', 'payrollPeriod', 'details']);
        
        return view('payroll.show', [
            'title' => 'Rincian Slip Gaji',
            'payroll' => $payroll
        ]);
    }

    public function edit(Payroll $payroll)
    {
        $payroll->load(['employee']);
        return view('payroll.edit', [
            'title' => 'Edit Gaji Karyawan',
            'payroll' => $payroll
        ]);
    }

    public function update(Request $request, Payroll $payroll)
    {
        $request->validate([
            'basic_salary' => 'required|numeric',
            'total_allowances' => 'required|numeric',
            'total_deductions' => 'required|numeric',
        ]);

        $net_salary = $request->basic_salary + $request->total_allowances - $request->total_deductions;

        $payroll->update([
            'basic_salary' => $request->basic_salary,
            'total_allowances' => $request->total_allowances,
            'total_deductions' => $request->total_deductions,
            'net_salary' => $net_salary
        ]);

        return to_route('payroll.index')->withSuccess('Data gaji berhasil diperbarui');
    }
    
    public function print(Payroll $payroll)
    {
        \Illuminate\Support\Facades\Gate::authorize('view', $payroll);
        $payroll->load(['employee.department', 'employee.position', 'payrollPeriod', 'details']);
        $setting = \App\Models\Setting::first();
        
        return view('payroll.print', [
            'payroll' => $payroll,
            'setting' => $setting
        ]);
    }
    
    public function destroy(Payroll $payroll)
    {
        DB::beginTransaction();
        try {
            $payroll->delete(); // otomatis cascade details jika foreign key diset cascade
            DB::commit();
            return back()->withSuccess('Data penggajian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function pay(Payroll $payroll)
    {
        DB::beginTransaction();
        try {
            $payroll->update(['status' => 'Paid']);
            
            // Cek apakah semua payroll di periode yang sama sudah paid
            $period_id = $payroll->payroll_period_id;
            
            $pendingCount = Payroll::where('payroll_period_id', $period_id)
                ->where('status', '!=', 'Paid')
                ->count();
                
            if ($pendingCount == 0) {
                PayrollPeriod::where('id', $period_id)->update(['status' => 'Final']);
            }
            
            DB::commit();
            return back()->withSuccess('Status gaji berhasil diubah menjadi Paid (Terbayar).');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Gagal melakukan pembayaran: ' . $e->getMessage());
        }
    }
}
