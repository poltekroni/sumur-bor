<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harga & Layanan - Berkah Air Rahayu</title>
    <link rel="stylesheet" href="style.css">
    <style>
    body {
        /* Menambahkan gambar latar belakang rumah minimalis */
        background-image: linear-gradient(rgba(255, 255, 255, 0.10), rgba(255, 255, 255, 0.85)), 
                          url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1470&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }

    /* Membuat kartu layanan sedikit transparan agar gambar latar terlihat estetik */
    .card {
        background: rgba(255, 255, 255, 0.9) !important;
        border: 1px solid #ddd;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>
</head>
<body>

    <header>
        <nav>
            <div class="logo">Berkah Air Rahayu</div>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="price.html" class="active">Harga & Layanan</a></li>
                <li><a href="about.html">Tentang Saya</a></li>
                <li><a href="contact.html">Kontak</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="price">
            <h2>Harga & Layanan</h2>
            <div class="services">
    
    <?php
    // 1. Include file koneksi
    include 'koneksi.php';

    // 2. Buat query untuk mengambil semua data (termasuk deskripsi)
    $sql_public = "SELECT nama_layanan, harga_layanan, deskripsi FROM layanan ORDER BY id_layanan ASC";
    $result_public = $koneksi->query($sql_public);

    // 3. Cek dan looping data
    if ($result_public->num_rows > 0) {
        while($row_public = $result_public->fetch_assoc()) {
            // 4. Tampilkan data dalam format <div class="card">
            echo '<div class="card">';
            echo '<h3>' . htmlspecialchars($row_public['nama_layanan']) . '</h3>';
            echo '<p>' . htmlspecialchars($row_public['harga_layanan']) . '</p>';
            echo '<p>' . htmlspecialchars($row_public['deskripsi']) . '</p>';
            echo '</div>';
        }
    } else {
        // Jika tidak ada layanan di database
        echo "<p>Saat ini belum ada layanan yang tersedia.</p>";
    }
    
    // 5. Tutup koneksi
    $koneksi->close();
    ?>

</div>
        </section>
    </main>


</body>
</html>
