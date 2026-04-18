<?php
// --- COUPONS ---
$coupons = mysqli_query($conn, "SELECT * FROM COUPONS ORDER BY id DESC");

$edit_coupon = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_coupon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM COUPONS WHERE id = $edit_id"));
}
?>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-5">
        <div class="form-card">
            <h4 class="form-card-title"><?php echo $edit_coupon ? 'Edit Coupon' : 'Add Coupon'; ?></h4>
            <form action="modules/coupon_action.php" method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?php echo $edit_coupon ? 'update' : 'create'; ?>">
                <?php if ($edit_coupon): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_coupon['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Coupon Code *</label>
                    <input type="text" name="code" class="form-control" required style="text-transform: uppercase;"
                           value="<?php echo $edit_coupon ? htmlspecialchars($edit_coupon['code']) : ''; ?>">
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Discount Type *</label>
                        <select name="loai_giam" class="form-select" required>
                            <option value="so_tien" <?php echo ($edit_coupon && $edit_coupon['loai_giam'] == 'so_tien') ? 'selected' : ''; ?>>
                                Fixed Amount (₫)
                            </option>
                            <option value="phan_tram" <?php echo ($edit_coupon && $edit_coupon['loai_giam'] == 'phan_tram') ? 'selected' : ''; ?>>
                                Percentage (%)
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Value *</label>
                        <input type="number" name="gia_tri" class="form-control" required
                               value="<?php echo $edit_coupon ? $edit_coupon['gia_tri'] : ''; ?>">
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Min Order Amount (₫)</label>
                    <input type="number" name="don_hang_toi_thieu" class="form-control"
                           value="<?php echo $edit_coupon ? $edit_coupon['don_hang_toi_thieu'] : 0; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="datetime-local" name="ngay_het_han" class="form-control"
                           value="<?php echo $edit_coupon ? date('Y-m-d\TH:i', strtotime($edit_coupon['ngay_het_han'])) : ''; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="trang_thai" class="form-select">
                        <option value="1" <?php echo ($edit_coupon && $edit_coupon['trang_thai']) ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo ($edit_coupon && !$edit_coupon['trang_thai']) ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn-admin" style="justify-content: center;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?php echo $edit_coupon ? 'Update' : 'Create'; ?>
                    </button>
                    <?php if ($edit_coupon): ?>
                    <a href="index.php?page=coupons" class="btn-admin btn-admin-outline" style="justify-content: center;">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="col-lg-7">
        <div class="data-table-wrapper">
            <div class="data-table-header">
                <h3 class="data-table-title">All Coupons (<?php echo mysqli_num_rows($coupons); ?>)</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Min Order</th>
                        <th>Expiry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($coupons) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($coupons)):
                            $is_expired = $row['ngay_het_han'] && strtotime($row['ngay_het_han']) < time();
                        ?>
                        <tr>
                            <td><strong style="letter-spacing: 1px;"><?php echo $row['code']; ?></strong></td>
                            <td>
                                <?php if ($row['loai_giam'] == 'phan_tram'): ?>
                                    <span class="badge bg-info text-white">Percentage</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Fixed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                if ($row['loai_giam'] == 'phan_tram') {
                                    echo $row['gia_tri'] . '%';
                                } else {
                                    echo number_format($row['gia_tri'], 0, ',', '.') . ' ₫';
                                }
                                ?>
                            </td>
                            <td><?php echo number_format($row['don_hang_toi_thieu'], 0, ',', '.'); ?> ₫</td>
                            <td>
                                <?php if ($row['ngay_het_han']): ?>
                                    <?php echo date('d/m/Y', strtotime($row['ngay_het_han'])); ?>
                                    <?php if ($is_expired): ?>
                                        <br><small class="text-danger">Expired</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">No expiry</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['trang_thai'] && !$is_expired): ?>
                                    <span class="badge-status badge-delivered">Active</span>
                                <?php else: ?>
                                    <span class="badge-status badge-cancelled">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=coupons&edit=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-outline"><i class="fa-solid fa-pen"></i></a>
                                <a href="modules/coupon_action.php?action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-danger"
                                   onclick="return confirmDelete('Delete this coupon?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7"><div class="empty-state"><i class="fa-solid fa-ticket d-block"></i><p>No coupons</p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
