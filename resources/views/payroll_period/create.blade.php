<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('payroll_period.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label required">Nama Periode</label>
                    <input class="form-control" type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Gaji Januari 2026">
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Mulai</label>
                    <input class="form-control" type="date" name="start_date" required value="{{ old('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Berakhir</label>
                    <input class="form-control" type="date" name="end_date" required value="{{ old('end_date') }}">
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('payroll_period.index') }}" class="btn btn-warning">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>