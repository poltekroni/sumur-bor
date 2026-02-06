<?php
// 1. Include file koneksi database
include 'koneksi.php';

// 2. Cek apakah form telah disubmit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Ambil data dari form
    // Kita menggunakan 'isset' untuk memastikan data ada
    $nama_layanan = isset($_POST['nama_layanan']) ? $_POST['nama_layanan'] : '';
    $harga_layanan = isset($_POST['harga_layanan']) ? $_POST['harga_layanan'] : '';
    $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : '';

    // Validasi sederhana (pastikan tidak kosong)
    if (empty($nama_layanan) || empty($harga_layanan) || empty($deskripsi)) {
        echo "Data tidak lengkap. Silakan isi semua field.";
        echo "<br><a href='admin-dashboard.php'>Kembali</a>";
        exit;
    }

    // 4. Siapkan query SQL untuk INSERT data
    // Kita menggunakan Prepared Statements untuk keamanan dari SQL Injection
    // Kolom 'id_layanan' tidak perlu dimasukkan karena diasumsikan AUTO_INCREMENT
    $sql = "INSERT INTO layanan (nama_layanan, harga_layanan, deskripsi) VALUES (?, ?, ?)";
    
    // 5. Siapkan statement
    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        // 6. Bind parameter ke statement
        // "sss" berarti ketiga variabel adalah Tipe String.
        $stmt->bind_param("sss", $nama_layanan, $harga_layanan, $deskripsi);
        
        // 7. Eksekusi statement
        if ($stmt->execute()) {
            // 8. Jika berhasil, redirect (arahkan) kembali ke halaman dashboard
            // Ini membuat admin tahu bahwa datanya sudah masuk
            header("Location: admin-dashboard.php?status=sukses");
            exit;
        } else {
            // Jika eksekusi gagal
            echo "Error: Gagal menyimpan data. " . $stmt->error;
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
    // Jika file diakses langsung tanpa submit form
    echo "Akses tidak sah.";
    header("Location: admin-dashboard.php?status=sukses");
    exit;
}
?>