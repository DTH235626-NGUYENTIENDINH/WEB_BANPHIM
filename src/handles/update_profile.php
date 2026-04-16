<?php
session_start();
require_once '../DB/db_connect.php';

// Kiểm tra xem có phải gửi từ Form không
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $u_id = $_SESSION['user_id'];

    // Nhận và làm sạch dữ liệu
    $ho_ten  = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $so_dien_thoai     = mysqli_real_escape_string($conn, $_POST['so_dien_thoai']);
    $dia_chi = mysqli_real_escape_string($conn, $_POST['dia_chi']);

    // Câu lệnh SQL cập nhật
    $sql_update = "UPDATE USERS SET 
                    ho_ten = '$ho_ten', 
                    email = '$email', 
                    so_dien_thoai = '$so_dien_thoai', 
                    dia_chi = '$dia_chi' 
                   WHERE id = $u_id";

    if (mysqli_query($conn, $sql_update)) {
        // Cập nhật lại session tên người dùng để hiển thị trên Header ngay lập tức
        $_SESSION['user_name'] = $ho_ten;
        
        // Chuyển hướng về trang profile kèm thông báo thành công
        header("Location: ../index.php?page=profile&status=success");
    } else {
        // Chuyển hướng về kèm thông báo lỗi
        header("Location: ../index.php?page=profile&status=error");
    }
    exit();
} else {
    header("Location: ../index.php");
    exit();
}