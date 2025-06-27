<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Xử lý thêm danh mục mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    
    if (!empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            
            $_SESSION['success_message'] = 'Thêm danh mục thành công!';
            header('Location: /controllers/categories.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
            exit;
        } catch (PDOException $e) {
            $error = 'Lỗi khi thêm danh mục: ' . $e->getMessage();
        }
    } else {
        $error = 'Vui lòng nhập tên danh mục';
    }
}

// Xử lý tham số tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Xử lý tham số sắp xếp
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$order = isset($_GET['order']) && strtoupper($_GET['order']) === 'DESC' ? 'DESC' : 'ASC';

// Các cột được phép sắp xếp
$allowed_sort_columns = ['id', 'name', 'created_at'];
if (!in_array($sort, $allowed_sort_columns)) {
    $sort = 'name';
}

// Xây dựng câu truy vấn SQL
$sql = "SELECT c.*, 
       (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count 
       FROM categories c";
$where = [];
$params = [];

// Thêm điều kiện tìm kiếm
if (!empty($search)) {
    $where[] = "(c.name LIKE ? OR c.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Thêm điều kiện WHERE nếu có
if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

// Thêm sắp xếp
$sql .= " ORDER BY $sort $order";

// Thực hiện đếm tổng số bản ghi cho phân trang
$count_sql = "SELECT COUNT(*) as total FROM categories c" . (!empty($where) ? ' WHERE ' . implode(' AND ', $where) : '');
$total_records = $pdo->prepare($count_sql);
$total_records->execute($params);
$total_records = $total_records->fetchColumn();

// Phân trang
$records_per_page = 10;
$total_pages = ceil($total_records / $records_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;
$offset = ($current_page - 1) * $records_per_page;

// Thêm phân trang vào câu truy vấn
$sql .= " LIMIT $offset, $records_per_page";

// Thực hiện truy vấn
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$categories = $stmt->fetchAll(PDO::FETCH_OBJ);

// Tạo URL với các tham số hiện tại
function buildUrl($params = []) {
    $query = $_GET;
    foreach ($params as $key => $value) {
        $query[$key] = $value;
    }
    return '/controllers/categories.php?' . http_build_query($query);
}

// Include view
require_once __DIR__ . '/../views/categories/index.php';
