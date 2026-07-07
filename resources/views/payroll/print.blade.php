<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fff; padding: 40px; }
        .slip-container { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 30px; }
        .company-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .table-borderless td, .table-borderless th { padding: 4px 8px; }
        .table-bordered th { background-color: #f8f9fa !important; }
        @media print {
            body { padding: 0; }
            .slip-container { border: none; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    
    <div class="mb-3 text-end btn-print">
        <button onclick="window.print()" class="btn btn-primary">Cetak / Save PDF</button>
    </div>

    <div class="slip-container">
        <div class="company-header">
            <h2>{{ $setting->app_name }}</h2>
            <p class="mb-0">{{ $setting->company_address }}</p>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">SLIP GAJI KARYAWAN</h4>
            <h6 class="text-muted">Periode: {{ $payroll->payrollPeriod->name }}</h6>
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
            <div class="col-6">
                <table class="table table-sm table-borderless">
                    <tr><th width="40%">Bank</th><td>: {{ $payroll->employee->bank_name ?? '-' }}</td></tr>
                    <tr><th>No Rekening</th><td>: {{ $payroll->employee->bank_account ?? '-' }}</td></tr>
                    <tr><th>Tanggal Cetak</th><td>: {{ now()->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <table class="table table-bordered table-sm mb-4">
                    <thead>
                        <tr>
                            <th>PENERIMAAN</th>
                            <th class="text-end">JUMLAH (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok</td>
                            <td class="text-end">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                        </tr>
                        @foreach($payroll->details->where('component_type', 'Allowance') as $allowance)
                        <tr>
                            <td>{{ $allowance->component_name }}</td>
                            <td class="text-end">{{ number_format($allowance->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Penerimaan (A)</th>
                            <th class="text-end">{{ number_format($payroll->basic_salary + $payroll->total_allowances, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-bordered table-sm mb-4">
                    <thead>
                        <tr>
                            <th>POTONGAN</th>
                            <th class="text-end">JUMLAH (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payroll->details->where('component_type', 'Deduction') as $deduction)
                        <tr>
                            <td>{{ $deduction->component_name }}</td>
                            <td class="text-end">{{ number_format($deduction->amount, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Tidak ada potongan</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Potongan (B)</th>
                            <th class="text-end">{{ number_format($payroll->total_deductions, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="border p-3 mt-2 text-center" style="background-color: #f8f9fa;">
            <h5 class="mb-1">PENERIMAAN BERSIH (A - B)</h5>
            <h3 class="fw-bold mb-0">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</h3>
        </div>

        <div class="row mt-5 pt-4 text-center">
            <div class="col-6">
                <p>Penerima,</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline mb-0">{{ $payroll->employee->full_name }}</p>
                <p class="text-muted small">Karyawan</p>
            </div>
            <div class="col-6">
                <p>Mengetahui,</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline mb-0">{{ $setting->signatory_name }}</p>
                <p class="text-muted small">{{ $setting->signatory_position }} - {{ $setting->app_name }}</p>
            </div>
        </div>
    </div>

</body>
</html>