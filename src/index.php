<?php 
    session_start();
    include 'DB/db_connect.php'; //Ket nối
    include 'header.php'; // Gọi đầu trang
?>

<main style="background-color: transparent !important;">
    <?php
        // Lấy tham số 'page' từ link (ví dụ: index.php?page=login)
        $p = isset($_GET['page']) ? $_GET['page'] : 'home';

        if ($p == 'login') {
            include 'pages/login_content.php';
        } elseif ($p == 'register') {
            include 'pages/register_content.php';
        }elseif ($p == 'products') {
            include 'pages/product_content.php';
        } else {
            include 'pages/home_content.php';
        }
    ?>
</main>

<?php 
    include 'footer.php'; // Gọi cuối trang
?>