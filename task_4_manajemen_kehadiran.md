# Task 4: Manajemen Kehadiran (Attendance)

## Deskripsi Task
Membuat modul untuk merekapitulasi data kehadiran dan jam lembur karyawan pada suatu periode, yang nantinya akan dijadikan dasar perhitungan pemotongan absensi atau penambahan tunjangan lembur.

## Instruksi Teknis
1. **Struktur Tabel:**
   - Buat tabel `payroll_periods` untuk menyimpan referensi periode (misal: "Gaji Januari 2026", start_date, end_date).
   - Buat tabel `attendances` yang merelasikan karyawan dengan periode penggajian beserta detail hari masuk, sakit, izin, alpa, dan jam lembur.

2. **Fungsionalitas:**
   - Form input manual / bulk upload data kehadiran untuk karyawan berdasarkan periode.
   - Validasi agar data kehadiran untuk karyawan di periode yang sama tidak terduplikasi.

3. **Pembuatan Seeder & Data Dummy:**
   - Buat data dummy untuk `PayrollPeriodSeeder` (minimal 2-3 bulan periode penggajian).
   - Buat data dummy untuk `AttendanceSeeder` yang di-assign ke karyawan dummy dari Task 3.
