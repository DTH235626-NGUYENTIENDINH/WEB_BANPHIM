<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?page=login");
    exit();
}

$u_id = $_SESSION['user_id'];
$sql_orders = "SELECT * FROM ORDERS WHERE user_id = $u_id ORDER BY ngay_dat DESC";
$res_orders = mysqli_query($conn, $sql_orders);
?>

<div class="container py-5">
    <h3 class="fw-bold mb-4">MY ORDERS</h3>
    
    <?php if (mysqli_num_rows($res_orders) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle border-0">
                <thead class="bg-light">
                    <tr class="small text-secondary">
                        <th class="border-0 p-3">ORDER ID</th>
                        <th class="border-0 p-3">DATE</th>
                        <th class="border-0 p-3">TOTAL</th>
                        <th class="border-0 p-3">STATUS</th>
                        <th class="border-0 p-3">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($order = mysqli_fetch_assoc($res_orders)): ?>
                        <tr>
                            <td class="p-3 fw-bold">#<?php echo $order['id']; ?></td>
                            <td class="p-3"><?php echo date('d/m/Y', strtotime($order['ngay_dat'])); ?></td>
                            <td class="p-3 fw-bold"><?php echo number_format($order['tong_tien_hang'], 0, ',', '.'); ?> ₫</td>
                            <td class="p-3">
                                <span class="badge rounded-pill bg-dark py-2 px-3"><?php echo $order['trang_thai_don']; ?></span>
                            </td>
                            <td class="p-3">
                                <a href="index.php?page=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill">View Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fa-solid fa-box-open fs-1 text-light mb-3"></i>
            <p class="text-secondary">You haven't placed any orders yet.</p>
            <a href="index.php?page=products" class="btn btn-dark rounded-pill px-4">Shop Now</a>
        </div>
    <?php endif; ?>
</div>