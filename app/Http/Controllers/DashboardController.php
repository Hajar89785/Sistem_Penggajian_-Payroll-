<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollPeriod;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Top Cards (Murni dari Database)
        $totalEmployees = Employee::count();
        $totalExpense = Payroll::sum('net_salary');
        $totalUsers = User::count();
        
        $activePeriod = PayrollPeriod::latest()->first(); // Ambil periode terbaru

        // 2. Data Grafik History Payroll (6 bulan terakhir) (Menggunakan Collection agar support SQLite)
        $chartData = Payroll::latest()->get()->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('M Y'); // ex: Jun 2025
        })->map(function ($row) {
            return $row->sum('net_salary');
        })->take(6)->reverse();

        $chartLabels = $chartData->keys()->toArray();
        $chartSeries = $chartData->values()->toArray();

        // 3. Data Tabel Bawah (5 Karyawan dengan payroll terakhir)
        $recentPayrolls = Payroll::with(['employee.position', 'payrollPeriod'])
                            ->latest()
                            ->take(5)
                            ->get();

        // 4. Data Pay Run (Statistik dari periode aktif)
        $payRunGross = Payroll::sum('basic_salary') + DB::table('payroll_details')->where('type', 'allowance')->sum('amount'); // Asumsi sederhana gross
        $payRunDeduction = DB::table('payroll_details')->where('type', 'deduction')->sum('amount');

        $periods = PayrollPeriod::orderBy('start_date', 'desc')->get();

        return view('dashboard.index', [
            'title' => 'Payment Details',
            'totalEmployees' => $totalEmployees,
            'totalExpense' => $totalExpense,
            'totalUsers' => $totalUsers,
            'activePeriod' => $activePeriod,
            'chartLabels' => $chartLabels,
            'chartSeries' => $chartSeries,
            'recentPayrolls' => $recentPayrolls,
            'payRunGross' => $payRunGross,
            'payRunDeduction' => $payRunDeduction,
            'periods' => $periods
        ]);
    }

    public function show()
    {
        return view('dashboard.show', [
            'title' => 'My Profile',
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('dashboard.edit', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $validate = $request->validate([
                'name' => 'required',
                'password' => 'nullable|min:8',
                'passwordconfirm' => 'nullable|same:password',
                'email' => 'required|email|lowercase|unique:users,email,' . $user->id,
                'avatar' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512'
            ], [
                'name.required' => 'Nama wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'passwordconfirm.same' => 'Konfirmasi password tidak cocok',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'avatar.image' => 'File avatar harus berupa gambar',
                'avatar.mimes' => 'Format avatar harus png, jpg, jpeg, atau svg',
                'avatar.max' => 'Ukuran avatar tidak boleh lebih dari 512 KB',
            ]);

            if ($request->file('avatar')) {
                $validate['avatar'] = $request->file('avatar')->store('img', 'public');
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            if ($request->password) {
                $validate['password'] = bcrypt($request->password);
            } else {
                unset($validate['password']);
            }
            $user->update($validate);

            DB::commit();
            return to_route('dashboard.show')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('dashboard.edit')->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }
}
