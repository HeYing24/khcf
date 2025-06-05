<?php
session_start();
include '../config/config.php';

// Thông báo đăng xuất
if (isset($_SESSION['thong_bao'])) {
    echo "<div style='background-color: #28a745; color: white; padding: 15px; text-align: center; border-radius: 5px; margin-top: 20px;'>"
        . $_SESSION['thong_bao'] . "</div>";
    unset($_SESSION['thong_bao']);
}

// Lấy giỏ hàng từ session
$gio_hang = isset($_SESSION['gio_hang']) ? $_SESSION['gio_hang'] : [];
$tong_so_luong = array_sum(array_column($gio_hang, 'so_luong'));

// Lấy thông tin khách hàng từ session
$khach_hang = isset($_SESSION['khach_hang']) ? $_SESSION['khach_hang'] : null;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang Chủ - Cà phê & Bánh ngọt</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f1f1f1;
        color: #333;
    }

    /* Header */
    .header {
        background-color: #6f4e37;
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .header a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        margin-left: 15px;
    }

    .header .left h2 a {
        font-size: 24px;
        color: #ffc107;
    }

    .header .right a {
        margin-left: 15px;
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 15px;
    }

    /* Tiêu đề chính */
    .title {
        font-size: 36px;
        font-weight: 700;
        text-align: center;
        color: #6f4e37;
        margin-bottom: 30px;
    }

    /* Tiêu đề danh mục */
    .section-title {
        font-size: 28px;
        font-weight: bold;
        color: #6f4e37;
        border-bottom: 3px solid #d4af7f;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    /* Danh sách sản phẩm */
    .product-list {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: center;
    }

    /* Sản phẩm */
    /* Sản phẩm */
    .product {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        width: calc(33.333% - 30px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .product img {
        width: 100%;
        height: 250px;
        /* Điều chỉnh chiều cao cho phù hợp */
        object-fit: cover;
        /* Đảm bảo hình ảnh không bị biến dạng */
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .product h3 {
        font-size: 20px;
        color: #6f4e37;
        margin: 10px 0;
    }

    .product p {
        font-size: 18px;
        color: #e53935;
        font-weight: bold;
        margin-bottom: 15px;
    }

    /* Nút */
    .btn {
        display: inline-block;
        padding: 12px 20px;
        background-color: #6f4e37;
        color: white;
        font-size: 16px;
        border-radius: 8px;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: #59342c;
    }

    /* Footer */
    footer {
        text-align: center;
        margin-top: 50px;
        padding: 15px;
        background-color: #6f4e37;
        color: white;
        font-size: 14px;
    }

    /* Giao diện cơ bản cho menu */
    .navbar {
        background-color: #6f4e37;
        padding: 10px;
        border-radius: 8px;
        display: flex;
        justify-content: flex-end;
        font-family: 'Segoe UI', sans-serif;
    }

    .navbar ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
    }

    .navbar li {
        position: relative;
        margin-left: 20px;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        padding: 8px 14px;
        display: block;
        border-radius: 5px;
    }

    .navbar a:hover {
        background-color: #59342c;
    }

    /* Dropdown */
    .navbar li ul {
        display: none;
        position: absolute;
        top: 36px;
        left: 0;
        background-color: #6f4e37;
        border-radius: 5px;
        min-width: 160px;
        z-index: 1000;
    }

    .navbar li:hover ul {
        display: block;
    }

    .navbar li ul li {
        margin: 0;
    }

    .navbar li ul a {
        padding: 10px;
    }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header">
        <div class="left">
            <h2><a href="index.php">☕ Cà phê & Bánh</a></h2>
        </div>
        <div class="right">
            <?php if (isset($_SESSION['id_khachhang'])): ?>
            <!-- Nếu người dùng đã đăng nhập -->
            <div class="navbar">
                <ul>
                    <li><a href="ds_sp.php">Sản phẩm cửa hàng</a></p>
                    </li>
                    <li><a href="gio_hang.php">Giỏ hàng (<?= $tong_so_luong ?>)</a></li>

                    <li><a href="#">Tài khoản</a>
                        <ul>
                            <li><a href="thong_tin_khach_hang.php">Hồ sơ cá nhân</a></li>
                            <li><a href="don_hang_cua_toi.php">Đơn hàng</a></li>
                            <li><a href="san_pham_yeu_thich.php">Sản phẩm yêu thích</a></li>
                            <li><a href="logout.php">Đăng xuất</a></li>
                        </ul>
                    <li><a href="lien_he.php">Liên hệ</a></li>
                    <a href="thong_tin_quan.php" class="btn">Thông tin quán</a>
                    </li>

                </ul>
            </div>
            <?php else: ?>
            <!-- Nếu người dùng chưa đăng nhập -->
            <a href="login.php" class="btn">Đăng nhập</a>
            <a href="register.php" class="btn">Đăng ký</a>
            <a href="gio_hang.php" class="btn">🛒 Giỏ hàng (<?= $tong_so_luong ?>)</a>
            <a href="thong_tin_quan.php" class="btn">Thông tin quán</a>
            <?php endif; ?>
        </div>
    </div>


    <!-- Nội dung -->
    <div class="container">
        <h1 class="title">Chào mừng đến với Quán Cà phê & Bánh ngọt</h1>

        <!-- Cà phê -->
        <h2 class="section-title">☕ Cà phê</h2>
        <div class="product-list">
            <?php
        $query = "SELECT sp.*, dm.ten_danh_muc 
                  FROM sanpham sp 
                  INNER JOIN danhmuc dm ON sp.id_danh_muc = dm.id 
                  WHERE dm.ten_danh_muc = 'Cà phê'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p style='color:red;'>Lỗi truy vấn: " . mysqli_error($conn) . "</p>";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product">';
            echo '<img src="../assets/images/' . $row['hinh_anh'] . '" alt="' . $row['ten_san_pham'] . '">';
            echo '<h3>' . $row['ten_san_pham'] . '</h3>';
            echo '<p>' . number_format($row['gia'], 0, ',', '.') . '.000 VNĐ</p>';
            echo '<a href="chi_tiet_sp.php?id=' . $row['id'] . '" class="btn" style="margin-right: 10px;">Xem chi tiết</a>';
            // Nút thêm vào giỏ hàng
            echo '<a href="them_vao_gio_hang.php?them_vao=' . $row['id'] . '" class="btn">Thêm vào giỏ hàng</a>';
            echo '</div>';
        }
        ?>
        </div>

        <!-- Bánh -->
        <h2 class="section-title">🍰 Bánh ngọt</h2>
        <div class="product-list">
            <?php
        $query = "SELECT sp.*, dm.ten_danh_muc 
                  FROM sanpham sp 
                  INNER JOIN danhmuc dm ON sp.id_danh_muc = dm.id
                  WHERE dm.ten_danh_muc = 'Bánh'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p style='color:red;'>Lỗi truy vấn: " . mysqli_error($conn) . "</p>";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product">';
            echo '<img src="../assets/images/' . $row['hinh_anh'] . '" alt="' . $row['ten_san_pham'] . '">';
            echo '<h3>' . $row['ten_san_pham'] . '</h3>';
            echo '<p>' . number_format($row['gia'], 0, ',', '.') . '.000 VNĐ</p>';
            echo '<a href="chi_tiet_sp.php?id=' . $row['id'] . '" class="btn" style="margin-right: 10px;">Xem chi tiết</a>';
            // Nút thêm vào giỏ hàng
            echo '<a href="them_vao_gio_hang.php?them_vao=' . $row['id'] . '" class="btn">Thêm vào giỏ hàng</a>';
            echo '</div>';
        }
        ?>
        </div>
        <!-- Nươc ep -->
        <h2 class="section-title">🥤 Nước ép trái cây</h2>
        <div class="product-list">
            <?php
        $query = "SELECT sp.*, dm.ten_danh_muc 
                  FROM sanpham sp 
                  INNER JOIN danhmuc dm ON sp.id_danh_muc = dm.id
                  WHERE dm.ten_danh_muc = 'Nước trái cây'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "<p style='color:red;'>Lỗi truy vấn: " . mysqli_error($conn) . "</p>";
        }
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="product">';
            echo '<img src="../assets/images/' . $row['hinh_anh'] . '" alt="' . $row['ten_san_pham'] . '">';
            echo '<h3>' . $row['ten_san_pham'] . '</h3>';
            echo '<p>' . number_format($row['gia'], 0, ',', '.') . '.000 VNĐ</p>';
            echo '<a href="chi_tiet_sp.php?id=' . $row['id'] . '" class="btn" style="margin-right: 10px;">Xem chi tiết</a>';
            // Nút thêm vào giỏ hàng
            echo '<a href="them_vao_gio_hang.php?them_vao=' . $row['id'] . '" class="btn">Thêm vào giỏ hàng</a>';
            echo '</div>';
        }
        ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 Cà phê & Bánh ngọt - Tất cả quyền sở hữu.
    </footer>

</body>

</html>