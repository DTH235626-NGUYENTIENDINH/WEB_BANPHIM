<?php
session_start();
include '../DB/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_SESSION['cart'])) {
    
    // 1. Lấy dữ liệu từ Form (Khớp với các thuộc tính 'name' trong thẻ input)
    $ten_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email          = mysqli_real_escape_string($conn, $_POST['email']);
    $sdt_nguoi_nhan = mysqli_real_escape_string($conn, $_POST['phone']);
    $dia_chi_giao   = mysqli_real_escape_string($conn, $_POST['address']);
    $phuong_thuc_tt = mysqli_real_escape_string($conn, $_POST['payment']); // giá trị: 'tien_mat'
    
    // Tạo mã đơn hàng ngẫu nhiên (Vì cột ma_don là NOT NULL UNIQUE)
    $ma_don = "ORD-" . strtoupper(uniqid());

    // Tính toán tiền bạc
    $tong_tien_hang = 0;
    foreach ($_SESSION['cart'] as $item) {
        $tong_tien_hang += $item['price'] * $item['quantity'];
    }
    $phi_ship = 0; // Đang để Free ship
    $tong_thanh_toan = $tong_tien_hang + $phi_ship;

    // 2. Chèn vào bảng ORDERS (Tên cột phải chuẩn đét theo SQL của ông)
    $sql_order = "INSERT INTO ORDERS (
                    ma_don, ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao, 
                    tong_tien_hang, phi_ship, tong_thanh_toan, phuong_thuc_tt, trang_thai_don
                  ) VALUES (
                    '$ma_don', '$ten_nguoi_nhan', '$sdt_nguoi_nhan', '$dia_chi_giao', 
                    '$tong_tien_hang', '$phi_ship', '$tong_thanh_toan', '$phuong_thuc_tt', 'cho_xac_nhan'
                  )";

    if (mysqli_query($conn, $sql_order)) {
        $order_id = mysqli_insert_id($conn);

        // 3. Chèn vào bảng ORDER_ITEMS (Lưu ý: dùng variant_id)
        foreach ($_SESSION['cart'] as $variant_id => $item) {
            $qty = $item['quantity'];
            $price = $item['price'];

            $sql_detail = "INSERT INTO ORDER_ITEMS (order_id, variant_id, so_luong, don_gia) 
                           VALUES ('$order_id', '$variant_id', '$qty', '$price')";
            mysqli_query($conn, $sql_detail);
        }

        // 4. Reset giỏ hàng
        unset($_SESSION['cart']);

        // 5. Sang trang thành công
        header("Location: ../index.php?page=success&id=" . $order_id);
    } else {
        echo "Lỗi SQL: " . mysqli_error($conn);
    }
} else {
    header("Location: ../index.php");
}
?>