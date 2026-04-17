<div class="container my-5" style="min-height: 50vh;">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <h2 class="fw-bold text-uppercase mb-4" style="letter-spacing: -1px;">Track Your Order</h2>
            <p class="text-secondary mb-4">Enter your order ID to check your shipping status.</p>

            <form action="" method="POST" class="mb-5">
                <div class="input-group mb-3 shadow-sm rounded-pill overflow-hidden border">
                    <input type="text" name="order_id" class="form-control border-0 px-4" 
                           placeholder="Ex: ORD-123456..." required>
                    <button class="btn btn-dark px-4 fw-bold" type="submit" name="track">TRACK</button>
                </div>
            </form>

            <?php
            if (isset($_POST['track'])) {
                $order_input = mysqli_real_escape_string($conn, $_POST['order_id']);
                $order_input = trim($order_input); 
            
                $sql = "SELECT * FROM orders WHERE ma_don = '$order_input'";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die("<div class='alert alert-danger'>System Error: " . mysqli_error($conn) . "</div>");
                }

                if (mysqli_num_rows($result) > 0) {
                    $order = mysqli_fetch_assoc($result);

                    $status = $order['trang_thai_don'];
                    $badgeClass = 'bg-secondary';
                    
                    // Logic đổi màu Badge theo trạng thái
                    if ($status == 'Success') $badgeClass = 'bg-success';
                    if ($status == 'Pending') $badgeClass = 'bg-warning text-dark';
                    if ($status == 'Shipping') $badgeClass = 'bg-info text-white';
                    ?>
                    
                    <div class="order-result p-4 bg-white rounded-4 text-start border shadow-sm mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="text-secondary small d-block text-uppercase fw-bold" style="font-size: 10px;">Order ID</span>
                                <span class="fw-bold text-primary"><?php echo $order['ma_don']; ?></span>
                            </div>
                            <span class="badge <?php echo $badgeClass; ?> rounded-pill px-3 py-2 text-uppercase" style="font-size: 11px;">
                                <?php echo $status; ?>
                            </span>
                        </div>
                        <hr class="my-3 opacity-50">
                        <div class="row">
                            <div class="col-6">
                                <p class="small mb-1 text-secondary text-uppercase fw-bold" style="font-size: 10px;">Order Date</p>
                                <p class="fw-medium mb-0"><?php echo date('M d, Y - H:i', strtotime($order['ngay_dat'])); ?></p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="small mb-1 text-secondary text-uppercase fw-bold" style="font-size: 10px;">Total Amount</p>
                                <p class="fw-bold text-danger mb-0" style="font-size: 1.1rem;">
                                    <?php echo number_format($order['tong_tien_hang'], 0, ',', '.'); ?> ₫
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php
                } else {
                    echo '<div class="alert alert-dark rounded-4 mt-4">Order not found: <b>' . htmlspecialchars($order_input) . '</b></div>';
                }
            }
            ?>
        </div>
    </div>
</div>