<?php
session_start();
require_once '../DB/db_connect.php';

if (isset($_POST['login'])) {
    $user_input = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $hashed_input = md5($password);

    $sql = "SELECT * FROM USERS WHERE (username = '$user_input' OR email = '$user_input') AND mat_khau = '$hashed_input' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $user_id = $user['id'];

        // --- ĐỒNG BỘ GIỎ HÀNG (BẢN FIX) ---
        // Phải JOIN 3 bảng để lấy đủ thông tin hiển thị ở trang Cart
        $sql_cart = "SELECT 
                        c.so_luong, 
                        v.id AS variant_id, 
                        v.ten_bien_the, 
                        v.gia_ban, 
                        p.id AS product_id, 
                        p.ten, 
                        p.anh_dai_dien 
                     FROM CART c
                     JOIN PRODUCT_VARIANTS v ON c.variant_id = v.id
                     JOIN PRODUCTS p ON v.product_id = p.id
                     WHERE c.user_id = '$user_id'";

        $result_cart = mysqli_query($conn, $sql_cart);
        $_SESSION['cart'] = array();

        while ($row = mysqli_fetch_assoc($result_cart)) {
            // Tạo cart_id theo định dạng "ID_SảnPhẩm_ID_BiếnThể" cho khớp với file handle_cart
            $cart_id = $row['product_id'] . "_" . $row['variant_id'];

            // Nạp ĐẦY ĐỦ "vitamin" cho Session để trang cart_content không bị lỗi index
            $_SESSION['cart'][$cart_id] = array(
                'id' => $row['product_id'],
                'name' => $row['ten'],
                'price' => (float) $row['gia_ban'],
                'image' => $row['anh_dai_dien'],
                'variant_id' => $row['variant_id'],
                'variant_name' => $row['ten_bien_the'],
                'quantity' => (int) $row['so_luong']
            );
        }
        // ------------------------------

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['ho_ten'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['vai_tro'];

        if (isset($_POST['redirect']) && $_POST['redirect'] == 'checkout') {
            header("Location: ../index.php?page=checkout");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        header("Location: ../index.php?page=login&error=wrong_credentials");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>