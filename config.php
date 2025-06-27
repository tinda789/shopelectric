<?php
/**
 * Cấu hình ứng dụng
 * 
 * File này chứa các hằng số cấu hình chung
 */

// Cấu hình URL
define('BASE_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/');
define('ADMIN_URL', BASE_URL . 'admin/');

// Cấu hình ứng dụng
define('APP_NAME', 'ShopElectronics');
define('APP_DEBUG', true);

// Cấu hình email
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-email-password');
define('MAIL_FROM_ADDRESS', 'noreply@example.com');
define('MAIL_FROM_NAME', APP_NAME);

// Cấu hình upload
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('ALLOWED_EXTENSIONS', serialize(['jpg', 'jpeg', 'png', 'gif']));
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Cấu hình session
define('SESSION_LIFETIME', 86400); // 1 ngày
define('SESSION_NAME', 'shop_electronics_session');

// Cấu hình bảo mật
define('HASH_ALGO', PASSWORD_BCRYPT);
// HASH_OPTIONS phải được định nghĩa dưới dạng chuỗi JSON vì define không hỗ trợ mảng
define('HASH_OPTIONS_JSON', '{"cost":12}');

// Cấu hình phân trang
define('ITEMS_PER_PAGE', 12);

// Cấu hình thời gian
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
