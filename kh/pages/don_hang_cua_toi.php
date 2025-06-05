<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id_khachhang'])) {
    echo "<script>alert('Vui lòng đăng nhập để xem đơn hàng của bạn!'); window.location='dang_nhap.php';</script>";
    exit;
}

$id_khachhang = $_SESSION['id_khachhang'];

// Truy vấn đơn hàng
$sql = "SELECT * FROM donhang WHERE id_khach_hang = ? ORDER BY ngay_tao DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_khachhang);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đơn hàng của tôi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f2ef;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #5e4632;
        }
        .order {
            background: white;
            margin: 20px 0;
            padding: 15px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            margin: 10px 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #8d6e63;
            color: white;
        }
        .status {
            padding: 4px 10px;
            border-radius: 4px;
            color: white;
            display: inline-block;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            display: inline-block;
            padding: 10px 20px;
            background-color:  #59342c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
} 
    </style>
</head>
<body>

<h2>Đơn hàng của tôi</h2>

<?php
if ($result->num_rows == 0) {
    echo "<p style='text-align:center;'>Chưa có đơn hàng nào!</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='order'>";
        echo "<h3>Đơn hàng #" . $row['id'] . " - Ngày tạo: " . date('d/m/Y', strtotime($row['ngay_tao'])) . "</h3>";
        echo "<p><strong>Tổng tiền: </strong>" . number_format($row['tong_tien'], 0, ',', '.') . ".000 đ</p>";

        // Trạng thái đơn hàng
        $trang_thai_text = '';
        $trang_thai_color = '';
        switch ($row['trang_thai']) {
            case 0:
                $trang_thai_text = 'Chờ xử lý';
                $trang_thai_color = '#6c757d';
                break;
            case 1:
                $trang_thai_text = 'Đang giao';
                $trang_thai_color = '#ffc107';
                break;
            case 2:
                $trang_thai_text = 'Đã giao';
                $trang_thai_color = '#28a745';
                break;
            case 3:
                $trang_thai_text = 'Đã huỷ';
                $trang_thai_color = '#dc3545';
                break;
        }

        echo "<p><strong>Trạng thái: </strong><span class='status' style='background-color: $trang_thai_color;'>$trang_thai_text</span></p>";

        // Chi tiết đơn hàng
        $sql_ct = "SELECT ten_san_pham, so_luong, don_gia, (so_luong * don_gia) AS thanh_tien 
                   FROM chitiet_donhang WHERE id_donhang = ?";
        $stmt_ct = $conn->prepare($sql_ct);
        $stmt_ct->bind_param("i", $row['id']);
        $stmt_ct->execute();
        $result_ct = $stmt_ct->get_result();

        if ($result_ct->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>";
            while ($item = $result_ct->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($item['ten_san_pham']) . "</td>";
                echo "<td>" . $item['so_luong'] . "</td>";
                echo "<td>" . number_format($item['don_gia'], 0, ',', '.') . ".000 đ</td>";
                echo "<td>" . number_format($item['thanh_tien'], 0, ',', '.') . ".000 đ</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Không có chi tiết đơn hàng.</p>";
        }

        echo "</div>";
    }
}
?>

<div class="back-link">
    <a href="index.php">← Quay lại trang chủ</a>
</div>

</body>
</html>

<?php
$stmt->close();
?>
