<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email atau kata sandi salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIINBE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }
        /* SVG Ornaments */
        .bg-svg {
            position: absolute;
            z-index: 0;
            pointer-events: none;
        }
        .bg-svg.top {
            top: -60px; left: -60px;
            width: 220px; height: 220px;
            opacity: 0.18;
        }
        .bg-svg.bottom {
            bottom: -60px; right: -60px;
            width: 200px; height: 200px;
            opacity: 0.13;
        }
        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.22);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(102,126,234,0.13), 0 1.5px 8px rgba(118,75,162,0.10);
            padding: 44px 32px 32px 32px;
            width: 370px;
            max-width: 97vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeIn 0.7s;
            border: 1.5px solid rgba(102,126,234,0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-sizing: border-box;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-logo {
            width: 54px;
            height: 54px;
            margin-bottom: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 60%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(102,126,234,0.13);
        }
        .login-logo i {
            color: #fff;
            font-size: 2rem;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 6px;
            color: #4b5bdc;
        }
        .login-subtitle {
            font-size: 1.08rem;
            font-weight: 500;
            margin-bottom: 26px;
            color: #444;
        }
        .form-group {
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 0.97rem;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
        }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 38px;
            color: #b3b3b3;
            font-size: 1.1rem;
            transition: color 0.2s;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px 38px 10px 38px;
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            font-size: 1rem;
            background: #f9f9fb;
            transition: border 0.2s, box-shadow 0.2s;
            box-shadow: 0 1px 2px rgba(102,126,234,0.03);
        }
        .form-group input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 2px #e0e7ff, 0 1px 2px rgba(102,126,234,0.06);
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 36px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #888;
            transition: color 0.2s, transform 0.2s;
        }
        .toggle-password:active {
            color: #4b5bdc;
            transform: scale(1.15);
        }
        .remember-row {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            width: 100%;
        }
        .remember-row input[type="checkbox"] {
            margin-right: 8px;
        }
        .login-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1.13rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            transition: background 0.2s, box-shadow 0.2s, transform 0.15s;
        }
        .login-btn:hover {
            background: linear-gradient(90deg, #4b5bdc 60%, #764ba2 100%);
            box-shadow: 0 4px 16px rgba(102,126,234,0.13);
            transform: translateY(-2px) scale(1.01);
        }
        .error-message {
            color: #dc3545;
            background: #f8d7da;
            padding: 8px 0;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            margin-bottom: 12px;
            font-size: 0.98rem;
        }
        .register-link {
            margin-top: 18px;
            text-align: center;
            width: 100%;
        }
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 0.98rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        .register-link a:hover {
            color: #4b5bdc;
            text-decoration: underline;
        }
        @media (max-width: 500px) {
            .login-box {
                padding: 24px 8vw 18px 8vw;
                width: 98vw;
            }
        }
    </style>
</head>
<body>
    <svg class="bg-svg top" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100" fill="url(#g1)"/><defs><radialGradient id="g1"><stop stop-color="#667eea"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></radialGradient></defs></svg>
    <svg class="bg-svg bottom" viewBox="0 0 200 200"><circle cx="100" cy="100" r="100" fill="url(#g2)"/><defs><radialGradient id="g2"><stop stop-color="#764ba2"/><stop offset="1" stop-color="#fff" stop-opacity="0"/></radialGradient></defs></svg>
    <form class="login-box" method="POST" action="">
        <div class="login-logo"><i class="fa fa-user-circle"></i></div>
        <div class="login-title">SIINBE</div>
        <div class="login-subtitle">Masuk ke akun Anda</div>
        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <div class="form-group">
            <label for="email">Alamat email</label>
            <span class="input-icon"><i class="fa fa-envelope"></i></span>
            <input type="email" id="email" name="email" placeholder="Alamat email" required>
        </div>
        <div class="form-group">
            <label for="password">Kata sandi</label>
            <span class="input-icon"><i class="fa fa-lock"></i></span>
            <input type="password" id="password" name="password" placeholder="Kata sandi" required>
            <button type="button" class="toggle-password" onclick="togglePassword()" tabindex="-1"><span id="eye">👁️</span></button>
        </div>
        <div class="remember-row">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="font-size:0.97rem; color:#555; cursor:pointer;">Ingat saya</label>
        </div>
        <button type="submit" class="login-btn">Masuk</button>
        <div class="register-link">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </form>
    <script>
    function togglePassword() {
        var pwd = document.getElementById('password');
        var eye = document.getElementById('eye');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            eye.textContent = '🙈';
        } else {
            pwd.type = 'password';
            eye.textContent = '👁️';
        }
    }
    </script>
</body>
</html> 