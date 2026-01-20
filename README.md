# Sistem Informasi Akademik MyKRS

## Deskripsi Singkat

Aplikasi ini merupakan sistem informasi akademik berbasis web yang digunakan untuk mengelola data mahasiswa, mata kuliah, dan KRS (Kartu Rencana Studi). Sistem ini memudahkan proses administrasi akademik di lingkungan perguruan tinggi.

## Rancangan dan Analisis

### Role User

- **Admin**: Mengelola data mahasiswa, mata kuliah(API), dan KRS.
- **Mahasiswa**: Melihat data KRS dan informasi akademik.

### Struktur Database

- **mahasiswa**: Menyimpan data mahasiswa (NIM, nama, jurusan, dll).
- **matakuliah**: Menyimpan data mata kuliah (kode, nama, SKS, dll).
- **krs**: Menyimpan data KRS mahasiswa.
- **user**: Menyimpan data login pengguna.

### DFD (Data Flow Diagram)

- **Level 0**: User login → Dashboard → Kelola Data (Mahasiswa, Mata Kuliah, KRS)
- **Level 1**: Admin dapat CRUD data, mahasiswa dapat melihat data KRS

### Fitur

- **Form Input**: Penambahan data mahasiswa, mata kuliah, dan KRS
- **Validasi**: Validasi input pada form (misal: NIM unik, format email, dll)
- **CRUD**: Create, Read, Update, Delete data mahasiswa, mata kuliah, dan KRS
- **Export**: Cetak/export data KRS ke PDF
- **Login**: Sistem autentikasi pengguna
- **Optional**: Webservice API untuk integrasi data (lihat file `api_matakuliah.php`)

## Fitur Utama

- Manajemen data mahasiswa
- Manajemen data mata kuliah
- Manajemen KRS
- Cetak KRS (PDF)
- Login dan logout
- Validasi data pada form

## Fitur Optional

- Webservice API untuk data mata kuliah
- Pengembangan fitur lain sesuai kebutuhan

## Cara Menjalankan

1. Clone repository ini
2. Import database dari file `akademik.sql`
3. Konfigurasi koneksi database di `koneksi.php`
4. Jalankan aplikasi di web server (misal: XAMPP)

## Struktur Folder

- `mahasiswa/` : Modul data mahasiswa
- `krs/` : Modul KRS
- `vendor/` : Library eksternal (FPDF untuk export PDF)

## Lisensi

Aplikasi ini bersifat open source dan dapat dikembangkan sesuai kebutuhan.
