<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period_id = $request->get('period_id');
        $periods = PayrollPeriod::orderBy('start_date', 'desc')->get();
        
        $summary = null;
        if ($period_id) {
            $summary = Payroll::where('payroll_period_id', $period_id)
                ->selectRaw('COUNT(*) as total_employees')
                ->selectRaw('SUM(basic_salary) as total_basic_salary')
                ->selectRaw('SUM(total_allowances) as total_allowances')
                ->selectRaw('SUM(total_deductions) as total_deductions')
                ->selectRaw('SUM(net_salary) as total_net_salary')
                ->first();
        } else {
            // Aggregate per period
            $summary = Payroll::selectRaw('payroll_period_id')
                ->selectRaw('COUNT(*) as total_employees')
                ->selectRaw('SUM(basic_salary) as total_basic_salary')
                ->selectRaw('SUM(total_allowances) as total_allowances')
                ->selectRaw('SUM(total_deductions) as total_deductions')
                ->selectRaw('SUM(net_salary) as total_net_salary')
                ->groupBy('payroll_period_id')
                ->with('payrollPeriod')
                ->get();
        }
        
        return view('report.index', [
            'title' => 'Laporan Rekapitulasi Gaji',
            'periods' => $periods,
            'selected_period' => $period_id,
            'summary' => $summary
        ]);
    }

    public function export(Request $request)
    {
        $period_id = $request->get('period_id');
        $filename = "Laporan_Gaji_" . date('Y-m-d_H-i-s') . ".xlsx";
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PayrollExport($period_id), $filename);
    }
}
