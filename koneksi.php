<?php
// Konfigurasi koneksi database
$db_host = "localhost";    // Biasanya "localhost" jika di XAMPP
$db_user = "root";         // User default XAMPP
$db_pass = "";             // Password default XAMPP (kosong)
$db_name = "Db-jasa";      // Nama database Anda

// Membuat koneksi
$koneksi = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi ke database gagal: " . $koneksi->connect_error);
}

// Jika ingin menggunakan karakter set utf8 (disarankan)
$koneksi->set_charset("utf8");

?>