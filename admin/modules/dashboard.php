<?php
// --- DASHBOARD ---
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM PRODUCTS WHERE deleted_at IS NULL"));
$total_products = $row['c'];

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM ORDERS"));
$total_orders = $row['c'];

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(tong_thanh_toan), 0) as c FROM ORDERS WHERE trang_thai_don = 'Delivered'"));
$total_revenue = $row['c'];

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM USERS WHERE vai_tro = 'khach'"));
$total_users = $row['c'];

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM ORDERS WHERE trang_thai_don = 'Pending'"));
$pending_orders = $row['c'];

$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM PRODUCT_VARIANTS"));
$total_variants = $row['c'];
?>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #fef3c7; color: #92400e;">
                <i class="fa-solid fa-keyboard"></i>
            </div>
            <div class="stat-card-value"><?php echo $total_products; ?></div>
            <div class="stat-card-label">Total Products</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #dbeafe; color: #1e40af;">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <div class="stat-card-value"><?php echo $total_orders; ?></div>
            <div class="stat-card-label">Total Orders</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #dcfce7; color: #166534;">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
            <div class="stat-card-value"><?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
            <div class="stat-card-label">Revenue (₫)</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-icon" style="background: #e0e7ff; color: #3730a3;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-card-value"><?php echo $total_users; ?></div>
            <div class="stat-card-label">Customers</div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon mb-0" style="background: #fee2e2; color: #991b1b;">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div>
                    <div class="stat-card-value" style="font-size: 22px;"><?php echo $pending_orders; ?></div>
                    <div class="stat-card-label">Pending Orders</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon mb-0" style="background: #fef3c7; color: #92400e;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <div class="stat-card-value" style="font-size: 22px;"><?php echo $total_variants; ?></div>
                    <div class="stat-card-label">Product Variants</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3 class="data-table-title">Recent Orders</h3>
        <a href="index.php?page=orders" class="btn-admin btn-admin-sm btn-admin-outline">
            View All <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $recent = mysqli_query($conn, "SELECT o.*, u.ho_ten FROM ORDERS o LEFT JOIN USERS u ON o.user_id = u.id ORDER BY o.ngay_dat DESC LIMIT 5");
            if (mysqli_num_rows($recent) > 0):
                while ($row = mysqli_fetch_assoc($recent)):
                    $status_class = 'badge-' . strtolower($row['trang_thai_don']);
            ?>
            <tr>
                <td><strong><?php echo $row['ma_don']; ?></strong></td>
                <td><?php echo $row['ten_nguoi_nhan']; ?></td>
                <td><?php echo number_format($row['tong_thanh_toan'], 0, ',', '.'); ?> ₫</td>
                <td><span class="badge-status <?php echo $status_class; ?>"><?php echo $row['trang_thai_don']; ?></span></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_dat'])); ?></td>
                <td>
                    <a href="index.php?page=order_detail&id=<?php echo $row['id']; ?>" class="btn-admin btn-admin-sm btn-admin-outline">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endwhile; else: ?>
            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox d-block"></i>
                        <p>No orders yet</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
