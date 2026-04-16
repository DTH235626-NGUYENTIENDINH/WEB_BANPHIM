<?php
session_start();
require_once '../DB/db_connect.php';

if (isset($_POST['register'])) {

    // Lấy dữ liệu và dùng mysqli_real_escape_string để chống SQL Injection
    $ho_ten = mysqli_real_escape_string($conn, $_POST['ho_ten']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt = mysqli_real_escape_string($conn, $_POST['so_dien_thoai']);
    $password = $_POST['mat_khau'];
    $confirm_p = $_POST['confirm_password'];

    // 3. Kiểm tra logic mật khẩu khớp nhau
    if ($password !== $confirm_p) {
        header("Location: ../index.php?page=register&error=password_mismatch");
        exit();
    }

    // 4. Kiểm tra xem username hoặc email đã tồn tại trong hệ thống chưa
    $check_user = "SELECT id FROM USERS WHERE username = '$username' OR email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        // Nếu đã tồn tại, trả về lỗi
        header("Location: ../index.php?page=register&error=user_exists");
        exit();
    }

    // 5. Mã hóa mật khẩu (Hash) - Bước này cực kỳ quan trọng để bảo mật
    $hashed_password = md5($password);

    // 6. Chèn dữ liệu vào bảng USERS (vai_tro mặc định là 'khach')
    $sql = "INSERT INTO USERS (username, ho_ten, email, mat_khau, so_dien_thoai, vai_tro) 
            VALUES ('$username', '$ho_ten', '$email', '$hashed_password', '$sdt', 'khach')";

    if (mysqli_query($conn, $sql)) {
        // Đăng ký thành công -> Chuyển hướng sang trang Login với thông báo thành công
        header("Location: ../index.php?page=login&success=registered");
        exit();
    } else {
        // Thông báo lỗi nếu câu lệnh SQL có vấn đề
        echo "Lỗi hệ thống: " . mysqli_error($conn);
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>