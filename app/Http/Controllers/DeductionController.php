<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeductionController extends Controller
{
    public function index()
    {
        return view('deduction.index', [
            'title' => 'Potongan',
            'deductions' => Deduction::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('deduction.create', [
            'title' => 'Tambah Potongan',
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
            Deduction::create($validate);
            DB::commit();
            return to_route('deduction.index')->withSuccess('Data berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Deduction $deduction)
    {
        return view('deduction.show', [
            'title' => 'Detail Potongan',
            'deduction' => $deduction,
        ]);
    }

    public function edit(Deduction $deduction)
    {
        return view('deduction.edit', [
            'title' => 'Edit Potongan',
            'deduction' => $deduction,
        ]);
    }

    public function update(Request $request, Deduction $deduction)
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
            $deduction->update($validate);
            DB::commit();
            return to_route('deduction.index')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.edit', $deduction)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Deduction $deduction)
    {
        DB::beginTransaction();
        try {
            $deduction->delete();
            DB::commit();
            return to_route('deduction.index')->withSuccess('Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('deduction.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
