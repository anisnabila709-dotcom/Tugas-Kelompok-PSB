# Aplikasi Todo List (PHP Native) — CRUD + Autentikasi

## Deskripsi singkat
Aplikasi Todo List Student Planner adalah aplikasi web berbasis PHP native yang digunakan untuk membantu pengguna dalam mengelola tugas pribadi. Aplikasi ini menyediakan fitur autentikasi pengguna, manajemen tugas (CRUD), pencarian, serta filtrasi status tugas. Setiap data tugas terhubung langsung dengan akun pengguna sehingga keamanan dan privasi data tetap terjaga.

## Daftar anggota
- Ni Putu Risma Pradnya Maharani (240030133) 
- Denis Saputri (240030096) 
- Anis Nabila (240030129) 
- Anak Agung Istri Sri Wangi Nariswari(240030378)


## Lingkungan pengembangan
- PHP native
- MariaDB/MySQL
- Server lokal (XAMPP)
- HTML, CSS, JavaScript

## Hasil pengembangan (fitur utama)
1. Autentikasi 
- Registrasi pengguna dengan password yang di-hash menggunakan password_hash()
-Login menggunakan verifikasi password_verify()
-Logout dan penghapusan sesi
-Proteksi halaman agar hanya bisa diakses oleh pengguna yang sudah login
-Session timeout untuk meningkatkan keamanan

2. CRUD Tugas
- Menambahkan tugas baru (judul, deskripsi, tanggal jatuh tempo)
- Menampilkan daftar tugas milik pengguna
- Mengedit tugas
- Menghapus tugas
- Menandai tugas selesai maupun yang belum selesai

3. Filter & Pencarian
- Filter tugas berdasarkan status: Semua, Pending, dan Selesai
- Pencarian tugas berdasarkan judul atau deskripsi

4. Manajemen Session
- Session berbasis cookie yang lebih aman
- Otomatis logout jika tidak aktif selama waktu tertentu
- Setiap tugas terikat dengan user_id

5. Keamanan
- Menggunakan prepared statements (MySQLi & PDO) untuk seluruh query database guna mencegah serangan SQL Injection.
- Menerapkan sanitasi output menggunakan `htmlspecialchars()` saat menampilkan data ke halaman web untuk mencegah serangan Cross-Site Scripting (XSS).
- Password pengguna disimpan menggunakan metode hashing (`password_hash()` dan `password_verify()`).
- Manajemen sesi yang aman dengan session timeout dan proteksi halaman.


## Struktur folder


## Cara instalasi dan menjalankan aplikasi
1. Buat database dan tabel:
   ```sql
   CREATE DATABASE IF NOT EXISTS tugas_psb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE tugas_psb;
   -- Tabel users & tasks ada di file SQL pada dokumentasi kode (lihat repository).
