<?php
// Tính lại tổng tiền để hiển thị
$total_all = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_all += $item['price'] * $item['quantity'];
}
?>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4 text-uppercase">Shipping Information</h4>
            <form action="handles/process_order.php" method="POST" id="checkout-form">
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="fullname" class="form-control py-2 rounded-3" placeholder="Nguyen Van A" required>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" name="email" class="form-control py-2 rounded-3" placeholder="name@example.com" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control py-2 rounded-3" placeholder="090..." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold">Address</label>
                        <input type="text" name="address" class="form-control py-2 rounded-3" placeholder="123 Street, District 1..." required>
                    </div>

                    <div class="col-12 mt-4">
                        <h5 class="fw-bold mb-3 small text-uppercase">Payment Method</h5>
                        <div class="form-check p-3 border rounded-3 mb-2">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment" id="cod" value="COD" checked>
                            <label class="form-check-label fw-bold" for="cod">
                                <i class="fa-solid fa-truck me-2"></i> Cash on Delivery (COD)
                            </label>
                        </div>
                        <div class="form-check p-3 border rounded-3 opacity-50">
                            <input class="form-check-input ms-0 me-3" type="radio" name="payment" id="bank" value="Bank" disabled>
                            <label class="form-check-label fw-bold" for="bank">
                                <i class="fa-solid fa-building-columns me-2"></i> Bank Transfer (Coming Soon)
                            </label>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <button class="btn btn-checkout w-100 py-3 rounded-3 fw-bold shadow-sm" type="submit">
                    COMPLETE PURCHASE
                </button>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 bg-light p-4 rounded-4 shadow-sm">
                <h4 class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold text-uppercase" style="font-size: 1rem;">Your Order</span>
                    <span class="badge bg-dark rounded-pill"><?php echo count($_SESSION['cart']); ?></span>
                </h4>
                <ul class="list-group list-group-flush mb-3 bg-transparent">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm bg-transparent border-0 px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-1 rounded border me-3" style="width: 60px;">
                                <img src="img/<?php echo $item['image']; ?>" class="img-fluid">
                            </div>
                            <div>
                                <h6 class="my-0 fw-bold"><?php echo $item['name']; ?></h6>
                                <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                <?php if($item['variant_name']): ?>
                                    <br><small class="text-muted"><?php echo $item['variant_name']; ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <span class="text-muted small"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> ₫</span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span><?php echo number_format($total_all, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping</span>
                    <span class="text-success fw-bold">Free</span>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <strong class="fs-5">Total</strong>
                    <strong class="fs-4 text-danger"><?php echo number_format($total_all, 0, ',', '.'); ?> ₫</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-checkout {
    background-color: #ffcc00 !important;
    color: #000 !important;
    letter-spacing: 1px;
    font-size: 0.9rem;
    transition: 0.3s;
}
.btn-checkout:hover {
    background-color: #e6b800 !important;
    transform: translateY(-2px);
}
.form-control:focus {
    border-color: #ffcc00;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25);
}
</style>