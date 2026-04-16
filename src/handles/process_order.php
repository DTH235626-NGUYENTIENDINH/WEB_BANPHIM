<?php
session_start();
include '../DB/db_connect.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Error: User session not found. Please login again.");
}

// 2. Kiểm tra giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {

    $user_id = $_SESSION['user_id'];
    $ten_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['phone']);
    $dia_chi_giao = mysqli_real_escape_string($conn, $_POST['address']);
    $phuong_thuc_tt = mysqli_real_escape_string($conn, $_POST['payment']);

    $ma_don = "ORD-" . strtoupper(uniqid());

    $tong_tien_hang = 0;
    foreach ($_SESSION['cart'] as $item) {
        $tong_tien_hang += $item['price'] * $item['quantity'];
    }
    $phi_ship = 0;
    $tong_thanh_toan = $tong_tien_hang + $phi_ship;

    // --- BƯỚC 1: LƯU BẢNG ORDERS ---
    $sql_order = "INSERT INTO ORDERS (
                    user_id, ma_don, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, 
                    tong_tien_hang, phi_ship, tong_thanh_toan, phuong_thuc_tt, trang_thai_don
                  ) VALUES (
                    '$user_id', '$ma_don', '$ten_nguoi_nhan', '$sdt_nguoi_nhan', '$dia_chi_giao', 
                    '$tong_tien_hang', '$phi_ship', '$tong_thanh_toan', '$phuong_thuc_tt', 'Pending'
                  )";

    if (mysqli_query($conn, $sql_order)) {
        $order_id = mysqli_insert_id($conn);

        // --- BƯỚC 2: LƯU BẢNG ORDER_ITEMS ---
        foreach ($_SESSION['cart'] as $item) {
            $v_id = isset($item['variant_id']) ? $item['variant_id'] : 0;
            $qty = $item['quantity'];
            $price = $item['price'];

            // QUAN TRỌNG: Nếu là 0 thì gửi chữ NULL, nếu có ID thì bao bọc trong nháy đơn
            $v_id_sql = ($v_id == 0 || $v_id == "") ? "NULL" : "'$v_id'";

            $sql_detail = "INSERT INTO ORDER_ITEMS (order_id, variant_id, so_luong, don_gia) 
                   VALUES ('$order_id', $v_id_sql, '$qty', '$price')";

            if (!mysqli_query($conn, $sql_detail)) {
                die("Lỗi chèn sản phẩm: " . mysqli_error($conn) . " | SQL: " . $sql_detail);
            }
        }

        // --- BƯỚC 3: DỌN DẸP ---
        mysqli_query($conn, "DELETE FROM CART WHERE user_id = '$user_id'");
        unset($_SESSION['cart']);

        header("Location: ../index.php?page=success&id=" . $order_id);
        exit();

    } else {
        die("Lỗi lưu đơn hàng: " . mysqli_error($conn));
    }
} else {
    header("Location: ../index.php");
    exit();
}