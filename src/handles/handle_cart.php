<?php
session_start();
include '../DB/db_connect.php';

/**
 * 1. XỬ LÝ CÁC HÀNH ĐỘNG CẬP NHẬT (GET)
 * Dùng cho nút Tăng, Giảm và Xóa trực tiếp từ URL
 */
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id_key = $_GET['id']; // Đây là cart_id (ví dụ: 5_10)

    if (isset($_SESSION['cart'][$id_key])) {
        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$id_key]['quantity'] += 1;
                break;

            case 'decrease':
                if ($_SESSION['cart'][$id_key]['quantity'] > 1) {
                    $_SESSION['cart'][$id_key]['quantity'] -= 1;
                }
                break;

            case 'delete':
                unset($_SESSION['cart'][$id_key]);
                break;
        }
    }
    header("Location: ../index.php?page=cart");
    exit();
}

/**
 * 2. XỬ LÝ THÊM VÀO GIỎ (POST)
 * Dùng cho nút ADD TO CART từ trang chi tiết
 */
if (isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $variant_id = isset($_POST['variant_id']) ? $_POST['variant_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

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
        $variant_name = "";

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

    header("Location: ../index.php?page=cart");
    exit();
}