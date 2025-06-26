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
        body { margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background: #fff; color: #222; transition: background 0.3s, color 0.3s; }
        body.dark-mode { background: #181c24; color: #e2e6ef; }
        .topbar { display: flex; align-items: center; justify-content: space-between; height: 54px; border-bottom: 1.5px solid #ececec; padding: 0 24px; background: #fff; position: sticky; top: 0; z-index: 10; transition: background 0.3s, color 0.3s; }
        body.dark-mode .topbar { background: #23283a; color: #e2e6ef; border-bottom: 1.5px solid #23283a; }
        .logo { font-weight: 700; font-size: 1.18rem; letter-spacing: 1.5px; color: #4b5bdc; }
        .dark-toggle { background: none; border: none; color: #4b5bdc; font-size: 1.5rem; margin-right: 18px; cursor: pointer; transition: color 0.2s; }
        body.dark-mode .dark-toggle { color: #ffc107; }
        .container { display: flex; min-height: calc(100vh - 54px); }
        .sidebar { width: 220px; background: #fff; border-right: 1.5px solid #ececec; padding: 18px 0 0 0; min-height: 100vh; transition: background 0.3s, color 0.3s; }
        body.dark-mode .sidebar { background: #23283a; color: #e2e6ef; border-right: 1.5px solid #23283a; }
        .sidebar .menu-group { margin-bottom: 18px; }
        .sidebar .menu-title { font-size: 0.97rem; font-weight: 600; color: #888; margin: 10px 0 6px 28px; letter-spacing: 0.5px; }
        .sidebar ul { list-style: none; margin: 0; padding: 0; }
        .sidebar li { display: flex; align-items: center; padding: 7px 18px 7px 28px; cursor: pointer; border-radius: 7px; transition: background 0.15s; font-size: 1rem; color: #222; }
        .sidebar li:hover, .sidebar li.active { background: #f4f6fb; }
        body.dark-mode .sidebar li { color: #e2e6ef; }
        body.dark-mode .sidebar li.active, body.dark-mode .sidebar li:hover { background: linear-gradient(90deg, #23283a 60%, #2d3350 100%); color: #ffc107; }
        .sidebar li i { margin-right: 12px; font-size: 1.08rem; min-width: 18px; text-align: center; }
        .sidebar li.active, .sidebar li.active a { background: #fff6f0 !important; color: #ff6600 !important; }
        .sidebar li.active i { color: #ff6600 !important; }
        .sidebar li a { color: inherit; text-decoration: none; width: 100%; display: flex; align-items: center; }
        .main-content { flex: 1; padding: 32px 32px 0 32px; transition: color 0.3s; }
        body.dark-mode .main-content { color: #e2e6ef; }
        @media (max-width: 900px) { .container { flex-direction: column; } .sidebar { width: 100vw; border-right: none; border-bottom: 1.5px solid #ececec; } .main-content { padding: 24px 4vw 0 4vw; } }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">SIINBE</div>
        <button class="dark-toggle" id="darkToggle" title="Toggle dark mode"><span id="darkIcon">🌙</span></button>
    </div>
    <div class="container">
        <nav class="sidebar">
            <div style="text-align:center;margin-bottom:18px;">
                <img src="logo_smk7.png" alt="Logo SMK 7 Baleendah" style="height:60px;">
            </div>
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
            <?php
            require_once 'config.php';
            // Proses tambah lokasi
            if (isset($_POST['tambah_lokasi'])) {
                $nama = trim($_POST['nama_lokasi']);
                if ($nama !== '') {
                    $stmt = $pdo->prepare("INSERT INTO lokasi (nama) VALUES (?)");
                    $stmt->execute([$nama]);
                    echo '<div style="color:green;margin-bottom:10px;">Lokasi berhasil ditambahkan!</div>';
                }
            }
            $lokasiList = $pdo->query("SELECT * FROM lokasi ORDER BY id DESC")->fetchAll();
            ?>
            <form method="post" style="margin-bottom:18px;">
                <input type="text" name="nama_lokasi" placeholder="Nama Lokasi" required style="padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                <button type="submit" name="tambah_lokasi" style="padding:7px 18px;border-radius:6px;background:#4b5bdc;color:#fff;border:none;font-weight:600;">Tambah Lokasi</button>
            </form>
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f6f6fa;">
                        <th style="padding:8px 6px;">No</th>
                        <th style="padding:8px 6px;">Nama Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lokasiList as $i => $l): ?>
                    <tr>
                        <td style="padding:8px 6px;"> <?= $i+1 ?> </td>
                        <td style="padding:8px 6px;"> <?= htmlspecialchars($l['nama']) ?> </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
    <script>
    // Dark mode toggle
    const darkToggle = document.getElementById('darkToggle');
    const darkIcon = document.getElementById('darkIcon');
    function setDarkMode(on) {
        if (on) {
            document.body.classList.add('dark-mode');
            darkIcon.textContent = '☀️';
            localStorage.setItem('darkMode', '1');
        } else {
            document.body.classList.remove('dark-mode');
            darkIcon.textContent = '🌙';
            localStorage.setItem('darkMode', '0');
        }
    }
    setDarkMode(localStorage.getItem('darkMode') === '1');
    darkToggle.onclick = function() {
        setDarkMode(!document.body.classList.contains('dark-mode'));
    };
    </script>
</body>
</html> 