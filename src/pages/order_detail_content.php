<?php
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$order_id = (int) $_GET['id'];
$u_id = $_SESSION['user_id'];

// 1. Lấy thông tin chung của Đơn hàng (Bảo mật: Phải thuộc về User đang login)
$sql_order = "SELECT * FROM ORDERS WHERE id = $order_id AND user_id = $u_id";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

if (!$order) {
    die("<div class='container py-5 text-center'><h3>Order not found or access denied.</h3></div>");
}

// 2. Lấy danh sách sản phẩm trong đơn hàng đó
$sql_items = "SELECT 
                oi.so_luong, 
                oi.don_gia, 
                p.ten, 
                p.anh_dai_dien, 
                v.ten_bien_the,
                COALESCE(v.hinh_anh_bien_the, p.anh_dai_dien) AS hinh_anh_hien_thi 
              FROM ORDER_ITEMS oi
              LEFT JOIN PRODUCT_VARIANTS v ON oi.variant_id = v.id
              LEFT JOIN PRODUCTS p ON v.product_id = p.id
              WHERE oi.order_id = $order_id";
$res_items = mysqli_query($conn, $sql_items);
?>

<div class="container py-5">
    <a href="index.php?page=orders" class="btn btn-link text-dark p-0 mb-4 text-decoration-none">
        <i class="fa-solid fa-arrow-left me-2"></i> Back to My Orders
    </a>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4 text-uppercase">Delivery Info</h5>
                <div class="mb-3">
                    <label class="small text-secondary fw-bold text-uppercase">Recipient</label>
                    <p class="mb-0 fw-bold"><?php echo $order['ten_nguoi_nhan']; ?></p>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary fw-bold text-uppercase">Phone</label>
                    <p class="mb-0"><?php echo $order['sdt_nguoi_nhan']; ?></p>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary fw-bold text-uppercase">Address</label>
                    <p class="mb-0 text-muted"><?php echo $order['dia_chi_giao']; ?></p>
                </div>
                <div class="mb-0">
                    <label class="small text-secondary fw-bold text-uppercase">Payment Method</label>
                    <p class="mb-0"><span
                            class="badge bg-light text-dark border"><?php echo $order['phuong_thuc_tt']; ?></span></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-uppercase">Order #<?php echo $order['ma_don']; ?></h5>
                    <span class="badge rounded-pill bg-dark px-3 py-2"><?php echo $order['trang_thai_don']; ?></span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle border-0">
                        <thead>
                            <tr class="small text-secondary text-uppercase">
                                <th class="border-0 px-0">Product</th>
                                <th class="border-0 text-center">Price</th>
                                <th class="border-0 text-center">Qty</th>
                                <th class="border-0 text-end px-0">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($res_items)): ?>
                                <tr>
                                    <td class="border-0 px-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="../public/products/<?php echo $item['hinh_anh_hien_thi']; ?>" class="rounded border"
                                                style="width: 50px; height: 50px; object-fit: contain;">
                                            <div class="ms-3">
                                                <p class="mb-0 fw-bold small"><?php echo $item['ten']; ?></p>
                                                <small class="text-muted">
                                                    <?php echo !empty($item['ten_bien_the']) ? $item['ten_bien_the'] : "Standard Edition"; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0 text-center small">
                                        <?php echo number_format($item['don_gia'], 0, ',', '.'); ?> ₫</td>
                                    <td class="border-0 text-center small"><?php echo $item['so_luong']; ?></td>
                                    <td class="border-0 text-end px-0 fw-bold small">
                                        <?php echo number_format($item['don_gia'] * $item['so_luong'], 0, ',', '.'); ?> ₫
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end border-0 pt-4 text-secondary small uppercase fw-bold">
                                    Items Total:</td>
                                <td class="text-end border-0 pt-4 fw-bold">
                                    <?php echo number_format($order['tong_tien_hang'], 0, ',', '.'); ?> ₫</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end border-0 text-secondary small uppercase fw-bold">
                                    Shipping Fee:</td>
                                <td class="text-end border-0 text-success fw-bold">Free</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end border-0 pt-3">
                                    <h5 class="fw-bold text-uppercase mb-0">Total Amount:</h5>
                                </td>
                                <td class="text-end border-0 pt-3">
                                    <h5 class="fw-bold text-danger mb-0">
                                        <?php echo number_format($order['tong_thanh_toan'], 0, ',', '.'); ?> ₫</h5>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>