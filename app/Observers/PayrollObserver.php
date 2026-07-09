<?php

namespace App\Observers;

use App\Models\Payroll;

class PayrollObserver
{
    /**
     * Handle the Payroll "updated" event.
     */
    public function updated(Payroll $payroll): void
    {
        // Jika status berubah menjadi Pending
        if ($payroll->wasChanged('status') && $payroll->status === 'Pending') {
            $period = $payroll->payrollPeriod;
            
            // Jika periode saat ini Final, otomatis kembalikan ke Draft
            if ($period && $period->status === 'Final') {
                $period->update(['status' => 'Draft']);
            }
        }
    }
}
