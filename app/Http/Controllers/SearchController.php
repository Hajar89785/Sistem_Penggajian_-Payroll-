<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $search = $request->input('search');
        
        $employees = collect();
        $positions = collect();

        if ($search) {
            $employees = Employee::with(['department', 'position'])
                ->where(function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%")
                      ->orWhere('employee_code', 'LIKE', "%{$search}%")
                      ->orWhereHas('position', function($query) use ($search) {
                          $query->where('name', 'LIKE', "%{$search}%");
                      });
                })->get();
                
            $positions = Position::where('name', 'LIKE', "%{$search}%")->get();
        }

        return view('search.results', [
            'title' => 'Hasil Pencarian',
            'search' => $search,
            'employees' => $employees,
            'positions' => $positions
        ]);
    }
}
