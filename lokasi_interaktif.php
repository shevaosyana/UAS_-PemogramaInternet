<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
require_once 'config.php';
// Ambil semua lokasi
$lokasiList = $pdo->query("SELECT * FROM lokasi")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lokasi Interaktif</title>
    <style>
        .denah-wrap { position: relative; display: inline-block; }
        .lokasi-btn {
            position: absolute;
            background: rgba(102,126,234,0.85);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: background 0.2s;
        }
        .lokasi-btn:hover { background: #4b5bdc; }
        .modal-bg {
            display: none; position: fixed; top:0; left:0; width:100vw; height:100vh;
            background:rgba(0,0,0,0.18); z-index:2000; align-items:center; justify-content:center;
        }
        .modal-content {
            background:#fff; border-radius:12px; padding:24px 32px; min-width:320px; min-height:120px;
            box-shadow:0 8px 32px rgba(102,126,234,0.13); position:relative;
        }
        .close-modal { position:absolute; top:10px; right:16px; background:none; border:none; font-size:1.3rem; cursor:pointer; color:#888; }
    </style>
</head>
<body>
    <h2>Denah Lokasi Interaktif</h2>
    <div class="denah-wrap">
        <img src="denah.png" alt="Denah Sekolah" width="700">
        <!-- Contoh: Tombol lokasi, atur posisi left/top sesuai denah -->
        <button class="lokasi-btn" style="left:100px;top:80px;" onclick="showBarang('LAB 1')">LAB 1</button>
        <button class="lokasi-btn" style="left:300px;top:120px;" onclick="showBarang('Ruang Guru')">Ruang Guru</button>
        <!-- Tambahkan tombol lokasi lain sesuai kebutuhan -->
    </div>
    <div id="modalLokasi" class="modal-bg">
        <div class="modal-content">
            <button class="close-modal" onclick="closeModal()">&times;</button>
            <h3 id="modalTitle">Daftar Barang</h3>
            <div id="modalBarang">Loading...</div>
        </div>
    </div>
    <script>
    function showBarang(lokasi) {
        document.getElementById('modalLokasi').style.display = 'flex';
        document.getElementById('modalTitle').textContent = 'Daftar Barang di ' + lokasi;
        document.getElementById('modalBarang').textContent = 'Loading...';
        fetch('barang_by_lokasi.php?lokasi=' + encodeURIComponent(lokasi))
            .then(res => res.json())
            .then(data => {
                if(data.length === 0) {
                    document.getElementById('modalBarang').innerHTML = '<i>Tidak ada barang di lokasi ini.</i>';
                } else {
                    let html = '<ul style="padding-left:18px;">';
                    data.forEach(b => {
                        html += `<li><b>${b.nama}</b> (${b.merk}) - ${b.status} [${b.nomor}]</li>`;
                    });
                    html += '</ul>';
                    document.getElementById('modalBarang').innerHTML = html;
                }
            })
            .catch(() => {
                document.getElementById('modalBarang').textContent = 'Gagal memuat data.';
            });
    }
    function closeModal() {
        document.getElementById('modalLokasi').style.display = 'none';
    }
    </script>
</body>
</html> 