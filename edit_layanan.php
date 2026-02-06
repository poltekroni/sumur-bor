<?php
// 1. Include file koneksi
include 'koneksi.php';

// 2. Cek apakah ada parameter ID di URL
if (!isset($_GET['id'])) {
    // Jika tidak ada ID, kembalikan ke dashboard
    header('Location: admin-dashboard.php');
    exit;
}

$id = $_GET['id'];

// 3. Ambil data spesifik dari database berdasarkan ID
$sql = "SELECT * FROM layanan WHERE id_layanan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id); // 'i' berarti parameter adalah integer
$stmt->execute();
$result = $stmt->get_result();

// 4. Cek apakah data ditemukan
if ($result->num_rows === 0) {
    echo "Data layanan tidak ditemukan.";
    exit;
}

// 5. Simpan data ke dalam variabel
$data = $result->fetch_assoc();

$stmt->close();
// Kita *belum* menutup $koneksi karena mungkin diperlukan lagi nanti
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Layanan - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css"> <style>
        /* Style ini sama dengan di dashboard, hanya untuk memastikan */
        main {
            padding-top: 100px;
            max-width: 900px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        .dashboard-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .dashboard-section h3 {
            color: #00bcd4;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <header>
        <nav>
            <div class="logo">Admin Panel</div>
            <ul>
                <li><a href="admin-dashboard.php">Kembali ke Dashboard</a></li>
                <li><a href="admin-login.html">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="dashboard-section">
            <h3>Edit Layanan</h3>
            <p>Anda sedang mengedit: <strong><?php echo htmlspecialchars($data['nama_layanan']); ?></strong></p>

            <form action="update_process.php" method="POST">
                
                <input type="hidden" name="id_layanan" value="<?php echo $data['id_layanan']; ?>">
                
                <div class="input-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="nama_layanan" value="<?php echo htmlspecialchars($data['nama_layanan']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Harga Layanan</label>
                    <input type="text" name="harga_layanan" value="<?php echo htmlspecialchars($data['harga_layanan']); ?>" required>
                </div>

                <div class="input-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required><?php echo htmlspecialchars($data['deskripsi']); ?></textarea>
                </div>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </section>
    </main>

</body>
</html>