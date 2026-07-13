<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <style>
        /* Custom Dashboard Overrides */
        .page-title-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-title-box h3 {
            font-size: 22px;
            font-weight: 700;
            color: var(--payflow-text-dark);
            margin: 0;
        }
        .page-title-box p {
            font-size: 13px;
            color: var(--payflow-text-muted);
            margin: 5px 0 0 0;
        }
        .btn-purple {
            background-color: var(--payflow-purple);
            color: #fff;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 600;
            border: none;
            font-size: 14px;
        }
        .btn-purple:hover {
            background-color: #6A38F5;
            color: #fff;
        }
        .btn-outline-custom {
            border: 1px solid #EBECEF;
            color: var(--payflow-text-dark);
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 14px;
            background: #fff;
            font-weight: 600;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--payflow-text-dark);
        }

        /* Metric Cards */
        .metric-card {
            border: 1px solid #EBECEF;
            border-radius: 12px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.01);
            height: 100%;
        }
        .metric-card .icon-box {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
        .metric-card .card-title-text {
            font-size: 12px;
            font-weight: 700;
            color: var(--payflow-text-dark);
            margin: 0;
        }
        .metric-card h2 {
            font-size: 24px;
            font-weight: 800;
            margin: 15px 0 5px 0;
            color: var(--payflow-text-dark);
        }
        .metric-card p {
            font-size: 11px;
            font-weight: 600;
            margin: 0;
        }

        /* Pay Runs Card */
        .payruns-card {
            border: 1px solid #EBECEF;
            border-radius: 12px;
            background: #fff;
            padding: 20px;
        }
        .payruns-card .info-block {
            margin-bottom: 15px;
        }
        .payruns-card .info-block span {
            display: block;
            font-size: 11px;
            color: var(--payflow-text-muted);
            margin-bottom: 2px;
        }
        .payruns-card .info-block strong {
            font-size: 13px;
            color: var(--payflow-text-dark);
        }

        /* Tables */
        .custom-table {
            border: 1px solid #EBECEF;
            border-radius: 12px;
            background: #fff;
            padding: 20px;
        }
        .table thead th {
            border-bottom: 1px solid #EBECEF;
            color: var(--payflow-text-muted);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 12px 15px;
        }
        .table tbody td {
            font-size: 13px;
            color: var(--payflow-text-dark);
            font-weight: 600;
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #F4F5F9;
        }
        .badge-status {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-scheduled { background: #F2EEFF; color: var(--payflow-purple); border: 1px solid #D9CBFF; }
        .badge-completed { background: #E6F8F0; color: #20B070; border: 1px solid #B0EACF; }
        .badge-pending { background: #FFF4E5; color: #F2994A; border: 1px solid #FAD1A8; }
    </style>

    <div class="page-title-box">
        <div>
            <h3>Dashboard Utama</h3>
            <p>Kelola pembayaran gaji, pantau proses, dan tinjau laporan keuangan.</p>
        </div>
        <div>
            <a href="{{ route('report.index') }}" class="btn btn-outline-custom me-2"><i class="bi bi-download me-1"></i> Export</a>
            <a href="{{ route('payroll.index') }}" class="btn btn-purple"><i class="bi bi-plus-lg me-1"></i> New Payroll</a>
        </div>
    </div>

    <!-- 1. Payroll Breakdown / Top Cards -->
    <div class="row mb-4">
        <!-- Total Karyawan -->
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box" style="background: #F2EEFF; color: var(--payflow-purple);">
                        <i class="bx bx-group"></i>
                    </div>
                    <p class="card-title-text">Total Karyawan</p>
                </div>
                <h2>{{ $totalEmployees }}</h2>
                <p class="text-success"><i class="bi bi-arrow-up-short"></i> 13% increase since last month</p>
            </div>
        </div>
        <!-- Total Pengeluaran -->
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box" style="background: #FFF1F0; color: #FF4D4F;">
                        <i class="bx bx-wallet"></i>
                    </div>
                    <p class="card-title-text">Total Pengeluaran Gaji</p>
                </div>
                <h2>Rp{{ number_format($totalExpense, 0, ',', '.') }}</h2>
                <p class="text-success"><i class="bi bi-arrow-up-short"></i> 7% increase since last month</p>
            </div>
        </div>
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box" style="background: #E6F7FF; color: #1890FF;">
                        <i class="bx bx-user"></i>
                    </div>
                    <p class="card-title-text">Total Users</p>
                </div>
                <h2>{{ $totalUsers }}</h2>
                <p style="color: var(--payflow-text-muted);">{{ $totalEmployees }} Karyawan</p>
            </div>
        </div>
        <!-- Periode Gaji Aktif -->
        <div class="col-md-3">
            <div class="metric-card">
                <div class="d-flex align-items-center">
                    <div class="icon-box" style="background: #FFFBE6; color: #FAAD14;">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <p class="card-title-text">Periode Gaji Saat Ini</p>
                </div>
                <h2 style="font-size: 18px; margin-top: 20px;">{{ $activePeriod ? $activePeriod->name : 'Belum Ada' }}</h2>
                <p style="color: var(--payflow-text-muted);">
                    {{ $activePeriod ? \Carbon\Carbon::parse($activePeriod->start_date)->format('M d') . ' - ' . \Carbon\Carbon::parse($activePeriod->end_date)->format('M d, Y') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- 2. Pay Runs & History -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="payruns-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="section-title mb-0">Periode Berjalan</h5>
                </div>
                <div class="row">
                    <div class="col-6 info-block">
                        <span>Periode Gaji</span>
                        <strong>{{ $activePeriod ? \Carbon\Carbon::parse($activePeriod->start_date)->format('d M') . ' - ' . \Carbon\Carbon::parse($activePeriod->end_date)->format('d M Y') : '-' }}</strong>
                    </div>
                    <div class="col-6 info-block">
                        <span>Total Karyawan</span>
                        <strong>{{ $totalEmployees }}</strong>
                    </div>
                    <div class="col-6 info-block">
                        <span>Tanggal Bayar</span>
                        <strong>{{ $activePeriod ? \Carbon\Carbon::parse($activePeriod->end_date)->format('M d, Y') : '-' }}</strong>
                    </div>
                    <div class="col-6 info-block">
                        <span>Status</span>
                        <strong style="color: var(--payflow-purple);">Dijadwalkan</strong>
                    </div>
                </div>
                <hr style="border-color: #EBECEF;">
                
                <div class="d-flex align-items-center justify-content-center mt-3">
                    <div id="donutChart"></div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="payruns-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title mb-0">Riwayat Penggajian</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-custom btn-sm dropdown-toggle" type="button" id="dropdownFilter" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 11px; padding: 4px 10px;">
                            Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownFilter">
                            <li><a class="dropdown-item" href="{{ route('payroll.index') }}">Semua Periode</a></li>
                            @foreach($periods as $period)
                                <li><a class="dropdown-item" href="{{ route('payroll.index', ['period_id' => $period->id]) }}">{{ $period->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div>
                    <h3 style="font-weight: 800; font-size: 20px; color: var(--payflow-text-dark); margin:0;">Rp{{ number_format(count($chartSeries) > 0 ? $chartSeries[count($chartSeries)-1] : 0, 0, ',', '.') }}</h3>
                    <p style="font-size: 11px; color: var(--payflow-text-muted);">Grafik pengeluaran gaji tahun berjalan</p>
                </div>
                <div id="barChart" style="min-height: 250px;"></div>
            </div>
        </div>
    </div>

    <!-- 3. Employees Payroll Table -->
    <div class="custom-table mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="section-title mb-0">Data Penggajian Karyawan</h5>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Search..." id="dashboard-search">
                </div>
                <a href="{{ route('employee.create') }}" class="btn btn-purple btn-sm">Tambah Karyawan +</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>ID Penggajian</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Total Gaji</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayrolls as $slip)
                    <tr>
                        <td style="color: var(--payflow-text-muted);">PYRL-{{ str_pad($slip->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $slip->employee->user && $slip->employee->user->avatar ? asset('storage/' . $slip->employee->user->avatar) : asset('niceadmin/img/noprofil.png') }}" class="rounded-circle me-2" width="30" height="30">
                                <span>{{ $slip->employee->full_name }}</span>
                            </div>
                        </td>
                        <td><span style="color: var(--payflow-text-muted);">{{ $slip->employee->position->name ?? '-' }}</span></td>
                        <td>{{ $slip->created_at->format('d M, Y') }}</td>
                        <td>Rp{{ number_format($slip->net_salary, 0, ',', '.') }}</td>
                        <td>
                            @if(strtolower($slip->status) == 'paid')
                                <span class="badge-status badge-completed">{{ $slip->status }}</span>
                            @elseif(strtolower($slip->status) == 'pending')
                                <span class="badge-status badge-pending">{{ $slip->status }}</span>
                            @else
                                <span class="badge-status badge-scheduled">{{ $slip->status }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-info btn-sm btn-detail" data-route="{{ route('payroll.show', $slip->id) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                <a href="{{ route('payroll.print', $slip->id) }}" target="_blank" class="btn btn-warning btn-sm">
                                    <i class='bx bx-printer'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-route="{{ route('payroll.destroy', $slip->id) }}">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data payroll.</td>
                    </tr>
                    @endforelse
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
        document.addEventListener("DOMContentLoaded", () => {
            // Data Grafik Bar (Payroll History)
            const labels = {!! json_encode($chartLabels) !!};
            const seriesData = {!! json_encode($chartSeries) !!};
            
            new ApexCharts(document.querySelector("#barChart"), {
                series: [{
                    name: 'Total Pengeluaran (Rp)',
                    data: seriesData
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '30%',
                    }
                },
                colors: ['#7B3DFF'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#8A8B9F', fontSize: '11px' } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#8A8B9F', fontSize: '11px' },
                        formatter: function (value) {
                            return "Rp " + value.toLocaleString("id-ID");
                        }
                    }
                },
                grid: { borderColor: '#F4F5F9', strokeDashArray: 4 }
            }).render();

            // Data Donut Chart (Pay Runs)
            const gross = {{ $payRunGross ?? 0 }};
            const deduction = {{ $payRunDeduction ?? 0 }};
            
            new ApexCharts(document.querySelector("#donutChart"), {
                series: [gross, deduction],
                labels: ['Gross Pay', 'Deduction'],
                chart: {
                    type: 'donut',
                    height: 180
                },
                colors: ['#7B3DFF', '#A855F7'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { show: false },
                                value: {
                                    show: true,
                                    fontSize: '14px',
                                    fontWeight: 700,
                                    formatter: function (val) {
                                        return "Rp" + (val/1000000).toFixed(1) + "M";
                                    }
                                }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                stroke: { show: false }
            }).render();

            // Fitur Filter/Search Manual
            const searchInput = document.getElementById('dashboard-search');
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const value = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.custom-table tbody tr');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.indexOf(value) > -1 ? '' : 'none';
                    });
                });
            }
        });

        // Modal Detail Logic & Delete Logic for Table
        $('.custom-table').on('click', '.btn-delete', function() {
            $('#form-delete').attr('action', $(this).data('route'));
        });
        $('.custom-table').on('click', '.btn-detail', function() {
            Swal.fire({
                title: 'Memuat...', text: 'Mohon tunggu sebentar', allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            $('#modal-detail').load($(this).data('route'), function(response, status, xhr) {
                if (status == "success") {
                    setTimeout(() => { Swal.close(); $('#detailModal').modal('show'); }, 500);
                } else {
                    Swal.fire({ title: "Error", text: "Gagal memuat data", icon: "error" });
                }
            });
        });
    </script>
    @endpush
</x-app>
