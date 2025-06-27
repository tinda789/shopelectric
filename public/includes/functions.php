<?php
/**
 * Các hàm helper dùng chung cho ứng dụng
 */

// Hàm kiểm tra đăng nhập
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hàm kiểm tra quyền admin
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Hàm tạo CSRF token
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Hàm kiểm tra CSRF token
function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Hàm tạo CSRF field cho form
function csrf_field() {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

// Hàm chuyển hướng
function redirect($url, $statusCode = 302) {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

// Hàm tạo thông báo flash
function addFlashMessage($type, $message) {
    if (!isset($_SESSION['flash_messages'])) {
        $_SESSION['flash_messages'] = [];
    }
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message,
        'time' => time()
    ];
}

// Hàm lấy thông báo flash
function getFlashMessages($type = null) {
    if (!isset($_SESSION['flash_messages'])) {
        return [];
    }
    
    $messages = $_SESSION['flash_messages'];
    unset($_SESSION['flash_messages']);
    
    if ($type) {
        return array_filter($messages, function($msg) use ($type) {
            return $msg['type'] === $type;
        });
    }
    
    return $messages;
}

// Hàm kiểm tra vai trò người dùng
function hasRole($roles) {
    global $currentUser;
    
    if (!isLoggedIn() || !$currentUser) {
        return false;
    }
    
    $userRole = $currentUser->role ?? null;
    
    if (is_array($roles)) {
        return in_array($userRole, $roles);
    }
    
    return $userRole === $roles;
}

// Hàm yêu cầu đăng nhập
function requireAuth() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('/login.php');
    }
}

// Hàm yêu cầu quyền hạn
function requireRole($roles) {
    requireAuth();
    
    if (!hasRole($roles)) {
        http_response_code(403);
        die('Bạn không có quyền truy cập trang này.');
    }
}

// Hàm tạo slug từ chuỗi
function createSlug($string) {
    $string = mb_strtolower($string, 'UTF-8');
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/\s+/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

// Hàm định dạng tiền tệ
function formatCurrency($amount, $currency = 'VND') {
    return number_format($amount, 0, ',', '.') . ' ' . $currency;
}

// Hàm lấy giá trị từ mảng hoặc trả về giá trị mặc định
function getValue($array, $key, $default = null) {
    return $array[$key] ?? $default;
}

// Hàm kiểm tra xem request có phải là AJAX không
function isAjax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Hàm trả về phản hồi JSON
function jsonResponse($data = null, $statusCode = 200, $message = '') {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => $statusCode < 400 ? 'success' : 'error',
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Hàm lấy đường dẫn asset
function asset($path) {
    return BASE_URL . 'assets/' . ltrim($path, '/');
}

// Hàm lấy đường dẫn upload
function upload_path($path = '') {
    return __DIR__ . '/../../uploads/' . ltrim($path, '/');
}

// Hàm xử lý upload file
function handleFileUpload($file, $targetDir = '') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Lỗi khi tải lên file');
    }
    
    $targetDir = upload_path($targetDir);
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetFile = $targetDir . $fileName;
    
    $allowedExtensions = unserialize(ALLOWED_EXTENSIONS);
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Định dạng file không được hỗ trợ');
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        throw new Exception('Kích thước file quá lớn');
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $fileName;
    }
    
    throw new Exception('Có lỗi xảy ra khi lưu file');
}
