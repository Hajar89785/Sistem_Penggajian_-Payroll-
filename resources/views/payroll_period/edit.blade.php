<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('payroll_period.update', $period) }}" method="post" class="form">
            @csrf @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label required">Nama Periode</label>
                    <input class="form-control" type="text" name="name" required value="{{ old('name', $period->name) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tanggal Mulai</label>
                    <input class="form-control" type="date" name="start_date" required value="{{ old('start_date', $period->start_date) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Tanggal Berakhir</label>
                    <input class="form-control" type="date" name="end_date" required value="{{ old('end_date', $period->end_date) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label required">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="Draft" @selected(old('status', $period->status) == 'Draft')>Draft</option>
                        <option value="Final" @selected(old('status', $period->status) == 'Final')>Final</option>
                    </select>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('payroll_period.index') }}" class="btn btn-warning">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>