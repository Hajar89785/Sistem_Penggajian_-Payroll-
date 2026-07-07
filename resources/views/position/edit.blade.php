<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('position.update', $position) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" required value="{{ old('name', $position->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="basic_salary" class="form-label required">Gaji Pokok</label>
                        <input class="form-control @error('basic_salary') is-invalid @enderror" type="number" id="basic_salary" name="basic_salary" required value="{{ old('basic_salary', $position->basic_salary) }}">
                        @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('position.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>