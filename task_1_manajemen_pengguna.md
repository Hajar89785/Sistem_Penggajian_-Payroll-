# Task 1: Manajemen Pengguna & Hak Akses (User Management)

## Deskripsi Task
Menyesuaikan sistem autentikasi dan manajemen pengguna yang sudah ada di Laravel agar selaras dengan kebutuhan di PRD. Tujuan utama adalah mengelola akses pengguna berdasarkan peran (role): `Superadmin`, `Admin`, dan `Employee`.

## Instruksi Teknis
1. **Analisis Kode Existing:**
   - Dilarang membuat pola baru yang tidak konsisten. Wajib mengikuti standar coding style, pola arsitektur, dan konvensi penamaan yang sudah ada (existing) pada modul user saat ini.

2. **Penyesuaian Struktur Tabel:**
   - Sesuaikan struktur tabel `users` (atau tabel terkait role) agar sesuai dengan PRD (memiliki tipe role: 'Superadmin', 'Admin', 'Employee').

3. **Penyesuaian Logika CRUD:**
   - Sesuaikan logika operasi Create, Read, Update, dan Delete (CRUD) pada Controller dan Repository/Model agar mengenali dan memproses pemilihan/perubahan role pengguna.
   - Sesuaikan tampilan (View) pengelolaan pengguna untuk menyertakan atribut role ini.

4. **Pembuatan Seeder & Data Dummy:**
   - Buat atau perbarui `UserSeeder`.
   - Wajib membuat data dummy untuk setiap role (`Superadmin`, `Admin`, `Employee`) agar bisa digunakan untuk testing dan melihat visualisasi antarmuka aplikasi.
