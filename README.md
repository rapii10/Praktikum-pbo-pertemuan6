# Praktikum PBO Pertemuan 06 — Inheritance

Implementasi tugas praktikum PHP 8+ tentang pewarisan class (inheritance).

## Identitas
- Nama: Rafy Adilaputra
- NIM: 2530521060
- Kelas: 3 TI C

## Isi proyek
- `Mahasiswa.php`: parent abstract dengan property private dan getter.
- `MahasiswaReguler.php`: child untuk mahasiswa reguler.
- `MahasiswaBeasiswa.php`: child untuk mahasiswa beasiswa.
- `DataMahasiswa.php`: pengelolaan data tambah, cari, ubah, dan hapus.
- `index.php`: aplikasi CRUD mahasiswa berbasis PHP Session.
- `tugas2_pegawai.php`: implementasi inheritance untuk Pegawai Tetap dan Pegawai Kontrak.
- `contoh1_extends.php` sampai `contoh5_parent_trait.php`: eksperimen konsep pada modul.
- `assets/style.css`: tampilan aplikasi.

## Cara menjalankan
1. Pastikan XAMPP dan PHP 8+ sudah terpasang.
2. Salin folder `PBO_Pertemuan_06_Inheritance` ke `C:\xampp\htdocs\`.
3. Jalankan Apache dari XAMPP.
4. Buka `http://localhost/PBO_Pertemuan_06_Inheritance/`.
5. Tambahkan data reguler dan beasiswa untuk menguji perhitungan biaya.

## Aturan perhitungan
- Reguler: biaya akhir sama dengan UKT.
- Beasiswa: biaya akhir = UKT × (100 − persentase potongan) / 100.
- Data aplikasi disimpan dalam PHP Session, bukan database.

## Contoh pengujian
- Reguler, UKT Rp5.000.000 → biaya akhir Rp5.000.000.
- Beasiswa, UKT Rp5.000.000, potongan 60% → biaya akhir Rp2.000.000.
- Semester harus 1–14, UKT tidak boleh negatif, dan potongan harus 0–100%.
- NIM duplikat ditolak dan NIM tidak dapat diubah ketika mengedit data.

Catatan: laporan praktikum dibuat setelah aplikasi diunggah ke GitHub dan pengujian/screenshot dilakukan.
