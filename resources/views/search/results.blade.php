<x-app>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="page-title-box mb-4">
        <h3 class="fw-bold" style="color: var(--payflow-text-dark); font-size: 22px;">Hasil Pencarian: "{{ $search }}"</h3>
        <p style="color: var(--payflow-text-muted); font-size: 13px;">Ditemukan {{ $employees->count() }} karyawan dan {{ $positions->count() }} jabatan terkait.</p>
    </div>

    <!-- Karyawan Results -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color: var(--payflow-text-dark); font-size: 16px;">
                <i class="bx bx-group" style="color: var(--payflow-purple);"></i> Data Karyawan ({{ $employees->count() }})
            </h5>
            
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead style="border-bottom: 1px solid #EBECEF;">
                        <tr>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">NIK</th>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Nama</th>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Departemen</th>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Jabatan</th>
                            <th class="text-end" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr style="border-bottom: 1px solid #F4F5F9;">
                            <td style="font-size: 13px; font-weight: 600; color: var(--payflow-text-dark);">{{ $employee->employee_code }}</td>
                            <td style="font-size: 13px; font-weight: 600; color: var(--payflow-text-dark);">{{ $employee->full_name }}</td>
                            <td style="font-size: 13px; color: var(--payflow-text-dark);">{{ $employee->department->name ?? '-' }}</td>
                            <td style="font-size: 13px; color: var(--payflow-text-dark);">{{ $employee->position->name ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('employee.index') }}" class="btn btn-sm btn-light border p-1 rounded" title="Lihat Karyawan"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4" style="font-size: 13px;">Tidak ada karyawan yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Jabatan Results -->
    @if($positions->count() > 0)
    <div class="card shadow-sm border-0" style="border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3" style="color: var(--payflow-text-dark); font-size: 16px;">
                <i class="bx bx-briefcase" style="color: var(--payflow-purple);"></i> Data Jabatan ({{ $positions->count() }})
            </h5>
            
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead style="border-bottom: 1px solid #EBECEF;">
                        <tr>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Nama Jabatan</th>
                            <th style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Gaji Pokok</th>
                            <th class="text-end" style="color: var(--payflow-text-muted); font-size: 11px; text-transform: uppercase;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($positions as $position)
                        <tr style="border-bottom: 1px solid #F4F5F9;">
                            <td style="font-size: 13px; font-weight: 600; color: var(--payflow-text-dark);">{{ $position->name }}</td>
                            <td style="font-size: 13px; font-weight: 600; color: var(--payflow-text-dark);">Rp {{ number_format($position->basic_salary, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('position.index') }}" class="btn btn-sm btn-light border p-1 rounded" title="Lihat Jabatan"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4" style="font-size: 13px;">Tidak ada jabatan yang cocok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</x-app>
