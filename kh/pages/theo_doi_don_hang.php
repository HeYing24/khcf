<?php
session_start();
include("../config/config.php");

// Kiểm tra đăng nhập
if (!isset($_SESSION['id_khachhang'])) {
    header("Location: ../pages/dang_nhap.php");
    exit();
}

// Lấy id đơn hàng
if (!isset($_GET['id_donhang'])) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

$id_donhang = (int)$_GET['id_donhang'];
$id_khachhang = $_SESSION['id_khachhang'];

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM donhang WHERE id = ? AND id_khach_hang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_donhang, $id_khachhang);
$stmt->execute();
$result = $stmt->get_result();
$donhang = $result->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_chitiet = "SELECT sp.ten_san_pham, sp.gia, ct.so_luong 
                FROM chitiet_donhang ct 
                JOIN sanpham sp ON ct.id_san_pham = sp.id 
                WHERE ct.id_donhang = ?";
$stmt_chitiet = $conn->prepare($sql_chitiet);
if (!$stmt_chitiet) {
    die("Lỗi prepare chi tiết đơn hàng: " . $conn->error);
}
$stmt_chitiet->bind_param("i", $id_donhang);

$stmt_chitiet->execute();
$result_chitiet = $stmt_chitiet->get_result();
$chitiet_donhang = $result_chitiet->fetch_all(MYSQLI_ASSOC);

if (!$donhang) {
    echo "Đơn hàng không tồn tại.";
    exit();
}

// Trạng thái đơn hàng
$trang_thai = [
    0 => "Chờ xử lý",
    1 => "Đang giao",
    2 => "Đã giao",
    3 => "Đã huỷ"
];

// Nếu trạng thái không xác định
$trang_thai_donhang = $trang_thai[$donhang['trang_thai']] ?? "Chờ xử lý";


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Theo dõi đơn hàng</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2, h3 {
            color: #6f4e37;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #6f4e37;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #6f4e37;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
            text-align: center;
        }

        .btn:hover {
            background-color: #59342c;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #4CAF50;
        }

        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🚚 Theo dõi đơn hàng #<?= htmlspecialchars($id_donhang) ?></h2>
    <p><strong>Ngày đặt:</strong> <?= htmlspecialchars($donhang['ngay_tao']) ?></p>
    <p><strong>Tổng tiền:</strong> <?= number_format($donhang['tong_tien']) ?>.000 đ</p>
    <p><strong>Trạng thái đơn hàng:</strong> <span style="color: blue;"><?= $trang_thai_donhang  ?></span></p>
    <h3>🛒 Sản phẩm trong đơn hàng</h3>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Tên sản phẩm</th>
        <th>Đơn giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    <?php foreach ($chitiet_donhang as $sp): ?>
        <tr>
            <td><?= htmlspecialchars($sp['ten_san_pham']) ?></td>
            <td><?= number_format($sp['gia']) ?>.000 đ</td>
            <td><?= $sp['so_luong'] ?></td>
            <td><?= number_format($sp['gia'] * $sp['so_luong']) ?>.000 đ</td>
        </tr>
    <?php endforeach; ?>
</table>

    <br>
    <a href="don_hang_cua_toi.php" class="btn">🔙 Quay lại lịch sử đơn hàng</a>
</div>
</body>
</html>
