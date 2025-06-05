<?php
session_start();
include '../config/config.php';

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['id_khachhang'])) {
    header("Location: thanh_toan.php"); // Nếu đã đăng nhập, chuyển trực tiếp đến trang thanh toán
    exit();
}

// Xử lý đăng ký người dùng mới
if (isset($_POST['submit'])) {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['sdt']);
    $dia_chi = mysqli_real_escape_string($conn, $_POST['dia_chi']);
    $mat_khau = mysqli_real_escape_string($conn, $_POST['mat_khau']);
    $mat_khau_confirm = mysqli_real_escape_string($conn, $_POST['mat_khau_confirm']);

    // Kiểm tra xem mật khẩu và xác nhận mật khẩu có khớp không
    if ($mat_khau !== $mat_khau_confirm) {
        $_SESSION['thong_bao'] = "Mật khẩu và xác nhận mật khẩu không khớp!";
        header("Location: register.php");
        exit();
    }

    // Mã hóa mật khẩu trước khi lưu vào CSDL
    $mat_khau = password_hash($mat_khau, PASSWORD_DEFAULT);

    // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
    $sql_check_email = "SELECT * FROM khachhang WHERE email = '$email'";
    $result = mysqli_query($conn, $sql_check_email);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['thong_bao'] = "Email đã được đăng ký trước đó!";
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $sql = "INSERT INTO khachhang (ten, email, sdt, dia_chi, mat_khau) 
                VALUES ('$ten', '$email', '$sdt', '$dia_chi', '$mat_khau')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['thong_bao'] = "Đăng ký thành công!";
            header("Location: login.php"); // Chuyển hướng tới trang đăng nhập
            exit();
        } else {
            $_SESSION['thong_bao'] = "Đã có lỗi xảy ra, vui lòng thử lại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký - Cà phê & Bánh ngọt</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
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
            padding: 40px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .container h2 {
            font-size: 28px;
            color: #6f4e37;
        }
        .container p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }
        .container form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .container input[type="text"],
        .container input[type="email"],
        .container input[type="password"] {
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s;
        }
        .container input[type="text"]:focus,
        .container input[type="email"]:focus,
        .container input[type="password"]:focus {
            border-color: #6f4e37;
        }
        .container button {
            background-color: #6f4e37;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            border: none;
        }
        .container button:hover {
            background-color: #59342c;
        }
        .container a {
            color: #6f4e37;
            text-decoration: none;
        }
        .container a:hover {
            text-decoration: underline;
        }
        .notification {
            color: red;
            margin-bottom: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đăng Ký Tài Khoản</h2>
        
        <?php if (isset($_SESSION['thong_bao'])): ?>
            <div class="notification"><?= $_SESSION['thong_bao']; unset($_SESSION['thong_bao']); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <label for="ten">Tên:</label>
            <input type="text" id="ten" name="ten" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="sdt">Số điện thoại:</label>
            <input type="text" id="sdt" name="sdt" required>

            <label for="dia_chi">Địa chỉ:</label>
            <input type="text" id="dia_chi" name="dia_chi" required>

            <label for="mat_khau">Mật khẩu:</label>
            <input type="password" id="mat_khau" name="mat_khau" required>

            <label for="mat_khau_confirm">Xác nhận mật khẩu:</label>
            <input type="password" id="mat_khau_confirm" name="mat_khau_confirm" required>

            <button type="submit" name="submit">Đăng Ký</button>
        </form>

        <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </div>
</body>
</html>
