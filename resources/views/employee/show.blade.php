<div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-3">Data Profil</h6>
        <table class="table table-sm table-borderless">
            <tr><th width="40%">NIK</th><td>: {{ $employee->employee_code }}</td></tr>
            <tr><th>Nama</th><td>: {{ $employee->full_name }}</td></tr>
            <tr><th>Email</th><td>: {{ $employee->email ?? '-' }}</td></tr>
            <tr><th>Telepon</th><td>: {{ $employee->phone ?? '-' }}</td></tr>
            <tr><th>Tanggal Join</th><td>: {{ $employee->join_date }}</td></tr>
            <tr><th>Akun Login</th><td>: {{ $employee->user->email ?? 'Tidak Ada' }}</td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-3">Pekerjaan & Bank</h6>
        <table class="table table-sm table-borderless">
            <tr><th width="40%">Departemen</th><td>: {{ $employee->department->name ?? '-' }}</td></tr>
            <tr><th>Jabatan</th><td>: {{ $employee->position->name ?? '-' }}</td></tr>
            <tr><th>Gaji Pokok</th><td>: Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}</td></tr>
            <tr><th>Bank</th><td>: {{ $employee->bank_name ?? '-' }}</td></tr>
            <tr><th>No Rekening</th><td>: {{ $employee->bank_account ?? '-' }}</td></tr>
        </table>
    </div>
</div>

<hr>

<div class="row mt-3">
    <div class="col-md-6">
        <h6 class="fw-bold text-success mb-2">Tunjangan Aktif</h6>
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr><th>Tunjangan</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                @forelse($employee->allowances as $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td class="text-end">Rp {{ number_format($a->pivot->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Tidak ada tunjangan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-danger mb-2">Potongan Aktif</h6>
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr><th>Potongan</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                @forelse($employee->deductions as $d)
                    <tr>
                        <td>{{ $d->name }}</td>
                        <td class="text-end">Rp {{ number_format($d->pivot->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Tidak ada potongan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>