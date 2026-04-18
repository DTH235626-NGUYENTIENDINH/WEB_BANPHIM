<?php
// --- ORDER DETAIL ---
if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Order ID missing.</div>';
    return;
}

$order_id = (int)$_GET['id'];
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT o.*, u.ho_ten AS user_name, u.email AS user_email
                                                  FROM ORDERS o LEFT JOIN USERS u ON o.user_id = u.id 
                                                  WHERE o.id = $order_id"));

if (!$order) {
    echo '<div class="alert alert-danger">Order not found.</div>';
    return;
}

$items = mysqli_query($conn, "SELECT oi.*, p.ten AS product_name, p.anh_dai_dien,
                               v.ten_bien_the, v.product_id,
                               COALESCE(v.hinh_anh_bien_the, p.anh_dai_dien) AS hinh_anh
                               FROM ORDER_ITEMS oi 
                               LEFT JOIN PRODUCT_VARIANTS v ON oi.variant_id = v.id
                               LEFT JOIN PRODUCTS p ON v.product_id = p.id
                               WHERE oi.order_id = $order_id");

$statuses = array('Pending', 'Processing', 'Shipping', 'Delivered', 'Cancelled');
?>

<a href="index.php?page=orders" class="btn-admin btn-admin-sm btn-admin-outline mb-4">
    <i class="fa-solid fa-arrow-left"></i> Back to Orders
</a>

<div class="row g-4">
    <!-- Order Info -->
    <div class="col-lg-4">
        <div class="form-card">
            <h4 class="form-card-title">Order Info</h4>
            <div class="mb-3">
                <label class="form-label">Order ID</label>
                <p class="mb-0 fw-bold"><?php echo $order['ma_don']; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <p class="mb-0"><?php echo date('d/m/Y H:i', strtotime($order['ngay_dat'])); ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Method</label>
                <p class="mb-0"><span class="badge bg-light text-dark border"><?php echo $order['phuong_thuc_tt']; ?></span></p>
            </div>
            <hr>
            <div class="mb-3">
                <label class="form-label">Recipient</label>
                <p class="mb-0 fw-bold"><?php echo $order['ten_nguoi_nhan']; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <p class="mb-0"><?php echo $order['sdt_nguoi_nhan']; ?></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <p class="mb-0 text-muted"><?php echo $order['dia_chi_giao']; ?></p>
            </div>
            <?php if ($order['user_email']): ?>
            <div class="mb-0">
                <label class="form-label">Email</label>
                <p class="mb-0 text-muted"><?php echo $order['user_email']; ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Update Status -->
        <div class="form-card">
            <h4 class="form-card-title">Update Status</h4>
            <form action="modules/order_action.php" method="POST" class="admin-form">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                <div class="mb-3">
                    <select name="trang_thai_don" class="form-select">
                        <?php foreach ($statuses as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $order['trang_thai_don'] == $s ? 'selected' : ''; ?>>
                            <?php echo $s; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-admin w-100" style="justify-content: center;">
                    <i class="fa-solid fa-rotate"></i> Update Status
                </button>
            </form>
        </div>
    </div>

    <!-- Order Items -->
    <div class="col-lg-8">
        <div class="data-table-wrapper mb-4">
            <div class="data-table-header">
                <h3 class="data-table-title">Order Items</h3>
                <span class="badge-status badge-<?php echo strtolower($order['trang_thai_don']); ?>" style="font-size: 13px;">
                    <?php echo $order['trang_thai_don']; ?>
                </span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($items)): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="../public/products/<?php echo $item['hinh_anh'] ? $item['hinh_anh'] : 'default.png'; ?>" 
                                     class="product-thumb">
                                <strong><?php echo $item['product_name'] ? $item['product_name'] : 'Deleted Product'; ?></strong>
                            </div>
                        </td>
                        <td><?php echo $item['ten_bien_the'] ? $item['ten_bien_the'] : '-'; ?></td>
                        <td><?php echo number_format($item['don_gia'], 0, ',', '.'); ?> ₫</td>
                        <td><?php echo $item['so_luong']; ?></td>
                        <td><strong><?php echo number_format($item['don_gia'] * $item['so_luong'], 0, ',', '.'); ?> ₫</strong></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="form-card">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span><?php echo number_format($order['tong_tien_hang'], 0, ',', '.'); ?> ₫</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Shipping</span>
                <span><?php echo number_format($order['phi_ship'], 0, ',', '.'); ?> ₫</span>
            </div>
            <?php if ($order['giam_gia'] > 0): ?>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Discount</span>
                <span class="text-success">-<?php echo number_format($order['giam_gia'], 0, ',', '.'); ?> ₫</span>
            </div>
            <?php endif; ?>
            <hr>
            <div class="d-flex justify-content-between">
                <span class="fw-bold fs-5">Total</span>
                <span class="fw-bold fs-4 text-danger"><?php echo number_format($order['tong_thanh_toan'], 0, ',', '.'); ?> ₫</span>
            </div>
        </div>
    </div>
</div>
