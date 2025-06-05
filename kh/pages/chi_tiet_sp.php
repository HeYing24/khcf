<?php
include '../config/config.php';
session_start();

// Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn sản phẩm
$stmt = $conn->prepare("SELECT * FROM sanpham WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo "Sản phẩm không tồn tại.";
    exit();
}

$sp = $result->fetch_assoc();
$so_luong_max = 20; // Giới hạn số lượng tối đa mỗi sản phẩm

// Xử lý thêm vào giỏ hàng khi nhấn nút
if (isset($_POST['them_vao_gio'])) {
    $so_luong = isset($_POST['so_luong']) ? (int)$_POST['so_luong'] : 1;
    if ($so_luong < 1) $so_luong = 1;

    // Giới hạn số lượng theo số lượng tối đa cho phép
    if ($so_luong > $so_luong_max) {
        $so_luong = $so_luong_max;
        echo "<script>alert('Bạn chỉ có thể mua tối đa $so_luong_max sản phẩm');</script>";
    }

    // Nếu giỏ hàng chưa tồn tại, tạo giỏ hàng
    if (!isset($_SESSION['gio_hang'])) {
        $_SESSION['gio_hang'] = [];
    }

    $da_co = false;
    foreach ($_SESSION['gio_hang'] as &$item) {
        if ($item['id'] == $sp['id']) {
            // Cập nhật số lượng sản phẩm trong giỏ
            $item['so_luong'] += $so_luong;
            if ($item['so_luong'] > $so_luong_max) {
                $item['so_luong'] = $so_luong_max;
                echo "<script>alert('Tổng số lượng sản phẩm này trong giỏ không được vượt quá $so_luong_max');</script>";
            }
            $da_co = true;
            break;
        }
    }
    unset($item);

    if (!$da_co) {
        // Thêm sản phẩm mới vào giỏ
        $_SESSION['gio_hang'][] = [
            'id' => $sp['id'],
            'ten' => $sp['ten_san_pham'],
            'gia' => $sp['gia'],
            'hinh_anh' => $sp['hinh_anh'],
            'so_luong' => $so_luong
        ];
    }

    header("Location: gio_hang.php");
    exit();
}

// Xử lý thêm vào yêu thích
if (isset($_POST['them_vao_wishlist'])) {
    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    $da_co = false;
    foreach ($_SESSION['wishlist'] as $item) {
        if ($item['id'] == $sp['id']) {
            $da_co = true;
            break;
        }
    }

    if (!$da_co) {
        $_SESSION['wishlist'][] = [
            'id' => $sp['id'],
            'ten' => $sp['ten_san_pham'],
            'gia' => $sp['gia'],
            'hinh_anh' => $sp['hinh_anh']
        ];
        echo "<script>alert('Sản phẩm đã được thêm vào danh sách yêu thích!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($sp['ten_san_pham']) ?> - Coffee Store</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Chắc chắn sử dụng tệp CSS chung -->
<style>
/* Tổng quan trang chi tiết sản phẩm - Chủ đề Nâu */
.product-detail {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px;
    background-color: #f4e1d2;  /* Màu nền sáng giống màu của cà phê sữa */
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-detail:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}

.product-detail img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 12px;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.product-detail img:hover {
    transform: scale(1.05);
}

.product-info {
    flex: 1;
    margin-left: 30px;
    color: #6e4b3a;  /* Màu nâu cà phê */
    font-family: 'Roboto', sans-serif;
}

.product-info h2 {
    font-size: 32px;
    color: #4e3629; /* Nâu đậm */
    margin-bottom: 15px;
    font-weight: 700;
    transition: color 0.3s ease;
}

.product-info h2:hover {
    color: #3c2c1f; /* Nâu đậm hơn khi hover */
}

.product-info p {
    font-size: 18px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 20px;
    text-align: justify;
}

.product-info strong {
    color: #d17b39;  /* Màu cam sáng của cà phê khi nhấn mạnh */
}

.product-info .price {
    font-size: 24px;
    font-weight: 700;
    color: #8d6e63;  /* Màu nâu đậm cho giá */
    margin: 10px 0;
}

/* Phần form nhập số lượng và các nút */
form {
    margin-top: 30px;
}

input[type="number"] {
    padding: 12px;
    font-size: 16px;
    width: 100px;
    border: 1px solid #6e4b3a;  /* Màu nâu cà phê */
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s ease;
}

input[type="number"]:focus {
    border-color: #3c2c1f;  /* Nâu đậm khi focus */
}

.buttons {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

button[type="submit"],
a.btn-cart,
a.btn-back {
    background-color: #6e4b3a;  /* Nâu cà phê */
    color: #fff;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 18px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button[type="submit"]:hover,
a.btn-cart:hover,
a.btn-back:hover {
    background-color: #4e3629;  /* Nâu đậm hơn khi hover */
    transform: translateY(-3px);
}

button[type="submit"]:active,
a.btn-cart:active,
a.btn-back:active {
    transform: translateY(1px);
}

/* Nút yêu thích */
.wishlist-btn {
    background-color: #d17b39;  /* Màu cam sáng khi yêu thích */
    color: white;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 18px;
    text-align: center;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.wishlist-btn:hover {
    background-color: #c15e2e;  /* Màu cam đậm khi hover */
    transform: translateY(-3px);
}

.wishlist-btn:active {
    transform: translateY(1px);
}

/* Đảm bảo giao diện responsive */
@media (max-width: 768px) {
    .product-detail {
        flex-direction: column;
        padding: 20px;
    }

    .product-detail img {
        max-width: 100%;
        margin-bottom: 20px;
    }

    .product-info {
        margin-left: 0;
    }

    .buttons {
        flex-direction: column;
        align-items: center;
    }

    input[type="number"] {
        width: 80px;
    }
}


</style>
</head>
<body>

<!-- Header / Navbar -->
<?php include '../includes/header.php'; ?>

<div class="product-detail">
    <img src="../assets/images/<?= $sp['hinh_anh'] ?>" alt="<?= htmlspecialchars($sp['ten_san_pham']) ?>">
    <div class="product-info">
        <h2><?= htmlspecialchars($sp['ten_san_pham']) ?></h2>
        <p><strong>Giá:</strong> <?= number_format($sp['gia'], 0, ',', '.') ?>.000 đ</p>
        <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($sp['mo_ta'])) ?></p>

        <form method="POST">
            <label for="so_luong">Số lượng: </label>
            <input type="number" name="so_luong" id="so_luong" value="1" min="1" max="<?= $so_luong_max ?>" required>
            <div class="buttons">
                <button type="submit" name="them_vao_gio" class="btn btn-cart">🛒 Thêm vào giỏ hàng</button>
                <a href="gio_hang.php" class="btn btn-cart">Xem Giỏ Hàng</a>
                <a href="index.php" class="btn btn-back">← Quay lại Trang Chủ</a>
            </div>
        </form>

    </div>
</div>

<!-- Footer -->
<?php include '../includes/footer.php'; ?>

</body>
</html>
