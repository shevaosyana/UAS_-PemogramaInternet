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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', Arial, sans-serif;
        }

        body {
            background: #1a1c24;
            color: #e2e6ef;
        }

        .main-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: #23283a;
            padding: 2rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar-logo {
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .sidebar-logo img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }

        .nav-menu {
            width: 100%;
            padding: 0 1rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: #e2e6ef;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-item.active, .nav-item:hover {
            background: #2a2f44;
            color: #ffc107;
        }

        .nav-item i {
            margin-right: 1rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .page-title {
            color: #ffc107;
            font-size: 2rem;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #23283a;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            color: #ffc107;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #e2e6ef;
            font-size: 1rem;
        }

        .info-card {
            background: #23283a;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .info-card h2 {
            color: #ffc107;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .info-card p {
            color: #e2e6ef;
            line-height: 1.6;
        }

        /* Charts Container */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .chart-card {
            background: #23283a;
            border-radius: 12px;
            padding: 1.5rem;
            height: 300px;
        }

    </style>
</head>
<body>
    <div class="main-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-logo">
                <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah">
                <h2 style="color: #ffc107;">SIINBE</h2>
            </div>
            <div class="nav-menu">
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Dasbor</span>
                </a>
                <a href="barang.php" class="nav-item">
                    <i class="fas fa-box"></i>
                    <span>Barang</span>
                </a>
                <a href="lokasi.php" class="nav-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Lokasi</span>
                </a>
                <a href="produk.php" class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Produk</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="page-title">Dasbor</h1>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalBarang; ?></div>
                    <div class="stat-label">Total Barang</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalLokasi; ?></div>
                    <div class="stat-label">Total Lokasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo $totalProduk; ?></div>
                    <div class="stat-label">Kategori Produk</div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="info-card">
                <h2>APLIKASI INVENTARIS BARANG</h2>
                <p>SMK NEGERI 7 BALEENDAH</p>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <div class="chart-card">
                    <!-- Tempat untuk grafik statistik -->
                </div>
                <div class="chart-card">
                    <!-- Tempat untuk grafik donut -->
                </div>
            </div>
        </main>
    </div>

    <!-- Tambahkan script untuk charts jika diperlukan -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html> 