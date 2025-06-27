<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Lấy danh sách danh mục và thương hiệu cho filter
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();
$brands = $pdo->query("SELECT id, name FROM brands ORDER BY name")->fetchAll();

// Xử lý tham số tìm kiếm và lọc
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$brand_id = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
$min_quantity = isset($_GET['min_quantity']) ? (int)$_GET['min_quantity'] : null;
$max_quantity = isset($_GET['max_quantity']) ? (int)$_GET['max_quantity'] : null;

// Xử lý tham số sắp xếp
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';

// Các cột được phép sắp xếp
$allowed_sort_columns = ['id', 'name', 'price', 'stock_quantity', 'created_at'];
if (!in_array($sort, $allowed_sort_columns)) {
    $sort = 'id';
}

// Xây dựng câu truy vấn SQL
$sql = "SELECT 
            p.id, p.name, p.price, p.stock_quantity, p.created_at, p.status,
            c.id as category_id, c.name as category_name, 
            b.id as brand_id, b.name as brand_name,
            (SELECT image_url FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        LEFT JOIN brands b ON p.brand_id = b.id 
        WHERE 1=1";

$where = [];
$params = [];

// Thêm điều kiện tìm kiếm
if (!empty($search)) {
    $where[] = "(p.name LIKE ? OR p.description LIKE ? OR p.sku LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Thêm điều kiện lọc theo danh mục
if ($category_id > 0) {
    $where[] = "p.category_id = ?";
    $params[] = $category_id;
}

// Thêm điều kiện lọc theo thương hiệu
if ($brand_id > 0) {
    $where[] = "p.brand_id = ?";
    $params[] = $brand_id;
}

// Thêm điều kiện lọc theo khoảng giá
if ($min_price !== null) {
    $where[] = "p.price >= ?";
    $params[] = $min_price;
}
if ($max_price !== null) {
    $where[] = "p.price <= ?";
    $params[] = $max_price;
}

// Thêm điều kiện lọc theo số lượng tồn kho
if ($min_quantity !== null) {
    $where[] = "p.stock_quantity >= ?";
    $params[] = $min_quantity;
}
if ($max_quantity !== null) {
    $where[] = "p.stock_quantity <= ?";
    $params[] = $max_quantity;
}

// Thêm điều kiện WHERE nếu có
if (!empty($where)) {
    $sql .= " AND " . implode(' AND ', $where);
}

// Thêm sắp xếp
$sql .= " ORDER BY $sort $order";

// Thực hiện đếm tổng số bản ghi cho phân trang
$count_sql = "SELECT COUNT(*) as total FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              LEFT JOIN brands b ON p.brand_id = b.id 
              WHERE 1=1" . 
             (!empty($where) ? ' AND ' . implode(' AND ', $where) : '');

$stmt = $pdo->prepare($count_sql);
$stmt->execute($params);
$total_products = $stmt->fetchColumn();

// Phân trang
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$per_page = in_array($per_page, [10, 25, 50, 100]) ? $per_page : 10;
$total_pages = ceil($total_products / $per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, $total_pages));
$offset = ($current_page - 1) * $per_page;

// Thêm phân trang vào câu truy vấn
$sql .= " LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;

// Thực hiện truy vấn lấy dữ liệu sản phẩm
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_OBJ);

// Đảm bảo $total_products luôn có giá trị
$total_products = $total_products ?? 0;

// Hàm tạo URL với các tham số hiện tại
function buildUrl($params = []) {
    $query = $_GET;
    unset($query['_']); // Loại bỏ tham số _ nếu có
    
    // Cập nhật các tham số mới
    foreach ($params as $key => $value) {
        // Nếu giá trị là null, xóa tham số đó
        if ($value === null) {
            unset($query[$key]);
        } else {
            $query[$key] = $value;
        }
    }
    
    // Xóa tham số page nếu đang ở trang 1 để URL gọn gàng hơn
    if (isset($query['page']) && $query['page'] == 1) {
        unset($query['page']);
    }
    
    return '/controllers/products.php' . (!empty($query) ? '?' . http_build_query($query) : '');
}

// Include view
require_once __DIR__ . '/../views/products/index.php';
