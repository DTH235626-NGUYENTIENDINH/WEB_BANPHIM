<?php
$id = $_GET['id']; // Lấy ID từ URL
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
            <img src="img/<?php echo $product['anh_dai_dien']; ?>" class="img-fluid rounded shadow-sm">
        </div>

        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?page=products">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $product['ten']; ?>
                    </li>
                </ol>
            </nav>

            <h1 class="fw-bold"><?php echo $product['ten']; ?></h1>
            <p class="text-muted">Brand: <?php echo $product['ten_thuong_hieu']; ?></p>
            <h3 class="text-danger fw-bold mb-4"><?php echo number_format($product['gia_hien_thi'], 0, ',', '.'); ?> ₫
            </h3>

            <form action="handles/handle_cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">

                <div class="product-options mb-4">
                    <?php if (mysqli_num_rows($res_variants) > 0): ?>
                        <label class="fw-bold mb-2 text-uppercase small">Select Version / Switch:</label>
                        <select name="variant_id" class="form-select border-2 py-2" required>
                            <option value="">-- Choose an option --</option>
                            <?php
                            // Reset con trỏ dữ liệu nếu đã dùng ở đâu đó phía trên
                            mysqli_data_seek($res_variants, 0);
                            while ($variant = mysqli_fetch_assoc($res_variants)):
                                ?>
                                <option value="<?php echo $variant['id']; ?>">
                                    <?php echo $variant['ten_bien_the']; ?>
                                    (<?php echo number_format($variant['gia_ban'], 0, ',', '.'); ?> ₫)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    <?php else: ?>
                        <p class="text-muted small italic">Standard Edition</p>
                        <input type="hidden" name="variant_id" value="0">
                    <?php endif; ?>
                </div>

                <div class="d-grid">
                    <button type="submit" name="add_to_cart" class="btn btn-dark btn-lg py-3 rounded-pill fw-bold">
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