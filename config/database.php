<?php
// Cấu hình cơ sở dữ liệu đơn giản
$host = getenv('DB_HOST') ?: 'db'; // Changed from 'localhost' to 'db' to match Docker service name
$dbname = getenv('DB_DATABASE') ?: 'shopelectrics'; // Đã đổi tên database thành 'shopelectrics'
$username = getenv('DB_USERNAME') ?: 'root'; // Sử dụng tài khoản root
$password = getenv('DB_PASSWORD') ?: 'root'; // Mật khẩu root
$charset = 'utf8mb4';

// Tùy chọn kết nối PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset"
];

try {
    // Tạo kết nối PDO
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Đặt timezone
    $pdo->exec("SET time_zone='+07:00'");
    
} catch (PDOException $e) {
    // Hiển thị lỗi chi tiết trong môi trường phát triển
    if (getenv('APP_ENV') !== 'production') {
        die('Lỗi kết nối CSDL: ' . $e->getMessage());
    } else {
        die('Lỗi kết nối cơ sở dữ liệu. Vui lòng thử lại sau.');
    }
}

/**
 * Thực thi câu lệnh SQL
 */
function db_query($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log('Lỗi SQL: ' . $e->getMessage() . ' - Query: ' . $sql);
        throw $e;
    }
}

/**
 * Lấy một bản ghi
 */
function db_get_row($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetch();
}

/**
 * Lấy tất cả bản ghi
 */
function db_get_all($sql, $params = []) {
    $stmt = db_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Chèn dữ liệu và trả về ID
 */
function db_insert($table, $data) {
    global $pdo;
    
    $fields = array_keys($data);
    $placeholders = array_map(fn($field) => ":$field", $fields);
    
    $sql = sprintf(
        'INSERT INTO %s (%s) VALUES (%s)',
        $table,
        implode(', ', $fields),
        implode(', ', $placeholders)
    );
    
    db_query($sql, $data);
    return $pdo->lastInsertId();
}

/**
 * Cập nhật dữ liệu
 */
function db_update($table, $data, $where, $whereParams = []) {
    $set = [];
    foreach (array_keys($data) as $field) {
        $set[] = "$field = :$field";
    }
    
    $sql = sprintf(
        'UPDATE %s SET %s WHERE %s',
        $table,
        implode(', ', $set),
        $where
    );
    
    return db_query($sql, array_merge($data, $whereParams))->rowCount();
}

/**
 * Xóa dữ liệu
 */
function db_delete($table, $where, $params = []) {
    $sql = "DELETE FROM $table WHERE $where";
    return db_query($sql, $params)->rowCount();
}

// Tự động đóng kết nối khi script kết thúc
register_shutdown_function(function() {
    global $pdo;
    $pdo = null;
});
