<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // kosongkan kalau phpMyAdmin tidak minta login
$DB_NAME = 'todo_app'; // ganti sesuai database yang anda buat

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die("Gagal koneksi MySQL: " . mysqli_connect_error());
}
?>
