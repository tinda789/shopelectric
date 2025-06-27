<?php
/**
 * ShopElectrics - Trang chủ
 */

// Khởi tạo session và kết nối database
require_once __DIR__ . '/includes/init.php';

// Lấy danh sách sản phẩm nổi bật
$stmt = $pdo->query("SELECT p.*, c.name as category_name, b.name as brand_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN brands b ON p.brand_id = b.id 
                    WHERE p.featured = 1 
                    ORDER BY p.created_at DESC 
                    LIMIT 8");
$featuredProducts = $stmt->fetchAll(PDO::FETCH_OBJ);

// Lấy danh sách sản phẩm mới nhất
$stmt = $pdo->query("SELECT p.*, c.name as category_name, b.name as brand_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    LEFT JOIN brands b ON p.brand_id = b.id 
                    ORDER BY p.created_at DESC 
                    LIMIT 8");
$newProducts = $stmt->fetchAll(PDO::FETCH_OBJ);

// Lấy danh sách danh mục
$stmt = $pdo->query("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_OBJ);

// Lấy danh sách thương hiệu
$stmt = $pdo->query("SELECT * FROM brands ORDER BY name");
$brands = $stmt->fetchAll(PDO::FETCH_OBJ);

// Đặt tiêu đề trang
$pageTitle = 'Trang chủ - ShopElectrics';

// Include header
include __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section mb-5">
    <div class="container">
        <div class="row align-items-center min-vh-50">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Công nghệ mới nhất với giá tốt nhất</h1>
                <p class="lead mb-4">Khám phá bộ sưu tập sản phẩm công nghệ hiện đại, chính hãng với nhiều ưu đãi hấp dẫn.</p>
                <a href="/products.php" class="btn btn-primary btn-lg px-4 me-2">Mua ngay</a>
                <a href="#featured-products" class="btn btn-outline-light btn-lg px-4">Khám phá</a>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="/assets/images/hero-image.png" alt="Công nghệ hiện đại" class="img-fluid">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section mb-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Danh mục sản phẩm</h2>
        <div class="row g-4">
            <?php foreach (array_slice($categories, 0, 6) as $category): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="/category/<?= htmlspecialchars($category->slug) ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm text-center p-3">
                        <div class="mb-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                <i class="bi bi-laptop" style="font-size: 2rem; color: var(--primary);"></i>
                            </div>
                        </div>
                        <h5 class="mb-0"><?= htmlspecialchars($category->name) ?></h5>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section id="featured-products" class="featured-products py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Sản phẩm nổi bật</h2>
            <a href="/products.php?sort=featured" class="btn btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="row g-4">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <?php include __DIR__ . '/includes/partials/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Banner -->
<section class="banner-section my-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a href="/promotion" class="d-block">
                    <img src="/assets/images/banner-1.jpg" alt="Khuyến mãi đặc biệt" class="img-fluid rounded-3 w-100">
                </a>
            </div>
        </div>
    </div>
</section>

<!-- New Arrivals -->
<section class="new-arrivals py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">Sản phẩm mới về</h2>
            <a href="/products.php?sort=newest" class="btn btn-outline-primary">Xem tất cả</a>
        </div>
        <div class="row g-4">
            <?php foreach ($newProducts as $product): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <?php include __DIR__ . '/includes/partials/product-card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Brands -->
<section class="brands-section py-4 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center align-items-center">
                    <?php foreach ($brands as $brand): ?>
                    <div class="p-3">
                        <a href="/brand/<?= htmlspecialchars($brand->slug) ?>" class="d-block">
                            <img src="/uploads/brands/<?= htmlspecialchars($brand->logo) ?>" alt="<?= htmlspecialchars($brand->name) ?>" class="img-fluid" style="max-height: 50px; width: auto; filter: grayscale(100%); opacity: 0.7; transition: all 0.3s ease;">
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features-section py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6 text-center">
                <div class="p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-truck text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="h6 mb-2">Miễn phí vận chuyển</h5>
                    <p class="text-muted small mb-0">Cho đơn hàng từ 500K</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-arrow-repeat text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="h6 mb-2">Đổi trả miễn phí</h5>
                    <p class="text-muted small mb-0">Trong vòng 7 ngày</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="h6 mb-2">Bảo hành chính hãng</h5>
                    <p class="text-muted small mb-0">Lên đến 24 tháng</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="p-3">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-headset text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="h6 mb-2">Hỗ trợ 24/7</h5>
                    <p class="text-muted small mb-0">Hotline: 0123 456 789</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include __DIR__ . '/includes/footer.php';
?>
