<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = '';
$success = '';

$host = 'localhost';
$db   = 'login_db';  // sesuai dengan nama database di gambar
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

require_once 'config.php';

// Hitung total barang
$stmt = $pdo->query("SELECT COUNT(*) FROM barang");
$totalBarang = $stmt->fetchColumn();

// Hitung total lokasi
$stmt = $pdo->query("SELECT COUNT(*) FROM lokasi");
$totalLokasi = $stmt->fetchColumn();

// Hitung total produk
$stmt = $pdo->query("SELECT COUNT(*) FROM produk");
$totalProduk = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIINBE</title>
    <style>
        body {
  background: #181c24;
  color: #fff;
  font-family: 'Inter', Arial, sans-serif;
  margin: 0;
  padding: 0;
}

.main-layout {
  display: flex;
  min-height: 100vh;
}

.sidebar {
  width: 230px;
  background: #23283a;
  padding: 32px 0 0 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
}

.sidebar-logo {
  color: #FFC107;
  font-size: 1.3rem;
  font-weight: 800;
  letter-spacing: 2px;
  margin-bottom: 40px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
}

.sidebar-menu {
  list-style: none;
  padding: 0;
  width: 100%;
}

.sidebar-menu li {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 14px 32px;
  font-size: 1.13rem;
  color: #e2e6ef;
  cursor: pointer;
  border-radius: 8px;
  margin-bottom: 8px;
  transition: background 0.18s, color 0.18s;
}

.sidebar-menu li.active,
.sidebar-menu li:hover {
  background: #181c24;
  color: #FFC107;
}

.sidebar-menu li i {
  font-size: 1.25rem;
  min-width: 22px;
  text-align: center;
}

.dashboard-content {
  flex: 1;
  padding: 48px 40px;
  background: #181c24;
}

.dashboard-title {
  color: #FFC107;
  font-size: 2.3rem;
  font-weight: 900;
  margin-bottom: 36px;
  letter-spacing: 1px;
}

.dashboard-cards {
  display: flex;
  gap: 32px;
  flex-wrap: wrap;
  margin-bottom: 32px;
}

.dashboard-card {
  background: #23283a;
  border-radius: 18px;
  box-shadow: 0 2px 12px rgba(102,126,234,0.08);
  padding: 32px 38px 28px 38px;
  min-width: 220px;
  flex: 1 1 260px;
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 18px;
}

.card-icon {
  color: #FFC107;
  font-size: 2.2rem;
  margin-bottom: 12px;
}

.card-label {
  color: #FFC107;
  font-size: 1.13rem;
  font-weight: 700;
  margin-bottom: 10px;
  letter-spacing: 0.5px;
}

.card-value {
  color: #FFC107;
  font-size: 2.5rem;
  font-weight: 900;
  letter-spacing: 2px;
}

.dashboard-info-card {
  background: #fff;
  color: #23283a;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(102,126,234,0.06);
  padding: 32px 28px;
  max-width: 600px;
  margin: 0 auto 32px auto;
  text-align: center;
}

.dashboard-info-card h2 {
  margin-top: 0;
  color: #23283a;
  font-size: 1.3rem;
  font-weight: 600;
}

.dashboard-info-card p {
  color: #555;
  font-size: 1.08rem;
}

@media (max-width: 900px) {
  .main-layout { flex-direction: column; }
  .sidebar { width: 100vw; flex-direction: row; min-height: unset; padding: 16px 0; }
  .sidebar-menu { display: flex; flex-direction: row; gap: 8px; }
  .sidebar-menu li { margin-bottom: 0; padding: 10px 18px; }
  .dashboard-content { padding: 32px 8vw; }
  .dashboard-cards { flex-direction: column; gap: 18px; }
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>
<body>
<div class="main-layout">
  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="sidebar-logo">
      <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah" style="height:48px;margin-bottom:18px;">
      <span>SIINBE</span>
    </div>
    <ul class="sidebar-menu">
      <li class="active"><i class="fa-solid fa-users"></i> Pengguna</li>
      <li><i class="fa-solid fa-box"></i> Barang</li>
      <li><i class="fa-solid fa-location-dot"></i> Lokasi</li>
      <li><i class="fa-solid fa-tags"></i> Produk</li>
    </ul>
  </nav>

  <!-- Dashboard Content -->
  <div class="dashboard-content">
    <h1 class="dashboard-title">Dasbor</h1>
    <div class="dashboard-cards">
      <div class="dashboard-card">
        <div class="card-icon"><i class="fa-solid fa-box"></i></div>
        <div class="card-label">Total Barang</div>
        <div class="card-value">1</div>
      </div>
      <div class="dashboard-card">
        <div class="card-icon"><i class="fa-solid fa-location-dot"></i></div>
        <div class="card-label">Total Lokasi</div>
        <div class="card-value">0</div>
      </div>
      <div class="dashboard-card">
        <div class="card-icon"><i class="fa-solid fa-tags"></i></div>
        <div class="card-label">Kategori Produk</div>
        <div class="card-value">0</div>
      </div>
    </div>
  </div>
</div>
<div class="barang-container">
  <!-- QR Code Section -->
  <div class="barang-top">
    <div class="qr-card">
      <canvas id="barcodeStatic"></canvas>
      <div class="qr-desc">Scan untuk daftar barang</div>
    </div>
    <div class="barang-actions">
      <button class="scan-btn"><i class="fa fa-qrcode"></i> Scan QR</button>
      <button class="add-btn"><i class="fa fa-plus"></i> Tambah</button>
    </div>
    <div class="searchbar">
      <input type="text" placeholder="Cari barang..." />
      <i class="fa fa-search"></i>
    </div>
  </div>

  <!-- Tabel Barang -->
  <table class="barang-table">
    <thead>
      <tr>
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
      <tr>
        <td>1</td>
        <td>Komputer 1</td>
        <td>Lab</td>
        <td>Asus</td>
        <td><span class="badge-status badge-aktif">Aktif</span></td>
        <td>INV-001</td>
        <td>
          <button class="aksi-btn" title="QR"><i class="fa fa-qrcode"></i></button>
          <button class="aksi-btn" title="Edit"><i class="fa fa-edit"></i></button>
          <button class="aksi-btn" title="Hapus"><i class="fa fa-trash"></i></button>
        </td>
      </tr>
      <!-- Tambahkan baris lain sesuai data -->
    </tbody>
  </table>
</div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
<script>
  new QRious({
    element: document.getElementById('barcodeStatic'),
    value: 'DAFTAR_BARANG',
    size: 120,
    background: '#fff',
    foreground: '#222',
    level: 'H'
  });
</script>