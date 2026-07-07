<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Allowance;
use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index', [
            'title' => 'Data Karyawan',
            'employees' => Employee::with(['department', 'position'])->latest()->get(),
        ]);
    }

    public function create()
    {
        return view('employee.create', [
            'title' => 'Tambah Karyawan',
            'users' => User::where('role', 'Employee')->doesntHave('employee')->get(), // Only users who don't have employee profile
            'departments' => Department::all(),
            'positions' => Position::all(),
            'allowances' => Allowance::all(),
            'deductions' => Deduction::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code',
            'full_name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'join_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id',
            'basic_salary' => 'nullable|numeric',
            'bank_name' => 'nullable',
            'bank_account' => 'nullable',
            
            // Arrays for sync
            'allowances' => 'array',
            'allowance_amounts' => 'array',
            'deductions' => 'array',
            'deduction_amounts' => 'array',
        ]);

        DB::beginTransaction();
        try {
            if (empty($validate['basic_salary'])) {
                $position = Position::find($validate['position_id']);
                $validate['basic_salary'] = $position->basic_salary;
            }

            $employee = Employee::create($validate);

            // Sync Allowances
            $syncAllowances = [];
            if ($request->has('allowances')) {
                foreach ($request->allowances as $allowance_id) {
                    $amount = $request->allowance_amounts[$allowance_id] ?? 0;
                    if ($amount > 0) {
                        $syncAllowances[$allowance_id] = ['amount' => $amount];
                    }
                }
            }
            $employee->allowances()->sync($syncAllowances);

            // Sync Deductions
            $syncDeductions = [];
            if ($request->has('deductions')) {
                foreach ($request->deductions as $deduction_id) {
                    $amount = $request->deduction_amounts[$deduction_id] ?? 0;
                    if ($amount > 0) {
                        $syncDeductions[$deduction_id] = ['amount' => $amount];
                    }
                }
            }
            $employee->deductions()->sync($syncDeductions);

            DB::commit();
            return to_route('employee.index')->withSuccess('Karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withError('Gagal menambahkan karyawan: ' . $e->getMessage());
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'position', 'allowances', 'deductions']);
        return view('employee.show', [
            'title' => 'Detail Karyawan',
            'employee' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        $employee->load(['allowances', 'deductions']);
        
        return view('employee.edit', [
            'title' => 'Edit Karyawan',
            'employee' => $employee,
            // Users without employee profile OR this employee's user
            'users' => User::where('role', 'Employee')
                ->where(function($q) use ($employee) {
                    $q->doesntHave('employee');
                    if ($employee->user_id) {
                        $q->orWhere('id', $employee->user_id);
                    }
                })->get(),
            'departments' => Department::all(),
            'positions' => Position::all(),
            'allowances' => Allowance::all(),
            'deductions' => Deduction::all(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validate = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'full_name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'join_date' => 'required|date',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'user_id' => 'nullable|exists:users,id|unique:employees,user_id,' . $employee->id,
            'basic_salary' => 'nullable|numeric',
            'bank_name' => 'nullable',
            'bank_account' => 'nullable',
            
            // Arrays for sync
            'allowances' => 'array',
            'allowance_amounts' => 'array',
            'deductions' => 'array',
            'deduction_amounts' => 'array',
        ]);

        DB::beginTransaction();
        try {
            if (empty($validate['basic_salary'])) {
                $position = Position::find($validate['position_id']);
                $validate['basic_salary'] = $position->basic_salary;
            }

            $employee->update($validate);

            // Sync Allowances
            $syncAllowances = [];
            if ($request->has('allowances')) {
                foreach ($request->allowances as $allowance_id) {
                    $amount = $request->allowance_amounts[$allowance_id] ?? 0;
                    if ($amount > 0) {
                        $syncAllowances[$allowance_id] = ['amount' => $amount];
                    }
                }
            }
            $employee->allowances()->sync($syncAllowances);

            // Sync Deductions
            $syncDeductions = [];
            if ($request->has('deductions')) {
                foreach ($request->deductions as $deduction_id) {
                    $amount = $request->deduction_amounts[$deduction_id] ?? 0;
                    if ($amount > 0) {
                        $syncDeductions[$deduction_id] = ['amount' => $amount];
                    }
                }
            }
            $employee->deductions()->sync($syncDeductions);

            DB::commit();
            return to_route('employee.index')->withSuccess('Karyawan berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withError('Gagal mengubah karyawan: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {
        DB::beginTransaction();
        try {
            $employee->delete();
            DB::commit();
            return to_route('employee.index')->withSuccess('Karyawan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('employee.index')->withError('Gagal menghapus karyawan: ' . $e->getMessage());
        }
    }
}
