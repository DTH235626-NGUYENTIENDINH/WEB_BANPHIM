<?php
session_start();
include '../../src/DB/db_connect.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $thu_tu = (int)$_POST['thu_tu'];

    if (mysqli_query($conn, "INSERT INTO CATEGORIES (ten, slug, thu_tu) VALUES ('$ten', '$slug', '$thu_tu')")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Category created!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=categories');
    exit();
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $ten = mysqli_real_escape_string($conn, $_POST['ten']);
    $slug = mysqli_real_escape_string($conn, $_POST['slug']);
    $thu_tu = (int)$_POST['thu_tu'];

    if (mysqli_query($conn, "UPDATE CATEGORIES SET ten='$ten', slug='$slug', thu_tu='$thu_tu' WHERE id=$id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Category updated!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Error: ' . mysqli_error($conn));
    }
    header('Location: ../index.php?page=categories');
    exit();
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (mysqli_query($conn, "DELETE FROM CATEGORIES WHERE id = $id")) {
        $_SESSION['flash'] = array('type' => 'success', 'message' => 'Category deleted!');
    } else {
        $_SESSION['flash'] = array('type' => 'error', 'message' => 'Cannot delete: products may be linked.');
    }
    header('Location: ../index.php?page=categories');
    exit();
}

header('Location: ../index.php?page=categories');
exit();
?>
