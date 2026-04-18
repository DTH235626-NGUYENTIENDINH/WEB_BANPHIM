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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<body>
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
                <div class="dropdown">
                    <a href="#" class="text-dark nav-icon-link" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3 border-0 shadow-sm rounded-3 mt-3 animate__animated animate__fadeIn animate__faster" style="width: 300px;">
                        <form action="index.php" method="GET" class="d-flex m-0">
                            <input type="hidden" name="page" value="products">
                            <input type="text" name="q" class="form-control rounded-pill me-2 shadow-none focus-ring focus-ring-dark border" placeholder="Search products..." required>
                            <button type="submit" class="btn btn-dark rounded-circle px-3"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="dropdown">
                        <a href="#" class="text-dark nav-icon-link text-decoration-none d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown">
                            <i class="fa-regular fa-circle-user fs-5"></i>
                            <span class="small fw-bold d-none d-md-inline">
                                <?php
                                $name_parts = explode(' ', $_SESSION['user_name']);
                                echo strtoupper($name_parts[0]);
                                ?>
                            </span>
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-3 animate__animated animate__fadeIn animate__faster">
                            <li><a class="dropdown-item small py-2" href="index.php?page=profile"><i
                                        class="fa-solid fa-address-card me-2"></i> My Profile</a></li>
                            <li><a class="dropdown-item small py-2" href="index.php?page=orders"><i
                                        class="fa-solid fa-box me-2"></i> Orders</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item small py-2 fw-bold" href="../admin/index.php"><i
                                        class="fa-solid fa-gauge-high me-2"></i> Admin Panel</a></li>
                            <?php endif; ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item small py-2 text-danger" href="handles/logout_action.php"><i
                                        class="fa-solid fa-right-from-bracket me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="index.php?page=login" class="text-dark nav-icon-link">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>

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