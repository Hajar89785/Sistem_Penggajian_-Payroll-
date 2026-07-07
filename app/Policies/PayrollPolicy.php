<?php

namespace App\Policies;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PayrollPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['Superadmin', 'Admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Payroll $payroll): bool
    {
        if (in_array($user->role, ['Superadmin', 'Admin'])) {
            return true;
        }

        // Karyawan hanya bisa melihat miliknya sendiri
        return $user->employee && $user->employee->id === $payroll->employee_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['Superadmin', 'Admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Payroll $payroll): bool
    {
        return in_array($user->role, ['Superadmin', 'Admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Payroll $payroll): bool
    {
        return in_array($user->role, ['Superadmin', 'Admin']);
    }
}
