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

// Lấy ID ảnh cần xóa
$image_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($image_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid image ID']);
    exit;
}

try {
    // Bắt đầu transaction
    $pdo->beginTransaction();
    
    // Lấy thông tin ảnh trước khi xóa
    $stmt = $pdo->prepare("SELECT * FROM product_images WHERE id = ?");
    $stmt->execute([$image_id]);
    $image = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$image) {
        throw new Exception('Không tìm thấy ảnh');
    }
    
    // Xóa ảnh trong CSDL
    $stmt = $pdo->prepare("DELETE FROM product_images WHERE id = ?");
    $stmt->execute([$image_id]);
    
    // Nếu ảnh bị xóa là ảnh chính, cập nhật ảnh chính khác (nếu có)
    if ($image->is_primary) {
        // Lấy ảnh đầu tiên làm ảnh chính mới
        $stmt = $pdo->prepare("
            SELECT id FROM product_images 
            WHERE product_id = ? AND id != ? 
            ORDER BY id ASC LIMIT 1
        ");
        $stmt->execute([$image->product_id, $image_id]);
        $new_primary = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($new_primary) {
            $stmt = $pdo->prepare("UPDATE product_images SET is_primary = 1 WHERE id = ?");
            $stmt->execute([$new_primary->id]);
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Xóa file ảnh
    $file_path = __DIR__ . '/../../uploads/products/' . $image->image_url;
    if (file_exists($file_path)) {
        @unlink($file_path);
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Xóa ảnh thành công']);
    
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $pdo->rollBack();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false, 
        'message' => 'Có lỗi xảy ra khi xóa ảnh: ' . $e->getMessage()
    ]);
}

exit;
