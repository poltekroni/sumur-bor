<?php
// Mulai session untuk baca pesan error
session_start();

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Berkah Air Rahayu</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            /* --- GAMBAR BACKGROUND FULL SCREEN --- */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                              url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1470&auto=format&fit=crop');
            
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- TOMBOL KEMBALI KE BERANDA --- */
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
            transform: translateX(5px);
        }

        .login-box {
            background: rgba(255, 255, 255, 0.95);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        h1 {
            color: #0e69c2;
            margin-bottom: 10px;
            font-size: 24px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #f5c6cb;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #0e69c2;
            outline: none;
            box-shadow: 0 0 8px rgba(14, 105, 194, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #0e69c2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #084a8a;
            transform: translateY(-2px);
        }

        .footer-text {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .footer-text a {
            color: #0e69c2;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsif untuk HP */
        @media (max-width: 480px) {
            .btn-back-home {
                top: 10px;
                left: 10px;
                padding: 8px 15px;
                font-size: 12px;
            }
            .login-box {
                margin: 0 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <a href="index.html" class="btn-back-home">← Kembali ke Beranda</a>

    <div class="login-box">
        <h1>Berkah Air Rahayu</h1>
        <p>Silakan login untuk mengakses layanan kami</p>

        <?php if (!empty($error_message)): ?>
            <div class="error-box">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login_process_pelanggan.php" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="footer-text">
            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
        </div>
    </div>

</body>
</html>