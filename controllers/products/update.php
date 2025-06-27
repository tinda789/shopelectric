<?php
require_once __DIR__ . '/../../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = 'Yêu cầu không hợp lệ';
    header('Location: /controllers/products.php');
    exit;
}

// Lấy dữ liệu từ form
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name = trim($_POST['name'] ?? '');
$sku = trim($_POST['sku'] ?? '');
$category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
$brand_id = !empty($_POST['brand_id']) ? (int)$_POST['brand_id'] : null;
$price = (float)($_POST['price'] ?? 0);
$original_price = !empty($_POST['original_price']) ? (float)$_POST['original_price'] : null;
$stock_quantity = (int)($_POST['stock_quantity'] ?? 0);
$weight = !empty($_POST['weight']) ? (float)$_POST['weight'] : null;
$short_description = trim($_POST['short_description'] ?? '');
$description = trim($_POST['description'] ?? '');
$status = in_array($_POST['status'] ?? '', ['active', 'inactive', 'out_of_stock']) ? $_POST['status'] : 'active';

// Validate dữ liệu
if (empty($name)) {
    $_SESSION['error_message'] = 'Vui lòng nhập tên sản phẩm';
    header("Location: /controllers/products/edit.php?id=$id");
    exit;
}

if ($price <= 0) {
    $_SESSION['error_message'] = 'Giá sản phẩm phải lớn hơn 0';
    header("Location: /controllers/products/edit.php?id=$id");
    exit;
}

try {
    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // Cập nhật thông tin sản phẩm
    $stmt = $pdo->prepare("
        UPDATE products 
        SET name = ?, 
            sku = ?, 
            category_id = ?, 
            brand_id = ?, 
            price = ?, 
            original_price = ?, 
            stock_quantity = ?, 
            weight = ?, 
            short_description = ?, 
            description = ?, 
            status = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $stmt->execute([
        $name,
        $sku,
        $category_id,
        $brand_id,
        $price,
        $original_price,
        $stock_quantity,
        $weight,
        $short_description,
        $description,
        $status,
        $id
    ]);
    
    // Commit transaction
    $pdo->commit();
    
    $_SESSION['success_message'] = 'Cập nhật sản phẩm thành công';
    header("Location: /controllers/products/edit.php?id=$id");
    exit;
    
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $pdo->rollBack();
    
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage();
    header("Location: /controllers/products/edit.php?id=$id");
    exit;
}
