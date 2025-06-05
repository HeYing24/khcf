<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['khach_hang'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['khach_hang']['id'];

// Nếu người dùng gửi form cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];

    $sql = "UPDATE khachhang SET ten='$ten', email='$email', sdt='$sdt', dia_chi='$dia_chi' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Cập nhật lại session
        $_SESSION['khach_hang'] = [
            'id' => $id,
            'ten' => $ten,
            'email' => $email,
            'sdt' => $sdt,
            'dia_chi' => $dia_chi
        ];
        
        // Lưu thông báo vào session
        $_SESSION['thong_bao'] = 'Cập nhật thông tin thành công!';
        header('Location: thong_tin_khach_hang.php');
        exit;
    } else {
        $error = "Lỗi cập nhật: " . mysqli_error($conn);
    }
}

// Lấy thông tin hiện tại
$sql = "SELECT * FROM khachhang WHERE id = $id";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin cá nhân</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; }
        .container {
            width: 50%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #218838; }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover { background: #5a6268; }
        .alert {
            padding: 10px;
            background-color: #28a745;
            color: white;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✏️ Sửa thông tin cá nhân</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
    <!-- Hiển thị thông báo nếu có -->
    <?php if (isset($_SESSION['thong_bao'])): ?>
        <div class="alert">
            <?= $_SESSION['thong_bao']; ?>
            <?php unset($_SESSION['thong_bao']); // Xóa thông báo sau khi hiển thị ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="ten">Họ tên:</label>
            <input type="text" id="ten" name="ten" value="<?= htmlspecialchars($data['ten']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
        </div>
        <div class="form-group">
            <label for="sdt">Số điện thoại:</label>
            <input type="text" id="sdt" name="sdt" value="<?= htmlspecialchars($data['sdt']) ?>" required>
        </div>
        <div class="form-group">
            <label for="dia_chi">Địa chỉ:</label>
            <input type="text" id="dia_chi" name="dia_chi" value="<?= htmlspecialchars($data['dia_chi']) ?>" required>
        </div>
        <button type="submit">Lưu thay đổi</button>
    </form>
    <a href="thong_tin_khach_hang.php" class="btn-back">⬅ Quay lại</a>
</div>
</body>
</html>
