<?php
session_start();
include 'DB/db_connect.php'; //Ket nối
include 'header.php'; // Gọi đầu trang
?>

<main style="background-color: transparent !important;">
    <?php
    // Lấy tham số 'page' từ link (ví dụ: index.php?page=login)
    $p = isset($_GET['page']) ? $_GET['page'] : 'home';

    switch ($p) {
        case 'login':
            include 'pages/login_content.php';
            break;

        case 'register':
            include 'pages/register_content.php';
            break;

        case 'products':
            include 'pages/product_content.php';
            break;

        case 'detail':
            include 'pages/detailproduct_content.php';
            break;

        case 'cart':
            include 'pages/cart_content.php';
            break;

        case 'checkout':
            include 'pages/checkout_content.php';
            break;

        case 'success':
            include 'pages/success.php';
            break;
        case 'profile':
            include 'pages/profile_content.php';
            break;
        case 'orders':
            include 'pages/orders_content.php';
            break;
        case 'order_detail':
            include 'pages/order_detail_content.php';
            break;
        case 'order_status':
            include 'pages/order_status.php';
            break;
        case 'home':
        default:
            include 'pages/home_content.php';
            break;
    }
    ?>
</main>

<?php
include 'footer.php'; // Gọi cuối trang
?>