<?php
session_start();
include '../config/config.php';

// Hiện lỗi SQL nếu có
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['ds_id_sp']) || !isset($_SESSION['gio_hang']) || !is_array($_SESSION['gio_hang']) || empty($_SESSION['gio_hang'])) {
    echo "<script>alert('Không có sản phẩm nào được chọn hoặc giỏ hàng trống.'); window.location='gio_hang.php';</script>";
    exit;
}

// Kiểm tra và làm sạch ds_id_sp
if (empty($_POST['ds_id_sp']) || !preg_match('/^[0-9,]+$/', $_POST['ds_id_sp'])) {
    echo "<script>alert('Danh sách sản phẩm không hợp lệ.'); window.location='gio_hang.php';</script>";
    exit;
}

$ds_id_sp = array_filter(explode(',', $_POST['ds_id_sp']), function($id) {
    return is_numeric($id) && (int)$id > 0; // Chỉ giữ ID là số và lớn hơn 0
});

if (empty($ds_id_sp)) {
    echo "<script>alert('Không có sản phẩm hợp lệ được chọn.'); window.location='gio_hang.php';</script>";
    exit;
}

// Lấy dữ liệu khách hàng
$ten = trim($_POST['ten']);
$email = trim($_POST['email']);
$sdt = trim($_POST['sdt']);
$dia_chi = trim($_POST['dia_chi']);

if (empty($ten) || empty($email) || empty($sdt) || empty($dia_chi)) {
    echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); window.location='gio_hang.php';</script>";
    exit;
}

// Lấy ID khách hàng
if (isset($_SESSION['id_khachhang'])) {
    $id_khachhang = $_SESSION['id_khachhang'];
} else {
    $sql_kh = "INSERT INTO khachhang (ten, email, sdt, dia_chi) VALUES (?, ?, ?, ?)";
    $stmt_kh = $conn->prepare($sql_kh);
    $stmt_kh->bind_param("ssss", $ten, $email, $sdt, $dia_chi);
    if (!$stmt_kh->execute()) {
        die("Lỗi khi tạo khách hàng: " . $stmt_kh->error);
    }
    $id_khachhang = $stmt_kh->insert_id;
    $stmt_kh->close();
    $_SESSION['id_khachhang'] = $id_khachhang;
}
echo "ID khách hàng: $id_khachhang<br>";

$tong_tien = 0;
$gio_hang = $_SESSION['gio_hang'];
foreach ($ds_id_sp as $id) {
    $id = (int)$id;
    if (!isset($gio_hang[$id]) || !is_array($gio_hang[$id])) continue;
    $tong_tien += $gio_hang[$id]['gia'] * $gio_hang[$id]['so_luong'];
}
echo "Tổng tiền: $tong_tien<br>";

// Tạo đơn hàng
$sql_dh = "INSERT INTO donhang (id_khach_hang, ngay_tao, tong_tien, trang_thai) VALUES (?, NOW(), ?, 0)";
$stmt_dh = $conn->prepare($sql_dh);
$stmt_dh->bind_param("ii", $id_khachhang, $tong_tien);

if (!$stmt_dh->execute()) {
    die("Lỗi khi tạo đơn hàng: " . $stmt_dh->error);
}

$id_donhang = $stmt_dh->insert_id;
$stmt_dh->close();

// Chuẩn bị lưu chi tiết đơn hàng
$sql_ct = "INSERT INTO chitiet_donhang (id_donhang, id_san_pham, ten_san_pham, so_luong, don_gia) VALUES (?, ?, ?, ?, ?)";
$stmt_ct = $conn->prepare($sql_ct);

if (!$stmt_ct) {
    die("Lỗi prepare(): " . $conn->error);
}

// Lưu từng sản phẩm
foreach ($ds_id_sp as $id) {
    $id = (int)$id;
    if ($id <= 0 || !isset($gio_hang[$id])) continue;

    $sp = $gio_hang[$id];
    $ten_sp = $sp['ten'];

    // Kiểm tra sản phẩm trong CSDL
    $sql_check = "SELECT ten_san_pham FROM sanpham WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $stmt_check->bind_result($ten_san_pham_db);
    $stmt_check->fetch();
    $stmt_check->close();

    if (!$ten_san_pham_db) {
        echo "<script>alert('Sản phẩm với ID $id không tồn tại.'); window.location='gio_hang.php';</script>";
        exit;
    }

    $so_luong = $sp['so_luong'];
    $gia = $sp['gia'];

    echo "Thêm SP ID=$id, tên=$ten_sp, SL=$so_luong, Giá=$gia<br>";

    $stmt_ct->bind_param("iisii", $id_donhang, $id, $ten_sp, $so_luong, $gia);
    if (!$stmt_ct->execute()) {
        die("Lỗi khi lưu chi tiết đơn hàng: " . $stmt_ct->error);
    }

    // Xoá khỏi giỏ hàng
    unset($_SESSION['gio_hang'][$id]);
}
$stmt_ct->close();
// Cập nhật id_san_pham trong chitiet_donhang cho đơn hàng vừa tạo
$sql_update = "UPDATE chitiet_donhang cd
               JOIN sanpham sp ON LOWER(cd.ten_san_pham) = LOWER(sp.ten_san_pham)
               SET cd.id_san_pham = sp.id
               WHERE cd.id_donhang = ?";
$stmt_update = $conn->prepare($sql_update);
if (!$stmt_update) {
    die("Lỗi prepare UPDATE: " . $conn->error);
}
$stmt_update->bind_param("i", $id_donhang);
if (!$stmt_update->execute()) {
    die("Lỗi khi cập nhật id_san_pham: " . $stmt_update->error);
}
$stmt_update->close();

// Sau khi đặt hàng thành công
echo "<script>alert('Đặt hàng thành công!');</script>";
header("Location: don_hang_cua_toi.php");
exit;
?>