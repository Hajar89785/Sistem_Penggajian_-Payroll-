<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('payroll.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Periode Gaji</label>
                <select name="period_id" class="form-select select2-default" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" @selected($selected_period == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
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
                        <th>Gaji Pokok</th>
                        <th>Total Tunjangan</th>
                        <th>Total Potongan</th>
                        <th>Gaji Bersih (Net)</th>
                        <th>Status</th>
                        <th>Action</th>
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
                        <td>
                            <button type="button" class="btn btn-info btn-sm btn-detail"
                                data-route="{{ route('payroll.show', $p) }}">
                                <i class='bx bx-show'></i> Detail
                            </button>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-route="{{ route('payroll.destroy', $p) }}">
                                <i class='bx bx-trash'></i>
                            </button>
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