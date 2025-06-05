<?php
session_start();

// Hủy tất cả session
session_unset();

// Hủy session
session_destroy();

// Lưu thông báo đăng xuất thành công vào session để hiển thị cho người dùng
$_SESSION['thong_bao'] = "Đăng xuất thành công!";

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
