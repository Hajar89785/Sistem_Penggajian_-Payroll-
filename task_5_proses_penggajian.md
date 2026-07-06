# Task 5: Proses Penggajian (Payroll Core)

## Deskripsi Task
Membuat sistem inti (core) untuk melakukan perhitungan gaji otomatis (Generate Payroll) berdasarkan data karyawan, master tunjangan/potongan yang ditetapkan, dan kehadiran.

## Instruksi Teknis
1. **Struktur Tabel:**
   - Buat tabel `payrolls` untuk mencatat transaksi total gaji per periode per karyawan.
   - Buat tabel `payroll_details` untuk mem-freeze (menyimpan secara statis) komponen rinci dari gaji saat itu (mencegah data berubah jika master data tunjangan/potongan diubah di masa depan).

2. **Logika Proses (Generate):**
   - Buat fungsi/service yang mengambil Gaji Pokok (dari jabatan atau override di profil karyawan).
   - Hitung total tunjangan, hitung total potongan.
   - Tambahkan potongan akibat tidak hadir (alpa) jika ada peraturannya.
   - Simpan kalkulasi ke dalam tabel `payrolls` dan detailnya di `payroll_details`.

3. **Pembuatan Seeder & Data Dummy:**
   - Karena modul ini bersifat transaksional, buat sebuah command / seeder `PayrollSeeder` yang bisa mensimulasikan proses generate gaji dari data attendance (Task 4) sehingga kita bisa melihat daftar riwayat penggajian secara instan di antarmuka.
