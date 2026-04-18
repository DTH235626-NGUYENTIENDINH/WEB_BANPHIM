<?php
// --- PRODUCTS LIST ---
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$filter_cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$show_deleted = isset($_GET['deleted']) ? true : false;

$sql = "SELECT p.*, c.ten AS ten_danh_muc, b.ten AS ten_brand,
        (SELECT COUNT(*) FROM PRODUCT_VARIANTS WHERE product_id = p.id) AS variant_count
        FROM PRODUCTS p 
        LEFT JOIN CATEGORIES c ON p.category_id = c.id 
        LEFT JOIN BRANDS b ON p.brand_id = b.id";

if ($show_deleted) {
    $sql .= " WHERE p.deleted_at IS NOT NULL";
} else {
    $sql .= " WHERE p.deleted_at IS NULL";
}

if ($search != '') {
    $sql .= " AND p.ten LIKE '%$search%'";
}
if ($filter_cat > 0) {
    $sql .= " AND p.category_id = $filter_cat";
}

$sql .= " ORDER BY p.ngay_tao DESC";
$result = mysqli_query($conn, $sql);

$categories = mysqli_query($conn, "SELECT * FROM CATEGORIES ORDER BY ten ASC");
?>

<div class="data-table-wrapper">
    <div class="data-table-header">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <h3 class="data-table-title mb-0">
                <?php echo $show_deleted ? 'Deleted Products' : 'All Products'; ?>
                <span class="text-muted" style="font-size: 13px; font-weight: 400;">(<?php echo mysqli_num_rows($result); ?>)</span>
            </h3>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Search -->
            <form method="GET" class="d-flex gap-2 m-0">
                <input type="hidden" name="page" value="products">
                <?php if ($show_deleted): ?><input type="hidden" name="deleted" value="1"><?php endif; ?>
                <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" 
                       class="form-control form-control-sm" placeholder="Search..." style="width: 180px; border-radius: 6px;">
                <button type="submit" class="btn-admin btn-admin-sm"><i class="fa-solid fa-search"></i></button>
            </form>

            <!-- Filter Cat -->
            <form method="GET" class="m-0">
                <input type="hidden" name="page" value="products">
                <select name="cat" class="form-select form-select-sm" style="width: 160px; border-radius: 6px;" onchange="this.form.submit()">
                    <option value="0">All Categories</option>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $filter_cat == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo $cat['ten']; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <?php if ($show_deleted): ?>
                <a href="index.php?page=products" class="btn-admin btn-admin-sm btn-admin-outline">Active Products</a>
            <?php else: ?>
                <a href="index.php?page=products&deleted=1" class="btn-admin btn-admin-sm btn-admin-outline">
                    <i class="fa-solid fa-trash-can"></i> Trash
                </a>
            <?php endif; ?>

            <a href="index.php?page=product_form" class="btn-admin btn-admin-sm">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Variants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>
                        <img src="../public/products/<?php echo $row['anh_dai_dien'] ? $row['anh_dai_dien'] : 'default.png'; ?>" 
                             class="product-thumb">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($row['ten']); ?></strong>
                        <br><small class="text-muted"><?php echo $row['loai_san_pham']; ?></small>
                    </td>
                    <td><?php echo $row['ten_danh_muc'] ? $row['ten_danh_muc'] : '-'; ?></td>
                    <td><?php echo $row['ten_brand'] ? $row['ten_brand'] : '-'; ?></td>
                    <td><?php echo number_format($row['gia_hien_thi'], 0, ',', '.'); ?> ₫</td>
                    <td><span class="badge bg-secondary rounded-pill"><?php echo $row['variant_count']; ?></span></td>
                    <td>
                        <?php if ($show_deleted): ?>
                            <a href="modules/product_action.php?action=restore&id=<?php echo $row['id']; ?>" 
                               class="btn-admin btn-admin-sm btn-admin-success" title="Restore">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        <?php else: ?>
                            <a href="index.php?page=product_form&id=<?php echo $row['id']; ?>" 
                               class="btn-admin btn-admin-sm btn-admin-outline" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="modules/product_action.php?action=delete&id=<?php echo $row['id']; ?>" 
                               class="btn-admin btn-admin-sm btn-admin-danger" title="Delete"
                               onclick="return confirmDelete('Delete this product?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fa-solid fa-box-open d-block"></i>
                            <p>No products found</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
