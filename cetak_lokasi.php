<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
require_once 'config.php';
$lokasiList = $pdo->query("SELECT * FROM lokasi ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Lokasi</title>
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
    <h2>Laporan Data Lokasi</h2>
    <button onclick="window.print()">Cetak</button>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lokasi</th>
                <th>Jumlah</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($lokasiList as $i => $l): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($l['nama']) ?></td>
                <td><?= htmlspecialchars($l['jumlah']) ?></td>
                <td><?= htmlspecialchars($l['catatan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html> 