<?php
include '../config/config.php';
$id_khachhang = $_SESSION['id_khachhang'] ?? 0;
// Truy vấn danh sách sản phẩm
$sql = "SELECT * FROM sanpham ORDER BY ngay_tao DESC";
$result = mysqli_query($conn, $sql);

// Kiểm tra nếu truy vấn thành công
if (!$result) {
    die('Lỗi truy vấn: ' . mysqli_error($conn));
}
// Lấy danh sách ID sản phẩm đã thích
$ds_thich = [];
if ($id_khachhang) {
    $sql_thich = "SELECT id_san_pham FROM thich_sanpham WHERE id_khach_hang = $id_khachhang";
    $res_thich = mysqli_query($conn, $sql_thich);
    while ($row = mysqli_fetch_assoc($res_thich)) {
        $ds_thich[] = $row['id_san_pham'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>

/* Reset các thuộc tính mặc định */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
    color: #3a3a3a;
    line-height: 1.6;
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
    color: #6f4f1f;
    text-align: center;
    margin-bottom: 40px;
    font-weight: bold;
}

/* Hàng sản phẩm */
.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 30px;
}

/* Thẻ sản phẩm */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #ffffff;
    overflow: hidden;
    width: 100%;
    max-width: 270px;
    margin-bottom: 30px;
}

/* Hiệu ứng hover cho thẻ sản phẩm */
.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

/* Hình ảnh sản phẩm */
.card-img-top {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}

/* Thông tin sản phẩm */
.card-body {
    padding: 15px;
    background-color: #fff8f0;
}

.card-title {
    font-size: 1.4rem;
    font-weight: bold;
    color: #6f4f1f;
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

/* Đảm bảo các thẻ sản phẩm không bị dính nhau */
.col-md-3 {
    flex: 1 1 calc(33.33% - 30px);  /* Hiển thị 3 sản phẩm trên một hàng */
    max-width: calc(33.33% - 30px); /* Đảm bảo các sản phẩm được chia đều */
}

/* Phản hồi trên màn hình nhỏ */
@media (max-width: 768px) {
    .col-md-3 {
        flex: 1 1 calc(50% - 15px); /* 2 sản phẩm mỗi hàng */
        max-width: calc(50% - 15px);
    }
}

@media (max-width: 480px) {
    .col-md-3 {
        flex: 1 1 100%;  /* 1 sản phẩm mỗi hàng trên màn hình rất nhỏ */
        max-width: 100%;
    }
}
.btn-back {
    display: inline-block;
    padding: 10px 20px;
    background-color: #6f4f1f;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    margin-left: 180px;
}

.btn-back:hover {
    background-color: #6f4f1f;
    text-decoration: none;
    color: #fff;
}
/* Nút thích với hiệu ứng */
.like-btn {
            background-color: transparent;
            border: none;
            color: #e85992; /* Màu đỏ cho biểu tượng trái tim */
            font-size: 20px;
            cursor: pointer;
            transition: color 0.3s, transform 0.3s;
        }

        .like-btn:hover {
            color: #c56358; /* Màu đỏ đậm khi hover */
            transform: scale(1.2);
        }

        .liked {
            color: #f82008; /* Màu đỏ khi đã thích */
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
    <h2>📋 Danh sách sản phẩm</h2>
    <div class="row">
        <?php while ($sp = mysqli_fetch_assoc($result)) : ?>
        <div class="col-md-3 mb-4">
            <div class="card position-relative">
                <img src="../assets/images/<?= $sp['hinh_anh'] ?>" class="card-img-top" style="height:220px; object-fit:cover">
                <div class="card-body">
                    <h5 class="card-title"><?= $sp['ten_san_pham'] ?></h5>
                    <p class="card-text"><?= number_format($sp['gia'], 0, ',', '.') ?> .000 đ</p>
                    <a href="chi_tiet_sp.php?id=<?= $sp['id'] ?>" class="btn btn-outline-dark btn-sm">Xem chi tiết</a>
                    <button class="like-btn <?= in_array($sp['id'], $ds_thich) ? 'liked' : '' ?>" onclick="toggleLike(<?= $sp['id'] ?>, this)">
                        <?= in_array($sp['id'], $ds_thich) ? '💔' : '❤️' ?>
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Thông báo -->
<div id="toast" class="toast">Sản phẩm đã được yêu thích!</div>

<div class="back-link">
    <a href="index.php" class="btn btn-back">← Quay lại trang chủ</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleLike(productId, button) {
        const isLiked = $(button).hasClass('liked');
        const action = isLiked ? 'bo_thich' : 'thich';
        const icon = isLiked ? '❤️' : '💔';

        // Gửi AJAX request để thêm hoặc xóa sản phẩm yêu thích
        $.ajax({
            url: action + '.php',
            type: 'GET',
            data: { id: productId },
            success: function(response) {
                // Thêm hoặc xóa lớp 'liked' và cập nhật biểu tượng
                if (isLiked) {
                    $(button).removeClass('liked').text(icon);
                    showToast('Sản phẩm đã bỏ thích!');
                } else {
                    $(button).addClass('liked').text(icon);
                    showToast('Sản phẩm đã được yêu thích!');
                }
            }
        });
    }

    // Hiển thị thông báo
    function showToast(message) {
        $('#toast').text(message).fadeIn(400).delay(2000).fadeOut(400);
    }
</script>
</body>
</html>
