<?php
$host = 'localhost';
$dbname = 'login_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

function catat_log($pdo, $user_id, $aktivitas) {
    $stmt = $pdo->prepare("INSERT INTO log_aktivitas (user_id, aktivitas) VALUES (?, ?)");
    $stmt->execute([$user_id, $aktivitas]);
}
?> 