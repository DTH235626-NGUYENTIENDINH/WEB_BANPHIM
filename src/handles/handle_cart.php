<?php
session_start();
include '../DB/db_connect.php';

// 1. XỬ LÝ GET (Tăng/Giảm/Xóa)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id_key = $_GET['id'];
    $parts = explode('_', $id_key);
    $v_id_get = (int) $parts[1];

    if (isset($_SESSION['cart'][$id_key])) {
        $u_id_get = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        switch ($action) {
            case 'increase':
                // --- KIỂM TRA TỒN KHO TRƯỚC KHI TĂNG ---
                $sql_check = "SELECT so_luong_ton FROM product_variants WHERE id = $v_id_get";
                $res_check = mysqli_query($conn, $sql_check);
                $row_check = mysqli_fetch_assoc($res_check);

                if ($_SESSION['cart'][$id_key]['quantity'] < $row_check['so_luong_ton']) {
                    $_SESSION['cart'][$id_key]['quantity'] += 1;
                    if ($u_id_get)
                        mysqli_query($conn, "UPDATE CART SET so_luong = so_luong + 1 WHERE user_id = $u_id_get AND variant_id = $v_id_get");
                } else {
                    // Nếu vượt quá tồn kho, có thể gán một thông báo lỗi vào session để hiển thị bên trang cart
                    $_SESSION['error_cart'] = "Sorry, we only have " . $row_check['so_luong_ton'] . " items in stock.";
                }
                break;

            case 'decrease':
                if ($_SESSION['cart'][$id_key]['quantity'] > 1) {
                    $_SESSION['cart'][$id_key]['quantity'] -= 1;
                    if ($u_id_get)
                        mysqli_query($conn, "UPDATE CART SET so_luong = so_luong - 1 WHERE user_id = $u_id_get AND variant_id = $v_id_get");
                }
                break;

            case 'delete':
                unset($_SESSION['cart'][$id_key]);
                if ($u_id_get)
                    mysqli_query($conn, "DELETE FROM CART WHERE user_id = $u_id_get AND variant_id = $v_id_get");
                break;
        }
    }
    header("Location: ../index.php?page=cart");
    exit();
}

// 2. XỬ LÝ POST (Thêm vào giỏ)
if (isset($_POST['add_to_cart'])) {
    $id = (int) $_POST['product_id'];
    $variant_id = isset($_POST['variant_id']) ? (int) $_POST['variant_id'] : 0;
    $quantity = 1;
    $cart_id = $id . "_" . $variant_id;

    // --- BẮT BUỘC KIỂM TRA TỒN KHO ---
    $sql_v = "SELECT ten_bien_the, gia_ban, so_luong_ton, hinh_anh_bien_the FROM product_variants WHERE id = $variant_id";
    $res_v = mysqli_query($conn, $sql_v);
    $v_data = mysqli_fetch_assoc($res_v);

    $current_qty_in_cart = isset($_SESSION['cart'][$cart_id]) ? $_SESSION['cart'][$cart_id]['quantity'] : 0;

    if (($current_qty_in_cart + $quantity) > $v_data['so_luong_ton']) {
        // Nếu tổng lượng định mua > tồn kho -> Quay về trang detail báo lỗi
        header("Location: ../index.php?page=detail&id=$id&error=out_of_stock");
        exit();
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$cart_id])) {
        $_SESSION['cart'][$cart_id]['quantity'] += $quantity;
    } else {
        $sql = "SELECT ten, gia_hien_thi, anh_dai_dien FROM products WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        $p = mysqli_fetch_assoc($res);

        // Dùng ảnh biến thể (hinh_anh_bien_the), nếu không có thì dùng ảnh sản phẩm (anh_dai_dien)
        $variant_image = !empty($v_data['hinh_anh_bien_the']) ? $v_data['hinh_anh_bien_the'] : $p['anh_dai_dien'];

        $_SESSION['cart'][$cart_id] = array(
            'id' => $id,
            'name' => $p['ten'],
            'price' => (float) $v_data['gia_ban'],
            'image' => $variant_image,
            'variant_id' => $variant_id,
            'variant_name' => $v_data['ten_bien_the'],
            'quantity' => $quantity
        );
    }

    // LƯU DATABASE (Giữ nguyên logic ON DUPLICATE KEY của ông)
    if (isset($_SESSION['user_id'])) {
        $u_id = $_SESSION['user_id'];
        $sql_db = "INSERT INTO CART (user_id, variant_id, so_luong) 
                   VALUES ($u_id, $variant_id, $quantity) 
                   ON DUPLICATE KEY UPDATE so_luong = so_luong + $quantity";
        mysqli_query($conn, $sql_db);
    }

    header("Location: ../index.php?page=cart");
    exit();
}
?>