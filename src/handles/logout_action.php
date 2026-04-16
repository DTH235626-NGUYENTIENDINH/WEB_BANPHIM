<?php
session_start();
session_unset(); // Xóa sạch các biến session
session_destroy(); // Hủy session

// Về lại trang chủ sau khi thoát
header("Location: ../index.php");
exit();
?>