# SIINBE - Sistem Informasi Inventaris Barang Elektronik

## Deskripsi
SIINBE adalah aplikasi web untuk manajemen inventaris barang elektronik di SMK Negeri 7 Baleendah. Aplikasi ini memudahkan pencatatan, pelacakan, dan pelaporan data barang, lokasi, produk, serta pengguna.

## Fitur Utama
- **Manajemen Barang:** Tambah, edit, hapus, dan lihat detail barang beserta status (Aktif, Perlu Servis, Rusak Ringan, Rusak).
- **Manajemen Lokasi:** Kelola data lokasi penyimpanan barang.
- **Manajemen Produk:** Tambah, lihat, dan kelola data produk.
- **Manajemen Pengguna:** Kelola user yang dapat mengakses aplikasi.
- **Cetak Laporan:** Cetak data barang, lokasi, dan produk dalam format siap print.
- **Dark Mode:** Tampilan aplikasi dapat diubah ke mode gelap untuk kenyamanan mata.
- **Login & Logout:** Sistem autentikasi untuk keamanan data.

## Cara Instalasi
1. **Clone/download** project ini ke folder web server (misal: `htdocs` di XAMPP).
2. **Import database**:
   - Buka `phpMyAdmin`.
   - Buat database baru (misal: `siinbe`).
   - Import file `database.sql` ke database tersebut.
3. **Konfigurasi koneksi database**:
   - Edit file `config.php` jika perlu, sesuaikan user, password, dan nama database.
4. **Jalankan aplikasi**:
   - Buka browser dan akses `http://localhost/NAMA_FOLDER_PROJECT`.

## Cara Menggunakan
### 1. Login
- Masukkan email dan password yang sudah terdaftar.
- Jika belum punya akun, minta admin untuk mendaftarkan.

### 2. Manajemen Data
- **Barang:**
  - Klik menu "Barang" untuk melihat daftar barang.
  - Gunakan tombol tambah (+) untuk menambah barang baru.
  - Edit atau hapus barang dengan tombol aksi di tabel.
  - Status barang: Aktif, Perlu Servis, Rusak Ringan, Rusak.
- **Lokasi:**
  - Klik menu "Lokasi" untuk kelola lokasi penyimpanan.
  - Tambah, edit, atau hapus lokasi sesuai kebutuhan.
- **Produk:**
  - Klik menu "Produk" untuk kelola data produk.
  - Tambah produk baru melalui form yang tersedia.
- **Pengguna:**
  - Hanya admin yang dapat menambah/menghapus user.

### 3. Cetak Laporan
- Di halaman Barang, Lokasi, atau Produk, klik tombol "Cetak" untuk membuka tampilan siap print.
- Tekan tombol "Cetak" di halaman tersebut untuk print atau simpan PDF.

### 4. Dark Mode
- Klik ikon bulan/matahari di pojok kanan atas untuk mengaktifkan atau menonaktifkan dark mode.

## Tips
- Gunakan browser modern (Chrome, Edge, Firefox) untuk tampilan terbaik.
- Logout setelah selesai menggunakan aplikasi untuk keamanan data.

## Kontak Pengembang
Jika ada pertanyaan, bug, atau permintaan fitur, silakan hubungi:
- Email: admin@smkn7baleendah.sch.id

---
Aplikasi ini dikembangkan untuk mendukung digitalisasi inventaris di lingkungan SMK Negeri 7 Baleendah.