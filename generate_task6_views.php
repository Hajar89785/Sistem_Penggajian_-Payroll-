<?php

$payroll_print = <<<EOT
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ \$payroll->employee->full_name }}</title>
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
            <h2>PT. NAMA PERUSAHAAN</h2>
            <p class="mb-0">Jl. Contoh Alamat No. 123, Kota, Indonesia</p>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">SLIP GAJI KARYAWAN</h4>
            <h6 class="text-muted">Periode: {{ \$payroll->payrollPeriod->name }}</h6>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <table class="table table-sm table-borderless">
                    <tr><th width="40%">Nama</th><td>: {{ \$payroll->employee->full_name }}</td></tr>
                    <tr><th>NIK</th><td>: {{ \$payroll->employee->employee_code }}</td></tr>
                    <tr><th>Departemen</th><td>: {{ \$payroll->employee->department->name ?? '-' }}</td></tr>
                    <tr><th>Jabatan</th><td>: {{ \$payroll->employee->position->name ?? '-' }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless">
                    <tr><th width="40%">Bank</th><td>: {{ \$payroll->employee->bank_name ?? '-' }}</td></tr>
                    <tr><th>No Rekening</th><td>: {{ \$payroll->employee->bank_account ?? '-' }}</td></tr>
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
                            <td class="text-end">{{ number_format(\$payroll->basic_salary, 0, ',', '.') }}</td>
                        </tr>
                        @foreach(\$payroll->details->where('component_type', 'Allowance') as \$allowance)
                        <tr>
                            <td>{{ \$allowance->component_name }}</td>
                            <td class="text-end">{{ number_format(\$allowance->amount, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Penerimaan (A)</th>
                            <th class="text-end">{{ number_format(\$payroll->basic_salary + \$payroll->total_allowances, 0, ',', '.') }}</th>
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
                        @forelse(\$payroll->details->where('component_type', 'Deduction') as \$deduction)
                        <tr>
                            <td>{{ \$deduction->component_name }}</td>
                            <td class="text-end">{{ number_format(\$deduction->amount, 0, ',', '.') }}</td>
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
                            <th class="text-end">{{ number_format(\$payroll->total_deductions, 0, ',', '.') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="border p-3 mt-2 text-center" style="background-color: #f8f9fa;">
            <h5 class="mb-1">PENERIMAAN BERSIH (A - B)</h5>
            <h3 class="fw-bold mb-0">Rp {{ number_format(\$payroll->net_salary, 0, ',', '.') }}</h3>
        </div>

        <div class="row mt-5 pt-4 text-center">
            <div class="col-6">
                <p>Penerima,</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline mb-0">{{ \$payroll->employee->full_name }}</p>
                <p class="text-muted small">Karyawan</p>
            </div>
            <div class="col-6">
                <p>Mengetahui,</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline mb-0">HRD Manager</p>
                <p class="text-muted small">PT. Nama Perusahaan</p>
            </div>
        </div>
    </div>

</body>
</html>
EOT;

$report_index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('report.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Periode Gaji</label>
                <select name="period_id" class="form-select select2-default" onchange="this.form.submit()">
                    <option value="">Rekap Semua Periode (Bulan)</option>
                    @foreach(\$periods as \$p)
                        <option value="{{ \$p->id }}" @selected(\$selected_period == \$p->id)>{{ \$p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('report.export', ['period_id' => \$selected_period]) }}" class="btn btn-success">
                    <i class='bx bx-export'></i> Export CSV
                </a>
            </div>
        </form>
    </div>

    @if(\$selected_period && \$summary)
        <!-- Single Period Summary -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-white-50">Total Karyawan Digaji</h6>
                        <h3 class="mb-0">{{ \$summary->total_employees ?? 0 }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-white-50">Total Gaji Pokok</h6>
                        <h3 class="mb-0">Rp {{ number_format(\$summary->total_basic_salary ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-white-50">Total Potongan</h6>
                        <h3 class="mb-0">Rp {{ number_format(\$summary->total_deductions ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-white-50">Total Pengeluaran (Net)</h6>
                        <h3 class="mb-0">Rp {{ number_format(\$summary->total_net_salary ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @elseif(!\$selected_period && \$summary)
        <!-- All Periods Aggregation -->
        <div class="card shadow-lg p-3">
            <h5 class="mb-3">Agregat Total Pengeluaran Gaji per Periode</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Periode</th>
                            <th class="text-center">Jml Karyawan</th>
                            <th class="text-end">Total Gaji Pokok</th>
                            <th class="text-end">Total Tunjangan</th>
                            <th class="text-end">Total Potongan</th>
                            <th class="text-end">Pengeluaran Bersih (Net)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\$summary as \$row)
                        <tr>
                            <td class="fw-bold">{{ \$row->payrollPeriod->name }}</td>
                            <td class="text-center">{{ \$row->total_employees }}</td>
                            <td class="text-end">Rp {{ number_format(\$row->total_basic_salary, 0, ',', '.') }}</td>
                            <td class="text-end text-success">+ Rp {{ number_format(\$row->total_allowances, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">- Rp {{ number_format(\$row->total_deductions, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-primary">Rp {{ number_format(\$row->total_net_salary, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data penggajian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app>
EOT;

$my_payroll_index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    
    <div class="alert alert-info mb-4">
        <i class='bx bx-info-circle me-2'></i> Halo <strong>{{ Auth::user()->name }}</strong>, berikut adalah daftar riwayat slip gaji Anda.
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Tanggal Cetak</th>
                        <th>Gaji Bersih (Net)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$payrolls as \$p)
                    <tr>
                        <td>{{ \$loop->iteration }}</td>
                        <td class="fw-bold">{{ \$p->payrollPeriod->name }}</td>
                        <td>{{ \$p->created_at->format('d M Y') }}</td>
                        <td class="text-primary fw-bold">Rp {{ number_format(\$p->net_salary, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ \$p->status == 'Paid' ? 'success' : 'warning' }}">{{ \$p->status }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm btn-detail"
                                data-route="{{ route('payroll.show', \$p) }}">
                                <i class='bx bx-show'></i> Lihat Detail
                            </button>
                            <a href="{{ route('payroll.print', \$p) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class='bx bx-printer'></i> Cetak/PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('modals')
        <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Rincian Slip Gaji</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-detail">...</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-detail', function() {
                Swal.fire({
                    title: 'Memuat...', text: 'Mohon tunggu sebentar', allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
                $('#modal-detail').load(\$(this).data('route'), function(response, status, xhr) {
                    if (status == "success") {
                        setTimeout(() => { Swal.close(); $('#detailModal').modal('show'); }, 500);
                    } else {
                        Swal.fire({ title: "Error", text: "Gagal memuat data", icon: "error" });
                    }
                });
            })
        </script>
    @endpush
</x-app>
EOT;

mkdir(__DIR__ . '/resources/views/report', 0777, true);
mkdir(__DIR__ . '/resources/views/my_payroll', 0777, true);
file_put_contents(__DIR__ . '/resources/views/payroll/print.blade.php', $payroll_print);
file_put_contents(__DIR__ . '/resources/views/report/index.blade.php', $report_index);
file_put_contents(__DIR__ . '/resources/views/my_payroll/index.blade.php', $my_payroll_index);

echo "Views for Task 6 generated successfully.\n";
