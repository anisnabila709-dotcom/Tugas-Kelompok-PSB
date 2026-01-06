# Aplikasi Todo List (PHP Native) — CRUD + Autentikasi

## Deskripsi singkat
Aplikasi web sederhana untuk manajemen tugas dengan fitur **registrasi/login**, **session management**, dan **CRUD** tugas (judul, deskripsi, tanggal jatuh tempo), termasuk **filtrasi status** dan **pencarian**.

## Daftar anggota
- Ni Putu Risma Pradnya Maharani (240030133) 
- Denis Saputri (240030096) 
- Anis Nabila (240030129) 
- Anak Agung Istri Sri Wangi Nariswari(240030378)


## Lingkungan pengembangan
- PHP 8.x (native, tanpa framework)
- MariaDB/MySQL
- Server lokal (XAMPP/Laragon/WAMP)
- HTML, CSS, JavaScript

## Hasil pengembangan (fitur utama)
- Autentikasi: Register, Login, Logout, `password_hash/password_verify`, session timeout.
- CRUD Tugas: Create, Read (list), Update, Delete, toggle selesai/belum.
- Filtrasi: Berdasarkan status selesai/belum.
- Pencarian: Keyword pada judul/deskripsi.
- Keamanan: Prepared statements, sanitasi output (htmlspecialchars).

## Struktur folder


## Cara instalasi dan menjalankan aplikasi
1. Buat database dan tabel:
   ```sql
   CREATE DATABASE IF NOT EXISTS tugas_psb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE tugas_psb;
   -- Tabel users & tasks ada di file SQL pada dokumentasi kode (lihat repository).
