<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rabu keyboard</title>
    <script src="https://unpkg.com/@hotwired/turbo@7.1.0/dist/turbo.es201.umd.js"></script>
    <link rel="stylesheet" href="CSS/products.css">
    <link rel="stylesheet" href="CSS/detail_products.css">
    <link rel="stylesheet" href="CSS/cart.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>

<body>
    <!-- THANH MENU -->
    <nav class="navbar navbar-expand-lg bg-white sticky-top border-bottom py-3">
        <div class="container-fluid px-md-5">

            <div class="d-flex align-items-center">
                <button class="navbar-toggler border-0 shadow-none ps-0 me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navRabu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand fw-bold fs-3 m-0" href="index.php" style="letter-spacing: -1.5px;">RABU
                    Keyboard</a>
            </div>

            <div class="collapse navbar-collapse justify-content-center" id="navRabu">
                <ul class="navbar-nav gap-lg-4 text-start">
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="index.php?page=products">Our
                            products</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="#">Software</a></li>
                    <li class="nav-item"><a class="nav-link text-dark fw-medium" href="#">Support</a></li>
                </ul>
            </div>

            <div class="d-flex align-items-center gap-3 gap-md-4">

                <a href="#" class="text-dark nav-icon-link d-none d-sm-block"><i class="fa-regular fa-envelope"></i></a>

                <a href="#" class="text-dark nav-icon-link"><i class="fa-solid fa-magnifying-glass"></i></a>

                <a href="index.php?page=login" class="text-dark nav-icon-link"><i class="fa-regular fa-user"></i></a>
                
                <!--Cart-->
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'];
                    }
                }
                ?>
                <a href="index.php?page=cart" class="text-dark nav-icon-link position-relative">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($cart_count > 0): ?>
                        <span class="cart-badge">
                            <?php echo $cart_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>