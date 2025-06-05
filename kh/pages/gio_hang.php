<?php
session_start();
include '../config/config.php';


// Xử lý cập nhật số lượng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_sua'])) {
    $id_sua = $_POST['id_sua'];
    define('SO_LUONG_MIN', 1);
    define('SO_LUONG_MAX', 20);
    $so_luong_moi = max(SO_LUONG_MIN, min(SO_LUONG_MAX, (int)$_POST['so_luong_moi']));
if (isset($_SESSION['gio_hang'][$id_sua])) {
        $_SESSION['gio_hang'][$id_sua]['so_luong'] = $so_luong_moi;
    }
    // Trả về JSON nếu là fetch
    if (isset($_POST['is_ajax'])) {
        echo json_encode(['success' => true]);
        exit;
    }
}

// Xử lý xóa sản phẩm khỏi giỏ
if (isset($_GET['xoa']) && isset($_SESSION['gio_hang'][$_GET['xoa']])) {
    unset($_SESSION['gio_hang'][$_GET['xoa']]);
}

$gio_hang = $_SESSION['gio_hang'] ?? [];
$tong_tien = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f4f0;
            padding: 20px;
        }
        h1 {
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
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #8d6e63;
            color: white;
        }
        img {
            max-width: 60px;
            border-radius: 6px;
        }
        .btn-delete {
            padding: 6px 10px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
        }
        .total {
            width: 90%;
            margin: auto;
            text-align: right;
            font-size: 18px;
            padding: 15px 0;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 16px;
            background-color:  #59342c;
            color: white;
            padding: 8px 14px; 
            border-radius: 6px;
            text-decoration: none;
            margin: 20px 10px;
        }
        .btn-checkout {
            background-color: #4caf50;
            padding: 10px 16px;
            color: white;
            padding: 8px 16px; 
            border-radius: 6px;
            text-decoration: none;
            margin: 20px 10px;
        }
        input[type=number] {
            width: 60px;
            padding: 6px;
            text-align: center;
        }
        .btn-back:hover,
.btn-checkout:hover {
    transform: scale(1.05);
}
    </style>
</head>
<body>

<h1>🛒 Giỏ hàng của bạn</h1>

<?php if (empty($gio_hang)): ?>
    <p style="text-align:center;">Giỏ hàng trống. <a href="ds_sp.php">Tiếp tục mua sắm</a></p>
<?php else: ?>
    <form action="thanh_toan.php" method="POST" id="form-thanh-toan">
        
    <table>
        <tr>
            <th>Chọn</th>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($gio_hang as $id => $item): 
            $thanh_tien = $item['gia'] * $item['so_luong'];
            $tong_tien += $thanh_tien;
        ?>
        <tr>
        <td>
    <input type="checkbox" name="chon_sp[]" value="<?= $id ?>" class="chon-sp" 
           data-gia="<?= $item['gia'] ?>" data-soluong="<?= $item['so_luong'] ?>">
</td>

        
            <td><img src="../assets/images/<?= $item['hinh_anh'] ?>" alt="<?= $item['ten'] ?>"></td>
            <td><?= htmlspecialchars($item['ten']) ?></td>
            <td><?= number_format($item['gia'], 0, ',', '.') ?> .000 đ</td>
            <td>
                <input type="number" value="<?= $item['so_luong'] ?>" min="1"
                    onchange="capNhatSoLuong(<?= $id ?>, this.value)">
            </td>
            <td><?= number_format($thanh_tien, 0, ',', '.') ?>.000 đ</td>
            <td>
                <a href="gio_hang.php?xoa=<?= $id ?>" class="btn-delete" onclick="return confirm('Xóa sản phẩm này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="total">
    <strong>Tổng tiền các sản phẩm đã chọn: <span id="tong-tien">0</span>.000 đ</strong>
</div>


<input type="hidden" name="chon_sp" id="selected-products">
        <div style="width: 90%; margin: 30px auto 0; display: flex; justify-content: space-between;">
            <a href="index.php" class="btn-back">← Quay lại Trang chủ</a>
            <button type="submit" class="btn-checkout" onclick="return confirm('Xác nhận thanh toán các sản phẩm đã chọn?')">Tiến hành thanh toán</button>

        </div>
    </form>
<?php endif; ?>

<script>
function capNhatSoLuong(id, soLuong) {
    const formData = new FormData();
    formData.append("id_sua", id);
    formData.append("so_luong_moi", soLuong);
    formData.append("is_ajax", true);

    fetch("gio_hang.php", {
        method: "POST",
        body: formData
    }).then(() => {
        location.reload();
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll(".chon-sp");
    const formThanhToan = document.getElementById("form-thanh-toan");
    const selectedProductsInput = document.getElementById("selected-products");

    // Hàm tính tổng tiền và cập nhật giá trị của các sản phẩm đã chọn
    function tinhTong() {
        let tong = 0;
        const selectedIds = [];
        checkboxes.forEach(cb => {
            if (cb.checked) {
                selectedIds.push(cb.value); // Lưu id sản phẩm đã chọn
                const gia = parseInt(cb.getAttribute("data-gia"));
                const soluong = parseInt(cb.getAttribute("data-soluong"));
                tong += gia * soluong; // tính thành tiền
            }
        });

        // Cập nhật danh sách sản phẩm đã chọn vào input ẩn
        selectedProductsInput.value = selectedIds.join(','); // Chuyển mảng thành chuỗi

        // Cập nhật tổng tiền hiển thị
        document.getElementById("tong-tien").textContent = tong.toLocaleString() + " ";
    }

    checkboxes.forEach(cb => {
        cb.addEventListener("change", tinhTong);
    });

    tinhTong(); // Tính tổng tiền ngay khi trang tải
});
formThanhToan.addEventListener("submit", (e) => {
    if (!selectedProductsInput.value) {
        alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
        e.preventDefault();
    }
});

</script>
</body>
</html>
