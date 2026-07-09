<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="card shadow-lg p-3 mb-4">
        <form action="{{ route('attendance.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Filter Periode Gaji</label>
                <select name="period_id" class="form-select select2-default" onchange="this.form.submit()">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $p)
                        <option value="{{ $p->id }}" @selected($selected_period == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 text-end">
                <a href="{{ route('attendance.create', ['period_id' => $selected_period]) }}" class="btn btn-primary"><i class='bx bx-plus'></i> Input Absen Massal</a>
            </div>
        </form>
    </div>

    <div class="card shadow-lg p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
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
                    @foreach ($attendances as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $a->payrollPeriod->name }}</td>
                        <td>{{ $a->employee->full_name }}</td>
                        <td class="text-success fw-bold">{{ $a->present_days }}</td>
                        <td class="text-warning fw-bold">{{ $a->sick_days }}</td>
                        <td class="text-info fw-bold">{{ $a->leave_days }}</td>
                        <td class="text-danger fw-bold">{{ $a->absent_days }}</td>
                        <td class="text-primary fw-bold">{{ $a->overtime_hours }}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-route="{{ route('attendance.destroy', $a) }}">
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
                $('#form-delete').attr('action', $(this).data('route'))
            })
        </script>
    @endpush
</x-app>