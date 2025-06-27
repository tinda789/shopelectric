<?php
require_once 'includes/init.php';

// Nếu đã đăng nhập, chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';
$remember = false;

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    $redirect = $_POST['redirect'] ?? 'index.php';
    
    if (empty($username) || empty($password)) {
        $error = 'Vui lòng nhập tên đăng nhập và mật khẩu';
    } else {
        try {
            // Tìm user theo username hoặc email
            $stmt = $pdo->prepare("
                SELECT u.*, r.name as role_name, u.first_name, u.last_name
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.username = ? OR u.email = ? 
                LIMIT 1
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($user && password_verify($password, $user->password_hash)) {
                // Đăng nhập thành công
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['email'] = $user->email;
                $_SESSION['first_name'] = $user->first_name;
                $_SESSION['last_name'] = $user->last_name;
                $_SESSION['is_admin'] = ($user->role_name === 'admin');
                
                // Cập nhật last_login
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user->id]);
                
                // Nếu chọn "Ghi nhớ đăng nhập"
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                    
                    // Xóa token cũ nếu có
                    $stmt = $pdo->prepare("DELETE FROM user_tokens WHERE user_id = ?");
                    $stmt->execute([$user['id']]);
                    
                    // Lưu token mới vào database
                    $stmt = $pdo->prepare("INSERT INTO user_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
                    if ($stmt->execute([$user['id'], $token, $expires])) {
                        setcookie('remember_token', $token, [
                            'expires' => strtotime('+30 days'),
                            'path' => '/',
                            'domain' => '',
                            'secure' => isset($_SERVER['HTTPS']),
                            'httponly' => true,
                            'samesite' => 'Lax'
                        ]);
                    }
                } else {
                    // Xóa cookie remember nếu có
                    setcookie('remember_token', '', time() - 3600, '/');
                }
                
                // Chuyển hướng về trang trước đó hoặc trang chủ
                header('Location: ' . $redirect);
                exit();
            } else {
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
            }
        } catch (PDOException $e) {
            error_log('Lỗi đăng nhập: ' . $e->getMessage());
            $error = 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại sau.';
        }
    }
} else {
    // Nếu là GET request, lấy redirect URL nếu có
    $redirect = $_GET['redirect'] ?? 'index.php';
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</h3>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['registered'])): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>Đăng ký tài khoản thành công! Vui lòng đăng nhập.
                        </div>
                    <?php endif; ?>
                    
                    <form action="login.php" method="post" class="needs-validation" novalidate>
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect ?? 'index.php') ?>">
                        
                        <div class="mb-4">
                            <label for="username" class="form-label fw-medium">Tên đăng nhập hoặc Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" 
                                       class="form-control form-control-lg <?= $error ? 'is-invalid' : '' ?>" 
                                       id="username" 
                                       name="username" 
                                       value="<?= htmlspecialchars($username) ?>" 
                                       placeholder="Nhập tên đăng nhập hoặc email" 
                                       required>
                                <div class="invalid-feedback">
                                    Vui lòng nhập tên đăng nhập hoặc email
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <label for="password" class="form-label fw-medium">Mật khẩu <span class="text-danger">*</span></label>
                                <a href="forgot-password.php" class="text-decoration-none small">Quên mật khẩu?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" 
                                       class="form-control form-control-lg <?= $error ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Nhập mật khẩu" 
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Vui lòng nhập mật khẩu
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" <?= $remember ? 'checked' : '' ?>>
                            <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-2 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                        </button>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">Chưa có tài khoản? 
                                <a href="register.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>" class="text-primary fw-medium">
                                    Đăng ký ngay
                                </a>
                            </p>
                        </div>
                        
                        <div class="position-relative my-4">
                            <hr>
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                hoặc đăng nhập bằng
                            </div>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="#" class="btn btn-outline-dark w-100">
                                    <i class="bi bi-google me-2"></i>Google
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="#" class="btn btn-primary w-100" style="background-color: #1877f2; border-color: #1877f2;">
                                    <i class="bi bi-facebook me-2"></i>Facebook
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
