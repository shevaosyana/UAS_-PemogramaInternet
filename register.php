<?php
require_once 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    // Validasi sederhana
    if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
        $message = 'Semua field harus diisi!';
    } elseif ($password !== $confirm_password) {
        $message = 'Konfirmasi password tidak cocok!';
    } else {
        // Cek apakah username/email sudah ada
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $message = 'Username atau email sudah terdaftar!';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Simpan user baru
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            if ($stmt->execute([$username, $email, $hashedPassword])) {
                header('Location: login.php?register=success');
                exit();
            } else {
                $message = 'Registrasi gagal, coba lagi.';
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
    <title>Register - SIINBE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f6f8fc;
            color: #222;
            transition: background 0.3s, color 0.3s;
            overflow-x: hidden;
        }
        .topbar {
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            padding: 0 36px;
            box-sizing: border-box;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }
        .topbar .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 2px;
        }
        .topbar .logo img {
            height: 40px;
            margin-right: 14px;
            border-radius: 50%;
            background: #fff;
            padding: 2px;
        }
        .container {
            display: flex;
            margin-top: 64px;
            min-height: calc(100vh - 64px);
        }
        .sidebar {
            width: 220px;
            background: #23283a;
            color: #e2e6ef;
            border-right: 1.5px solid #23283a;
            min-height: calc(100vh - 64px);
            box-shadow: 2px 0 8px rgba(102,126,234,0.04);
            padding-top: 24px;
        }
        .sidebar .logo-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .sidebar .logo-wrap img {
            height: 60px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .sidebar li {
            display: flex;
            align-items: center;
            padding: 12px 28px;
            border-radius: 8px;
            margin-bottom: 6px;
            color: #e2e6ef;
            font-size: 1.08rem;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
        }
        .sidebar li.active, .sidebar li:hover {
            background: linear-gradient(90deg, #e0e7ff 60%, #f3e8ff 100%);
            color: #4b5bdc;
        }
        .sidebar li i {
            margin-right: 14px;
            font-size: 1.18rem;
        }
        .main-content {
            flex: 1;
            padding: 32px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 64px);
            background: #23283a;
        }
        .register-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 36px 32px 28px 32px;
            min-width: 320px;
            max-width: 400px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .register-card h2 {
            margin-top: 0;
            color: #4b5bdc;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .register-card form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .register-card input[type="text"], .register-card input[type="email"], .register-card input[type="password"] {
            padding: 10px 12px;
            border-radius: 8px;
            border: 1.2px solid #ececec;
            font-size: 1rem;
            outline: none;
        }
        .register-card button {
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 0;
            font-size: 1.08rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s;
        }
        .register-card button:hover {
            background: linear-gradient(90deg, #764ba2 60%, #667eea 100%);
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">
            <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah">
            SIINBE
        </div>
    </div>
    <div class="container">
        <div class="sidebar">
            <div class="logo-wrap">
                <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah">
            </div>
            <ul>
                <li onclick="window.location='users.php'" class="active"><i class="fa fa-user"></i> Pengguna</li>
                <li onclick="window.location='barang.php'"><i class="fa fa-box"></i> Barang</li>
                <li onclick="window.location='lokasi.php'"><i class="fa fa-location-dot"></i> Lokasi</li>
                <li onclick="window.location='produk.php'"><i class="fa fa-bookmark"></i> Produk</li>
            </ul>
        </div>
        <main class="main-content">
            <div class="register-card">
                <h2>Register</h2>
                <?php if ($message): ?>
                    <div style="color:#ff4848;text-align:center;margin-bottom:1rem;"> <?= htmlspecialchars($message) ?> </div>
                <?php endif; ?>
                <form action="register.php" method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
                    <button type="submit">Daftar</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 