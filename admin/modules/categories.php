<?php
// --- CATEGORIES ---
$categories = mysqli_query($conn, "SELECT c.*, (SELECT COUNT(*) FROM PRODUCTS WHERE category_id = c.id AND deleted_at IS NULL) AS product_count 
                                   FROM CATEGORIES c ORDER BY c.thu_tu ASC, c.id ASC");

$edit_cat = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_cat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM CATEGORIES WHERE id = $edit_id"));
}
?>

<div class="row g-4">
    <!-- Form -->
    <div class="col-lg-4">
        <div class="form-card">
            <h4 class="form-card-title"><?php echo $edit_cat ? 'Edit Category' : 'Add Category'; ?></h4>
            <form action="modules/category_action.php" method="POST" class="admin-form">
                <input type="hidden" name="action" value="<?php echo $edit_cat ? 'update' : 'create'; ?>">
                <?php if ($edit_cat): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_cat['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="ten" class="form-control" required
                           value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['ten']) : ''; ?>"
                           onkeyup="generateSlug(this, 'cat-slug')">
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug *</label>
                    <input type="text" name="slug" id="cat-slug" class="form-control" required
                           value="<?php echo $edit_cat ? htmlspecialchars($edit_cat['slug']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="thu_tu" class="form-control" value="<?php echo $edit_cat ? $edit_cat['thu_tu'] : 0; ?>">
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn-admin" style="justify-content: center;">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <?php echo $edit_cat ? 'Update' : 'Create'; ?>
                    </button>
                    <?php if ($edit_cat): ?>
                    <a href="index.php?page=categories" class="btn-admin btn-admin-outline" style="justify-content: center;">Cancel</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- List -->
    <div class="col-lg-8">
        <div class="data-table-wrapper">
            <div class="data-table-header">
                <h3 class="data-table-title">All Categories (<?php echo mysqli_num_rows($categories); ?>)</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($categories) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['ten']); ?></strong></td>
                            <td><code><?php echo $row['slug']; ?></code></td>
                            <td><span class="badge bg-secondary rounded-pill"><?php echo $row['product_count']; ?></span></td>
                            <td><?php echo $row['thu_tu']; ?></td>
                            <td>
                                <a href="index.php?page=categories&edit=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-outline"><i class="fa-solid fa-pen"></i></a>
                                <a href="modules/category_action.php?action=delete&id=<?php echo $row['id']; ?>" 
                                   class="btn-admin btn-admin-sm btn-admin-danger"
                                   onclick="return confirmDelete('Delete this category?')"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6"><div class="empty-state"><i class="fa-solid fa-folder-open d-block"></i><p>No categories</p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
