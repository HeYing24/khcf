<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thông Tin Quán Cà Phê</title>
    <style>
    body {
        background-color: #fdf6f0;
        font-family: 'Segoe UI', sans-serif;
        color: #4e342e;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 700px;
        margin: 60px auto;
        background-color: #fff8f0;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0c9a6;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 28px;
        color: #6d4c41;
    }

    .info-item {
        margin-bottom: 20px;
        font-size: 18px;
        display: flex;
        align-items: center;
    }

    .info-item i {
        margin-right: 12px;
        color: #795548;
    }

    a {
        color: #5d4037;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    .back-link {
        position: fixed;
        bottom: 20px;
        left: 20px;
        z-index: 1000;
    }

    .btn-back {
        display: inline-block;
        padding: 10px 16px;
        background-color: #59342c;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        margin: 20px 10px;
    }
    </style>
    <!-- Font Awesome để có icon đẹp -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>☕ Thông Tin Quán Cà Phê</h2>

        <div class="info-item">
            <i class="fas fa-store"></i> <strong>Tên quán:</strong>&nbsp; Cà Phê & Bánh Ngọt
        </div>
        <div class="info-item">
            <i class="fas fa-map-marker-alt"></i> <strong>Địa chỉ:</strong>&nbsp; 255 Đường Âu Cơ, TP Bắc Ninh
        </div>
        <div class="info-item">
            <i class="fas fa-phone-alt"></i> <strong>SĐT:</strong>&nbsp; 0909 123 456
        </div>
        <div class="info-item">
            <i class="fas fa-envelope"></i> <strong>Email:</strong>&nbsp; caphebanhngot@gmail.com
        </div>
        <div class="info-item">
            <i class="fab fa-facebook"></i> <strong>Facebook:</strong>&nbsp;
            <a href="https://facebook.com/caphenhamoc" target="_blank">facebook.com/caphebanhngot</a>
        </div>
    </div>
</body>

<div class="back-link">

    <a href="index.php" class="btn btn-back">← Quay lại trang chủ</a>
</div>

</html>