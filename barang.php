<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
require_once 'config.php';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
    if ($stmt->execute([$id])) {
        header('Location: barang.php?msg=hapus_sukses');
        exit();
    } else {
        header('Location: barang.php?msg=hapus_gagal');
        exit();
    }
}
// Ambil data barang dari database
$stmt = $pdo->query("SELECT * FROM barang ORDER BY id DESC");
$daftar_barang = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIINBE - Barang</title>
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
        .breadcrumb {
            font-size: 0.97rem;
            color: #888;
            margin-bottom: 10px;
        }
        .breadcrumb span {
            margin: 0 6px;
        }
        .barang-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .barang-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            color: #222;
        }
        .barang-header .add-btn {
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 18px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            transition: background 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .barang-header .add-btn:hover {
            background: linear-gradient(90deg, #4b5bdc 60%, #764ba2 100%);
            box-shadow: 0 4px 16px rgba(102,126,234,0.13);
        }
        .barang-table-wrap {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 0 0 12px 0;
            border: 1.2px solid #ececec;
            overflow-x: auto;
        }
        .barang-table-tools {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px 0 18px;
        }
        .barang-table-tools .search {
            display: flex;
            align-items: center;
            background: #f6f6fa;
            border-radius: 20px;
            padding: 0 12px;
            border: 1.2px solid #ececec;
        }
        .barang-table-tools input[type="text"] {
            border: none;
            background: transparent;
            outline: none;
            padding: 7px 6px 7px 0;
            font-size: 1rem;
            width: 120px;
        }
        .barang-table-tools i {
            color: #b3b3b3;
            font-size: 1rem;
        }
        table.barang-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 8px;
            background: #f8fafc;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(102,126,234,0.08);
        }
        table.barang-table th, table.barang-table td {
            padding: 13px 10px;
            text-align: left;
            font-size: 1rem;
        }
        table.barang-table th {
            background: #4b5bdc;
            color: #fff;
            font-weight: 700;
            border-bottom: 2.5px solid #ececec;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        table.barang-table tr:nth-child(even) td {
            background: #eef1fa;
        }
        table.barang-table tr:nth-child(odd) td {
            background: #f8fafc;
        }
        table.barang-table tr:hover td {
            background: #dbeafe !important;
            transition: background 0.2s;
        }
        body.dark-mode table.barang-table th {
            background: #23283a;
            color: #ffc107;
            border-bottom: 2.5px solid #23283a;
        }
        body.dark-mode table.barang-table tr:nth-child(even) td {
            background: #23283a;
        }
        body.dark-mode table.barang-table tr:nth-child(odd) td {
            background: #181c24;
        }
        body.dark-mode table.barang-table tr:hover td {
            background: #2d3350 !important;
        }
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.97rem;
            font-weight: 600;
            color: #fff;
        }
        .badge-aktif { background: #28a745; }
        .badge-servis { background: #ffc107; color: #222; }
        .badge-rusak { background: #dc3545; }
        .badge-rusak-ringan { background: #ff9800; color: #fff; }
        body.dark-mode .badge-rusak-ringan { background: #ff9800; color: #fff; }
        .table-actions {
            display: flex;
            gap: 10px;
        }
        .table-actions button {
            background: none;
            border: none;
            color: #4b5bdc;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 6px;
            padding: 6px 8px;
            transition: background 0.18s, color 0.18s;
        }
        .table-actions button:hover {
            background: #e0e7ff;
            color: #222;
        }
        body.dark-mode .table-actions button {
            color: #ffc107;
        }
        body.dark-mode .table-actions button:hover {
            background: #2d3350;
            color: #fff;
        }
        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 18px 0 18px;
            font-size: 0.97rem;
            color: #888;
        }
        @media (max-width: 900px) { .container { flex-direction: column; } .sidebar { width: 100vw; border-right: none; border-bottom: 1.5px solid #ececec; } .main-content { padding: 24px 4vw 0 4vw; } }
        @media (max-width: 600px) {
            .main-content { padding: 12px 2vw 0 2vw; }
            .barang-header { flex-direction: column; gap: 12px; align-items: flex-start; }
            .barang-table-tools { flex-direction: column; gap: 10px; align-items: flex-start; }
            .table-footer { flex-direction: column; gap: 8px; align-items: flex-start; }
        }
        .qr-card {
            display: flex;
            align-items: center;
            gap: 18px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(102,126,234,0.10);
            padding: 18px 32px;
            margin-bottom: 24px;
            width: fit-content;
        }
        .qr-desc {
            font-weight: 600;
            font-size: 1.08rem;
            color: #4b5bdc;
        }
        body.dark-mode .qr-card {
            background: #23283a;
            color: #e2e6ef;
        }
        body.dark-mode .qr-desc {
            color: #b3bafc;
        }
        #barcodeStatic {
            width: 120px;
            height: 120px;
            background: #fff;
            border-radius: 8px;
            border: 1.5px solid #ececec;
        }
        .subtitle {
            font-size: 1.05rem;
            color: #888;
            margin-top: 2px;
            margin-bottom: 0;
        }
        .scan-btn {
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 8px 18px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            transition: background 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }
        .scan-btn:hover {
            background: linear-gradient(90deg, #4b5bdc 60%, #764ba2 100%);
            box-shadow: 0 4px 16px rgba(102,126,234,0.13);
        }
        body.dark-mode .scan-btn {
            background: linear-gradient(90deg, #4b5bdc 60%, #667eea 100%);
            color: #fff;
        }
        body.dark-mode .scan-btn:hover {
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
        }
        .searchbar, .barang-table-tools .search {
            display: flex;
            align-items: center;
            background: #f6f6fa;
            border-radius: 20px;
            padding: 0 12px;
            border: 1.2px solid #ececec;
        }
        .searchbar input, .barang-table-tools input[type="text"] {
            border: none;
            background: transparent;
            outline: none;
            padding: 7px 6px 7px 0;
            font-size: 1rem;
            width: 120px;
            color: #222;
        }
        .searchbar i, .barang-table-tools i {
            color: #b3b3b3;
            font-size: 1rem;
        }
        body.dark-mode .searchbar, body.dark-mode .barang-table-tools .search {
            background: #181c24;
            border: 1.2px solid #444;
        }
        body.dark-mode .searchbar input, body.dark-mode .barang-table-tools input[type="text"] {
            color: #e2e6ef;
        }
        body.dark-mode .searchbar i, body.dark-mode .barang-table-tools i {
            color: #e2e6ef;
        }
        .barang-table-tools button {
            background: none;
            border: none;
            color: #667eea;
            font-size: 1.1rem;
            cursor: pointer;
        }
        body.dark-mode .barang-table-tools button {
            color: #b3bafc;
        }
        .table-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 18px 0 18px;
            font-size: 0.97rem;
            color: #888;
        }
        body.dark-mode .table-footer {
            background: #23283a;
            color: #e2e6ef;
        }
        .modal-qr {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.18);
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-qr-content {
            background: #fff;
            padding: 32px 32px 24px 32px;
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(102,126,234,0.13);
            position: relative;
            min-width: 340px;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        body.dark-mode .modal-qr-content {
            background: #23283a;
            color: #e2e6ef;
        }
        .modal-qr-content h2 {
            color: #4b5bdc;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 18px;
        }
        body.dark-mode .modal-qr-content h2 {
            color: #ffc107;
        }
        .modal-qr-content label {
            color: #222;
            font-weight: 600;
        }
        body.dark-mode .modal-qr-content label {
            color: #e2e6ef;
        }
        .modal-qr-content input,
        .modal-qr-content select {
            background: #fff;
            color: #222;
            border: 1px solid #ececec;
        }
        body.dark-mode .modal-qr-content input,
        body.dark-mode .modal-qr-content select {
            background: #181c24;
            color: #e2e6ef;
            border: 1.5px solid #444;
        }
        .modal-qr-content button[type="submit"] {
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
        .modal-qr-content button[type="submit"]:hover {
            background: linear-gradient(90deg, #4b5bdc 60%, #667eea 100%);
        }
        body.dark-mode .modal-qr-content button[type="submit"] {
            background: linear-gradient(90deg, #ffc107 60%, #4b5bdc 100%);
            color: #23283a;
        }
        body.dark-mode .modal-qr-content button[type="submit"]:hover {
            background: linear-gradient(90deg, #ffe066 60%, #ffc107 100%);
            color: #23283a;
        }
        .close-modal {
            position: absolute;
            top: 12px;
            right: 16px;
            background: none;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            color: #888;
        }
        .scan-result {
            margin-top: 18px;
            font-size: 1.08rem;
            color: #222;
            font-weight: 600;
            text-align: center;
        }
        .notif {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px 28px;
            border-radius: 10px;
            margin-bottom: 22px;
            font-weight: 600;
            font-size: 1.08rem;
            text-align: center;
            box-shadow: 0 4px 24px rgba(102,126,234,0.13);
            opacity: 1;
            transition: opacity 0.5s, transform 0.5s;
            transform: translateY(-20px);
            animation: notifIn 0.5s cubic-bezier(.4,2,.6,1) 1;
        }
        @keyframes notifIn {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(-20px); }
        }
        .notif.hide {
            opacity: 0;
            transform: translateY(-40px);
        }
        .notif-success {
            background: linear-gradient(90deg, #d4edda 60%, #b7f5c2 100%);
            color: #155724;
            border: 1.5px solid #51cf66;
        }
        .notif-error {
            background: linear-gradient(90deg, #f8d7da 60%, #ffd6d6 100%);
            color: #721c24;
            border: 1.5px solid #ff6b6b;
        }
        body.dark-mode .notif-success {
            background: linear-gradient(90deg, #223a2a 60%, #2e5c3a 100%);
            color: #51cf66;
            border-color: #51cf66;
        }
        body.dark-mode .notif-error {
            background: linear-gradient(90deg, #3a2222 60%, #5c2e2e 100%);
            color: #ff6b6b;
            border-color: #ff6b6b;
        }
        .notif .notif-icon {
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notif-success .notif-icon {
            color: #28a745;
        }
        .notif-error .notif-icon {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">SIINBE</div>
        <button class="dark-toggle" id="darkToggle" title="Toggle dark mode"><span id="darkIcon">🌙</span></button>
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
                    <li><a href="users.php"><i class="fa fa-user"></i> Pengguna</a> <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Barang</div>
                <ul>
                    <li class="active"><i class="fa fa-box"></i> Barang <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Lokasi</div>
                <ul>
                    <li><a href="lokasi.php"><i class="fa fa-location-dot"></i> Lokasi</a> <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
            <div class="menu-group">
                <div class="menu-title">Produk</div>
                <ul>
                    <li><a href="produk.php"><i class="fa fa-bookmark"></i> Produk</a> <span class="collapse-toggle"><i class="fa fa-chevron-up"></i></span></li>
                </ul>
            </div>
        </nav>
        <main class="main-content">
            <!-- Hanya konten barang, tanpa dashboard mini -->
            <button class="add-btn" style="margin-bottom: 18px;"><i class="fa fa-plus"></i>Tambah</button>
            <div class="breadcrumb">Barang &gt; Daftar</div>
            <div class="barang-header">
                <div>
                    <h1>Barang</h1>
                    <div class="subtitle">Daftar inventaris barang SMK NEGERI 7 BALEENDAH</div>
                </div>
            </div>
            <!-- QR Card -->
            <div class="qr-card">
                <canvas id="barcodeStatic"></canvas>
                <div class="qr-desc">Scan untuk daftar barang</div>
            </div>
            <!-- Tombol Scan QR -->
            <button class="scan-btn" onclick="openScanModal()"><i class="fa fa-qrcode"></i> Scan QR</button>
            <!-- Modal Scan QR -->
            <div id="scanModal" class="modal-qr" style="display:none;">
                <div class="modal-qr-content">
                    <button class="close-modal" onclick="closeScanModal()">&times;</button>
                    <h2>Scan QR Barang</h2>
                    <div id="qr-reader" style="width:320px;"></div>
                    <div id="scanResult" class="scan-result"></div>
                </div>
            </div>
            <!-- Modal Tambah Barang -->
            <div id="modalTambahBarang" class="modal-qr" style="display:none;z-index:3000;">
                <div class="modal-qr-content" style="min-width:380px;min-height:auto;">
                    <button class="close-modal" onclick="closeTambahBarang()">&times;</button>
                    <h2>Tambah Barang</h2>
                    <form id="formTambahBarang" onsubmit="submitTambahBarang(event)">
                        <div style="margin-bottom:12px;width:100%;">
                            <label>Nama</label><br>
                            <input type="text" name="nama" required style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                        </div>
                        <div style="margin-bottom:12px;width:100%;">
                            <label>Lokasi</label><br>
                            <input type="text" name="lokasi" required style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                        </div>
                        <div style="margin-bottom:12px;width:100%;">
                            <label>Merk</label><br>
                            <input type="text" name="merk" required style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                        </div>
                        <div style="margin-bottom:12px;width:100%;">
                            <label>Status</label><br>
                            <select name="status" required style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                                <option value="Aktif">Aktif</option>
                                <option value="Perlu Servis">Perlu Servis</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak">Rusak</option>
                            </select>
                        </div>
                        <div style="margin-bottom:18px;width:100%;">
                            <label>Nomor</label><br>
                            <input type="text" name="nomor" required style="width:100%;padding:7px 10px;border-radius:6px;border:1px solid #ececec;">
                        </div>
                        <button type="submit" class="add-btn" style="width:100%;justify-content:center;"><i class="fa fa-save"></i> Simpan</button>
                    </form>
                </div>
            </div>
            <div class="barang-table-wrap">
                <div class="barang-table-tools">
                    <div class="search">
                        <input type="text" placeholder="Cari barang..." />
                        <i class="fa fa-search"></i>
                    </div>
                    <div>
                        <button style="background:none;border:none;color:#667eea;font-size:1.1rem;cursor:pointer;"><i class="fa fa-filter"></i> Filter</button>
                    </div>
                </div>
                <table class="barang-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" /></th>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Merk</th>
                            <th>Status</th>
                            <th>Nomor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
<?php foreach ($daftar_barang as $i => $b): ?>
    <tr>
        <td><input type="checkbox" /></td>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($b['nama']) ?></td>
        <td><?= htmlspecialchars($b['lokasi']) ?></td>
        <td><?= htmlspecialchars($b['merk']) ?></td>
        <td>
            <?php if ($b['status'] == 'Aktif'): ?>
                <span class="badge-status badge-aktif">Aktif</span>
            <?php elseif ($b['status'] == 'Perlu Servis'): ?>
                <span class="badge-status badge-servis">Perlu Servis</span>
            <?php elseif ($b['status'] == 'Rusak Ringan'): ?>
                <span class="badge-status badge-rusak-ringan">Rusak Ringan</span>
            <?php else: ?>
                <span class="badge-status badge-rusak"><?= htmlspecialchars($b['status']) ?></span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($b['nomor']) ?></td>
        <td class="table-actions">
            <button title="Lihat Barcode" onclick="showBarcode('<?= htmlspecialchars($b['nomor']) ?>','<?= htmlspecialchars($b['nama']) ?>')"><i class="fa fa-qrcode"></i></button>
            <button title="Edit"><i class="fa fa-edit"></i></button>
            <a href="barang.php?delete=<?= $b['id'] ?>" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-footer">
                    <div>Showing 1-2 of 2</div>
                    <div>
                        <select style="padding:3px 8px;border-radius:5px;border:1px solid #ececec;">
                            <option>Per page: 5</option>
                            <option>10</option>
                            <option>20</option>
                        </select>
                        <span style="margin-left:10px;">&lt; 1 &gt;</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="barcodeModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.18);z-index:1000;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:32px 32px 24px 32px;border-radius:14px;box-shadow:0 8px 32px rgba(102,126,234,0.13);position:relative;min-width:320px;min-height:320px;display:flex;flex-direction:column;align-items:center;">
            <button onclick="closeBarcode()" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:1.3rem;cursor:pointer;color:#888;">&times;</button>
            <div id="barcodeTitle" style="font-weight:600;font-size:1.1rem;margin-bottom:18px;"></div>
            <canvas id="barcodeCanvas" style="width:220px;height:220px;"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
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
    function showBarcode(nomor, nama) {
        document.getElementById('barcodeModal').style.display = 'flex';
        document.getElementById('barcodeTitle').textContent = nama + ' (' + nomor + ')';
        var qr = new QRious({
            element: document.getElementById('barcodeCanvas'),
            value: nomor,
            size: 220,
            background: '#fff',
            foreground: '#222',
            level: 'H'
        });
    }
    function closeBarcode() {
        document.getElementById('barcodeModal').style.display = 'none';
    }
    // Barcode statis di atas tabel
    new QRious({
        element: document.getElementById('barcodeStatic'),
        value: 'DAFTAR_BARANG',
        size: 120,
        background: '#fff',
        foreground: '#222',
        level: 'H'
    });
    // Modal Scan QR
    function openScanModal() {
        document.getElementById('scanModal').style.display = 'flex';
        document.getElementById('scanResult').textContent = '';
        if (!window.qrScanner) {
            window.qrScanner = new Html5Qrcode("qr-reader");
        }
        window.qrScanner.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 220 },
            qrCodeMessage => {
                document.getElementById('scanResult').innerHTML = '<b>Hasil Scan:</b> ' + qrCodeMessage + '<br>Menampilkan detail barang...';
                // Simulasi detail barang, bisa diubah sesuai kebutuhan
                if(qrCodeMessage === 'INV-001') {
                    document.getElementById('scanResult').innerHTML += '<div style="margin-top:10px;text-align:left;">Nama: Printer A<br>Lokasi: Ruang Guru<br>Merk: Epson<br>Status: <span style="color:#28a745;font-weight:600;">Aktif</span></div>';
                } else if(qrCodeMessage === 'INV-002') {
                    document.getElementById('scanResult').innerHTML += '<div style="margin-top:10px;text-align:left;">Nama: Komputer B<br>Lokasi: Lab Komputer<br>Merk: Lenovo<br>Status: <span style="color:#ffc107;font-weight:600;">Perlu Servis</span></div>';
                } else {
                    document.getElementById('scanResult').innerHTML += '<div style="margin-top:10px;">Barang tidak ditemukan.</div>';
                }
                window.qrScanner.stop();
            },
            errorMessage => {
                // ignore errors
            }
        );
    }
    function closeScanModal() {
        document.getElementById('scanModal').style.display = 'none';
        if(window.qrScanner) window.qrScanner.stop();
    }
    // Modal Tambah Barang
    document.querySelector('.add-btn').onclick = openTambahBarang;
    function openTambahBarang() {
        document.getElementById('modalTambahBarang').style.display = 'flex';
    }
    function closeTambahBarang() {
        document.getElementById('modalTambahBarang').style.display = 'none';
    }
    function submitTambahBarang(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        fetch('barang_tambah.php', {
            method: 'POST',
            body: data
        })
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                alert('Barang berhasil ditambahkan!');
                closeTambahBarang();
                form.reset();
                location.reload();
            } else {
                alert('Gagal menambah barang: ' + res.message);
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan saat menambah barang.');
        });
    }
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
    function hapusBarang(id) {
        if (confirm('Yakin ingin menghapus barang ini?')) {
            window.location = 'barang.php?delete=' + id;
        }
    }
    if (document.getElementById('notifBarang')) {
        setTimeout(function() {
            document.getElementById('notifBarang').classList.add('hide');
        }, 2500);
        setTimeout(function() {
            document.getElementById('notifBarang').style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html> 