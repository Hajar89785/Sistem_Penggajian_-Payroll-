<?php

// --- PayrollPeriod Views ---
$payroll_index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('payroll_period.create') }}">Tambah Periode</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Periode</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$periods as \$p)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$p->name }}</td>
                        <td>{{ \$p->start_date }}</td>
                        <td>{{ \$p->end_date }}</td>
                        <td>
                            <span class="badge bg-{{ \$p->status == 'Final' ? 'success' : 'warning' }}">{{ \$p->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('payroll_period.edit', \$p) }}" class="btn btn-warning btn-sm"><i class='bx bx-edit-alt'></i></a>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-route="{{ route('payroll_period.destroy', \$p) }}">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', \$(this).data('route'))
            })
        </script>
    @endpush
</x-app>
EOT;

$payroll_create = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('payroll_period.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label required">Nama Periode</label>
                    <input class="form-control" type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Gaji Januari 2026">
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Mulai</label>
                    <input class="form-control" type="date" name="start_date" required value="{{ old('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Berakhir</label>
                    <input class="form-control" type="date" name="end_date" required value="{{ old('end_date') }}">
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('payroll_period.index') }}" class="btn btn-warning">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
EOT;

$payroll_edit = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('payroll_period.update', \$period) }}" method="post" class="form">
            @csrf @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label required">Nama Periode</label>
                    <input class="form-control" type="text" name="name" required value="{{ old('name', \$period->name) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tanggal Mulai</label>
                    <input class="form-control" type="date" name="start_date" required value="{{ old('start_date', \$period->start_date) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tanggal Berakhir</label>
                    <input class="form-control" type="date" name="end_date" required value="{{ old('end_date', \$period->end_date) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="Draft" @selected(old('status', \$period->status) == 'Draft')>Draft</option>
                        <option value="Final" @selected(old('status', \$period->status) == 'Final')>Final</option>
                    </select>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('payroll_period.index') }}" class="btn btn-warning">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
EOT;


// --- Attendance Views ---
$att_index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('attendance.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Periode Gaji</label>
                <select name="period_id" class="form-select select2-default" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach(\$periods as \$p)
                        <option value="{{ \$p->id }}" @selected(\$selected_period == \$p->id)>{{ \$p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('attendance.create', ['period_id' => \$selected_period]) }}" class="btn btn-primary"><i class='bx bx-plus'></i> Input Absen Massal</a>
            </div>
        </form>
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Karyawan</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th>Lembur (Jam)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$attendances as \$a)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td>{{ \$a->payrollPeriod->name }}</td>
                        <td>{{ \$a->employee->full_name }}</td>
                        <td class="text-success fw-bold">{{ \$a->present_days }}</td>
                        <td class="text-warning fw-bold">{{ \$a->sick_days }}</td>
                        <td class="text-info fw-bold">{{ \$a->leave_days }}</td>
                        <td class="text-danger fw-bold">{{ \$a->absent_days }}</td>
                        <td class="text-primary fw-bold">{{ \$a->overtime_hours }}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-route="{{ route('attendance.destroy', \$a) }}">
                                <i class='bx bx-trash'></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', \$(this).data('route'))
            })
        </script>
    @endpush
</x-app>
EOT;

$att_create = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('attendance.create') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label required">Pilih Periode Penggajian</label>
                <select name="period_id" class="form-select select2-default" required onchange="this.form.submit()">
                    <option value="">Pilih Periode...</option>
                    @foreach(\$periods as \$p)
                        <option value="{{ \$p->id }}" @selected(\$selected_period == \$p->id)>{{ \$p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                @if(\$period)
                    <div class="alert alert-info py-2 mb-0">
                        <i class='bx bx-info-circle me-2'></i> Menampilkan karyawan yang belum diinput absensinya pada periode <strong>{{ \$period->name }}</strong>
                    </div>
                @endif
            </div>
        </form>
    </div>

    @if(\$period)
    <div class="card shadow-lg p-3">
        @if(count(\$employees) > 0)
        <form action="{{ route('attendance.store') }}" method="post" class="form">
            @csrf
            <input type="hidden" name="payroll_period_id" value="{{ \$period->id }}">
            
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
                        @foreach(\$employees as \$key => \$emp)
                        <tr>
                            <td class="text-start">
                                <input type="hidden" name="attendances[{{ \$key }}][employee_id]" value="{{ \$emp->id }}">
                                <span class="fw-bold">{{ \$emp->full_name }}</span><br>
                                <small class="text-muted">{{ \$emp->employee_code }}</small>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ \$key }}][present_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ \$key }}][sick_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ \$key }}][leave_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ \$key }}][absent_days]" value="0" min="0" required>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm text-center" name="attendances[{{ \$key }}][overtime_hours]" value="0" min="0" required>
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
                <a href="{{ route('attendance.index', ['period_id' => \$period->id]) }}" class="btn btn-primary mt-2">Lihat Rekap Absensi</a>
            </div>
        @endif
    </div>
    @endif

</x-app>
EOT;

mkdir(__DIR__ . '/resources/views/payroll_period', 0777, true);
mkdir(__DIR__ . '/resources/views/attendance', 0777, true);
file_put_contents(__DIR__ . '/resources/views/payroll_period/index.blade.php', $payroll_index);
file_put_contents(__DIR__ . '/resources/views/payroll_period/create.blade.php', $payroll_create);
file_put_contents(__DIR__ . '/resources/views/payroll_period/edit.blade.php', $payroll_edit);
file_put_contents(__DIR__ . '/resources/views/attendance/index.blade.php', $att_index);
file_put_contents(__DIR__ . '/resources/views/attendance/create.blade.php', $att_create);

echo "Views for Task 4 generated successfully.\n";
