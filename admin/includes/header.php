<?php
/**
 * Header riêng cho khu vực quản trị
 */

// Kiểm tra đăng nhập và quyền admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: /login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - ShopElectrics</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .sidebar {
            min-height: calc(100vh - 56px);
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }
        .sidebar .nav-link {
            color: #333;
            border-radius: 0.25rem;
            margin: 0.25rem 0.5rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: #e9ecef;
            color: #0d6efd;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        .main-content {
            padding: 20px;
        }
        .admin-header {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/admin">
                <i class="bi bi-speedometer2 me-2"></i>Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile.php"><i class="bi bi-person me-2"></i>Trang cá nhân</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="/admin">
                                <i class="bi bi-speedometer2"></i> Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'products') !== false ? 'active' : '' ?>" href="/admin/products">
                                <i class="bi bi-box-seam"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'categories') !== false ? 'active' : '' ?>" href="/admin/categories">
                                <i class="bi bi-tags"></i> Danh mục
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'orders') !== false ? 'active' : '' ?>" href="/admin/orders">
                                <i class="bi bi-cart-check"></i> Đơn hàng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'users') !== false ? 'active' : '' ?>" href="/admin/users">
                                <i class="bi bi-people"></i> Người dùng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/" target="_blank">
                                <i class="bi bi-house-door"></i> Về trang chủ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="admin-header">
                    <h2><?= $page_title ?? 'Quản trị' ?></h2>
                </div>
