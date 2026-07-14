<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPayrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'Employee') {
            return redirect('/dashboard')->withError('Halaman ini khusus untuk Karyawan.');
        }

        if (!$user->employee) {
            return redirect('/dashboard')->withError('Profil karyawan Anda belum terhubung dengan akun ini. Hubungi HRD.');
        }

        $payrolls = Payroll::with(['payrollPeriod'])
            ->where('employee_id', $user->employee->id)
            ->latest()
            ->get();
            
        return view('my_payroll.index', [
            'title' => 'Slip Gaji Saya',
            'payrolls' => $payrolls,
        ]);
    }
}
