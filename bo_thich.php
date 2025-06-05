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
    $sql = "DELETE FROM thich_sanpham WHERE id_khach_hang = ? AND id_san_pham = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_khachhang, $id_san_pham);
    $stmt->execute();
}

header("Location: san_pham_yeu_thich.php");
