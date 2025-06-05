<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id_khachhang'])) {
    header("Location: login.php");
    exit;
}

$id_khachhang = $_SESSION['id_khachhang'];
$id_san_pham = $_GET['id'] ?? 0;

if ($id_san_pham) {
    $sql = "INSERT IGNORE INTO thich_sanpham (id_khach_hang, id_san_pham) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_khachhang, $id_san_pham);
    $stmt->execute();
}

header("Location: ds_sp.php");
exit;
