<?php
// 1. Include file koneksi
include 'koneksi.php';

// 2. Cek apakah request datang dari method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Ambil semua data dari form
    $id_layanan    = $_POST['id_layanan'];
    $nama_layanan  = $_POST['nama_layanan'];
    $harga_layanan = $_POST['harga_layanan'];
    $deskripsi     = $_POST['deskripsi'];

    // 4. Siapkan query SQL UPDATE
    // Gunakan prepared statements untuk keamanan
    $sql = "UPDATE layanan 
            SET nama_layanan = ?, harga_layanan = ?, deskripsi = ? 
            WHERE id_layanan = ?";
    
    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        // 5. Bind parameter ke query
        // "sssi" berarti: String, String, String, Integer
        $stmt->bind_param("sssi", $nama_layanan, $harga_layanan, $deskripsi, $id_layanan);
        
        // 6. Eksekusi query
        if ($stmt->execute()) {
            // 7. Jika berhasil, redirect (arahkan) kembali ke dashboard
            header("Location: admin-dashboard.php?status=update_sukses");
            exit;
        } else {
            // Jika eksekusi gagal
            echo "Error: Gagal mengupdate data. " . $stmt->error;
        }
        
        // 8. Tutup statement
        $stmt->close();
        
    } else {
        // Jika persiapan statement gagal
        echo "Error: Gagal menyiapkan query. " . $koneksi->error;
    }

    // 9. Tutup koneksi
    $koneksi->close();

} else {
    // Jika file diakses langsung tanpa submit form, kembalikan ke dashboard
    header("Location: admin-dashboard.php");
    exit;
}
?>