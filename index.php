<?php
require_once 'includes/init.php';

// Lấy số liệu thống kê
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
];
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Trang quản trị ShopElectrics</h1>
        
        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= number_format($stats['products']) ?></h5>
                        <p class="card-text">Sản phẩm</p>
                        <a href="products.php" class="text-white">Xem chi tiết <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= number_format($stats['categories']) ?></h5>
                        <p class="card-text">Danh mục</p>
                        <a href="categories.php" class="text-white">Xem danh mục <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= number_format($stats['orders']) ?></h5>
                        <p class="card-text">Đơn hàng</p>
                        <a href="orders.php" class="text-white">Xem đơn hàng <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= number_format($stats['users']) ?></h5>
                        <p class="card-text">Người dùng</p>
                        <a href="#" class="text-white">Xem người dùng <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Các chức năng chính -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Quản lý sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <p>Quản lý danh sách sản phẩm, thêm mới, chỉnh sửa hoặc xóa sản phẩm.</p>
                        <a href="products.php" class="btn btn-primary">Đi đến quản lý sản phẩm</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Quản lý đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <p>Xem và xử lý các đơn hàng mới, cập nhật trạng thái đơn hàng.</p>
                        <a href="orders.php" class="btn btn-success">Đi đến quản lý đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
