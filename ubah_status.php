<?php
session_start();
include 'koneksi.php';

// (opsional) cek admin
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
//     header("Location: admin-login.php");
//     exit;
// }

$id     = $_GET['id'] ?? 0;
$status = $_GET['status'] ?? '';

$allowed = ['pending','diproses','selesai','dibatalkan'];
if (!in_array($status, $allowed)) {
    die("Status tidak valid!");
}

$sql  = "UPDATE pemesanan SET status = ? WHERE id_pemesanan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {
    header("Location: admin-dashboard.php");
    exit;
} else {
    echo "Gagal mengupdate status.";
}
