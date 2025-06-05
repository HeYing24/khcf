<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['id_khachhang'])) {
    header("Location: login.php");
    exit;
}

$id_khachhang = $_SESSION['id_khachhang'];

$sql = "SELECT sp.* FROM sanpham sp
        JOIN thich_sanpham ts ON sp.id = ts.id_san_pham
        WHERE ts.id_khach_hang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_khachhang);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sản phẩm yêu thích</title>
    
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>/* Reset các thuộc tính mặc định */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
    color: #333;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
}

/* Tiêu đề */
h2 {
    font-size: 2.5rem;
    color: #e85992;
    text-align: center;
    margin-bottom: 40px;
    font-weight: bold;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

/* Hàng sản phẩm */
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
}

/* Thẻ sản phẩm */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 100%;
    max-width: 270px;
    background-color: #ffffff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hiệu ứng hover cho thẻ sản phẩm */
.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

/* Hình ảnh sản phẩm */
.card-img-top {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

/* Thông tin sản phẩm */
.card-body {
    padding: 15px;
    background-color: #fff8f0;
}

.card-title {
    font-size: 1.4rem;
    font-weight: bold;
    color: #e85992;
    margin-bottom: 10px;
}

.card-text {
    font-size: 1.1rem;
    color: #8b4513;
    margin-bottom: 15px;
}

/* Nút Xem chi tiết */
.btn-outline-dark {
    color: #6f4f1f;
    border: 1px solid #6f4f1f;
    font-weight: bold;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.btn-outline-dark:hover {
    background-color: #6f4f1f;
    color: #ffffff;
}

/* Nút Bỏ thích */
.btn-danger {
    background-color: #e85992;
    border: 1px solid #e85992;
    color: #fff;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #c4637b;
    color: #fff;
}

/* Nút quay lại */
.btn-secondary {
    background-color: #6c757d;
    border: 1px solid #6c757d;
    color: #fff;
    padding: 10px 20px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    display: inline-block;
}

.btn-secondary:hover {
    background-color: #5a6268;
    color: #fff;
}

/* Hiển thị thông báo */
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    display: none;
    font-weight: bold;
}

/* Hiệu ứng cho nút Bỏ thích */
.btn-danger {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.btn-danger:hover {
    transform: scale(1.1);
}

/* Thêm một chút khoảng cách cho các phần tử */
.mb-4 {
    margin-bottom: 20px;
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
<div class="container mt-4">
    <h2 class="text-center text-danger mb-4">❤️ Sản phẩm yêu thích</h2>
    <div class="row">
        <?php while ($sp = mysqli_fetch_assoc($result)) : ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="../assets/images/<?= $sp['hinh_anh'] ?>" class="card-img-top" style="height:220px; object-fit:cover">
                <div class="card-body">
                    <h5 class="card-title"><?= $sp['ten_san_pham'] ?></h5>
                    <p class="card-text"><?= number_format($sp['gia'], 0, ',', '.') ?>.000 đ</p>
                    <a href="chi_tiet_sp.php?id=<?= $sp['id'] ?>" class="btn btn-outline-dark btn-sm">Xem chi tiết</a>
                    <a href="bo_thich.php?id=<?= $sp['id'] ?>" class="btn btn-danger btn-sm float-end">💔 Bỏ thích</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="back-link">
    <a href="ds_sp.php" class="btn btn-secondary mt-3">← Quay lại danh sách sản phẩm</a>
    
    
</div>
</div>
</body>
</html>
