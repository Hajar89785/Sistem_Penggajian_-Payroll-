<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <form action="{{ route('payroll.index') }}" method="GET" id="filter-form">
                    <label class="form-label">Filter Periode Gaji</label>
                    <select name="period_id" class="form-select select2-default" onchange="document.getElementById('filter-form').submit()">
                        <option value="">Semua Periode</option>
                        @foreach($periods as $p)
                            <option value="{{ $p->id }}" @selected($selected_period == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            @if($selected_period)
            <div class="col-md-8 text-end">
                <form action="{{ route('payroll.generate') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="period_id" value="{{ $selected_period }}">
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin melakukan proses Generate Gaji (kalkulasi ulang) untuk semua karyawan yang belum di-generate pada periode ini?')">
                        <i class='bx bx-cog'></i> Generate Payroll
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Periode</th>
                        <th>Karyawan</th>
                        <th>Gaji Pokok</th>
                        <th>Total Tunjangan</th>
                        <th>Total Potongan</th>
                        <th>Gaji Bersih (Net)</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payrolls as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->payrollPeriod->name }}</td>
                        <td>
                            <span class="fw-bold">{{ $p->employee->full_name }}</span><br>
                            <small class="text-muted">{{ $p->employee->employee_code }}</small>
                        </td>
                        <td class="text-end">Rp {{ number_format($p->basic_salary, 0, ',', '.') }}</td>
                        <td class="text-end text-success">+ Rp {{ number_format($p->total_allowances, 0, ',', '.') }}</td>
                        <td class="text-end text-danger">- Rp {{ number_format($p->total_deductions, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-primary">Rp {{ number_format($p->net_salary, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'Paid' ? 'success' : 'warning' }}">{{ $p->status }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('payroll.show', $p) }}" title="Lihat Detail">
                                    <i class='bx bx-show'></i>
                                </button>
                                
                                @if (in_array($p->status, ['Paid', 'Final']))
                                    @if (Auth::user()->role == 'Superadmin')
                                        <a href="{{ route('payroll.edit', $p) }}" class="btn btn-warning btn-sm" title="Edit Data (Bypass Superadmin)">
                                            <i class='bx bx-edit-alt'></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-route="{{ route('payroll.destroy', $p) }}" title="Hapus Data (Bypass Superadmin)">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    @else
                                        <span class="badge bg-secondary" title="Data Terkunci untuk Admin"><i class="bx bx-lock-alt"></i> Terkunci</span>
                                    @endif
                                @else
                                    <form action="{{ route('payroll.pay', $p) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Konfirmasi pembayaran gaji ini (Tandai sebagai Paid)?')" title="Bayar">
                                            <i class='bx bx-check'></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('payroll.edit', $p) }}" class="btn btn-warning btn-sm" title="Edit Data">
                                        <i class='bx bx-edit-alt'></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-route="{{ route('payroll.destroy', $p) }}" title="Hapus Data">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                @endif
                            </div>
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
                        <!-- Nanti tombol Cetak PDF diletakkan di sini -->
                    </div>
                </div>
            </div>
        </div>
    @endpush
    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'))
            })
            $('#data-table').on('click', '.btn-detail', function() {
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
            })
        </script>
    @endpush
</x-app>