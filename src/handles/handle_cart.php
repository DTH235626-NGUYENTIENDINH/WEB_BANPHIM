<?php
session_start();
include '../DB/db_connect.php';

// 1. XỬ LÝ GET (Tăng/Giảm/Xóa) - Giữ nguyên logic cũ của ông nhưng fix biến id_key
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id_key = $_GET['id'];
    $parts = explode('_', $id_key);
    $v_id_get = (int) $parts[1];

    if (isset($_SESSION['cart'][$id_key])) {
        $u_id_get = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$id_key]['quantity'] += 1;
                if ($u_id_get)
                    mysqli_query($conn, "UPDATE CART SET so_luong = so_luong + 1 WHERE user_id = $u_id_get AND variant_id = $v_id_get");
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

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$cart_id])) {
        $_SESSION['cart'][$cart_id]['quantity'] += $quantity;
    } else {
        $sql = "SELECT ten, gia_hien_thi, anh_dai_dien FROM products WHERE id = $id";
        $res = mysqli_query($conn, $sql);
        $p = mysqli_fetch_assoc($res);

        $final_price = $p['gia_hien_thi'];
        $variant_name = "Standard Edition";

        if ($variant_id > 0) {
            $sql_v = "SELECT ten_bien_the, gia_ban FROM product_variants WHERE id = $variant_id";
            $res_v = mysqli_query($conn, $sql_v);
            if ($v = mysqli_fetch_assoc($res_v)) {
                $final_price = $v['gia_ban'];
                $variant_name = $v['ten_bien_the'];
            }
        }

        $_SESSION['cart'][$cart_id] = array(
            'id' => $id,
            'name' => $p['ten'],
            'price' => (float) $final_price,
            'image' => $p['anh_dai_dien'],
            'variant_id' => $variant_id,
            'variant_name' => $variant_name,
            'quantity' => $quantity
        );
    }

    // LƯU DATABASE
    if (isset($_SESSION['user_id'])) {
        $u_id = $_SESSION['user_id'];

        $v_id_sql = ($variant_id == 0) ? "NULL" : $variant_id;

        $sql_db = "INSERT INTO CART (user_id, variant_id, so_luong) 
               VALUES ($u_id, $v_id_sql, $quantity) 
               ON DUPLICATE KEY UPDATE so_luong = so_luong + $quantity";

        if (!mysqli_query($conn, $sql_db)) {
            die("Lỗi Database: " . mysqli_error($conn) . " | Câu lệnh: " . $sql_db);
        }
    }

    header("Location: ../index.php?page=cart");
    exit();
}
?>