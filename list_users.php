<?php
require_once 'config/database.php';

try {
    // Lấy danh sách người dùng
    $stmt = $pdo->query("SELECT id, username, email, first_name, last_name FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "Không tìm thấy người dùng nào.\n";
        exit;
    }
    
    // Hiển thị tiêu đề
    echo str_pad("ID", 5) . str_pad("Username", 20) . str_pad("Email", 30) . "Tên đầy đủ\n";
    echo str_repeat("-", 70) . "\n";
    
    // Hiển thị từng người dùng
    foreach ($users as $user) {
        echo str_pad($user['id'], 5) . 
             str_pad($user['username'] ?? 'NULL', 20) . 
             str_pad($user['email'] ?? 'NULL', 30) . 
             ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '') . "\n";
    }
    
} catch (PDOException $e) {
    die("Lỗi: " . $e->getMessage() . "\n");
}
