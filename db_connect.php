<?php
$servername = "db";  // Tên service trong docker-compose.yml
$username = "user";    // Tên người dùng MySQL
$password = "secret";  // Mật khẩu MySQL
$dbname = "shopelectrics";  // Tên cơ sở dữ liệu

try {
    // Tạo kết nối
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Đặt chế độ lỗi PDO thành ngoại lệ
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Kết nối thành công đến cơ sở dữ liệu $dbname";
} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
}
?>
