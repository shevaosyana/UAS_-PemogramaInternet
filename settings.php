<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
</head>
<body>
    <h1>Settings Page</h1>
    <p>Pengaturan aplikasi akan ditampilkan di sini.</p>
    <a href="dashboard.php">Kembali ke Dashboard</a>
</body>
</html> 