<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function index()
    {
        return view('position.index', [
            'title' => 'Jabatan',
            'positions' => Position::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('position.create', [
            'title' => 'Tambah Jabatan',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'basic_salary' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama wajib diisi',
            'basic_salary.required' => 'Gaji pokok wajib diisi',
            'basic_salary.numeric' => 'Gaji pokok harus berupa angka',
            'basic_salary.min' => 'Gaji pokok tidak boleh kurang dari 0',
        ]);

        DB::beginTransaction();
        try {
            Position::create($validate);
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Position $position)
    {
        return view('position.show', [
            'title' => 'Detail Jabatan',
            'position' => $position,
        ]);
    }

    public function edit(Position $position)
    {
        return view('position.edit', [
            'title' => 'Edit Jabatan',
            'position' => $position,
        ]);
    }

    public function update(Request $request, Position $position)
    {
        $validate = $request->validate([
            'name' => 'required',
            'basic_salary' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Nama wajib diisi',
            'basic_salary.required' => 'Gaji pokok wajib diisi',
            'basic_salary.numeric' => 'Gaji pokok harus berupa angka',
            'basic_salary.min' => 'Gaji pokok tidak boleh kurang dari 0',
        ]);

        DB::beginTransaction();
        try {
            $position->update($validate);
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.edit', $position)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Position $position)
    {
        DB::beginTransaction();
        try {
            $position->delete();
            DB::commit();
            return to_route('position.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('position.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
