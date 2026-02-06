<?php
// 1. Include file koneksi database
include 'koneksi.php';

// 2. Cek apakah parameter 'id' ada di URL dan apakah itu angka
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    
    // 3. Ambil ID dari URL
    $id_layanan = $_GET['id'];

    // 4. Siapkan query SQL untuk DELETE
    // Kita menggunakan Prepared Statements untuk keamanan
    $sql = "DELETE FROM layanan WHERE id_layanan = ?";
    
    // 5. Siapkan statement
    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        // 6. Bind parameter 'id' ke statement
        // 'i' berarti tipe datanya adalah Integer
        $stmt->bind_param("i", $id_layanan);
        
        // 7. Eksekusi statement
        if ($stmt->execute()) {
            // 8. Jika berhasil, redirect kembali ke halaman dashboard
            header("Location: admin-dashboard.php?status=sukses_hapus");
            exit;
        } else {
            // Jika eksekusi gagal
            echo "Error: Gagal menghapus data. " . $stmt->error;
        }
        
        // 9. Tutup statement
        $stmt->close();
        
    } else {
        // Jika persiapan statement gagal
        echo "Error: Gagal menyiapkan query. " . $koneksi->error;
    }

    // 10. Tutup koneksi
    $koneksi->close();

} else {
    // Jika ID tidak valid atau tidak ada, redirect kembali
    echo "ID tidak valid.";
    header("Location: admin-dashboard.php?status=error_id");
    exit;
}
?>