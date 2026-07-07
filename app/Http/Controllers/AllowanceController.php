<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllowanceController extends Controller
{
    public function index()
    {
        return view('allowance.index', [
            'title' => 'Tunjangan',
            'allowances' => Allowance::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('allowance.create', [
            'title' => 'Tambah Tunjangan',
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'type' => 'required|in:Fixed,Variable',
        ], [
            'name.required' => 'Nama wajib diisi',
            'type.required' => 'Tipe wajib diisi',
            'type.in' => 'Tipe tidak valid',
        ]);

        DB::beginTransaction();
        try {
            Allowance::create($validate);
            DB::commit();
            return to_route('allowance.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Allowance $allowance)
    {
        return view('allowance.show', [
            'title' => 'Detail Tunjangan',
            'allowance' => $allowance,
        ]);
    }

    public function edit(Allowance $allowance)
    {
        return view('allowance.edit', [
            'title' => 'Edit Tunjangan',
            'allowance' => $allowance,
        ]);
    }

    public function update(Request $request, Allowance $allowance)
    {
        $validate = $request->validate([
            'name' => 'required',
            'type' => 'required|in:Fixed,Variable',
        ], [
            'name.required' => 'Nama wajib diisi',
            'type.required' => 'Tipe wajib diisi',
            'type.in' => 'Tipe tidak valid',
        ]);

        DB::beginTransaction();
        try {
            $allowance->update($validate);
            DB::commit();
            return to_route('allowance.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.edit', $allowance)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Allowance $allowance)
    {
        DB::beginTransaction();
        try {
            $allowance->delete();
            DB::commit();
            return to_route('allowance.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('allowance.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
