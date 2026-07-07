<?php

namespace App\Http\Controllers;

use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollPeriodController extends Controller
{
    public function index()
    {
        return view('payroll_period.index', [
            'title' => 'Periode Gaji',
            'periods' => PayrollPeriod::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('payroll_period.create', [
            'title' => 'Tambah Periode Gaji',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:payroll_periods,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        DB::beginTransaction();
        try {
            PayrollPeriod::create($validate);
            DB::commit();
            return to_route('payroll_period.index')->withSuccess('Periode gaji berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('payroll_period.create')->withError('Gagal menambahkan periode gaji: ' . $e->getMessage());
        }
    }

    public function edit(PayrollPeriod $payroll_period)
    {
        return view('payroll_period.edit', [
            'title' => 'Edit Periode Gaji',
            'period' => $payroll_period,
        ]);
    }

    public function update(Request $request, PayrollPeriod $payroll_period)
    {
        $validate = $request->validate([
            'name' => 'required|unique:payroll_periods,name,' . $payroll_period->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Draft,Final',
        ]);

        DB::beginTransaction();
        try {
            $payroll_period->update($validate);
            DB::commit();
            return to_route('payroll_period.index')->withSuccess('Periode gaji berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('payroll_period.edit', $payroll_period)->withError('Gagal mengubah periode gaji: ' . $e->getMessage());
        }
    }

    public function destroy(PayrollPeriod $payroll_period)
    {
        DB::beginTransaction();
        try {
            $payroll_period->delete();
            DB::commit();
            return to_route('payroll_period.index')->withSuccess('Periode gaji berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('payroll_period.index')->withError('Gagal menghapus periode gaji: ' . $e->getMessage());
        }
    }
}
