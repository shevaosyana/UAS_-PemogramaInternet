<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'config.php';
// Query data untuk grafik
// Barang per lokasi
$barangPerLokasi = $pdo->query("SELECT lokasi, COUNT(*) as jumlah FROM barang GROUP BY lokasi ORDER BY jumlah DESC")->fetchAll();
// Jenis barang terbanyak (berdasarkan nama)
$jenisBarang = $pdo->query("SELECT nama, COUNT(*) as jumlah FROM barang GROUP BY nama ORDER BY jumlah DESC LIMIT 5")->fetchAll();
// Untuk pencarian cepat
$allBarang = $pdo->query("SELECT nama FROM barang")->fetchAll(PDO::FETCH_COLUMN);
$allLokasi = $pdo->query("SELECT nama FROM lokasi")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIINBE - Dasbor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: #f6f8fc;
            color: #222;
            transition: background 0.3s, color 0.3s;
        }
        body.dark-mode {
            background: #181c24;
            color: #e2e6ef;
        }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            padding: 0 36px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            position: relative;
        }
        .topbar .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: 2px;
        }
        .topbar .logo img {
            height: 40px;
            margin-right: 14px;
            border-radius: 50%;
            background: #fff;
            padding: 2px;
        }
        .topbar .user-info {
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .dark-toggle {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            margin-right: 18px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .dark-toggle:active {
            color: #ffc107;
        }
        .quick-search {
            position: relative;
            margin-left: 24px;
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 20px;
            padding: 0 12px;
            border: 1.2px solid #ececec;
            box-shadow: 0 1px 4px rgba(102,126,234,0.06);
        }
        .quick-search input {
            border: none;
            background: transparent;
            outline: none;
            padding: 7px 6px 7px 0;
            font-size: 1rem;
            width: 140px;
            color: #222;
        }
        .quick-search button {
            background: none;
            border: none;
            color: #667eea;
            font-size: 1.1rem;
            cursor: pointer;
        }
        .search-results {
            position: absolute;
            top: 38px;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ececec;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
            z-index: 100;
            display: none;
            max-height: 180px;
            overflow-y: auto;
        }
        .search-results div {
            padding: 8px 14px;
            cursor: pointer;
            color: #222;
        }
        .search-results div:hover {
            background: #f4f6fb;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 64px);
        }
        .sidebar {
            width: 230px;
            background: #fff;
            border-right: 1.5px solid #ececec;
            min-height: 100vh;
            box-shadow: 2px 0 8px rgba(102,126,234,0.04);
            padding-top: 24px;
            transition: background 0.3s, color 0.3s;
        }
        body.dark-mode .sidebar {
            background: #23283a;
            color: #e2e6ef;
            border-right: 1.5px solid #23283a;
        }
        .sidebar .logo-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .sidebar .logo-wrap img {
            height: 60px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar li {
            display: flex;
            align-items: center;
            padding: 12px 28px;
            border-radius: 8px;
            margin-bottom: 6px;
            color: #444;
            font-size: 1.08rem;
            cursor: pointer;
            transition: background 0.18s, color 0.18s;
        }
        .sidebar li.active, .sidebar li:hover {
            background: linear-gradient(90deg, #e0e7ff 60%, #f3e8ff 100%);
            color: #4b5bdc;
        }
        body.dark-mode .sidebar li {
            color: #e2e6ef;
        }
        body.dark-mode .sidebar li.active, body.dark-mode .sidebar li:hover {
            background: linear-gradient(90deg, #23283a 60%, #2d3350 100%);
            color: #ffc107;
        }
        .sidebar li i {
            margin-right: 14px;
            font-size: 1.18rem;
        }
        .main-content {
            flex: 1;
            padding: 48px 56px 0 56px;
            transition: color 0.3s;
        }
        .main-content h1 {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 18px;
            letter-spacing: 1px;
            color: #4b5bdc;
        }
        body.dark-mode .main-content h1 {
            color: #ffc107;
        }
        .dashboard-charts {
            display: flex;
            gap: 32px;
            margin-top: 32px;
            flex-wrap: wrap;
        }
        .chart-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 24px 24px 18px 24px;
            min-width: 320px;
            max-width: 400px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: background 0.3s, color 0.3s;
        }
        body.dark-mode .chart-card {
            background: #23283a;
            color: #e2e6ef;
        }
        .chart-title {
            margin-top: 18px;
            font-size: 1.08rem;
            color: #555;
            font-weight: 600;
        }
        body.dark-mode .chart-title {
            color: #ffc107;
        }
        @media (max-width: 900px) {
            .container { flex-direction: column; }
            .sidebar { width: 100vw; min-height: auto; }
            .main-content { padding: 24px 4vw 0 4vw; }
            .dashboard-charts { flex-direction: column; gap: 18px; }
        }
        @media (max-width: 600px) {
            .main-content { padding: 12px 2vw 0 2vw; }
            .dashboard-charts { flex-direction: column; gap: 12px; }
            .chart-card { min-width: 0; max-width: 100vw; padding: 12px 4px 10px 4px; }
            .sidebar { padding-top: 8px; }
            .topbar { padding: 0 8px; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">
            <img src="logo_smk7.png" alt="Logo SMK 7 Baleendah">
            SIINBE
        </div>
        <button class="dark-toggle" id="darkToggle" title="Toggle dark mode"><span id="darkIcon">🌙</span></button>
        <form class="quick-search" action="#" method="get" autocomplete="off" onsubmit="return false;">
            <input type="text" id="quickSearchInput" placeholder="Cari barang/lokasi..." />
            <button type="submit"><i class="fa fa-search"></i></button>
            <div id="quickSearchResults" class="search-results"></div>
        </form>
        <div class="user-info">
            <i class="fa fa-user-circle"></i> Halo, <b><?= htmlspecialchars($_SESSION['username']) ?></b>
        </div>
    </div>
    <div class="container">
        <nav class="sidebar">
            <div class="logo-wrap">
                <img src="logo_smk7.png" alt="Logo SMK 7 Baleendah">
            </div>
            <ul>
                <li class="active"><i class="fa fa-home"></i> Dasbor</li>
                <li onclick="window.location='barang.php'"><i class="fa fa-box"></i> Barang</li>
                <li onclick="window.location='lokasi.php'"><i class="fa fa-location-dot"></i> Lokasi</li>
                <li onclick="window.location='produk.php'"><i class="fa fa-bookmark"></i> Produk</li>
                </ul>
        </nav>
        <main class="main-content">
            <h1>Dasbor</h1>
            <div style="background:#fff;border-radius:14px;box-shadow:0 2px 10px rgba(102,126,234,0.06);padding:32px 28px;max-width:600px;">
                <h2 style="margin-top:0;color:#222;font-size:1.3rem;font-weight:600;">APLIKASI INVENTARIS BARANG</h2>
                <p style="color:#555;font-size:1.08rem;">SMK NEGERI 7 BALEENDAH</p>
            </div>
            <div class="dashboard-charts">
                <div class="chart-card">
                    <canvas id="chartLokasi" width="340" height="220"></canvas>
                    <div class="chart-title">Barang per Lokasi</div>
                </div>
                <div class="chart-card">
                    <canvas id="chartJenis" width="340" height="220"></canvas>
                    <div class="chart-title">Jenis Barang Terbanyak</div>
                </div>
            </div>
        </main>
    </div>
    <script>
    // Chart.js Barang per Lokasi
    const lokasiLabels = <?= json_encode(array_column($barangPerLokasi, 'lokasi')) ?>;
    const lokasiData = <?= json_encode(array_column($barangPerLokasi, 'jumlah')) ?>;
    const ctxLokasi = document.getElementById('chartLokasi').getContext('2d');
    new Chart(ctxLokasi, {
        type: 'bar',
        data: {
            labels: lokasiLabels,
            datasets: [{
                label: 'Jumlah Barang',
                data: lokasiData,
                backgroundColor: '#667eea',
                borderRadius: 8,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
    // Chart.js Jenis Barang Terbanyak
    const jenisLabels = <?= json_encode(array_column($jenisBarang, 'nama')) ?>;
    const jenisData = <?= json_encode(array_column($jenisBarang, 'jumlah')) ?>;
    const ctxJenis = document.getElementById('chartJenis').getContext('2d');
    new Chart(ctxJenis, {
        type: 'doughnut',
        data: {
            labels: jenisLabels,
            datasets: [{
                label: 'Jumlah',
                data: jenisData,
                backgroundColor: [
                    '#667eea', '#764ba2', '#4b5bdc', '#ff6600', '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: { legend: { position: 'bottom' } }
        }
    });
    // Quick Search Autocomplete
    const allData = [...<?= json_encode($allBarang) ?>, ...<?= json_encode($allLokasi) ?>];
    const input = document.getElementById('quickSearchInput');
    const results = document.getElementById('quickSearchResults');
    input.addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();
        if (!val) { results.style.display = 'none'; results.innerHTML = ''; return; }
        const filtered = allData.filter(x => x.toLowerCase().includes(val)).slice(0, 8);
        if (filtered.length === 0) { results.style.display = 'none'; results.innerHTML = ''; return; }
        results.innerHTML = filtered.map(x => `<div>${x}</div>`).join('');
        results.style.display = 'block';
    });
    results.addEventListener('mousedown', function(e) {
        if (e.target.tagName === 'DIV') {
            input.value = e.target.textContent;
            results.style.display = 'none';
        }
    });
    document.addEventListener('click', function(e) {
        if (!results.contains(e.target) && e.target !== input) {
            results.style.display = 'none';
        }
    });
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
    // Inisialisasi dark mode dari localStorage
    setDarkMode(localStorage.getItem('darkMode') === '1');
    darkToggle.onclick = function() {
        setDarkMode(!document.body.classList.contains('dark-mode'));
    };
    </script>
</body>
</html> 