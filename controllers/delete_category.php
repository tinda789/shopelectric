<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Xử lý xóa danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = (int)$_POST['category_id'];
    
    try {
        // Kiểm tra xem danh mục có sản phẩm nào không
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $product_count = $stmt->fetchColumn();
        
        if ($product_count > 0) {
            $_SESSION['error_message'] = 'Không thể xóa danh mục vì có sản phẩm đang sử dụng.';
        } else {
            // Xóa danh mục nếu không có sản phẩm nào
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category_id]);
            
            if ($stmt->rowCount() > 0) {
                $_SESSION['success_message'] = 'Xóa danh mục thành công!';
            } else {
                $_SESSION['error_message'] = 'Không tìm thấy danh mục để xóa.';
            }
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Lỗi khi xóa danh mục: ' . $e->getMessage();
    }
}

// Quay lại trang danh sách danh mục
header('Location: /controllers/categories.php');
exit;
