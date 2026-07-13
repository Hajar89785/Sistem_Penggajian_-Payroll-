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
                ->whereIn('status', ['Paid', 'Final'])
                ->selectRaw('COUNT(*) as total_employees')
                ->selectRaw('SUM(basic_salary) as total_basic_salary')
                ->selectRaw('SUM(total_allowances) as total_allowances')
                ->selectRaw('SUM(total_deductions) as total_deductions')
                ->selectRaw('SUM(net_salary) as total_net_salary')
                ->first();
        } else {
            // Aggregate per period
            $summary = Payroll::whereIn('status', ['Paid', 'Final'])
                ->selectRaw('payroll_period_id')
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

    public function exportPdf(Request $request)
    {
        $period_id = $request->get('period_id');
        
        $period = null;
        if ($period_id) {
            $summary = Payroll::where('payroll_period_id', $period_id)
                ->whereIn('status', ['Paid', 'Final'])
                ->selectRaw('COUNT(*) as total_employees')
                ->selectRaw('SUM(basic_salary) as total_basic_salary')
                ->selectRaw('SUM(total_allowances) as total_allowances')
                ->selectRaw('SUM(total_deductions) as total_deductions')
                ->selectRaw('SUM(net_salary) as total_net_salary')
                ->first();
            $period = PayrollPeriod::find($period_id);
            $payrolls = Payroll::with(['employee.department', 'employee.position'])->where('payroll_period_id', $period_id)->whereIn('status', ['Paid', 'Final'])->get();
        } else {
            // Aggregate per period
            $summary = Payroll::whereIn('status', ['Paid', 'Final'])
                ->selectRaw('payroll_period_id')
                ->selectRaw('COUNT(*) as total_employees')
                ->selectRaw('SUM(basic_salary) as total_basic_salary')
                ->selectRaw('SUM(total_allowances) as total_allowances')
                ->selectRaw('SUM(total_deductions) as total_deductions')
                ->selectRaw('SUM(net_salary) as total_net_salary')
                ->groupBy('payroll_period_id')
                ->with('payrollPeriod')
                ->get();
            $payrolls = [];
        }

        $setting = \App\Models\Setting::first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('report.pdf', [
            'summary' => $summary,
            'period' => $period,
            'payrolls' => $payrolls,
            'setting' => $setting
        ]);
        
        $filename = "Laporan_Gaji_" . ($period ? str_replace(' ', '_', $period->name) : 'Semua_Periode') . "_" . date('Y-m-d_H-i-s') . ".pdf";
        return $pdf->download($filename);
    }
}
