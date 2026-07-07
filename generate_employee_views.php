<?php
$index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('employee.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">NIK</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Departemen</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$employees as \$employee)
                        <tr>
                            <td>{{ \$loop->iteration }}</td>
                            <td>{{ \$employee->employee_code }}</td>
                            <td>{{ \$employee->full_name }}</td>
                            <td>{{ \$employee->department->name ?? '-' }}</td>
                            <td>{{ \$employee->position->name ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('employee.show', \$employee) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                <a href="{{ route('employee.edit', \$employee) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('employee.destroy', \$employee) }}">
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
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Detail Karyawan</h1>
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
EOT;

$create = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('employee.store') }}" method="post" class="form">
            @csrf
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-user me-2'></i>Profil & Pekerjaan</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label required">Kode Karyawan / NIK</label>
                    <input class="form-control" type="text" name="employee_code" required value="{{ old('employee_code') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Nama Lengkap</label>
                    <input class="form-control" type="text" name="full_name" required value="{{ old('full_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input class="form-control" type="text" name="phone" value="{{ old('phone') }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label required">Departemen</label>
                    <select class="form-select select2-default" name="department_id" required>
                        <option value="">Pilih Departemen</option>
                        @foreach(\$departments as \$d)
                            <option value="{{ \$d->id }}" @selected(old('department_id') == \$d->id)>{{ \$d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Jabatan</label>
                    <select class="form-select select2-default" name="position_id" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach(\$positions as \$p)
                            <option value="{{ \$p->id }}" @selected(old('position_id') == \$p->id)>{{ \$p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Bergabung</label>
                    <input class="form-control" type="date" name="join_date" required value="{{ old('join_date') }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Akun User (Opsional)</label>
                    <select class="form-select select2-default" name="user_id">
                        <option value="">Tanpa Akun Login</option>
                        @foreach(\$users as \$u)
                            <option value="{{ \$u->id }}" @selected(old('user_id') == \$u->id)>{{ \$u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="mb-4">
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-wallet me-2'></i>Informasi Gaji & Bank</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok (Kosongkan utk pakai Gaji Jabatan)</label>
                    <input class="form-control" type="number" name="basic_salary" value="{{ old('basic_salary') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Bank</label>
                    <input class="form-control" type="text" name="bank_name" value="{{ old('bank_name') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Rekening</label>
                    <input class="form-control" type="text" name="bank_account" value="{{ old('bank_account') }}">
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
                                    <th>Tipe</th>
                                    <th>Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\$allowances as \$a)
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-allowance" type="checkbox" name="allowances[]" value="{{ \$a->id }}" id="al-{{ \$a->id }}">
                                        </td>
                                        <td><label for="al-{{ \$a->id }}">{{ \$a->name }}</label></td>
                                        <td>{{ \$a->type }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-allowance" name="allowance_amounts[{{ \$a->id }}]" placeholder="0" disabled>
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
                                    <th>Tipe</th>
                                    <th>Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\$deductions as \$d)
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-deduction" type="checkbox" name="deductions[]" value="{{ \$d->id }}" id="ded-{{ \$d->id }}">
                                        </td>
                                        <td><label for="ded-{{ \$d->id }}">{{ \$d->name }}</label></td>
                                        <td>{{ \$d->type }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-deduction" name="deduction_amounts[{{ \$d->id }}]" placeholder="0" disabled>
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
EOT;

$edit = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('employee.update', \$employee) }}" method="post" class="form">
            @csrf
            @method('put')
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-user me-2'></i>Profil & Pekerjaan</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label required">Kode Karyawan / NIK</label>
                    <input class="form-control" type="text" name="employee_code" required value="{{ old('employee_code', \$employee->employee_code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label required">Nama Lengkap</label>
                    <input class="form-control" type="text" name="full_name" required value="{{ old('full_name', \$employee->full_name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email', \$employee->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input class="form-control" type="text" name="phone" value="{{ old('phone', \$employee->phone) }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label required">Departemen</label>
                    <select class="form-select select2-default" name="department_id" required>
                        <option value="">Pilih Departemen</option>
                        @foreach(\$departments as \$d)
                            <option value="{{ \$d->id }}" @selected(old('department_id', \$employee->department_id) == \$d->id)>{{ \$d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Jabatan</label>
                    <select class="form-select select2-default" name="position_id" required>
                        <option value="">Pilih Jabatan</option>
                        @foreach(\$positions as \$p)
                            <option value="{{ \$p->id }}" @selected(old('position_id', \$employee->position_id) == \$p->id)>{{ \$p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tanggal Bergabung</label>
                    <input class="form-control" type="date" name="join_date" required value="{{ old('join_date', \$employee->join_date) }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Akun User (Opsional)</label>
                    <select class="form-select select2-default" name="user_id">
                        <option value="">Tanpa Akun Login</option>
                        @foreach(\$users as \$u)
                            <option value="{{ \$u->id }}" @selected(old('user_id', \$employee->user_id) == \$u->id)>{{ \$u->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="mb-4">
            
            <h5 class="fw-bold text-primary mb-3"><i class='bx bx-wallet me-2'></i>Informasi Gaji & Bank</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Gaji Pokok</label>
                    <input class="form-control" type="number" name="basic_salary" value="{{ old('basic_salary', \$employee->basic_salary) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Bank</label>
                    <input class="form-control" type="text" name="bank_name" value="{{ old('bank_name', \$employee->bank_name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. Rekening</label>
                    <input class="form-control" type="text" name="bank_account" value="{{ old('bank_account', \$employee->bank_account) }}">
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
                                @foreach(\$allowances as \$a)
                                    @php 
                                        \$checked = \$employee->allowances->contains(\$a->id);
                                        \$amount = \$checked ? \$employee->allowances->find(\$a->id)->pivot->amount : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-allowance" type="checkbox" name="allowances[]" value="{{ \$a->id }}" id="al-{{ \$a->id }}" {{ \$checked ? 'checked' : '' }}>
                                        </td>
                                        <td><label for="al-{{ \$a->id }}">{{ \$a->name }} ({{ \$a->type }})</label></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-allowance" name="allowance_amounts[{{ \$a->id }}]" value="{{ \$amount }}" placeholder="0" {{ \$checked ? 'required' : 'disabled' }}>
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
                                @foreach(\$deductions as \$d)
                                    @php 
                                        \$checked = \$employee->deductions->contains(\$d->id);
                                        \$amount = \$checked ? \$employee->deductions->find(\$d->id)->pivot->amount : '';
                                    @endphp
                                    <tr>
                                        <td class="text-center">
                                            <input class="form-check-input check-deduction" type="checkbox" name="deductions[]" value="{{ \$d->id }}" id="ded-{{ \$d->id }}" {{ \$checked ? 'checked' : '' }}>
                                        </td>
                                        <td><label for="ded-{{ \$d->id }}">{{ \$d->name }} ({{ \$d->type }})</label></td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm input-deduction" name="deduction_amounts[{{ \$d->id }}]" value="{{ \$amount }}" placeholder="0" {{ \$checked ? 'required' : 'disabled' }}>
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
EOT;

$show = <<<EOT
<div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-3">Data Profil</h6>
        <table class="table table-sm table-borderless">
            <tr><th width="40%">NIK</th><td>: {{ \$employee->employee_code }}</td></tr>
            <tr><th>Nama</th><td>: {{ \$employee->full_name }}</td></tr>
            <tr><th>Email</th><td>: {{ \$employee->email ?? '-' }}</td></tr>
            <tr><th>Telepon</th><td>: {{ \$employee->phone ?? '-' }}</td></tr>
            <tr><th>Tanggal Join</th><td>: {{ \$employee->join_date }}</td></tr>
            <tr><th>Akun Login</th><td>: {{ \$employee->user->email ?? 'Tidak Ada' }}</td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-primary mb-3">Pekerjaan & Bank</h6>
        <table class="table table-sm table-borderless">
            <tr><th width="40%">Departemen</th><td>: {{ \$employee->department->name ?? '-' }}</td></tr>
            <tr><th>Jabatan</th><td>: {{ \$employee->position->name ?? '-' }}</td></tr>
            <tr><th>Gaji Pokok</th><td>: Rp {{ number_format(\$employee->basic_salary, 0, ',', '.') }}</td></tr>
            <tr><th>Bank</th><td>: {{ \$employee->bank_name ?? '-' }}</td></tr>
            <tr><th>No Rekening</th><td>: {{ \$employee->bank_account ?? '-' }}</td></tr>
        </table>
    </div>
</div>

<hr>

<div class="row mt-3">
    <div class="col-md-6">
        <h6 class="fw-bold text-success mb-2">Tunjangan Aktif</h6>
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr><th>Tunjangan</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                @forelse(\$employee->allowances as \$a)
                    <tr>
                        <td>{{ \$a->name }}</td>
                        <td class="text-end">Rp {{ number_format(\$a->pivot->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Tidak ada tunjangan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-danger mb-2">Potongan Aktif</h6>
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr><th>Potongan</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                @forelse(\$employee->deductions as \$d)
                    <tr>
                        <td>{{ \$d->name }}</td>
                        <td class="text-end">Rp {{ number_format(\$d->pivot->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center text-muted">Tidak ada potongan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
EOT;

mkdir(__DIR__ . '/resources/views/employee', 0777, true);
file_put_contents(__DIR__ . '/resources/views/employee/index.blade.php', $index);
file_put_contents(__DIR__ . '/resources/views/employee/create.blade.php', $create);
file_put_contents(__DIR__ . '/resources/views/employee/edit.blade.php', $edit);
file_put_contents(__DIR__ . '/resources/views/employee/show.blade.php', $show);
echo "Employee views generated successfully.\n";
