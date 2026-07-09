<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="alert alert-info mb-4">
        <i class='bx bx-info-circle me-2'></i> Halo <strong>{{ Auth::user()->name }}</strong>, berikut adalah daftar riwayat slip gaji Anda.
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
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
                    @foreach ($payrolls as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $p->payrollPeriod->name }}</td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td class="text-primary fw-bold">Rp {{ number_format($p->net_salary, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'Paid' ? 'success' : 'warning' }}">{{ $p->status }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm btn-detail"
                                data-route="{{ route('payroll.show', $p) }}">
                                <i class='bx bx-show'></i> Lihat Detail
                            </button>
                            <a href="{{ route('payroll.print', $p) }}" target="_blank" class="btn btn-primary btn-sm">
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