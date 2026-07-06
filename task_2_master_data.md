# Task 2: Manajemen Master Data

## Deskripsi Task
Membuat fitur pengelolaan (CRUD) untuk data master yang akan menjadi fondasi dari proses penggajian dan penugasan karyawan.

## Instruksi Teknis
1. **Tabel & Model yang Dibuat:**
   - `departments`: Master data divisi/departemen (kolom: id, name, description).
   - `positions`: Master data jabatan (kolom: id, name, basic_salary).
   - `allowances`: Master data tunjangan (kolom: id, name, type).
   - `deductions`: Master data potongan (kolom: id, name, type).

2. **Fitur CRUD & UI:**
   - Buat fungsi Create, Read, Update, Delete untuk masing-masing entitas master data tersebut.
   - Integrasikan dengan template antarmuka admin yang sudah ada.

3. **Pembuatan Seeder & Data Dummy:**
   - Wajib membuat class Seeder untuk `departments`, `positions`, `allowances`, dan `deductions`.
   - Isi dengan minimal 5 data dummy untuk masing-masing master data agar dapat melihat visualisasi data yang lengkap pada tampilan antarmuka.
