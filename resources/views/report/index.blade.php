<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('report.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Periode Gaji</label>
                <select name="period_id" class="form-select select2-default" onchange="this.form.submit()">
                    <option value="">Rekap Semua Periode (Bulan)</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" @selected($selected_period == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('report.export', ['period_id' => $selected_period]) }}" class="btn btn-success">
                    <i class='bx bx-file'></i> Export Excel
                </a>
            </div>
        </form>
    </div>

    @if($selected_period && $summary)
        <!-- Single Period Summary -->
        <div class="row g-4 mb-4">
            <!-- Card Karyawan -->
            <div class="col-xl-3 col-md-6">
                <div class="card info-card customers-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Karyawan <span>| Digaji</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $summary->total_employees ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Gaji Pokok -->
            <div class="col-xl-3 col-md-6">
                <div class="card info-card revenue-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Gaji Pokok <span>| Total</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info text-white" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                            <div class="ps-3">
                                <h6>Rp {{ number_format($summary->total_basic_salary ?? 0, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Potongan -->
            <div class="col-xl-3 col-md-6">
                <div class="card info-card sales-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Potongan <span>| Total</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="bi bi-graph-down-arrow"></i>
                            </div>
                            <div class="ps-3">
                                <h6>Rp {{ number_format($summary->total_deductions ?? 0, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Net -->
            <div class="col-xl-3 col-md-6">
                <div class="card info-card revenue-card shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title">Pengeluaran <span>| Net</span></h5>
                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white" style="width: 50px; height: 50px; font-size: 24px;">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <div class="ps-3">
                                <h6>Rp {{ number_format($summary->total_net_salary ?? 0, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(!$selected_period && $summary)
        <!-- All Periods Aggregation -->
        <div class="card shadow-lg p-3">
            <h5 class="mb-3">Agregat Total Pengeluaran Gaji per Periode</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100">
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
                        @forelse($summary as $row)
                        <tr>
                            <td class="fw-bold">{{ $row->payrollPeriod->name }}</td>
                            <td class="text-center">{{ $row->total_employees }}</td>
                            <td class="text-end">Rp {{ number_format($row->total_basic_salary, 0, ',', '.') }}</td>
                            <td class="text-end text-success">+ Rp {{ number_format($row->total_allowances, 0, ',', '.') }}</td>
                            <td class="text-end text-danger">- Rp {{ number_format($row->total_deductions, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold text-primary">Rp {{ number_format($row->total_net_salary, 0, ',', '.') }}</td>
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