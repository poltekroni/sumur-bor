<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM pelanggan WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $data = $result->fetch_assoc();

    if (password_verify($password, $data['password'])) {

        // 👉 DI SINI kode session disimpan
        $_SESSION['pelanggan_id']   = $data['id_pelanggan'];
        $_SESSION['pelanggan_nama'] = $data['nama_lengkap'];

        header("Location: pelanggan-dashboard.php");
        exit;

    } else {
        $_SESSION['login_error'] = "Password salah!";
        header("Location: login.php");
        exit;
    }

} else {
    $_SESSION['login_error'] = "Username tidak ditemukan!";
    header("Location: login.php");
    exit;
}
?>
