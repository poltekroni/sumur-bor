<?php
session_start();

// (Opsional) cek apakah benar2 admin
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
//     header("Location: admin-login.php");
//     exit;
// }

include 'koneksi.php';

/* =======================
   1. AMBIL DATA LAYANAN
   ======================= */
$sql_layanan = "SELECT id_layanan, nama_layanan, harga_layanan 
                FROM layanan 
                ORDER BY id_layanan DESC";
$result_layanan = $koneksi->query($sql_layanan);

/* =========================
   2. AMBIL DATA PEMESANAN
   ========================= */
$sql_pemesanan = "
    SELECT p.id_pemesanan,
           p.tanggal_pemesanan,
           p.status,
           pel.nama_lengkap,
           l.nama_layanan,
           l.harga_layanan
    FROM pemesanan p
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    JOIN layanan l     ON p.id_layanan   = l.id_layanan
    ORDER BY p.tanggal_pemesanan DESC
";
$result_pemesanan = $koneksi->query($sql_pemesanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Berkah Air Rahayu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style khusus dashboard */
        main {
            padding-top: 100px; /* Di bawah header */
            max-width: 1000px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        .dashboard-section {
            background-color: white;
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: left;
        }
        .dashboard-section h3 {
            color: #00bcd4;
            margin-bottom: 15px;
        }

        /* Form input */
        .dashboard-section form input,
        .dashboard-section form textarea {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .dashboard-section form button {
            padding: 10px 18px;
            border-radius: 8px;
            border: none;
            background-color: #00bcd4;
            color: #fff;
            cursor: pointer;
        }
        .dashboard-section form button:hover {
            background-color: #0097a7;
        }

        /* Tabel */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        /* Tombol aksi */
        .btn-update, .btn-delete, .btn-selesai {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
        }
        .btn-update {
            background-color: #ffc107; /* Kuning */
        }
        .btn-delete {
            background-color: #dc3545; /* Merah */
        }
        .btn-selesai {
            background-color: #28a745; /* Hijau */
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
        <div class="logo">Admin Panel</div>
        <ul>
            <li><a href="admin-dashboard.php" class="active">Dashboard</a></li>
            <li><a href="logout_admin.php">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <!-- ==================== FORM TAMBAH LAYANAN ==================== -->
    <section class="dashboard-section">
        <h3>Input Layanan Baru</h3>
        <form action="tambah_layanan.php" method="POST">
            <input type="text" name="nama_layanan" placeholder="Nama Layanan (misal: Pengecatan Dinding)" required>
            <input type="text" name="harga_layanan" placeholder="Harga (misal: Rp 50.000/m²)" required>
            <textarea name="deskripsi" placeholder="Deskripsi singkat" required></textarea>
            <button type="submit">Tambah Layanan</button>
        </form>
    </section>

    <!-- ==================== TABEL LAYANAN ==================== -->
    <section class="dashboard-section">
        <h3>Kelola Layanan (Update / Delete)</h3>
        <p>Data di bawah ini adalah data yang saat ini tampil di halaman 'Harga & Layanan'.</p>

        <table>
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result_layanan && $result_layanan->num_rows > 0) {
                while ($row = $result_layanan->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_layanan']); ?></td>
                        <td><?= htmlspecialchars($row['harga_layanan']); ?></td>
                        <td>
                            <a href="edit_layanan.php?id=<?= $row['id_layanan']; ?>" class="btn-update">Update</a>
                            <a href="hapus_layanan.php?id=<?= $row['id_layanan']; ?>"
                               class="btn-delete"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='3'>Belum ada data layanan yang ditambahkan.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </section>

    <!-- ==================== TABEL PEMESANAN + TOMBOL PROSES ==================== -->
    <section class="dashboard-section">
        <h3>Kelola Pemesanan</h3>
        <p>Daftar semua pemesanan dari pelanggan.</p>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($result_pemesanan && $result_pemesanan->num_rows > 0) {
                while ($p = $result_pemesanan->fetch_assoc()) {
                    // Tentukan badge status
                    $status = $p['status'];
                    $class  = 'status-pending';
                    if ($status == 'diproses')   $class = 'status-diproses';
                    elseif ($status == 'selesai')   $class = 'status-selesai';
                    elseif ($status == 'dibatalkan') $class = 'status-dibatalkan';
                    ?>
                    <tr>
                        <td><?= $p['tanggal_pemesanan']; ?></td>
                        <td><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                        <td><?= htmlspecialchars($p['nama_layanan']); ?></td>
                        <td>
                            <span class="status-badge <?= $class; ?>">
                                <?= ucfirst($status); ?>
                            </span>
                        </td>
                        <td>
                            <!-- Tombol PROSES → ubah status jadi 'diproses' -->
                            <a href="ubah_status.php?id=<?= $p['id_pemesanan']; ?>&status=diproses"
                               class="btn-update">
                                Diproses
                            </a>

                            <!-- Tombol SELESAI -->
                            <a href="ubah_status.php?id=<?= $p['id_pemesanan']; ?>&status=selesai"
                               class="btn-selesai">
                                Selesai
                            </a>

                            <!-- Tombol BATALKAN -->
                            <a href="ubah_status.php?id=<?= $p['id_pemesanan']; ?>&status=dibatalkan"
                               class="btn-delete"
                               onclick="return confirm('Yakin ingin membatalkan pemesanan ini?');">
                                Batalkan
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='5'>Belum ada pemesanan.</td></tr>";
            }

            // tutup koneksi
            $koneksi->close();
            ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>
