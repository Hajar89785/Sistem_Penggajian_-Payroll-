<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'position_id',
        'employee_code',
        'full_name',
        'email',
        'phone',
        'join_date',
        'basic_salary',
        'bank_name',
        'bank_account',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function allowances()
    {
        return $this->belongsToMany(Allowance::class, 'employee_allowances')->withPivot('amount')->withTimestamps();
    }

    public function deductions()
    {
        return $this->belongsToMany(Deduction::class, 'employee_deductions')->withPivot('amount')->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
