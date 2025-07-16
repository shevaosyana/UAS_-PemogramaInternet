<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
require_once 'config.php';
$produkList = $pdo->query("SELECT * FROM produk ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Produk</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
        @media print {
            button { display: none; }
        }
    </style>
</head>
<body>
    <h2>Laporan Data Produk</h2>
    <button onclick="window.print()">Cetak</button>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Merk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produkList as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                <td><?= htmlspecialchars($p['merk']) ?></td>
                <td><?= number_format($p['harga'],0,',','.') ?></td>
                <td><?= htmlspecialchars($p['stok']) ?></td>
                <td><?= isset($p['created_at']) ? htmlspecialchars($p['created_at']) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html> 