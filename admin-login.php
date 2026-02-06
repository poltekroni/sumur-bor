<?php
// Mulai session untuk baca pesan error dari login_process.php
session_start();

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // hapus setelah ditampilkan
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Berkah Air Rahayu</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            
            /* --- BAGIAN YANG DIUBAH: MENAMBAHKAN GAMBAR BACKGROUND --- */
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                              url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* --------------------------------------------------------- */
            
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95); /* Sedikit transparan agar estetik */
            width: 380px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.7s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #f5c6cb;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        .input-group input:focus {
            border-color: #0e69c2;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #0e69c2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: #084a8a;
        }

        .footer-text {
            text-align: center;
            margin-top: 18px;
            font-size: 14px;
            color: #555;
        }

        .footer-text a {
            color: #0e69c2;
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login Admin</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-box">
                <?= htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <form action="login_process_admin.php" method="POST">
            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan Username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit" class="btn-login">Masuk Sebagai Admin</button>
        </form>

        <div class="footer-text">
            <a href="index.html">← Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>