<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
require_once 'config.php';

// Ambil data dari POST
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$lokasi = isset($_POST['lokasi']) ? trim($_POST['lokasi']) : '';
$merk = isset($_POST['merk']) ? trim($_POST['merk']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$nomor = isset($_POST['nomor']) ? trim($_POST['nomor']) : '';

if ($nama === '' || $lokasi === '' || $merk === '' || $status === '' || $nomor === '') {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO barang (nama, lokasi, merk, status, nomor) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nama, $lokasi, $merk, $status, $nomor]);
    // Catat log aktivitas
    if (isset($_SESSION['user_id'])) {
        catat_log($pdo, $_SESSION['user_id'], "Menambahkan barang $nama");
    }
    echo json_encode(['success' => true, 'message' => 'Barang berhasil ditambahkan']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Gagal menambah barang: ' . $e->getMessage()]);
}

// Ambil data barang dari database
$stmt = $pdo->query("SELECT * FROM barang ORDER BY id DESC");
$daftar_barang = $stmt->fetchAll();

// Ambil data lokasi dan produk dari database
$lokasiList = $pdo->query("SELECT * FROM lokasi ORDER BY id DESC")->fetchAll();
$produkList = $pdo->query("SELECT * FROM produk")->fetchAll();
?>

<div class="topbar">
    <img src="logo_smk7.png" alt="Logo SMK 7 Baleendah" style="height:40px;vertical-align:middle;margin-right:12px;">
    <span class="logo">SIINBE</span>
    <!-- ... -->
</div>

<div>
    <?php
        $total = count($daftar_barang);
        if ($total > 0) {
            echo "Showing 1-$total of $total";
        } else {
            echo "No data";
        }
    ?>
</div>

<label>Lokasi</label><br>
<select name="lokasi" required>
    <?php foreach($lokasiList as $l): ?>
        <option value="<?= htmlspecialchars($l['nama']) ?>"><?= htmlspecialchars($l['nama']) ?></option>
    <?php endforeach; ?>
</select>

<label>Produk</label><br>
<select name="produk" required>
    <?php foreach($produkList as $p): ?>
        <option value="<?= htmlspecialchars($p['nama']) ?>"><?= htmlspecialchars($p['nama']) ?></option>
    <?php endforeach; ?> 
</div>

<div class="sidebar">
    <div style="text-align:center;margin-bottom:18px;">
        <img src="logo_smk7.png" alt="Logo SMK 7 Baleendah" style="height:60px;">
    </div>
    <!-- ...menu... -->
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lokasi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($lokasiList as $i => $l): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><?= htmlspecialchars($l['nama']) ?></td>
            <td>
                <!-- Tombol edit/hapus bisa ditambahkan di sini -->
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table> 