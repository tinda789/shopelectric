<?php
/**
 * Initialize admin application
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Load environment variables
require_once __DIR__ . '/../../config.php';

// Error reporting
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Database connection
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die('Kết nối cơ sở dữ liệu thất bại: ' . $e->getMessage());
}

// Check if user is logged in and is admin
$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND status = 1 AND role = 'admin' LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();
    
    // Update last seen
    if ($currentUser) {
        $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = ?")->execute([$currentUser->id]);
    } else {
        // User is not an admin, redirect to shop home
        header('Location: /');
        exit;
    }
} else {
    // User is not logged in, redirect to shop home
    header('Location: /');
    exit;
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Flash messages
if (!isset($_SESSION['flash_messages'])) {
    $_SESSION['flash_messages'] = [];
}

// Helper functions
require_once __DIR__ . '/../../includes/functions.php';

// Add admin-specific functions here

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_token']) || !verifyCsrfToken($_POST['_token'])) {
        http_response_code(419);
        die('Token xác thực không hợp lệ. Vui lòng thử lại.');
    }
}

// Set default meta tags for admin
$meta = [
    'title' => 'Trang quản trị - ShopElectrics',
    'description' => 'Hệ thống quản trị cửa hàng điện tử ShopElectrics',
    'keywords' => 'quản trị, admin, shop, điện tử',
];

// Set page title
$pageTitle = $meta['title'];

// Function to set page title
function setPageTitle($title) {
    global $pageTitle;
    $pageTitle = $title . ' - ShopElectrics Admin';
}

// Function to set meta tags
function setMetaTags($tags) {
    global $meta;
    $meta = array_merge($meta, $tags);
}

// Function to get meta tags
function getMetaTags() {
    global $meta;
    return $meta;
}
