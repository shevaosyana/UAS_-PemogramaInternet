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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
    <style>
        body {
            background: #f6f8fc;
            font-family: 'Inter', Arial, sans-serif;
            color: #222;
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
        .back-btn, .logout-btn {
            background: #6c757d;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.97rem;
            margin-right: 1rem;
            transition: background 0.18s;
        }
        .logout-btn {
            background: #dc3545;
            margin-right: 0;
        }
        .back-btn:hover { background: #495057; }
        .logout-btn:hover { background: #b52a37; }
        .container {
            display: flex;
            gap: 32px;
            align-items: flex-start;
            max-width: 1100px;
            margin: 40px auto;
        }
        .content-card, .log-card, .calendar-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(102,126,234,0.06);
            padding: 24px 32px 18px 32px;
            margin-bottom: 24px;
        }
        .content-card { flex: 2; }
        .log-card, .calendar-card { flex: 1; min-width: 260px; }
        .card-header {
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header h3, h3 {
            color: #4b5bdc;
            font-size: 1.1rem;
            font-weight: 700;
            margin-top: 0;
        }
        .btn, .btn-edit, .btn-delete, .btn-add {
            border-radius: 7px;
            font-weight: 600;
            font-family: inherit;
            transition: background 0.18s, color 0.18s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
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
            background: linear-gradient(90deg, #51cf66 60%, #40c057 100%);
            color: #fff;
        }
        .btn-add:hover {
            background: linear-gradient(90deg, #40c057 60%, #51cf66 100%);
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
        .log-card {
            margin-bottom: 18px;
        }
        .log-card ul { list-style: none; padding: 0; margin: 0; }
        .log-card li { padding: 8px 0; border-bottom: 1px solid #ececec; font-size: 1.01rem; display: flex; align-items: center; gap: 8px; }
        .log-card li:last-child { border-bottom: none; }
        .log-time { color: #888; font-size: 0.97rem; margin-right: 8px; min-width: 60px; display: inline-block; }
        .calendar-card {
            margin-top: 24px;
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            font-size: 0.98rem;
        }
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
        @media (max-width: 900px) {
            .container { flex-direction: column; gap: 18px; }
            .content-card, .log-card, .calendar-card { padding: 18px 8vw 12px 8vw; }
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2vw; }
            .users-table th, .users-table td { padding: 8px 4px; font-size: 0.95rem; }
            .btn { font-size: 0.95rem; padding: 6px 10px; }
            .content-card, .log-card, .calendar-card { padding: 12px 2vw 8px 2vw; }
        }
        .sidebar {
            width: 220px;
            background: #fff;
            border-right: 1.5px solid #ececec;
            min-height: 100vh;
            box-shadow: 2px 0 8px rgba(102,126,234,0.04);
            padding-top: 24px;
            transition: background 0.3s, color 0.3s;
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
        .sidebar li i {
            margin-right: 14px;
            font-size: 1.18rem;
        }
        @media (max-width: 900px) {
            .sidebar { width: 100vw; border-right: none; border-bottom: 1.5px solid #ececec; }
        }
        body.dark-mode {
            background: #181c24;
            color: #e2e6ef;
        }
        body.dark-mode .topbar {
            background: linear-gradient(90deg, #23283a 60%, #2d3350 100%);
            color: #ffc107;
        }
        body.dark-mode .sidebar {
            background: #23283a;
            color: #e2e6ef;
            border-right: 1.5px solid #23283a;
        }
        body.dark-mode .sidebar li {
            color: #e2e6ef;
        }
        body.dark-mode .sidebar li.active, body.dark-mode .sidebar li:hover {
            background: linear-gradient(90deg, #23283a 60%, #2d3350 100%);
            color: #ffc107;
        }
        body.dark-mode .content-card,
        body.dark-mode .log-card,
        body.dark-mode .calendar-card {
            background: #23283a;
            color: #e2e6ef;
        }
        body.dark-mode .users-table th {
            background: #23283a;
            color: #ffc107;
        }
        body.dark-mode .users-table tr:hover {
            background: #2d3350;
        }
        body.dark-mode .btn-edit {
            background: #fff3cd;
            color: #856404;
        }
        body.dark-mode .btn-delete {
            background: #fa5252;
            color: #fff;
        }
        body.dark-mode .btn-add {
            background: linear-gradient(90deg, #40c057 60%, #51cf66 100%);
            color: #fff;
        }
        body.dark-mode .alert-stok {
            background: #2d3350;
            color: #ffc107;
            border: 1.5px solid #ffc107;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="logo">
            <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah">
            SIINBE
        </div>
        <div class="user-info">
            Users Management
        </div>
        <button class="dark-toggle" id="darkToggle" title="Toggle dark mode"><span id="darkIcon">🌙</span></button>
    </div>
    <div style="display:flex; min-height:calc(100vh - 64px);">
        <nav class="sidebar">
            <div class="logo-wrap" style="text-align:center; margin-bottom:24px;">
                <img src="logo_smk7baleendah.png" alt="Logo SMK 7 Baleendah" style="height:60px; border-radius:50%; background:#fff; box-shadow:0 2px 8px rgba(102,126,234,0.08);">
            </div>
            <ul>
                <li onclick="window.location='dashboard.php'" style="cursor:pointer;"><i class="fa fa-home"></i> Dashboard</li>
                <li class="active"><i class="fa fa-user"></i> Users</li>
                <li onclick="window.location='barang.php'" style="cursor:pointer;"><i class="fa fa-box"></i> Barang</li>
                <li onclick="window.location='lokasi.php'" style="cursor:pointer;"><i class="fa fa-location-dot"></i> Lokasi</li>
                <li onclick="window.location='produk.php'" style="cursor:pointer;"><i class="fa fa-bookmark"></i> Produk</li>
            </ul>
        </nav>
        <div style="flex:1;">

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

    <div class="container">
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
        <div style="flex:1; min-width:260px;">
            <div class="log-card">
                <h3>Log Aktivitas Terbaru</h3>
                <ul>
                    <?php foreach($logList as $log): ?>
                    <li>
                        <span class="log-time"><?= date('d/m H:i', strtotime($log['waktu'])) ?></span>
                        <b><?= htmlspecialchars($log['username']) ?></b> <?= htmlspecialchars($log['aktivitas']) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="calendar-card">
                <h3>Kalender Inventaris</h3>
                <div id="calendar"></div>
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
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 350,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: [
                    {
                        title: 'Servis Komputer Lab',
                        start: new Date().toISOString().slice(0,10),
                        description: 'Jadwal servis komputer di Lab Komputer'
                    },
                    {
                        title: 'Barang Masuk: Printer',
                        start: new Date(new Date().setDate(new Date().getDate()+2)).toISOString().slice(0,10),
                        description: 'Penerimaan printer baru'
                    },
                    {
                        title: 'Barang Keluar: Proyektor',
                        start: new Date(new Date().setDate(new Date().getDate()+5)).toISOString().slice(0,10),
                        description: 'Pengeluaran proyektor untuk ruang kelas'
                    }
                ],
                eventClick: function(info) {
                    alert(info.event.title + (info.event.extendedProps.description ? '\n' + info.event.extendedProps.description : ''));
                }
            });
            calendar.render();
        }
    });
    </script>
    <script>
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
    setDarkMode(localStorage.getItem('darkMode') === '1');
    darkToggle.onclick = function() {
        setDarkMode(!document.body.classList.contains('dark-mode'));
    };
    </script>
</div>
</div>
</body>
</html> 