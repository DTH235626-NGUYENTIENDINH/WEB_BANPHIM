<?php
$id = $_GET['id'];
$sql_product = "SELECT p.*, b.ten AS ten_thuong_hieu 
                FROM PRODUCTS p 
                LEFT JOIN BRANDS b ON p.brand_id = b.id 
                WHERE p.id = $id";
$res_product = mysqli_query($conn, $sql_product);
$product = mysqli_fetch_assoc($res_product);

$sql_variants = "SELECT * FROM PRODUCT_VARIANTS WHERE product_id = $id";
$res_variants = mysqli_query($conn, $sql_variants);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img id="product-main-img" src="../public/products/<?php echo $product['anh_dai_dien']; ?>"
                class="img-fluid rounded shadow-sm" style="transition: 0.3s; width: 400px; height: 400px; object-fit: contain; display: block; margin: 0 auto;">
            
            <?php
            // Collect all unique images for the gallery
            $gallery_images = array();
            $gallery_images[] = $product['anh_dai_dien']; // Main image

            mysqli_data_seek($res_variants, 0);
            while ($variant = mysqli_fetch_assoc($res_variants)) {
                if (!empty($variant['hinh_anh_bien_the']) && !in_array($variant['hinh_anh_bien_the'], $gallery_images)) {
                    $gallery_images[] = $variant['hinh_anh_bien_the'];
                }
            }
            // Reset pointer again for the select box
            mysqli_data_seek($res_variants, 0);
            
            if (count($gallery_images) > 1):
            ?>
            <div class="d-flex justify-content-center gap-2 mt-3 overflow-auto py-2 px-3">
                <?php foreach ($gallery_images as $index => $img): ?>
                    <img src="../public/products/<?php echo $img; ?>" 
                         class="img-thumbnail cursor-pointer gallery-thumb <?php echo $index === 0 ? 'border-dark border-2' : ''; ?>" 
                         style="width: 70px; height: 70px; object-fit: contain; cursor: pointer;"
                         onclick="changeMainImage('../public/products/<?php echo $img; ?>', this)">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=products">Products</a></li>
                    <li class="breadcrumb-item active"><?php echo $product['ten']; ?></li>
                </ol>
            </nav>

            <h1 class="fw-bold"><?php echo $product['ten']; ?></h1>
            <p class="text-muted">Brand: <?php echo $product['ten_thuong_hieu']; ?></p>

            <h3 class="text-danger fw-bold mb-4" id="main-price">
                <?php echo number_format($product['gia_hien_thi'], 0, ',', '.'); ?> ₫
            </h3>

            <form action="handles/handle_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">

                <div class="product-options mb-4">
                    <?php if (mysqli_num_rows($res_variants) > 0): ?>
                        <label class="fw-bold mb-2 text-uppercase small">Select Version:</label>
                        <select name="variant_id" id="variantSelect" class="form-select border-2 py-2" required
                            onchange="updateProductUI()">
                            <option value=""
                                data-price="<?php echo number_format($product['gia_hien_thi'], 0, ',', '.'); ?> ₫"
                                data-image="../public/products/<?php echo $product['anh_dai_dien']; ?>" data-stock="-1">
                                -- Choose an option --
                            </option>

                            <?php
                            mysqli_data_seek($res_variants, 0);
                            while ($variant = mysqli_fetch_assoc($res_variants)):
                                $stock = (int) $variant['so_luong_ton'];
                                $outOfStock = ($stock <= 0);
                                $v_img = !empty($variant['hinh_anh_bien_the']) ? $variant['hinh_anh_bien_the'] : $product['anh_dai_dien'];
                                ?>
                                <option value="<?php echo $variant['id']; ?>"
                                    data-price="<?php echo number_format($variant['gia_ban'], 0, ',', '.'); ?> ₫"
                                    data-image="../public/products/<?php echo $v_img; ?>" data-stock="<?php echo $stock; ?>"
                                    <?php echo $outOfStock ? 'disabled' : ''; ?>>
                                    <?php echo $variant['ten_bien_the']; ?>
                                    - <?php echo number_format($variant['gia_ban'], 0, ',', '.'); ?> ₫
                                    <?php echo $outOfStock ? '(Out of Stock)' : '(In Stock: ' . $stock . ')'; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    <?php else: ?>
                        <p class="text-muted small italic">Standard Edition</p>
                        <input type="hidden" name="variant_id" value="0">
                    <?php endif; ?>
                </div>

                <div id="stock-status" class="mb-3 small fw-bold"></div>

                <div class="d-grid">
                    <button type="submit" name="add_to_cart" id="addToCartBtn"
                        class="btn btn-dark btn-lg py-3 rounded-pill fw-bold">
                        ADD TO CART
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5 pt-5 border-top">
        <h4 class="fw-bold mb-3">Product Description</h4>
        <div class="content">
            <?php echo nl2br($product['mo_ta']); ?>
        </div>
    </div>
</div>

<script>
    function changeMainImage(src, element) {
        const mainImg = document.getElementById('product-main-img');
        mainImg.style.opacity = '0.4';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 100);

        // Update active state on thumbnails
        document.querySelectorAll('.gallery-thumb').forEach(thumb => {
            thumb.classList.remove('border-dark', 'border-2');
        });
        if (element) {
            element.classList.add('border-dark', 'border-2');
        }
    }

    function updateProductUI() {
        const select = document.getElementById('variantSelect');
        const selectedOption = select.options[select.selectedIndex];

        // 1. Lấy dữ liệu từ data attributes
        const price = selectedOption.getAttribute('data-price');
        const image = selectedOption.getAttribute('data-image');
        const stock = parseInt(selectedOption.getAttribute('data-stock'));

        // 2. Cập nhật Hình ảnh (Cập nhật src)
        if (image) {
            const mainImg = document.getElementById('product-main-img');
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = image;
                mainImg.style.opacity = '1';
            }, 100);
            
            // Sync active thumbnail
            document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                thumb.classList.remove('border-dark', 'border-2');
                // Use getAttribute('src') or includes to match the end of the URL
                if (thumb.getAttribute('src') === image) {
                    thumb.classList.add('border-dark', 'border-2');
                }
            });
        }

        // 3. Cập nhật Giá
        if (price) {
            document.getElementById('main-price').innerText = price;
        }

        // 4. Cập nhật trạng thái kho & Nút bấm
        const btn = document.getElementById('addToCartBtn');
        const statusDiv = document.getElementById('stock-status');

        if (stock === -1) {
            statusDiv.innerHTML = "";
            btn.disabled = false;
            btn.innerText = "ADD TO CART";
        } else if (stock <= 0) {
            statusDiv.innerHTML = '<span class="text-danger">Out of Stock - Choose another version</span>';
            btn.disabled = true;
            btn.innerText = "OUT OF STOCK";
        } else {
            statusDiv.innerHTML = '<span class="text-success">In Stock: ' + stock + ' units available</span>';
            btn.disabled = false;
            btn.innerText = "ADD TO CART";
        }
    }
</script>

<style>
    .btn-dark {
        background-color: #FFCC00 !important;
        color: #000 !important;
        border: none;
    }

    .btn-dark:hover {
        background-color: #000 !important;
        color: #FFCC00 !important;
    }

    .btn-dark:disabled {
        background-color: #ccc !important;
        color: #666 !important;
        border: none;
    }
</style>