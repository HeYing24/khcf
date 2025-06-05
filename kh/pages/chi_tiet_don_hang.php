<?php
include '../config/config.php';

if (!isset($_GET['id'])) {
    echo "Không tìm thấy đơn hàng.";
    exit;
}

$id_donhang = intval($_GET['id']);

// Lấy thông tin đơn hàng
$sql_donhang = "SELECT id, ngay_tao, trang_thai, tong_tien FROM donhang WHERE id = ?";
$stmt1 = $conn->prepare($sql_donhang);
$stmt1->bind_param("i", $id_donhang);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1->num_rows === 0) {
    echo "Đơn hàng không tồn tại.";
    exit;
}

$donhang = $result1->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_chitiet = "
    SELECT sp.ten_san_pham, ct.so_luong, ct.don_gia, (ct.so_luong * ct.don_gia) AS thanh_tien
    FROM chitiet_donhang ct
    JOIN sanpham sp ON ct.id_san_pham = sp.id
    WHERE ct.id_donhang = ?
";
$stmt2 = $conn->prepare($sql_chitiet);
$stmt2->bind_param("i", $id_donhang);
$stmt2->execute();
$result2 = $stmt2->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        h2 { margin-bottom: 10px; }
        .back-btn { margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>

<h2>Chi tiết đơn hàng #<?= $donhang['id'] ?></h2>
<p><strong>Ngày đặt:</strong> <?= $donhang['ngay_tao'] ?></p>
<p><strong>Trạng thái:</strong> <?= htmlspecialchars($donhang['trang_thai']) ?></p>

<?php if ($result2->num_rows > 0): ?>
    <table>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá </th>
            <th>Thành tiền </th>
        </tr>
        <?php while ($row = $result2->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['ten_san_pham']) ?></td>
                <td><?= $row['so_luong'] ?></td>
                <td><?= number_format($row['don_gia'], 0, ',', '.') ?>.000 đ</td>
                <td><?= number_format($row['thanh_tien'], 0, ',', '.') ?>.000 đ</td>
            </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Tổng tiền:</strong></td>
            <td><strong><?= number_format($donhang['tong_tien'], 0, ',', '.') ?>.000 đ</strong></td>
        </tr>
    </table>
<?php else: ?>
    <p>Không có sản phẩm nào trong đơn hàng này.</p>
<?php endif; ?>

<a class="back-btn" href="don_hang_cua_toi.php">
    <button>← Quay lại danh sách đơn hàng</button>
</a>

</body>
</html>
