<div class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h2 class="fw-bold m-0" style="font-size: 2.5rem;">Your Cart</h2>

        <?php
        $actual_total_qty = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                // Phải cộng dồn quantity thì mới ra số 3 như trên icon
                $actual_total_qty += $item['quantity'];
            }
        }
        ?>
        <span class="text-muted"><?php echo $actual_total_qty; ?> items total</span>
    </div>

    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="text-center py-5 border-0 shadow-sm rounded-4 bg-light">
            <i class="fa-solid fa-cart-shopping display-1 text-muted mb-3"></i>
            <p class="fs-5 text-muted">Your cart is currently empty.</p>
            <a href="index.php?page=products" class="btn btn-dark px-5 py-3 rounded-pill fw-bold">GO TO SHOP</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-lg-8">
                <?php
                $total_all = 0;
                foreach ($_SESSION['cart'] as $key => $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_all += $subtotal;
                    ?>
                    <div class="cart-card mb-3 p-4 bg-white rounded-4 shadow-sm border">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="img-container rounded-3 p-3 mb-2" style="background-color: #f0f2f5;">
                                    <img src="img/<?php echo $item['image']; ?>" class="img-fluid">
                                </div>
                            </div>

                            <div class="col-md-5 ps-md-4">
                                <h4 class="fw-bold mb-1"><?php echo $item['name']; ?></h4>

                                <div class="product-selection mt-2">
                                    <?php if (!empty($item['variant_name'])): ?>
                                        <p class="mb-0 text-muted" style="font-size: 0.9rem;">
                                            <span class="opacity-75">Select Version / Switch:</span>
                                            <span class="text-dark fw-bold ms-1"><?php echo $item['variant_name']; ?></span>
                                        </p>
                                    <?php else: ?>
                                        <p class="mb-0 text-muted small italic">Standard Edition</p>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-2">
                                    <span class="badge rounded-pill bg-light text-success border border-success fw-normal px-3"
                                        style="font-size: 0.7rem;">
                                        <i class="fa-solid fa-check me-1"></i> In stock
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4 text-end">
                                <div class="mb-4">
                                    <h3 class="fw-bold mb-0"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</h3>
                                    <?php if ($item['quantity'] > 1): ?>
                                        <div class="text-muted small"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫ /
                                            piece</div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center justify-content-end gap-3">
                                    <a href="handles/handle_cart.php?action=delete&id=<?php echo $key; ?>"
                                        class="text-muted hover-danger" onclick="return confirm('Remove this item?')">
                                        <i class="fa-regular fa-trash-can fs-5"></i>
                                    </a>

                                    <div
                                        class="qty-stepper d-flex align-items-center border rounded-3 bg-white overflow-hidden">

                                        <?php if ($item['quantity'] > 1): ?>
                                            <a href="handles/handle_cart.php?action=decrease&id=<?php echo $key; ?>"
                                                class="btn-step border-end">
                                                <i class="fa-solid fa-minus"></i>
                                            </a>
                                        <?php endif; ?>

                                        <span class="px-3 fw-bold fs-5" style="min-width: 50px; text-align: center;">
                                            <?php echo $item['quantity']; ?>
                                        </span>

                                        <a href="handles/handle_cart.php?action=increase&id=<?php echo $key; ?>"
                                            class="btn-step bg-dark text-white">
                                            <i class="fa-solid fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <a href="index.php?page=products" class="btn btn-link text-dark text-decoration-none p-0 mt-3 fw-bold">
                    <i class="fa-solid fa-chevron-left me-2"></i> Continue Shopping
                </a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 bg-white rounded-4 sticky-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4 text-uppercase">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold"><?php echo number_format($total_all, 0, ',', '.'); ?> ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success fw-bold">Free</span>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold fs-4">Total</span>
                        <span class="fw-bold fs-3 text-danger"><?php echo number_format($total_all, 0, ',', '.'); ?>
                            ₫</span>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="index.php?page=checkout" class="btn btn-checkout rounded-3 fw-bold shadow-sm">
                            GO TO CHECKOUT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>