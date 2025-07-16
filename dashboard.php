<?php
require_once 'config.php';
// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
$totalBarang = $totalBarang ?? 0;
$totalLokasi = $totalLokasi ?? 0;
$totalProduk = $totalProduk ?? 0;
$barangPerLokasi = $barangPerLokasi ?? [];
$jenisBarang = $jenisBarang ?? [];
$allBarang = $allBarang ?? [];
$allLokasi = $allLokasi ?? [];
$logList = $logList ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIINBE</title>
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
            overflow-x: hidden;
        }
        body.dark-mode {
            background: #181c24;
            color: #e2e6ef;
        }
        .topbar {
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            background: linear-gradient(90deg, #667eea 60%, #764ba2 100%);
            color: #fff;
            padding: 0 36px;
            box-sizing: border-box;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
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
            margin-top: 64px; /* agar tidak ketutup topbar */
            min-height: calc(100vh - 64px);
        }
        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1.5px solid #ececec;
            min-height: calc(100vh - 64px);
            box-shadow: 2px 0 8px rgba(102,126,234,0.04);
            padding-top: 24px;
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
            width: 100%;
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
            padding: 32px 24px;
            min-width: 0;
            background: #f6f8fc;
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
            .container {
                flex-direction: column;
                max-width: 100vw;
            }
            .sidebar {
                width: 100vw;
                min-width: 0;
                border-right: none;
                border-bottom: 1.5px solid #ececec;
                min-height: unset;
                box-shadow: none;
                flex-direction: row;
                align-items: flex-start;
                justify-content: flex-start;
            }
            .sidebar ul {
                display: flex;
                flex-direction: row;
                width: 100vw;
            }
            .sidebar li {
                flex: 1;
                padding: 12px 0;
                justify-content: center;
            }
            .main-content {
                padding: 18px 6vw;
            }
            .dashboard-charts { flex-direction: column; gap: 18px; }
        }
        @media (max-width: 600px) {
            .main-content {
                padding: 10px 2vw;
            }
            .sidebar {
                padding-top: 10px;
            }
            .dashboard-charts { flex-direction: column; gap: 12px; }
            .chart-card { min-width: 0; max-width: 100vw; padding: 12px 4px 10px 4px; }
            .sidebar { padding-top: 8px; }
            .topbar { padding: 0 8px; }
        }
        .log-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 24px 28px 18px 28px;
            margin-top: 36px;
            max-width: 600px;
        }
        body.dark-mode .log-card {
            background: #23283a;
            color: #e2e6ef;
        }
        .log-card h3 {
            margin-top: 0;
            color: #4b5bdc;
            font-size: 1.15rem;
            font-weight: 700;
        }
        body.dark-mode .log-card h3 {
            color: #ffc107;
        }
        .log-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .log-card li {
            padding: 8px 0;
            border-bottom: 1px solid #ececec;
            font-size: 1.01rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        body.dark-mode .log-card li {
            border-bottom: 1px solid #23283a;
        }
        .log-card li:last-child {
            border-bottom: none;
        }
        .log-time {
            color: #888;
            font-size: 0.97rem;
            margin-right: 8px;
            min-width: 60px;
            display: inline-block;
        }
        .statistik-ringkasan {
            display: flex;
            gap: 28px;
            margin-bottom: 32px;
            margin-top: 18px;
            flex-wrap: wrap;
        }
        .stat-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 22px 32px 18px 32px;
            min-width: 160px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: background 0.3s, color 0.3s;
        }
        body.dark-mode .stat-card {
            background: #23283a;
            color: #e2e6ef;
        }
        .stat-title {
            font-size: 1.01rem;
            color: #888;
            margin-bottom: 8px;
            font-weight: 500;
        }
        body.dark-mode .stat-title {
            color: #ffc107;
        }
        .stat-value {
            font-size: 2.1rem;
            font-weight: 700;
            color: #4b5bdc;
        }
        body.dark-mode .stat-value {
            color: #ffc107;
        }
    </style>
</head>
<body>
<div class="container-dashboard">
  <aside class="sidebar">
    <div class="logo">
      <img src="logo_smk7baleendah.png" alt="Logo" />
    </div>
    <nav>
      <ul>
        <li class="active"><span class="icon">🏠</span> Dasbor</li>
        <li><span class="icon">💼</span> Barang</li>
        <li><span class="icon">📍</span> Lokasi</li>
        <li><span class="icon">🏷️</span> Produk</li>
      </ul>
    </nav>
  </aside>
  <main class="main-content">
    <h1>Dasbor</h1>
    <div class="summary-cards">
      <div class="card">
        <div class="card-title">Total Barang</div>
        <div class="card-value">1</div>
      </div>
      <div class="card">
        <div class="card-title">Total Lokasi</div>
        <div class="card-value">0</div>
      </div>
      <div class="card">
        <div class="card-title">Kategori Produk</div>
        <div class="card-value">0</div>
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