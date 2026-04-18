<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(mysqli_real_escape_string($conn, $_POST['code']));
    $loai = mysqli_real_escape_string($conn, $_POST['loai_giam']);
    $gia_tri = (int)$_POST['gia_tri'];
    $min = (int)$_POST['don_hang_toi_thieu'];
    $expiry = !empty($_POST['ngay_het_han']) ? "'" . mysqli_real_escape_string($conn, $_POST['ngay_het_han']) . "'" : "NULL";
    $status = (int)$_POST['trang_thai'];

    $sql = "INSERT INTO COUPONS (code, loai_giam, gia_tri, don_hang_toi_thieu, ngay_het_han, trang_thai)
            VALUES ('$code', '$loai', '$gia_tri', '$min', $expiry, '$status')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Coupon created!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=coupons');
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $code = strtoupper(mysqli_real_escape_string($conn, $_POST['code']));
    $loai = mysqli_real_escape_string($conn, $_POST['loai_giam']);
    $gia_tri = (int)$_POST['gia_tri'];
    $min = (int)$_POST['don_hang_toi_thieu'];
    $expiry = !empty($_POST['ngay_het_han']) ? "'" . mysqli_real_escape_string($conn, $_POST['ngay_het_han']) . "'" : "NULL";
    $status = (int)$_POST['trang_thai'];

    $sql = "UPDATE COUPONS SET code='$code', loai_giam='$loai', gia_tri='$gia_tri', 
            don_hang_toi_thieu='$min', ngay_het_han=$expiry, trang_thai='$status' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Coupon updated!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=coupons');
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (mysqli_query($conn, "DELETE FROM COUPONS WHERE id = $id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Coupon deleted!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=coupons');
    exit();
}

header('Location: ../index.php?page=coupons');
exit();
?>
