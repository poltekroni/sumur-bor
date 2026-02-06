<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $data = $result->fetch_assoc();

    if (password_verify($password, $data['password'])) {

        $_SESSION['user_id']   = $data['id_user'];
        $_SESSION['user_nama'] = $data['username'];

        header("Location: index.html");
        exit;

    } else {
        $_SESSION['login_error'] = "Password pelanggan salah!";
        header("Location: admin-dashboard.php");
        exit;
    }

} else {
    $_SESSION['login_error'] = "Username anda tidak ditemukan!";
    header("Location: login_process_admin.php");
    exit;
}
?>
