<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'payroll_period_id',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'payment_date',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollPeriod()
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }

    public function attendance()
    {
        // Relasi attendance (berdasarkan employee dan periode yang sama)
        return $this->hasOne(Attendance::class, 'employee_id', 'employee_id')
                    ->where('payroll_period_id', $this->payroll_period_id);
    }
}
