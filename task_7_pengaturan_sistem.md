# Task 7: Pengaturan Sistem (System Settings)

## Deskripsi Task
Menyediakan halaman untuk mengatur konfigurasi umum/dinamis aplikasi agar bisa disesuaikan tanpa menyentuh source code, seperti nama perusahaan, logo, dan penandatangan dokumen.

## Instruksi Teknis
1. **Struktur Tabel:**
   - Buat tabel `settings` dengan pola `key` dan `value` (atau sesuaikan dengan tabel existing dari admin template jika sudah ada).

2. **Fungsionalitas:**
   - Halaman pengaturan umum (Company Name, Company Address, Authorized Signatory Name, Signatory Position).
   - Form untuk update konfigurasi ini dan logika cache agar tidak selalu di-query setiap saat.

3. **Pembuatan Seeder & Data Dummy:**
   - Buat `SettingSeeder` dengan default value (contoh nama PT dummy dan alamat perusahaan) sehingga saat sistem pertama kali dijalankan, format laporan/slip gaji tidak kosong.
