<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'make_admin' && $id > 0) {
    mysqli_query($conn, "UPDATE USERS SET vai_tro = 'admin' WHERE id = $id");
    $_SESSION['flash'] = array('type' => 'success', 'message' => 'User promoted to admin!');
    header('Location: ../index.php?page=users');
    exit();
}

if ($action === 'make_customer' && $id > 0) {
    // Prevent removing own admin role
    if ($id == $_SESSION['user_id']) {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'You cannot remove your own admin role!');
    } else {
        mysqli_query($conn, "UPDATE USERS SET vai_tro = 'khach' WHERE id = $id");
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'User demoted to customer.');
    }
    header('Location: ../index.php?page=users');
    exit();
}

header('Location: ../index.php?page=users');
exit();
?>
