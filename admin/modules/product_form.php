<?php
// --- PRODUCT FORM (Add / Edit) ---
$is_edit = isset($_GET['id']);
$product = null;
$variants = array();

if ($is_edit) {
    $pid = (int)$_GET['id'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM PRODUCTS WHERE id = $pid"));
    if (!$product) {
        echo '<div class="alert alert-danger">Product not found.</div>';
        return;
    }
    $res_v = mysqli_query($conn, "SELECT * FROM PRODUCT_VARIANTS WHERE product_id = $pid ORDER BY id ASC");
    while ($v = mysqli_fetch_assoc($res_v)) {
        $variants[] = $v;
    }
}

$categories = mysqli_query($conn, "SELECT * FROM CATEGORIES ORDER BY ten ASC");
$brands = mysqli_query($conn, "SELECT * FROM BRANDS ORDER BY ten ASC");
?>

<form action="modules/product_action.php" method="POST" enctype="multipart/form-data" class="admin-form">
    <input type="hidden" name="action" value="<?php echo $is_edit ? 'update' : 'create'; ?>">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <?php endif; ?>

    <div class="row g-4">
        <!-- LEFT: Product Info -->
        <div class="col-lg-8">
            <div class="form-card">
                <h4 class="form-card-title">Product Information</h4>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="ten" class="form-control" required
                               value="<?php echo $is_edit ? htmlspecialchars($product['ten']) : ''; ?>"
                               onkeyup="generateSlug(this, 'slug-input')">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug-input" class="form-control"
                               value="<?php echo $is_edit ? htmlspecialchars($product['slug']) : ''; ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="mo_ta" class="form-control" rows="4"><?php echo $is_edit ? htmlspecialchars($product['mo_ta']) : ''; ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Variants -->
            <div class="form-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="form-card-title mb-0 pb-0 border-0">Product Variants</h4>
                    <button type="button" class="btn-admin btn-admin-sm" onclick="addVariantRow()">
                        <i class="fa-solid fa-plus"></i> Add Variant
                    </button>
                </div>

                <div id="variant-container">
                    <?php if (count($variants) > 0): ?>
                        <?php foreach ($variants as $i => $v): ?>
                        <div class="variant-row" id="variant-row-<?php echo $i; ?>">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="small text-muted">Variant #<?php echo $i + 1; ?></strong>
                                <button type="button" class="btn btn-admin-danger btn-admin-sm" 
                                        onclick="removeVariantRow(<?php echo $i; ?>)">
                                    <i class="fa-solid fa-trash"></i> Remove
                                </button>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-sm-4">
                                    <label class="form-label">SKU</label>
                                    <input type="text" name="variant_sku[]" class="form-control form-control-sm" 
                                           value="<?php echo htmlspecialchars($v['sku']); ?>" placeholder="e.g. W60-ANSI">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Variant Name *</label>
                                    <input type="text" name="variant_name[]" class="form-control form-control-sm" required
                                           value="<?php echo htmlspecialchars($v['ten_bien_the']); ?>" placeholder="e.g. ANSI Layout">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Attribute 1</label>
                                    <input type="text" name="variant_attr1[]" class="form-control form-control-sm"
                                           value="<?php echo htmlspecialchars($v['thuoc_tinh_1']); ?>" placeholder="e.g. Color">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Attribute 2</label>
                                    <input type="text" name="variant_attr2[]" class="form-control form-control-sm"
                                           value="<?php echo htmlspecialchars($v['thuoc_tinh_2']); ?>" placeholder="e.g. Black">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-sm-3">
                                    <label class="form-label">Sell Price (₫) *</label>
                                    <input type="number" name="variant_price[]" class="form-control form-control-sm" required
                                           value="<?php echo $v['gia_ban']; ?>">
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label">Original Price (₫)</label>
                                    <input type="number" name="variant_price_orig[]" class="form-control form-control-sm"
                                           value="<?php echo $v['gia_goc']; ?>">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label">Stock</label>
                                    <input type="number" name="variant_stock[]" class="form-control form-control-sm"
                                           value="<?php echo $v['so_luong_ton']; ?>">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Image</label>
                                    <input type="file" name="variant_image_new[]" class="form-control form-control-sm" accept="image/*">
                                    <?php if ($v['hinh_anh_bien_the']): ?>
                                        <small class="text-muted d-block mt-1"><i class="fa-solid fa-image me-1"></i><?php echo $v['hinh_anh_bien_the']; ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <input type="hidden" name="variant_id[]" value="<?php echo $v['id']; ?>">
                            <input type="hidden" name="variant_image_old[]" value="<?php echo $v['hinh_anh_bien_the']; ?>">
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (count($variants) == 0 && !$is_edit): ?>
                <p class="text-muted small mt-2 mb-0">
                    <i class="fa-solid fa-info-circle me-1"></i> 
                    Click "Add Variant" to add product variants (color, layout, etc.)
                </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT: Meta -->
        <div class="col-lg-4">
            <div class="form-card">
                <h4 class="form-card-title">Classification</h4>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select --</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $cat['id']; ?>" 
                            <?php echo ($is_edit && $product['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo $cat['ten']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select">
                        <option value="">-- Select --</option>
                        <?php while ($br = mysqli_fetch_assoc($brands)): ?>
                        <option value="<?php echo $br['id']; ?>"
                            <?php echo ($is_edit && $product['brand_id'] == $br['id']) ? 'selected' : ''; ?>>
                            <?php echo $br['ten']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Product Type *</label>
                    <select name="loai_san_pham" class="form-select" required>
                        <?php 
                        $types = array('keyboards', 'modules', 'switches', 'keycaps', 'cases');
                        foreach ($types as $t):
                        ?>
                        <option value="<?php echo $t; ?>" 
                            <?php echo ($is_edit && $product['loai_san_pham'] == $t) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($t); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Layout</label>
                    <input type="text" name="layout" class="form-control" placeholder="e.g. 60%, 75%, TKL..."
                           value="<?php echo $is_edit ? htmlspecialchars($product['layout']) : ''; ?>">
                </div>
                <div class="mb-0">
                    <label class="form-label">Connectivity</label>
                    <input type="text" name="ket_noi" class="form-control" placeholder="e.g. Wired USB-C..."
                           value="<?php echo $is_edit ? htmlspecialchars($product['ket_noi']) : ''; ?>">
                </div>
            </div>

            <div class="form-card">
                <h4 class="form-card-title">Pricing & Image</h4>
                <div class="mb-3">
                    <label class="form-label">Display Price (₫) *</label>
                    <input type="number" name="gia_hien_thi" class="form-control" required
                           value="<?php echo $is_edit ? $product['gia_hien_thi'] : ''; ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Main Image</label>
                    <input type="file" name="anh_dai_dien" class="form-control" accept="image/*"
                           onchange="previewImage(this, 'main-img-preview')">
                </div>
                <div class="img-preview-box mb-2" id="main-img-preview">
                    <?php if ($is_edit && $product['anh_dai_dien']): ?>
                        <img src="../public/products/<?php echo $product['anh_dai_dien']; ?>">
                    <?php else: ?>
                        <i class="fa-solid fa-image text-muted" style="font-size: 32px; opacity: 0.3;"></i>
                    <?php endif; ?>
                </div>
                <?php if ($is_edit && $product['anh_dai_dien']): ?>
                    <small class="text-muted"><?php echo $product['anh_dai_dien']; ?></small>
                    <input type="hidden" name="anh_dai_dien_old" value="<?php echo $product['anh_dai_dien']; ?>">
                <?php endif; ?>
            </div>

            <!-- Submit Buttons -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn-admin" style="justify-content: center; padding: 12px;">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <?php echo $is_edit ? 'Update Product' : 'Create Product'; ?>
                </button>
                <a href="index.php?page=products" class="btn-admin btn-admin-outline" style="justify-content: center; padding: 12px;">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
