<?php
require_once 'includes/init.php';

// Lưu lại URL trước đó để chuyển hướng lại sau khi đăng nhập
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';
$login_url = 'login.php';

// Xử lý đăng xuất
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
    // Xóa token ghi nhớ đăng nhập nếu có
    if (isset($_COOKIE['remember_token'])) {
        try {
            // Xóa token từ database
            $stmt = $pdo->prepare("DELETE FROM user_tokens WHERE token = ?");
            $stmt->execute([$_COOKIE['remember_token']]);
        } catch (PDOException $e) {
            error_log('Lỗi khi xóa token đăng nhập: ' . $e->getMessage());
        }
        
        // Xóa cookie
        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }
    
    // Hủy session
    $_SESSION = [];
    
    // Nếu muốn xóa cả cookie session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // Hủy session
    session_destroy();
    
    // Tạo token CSRF mới cho lần đăng nhập tiếp theo
    if (function_exists('generate_csrf_token')) {
        generate_csrf_token(true);
    }
    
    // Chuyển hướng về trang đăng nhập với thông báo
    $_SESSION['success_message'] = 'Bạn đã đăng xuất thành công.';
    
    // Nếu đang ở trang yêu cầu đăng nhập, chuyển về trang chủ
    if (strpos($redirect, 'login.php') !== false || 
        strpos($redirect, 'register.php') !== false ||
        strpos($redirect, 'logout.php') !== false) {
        $redirect = 'index.php';
    }
    
    header('Location: ' . $login_url . '?redirect=' . urlencode($redirect));
    exit();
} else {
    // Nếu là GET request, hiển thị trang xác nhận
    $page_title = 'Xác nhận đăng xuất';
    include 'includes/header.php';
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0"><i class="bi bi-box-arrow-right me-2"></i>Xác nhận đăng xuất</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bi bi-question-circle display-1 text-primary mb-3"></i>
                            <h5>Bạn có chắc chắn muốn đăng xuất?</h5>
                            <p class="text-muted">Bạn sẽ cần đăng nhập lại để tiếp tục sử dụng tài khoản.</p>
                        </div>
                        
                        <form method="post" action="logout.php">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                                <a href="<?= htmlspecialchars($redirect) ?>" class="btn btn-outline-secondary btn-lg">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'includes/footer.php';
}
