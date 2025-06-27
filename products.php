<?php
require_once 'includes/init.php';

// Lấy danh sách sản phẩm
$stmt = $pdo->query("SELECT 
                    p.id, p.name, p.price, p.stock_quantity, p.created_at,
                    c.name as category_name, 
                    b.name as brand_name,
                    (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                   FROM products p 
                   LEFT JOIN categories c ON p.category_id = c.id 
                   LEFT JOIN brands b ON p.brand_id = b.id 
                   WHERE p.status = 'active'
                   ORDER BY p.id DESC");
$products = $stmt->fetchAll();

// Debug: Kiểm tra dữ liệu trả về
// echo '<pre>'; print_r($products); echo '</pre>';
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>Danh sách sản phẩm</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product->id) ?></td>
                        <td>
                            <?php if (!empty($product->image)): ?>
                                <img src="/uploads/products/<?= htmlspecialchars($product->image) ?>" alt="" style="max-width: 50px;">
                            <?php else: ?>
                                <span class="text-muted">Không có ảnh</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product->name) ?></td>
                        <td><?= htmlspecialchars($product->category_name ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($product->brand_name ?? 'N/A') ?></td>
                        <td><?= number_format($product->price, 0, ',', '.') ?> đ</td>
                        <td><?= $product->stock_quantity ?? 0 ?></td>
                        <td><?= !empty($product->created_at) ? date('d/m/Y', strtotime($product->created_at)) : 'N/A' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
