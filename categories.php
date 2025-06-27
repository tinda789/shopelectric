<?php
require_once 'includes/init.php';

// Lấy danh sách danh mục
$stmt = $pdo->query("SELECT c.*, COUNT(p.id) as product_count 
                   FROM categories c 
                   LEFT JOIN products p ON c.id = p.category_id 
                   GROUP BY c.id 
                   ORDER BY c.name");
$categories = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>Danh mục sản phẩm</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= $category->id ?></td>
                        <td><?= htmlspecialchars($category->name) ?></td>
                        <td><?= htmlspecialchars($category->description ?? 'Không có mô tả') ?></td>
                        <td><?= $category->product_count ?></td>
                        <td><?= !empty($category->created_at) ? date('d/m/Y', strtotime($category->created_at)) : 'N/A' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
