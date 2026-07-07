<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('employee.update', $employee) }}" method="post" class="form">
            @csrf
            @method('put')
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-user me-2'></i>Profil & Pekerjaan</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label required">Kode Karyawan / NIK</label>
                    <input class="form-control" type="text" name="employee_code" required value="{{ old('employee_code', $employee->employee_code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Nama Lengkap</label>
                    <input class="form-control" type="text" name="full_name" required value="{{ old('full_name', $employee->full_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', $employee->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input class="form-control" type="text" name="phone" value="{{ old('phone', $employee->phone) }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label required">Departemen</label>
                    <select class="form-select select2-default" name="department_id" required>
                        <option value="">Pilih Departemen</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" @selected(old('department_id', $employee->department_id) == $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Jabatan</label>
                    <select class="form-select select2-default" name="position_id" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($positions as $p)
                            <option value="{{ $p->id }}" @selected(old('position_id', $employee->position_id) == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Bergabung</label>
                    <input class="form-control" type="date" name="join_date" required value="{{ old('join_date', $employee->join_date) }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Akun User (Opsional)</label>
                    <select class="form-select select2-default" name="user_id">
                        <option value="">Tanpa Akun Login</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id', $employee->user_id) == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="mb-4">
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-wallet me-2'></i>Informasi Gaji & Bank</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok</label>
                    <input class="form-control" type="number" name="basic_salary" value="{{ old('basic_salary', $employee->basic_salary) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Bank</label>
                    <input class="form-control" type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Rekening</label>
                    <input class="form-control" type="text" name="bank_account" value="{{ old('bank_account', $employee->bank_account) }}">
                </div>
            </div>

            <hr class="mb-4">

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold text-success mb-3"><i class='bx bx-plus-circle me-2'></i>Tunjangan (Allowances)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Pilih</th>
                                    <th>Tunjangan</th>
                                    <th>Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allowances as $a)
                                    @php 
                                        $checked = $employee->allowances->contains($a->id);
                                        $amount = $checked ? $employee->allowances->find($a->id)->pivot->amount : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-allowance" type="checkbox" name="allowances[]" value="{{ $a->id }}" id="al-{{ $a->id }}" {{ $checked ? 'checked' : '' }}>
                                        </td>
                                        <td><label for="al-{{ $a->id }}">{{ $a->name }} ({{ $a->type }})</label></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-allowance" name="allowance_amounts[{{ $a->id }}]" value="{{ $amount }}" placeholder="0" {{ $checked ? 'required' : 'disabled' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="fw-bold text-danger mb-3"><i class='bx bx-minus-circle me-2'></i>Potongan (Deductions)</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Pilih</th>
                                    <th>Potongan</th>
                                    <th>Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deductions as $d)
                                    @php 
                                        $checked = $employee->deductions->contains($d->id);
                                        $amount = $checked ? $employee->deductions->find($d->id)->pivot->amount : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-deduction" type="checkbox" name="deductions[]" value="{{ $d->id }}" id="ded-{{ $d->id }}" {{ $checked ? 'checked' : '' }}>
                                        </td>
                                        <td><label for="ded-{{ $d->id }}">{{ $d->name }} ({{ $d->type }})</label></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-deduction" name="deduction_amounts[{{ $d->id }}]" value="{{ $amount }}" placeholder="0" {{ $checked ? 'required' : 'disabled' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="{{ route('employee.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        $('.check-allowance').change(function() {
            let input = $(this).closest('tr').find('.input-allowance');
            if(this.checked) {
                input.prop('disabled', false).prop('required', true);
            } else {
                input.prop('disabled', true).prop('required', false).val('');
            }
        });
        $('.check-deduction').change(function() {
            let input = $(this).closest('tr').find('.input-deduction');
            if(this.checked) {
                input.prop('disabled', false).prop('required', true);
            } else {
                input.prop('disabled', true).prop('required', false).val('');
            }
        });
    </script>
    @endpush
</x-app>