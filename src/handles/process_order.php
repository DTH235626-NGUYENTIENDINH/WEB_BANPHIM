<?php
session_start();
include '../DB/db_connect.php';
require_once 'sending_order_to_email.php';
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
            $p_id = $item['id']; // ID sản phẩm chính

            // 2.1. Lưu vào bảng ORDER_ITEMS
            $v_id_sql = ($v_id == 0 || $v_id == "") ? "NULL" : "'$v_id'";
            $sql_detail = "INSERT INTO ORDER_ITEMS (order_id, variant_id, so_luong, don_gia) 
               VALUES ('$order_id', $v_id_sql, '$qty', '$price')";

            if (mysqli_query($conn, $sql_detail)) {

                // 2.2. TRỪ SỐ LƯỢNG TỒN KHO (Chỉ trừ nếu là biến thể hợp lệ)
                if ($v_id != 0 && $v_id != "") {
                    $sql_update_stock = "UPDATE PRODUCT_VARIANTS 
                                         SET so_luong_ton = so_luong_ton - $qty 
                                         WHERE id = '$v_id' AND so_luong_ton >= $qty";

                    if (!mysqli_query($conn, $sql_update_stock)) {
                        // Ghi log lỗi trừ kho nhưng có thể cho qua để không làm hỏng trải nghiệm khách
                        error_log("Lỗi trừ kho cho variant_id $v_id: " . mysqli_error($conn));
                    }
                }
            } else {
                die("Lỗi chèn sản phẩm: " . mysqli_error($conn));
            }
        }

        // --- BƯỚC 3: DỌN DẸP ---
        mysqli_query($conn, "DELETE FROM CART WHERE user_id = '$user_id'");
        unset($_SESSION['cart']);

        // --- BƯỚC 4: GỬI EMAIL XÁC NHẬN (MỚI THÊM) ---
        sendConfirmationEmail($email, $ten_nguoi_nhan, $ma_don, $tong_thanh_toan);

        header("Location: ../index.php?page=success&id=" . $order_id);
        exit();

    } else {
        die("Lỗi lưu đơn hàng: " . mysqli_error($conn));
    }
} else {
    header("Location: ../index.php");
    exit();
}