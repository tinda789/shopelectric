<?php
require_once __DIR__ . '/../../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Lấy dữ liệu
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$image_id = isset($_POST['image_id']) ? (int)$_POST['image_id'] : 0;

if ($product_id <= 0 || $image_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // Kiểm tra xem ảnh có thuộc về sản phẩm không
    $stmt = $pdo->prepare("
        SELECT id FROM product_images 
        WHERE id = ? AND product_id = ?
    ");
    $stmt->execute([$image_id, $product_id]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Ảnh không thuộc về sản phẩm này');
    }
    
    // Đặt tất cả ảnh của sản phẩm về không phải ảnh chính
    $stmt = $pdo->prepare("
        UPDATE product_images 
        SET is_primary = 0 
        WHERE product_id = ?
    
    
    
    ");
    $stmt->execute([$product_id]);
    
    // Đặt ảnh được chọn làm ảnh chính
    $stmt = $pdo->prepare("
        UPDATE product_images 
        SET is_primary = 1, 
            updated_at = NOW()
        WHERE id = ? AND product_id = ?
    ");
    $stmt->execute([$image_id, $product_id]);
    
    // Commit transaction
    $pdo->commit();
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Đã đặt làm ảnh chính']);
    
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $pdo->rollBack();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
    ]);
}

exit;
