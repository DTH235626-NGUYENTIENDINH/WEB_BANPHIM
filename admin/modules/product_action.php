<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
$upload_dir = '../../public/products/';

// --- Helper: Upload image ---
function uploadImage($file_input, $upload_dir) {
    if (isset($file_input) && $file_input['error'] == 0) {
        $ext = pathinfo($file_input['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . mt_rand(1000,9999) . '.' . $ext;
        move_uploaded_file($file_input['tmp_name'], $upload_dir . $filename);
        return $filename;
    }
    return '';
}

// ==========================================
// CREATE PRODUCT
// ==========================================
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $gia = (int)$_POST['gia_hien_thi'];
    $cat_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 'NULL';
    $brand_id = !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : 'NULL';
    $loai = mysqli_real_escape_string($conn, $_POST['loai_san_pham']);
    $layout = mysqli_real_escape_string($conn, $_POST['layout']);
    $ket_noi = mysqli_real_escape_string($conn, $_POST['ket_noi']);

    // Upload main image
    $anh = '';
    if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
        $anh = uploadImage($_FILES['anh_dai_dien'], $upload_dir);
    }

    $cat_sql = ($cat_id === 'NULL') ? 'NULL' : "'$cat_id'";
    $brand_sql = ($brand_id === 'NULL') ? 'NULL' : "'$brand_id'";

    $sql = "INSERT INTO PRODUCTS (ten, slug, mo_ta, anh_dai_dien, gia_hien_thi, category_id, brand_id, loai_san_pham, layout, ket_noi)
            VALUES ('$ten', '$slug', '$mo_ta', '$anh', '$gia', $cat_sql, $brand_sql, '$loai', '$layout', '$ket_noi')";

    if (mysqli_query($conn, $sql)) {
        $new_id = mysqli_insert_id($conn);

        // Save variants
        if (isset($_POST['variant_name'])) {
            $count = count($_POST['variant_name']);
            for ($i = 0; $i < $count; $i++) {
                $v_sku = mysqli_real_escape_string($conn, $_POST['variant_sku'][$i]);
                $v_name = mysqli_real_escape_string($conn, $_POST['variant_name'][$i]);
                $v_attr1 = mysqli_real_escape_string($conn, $_POST['variant_attr1'][$i]);
                $v_attr2 = mysqli_real_escape_string($conn, $_POST['variant_attr2'][$i]);
                $v_price = (int)$_POST['variant_price'][$i];
                $v_orig = !empty($_POST['variant_price_orig'][$i]) ? (int)$_POST['variant_price_orig'][$i] : 'NULL';
                $v_stock = (int)$_POST['variant_stock'][$i];

                $v_img = '';
                if (isset($_FILES['variant_image_new']['name'][$i]) && $_FILES['variant_image_new']['error'][$i] == 0) {
                    $file = array(
                        'name' => $_FILES['variant_image_new']['name'][$i],
                        'type' => $_FILES['variant_image_new']['type'][$i],
                        'tmp_name' => $_FILES['variant_image_new']['tmp_name'][$i],
                        'error' => $_FILES['variant_image_new']['error'][$i],
                        'size' => $_FILES['variant_image_new']['size'][$i]
                    );
                    $v_img = uploadImage($file, $upload_dir);
                }

                $v_orig_sql = ($v_orig === 'NULL') ? 'NULL' : "'$v_orig'";
                $v_sku_sql = !empty($v_sku) ? "'$v_sku'" : "NULL";

                $sql_v = "INSERT INTO PRODUCT_VARIANTS (product_id, sku, ten_bien_the, thuoc_tinh_1, thuoc_tinh_2, gia_ban, gia_goc, so_luong_ton, hinh_anh_bien_the)
                          VALUES ($new_id, $v_sku_sql, '$v_name', '$v_attr1', '$v_attr2', '$v_price', $v_orig_sql, '$v_stock', '$v_img')";
                mysqli_query($conn, $sql_v);
            }
        }

        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Product created successfully!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=products');
    exit();
}

// ==========================================
// UPDATE PRODUCT
// ==========================================
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);
    $gia = (int)$_POST['gia_hien_thi'];
    $cat_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : 'NULL';
    $brand_id = !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : 'NULL';
    $loai = mysqli_real_escape_string($conn, $_POST['loai_san_pham']);
    $layout = mysqli_real_escape_string($conn, $_POST['layout']);
    $ket_noi = mysqli_real_escape_string($conn, $_POST['ket_noi']);

    // Upload main image
    $anh = isset($_POST['anh_dai_dien_old']) ? $_POST['anh_dai_dien_old'] : '';
    if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] == 0) {
        $anh = uploadImage($_FILES['anh_dai_dien'], $upload_dir);
    }

    $cat_sql = ($cat_id === 'NULL') ? 'NULL' : "'$cat_id'";
    $brand_sql = ($brand_id === 'NULL') ? 'NULL' : "'$brand_id'";

    $sql = "UPDATE PRODUCTS SET 
            ten='$ten', slug='$slug', mo_ta='$mo_ta', anh_dai_dien='$anh', gia_hien_thi='$gia',
            category_id=$cat_sql, brand_id=$brand_sql, loai_san_pham='$loai', layout='$layout', ket_noi='$ket_noi'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        // Handle variants
        // Collect existing variant IDs from form
        $submitted_ids = array();
        if (isset($_POST['variant_id'])) {
            foreach ($_POST['variant_id'] as $vid) {
                if ($vid !== 'new') $submitted_ids[] = (int)$vid;
            }
        }

        // Delete variants that were removed from form
        $existing = mysqli_query($conn, "SELECT id FROM PRODUCT_VARIANTS WHERE product_id = $id");
        while ($ex = mysqli_fetch_assoc($existing)) {
            if (!in_array((int)$ex['id'], $submitted_ids)) {
                mysqli_query($conn, "DELETE FROM PRODUCT_VARIANTS WHERE id = " . $ex['id']);
            }
        }

        // Update/Insert variants
        if (isset($_POST['variant_name'])) {
            $count = count($_POST['variant_name']);
            for ($i = 0; $i < $count; $i++) {
                $v_id = $_POST['variant_id'][$i];
                $v_sku = mysqli_real_escape_string($conn, $_POST['variant_sku'][$i]);
                $v_name = mysqli_real_escape_string($conn, $_POST['variant_name'][$i]);
                $v_attr1 = mysqli_real_escape_string($conn, $_POST['variant_attr1'][$i]);
                $v_attr2 = mysqli_real_escape_string($conn, $_POST['variant_attr2'][$i]);
                $v_price = (int)$_POST['variant_price'][$i];
                $v_orig = !empty($_POST['variant_price_orig'][$i]) ? (int)$_POST['variant_price_orig'][$i] : 'NULL';
                $v_stock = (int)$_POST['variant_stock'][$i];

                // Handle variant image
                $v_img = isset($_POST['variant_image_old'][$i]) ? $_POST['variant_image_old'][$i] : '';
                if (isset($_FILES['variant_image_new']['name'][$i]) && $_FILES['variant_image_new']['error'][$i] == 0) {
                    $file = array(
                        'name' => $_FILES['variant_image_new']['name'][$i],
                        'type' => $_FILES['variant_image_new']['type'][$i],
                        'tmp_name' => $_FILES['variant_image_new']['tmp_name'][$i],
                        'error' => $_FILES['variant_image_new']['error'][$i],
                        'size' => $_FILES['variant_image_new']['size'][$i]
                    );
                    $v_img = uploadImage($file, $upload_dir);
                }

                $v_orig_sql = ($v_orig === 'NULL') ? 'NULL' : "'$v_orig'";
                $v_sku_sql = !empty($v_sku) ? "'$v_sku'" : "NULL";

                if ($v_id === 'new') {
                    // Insert new variant
                    $sql_v = "INSERT INTO PRODUCT_VARIANTS (product_id, sku, ten_bien_the, thuoc_tinh_1, thuoc_tinh_2, gia_ban, gia_goc, so_luong_ton, hinh_anh_bien_the)
                              VALUES ($id, $v_sku_sql, '$v_name', '$v_attr1', '$v_attr2', '$v_price', $v_orig_sql, '$v_stock', '$v_img')";
                } else {
                    // Update existing variant
                    $sql_v = "UPDATE PRODUCT_VARIANTS SET 
                              sku=$v_sku_sql, ten_bien_the='$v_name', thuoc_tinh_1='$v_attr1', thuoc_tinh_2='$v_attr2',
                              gia_ban='$v_price', gia_goc=$v_orig_sql, so_luong_ton='$v_stock', hinh_anh_bien_the='$v_img'
                              WHERE id=$v_id";
                }
                mysqli_query($conn, $sql_v);
            }
        }

        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Product updated successfully!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=products');
    exit();
}

// ==========================================
// SOFT DELETE
// ==========================================
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "UPDATE PRODUCTS SET deleted_at = NOW() WHERE id = $id");
    $_SESSION['flash'] = array('type' => 'success', 'message' => 'Product moved to trash.');
    header('Location: ../index.php?page=products');
    exit();
}

// ==========================================
// RESTORE
// ==========================================
if ($action === 'restore' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($conn, "UPDATE PRODUCTS SET deleted_at = NULL WHERE id = $id");
    $_SESSION['flash'] = array('type' => 'success', 'message' => 'Product restored.');
    header('Location: ../index.php?page=products&deleted=1');
    exit();
}

header('Location: ../index.php?page=products');
exit();
?>
