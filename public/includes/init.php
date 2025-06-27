<?php
/**
 * Initialize application
 */

// Load configuration
$configPath = __DIR__ . '/../../config.php';
if (!file_exists($configPath)) {
    die('Không tìm thấy file cấu hình. Vui lòng kiểm tra lại cấu trúc thư mục.');
}
require_once $configPath;

// Load database connection
$databasePath = __DIR__ . '/../../config/database.php';
if (!file_exists($databasePath)) {
    die('Không tìm thấy file cấu hình cơ sở dữ liệu.');
}
require_once $databasePath;

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_name(SESSION_NAME);
    session_start();
}

// Set default timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Make $pdo available globally
$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    die('Không thể kết nối đến cơ sở dữ liệu.');
}

// Helper functions
require_once __DIR__ . '/functions.php';

// Authentication
$currentUser = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND status = 1 LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch();
    
    // Update last seen
    if ($currentUser) {
        $pdo->prepare("UPDATE users SET last_seen = NOW() WHERE id = ?")->execute([$currentUser->id]);
    }
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Khởi tạo flash messages nếu chưa có
if (!isset($_SESSION['flash_messages'])) {
    $_SESSION['flash_messages'] = [];
}

// Các hàm xử lý flash message đã được chuyển sang functions.php

// Các hàm xác thực và phân quyền đã được chuyển sang functions.php

// Xử lý CSRF đã được chuyển sang functions.php
// Kiểm tra CSRF token cho các request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_token']) || !verifyCsrfToken($_POST['_token'])) {
        http_response_code(419);
        die('Token xác thực không hợp lệ. Vui lòng thử lại.');
    }
}

// Set default locale
setlocale(LC_MONETARY, 'vi_VN');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0,
        'count' => 0
    ];
}

/**
 * Add item to cart
 * 
 * @param int $productId Product ID
 * @param int $quantity Quantity (default: 1)
 * @param array $options Additional options
 * @return bool
 */
function addToCart($productId, $quantity = 1, $options = []) {
    global $pdo;
    
    // Get product info
    $stmt = $pdo->prepare("SELECT id, name, price, stock_quantity, images FROM products WHERE id = ? AND status = 1");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        return false;
    }
    
    // Check stock
    if ($product->stock_quantity < $quantity) {
        addFlashMessage('error', 'Sản phẩm không đủ số lượng tồn kho.');
        return false;
    }
    
    $cartItemId = md5($productId . serialize($options));
    $images = json_decode($product->images, true);
    $image = !empty($images[0]) ? $images[0] : '/assets/images/placeholder-product.jpg';
    
    if (isset($_SESSION['cart']['items'][$cartItemId])) {
        // Update quantity if item already in cart
        $newQuantity = $_SESSION['cart']['items'][$cartItemId]['quantity'] + $quantity;
        
        if ($newQuantity > $product->stock_quantity) {
            addFlashMessage('error', 'Số lượng sản phẩm vượt quá số lượng tồn kho.');
            return false;
        }
        
        $_SESSION['cart']['items'][$cartItemId]['quantity'] = $newQuantity;
    } else {
        // Add new item to cart
        $_SESSION['cart']['items'][$cartItemId] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'image' => $image,
            'options' => $options
        ];
    }
    
    // Update cart totals
    updateCartTotals();
    
    return true;
}

/**
 * Update cart item quantity
 * 
 * @param string $itemId Cart item ID
 * @param int $quantity New quantity
 * @return bool
 */
function updateCartItem($itemId, $quantity) {
    if (!isset($_SESSION['cart']['items'][$itemId])) {
        return false;
    }
    
    if ($quantity <= 0) {
        // Remove item if quantity is zero or negative
        unset($_SESSION['cart']['items'][$itemId]);
    } else {
        // Update quantity
        $_SESSION['cart']['items'][$itemId]['quantity'] = $quantity;
    }
    
    // Update cart totals
    updateCartTotals();
    
    return true;
}

/**
 * Remove item from cart
 * 
 * @param string $itemId Cart item ID
 * @return bool
 */
function removeFromCart($itemId) {
    if (!isset($_SESSION['cart']['items'][$itemId])) {
        return false;
    }
    
    unset($_SESSION['cart']['items'][$itemId]);
    
    // Update cart totals
    updateCartTotals();
    
    return true;
}

/**
 * Clear cart
 * 
 * @return void
 */
function clearCart() {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0,
        'count' => 0
    ];
}

/**
 * Update cart totals
 * 
 * @return void
 */
function updateCartTotals() {
    $total = 0;
    $count = 0;
    
    foreach ($_SESSION['cart']['items'] as $item) {
        $total += $item['price'] * $item['quantity'];
        $count += $item['quantity'];
    }
    
    $_SESSION['cart']['total'] = $total;
    $_SESSION['cart']['count'] = $count;
}

/**
 * Get cart items
 * 
 * @return array Cart items
 */
function getCartItems() {
    return $_SESSION['cart']['items'] ?? [];
}

/**
 * Get cart total
 * 
 * @return float Cart total
 */
function getCartTotal() {
    return $_SESSION['cart']['total'] ?? 0;
}

/**
 * Get cart item count
 * 
 * @return int Cart item count
 */
function getCartItemCount() {
    return $_SESSION['cart']['count'] ?? 0;
}

// Set default meta tags
$meta = [
    'title' => 'ShopElectrics - Cửa hàng điện tử hàng đầu Việt Nam',
    'description' => 'Chuyên cung cấp các sản phẩm điện tử, điện lạnh, gia dụng chính hãng, giá tốt nhất thị trường.',
    'keywords' => 'điện thoại, laptop, máy tính bảng, phụ kiện, điện tử, điện lạnh, gia dụng',
    'image' => '/assets/images/og-image.jpg',
    'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
];

// Set page title
$pageTitle = $meta['title'];

// Function to set page title
function setPageTitle($title) {
    global $pageTitle;
    $pageTitle = $title . ' - ShopElectrics';
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
