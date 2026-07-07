<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('department.index', [
            'title' => 'Departemen',
            'departments' => Department::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('department.create', [
            'title' => 'Tambah Departemen',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            Department::create($validate);
            DB::commit();
            return to_route('department.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('department.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Department $department)
    {
        return view('department.show', [
            'title' => 'Detail Departemen',
            'department' => $department,
        ]);
    }

    public function edit(Department $department)
    {
        return view('department.edit', [
            'title' => 'Edit Departemen',
            'department' => $department,
        ]);
    }

    public function update(Request $request, Department $department)
    {
        $validate = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ], [
            'name.required' => 'Nama wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $department->update($validate);
            DB::commit();
            return to_route('department.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('department.edit', $department)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        DB::beginTransaction();
        try {
            $department->delete();
            DB::commit();
            return to_route('department.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('department.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
