<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'update_status' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['trang_thai_don']);

    if (mysqli_query($conn, "UPDATE ORDERS SET trang_thai_don = '$status' WHERE id = $id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => "Order status updated to: $status");
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header("Location: ../index.php?page=order_detail&id=$id");
    exit();
}

header('Location: ../index.php?page=orders');
exit();
?>
