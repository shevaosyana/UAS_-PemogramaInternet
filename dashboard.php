<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIINBE - Dasbor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #fff;
            color: #222;
        }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 54px;
            border-bottom: 1.5px solid #ececec;
            padding: 0 24px;
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .logo {
            font-weight: 700;
            font-size: 1.18rem;
            letter-spacing: 1.5px;
            color: #4b5bdc;
        }
        .searchbar {
            display: flex;
            align-items: center;
            background: #f6f6fa;
            border-radius: 20px;
            padding: 0 12px;
            border: 1.2px solid #ececec;
        }
        .searchbar input {
            border: none;
            background: transparent;
            outline: none;
            padding: 7px 6px 7px 0;
            font-size: 1rem;
            width: 120px;
        }
        .searchbar i {
            color: #b3b3b3;
            font-size: 1rem;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 54px);
        }
        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1.5px solid #ececec;
            padding: 18px 0 0 0;
            min-height: 100vh;
        }
        .sidebar .menu-group {
            margin-bottom: 18px;
        }
        .sidebar .menu-title {
            font-size: 0.97rem;
            font-weight: 600;
            color: #888;
            margin: 10px 0 6px 28px;
            letter-spacing: 0.5px;
        }
        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .sidebar li {
            display: flex;
            align-items: center;
            padding: 7px 18px 7px 28px;
            cursor: pointer;
            border-radius: 7px;
            transition: background 0.15s;
            font-size: 1rem;
            color: #222;
        }
        .sidebar li:hover, .sidebar li.active {
            background: #f4f6fb;
        }
        .sidebar li i {
            margin-right: 12px;
            font-size: 1.08rem;
            min-width: 18px;
            text-align: center;
        }
        .sidebar .collapse-toggle {
            margin-left: auto;
            font-size: 0.95rem;
            color: #b3b3b3;
            transition: transform 0.2s;
        }
        .sidebar ul.submenu {
            margin-left: 18px;
            margin-top: 2px;
            display: none;
        }
        .sidebar ul.submenu.open {
            display: block;
        }
        .main-content {
            flex: 1;
            padding: 32px 32px 0 32px;
        }
        .main-content h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 18px;
            letter-spacing: 1px;
        }
        @media (max-width: 900px) {
            .container { flex-direction: column; }
            .sidebar { width: 100vw; border-right: none; border-bottom: 1.5px solid #ececec; }
            .main-content { padding: 24px 4vw 0 4vw; }
        }
        .sidebar li.active, .sidebar li.active a {
            background: #fff6f0 !important;
            color: #ff6600 !important;
        }
        .sidebar li.active i {
            color: #ff6600 !important;
        }
        .sidebar li a {
            color: inherit;
            text-decoration: none;
            width: 100%;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">SIINBE</div>
        <form class="searchbar" action="#" method="get">
            <input type="text" placeholder="search" name="search" />
            <i class="fa fa-search"></i>
        </form>
    </div>
    <div class="container">
        <nav class="sidebar">
            <div class="menu-group">
                <div class="menu-title">Pengguna</div>
                <ul>
                    <li class="active"><i class="fa fa-home"></i> <span>Dasbor</span> <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Barang</div>
                <ul>
                    <li><a href="barang.php"><i class="fa fa-box"></i> Barang</a> <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Lokasi</div>
                <ul>
                    <li><i class="fa fa-location-dot"></i> Lokasi <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Produk</div>
                <ul>
                    <li><i class="fa fa-bookmark"></i> Produk <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
        </nav>
        <main class="main-content">
            <h1>Dasbor</h1>
        </main>
    </div>
    <script>
    // Expand/collapse dummy (bisa dikembangkan untuk submenu)
    document.querySelectorAll('.collapse-toggle').forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            var submenu = this.closest('li').querySelector('ul.submenu');
            if (submenu) submenu.classList.toggle('open');
            this.querySelector('i').classList.toggle('fa-chevron-up');
            this.querySelector('i').classList.toggle('fa-chevron-down');
        });
    });
    </script>
</body>
</html> 