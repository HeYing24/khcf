<?php
$host = 'localhost';
$db = 'web1';
$user = 'root';
$pass = ''; // nếu dùng XAMPP thì thường để rỗng

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>