<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIINBE - Lokasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background: #fff; color: #222; }
        .topbar { display: flex; align-items: center; justify-content: space-between; height: 54px; border-bottom: 1.5px solid #ececec; padding: 0 24px; background: #fff; position: sticky; top: 0; z-index: 10; }
        .logo { font-weight: 700; font-size: 1.18rem; letter-spacing: 1.5px; color: #4b5bdc; }
        .container { display: flex; min-height: calc(100vh - 54px); }
        .sidebar { width: 220px; background: #fff; border-right: 1.5px solid #ececec; padding: 18px 0 0 0; min-height: 100vh; }
        .sidebar .menu-group { margin-bottom: 18px; }
        .sidebar .menu-title { font-size: 0.97rem; font-weight: 600; color: #888; margin: 10px 0 6px 28px; letter-spacing: 0.5px; }
        .sidebar ul { list-style: none; margin: 0; padding: 0; }
        .sidebar li { display: flex; align-items: center; padding: 7px 18px 7px 28px; cursor: pointer; border-radius: 7px; transition: background 0.15s; font-size: 1rem; color: #222; }
        .sidebar li:hover, .sidebar li.active { background: #f4f6fb; }
        .sidebar li i { margin-right: 12px; font-size: 1.08rem; min-width: 18px; text-align: center; }
        .sidebar li.active, .sidebar li.active a { background: #fff6f0 !important; color: #ff6600 !important; }
        .sidebar li.active i { color: #ff6600 !important; }
        .sidebar li a { color: inherit; text-decoration: none; width: 100%; display: flex; align-items: center; }
        .main-content { flex: 1; padding: 32px 32px 0 32px; }
        @media (max-width: 900px) { .container { flex-direction: column; } .sidebar { width: 100vw; border-right: none; border-bottom: 1.5px solid #ececec; } .main-content { padding: 24px 4vw 0 4vw; } }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">SIINBE</div>
    </div>
    <div class="container">
        <nav class="sidebar">
            <div class="menu-group">
                <div class="menu-title">Pengguna</div>
                <ul>
                    <li><a href="users.php"><i class="fa fa-user"></i> Pengguna</a></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Barang</div>
                <ul>
                    <li><a href="barang.php"><i class="fa fa-box"></i> Barang</a></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Lokasi</div>
                <ul>
                    <li class="active"><a href="lokasi.php"><i class="fa fa-location-dot"></i> Lokasi</a></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Produk</div>
                <ul>
                    <li><a href="produk.php"><i class="fa fa-bookmark"></i> Produk</a></li>
                </ul>
            </div>
        </nav>
        <main class="main-content">
            <h1>Daftar Lokasi</h1>
            <p>Halaman ini untuk menampilkan dan mengelola data lokasi di SMK NEGERI 7 BALEENDAH.</p>
        </main>
    </div>
</body>
</html> 