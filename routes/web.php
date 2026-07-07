<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    Route::resource('/user', UserController::class)->middleware('role:Superadmin');

    // Master Data Routes
    Route::resource('/department', \App\Http\Controllers\DepartmentController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/position', \App\Http\Controllers\PositionController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/allowance', \App\Http\Controllers\AllowanceController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/deduction', \App\Http\Controllers\DeductionController::class)->middleware('role:Superadmin,Admin');
    
    // Employee Routes
    Route::resource('/employee', \App\Http\Controllers\EmployeeController::class)->middleware('role:Superadmin,Admin');
    
    // Attendance Routes
    Route::resource('/payroll_period', \App\Http\Controllers\PayrollPeriodController::class)->middleware('role:Superadmin,Admin');
    Route::resource('/attendance', \App\Http\Controllers\AttendanceController::class)->middleware('role:Superadmin,Admin');
    
    // Payroll Core Routes
    Route::get('/payroll', [\App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index')->middleware('role:Superadmin,Admin');
    Route::post('/payroll/generate', [\App\Http\Controllers\PayrollController::class, 'generate'])->name('payroll.generate')->middleware('role:Superadmin,Admin');
    Route::get('/payroll/{payroll}', [\App\Http\Controllers\PayrollController::class, 'show'])->name('payroll.show'); // Diizinkan untuk Employee juga lewat Policy
    Route::get('/payroll/{payroll}/print', [\App\Http\Controllers\PayrollController::class, 'print'])->name('payroll.print'); // Diizinkan untuk Employee juga lewat Policy
    Route::delete('/payroll/{payroll}', [\App\Http\Controllers\PayrollController::class, 'destroy'])->name('payroll.destroy')->middleware('role:Superadmin,Admin');
    
    // Reporting Routes
    Route::get('/report/payroll', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index')->middleware('role:Superadmin,Admin');
    Route::get('/report/payroll/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('report.export')->middleware('role:Superadmin,Admin');
    
    // Employee self-service
    Route::get('/my-payroll', [\App\Http\Controllers\MyPayrollController::class, 'index'])->name('my_payroll.index')->middleware('role:Employee');
    
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
});
