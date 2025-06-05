<?php
session_start();
include '../config/config.php';

// Kiểm tra nếu có tham số "them_vao" trong URL
if (isset($_GET['them_vao'])) {
    $id_sp = intval($_GET['them_vao']); // Lấy ID sản phẩm từ URL

    // Kiểm tra xem sản phẩm có trong cơ sở dữ liệu không
    $sql = "SELECT * FROM sanpham WHERE id = $id_sp";
    $result = mysqli_query($conn, $sql);
    $san_pham = mysqli_fetch_assoc($result);

    if ($san_pham) {
        // Nếu giỏ hàng chưa tồn tại trong session, tạo mới
        if (!isset($_SESSION['gio_hang'])) {
            $_SESSION['gio_hang'] = [];
        }

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $da_co = false;
        foreach ($_SESSION['gio_hang'] as &$sp) {
            if ($sp['id'] == $id_sp) {
                // Nếu có rồi, tăng số lượng sản phẩm lên 1
                $sp['so_luong']++;
                $da_co = true;
                break;
            }
        }
        unset($sp);

        // Nếu sản phẩm chưa có trong giỏ, thêm mới
        if (!$da_co) {
            $_SESSION['gio_hang'][] = [
                'id' => $san_pham['id'],
                'ten' => $san_pham['ten_san_pham'],
                'gia' => $san_pham['gia'],
                'hinh_anh' => $san_pham['hinh_anh'],
                'so_luong' => 1
            ];
        }

        // Thông báo đã thêm sản phẩm vào giỏ hàng
        $_SESSION['thong_bao'] = "✅ Đã thêm sản phẩm vào giỏ hàng!";
    }

    // Điều hướng lại về giỏ hàng sau khi thêm sản phẩm
    header("Location: gio_hang.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm vào giỏ hàng - Cà phê & Bánh ngọt</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .container h1 {
            text-align: center;
            font-size: 24px;
            color: #6f4e37;
        }
        .container p {
            text-align: center;
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .container a {
            display: inline-block;
            padding: 12px 20px;
            background-color: #6f4e37;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            width: 100%;
        }
        .container a:hover {
            background-color: #59342c;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            width: 100%;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Thêm Sản Phẩm Vào Giỏ Hàng Thành Công!</h1>
   
    
    <a href="gio_hang.php">Xem Giỏ Hàng</a>
    <a href="index.php" class="back-btn">Quay lại Trang Chủ</a>
</div>
</body>
</html>
