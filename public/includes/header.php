<?php
/**
 * Header chung cho giao diện shop
 */
require_once __DIR__ . '/../../includes/init.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopElectrics - Cửa hàng điện tử hàng đầu Việt Nam</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .badge-notification {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            padding: 0.25rem 0.4rem;
        }
        
        .search-form {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .search-form .form-control {
            padding-right: 3rem;
            border-radius: 2rem;
            border: 1px solid #dee2e6;
            padding-left: 1.5rem;
        }
        
        .search-form .btn-search {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--secondary-color);
        }
        
        .cart-icon, .user-icon {
            position: relative;
            color: #333;
            font-size: 1.25rem;
            margin-left: 1rem;
        }
        
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 5rem 0;
            margin-bottom: 3rem;
            border-radius: 0.5rem;
        }
        
        .section-title {
            position: relative;
            margin-bottom: 2rem;
            padding-bottom: 0.5rem;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .product-card {
            border: none;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .product-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        
        .product-price {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
        }
        
        .old-price {
            text-decoration: line-through;
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin-left: 0.5rem;
        }
        
        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--danger-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .btn-add-to-cart {
            width: 100%;
            border-radius: 2rem;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        .footer h5 {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }
        
        .footer h5:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }
        
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .copyright {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 1rem 0;
            font-size: 0.9rem;
        }
        
        /* Responsive styles */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-top: 1rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            }
            
            .nav-item {
                margin-bottom: 0.5rem;
            }
            
            .nav-link {
                color: #333 !important;
                padding: 0.5rem 1rem;
                border-radius: 0.25rem;
            }
            
            .nav-link:hover, .nav-link.active {
                background-color: rgba(13, 110, 253, 0.1);
            }
            
            .dropdown-menu {
                border: 1px solid rgba(0, 0, 0, 0.1);
                margin-left: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="bg-dark text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope me-2"></i>
                        <span class="me-3">contact@shopelectrics.com</span>
                        <i class="bi bi-phone me-2"></i>
                        <span>0123 456 789</span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-flex align-items-center">
                        <a href="/track-order.php" class="text-white text-decoration-none me-3">
                            <i class="bi bi-truck me-1"></i> Theo dõi đơn hàng
                        </a>
                        <span class="text-white-50">|</span>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <div class="dropdown d-inline-block ms-3">
                                <a class="text-white text-decoration-none dropdown-toggle" href="#" role="button" 
                                   id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person me-1"></i> 
                                    <?= htmlspecialchars(($_SESSION['first_name'] ?? '') . ' ' . ($_SESSION['last_name'] ?? '')) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="/profile.php"><i class="bi bi-person me-2"></i> Tài khoản của tôi</a></li>
                                    <li><a class="dropdown-item" href="/orders.php"><i class="bi bi-bag me-2"></i> Đơn hàng</a></li>
                                    <li><a class="dropdown-item" href="/wishlist.php"><i class="bi bi-heart me-2"></i> Yêu thích</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="/controllers/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="/login.php" class="text-white text-decoration-none ms-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                            </a>
                            <span class="text-white-50 mx-1">/</span>
                            <a href="/register.php" class="text-white text-decoration-none">
                                Đăng ký
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/assets/images/logo.png" alt="ShopElectrics" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" 
                    aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="/index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Danh mục
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                            <?php
                            // Lấy danh sách danh mục từ database
                            $stmt = $pdo->query("SELECT id, name, slug FROM categories WHERE parent_id IS NULL AND status = 1 ORDER BY name");
                            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
                            
                            foreach ($categories as $category):
                                // Lấy danh mục con (nếu có)
                                $childStmt = $pdo->prepare("SELECT id, name, slug FROM categories WHERE parent_id = ? AND status = 1 ORDER BY name");
                                $childStmt->execute([$category->id]);
                                $childCategories = $childStmt->fetchAll(PDO::FETCH_OBJ);
                                
                                if (!empty($childCategories)): // Nếu có danh mục con
                            ?>
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="/category/<?= $category->slug ?>"><?= htmlspecialchars($category->name) ?></a>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($childCategories as $child): ?>
                                            <li><a class="dropdown-item" href="/category/<?= $child->slug ?>"><?= htmlspecialchars($child->name) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php else: // Nếu không có danh mục con ?>
                                <li><a class="dropdown-item" href="/category/<?= $category->slug ?>"><?= htmlspecialchars($category->name) ?></a></li>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products.php">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/promotions.php">Khuyến mãi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog.php">Tin tức</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact.php">Liên hệ</a>
                    </li>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/admin/" target="_blank">
                            <i class="bi bi-speedometer2 me-1"></i> Quản trị
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <div class="d-flex align-items-center">
                    <form class="search-form me-3 d-none d-md-block" action="/search.php" method="get">
                        <input type="text" class="form-control" name="q" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                        <button type="submit" class="btn btn-search">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    
                    <a href="/wishlist.php" class="position-relative me-3 text-dark" title="Yêu thích">
                        <i class="bi bi-heart"></i>
                        <span class="cart-count wishlist-count">0</span>
                    </a>
                    
                    <a href="/cart.php" class="position-relative text-dark" title="Giỏ hàng">
                        <i class="bi bi-cart3"></i>
                        <span class="cart-count cart-items-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Search Bar (Visible only on mobile) -->
    <div class="bg-light py-2 d-md-none">
        <div class="container">
            <form class="search-form" action="/search.php" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">
