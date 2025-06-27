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

// Lấy ID sản phẩm
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

// Kiểm tra xem có file ảnh được tải lên không
if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No images uploaded']);
    exit;
}

// Thư mục lưu trữ ảnh
$upload_dir = __DIR__ . '/../../uploads/products/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$uploaded_files = [];
$errors = [];

// Lặp qua từng file ảnh
foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $file_name = $_FILES['images']['name'][$key];
    $file_size = $_FILES['images']['size'][$key];
    $file_tmp = $_FILES['images']['tmp_name'][$key];
    $file_type = $_FILES['images']['type'][$key];
    
    // Kiểm tra lỗi
    if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
        $errors[] = "Lỗi khi tải lên file $file_name";
        continue;
    }
    
    // Kiểm tra kích thước file (tối đa 5MB)
    if ($file_size > 5 * 1024 * 1024) {
        $errors[] = "File $file_name vượt quá kích thước cho phép (5MB)";
        continue;
    }
    
    // Kiểm tra định dạng file
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($file_ext, $allowed_extensions)) {
        $errors[] = "Định dạng file $file_name không được hỗ trợ";
        continue;
    }
    
    // Tạo tên file mới để tránh trùng lặp
    $new_file_name = uniqid('product_') . '_' . time() . '.' . $file_ext;
    $destination = $upload_dir . $new_file_name;
    
    // Di chuyển file vào thư mục đích
    if (move_uploaded_file($file_tmp, $destination)) {
        // Thêm vào danh sách file đã tải lên thành công
        $uploaded_files[] = [
            'name' => $file_name,
            'path' => $new_file_name
        ];
        
        // Lưu thông tin ảnh vào CSDL
        try {
            $is_primary = $pdo->query("SELECT COUNT(*) FROM product_images WHERE product_id = $product_id")->fetchColumn() == 0 ? 1 : 0;
            
            $stmt = $pdo->prepare("
                INSERT INTO product_images (product_id, image_url, is_primary, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW())
            ");
            
            $stmt->execute([$product_id, $new_file_name, $is_primary]);
            
        } catch (Exception $e) {
            // Nếu lưu vào CSDL thất bại, xóa file đã tải lên
            @unlink($destination);
            $errors[] = "Lỗi khi lưu thông tin ảnh $file_name: " . $e->getMessage();
        }
    } else {
        $errors[] = "Không thể tải lên file $file_name";
    }
}

// Trả về kết quả
header('Content-Type: application/json');
if (empty($errors)) {
    echo json_encode([
        'success' => true,
        'message' => 'Tải lên ảnh thành công',
        'files' => $uploaded_files
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Có lỗi xảy ra khi tải lên một số ảnh',
        'errors' => $errors,
        'uploaded_files' => $uploaded_files
    ]);
}

exit;
