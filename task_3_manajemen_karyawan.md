# Task 3: Manajemen Karyawan (Employee Management)

## Deskripsi Task
Membuat fitur pengelolaan profil, detail pekerjaan, dan rincian gaji spesifik untuk tiap karyawan. Modul ini merupakan core profil yang menghubungkan user account dengan data penggajian.

## Instruksi Teknis
1. **Tabel & Relasi:**
   - Buat tabel `employees` sesuai struktur di PRD.
   - Buat tabel relasi/pivot: `employee_allowances` dan `employee_deductions`.
   - Pastikan relasi model Eloquent didefinisikan dengan benar antara `User`, `Department`, `Position`, dan master `Allowance`/`Deduction`.

2. **Fitur CRUD Karyawan:**
   - CRUD profil utama karyawan (termasuk rekening bank, tanggal bergabung, NIK/Employee Code).
   - Interface untuk menetapkan karyawan ke suatu Departemen dan Jabatan.
   - Interface untuk menetapkan (assign) besaran tunjangan dan potongan spesifik per karyawan.

3. **Pembuatan Seeder & Data Dummy:**
   - Buat `EmployeeSeeder` yang mengenerate minimal 10 karyawan dummy yang memiliki relasi lengkap (departemen, jabatan, tunjangan, potongan).
   - Pastikan ada karyawan yang terkait langsung dengan data `users` dari Task 1.
