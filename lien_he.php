<?php
    include('../config/config.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ten = $_POST['ten'];
        $email = $_POST['email'];
        $sdt = $_POST['sdt'];
        $noi_dung = $_POST['noi_dung'];

        $sql = "INSERT INTO khachhang (ten, email, sdt) VALUES ('$ten', '$email', '$sdt')";
        if (mysqli_query($conn, $sql)) {
            $id_khachhang = mysqli_insert_id($conn);

            $sql_phanhoi = "INSERT INTO phanhoi (id_khachhang, noi_dung, ngay_gui) 
                            VALUES ('$id_khachhang', '$noi_dung', NOW())";
            if (mysqli_query($conn, $sql_phanhoi)) {
                echo "<div class='thongbao'>☕ Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm!</div>";
            } else {
                echo "<div class='loi'>Có lỗi xảy ra khi gửi phản hồi.</div>";
            }
        } else {
            echo "<div class='loi'>Có lỗi xảy ra khi thêm khách hàng.</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <style>
        body {
            background-color: #fdf6f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #4e342e;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff8f0;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(100, 70, 50, 0.2);
            border: 1px solid #e0c9a6;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #6b4f3a;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #c8b08b;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            background-color: #fffdf8;
            color: #4e342e;
        }
        input:focus, textarea:focus {
            border-color: #a1887f;
            outline: none;
        }
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        button {
            background-color: #6d4c41;
            color: #fff;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #5d4037;
        }
        .thongbao, .loi {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-weight: 500;
        }
        .thongbao {
            background-color: #e9e4da;
            color: #4e342e;
        }
        .loi {
            background-color: #fbe9e7;
            color: #c62828;
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
            background-color:  #59342c;
            color: white;
            padding: 8px 14px; 
            border-radius: 6px;
            text-decoration: none;
            margin: 20px 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Liên Hệ Với Quán Cà Phê</h2>
        <form action="lien_he.php" method="POST">
            <label for="ten">Tên:</label>
            <input type="text" id="ten" name="ten" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="sdt">Số điện thoại:</label>
            <input type="text" id="sdt" name="sdt">

            <label for="noi_dung">Nội dung:</label>
            <textarea id="noi_dung" name="noi_dung" required></textarea>

            <button type="submit">Gửi phản hồi</button>
        </form>
    </div>
</body>
<div class="back-link">
   
    <a href="index.php" class="btn btn-back">← Quay lại trang chủ</a>
</div>

</html>
