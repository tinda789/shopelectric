<?php
// Đảm bảo không có output nào trước khi bắt đầu session
if (session_status() === PHP_SESSION_NONE) {
    // Cấu hình session bảo mật
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.cookie_samesite', 'Lax');
    
    // Tăng cường bảo mật session
    ini_set('session.cookie_lifetime', 0); // Hết hạn khi đóng trình duyệt
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_trans_sid', 0);
    
    session_start();
    
    // Tạo lại session ID sau khi đăng nhập để tránh tấn công session fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

// Thiết lập múi giờ
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Báo lỗi chi tiết trong môi trường phát triển
if (getenv('APP_ENV') === 'development' || !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Thiết lập header bảo mật
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Kết nối CSDL
try {
    require_once __DIR__ . '/../config/database.php';
    
    // Kiểm tra kết nối
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('SET NAMES utf8mb4');
    $pdo->exec('SET CHARACTER SET utf8mb4');
    $pdo->exec('SET SESSION collation_connection = "utf8mb4_unicode_ci"');
    
    // Tự động kiểm tra và tạo bảng nếu chưa tồn tại
    init_database_tables();
    
} catch (PDOException $e) {
    error_log('Lỗi kết nối CSDL: ' . $e->getMessage());
    if (getenv('APP_ENV') === 'development') {
        die('Lỗi kết nối CSDL: ' . $e->getMessage());
    } else {
        die('Đã xảy ra lỗi. Vui lòng thử lại sau.');
    }
}

// Hàm khởi tạo bảng nếu chưa tồn tại
function init_database_tables() {
    global $pdo;
    
    $tables = [
        'users' => "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                first_name VARCHAR(50) DEFAULT NULL,
                last_name VARCHAR(50) DEFAULT NULL,
                phone VARCHAR(20) DEFAULT NULL,
                address TEXT DEFAULT NULL,
                avatar VARCHAR(255) DEFAULT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                last_login DATETIME DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_username (username)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        'user_tokens' => "
            CREATE TABLE IF NOT EXISTS user_tokens (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                token VARCHAR(64) NOT NULL,
                expires_at DATETIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                UNIQUE KEY unique_token (token),
                INDEX idx_user_token (user_id, token)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        'roles' => "
            CREATE TABLE IF NOT EXISTS roles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL UNIQUE,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ",
        'user_roles' => "
            CREATE TABLE IF NOT EXISTS user_roles (
                user_id INT NOT NULL,
                role_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (user_id, role_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        "
    ];
    
    // Tạo các bảng
    foreach ($tables as $table => $sql) {
        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            error_log("Lỗi khi tạo bảng $table: " . $e->getMessage());
        }
    }
    
    // Thêm các vai trò mặc định nếu chưa có
    $default_roles = [
        'admin' => 'Quản trị viên hệ thống',
        'user' => 'Người dùng thông thường',
        'editor' => 'Biên tập viên',
        'moderator' => 'Điều hành viên'
    ];
    
    foreach ($default_roles as $name => $description) {
        try {
            $stmt = $pdo->prepare("INSERT IGNORE INTO roles (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
        } catch (PDOException $e) {
            error_log("Lỗi khi thêm vai trò $name: " . $e->getMessage());
        }
    }
    
    // Tạo tài khoản admin mặc định nếu chưa có
    try {
        $admin_email = 'admin@example.com';
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$admin_email]);
        
        if (!$stmt->fetch()) {
            $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password_hash, first_name, last_name, is_active) 
                VALUES (?, ?, ?, ?, ?, 1)
            
            ");
            $stmt->execute(['admin', $admin_email, $password_hash, 'Quản', 'Trị Viên']);
            $user_id = $pdo->lastInsertId();
            
            // Gán quyền admin
            $stmt = $pdo->prepare("SELECT id FROM roles WHERE name = 'admin' LIMIT 1");
            $stmt->execute();
            $role = $stmt->fetch();
            
            if ($role) {
                $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
                $stmt->execute([$user_id, $role['id']]);
            }
        }
    } catch (PDOException $e) {
        error_log("Lỗi khi tạo tài khoản admin mặc định: " . $e->getMessage());
    }
}

// Hàm tạo token CSRF
function generate_csrf_token($regenerate = false) {
    if ($regenerate || !isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Hàm kiểm tra token CSRF
function verify_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Token CSRF không hợp lệ');
    }
    return true;
}

// Hàm kiểm tra đăng nhập
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Hàm kiểm tra quyền admin
function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Hàm chuyển hướng đã được chuyển sang functions.php

// Hàm hiển thị thông báo
function set_flash_message($type, $message) {
    $_SESSION['flash_messages'][] = [
        'type' => $type,
        'message' => $message
    ];
}

// Hàm lấy và xóa thông báo
function get_flash_messages() {
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

// Hàm hiển thị thông báo lỗi
function display_errors($errors) {
    if (empty($errors)) return '';
    
    $html = '<div class="alert alert-danger">';
    $html .= '<ul class="mb-0">';
    foreach ($errors as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul></div>';
    return $html;
}

// Hàm lấy thông tin người dùng hiện tại
function current_user() {
    static $user = null;
    
    if ($user === null && isset($_SESSION['user_id'])) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("
                SELECT u.*, GROUP_CONCAT(r.name) as roles 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.id = ? 
                GROUP BY u.id
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $user['roles'] = $user['roles'] ? explode(',', $user['roles']) : [];
            }
        } catch (PDOException $e) {
            error_log('Lỗi khi lấy thông tin người dùng: ' . $e->getMessage());
        }
    }
    
    return $user;
}

// Tự động tải các file helper
$helper_dir = __DIR__ . '/helpers';
if (is_dir($helper_dir)) {
    $helpers = glob($helper_dir . '/*.php');
    foreach ($helpers as $helper) {
        require_once $helper;
    }
}
