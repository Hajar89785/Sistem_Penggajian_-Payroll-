<?php
$entities = [
    'department' => ['title' => 'Departemen', 'fields' => ['name' => ['label' => 'Nama', 'type' => 'text'], 'description' => ['label' => 'Deskripsi', 'type' => 'textarea']]],
    'position' => ['title' => 'Jabatan', 'fields' => ['name' => ['label' => 'Nama', 'type' => 'text'], 'basic_salary' => ['label' => 'Gaji Pokok', 'type' => 'number']]],
    'allowance' => ['title' => 'Tunjangan', 'fields' => ['name' => ['label' => 'Nama', 'type' => 'text'], 'type' => ['label' => 'Tipe', 'type' => 'select_type']]],
    'deduction' => ['title' => 'Potongan', 'fields' => ['name' => ['label' => 'Nama', 'type' => 'text'], 'type' => ['label' => 'Tipe', 'type' => 'select_type']]],
];

foreach ($entities as $ent => $data) {
    $Ent = ucfirst($ent);
    $title = $data['title'];
    $fields = $data['fields'];

    // index.blade.php
    $th = ""; $td = "";
    foreach($fields as $k => $f) {
        $th .= "<th scope=\"col\">{$f['label']}</th>\n                        ";
        $td .= "<td>{{ \${$ent}->{$k} }}</td>\n                            ";
    }

    $index = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('{$ent}.create') }}" role="button">Tambah</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-100" id="data-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        {$th}<th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\${$ent}s as \${$ent})
                        <tr>
                            <td>{{ \$loop->iteration }}</td>
                            {$td}<td>
                                <button type="button" class="btn btn-info btn-sm btn-detail"
                                    data-route="{{ route('{$ent}.show', \${$ent}) }}">
                                    <i class='bx bx-show'></i>
                                </button>
                                <a href="{{ route('{$ent}.edit', \${$ent}) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('{$ent}.destroy', \${$ent}) }}">
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Detail {$title}</h1>
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

    // create.blade.php
    $inputs = "";
    foreach($fields as $k => $f) {
        $l = $f['label']; $t = $f['type'];
        if ($t == 'text' || $t == 'number') {
            $inputs .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label required">{$l}</label>
                        <input class="form-control @error('{$k}') is-invalid @enderror" type="{$t}" id="{$k}" name="{$k}" required value="{{ old('{$k}') }}">
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        } elseif ($t == 'textarea') {
            $inputs .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label">{$l}</label>
                        <textarea class="form-control @error('{$k}') is-invalid @enderror" id="{$k}" name="{$k}" rows="3">{{ old('{$k}') }}</textarea>
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        } elseif ($t == 'select_type') {
            $inputs .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label required">{$l}</label>
                        <select class="form-select @error('{$k}') is-invalid @enderror" id="{$k}" name="{$k}" required>
                            <option value="">Pilih {$l}</option>
                            <option value="Fixed" @selected(old('{$k}') == 'Fixed')>Fixed</option>
                            <option value="Variable" @selected(old('{$k}') == 'Variable')>Variable</option>
                        </select>
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        }
    }

    $create = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('{$ent}.store') }}" method="post" class="form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-12">
{$inputs}
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('{$ent}.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
EOT;

    // edit.blade.php
    $inputs_edit = "";
    foreach($fields as $k => $f) {
        $l = $f['label']; $t = $f['type'];
        if ($t == 'text' || $t == 'number') {
            $inputs_edit .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label required">{$l}</label>
                        <input class="form-control @error('{$k}') is-invalid @enderror" type="{$t}" id="{$k}" name="{$k}" required value="{{ old('{$k}', \${$ent}->{$k}) }}">
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        } elseif ($t == 'textarea') {
            $inputs_edit .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label">{$l}</label>
                        <textarea class="form-control @error('{$k}') is-invalid @enderror" id="{$k}" name="{$k}" rows="3">{{ old('{$k}', \${$ent}->{$k}) }}</textarea>
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        } elseif ($t == 'select_type') {
            $inputs_edit .= <<<EOT
                    <div class="mb-3">
                        <label for="{$k}" class="form-label required">{$l}</label>
                        <select class="form-select @error('{$k}') is-invalid @enderror" id="{$k}" name="{$k}" required>
                            <option value="">Pilih {$l}</option>
                            <option value="Fixed" @selected(old('{$k}', \${$ent}->{$k}) == 'Fixed')>Fixed</option>
                            <option value="Variable" @selected(old('{$k}', \${$ent}->{$k}) == 'Variable')>Variable</option>
                        </select>
                        @error('{$k}') <div class="invalid-feedback">{{ \$message }}</div> @enderror
                    </div>

EOT;
        }
    }

    $edit = <<<EOT
<x-app>
    <x-slot:title>{{ \$title }}</x-slot:title>
    <div class="card shadow-lg p-3">
        <form action="{{ route('{$ent}.update', \${$ent}) }}" method="post" class="form">
            @csrf
            @method('put')
            <div class="row g-3 mb-3">
                <div class="col-md-12">
{$inputs_edit}
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('{$ent}.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</x-app>
EOT;

    // show.blade.php
    $show_fields = "";
    foreach($fields as $k => $f) {
        $show_fields .= <<<EOT
            <div class="list-group-item px-0 border-0">
                <div class="row">
                    <div class="col-4 text-muted">{$f['label']}</div>
                    <div class="col-8 fw-semibold">{{ \${$ent}->{$k} }}</div>
                </div>
            </div>

EOT;
    }

    $show = <<<EOT
<div class="list-group list-group-flush">
{$show_fields}
</div>
EOT;

    file_put_contents(__DIR__ . "/resources/views/{$ent}/index.blade.php", $index);
    file_put_contents(__DIR__ . "/resources/views/{$ent}/create.blade.php", $create);
    file_put_contents(__DIR__ . "/resources/views/{$ent}/edit.blade.php", $edit);
    file_put_contents(__DIR__ . "/resources/views/{$ent}/show.blade.php", $show);
}
echo "Views generated successfully.\n";
