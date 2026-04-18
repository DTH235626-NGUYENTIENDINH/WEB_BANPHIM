<?php
// --- BRANDS ---
$brands = mysqli_query($conn, "SELECT b.*, (SELECT COUNT(*) FROM PRODUCTS WHERE brand_id = b.id AND deleted_at IS NULL) AS product_count 
                                FROM BRANDS b ORDER BY b.ten ASC");

$edit_brand = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_brand = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM BRANDS WHERE id = $edit_id"));
}
?>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-4">
        <div class="form-card">
            <h4 class="form-card-title"><?php echo $edit_brand ? 'Edit Brand' : 'Add Brand'; ?></h4>
            <form action="modules/brand_action.php" method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?php echo $edit_brand ? 'update' : 'create'; ?>">
                <?php if ($edit_brand): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_brand['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Brand Name *</label>
                    <input type="text" name="ten" class="form-control" required
                           value="<?php echo $edit_brand ? htmlspecialchars($edit_brand['ten']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Logo Filename</label>
                    <input type="text" name="logo" class="form-control" placeholder="e.g. brand_logo.png"
                           value="<?php echo $edit_brand ? htmlspecialchars($edit_brand['logo']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="mo_ta" class="form-control" rows="3"><?php echo $edit_brand ? htmlspecialchars($edit_brand['mo_ta']) : ''; ?></textarea>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn-admin" style="justify-content: center;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?php echo $edit_brand ? 'Update' : 'Create'; ?>
                    </button>
                    <?php if ($edit_brand): ?>
                    <a href="index.php?page=brands" class="btn-admin btn-admin-outline" style="justify-content: center;">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="col-lg-8">
        <div class="data-table-wrapper">
            <div class="data-table-header">
                <h3 class="data-table-title">All Brands (<?php echo mysqli_num_rows($brands); ?>)</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($brands) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($brands)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['ten']); ?></strong>
                                <?php if ($row['mo_ta']): ?>
                                    <br><small class="text-muted"><?php echo mb_substr($row['mo_ta'], 0, 60) . '...'; ?></small>
                                <?php endif; ?>
                            </td>
                            <td><code><?php echo $row['logo'] ? $row['logo'] : '-'; ?></code></td>
                            <td><span class="badge bg-secondary rounded-pill"><?php echo $row['product_count']; ?></span></td>
                            <td>
                                <a href="index.php?page=brands&edit=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-outline"><i class="fa-solid fa-pen"></i></a>
                                <a href="modules/brand_action.php?action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-danger"
                                   onclick="return confirmDelete('Delete this brand?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5"><div class="empty-state"><i class="fa-solid fa-tag d-block"></i><p>No brands</p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
