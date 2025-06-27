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

// Lấy ID sản phẩm cần xóa
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    $_SESSION['error_message'] = 'ID sản phẩm không hợp lệ';
    header('Location: /controllers/products.php');
    exit;
}

try {
    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // 1. Xóa ảnh sản phẩm từ thư mục (nếu cần)
    $stmt = $pdo->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // 2. Xóa dữ liệu trong bảng product_images
    $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ?");
    $stmt->execute([$id]);
    
    // 3. Xóa sản phẩm
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    
    // Commit transaction
    $pdo->commit();
    
    // Xóa file ảnh sau khi xóa dữ liệu thành công
    foreach ($images as $image) {
        if (!empty($image) && file_exists(__DIR__ . '/../../uploads/products/' . $image)) {
            @unlink(__DIR__ . '/../../uploads/products/' . $image);
        }
    }
    
    $_SESSION['success_message'] = 'Xóa sản phẩm thành công';
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $pdo->rollBack();
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage();
}

// Chuyển hướng về trang danh sách sản phẩm
header('Location: /controllers/products.php');
exit;
