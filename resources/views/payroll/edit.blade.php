<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">
        <h4 class="mb-4">Edit Gaji Karyawan - {{ $payroll->employee->full_name }}</h4>
        
        <form action="{{ route('payroll.update', $payroll) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Karyawan</label>
                    <input type="text" class="form-control bg-light" value="{{ $payroll->employee->full_name }} ({{ $payroll->employee->employee_code }})" readonly>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gaji Pokok</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('basic_salary') is-invalid @enderror" name="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}" required>
                        @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Total Tunjangan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('total_allowances') is-invalid @enderror" name="total_allowances" value="{{ old('total_allowances', $payroll->total_allowances) }}" required>
                        @error('total_allowances') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Total Potongan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control @error('total_deductions') is-invalid @enderror" name="total_deductions" value="{{ old('total_deductions', $payroll->total_deductions) }}" required>
                        @error('total_deductions') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-3" style="font-size: 14px;">
                <i class="bx bx-info-circle"></i> Gaji Bersih (Net Salary) akan dikalkulasi ulang secara otomatis setelah Anda menyimpan berdasarkan pengurangan dan penambahan yang Anda berikan di atas.
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bx bx-save"></i> Simpan Perubahan</button>
                <a href="{{ route('payroll.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app>
