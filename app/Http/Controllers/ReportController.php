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
        
        $payrolls = Payroll::with(['employee', 'payrollPeriod']);
        if ($period_id) {
            $payrolls->where('payroll_period_id', $period_id);
        }
        
        $payrolls = $payrolls->get();
        
        $filename = "Laporan_Gaji_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = ['ID', 'Periode', 'NIK', 'Nama Karyawan', 'Gaji Pokok', 'Total Tunjangan', 'Total Potongan', 'Penerimaan Bersih', 'Tanggal Cetak'];
        
        $callback = function() use($payrolls, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($payrolls as $p) {
                $row['ID'] = $p->id;
                $row['Periode'] = $p->payrollPeriod->name;
                $row['NIK'] = $p->employee->employee_code;
                $row['Nama Karyawan'] = $p->employee->full_name;
                $row['Gaji Pokok'] = $p->basic_salary;
                $row['Total Tunjangan'] = $p->total_allowances;
                $row['Total Potongan'] = $p->total_deductions;
                $row['Penerimaan Bersih'] = $p->net_salary;
                $row['Tanggal Cetak'] = date('Y-m-d');
                
                fputcsv($file, array($row['ID'], $row['Periode'], $row['NIK'], $row['Nama Karyawan'], $row['Gaji Pokok'], $row['Total Tunjangan'], $row['Total Potongan'], $row['Penerimaan Bersih'], $row['Tanggal Cetak']));
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
