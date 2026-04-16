<?php
// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login&msg=login_required");
    exit();
}

// 2. Lấy thông tin User từ Database để tự động điền
$u_id = $_SESSION['user_id'];
$sql_user = "SELECT ho_ten, email, so_dien_thoai, dia_chi FROM USERS WHERE id = $u_id";
$res_user = mysqli_query($conn, $sql_user);
$user_data = mysqli_fetch_assoc($res_user);

// 3. Tính lại tổng tiền
$total_all = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_all += $item['price'] * $item['quantity'];
    }
}
?>

<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <h4 class="fw-bold mb-4 text-uppercase">Shipping Information</h4>
            <form action="handles/process_order.php" method="POST" id="checkout-form">
                <div class="row g-3">
                    <div class="col-sm-12">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Full Name</label>
                        <input type="text" name="fullname" class="form-control py-3 rounded-3 border-0 bg-light" 
                               value="<?php echo $user_data['ho_ten']; ?>" placeholder="Enter your full name" required>
                    </div>

                    <div class="col-md-7">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Email Address</label>
                        <input type="email" name="email" class="form-control py-3 rounded-3 border-0 bg-light" 
                               value="<?php echo $user_data['email']; ?>" placeholder="name@example.com" required>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Phone Number</label>
                        <input type="text" name="phone" class="form-control py-3 rounded-3 border-0 bg-light" 
                               value="<?php echo $user_data['so_dien_thoai']; ?>" placeholder="090..." required>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-bold text-uppercase text-secondary">Shipping Address</label>
                        <input type="text" name="address" class="form-control py-3 rounded-3 border-0 bg-light" 
                               value="<?php echo $user_data['dia_chi']; ?>" placeholder="House number, street, district..." required>
                    </div>

                    <div class="col-12 mt-4">
                        <h5 class="fw-bold mb-3 small text-uppercase text-secondary">Payment Method</h5>
                        <div class="form-check p-3 border rounded-3 mb-2 bg-white">
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
                <button class="btn btn-checkout w-100 py-3 rounded-pill fw-bold shadow-sm" type="submit">
                    COMPLETE PURCHASE <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 bg-light p-4 rounded-4 shadow-sm">
                <h4 class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold text-uppercase" style="font-size: 1rem;">Order Summary</span>
                    <span class="badge bg-dark rounded-pill"><?php echo count($_SESSION['cart']); ?> items</span>
                </h4>
                
                <ul class="list-group list-group-flush mb-3 bg-transparent">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm bg-transparent border-0 px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-white p-1 rounded border me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <img src="img/<?php echo $item['image']; ?>" class="img-fluid" style="max-height: 100%;">
                            </div>
                            <div>
                                <h6 class="my-0 fw-bold"><?php echo $item['name']; ?></h6>
                                <div class="text-muted small">
                                    Qty: <?php echo $item['quantity']; ?>
                                    <?php if($item['variant_name']): ?>
                                        <span class="mx-1">|</span> <?php echo $item['variant_name']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <span class="text-dark fw-bold small"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> ₫</span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <hr class="opacity-10">
                <div class="d-flex justify-content-between mb-2 small text-uppercase fw-bold text-secondary">
                    <span>Subtotal</span>
                    <span><?php echo number_format($total_all, 0, ',', '.'); ?> ₫</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-uppercase fw-bold text-secondary">
                    <span>Shipping</span>
                    <span class="text-success">Free</span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <strong class="fs-5 text-uppercase">Total</strong>
                    <strong class="fs-4 text-danger"><?php echo number_format($total_all, 0, ',', '.'); ?> ₫</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Style Wooting Custom */
.btn-checkout {
    background-color: #FFCC00 !important; /* Yellow Wooting */
    color: #000 !important;
    letter-spacing: 1px;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
}
.btn-checkout:hover {
    background-color: #000 !important;
    color: #FFCC00 !important;
    transform: translateY(-3px);
}
.form-control:focus {
    background-color: #fff !important;
    border-color: #FFCC00;
    box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.2);
}
.text-secondary { color: #6c757d !important; }
</style>