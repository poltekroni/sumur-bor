<?php
session_start();

// 🔐 Cek apakah pelanggan sudah login
if (!isset($_SESSION['pelanggan_id'])) {
    // Kalau belum login, arahkan ke login pelanggan
    header("Location: login.php"); // sesuaikan jika nama file login pelanggan beda
    exit;
}

$id_pelanggan  = $_SESSION['pelanggan_id'];
$nama_pelanggan = isset($_SESSION['pelanggan_nama']) ? $_SESSION['pelanggan_nama'] : 'Pelanggan';

include 'koneksi.php';

$success_message = "";
$error_message   = "";

// 📝 Proses jika form pemesanan dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_layanan = $_POST['id_layanan'] ?? '';
    $catatan    = trim($_POST['catatan'] ?? '');

    if ($id_layanan == '') {
        $error_message = "Silakan pilih layanan yang ingin dipesan.";
    } else {
        // Simpan pemesanan ke tabel pemesanan
        $stmt = $koneksi->prepare(
            "INSERT INTO pemesanan (id_pelanggan, id_layanan, catatan, status) 
             VALUES (?, ?, ?, 'pending')"
        );
        $stmt->bind_param("iis", $id_pelanggan, $id_layanan, $catatan);

        if ($stmt->execute()) {
            $success_message = "Pemesanan berhasil dikirim. Status awal: pending.";
        } else {
            $error_message = "Terjadi kesalahan saat menyimpan pemesanan.";
        }

        $stmt->close();
    }
}

// 📦 Ambil daftar layanan untuk dropdown
$layanan_result = $koneksi->query("SELECT id_layanan, nama_layanan, harga_layanan FROM layanan ORDER BY nama_layanan ASC");

// 📜 Ambil riwayat pemesanan pelanggan
$sql_pemesanan = "
    SELECT p.id_pemesanan, p.tanggal_pemesanan, p.status, p.catatan,
           l.nama_layanan, l.harga_layanan
    FROM pemesanan p
    JOIN layanan l ON p.id_layanan = l.id_layanan
    WHERE p.id_pelanggan = ?
    ORDER BY p.tanggal_pemesanan DESC
";
$stmt_riwayat = $koneksi->prepare($sql_pemesanan);
$stmt_riwayat->bind_param("i", $id_pelanggan);
$stmt_riwayat->execute();
$riwayat_result = $stmt_riwayat->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - Berkah Air Rahayu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        main {
            padding-top: 100px;
            max-width: 900px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        .dashboard-section {
            background-color: #ffffff;
            padding: 25px 30px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: left;
        }
        .dashboard-section h3 {
            color: #00bcd4;
            margin-bottom: 15px;
        }
        .message {
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .message.error {
            background: #ffdddd;
            border: 1px solid #ff5c5c;
            color: #b30000;
        }
        .message.success {
            background: #ddffdd;
            border: 1px solid #3bb54a;
            color: #1d7a2b;
        }
        form .form-group {
            margin-bottom: 12px;
        }
        form label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }
        form select,
        form textarea {
            width: 100%;
            padding: 9px 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        form textarea {
            resize: vertical;
            min-height: 70px;
        }
        .btn-submit {
            padding: 9px 18px;
            border-radius: 8px;
            border: none;
            background-color: #00bcd4;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            margin-top: 8px;
        }
        .btn-submit:hover {
            background-color: #0097a7;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 9px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            display: inline-block;
        }
        .status-pending {
            background: #fff8e1;
            color: #ff9800;
        }
        .status-diproses {
            background: #e3f2fd;
            color: #2196f3;
        }
        .status-selesai {
            background: #e8f5e9;
            color: #4caf50;
        }
        .status-dibatalkan {
            background: #ffebee;
            color: #f44336;
        }
    </style>
</head>
<body>

<header>
    <nav>
        <div class="logo">Berkah Air Rahayu</div>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="price.php">Harga & Layanan</a></li>
            <li><a href="pelanggan-dashboard.php" class="active">Dashboard Pelanggan</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- buatkan logout jika belum ada -->
        </ul>
    </nav>
</header>

<main>

    <section class="dashboard-section">
        <h3>Selamat datang, <?= htmlspecialchars($nama_pelanggan); ?> 👋</h3>
        <p>Silakan pilih layanan yang ingin Anda pesan.</p>

        <?php if (!empty($error_message)): ?>
            <div class="message error"><?= $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Pilih Layanan</label>
                <select name="id_layanan" required>
                    <option value="">-- Pilih Layanan --</option>
                    <?php if ($layanan_result && $layanan_result->num_rows > 0): ?>
                        <?php while($row = $layanan_result->fetch_assoc()): ?>
                            <option value="<?= $row['id_layanan']; ?>">
                                <?= htmlspecialchars($row['nama_layanan']); ?> 
                                - (<?= htmlspecialchars($row['harga_layanan']); ?>)
                            </option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="">Belum ada layanan tersedia</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Tambahan (opsional)</label>
                <textarea name="catatan" placeholder="Contoh: Mohon datang sore hari, rumah di gang sebelah warung..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Kirim Pemesanan</button>
        </form>
    </section>

    <section class="dashboard-section">
        <h3>Riwayat Pemesanan Anda</h3>

        <?php if ($riwayat_result && $riwayat_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal Pesan</th>
                        <th>Layanan</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($p = $riwayat_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $p['tanggal_pemesanan']; ?></td>
                        <td><?= htmlspecialchars($p['nama_layanan']); ?></td>
                        <td><?= htmlspecialchars($p['harga_layanan']); ?></td>
                        <td>
                            <?php
                            $status = $p['status'];
                            $class = 'status-pending';
                            if ($status == 'diproses') $class = 'status-diproses';
                            elseif ($status == 'selesai') $class = 'status-selesai';
                            elseif ($status == 'dibatalkan') $class = 'status-dibatalkan';
                            ?>
                            <span class="status-badge <?= $class; ?>">
                                <?= ucfirst($status); ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($p['catatan']); ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Anda belum memiliki pemesanan.</p>
        <?php endif; ?>
    </section>

</main>

</body>
</html>
