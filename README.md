# Aplikasi Todo List (PHP Native) — CRUD + Autentikasi

## Deskripsi singkat
Aplikasi Todo List Student Planner adalah aplikasi web berbasis PHP native yang digunakan untuk membantu pengguna dalam mengelola tugas pribadi. Aplikasi ini menyediakan fitur autentikasi pengguna, manajemen tugas (CRUD), pencarian, serta filtrasi status tugas. Setiap data tugas terhubung langsung dengan akun pengguna sehingga keamanan dan privasi data tetap terjaga.

## Daftar anggota
- Ni Putu Risma Pradnya Maharani (240030133), @rismaPradnya
- Denis Saputri (240030096), @
- Anis Nabila (240030129), @
- Anak Agung Istri Sri Wangi Nariswari(240030378), @


## Lingkungan pengembangan
- PHP native
- MariaDB/MySQL
- Server lokal (PHP Built-in Server)
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


## Struktur folder todo-list app

├── assets/                 # Menyimpan file pendukung tampilan aplikasi
│   └── style.css           # File CSS untuk mengatur desain dan layout aplikasi
│
├── auth/                   # Modul autentikasi pengguna
│   ├── login.php           # Proses login pengguna
│   ├── register.php        # Proses registrasi pengguna (password di-hash)
│   └── logout.php          # Proses logout dan penghapusan session
│
├── tasks/                  # Modul pengelolaan tugas (CRUD)
│   ├── index.php           # Menampilkan daftar tugas milik pengguna
│   ├── add.php             # Menambahkan tugas baru
│   ├── edit.php            # Mengedit data tugas
│   └── delete.php          # Menghapus tugas
│
├── database.php             # Konfigurasi koneksi database MySQL/MariaDB
├── session.php              # Pengelolaan session dan proteksi halaman
├── hash.php                 # Fungsi pendukung untuk hashing password
├── index.php                # Halaman utama / dashboard aplikasi
├── todo_app.sql             # File SQL struktur database aplikasi
└── README.md                # Dokumentasi project



## Cara instalasi dan menjalankan aplikasi
1. Buat database dan tabel:
   ```sql
   CREATE DATABASE IF NOT EXISTS todo_app;
   USE todo_app;
   
   -- Tabel users & tasks ada di file SQL pada dokumentasi kode (lihat repository).
   
2. Lakukan konfigurasi koneksi database pada file database.php sesuai dengan pengaturan server lokal yang digunakan.
   
<?php
// Kode koneksi database terdapat pada file database.php
// dan disesuaikan dengan konfigurasi server lokal masing-masing
?>

3. Pastikan web server dan database server telah dijalankan, kemudian akses aplikasi melalui browser menggunakan alamat http://localhost/nama-folder-project. Apabila halaman login dapat ditampilkan tanpa error, maka aplikasi telah berhasil dijalankan.

## Alur penggunaan aplikasi
Pengguna terlebih dahulu melakukan registrasi akun untuk dapat menggunakan aplikasi. Setelah registrasi berhasil, pengguna melakukan login ke dalam sistem. Ketika sudah masuk, pengguna dapat mengelola data tugas sesuai dengan kebutuhan, mulai dari menambahkan tugas baru, mengubah data tugas, hingga menghapus tugas yang tidak diperlukan.

Setiap tugas yang telah diselesaikan dapat ditandai dengan mencentang kotak status selesai. Lalu setelah seluruh aktivitas selesai dilakukan, pengguna dapat keluar dari sistem dengan melakukan logout agar sesi penggunaan berakhir dengan aman.
