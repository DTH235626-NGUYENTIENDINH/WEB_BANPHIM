<?php
// --- ORDERS LIST ---
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';

$sql = "SELECT o.*, u.ho_ten AS user_name FROM ORDERS o LEFT JOIN USERS u ON o.user_id = u.id WHERE 1=1";

if ($status_filter != '') {
    $sql .= " AND o.trang_thai_don = '$status_filter'";
}
if ($search != '') {
    $sql .= " AND (o.ma_don LIKE '%$search%' OR o.ten_nguoi_nhan LIKE '%$search%')";
}

$sql .= " ORDER BY o.ngay_dat DESC";
$orders = mysqli_query($conn, $sql);

$statuses = array('Pending', 'Processing', 'Shipping', 'Delivered', 'Cancelled');
?>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <h3 class="data-table-title mb-0">
            All Orders
            <span class="text-muted" style="font-size: 13px; font-weight: 400;">(<?php echo mysqli_num_rows($orders); ?>)</span>
        </h3>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Search -->
            <form method="GET" class="d-flex gap-2 m-0">
                <input type="hidden" name="page" value="orders">
                <?php if ($status_filter): ?><input type="hidden" name="status" value="<?php echo $status_filter; ?>"><?php endif; ?>
                <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" 
                       class="form-control form-control-sm" placeholder="Search order/name..." style="width: 180px; border-radius: 6px;">
                <button type="submit" class="btn-admin btn-admin-sm"><i class="fa-solid fa-search"></i></button>
            </form>

            <!-- Status Filter -->
            <div class="d-flex gap-1">
                <a href="index.php?page=orders" 
                   class="btn-admin btn-admin-sm <?php echo $status_filter == '' ? '' : 'btn-admin-outline'; ?>"
                   style="font-size: 11px;">All</a>
                <?php foreach ($statuses as $s): ?>
                <a href="index.php?page=orders&status=<?php echo $s; ?>" 
                   class="btn-admin btn-admin-sm <?php echo $status_filter == $s ? '' : 'btn-admin-outline'; ?>"
                   style="font-size: 11px;"><?php echo $s; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($orders) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                <tr>
                    <td><strong><?php echo $row['ma_don']; ?></strong></td>
                    <td><?php echo $row['ten_nguoi_nhan']; ?></td>
                    <td><?php echo $row['sdt_nguoi_nhan']; ?></td>
                    <td><strong><?php echo number_format($row['tong_thanh_toan'], 0, ',', '.'); ?> ₫</strong></td>
                    <td><span class="badge bg-light text-dark border"><?php echo $row['phuong_thuc_tt']; ?></span></td>
                    <td>
                        <span class="badge-status badge-<?php echo strtolower($row['trang_thai_don']); ?>">
                            <?php echo $row['trang_thai_don']; ?>
                        </span>
                    </td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['ngay_dat'])); ?></td>
                    <td>
                        <a href="index.php?page=order_detail&id=<?php echo $row['id']; ?>" 
                           class="btn-admin btn-admin-sm btn-admin-outline"><i class="fa-solid fa-eye"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fa-solid fa-inbox d-block"></i>
                            <p>No orders found</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
