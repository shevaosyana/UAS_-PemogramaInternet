<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
require_once 'config.php';

$message = '';
$success = false;
// Proses tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama_produk'])) {
    $nama = trim($_POST['nama_produk']);
    $merk = trim($_POST['merk']);
    $harga = isset($_POST['harga']) ? floatval($_POST['harga']) : null;
    $stok = isset($_POST['stok']) ? intval($_POST['stok']) : null;
    if ($nama !== '' && $merk !== '' && $harga !== null && $stok !== null) {
        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, merk, harga, stok) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nama, $merk, $harga, $stok])) {
            $message = 'Produk berhasil ditambahkan!';
            $success = true;
        } else {
            $message = 'Gagal menambah produk.';
            $success = false;
        }
    } else {
        $message = 'Semua field harus diisi!';
        $success = false;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM produk WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = 'Produk berhasil dihapus!';
        $success = true;
    } else {
        $message = 'Gagal menghapus produk.';
        $success = false;
    }
}

$produkList = $pdo->query("SELECT * FROM produk ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - SIINBE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            background: #f6f8fc;
            font-family: 'Inter', Arial, sans-serif;
            color: #222;
            margin: 0;
            padding: 0;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar {
            width: 220px;
            background: #23283a;
            color: #e2e6ef;
            min-height: 100vh;
            padding: 0;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .sidebar .logo {
            font-weight: 700;
            font-size: 1.3rem;
            letter-spacing: 1.5px;
            color: #667eea;
            padding: 32px 0 18px 32px;
        }
        .sidebar ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .sidebar li {
            display: flex;
            align-items: center;
            padding: 12px 32px;
            cursor: pointer;
            border-left: 4px solid transparent;
            font-size: 1.05rem;
            transition: background 0.15s, border 0.15s;
        }
        .sidebar li.active, .sidebar li:hover {
            background: #181c24;
            border-left: 4px solid #667eea;
            color: #ffc107;
        }
        .sidebar li i {
            margin-right: 12px;
            font-size: 1.15rem;
            min-width: 18px;
            text-align: center;
        }
        .main-content {
            margin-left: 220px;
            padding: 48px 36px 36px 36px;
            min-height: 100vh;
            background: none;
            transition: color 0.3s;
        }
        .topbar-produk {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }
        .topbar-produk h1 {
            color: #4b5bdc;
            font-size: 1.45rem;
            font-weight: 700;
            margin: 0;
        }
        .dark-toggle {
            background: none;
            border: none;
            color: #4b5bdc;
            font-size: 1.7rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        body.dark-mode .dark-toggle { color: #ffc107; }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(102,126,234,0.10);
            padding: 32px 28px 24px 28px;
            max-width: 600px;
            margin: 0 auto 32px auto;
        }
        body.dark-mode .card {
            background: #23283a;
            color: #e2e6ef;
        }
        .form-produk {
            display: flex;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .form-produk input[type="text"], .form-produk input[type="number"] {
            flex: 1;
            padding: 10px 14px;
            border-radius: 7px;
            border: 1.5px solid #ececec;
            font-size: 1rem;
            outline: none;
            transition: border 0.2s;
            min-width: 120px;
        }
        .form-produk input[type="text"]:focus, .form-produk input[type="number"]:focus {
            border: 1.5px solid #4b5bdc;
        }
        .form-produk button[type="submit"] {
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 10px 22px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.18s;
        }
        .form-produk button[type="submit"]:hover {
            background: linear-gradient(90deg, #4b5bdc 60%, #667eea 100%);
        }
        .table-card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(102,126,234,0.10);
            padding: 24px 18px 18px 18px;
            max-width: 900px;
            margin: 0 auto;
        }
        body.dark-mode .table-card {
            background: #23283a;
            color: #e2e6ef;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: none;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 10px;
            text-align: left;
        }
        th {
            background: #f6f6fa;
            color: #4b5bdc;
            font-weight: 600;
            border-bottom: 2px solid #ececec;
        }
        body.dark-mode th {
            background: #23283a;
            color: #ffc107;
        }
        tr:nth-child(even) {
            background: #f9faff;
        }
        body.dark-mode tr:nth-child(even) {
            background: #23283a;
        }
        tr:hover {
            background: #e0e7ff;
            transition: background 0.2s;
        }
        body.dark-mode tr:hover {
            background: #2d3350;
        }
        .no-data {
            text-align: center;
            color: #888;
            padding: 18px 0;
        }
        .btn-aksi {
            border: none;
            background: #f6f6fa;
            color: #4b5bdc;
            border-radius: 5px;
            padding: 5px 8px;
            font-size: 1rem;
            margin-right: 4px;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
            outline: none;
        }
        .btn-aksi:last-child { margin-right: 0; }
        .btn-edit:hover { background: #ffe066; color: #7c5c00; }
        .btn-delete:hover { background: #ff6b6b; color: #fff; }
        body.dark-mode .btn-aksi { background: #23283a; color: #ffc107; }
        body.dark-mode .btn-edit:hover { background: #ffe066; color: #7c5c00; }
        body.dark-mode .btn-delete:hover { background: #ff6b6b; color: #fff; }
        @media (max-width: 900px) {
            .sidebar { width: 100vw; position: static; min-height: auto; }
            .main-content { margin-left: 0; padding: 24px 4vw 0 4vw; }
            .card, .table-card { max-width: 100%; }
        }
        body.dark-mode {
            background: #181c24 !important;
            color: #e2e6ef !important;
        }
        body.dark-mode .main-content {
            background: #181c24 !important;
            color: #e2e6ef !important;
        }
        body.dark-mode .sidebar {
            background: #23283a !important;
            color: #e2e6ef !important;
        }
        body.dark-mode .card,
        body.dark-mode .table-card {
            background: #23283a !important;
            color: #e2e6ef !important;
        }
        body.dark-mode input,
        body.dark-mode select {
            background: #181c24 !important;
            color: #e2e6ef !important;
            border: 1.5px solid #444 !important;
        }
        body.dark-mode th {
            background: #23283a !important;
            color: #ffc107 !important;
        }
        body.dark-mode tr:nth-child(even) {
            background: #23283a !important;
        }
        body.dark-mode tr:hover {
            background: #2d3350 !important;
        }
        .notif {
            padding: 12px 18px;
            border-radius: 7px;
            margin-bottom: 18px;
            font-weight: 600;
            font-size: 1rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            opacity: 1;
            transition: opacity 0.5s;
        }
        .notif.hide {
            opacity: 0;
        }
        .notif-success { background: #d4edda; color: #155724; border: 1.5px solid #51cf66; }
        .notif-error { background: #f8d7da; color: #721c24; border: 1.5px solid #ff6b6b; }
        body.dark-mode .notif-success { background: #223a2a; color: #51cf66; border-color: #51cf66; }
        body.dark-mode .notif-error { background: #3a2222; color: #ff6b6b; border-color: #ff6b6b; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">SIINBE</div>
        <ul>
            <li onclick="window.location='users.php'" style="cursor:pointer;"><i class="fa fa-user"></i> Pengguna</li>
            <li onclick="window.location='barang.php'" style="cursor:pointer;"><i class="fa fa-box"></i> Barang</li>
            <li onclick="window.location='lokasi.php'" style="cursor:pointer;"><i class="fa fa-location-dot"></i> Lokasi</li>
            <li class="active" onclick="window.location='produk.php'" style="cursor:pointer;"><i class="fa fa-bookmark"></i> Produk</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="topbar-produk">
            <h1>Daftar Produk</h1>
            <button class="dark-toggle" id="darkToggle" title="Toggle dark mode"><span id="darkIcon">🌙</span></button>
        </div>
        <div class="card">
            <?php if ($message): ?>
                <div class="notif <?= $success ? 'notif-success' : 'notif-error' ?>" id="notifProduk">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
            <form class="form-produk" method="post" autocomplete="off">
                <input type="text" name="nama_produk" placeholder="Nama Produk" required />
                <input type="text" name="merk" placeholder="Merk" required />
                <input type="number" name="harga" placeholder="Harga" min="0" required />
                <input type="number" name="stok" placeholder="Stok" min="0" required />
                <button type="submit">Tambah Produk</button>
            </form>
        </div>
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Nama Produk</th>
                        <th>Merk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Tanggal Input</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produkList) === 0): ?>
                        <tr><td colspan="7" class="no-data">Belum ada produk.</td></tr>
                    <?php else: ?>
                        <?php foreach($produkList as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($p['merk']) ?></td>
                            <td><?= number_format($p['harga'],0,',','.') ?></td>
                            <td><?= htmlspecialchars($p['stok']) ?></td>
                            <td><?= isset($p['created_at']) ? htmlspecialchars($p['created_at']) : '-' ?></td>
                            <td>
                                <button class="btn-aksi btn-edit" title="Edit"><i class="fa fa-edit"></i></button>
                                <a href="produk.php?delete=<?= $p['id'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')" class="btn-aksi btn-delete" title="Hapus"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
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
    if (document.getElementById('notifProduk')) {
        setTimeout(function() {
            document.getElementById('notifProduk').classList.add('hide');
        }, 2500);
        setTimeout(function() {
            document.getElementById('notifProduk').style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html> 