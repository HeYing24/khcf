<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id_khachhang'])) {
    header("Location: login.php");
    exit;
}

// Kiểm tra có danh sách sản phẩm được chọn không
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['chon_sp'])) {
    echo "<script>alert('Không có sản phẩm nào được chọn để thanh toán!'); window.location='gio_hang.php';</script>";
    exit;
}

$gio_hang = $_SESSION['gio_hang'] ?? [];
$ds_chon = explode(',', $_POST['chon_sp']);
$san_pham_chon = [];
$tong_tien = 0;

// Lọc danh sách sản phẩm hợp lệ
foreach ($ds_chon as $id) {
    if (isset($gio_hang[$id])) {
        $item = $gio_hang[$id];
        $item['thanh_tien'] = $item['gia'] * $item['so_luong'];
        $tong_tien += $item['thanh_tien'];
        $san_pham_chon[$id] = $item;
    }
}

if (empty($san_pham_chon)) {
    echo "<script>alert('Không tìm thấy sản phẩm hợp lệ trong giỏ hàng!'); window.location='gio_hang.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
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
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #8d6e63;
            color: white;
        }
        .form-info {
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 6px rgba(0,0,0,0.1);
        }
        .form-info input, .form-info textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-submit {
            background-color: #4caf50;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .back-link {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1000;
}  
.btn-back {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            padding: 10px 20px;
            background-color: #59342c;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color:  #59342c;
        }

    </style>
</head>
<body>

<h2>🧾 Xác nhận đơn hàng</h2>

<table>
    <tr>
        <th>Hình ảnh</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
    </tr>
    <?php foreach ($san_pham_chon as $sp): ?>
    <tr>
        <td><img src="../assets/images/<?= $sp['hinh_anh'] ?>" alt="<?= $sp['ten'] ?>" width="60"></td>
        <td><?= htmlspecialchars($sp['ten']) ?></td>
        <td><?= number_format($sp['gia'], 0, ',', '.') ?>.000 đ</td>
        <td><?= $sp['so_luong'] ?></td>
        <td><?= number_format($sp['thanh_tien'], 0, ',', '.') ?>.000 đ</td>
    </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4" style="text-align: right;"><strong>Tổng tiền:</strong></td>
        <td><strong><?= number_format($tong_tien, 0, ',', '.') ?>.000 đ</strong></td>
    </tr>
</table>

<div class="form-info">
    <h3>🧍‍♂️ Thông tin khách hàng</h3>
    <form action="xuly_thanh_toan.php" method="POST">
        <input type="text" name="ten" placeholder="Họ và tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="sdt" placeholder="Số điện thoại" required>
        <textarea name="dia_chi" placeholder="Địa chỉ giao hàng" rows="3" required></textarea>

        <!-- Gửi danh sách ID sản phẩm chọn -->
        <input type="hidden" name="ds_id_sp" value="<?= htmlspecialchars($_POST['chon_sp']) ?>">

        <button type="submit" class="btn-submit">Xác nhận đặt hàng</button>
    </form>
</div>
<div class="back-link">
    <a href="gio_hang.php" class="btn btn-back">← Quay lại giỏ hàng</a>
</div>
</body>
</html>
