<?php
session_start();
include '../src/DB/db_connect.php';

// --- AUTH CHECK: Only allow admin users ---
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Check from DB if session doesn't have role
    if (isset($_SESSION['user_id'])) {
        $uid = (int)$_SESSION['user_id'];
        $check = mysqli_query($conn, "SELECT vai_tro FROM USERS WHERE id = $uid");
        $u = mysqli_fetch_assoc($check);
        if ($u && $u['vai_tro'] === 'admin') {
            $_SESSION['role'] = 'admin';
        } else {
            header('Location: ../src/index.php');
            exit();
        }
    } else {
        header('Location: ../src/index.php?page=login');
        exit();
    }
}

// --- ROUTING ---
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$page_title = 'Dashboard';

$menu = array(
    array('page' => 'dashboard',   'title' => 'Dashboard',   'icon' => 'fa-solid fa-chart-pie'),
    array('page' => 'products',    'title' => 'Products',    'icon' => 'fa-solid fa-keyboard'),
    array('page' => 'categories',  'title' => 'Categories',  'icon' => 'fa-solid fa-layer-group'),
    array('page' => 'brands',      'title' => 'Brands',      'icon' => 'fa-solid fa-tag'),
    array('page' => 'orders',      'title' => 'Orders',      'icon' => 'fa-solid fa-truck'),
    array('page' => 'users',       'title' => 'Users',       'icon' => 'fa-solid fa-users'),
    array('page' => 'coupons',     'title' => 'Coupons',     'icon' => 'fa-solid fa-ticket')
);

// Find current page title
foreach ($menu as $m) {
    if ($m['page'] === $page) {
        $page_title = $m['title'];
        break;
    }
}
if ($page === 'product_form') $page_title = isset($_GET['id']) ? 'Edit Product' : 'Add Product';
if ($page === 'order_detail') $page_title = 'Order Detail';

// Flash message helper
function setFlash($type, $message) {
    $_SESSION['flash'] = array('type' => $type, 'message' => $message);
}
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="CSS/admin.css">
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar">
    <a href="index.php" class="sidebar-brand">
        <div class="sidebar-brand-icon">R</div>
        <div class="sidebar-brand-text">
            RABU Keyboard
            <span>Admin Panel</span>
        </div>
    </a>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-title">Main Menu</li>
        <?php foreach ($menu as $m): ?>
        <li class="sidebar-nav-item">
            <a href="index.php?page=<?php echo $m['page']; ?>" 
               class="sidebar-nav-link <?php echo ($page === $m['page']) ? 'active' : ''; ?>">
                <i class="<?php echo $m['icon']; ?>"></i>
                <?php echo $m['title']; ?>
            </a>
        </li>
        <?php endforeach; ?>

        <li class="sidebar-nav-title">Quick Links</li>
        <li class="sidebar-nav-item">
            <a href="../src/index.php" target="_blank" class="sidebar-nav-link">
                <i class="fa-solid fa-store"></i>
                View Store
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="../src/handles/logout_action.php" class="sidebar-nav-link">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </li>
    </ul>
</aside>

<!-- MAIN -->
<div class="admin-main">
    <!-- Header -->
    <header class="admin-header">
        <h1 class="admin-header-title"><?php echo $page_title; ?></h1>
        <div class="admin-header-actions">
            <span class="text-muted" style="font-size: 13px;">
                <i class="fa-regular fa-circle-user me-1"></i>
                <?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin'; ?>
            </span>
        </div>
    </header>

    <!-- Content -->
    <div class="admin-content">
        <?php
        // Flash messages
        $flash = getFlash();
        if ($flash):
        ?>
        <div class="admin-alert alert alert-<?php echo $flash['type'] === 'success' ? 'success' : 'danger'; ?>">
            <i class="fa-solid <?php echo $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'; ?>"></i>
            <?php echo $flash['message']; ?>
        </div>
        <?php endif; ?>

        <?php
        // Route to modules
        switch ($page) {
            case 'dashboard':
                include 'modules/dashboard.php';
                break;
            case 'products':
                include 'modules/products.php';
                break;
            case 'product_form':
                include 'modules/product_form.php';
                break;
            case 'categories':
                include 'modules/categories.php';
                break;
            case 'brands':
                include 'modules/brands.php';
                break;
            case 'orders':
                include 'modules/orders.php';
                break;
            case 'order_detail':
                include 'modules/order_detail.php';
                break;
            case 'users':
                include 'modules/users.php';
                break;
            case 'coupons':
                include 'modules/coupons.php';
                break;
            default:
                include 'modules/dashboard.php';
                break;
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="Script/admin.js"></script>
</body>
</html>
