<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Fetch users from database
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ? ORDER BY id DESC");
    $like = "%$search%";
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
}
$users = $stmt->fetchAll();

// Fetch latest log aktivitas (5 terbaru)
$logList = $pdo->query("SELECT l.*, u.username FROM log_aktivitas l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.waktu DESC LIMIT 5")->fetchAll();

// Ambil produk dengan stok <= 3 (limit rendah) atau stok = 0 (habis)
$produkLimit = $pdo->query("SELECT * FROM produk WHERE stok <= 3 ORDER BY stok ASC, nama ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6fb;
            font-family: 'Inter', Arial, sans-serif;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(102,126,234,0.13);
            padding: 32px 36px 36px 36px;
        }
        h2 {
            font-weight: 700;
            color: #4b5bdc;
            margin-bottom: 24px;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
        .users-table th, .users-table td {
            padding: 14px 10px;
            text-align: left;
        }
        .users-table th {
            background: #f6f6fa;
            color: #444;
            font-weight: 600;
            border-bottom: 2px solid #ececec;
        }
        .users-table tr:nth-child(even) {
            background: #f9faff;
        }
        .users-table tr:hover {
            background: #e0e7ff;
            transition: background 0.2s;
        }
        .btn {
            border: none;
            border-radius: 6px;
            padding: 7px 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.18s, color 0.18s;
        }
        .btn-edit {
            background: #ffe066;
            color: #7c5c00;
        }
        .btn-edit:hover {
            background: #ffd43b;
            color: #5c4300;
        }
        .btn-delete {
            background: #ff6b6b;
            color: #fff;
        }
        .btn-delete:hover {
            background: #fa5252;
        }
        .btn-add {
            background: #51cf66;
            color: #fff;
            margin-bottom: 18px;
            float: right;
        }
        .btn-add:hover {
            background: #40c057;
        }
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4b5bdc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 8px;
            font-size: 1.1rem;
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2vw; }
            .users-table th, .users-table td { padding: 8px 4px; font-size: 0.95rem; }
            .btn { font-size: 0.95rem; padding: 6px 10px; }
        }
        .log-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 18px 22px 12px 22px;
            margin-bottom: 24px;
        }
        .log-card ul { list-style: none; padding: 0; margin: 0; }
        .log-card li { padding: 8px 0; border-bottom: 1px solid #ececec; font-size: 1.01rem; display: flex; align-items: center; gap: 8px; }
        .log-card li:last-child { border-bottom: none; }
        .log-time { color: #888; font-size: 0.97rem; margin-right: 8px; min-width: 60px; display: inline-block; }
        .alert-stok {
            background: #fff3cd;
            color: #856404;
            border: 1.5px solid #ffe066;
            border-radius: 8px;
            padding: 16px 22px;
            margin: 0 auto 24px auto;
            max-width: 700px;
            font-size: 1.05rem;
            box-shadow: 0 2px 10px rgba(255,224,102,0.08);
        }
        .alert-stok ul { margin: 8px 0 0 18px; padding: 0; }
        .alert-stok li { margin-bottom: 2px; }
        .alert-stok i { color: #ffc107; margin-right: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="page-title">Users Management</div>
        <div>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <?php if (count($produkLimit) > 0): ?>
    <div class="alert-stok">
        <i class="fas fa-exclamation-triangle"></i>
        <b>Perhatian!</b> Ada barang dengan stok menipis/habis:
        <ul>
            <?php foreach($produkLimit as $p): ?>
                <li>
                    <b><?= htmlspecialchars($p['nama']) ?></b> (Stok: <span style="color:<?= $p['stok']==0?'#dc3545':'#ffc107' ?>;font-weight:600;">
                        <?= $p['stok'] ?>
                    </span>)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="container" style="display: flex; gap: 32px; align-items: flex-start;">
        <div style="flex:2;">
            <div class="content-card">
                <div class="card-header">
                    <h3>All Users</h3>
                    <a href="add_user.php" class="btn btn-add">
                        <i class="fas fa-user-plus"></i> Add New User
                    </a>
                </div>
                <div class="card-body">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <span class="avatar"><?php echo strtoupper(substr($user['username'],0,1)); ?></span>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                <td>
                                    <button class="btn btn-edit"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>)"><i class="fas fa-trash"></i> Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div style="flex:1; min-width:260px;">
            <div class="log-card">
                <h3 style="margin-top:0; color:#4b5bdc; font-size:1.1rem; font-weight:700;">Log Aktivitas Terbaru</h3>
                <ul style="list-style:none; padding:0; margin:0;">
                    <?php foreach($logList as $log): ?>
                    <li style="padding:8px 0; border-bottom:1px solid #ececec; font-size:0.98rem; display:flex; align-items:center; gap:8px;">
                        <span style="color:#888; font-size:0.97rem; min-width:60px; display:inline-block;">
                            <?= date('d/m H:i', strtotime($log['waktu'])) ?>
                        </span>
                        <b><?= htmlspecialchars($log['username']) ?></b> <?= htmlspecialchars($log['aktivitas']) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                alert('User deletion functionality would be implemented here');
            }
        }
    </script>
</body>
</html> 