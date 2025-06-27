<?php
/**
 * Header chung cho toàn bộ trang
 */

// Include file khởi tạo
require_once __DIR__ . '/init.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopElectrics - Cửa hàng điện tử</title>
    
    <!-- Meta tags -->
    <meta name="description" content="Cửa hàng điện tử uy tín, chất lượng với đa dạng sản phẩm công nghệ">
    <meta name="keywords" content="điện thoại, laptop, phụ kiện, công nghệ">
    <meta name="author" content="ShopElectrics">
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Kích hoạt dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo dropdown
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    autoClose: true
                });
            });
            
            // Kích hoạt popover
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Xử lý sự kiện click cho dropdown trên mobile
            if (window.innerWidth <= 991.98) {
                document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        var menu = this.nextElementSibling;
                        var isOpen = menu.classList.contains('show');
                        
                        // Đóng tất cả các menu đang mở
                        document.querySelectorAll('.dropdown-menu.show').forEach(function(openMenu) {
                            if (openMenu !== menu) {
                                openMenu.classList.remove('show');
                            }
                        });
                        
                        // Toggle menu hiện tại
                        if (!isOpen) {
                            menu.classList.add('show');
                        } else {
                            menu.classList.remove('show');
                        }
                    });
                });
                
                // Đóng menu khi click ra ngoài
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                            menu.classList.remove('show');
                        });
                    }
                });
            }
        });
    </script>
    
    <style>
        body { 
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        .sidebar { 
            background-color: #f8f9fa; 
            padding: 20px; 
            border-radius: 5px; 
        }
        .table-container { 
            margin-top: 20px; 
        }
        .navbar-brand { 
            font-weight: bold; 
        }
        .form-container { 
            max-width: 500px; 
            margin: 30px auto; 
            padding: 20px; 
            background: #fff;
            border: 1px solid #dee2e6; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-title { 
            text-align: center; 
            margin-bottom: 30px; 
            color: #0d6efd;
        }
        .error-message { 
            color: #dc3545; 
            margin-top: 5px; 
            font-size: 0.875em;
        }
        .success-message { 
            color: #198754; 
            margin: 15px 0;
            padding: 10px;
            background-color: #d1e7dd;
            border-radius: 4px;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .dropdown-item:active {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="index.php">
                    <i class="bi bi-laptop me-2"></i>
                    <span>ShopElectrics</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" 
                        aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? ' active' : '' ?>" href="index.php">
                                <i class="bi bi-house-door me-1"></i> Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= strpos($_SERVER['PHP_SELF'], 'products') !== false ? ' active' : '' ?>" 
                               href="products.php">
                                <i class="bi bi-box me-1"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= strpos($_SERVER['PHP_SELF'], 'categories') !== false ? ' active' : '' ?>" 
                               href="categories.php">
                                <i class="bi bi-tags me-1"></i> Danh mục
                            </a>
                        </li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link<?= strpos($_SERVER['PHP_SELF'], 'orders') !== false ? ' active' : '' ?>" 
                               href="orders.php">
                                <i class="bi bi-cart-check me-1"></i> Đơn hàng
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Người dùng đã đăng nhập -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
                                   id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <span class="d-none d-md-inline">
                                        <?= htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) ?>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li>
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="bi bi-person me-2"></i> Hồ sơ
                                        </a>
                                    </li>
                                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                                    <li>
                                        <a class="dropdown-item" href="admin/">
                                            <i class="bi bi-speedometer2 me-2"></i> Bảng điều khiển
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="logout.php">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <!-- Chưa đăng nhập -->
                            <li class="nav-item">
                                <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'login.php' ? ' active' : '' ?>" 
                                   href="login.php">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?= basename($_SERVER['PHP_SELF']) == 'register.php' ? ' active' : '' ?>" 
                                   href="register.php">
                                    <i class="bi bi-person-plus me-1"></i> Đăng ký
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        
        <style>
            /* Thêm style cho dropdown menu */
            .dropdown-menu {
                background: white;
                border: 1px solid rgba(0,0,0,.15);
                border-radius: 0.375rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                min-width: 12rem;
                padding: 0.5rem 0;
                position: absolute;
                display: none;
            }
            .dropdown-item {
                color: #212529;
                display: block;
                padding: 0.25rem 1rem;
                clear: both;
                font-weight: 400;
                text-align: inherit;
                text-decoration: none;
                white-space: nowrap;
                background-color: transparent;
                border: 0;
            }
            .dropdown-item:hover {
                background-color: #f8f9fa;
                color: #0d6efd;
            }
            .dropdown-divider {
                height: 0;
                margin: 0.5rem 0;
                overflow: hidden;
                border-top: 1px solid rgba(0,0,0,.15);
            }
            .show {
                display: block !important;
            }
            /* Style cho mobile menu */
            @media (max-width: 991.98px) {
                .navbar-collapse {
                    display: none;
                    background: #fff;
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    z-index: 1000;
                    padding: 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 0.375rem;
                    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                }
                .navbar-collapse.show {
                    display: block;
                }
                .dropdown-menu {
                    position: static;
                    float: none;
                    border: none;
                    box-shadow: none;
                    padding-left: 1rem;
                }
            }
        </style>
        
        <script>
            // Xử lý menu mobile
            document.addEventListener('DOMContentLoaded', function() {
                const menuToggler = document.getElementById('menuToggler');
                const mainNav = document.getElementById('mainNav');
                
                if (menuToggler && mainNav) {
                    menuToggler.addEventListener('click', function() {
                        mainNav.classList.toggle('show');
                    });
                }
                
                // Xử lý dropdown menu
                const userMenu = document.getElementById('userMenu');
                const userDropdown = document.getElementById('userDropdown');
                const userDropdownMenu = document.getElementById('userDropdownMenu');
                
                if (userMenu && userDropdownMenu) {
                    userMenu.addEventListener('click', function(e) {
                        e.preventDefault();
                        userDropdownMenu.classList.toggle('show');
                    });
                    
                    // Đóng dropdown khi click ra ngoài
                    document.addEventListener('click', function(e) {
                        if (!userDropdown.contains(e.target)) {
                            userDropdownMenu.classList.remove('show');
                        }
                    });
                }
                
                // Đóng menu khi click vào liên kết (trên mobile)
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    if (link.id !== 'userMenu') {
                        link.addEventListener('click', function() {
                            if (window.innerWidth <= 991.98) {
                                mainNav.classList.remove('show');
                            }
                        });
                    }
                });
            });
            
            // Đóng menu khi thay đổi kích thước màn hình
            window.addEventListener('resize', function() {
                const mainNav = document.getElementById('mainNav');
                if (window.innerWidth > 991.98) {
                    mainNav.style.display = 'flex';
                } else {
                    mainNav.style.display = '';
                }
            });
        </script>
    </div>
