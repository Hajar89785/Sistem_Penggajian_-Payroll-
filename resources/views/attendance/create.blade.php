<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('attendance.create') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label required">Pilih Periode Penggajian</label>
                <select name="period_id" class="form-select select2-default" required onchange="this.form.submit()">
                    <option value="">Pilih Periode...</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" @selected($selected_period == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                @if($period)
                    <div class="alert alert-info py-2 mb-0">
                        <i class='bx bx-info-circle me-2'></i> Menampilkan karyawan yang belum diinput absensinya pada periode <strong>{{ $period->name }}</strong>
                    </div>
                @endif
            </div>
        </form>
    </div>

    @if($period)
    <div class="card shadow-lg p-3">
        @if(count($employees) > 0)
        <form action="{{ route('attendance.store') }}" method="post" class="form">
            @csrf
            <input type="hidden" name="payroll_period_id" value="{{ $period->id }}">
            
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th width="25%">Karyawan</th>
                            <th>Hadir</th>
                            <th>Sakit</th>
                            <th>Izin</th>
                            <th>Alpa</th>
                            <th>Lembur (Jam)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $key => $emp)
                        <tr>
                            <td class="text-start">
                                <input type="hidden" name="attendances[{{ $key }}][employee_id]" value="{{ $emp->id }}">
                                <span class="fw-bold">{{ $emp->full_name }}</span><br>
                                <small class="text-muted">{{ $emp->employee_code }}</small>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ $key }}][present_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ $key }}][sick_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ $key }}][leave_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ $key }}][absent_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ $key }}][overtime_hours]" value="0" min="0" required>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <a href="{{ route('attendance.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Semua Absensi</button>
            </div>
        </form>
        @else
            <div class="text-center py-5">
                <i class='bx bx-check-double text-success' style="font-size: 4rem;"></i>
                <h5 class="mt-3">Semua Karyawan Sudah Memiliki Data Absensi</h5>
                <p class="text-muted">Tidak ada data Karyawan yang tersisa untuk diinput pada periode ini.</p>
                <a href="{{ route('attendance.index', ['period_id' => $period->id]) }}" class="btn btn-primary mt-2">Lihat Rekap Absensi</a>
            </div>
        @endif
    </div>
    @endif

</x-app>