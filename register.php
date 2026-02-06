<?php
// Mulai session untuk menampilkan pesan
session_start();

// Panggil file koneksi (Pastikan file koneksi.php sudah ada di folder yang sama)
require_once "koneksi.php"; 

$success_message = "";
$error_message   = "";

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama     = trim($_POST['nama']);
    $no_hp    = trim($_POST['no_hp']);
    $email    = trim($_POST['email']);
    $alamat   = trim($_POST['alamat']);

    if ($username == "" || $password == "" || $nama == "" || $no_hp == "" || $email == "" || $alamat == "") {
        $error_message = "Semua field wajib diisi!";
    } else {

    $no_hp = trim($_POST['no_hp']);

if (!preg_match('/^(08)[0-9]{8,11}$/', $no_hp)) {
    $_SESSION['error'] = "Nomor HP tidak valid. Gunakan format 08xxxxxxxxxx";
    header("Location: register.php");
    exit;
}

$password = $_POST['password'];

if (!preg_match('/^(?=.*[A-Z])(?=.*[\W_]).{8,}$/', $password)) {
    $_SESSION['error'] = "Password harus minimal 8 karakter, mengandung 1 huruf kapital dan 1 karakter spesial.";
    header("Location: register.php");
    exit;
}

        $cek = $koneksi->prepare("SELECT id_pelanggan FROM pelanggan WHERE username = ? OR email = ?");
        $cek->bind_param("ss", $username, $email);
        $cek->execute();
        $hasil = $cek->get_result();

        if ($hasil->num_rows > 0) {
            $error_message = "Username atau Email sudah terdaftar!";
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $koneksi->prepare("INSERT INTO pelanggan (username, password, nama_lengkap, no_hp, email, alamat) VALUES (?, ?, ?, ?, ?, ?)");
            $ins->bind_param("ssssss", $username, $password_hash, $nama, $no_hp, $email, $alamat);
            
            if ($ins->execute()) {
                $success_message = "Pendaftaran berhasil! Silakan login.";
                $username = $nama = $no_hp = $email = $alamat = "";
            } else {
                $error_message = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Berkah Air Rahayu</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* MENAMBAHKAN GAMBAR BACKGROUND */
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                              url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* TOMBOL KEMBALI KE BERANDA */
        .btn-back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            background: #ffffff;
            color: #0e69c2;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: 0.3s;
            z-index: 1000;
        }

        .btn-back-home:hover {
            background: #0e69c2;
            color: white;
            transform: scale(1.05);
        }

        .register-box {
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            max-height: 90vh;
        }

        h2 {
            text-align: center;
            color: #0e69c2;
            margin-bottom: 20px;
        }

        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea { height: 60px; resize: none; }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #0e69c2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #084a8a;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .footer-text a {
            color: #0e69c2;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <a href="index.html" class="btn-back-home">← Kembali ke Beranda</a>

    <div class="register-box">
        <h2>Daftar Akun</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-error"><?= $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= $success_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= isset($username)?htmlspecialchars($username):''; ?>" required>
            </div>

           <div class="input-group">
    <label>Password</label>
    <input 
        type="password" 
        name="password" 
        required
        minlength="8"
        pattern="^(?=.*[A-Z])(?=.*[\W_]).{8,}$"
        placeholder="Minimal 8 karakter, 1 huruf kapital & 1 karakter spesial"
        title="Password minimal 8 karakter, mengandung 1 huruf kapital dan 1 karakter spesial"
    >
</div>


            <div class="input-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= isset($nama)?htmlspecialchars($nama):''; ?>" required>
            </div>

            <div class="input-group">
    <label>No HP</label>
    <input 
        type="tel"
        name="no_hp"
        value="<?= isset($no_hp) ? htmlspecialchars($no_hp) : ''; ?>"
        required
        pattern="^(08)[0-9]{8,11}$"
        placeholder="Contoh: 081234567890"
        title="Masukkan nomor HP yang valid (diawali 08, 10–13 digit)"
    >
</div>


            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= isset($email)?htmlspecialchars($email):''; ?>" required>
            </div>

            <div class="input-group">
                <label>Alamat</label>
                <textarea name="alamat" required><?= isset($alamat)?htmlspecialchars($alamat):''; ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>

            <div class="footer-text">
                Sudah punya akun? <a href="login.php">Login di sini</a>
            </div>
        </form>
    </div>

</body>
</html>