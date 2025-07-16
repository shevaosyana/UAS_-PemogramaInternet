<?php
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit(); }
require_once 'config.php';
$lokasi = isset($_GET['lokasi']) ? trim($_GET['lokasi']) : '';
if ($lokasi === '') { echo json_encode([]); exit(); }
$stmt = $pdo->prepare("SELECT nama, merk, status, nomor FROM barang WHERE lokasi = ? ORDER BY id DESC");
$stmt->execute([$lokasi]);
echo json_encode($stmt->fetchAll()); 