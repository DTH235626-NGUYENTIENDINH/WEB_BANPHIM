<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $logo = mysqli_real_escape_string($conn, $_POST['logo']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

    if (mysqli_query($conn, "INSERT INTO BRANDS (ten, logo, mo_ta) VALUES ('$ten', '$logo', '$mo_ta')")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Brand created!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=brands');
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $logo = mysqli_real_escape_string($conn, $_POST['logo']);
    $mo_ta = mysqli_real_escape_string($conn, $_POST['mo_ta']);

    if (mysqli_query($conn, "UPDATE BRANDS SET ten='$ten', logo='$logo', mo_ta='$mo_ta' WHERE id=$id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Brand updated!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=brands');
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (mysqli_query($conn, "DELETE FROM BRANDS WHERE id = $id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Brand deleted!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Cannot delete: products may be linked.');
    }
    header('Location: ../index.php?page=brands');
    exit();
}

header('Location: ../index.php?page=brands');
exit();
?>
