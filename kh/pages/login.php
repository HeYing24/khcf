<?php
session_start();
include '../config/config.php';

// Xử lý đăng nhập
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mat_khau = mysqli_real_escape_string($conn, $_POST['mat_khau']);

    // Kiểm tra thông tin người dùng
    $sql = "SELECT * FROM khachhang WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Kiểm tra mật khẩu
        if (password_verify($mat_khau, $user['mat_khau'])) {
            // Đăng nhập thành công
            $_SESSION['id_khachhang'] = $user['id']; // Lưu id của người dùng
            $_SESSION['ten_khachhang'] = $user['ten']; // Lưu tên người dùng
            $_SESSION['thong_bao'] = "Đăng nhập thành công!";
            header("Location: index.php"); // Chuyển hướng về trang chủ sau khi đăng nhập
            exit();
        } else {
            $_SESSION['thong_bao'] = "Mật khẩu không đúng!";
        }
    } else {
        $_SESSION['thong_bao'] = "Email không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Cà phê & Bánh ngọt</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-container h2 {
            font-size: 28px;
            color: #6f4e37;
            margin-bottom: 20px;
        }
        .login-container p {
            font-size: 16px;
            color: #555;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
        }
        .login-container input[type="email"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #6f4e37;
            box-shadow: 0 0 8px rgba(111, 78, 55, 0.3);
        }
        .login-container button {
            background-color: #6f4e37;
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .login-container button:hover {
            background-color: #59342c;
            transform: translateY(-2px);
        }
        .login-container a {
            color: #6f4e37;
            text-decoration: none;
            font-size: 16px;
        }
        .login-container a:hover {
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
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        
        <?php if (isset($_SESSION['thong_bao'])): ?>
            <div class="notification"><?= $_SESSION['thong_bao']; unset($_SESSION['thong_bao']); ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Nhập email của bạn">

            <label for="mat_khau">Mật khẩu:</label>
            <input type="password" id="mat_khau" name="mat_khau" required placeholder="Nhập mật khẩu">

            <button type="submit" name="submit">Đăng Nhập</button>
        </form>

        <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
    </div>
</body>
</html>
