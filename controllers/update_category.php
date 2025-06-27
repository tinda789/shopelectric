<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Xử lý cập nhật danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = (int)$_POST['category_id'];
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            $stmt->execute([$name, $description, $category_id]);
            
            $_SESSION['success_message'] = 'Cập nhật danh mục thành công!';
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Lỗi khi cập nhật danh mục: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = 'Vui lòng nhập tên danh mục';
    }
}

// Quay lại trang danh sách danh mục
header('Location: /controllers/categories.php');
exit;
