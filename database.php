<?php
// Konfigurasi MySQL kamu
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = 'jungis';
$DB_NAME = 'tugas_psb';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die('Gagal koneksi MySQL: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');