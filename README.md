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

## Fitur Lainnya

- **Pencarian Data**: Tersedia fitur pencarian data mahasiswa berdasarkan NIM, nama, atau alamat.
- **Pagination**: Tampilan data mahasiswa menggunakan pagination dan limit per halaman agar lebih mudah dibaca.
- **Keamanan Session**: Setiap halaman penting dilindungi dengan session dan role user, sehingga hanya user yang berhak dapat mengakses fitur tertentu.
- **Responsive Design**: Tampilan aplikasi sudah mendukung perangkat mobile dan desktop dengan CSS responsif.
- **Konfirmasi & Validasi**: Terdapat konfirmasi sebelum menghapus data dan validasi pada setiap form input.
- **Logout**: Fitur logout untuk mengakhiri sesi pengguna.
- **Integrasi API**: Data mata kuliah pada KRS diambil dari API (`api_matakuliah.php`).
- **Cetak PDF**: KRS dapat dicetak langsung ke PDF dengan library FPDF.
- **Notifikasi**: Terdapat notifikasi (alert) pada aksi sukses/gagal seperti tambah, ubah, atau hapus data.
- **Auto Redirect**: Setelah login, user akan diarahkan ke halaman sesuai role.

Fitur-fitur ini mendukung kemudahan penggunaan, keamanan, dan fleksibilitas pengembangan aplikasi lebih lanjut.

## Cara Menjalankan

1. Clone repository ini
2. Import database dari file `akademik.sql`
3. Konfigurasi koneksi database di `koneksi.php`
4. Jalankan aplikasi di web server (misal: XAMPP)

## Struktur Folder

- `mahasiswa/` : Modul data mahasiswa
- `krs/` : Modul KRS
- `vendor/` : Library eksternal (FPDF untuk export PDF)
- `auth/` : Authentikasi
- `db/` : Koneksi database dan API

## Lisensi

Aplikasi ini bersifat open source dan dapat dikembangkan sesuai kebutuhan.
