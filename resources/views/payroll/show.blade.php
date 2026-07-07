<div class="text-center mb-4">
    <h4 class="fw-bold text-uppercase">Slip Gaji Karyawan</h4>
    <h6 class="text-muted">{{ $payroll->payrollPeriod->name }}</h6>
</div>

<div class="row mb-4">
    <div class="col-6">
        <table class="table table-sm table-borderless">
            <tr><th width="40%">Nama</th><td>: {{ $payroll->employee->full_name }}</td></tr>
            <tr><th>NIK</th><td>: {{ $payroll->employee->employee_code }}</td></tr>
            <tr><th>Departemen</th><td>: {{ $payroll->employee->department->name ?? '-' }}</td></tr>
            <tr><th>Jabatan</th><td>: {{ $payroll->employee->position->name ?? '-' }}</td></tr>
        </table>
    </div>
    <div class="col-6 text-end">
        <table class="table table-sm table-borderless">
            <tr><th width="50%">Bank</th><td>: {{ $payroll->employee->bank_name ?? '-' }}</td></tr>
            <tr><th>No Rekening</th><td>: {{ $payroll->employee->bank_account ?? '-' }}</td></tr>
            <tr><th>Tanggal Cetak</th><td>: {{ now()->format('d M Y') }}</td></tr>
        </table>
    </div>
</div>

<table class="table table-bordered mb-4">
    <thead class="table-light">
        <tr>
            <th>Deskripsi Pendapatan</th>
            <th class="text-end">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Gaji Pokok</strong></td>
            <td class="text-end">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
        </tr>
        @foreach($payroll->details->where('component_type', 'Allowance') as $allowance)
        <tr>
            <td>{{ $allowance->component_name }}</td>
            <td class="text-end">Rp {{ number_format($allowance->amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="text-end">Total Pendapatan (A)</th>
            <th class="text-end text-success">Rp {{ number_format($payroll->basic_salary + $payroll->total_allowances, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<table class="table table-bordered mb-4">
    <thead class="table-light">
        <tr>
            <th>Deskripsi Potongan</th>
            <th class="text-end">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse($payroll->details->where('component_type', 'Deduction') as $deduction)
        <tr>
            <td>{{ $deduction->component_name }}</td>
            <td class="text-end">Rp {{ number_format($deduction->amount, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="2" class="text-center text-muted">Tidak ada potongan</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th class="text-end">Total Potongan (B)</th>
            <th class="text-end text-danger">Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<div class="alert alert-secondary d-flex justify-content-between align-items-center mb-0">
    <h5 class="mb-0 fw-bold">PENERIMAAN BERSIH (A - B)</h5>
    <h4 class="mb-0 fw-bold text-primary">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</h4>
</div>
<div class="mt-3 text-end">
    <a href="{{ route('payroll.print', $payroll) }}" target="_blank" class="btn btn-primary"><i class='bx bx-printer'></i> Cetak Slip Gaji</a>
</div>