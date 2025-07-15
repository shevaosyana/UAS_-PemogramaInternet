<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = '';
$success = '';

$host = 'localhost';
$db   = 'login_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIINBE</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .register-box { background: #fff; padding: 28px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 340px; }
        .register-box h2 { text-align: center; margin-bottom: 20px; color: #333; }
        .register-box input { width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; }
        .register-box button { width: 100%; padding: 10px; background: #4b5bdc; border: none; color: #fff; font-weight: bold; border-radius: 6px; cursor: pointer; }
        .error { color: red; margin-bottom: 10px; text-align: center; }
        .success { color: green; margin-bottom: 10px; text-align: center; }
        .login-link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Register Akun</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password2" placeholder="Konfirmasi Password" required>
            <button type="submit">Daftar</button>
        </form>
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>