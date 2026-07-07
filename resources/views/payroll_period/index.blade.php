<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('payroll_period.create') }}">Tambah Periode</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Periode</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($periods as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->start_date }}</td>
                        <td>{{ $p->end_date }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'Final' ? 'success' : 'warning' }}">{{ $p->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('payroll_period.edit', $p) }}" class="btn btn-warning btn-sm"><i class='bx bx-edit-alt'></i></a>
                            <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal" data-route="{{ route('payroll_period.destroy', $p) }}">
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