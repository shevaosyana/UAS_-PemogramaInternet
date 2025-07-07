<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
header('Content-Type: application/json');

$nama   = $_POST['nama'] ?? '';
$lokasi = $_POST['lokasi'] ?? '';
$merk   = $_POST['merk'] ?? '';
$status = $_POST['status'] ?? '';
$nomor  = $_POST['nomor'] ?? '';

if ($nama && $lokasi && $merk && $status && $nomor) {
    $stmt = $pdo->prepare("INSERT INTO barang (nama, lokasi, merk, status, nomor) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$nama, $lokasi, $merk, $status, $nomor])) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal insert ke database']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
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

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'tambah_sukses'): ?>
    <div class="notif notif-success" id="notifBarang">Barang berhasil ditambahkan!</div>
<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'tambah_gagal'): ?>
    <div class="notif notif-error" id="notifBarang">Gagal menambahkan barang!</div>
<?php endif; ?> 

<style>
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
.notif.hide { opacity: 0; }
.notif-success { background: #d4edda; color: #155724; border: 1.5px solid #51cf66; }
.notif-error { background: #f8d7da; color: #721c24; border: 1.5px solid #ff6b6b; }
body.dark-mode .notif-success { background: #223a2a; color: #51cf66; border-color: #51cf66; }
body.dark-mode .notif-error { background: #3a2222; color: #ff6b6b; border-color: #ff6b6b; }
</style> 