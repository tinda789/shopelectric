<?php
require_once 'includes/init.php';

// Nếu đã đăng nhập thì chuyển hướng về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$success = false;

// Lấy dữ liệu từ form nếu có
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$username = trim($_POST['username'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate dữ liệu
    if (empty($name)) {
        $errors['name'] = 'Vui lòng nhập họ tên';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Vui lòng nhập email hợp lệ';
    }
    
    if (empty($username)) {
        $errors['username'] = 'Vui lòng nhập tên đăng nhập';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{4,50}$/', $username)) {
        $errors['username'] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới, từ 4-50 ký tự';
    }
    
    if (strlen($password) < 6) {
        $errors['password'] = 'Mật khẩu phải có ít nhất 6 ký tự';
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp';
    }
    
    // Kiểm tra email và username đã tồn tại chưa
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($user = $stmt->fetch()) {
            if (strtolower($user['email']) === strtolower($email)) {
                $errors['email'] = 'Email này đã được đăng ký';
            }
            if (strtolower($user['username']) === strtolower($username)) {
                $errors['username'] = 'Tên đăng nhập đã được sử dụng';
            }
        }
    }
    
    // Nếu không có lỗi, thêm user mới
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $pdo->beginTransaction();
            
            // Thêm user mới
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password_hash, first_name, last_name, phone, is_active, email_verified, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, 1, 1, NOW(), NOW())
            ");
            
            // Tách họ và tên
            $name_parts = explode(' ', $name, 2);
            $first_name = $name_parts[0] ?? '';
            $last_name = $name_parts[1] ?? '';
            
            $stmt->execute([
                $username,
                $email,
                $hashed_password,
                $first_name,
                $last_name,
                $phone
            ]);
            
            $user_id = $pdo->lastInsertId();
            
            // Gán quyền mặc định là user (role_id = 2)
            $stmt = $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, 2)");
            $stmt->execute([$user_id]);
            
            $pdo->commit();
            
            // Đăng nhập tự động sau khi đăng ký thành công
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['is_admin'] = false;
            
            // Chuyển hướng về trang chủ
            header('Location: index.php?registered=1');
            exit;
            
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $errors[] = 'Có lỗi xảy ra khi đăng ký tài khoản. Vui lòng thử lại sau.';
            error_log('Lỗi đăng ký: ' . $e->getMessage());
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="bi bi-person-plus me-2"></i>Đăng ký tài khoản</h3>
                </div>
                <div class="card-body p-4">
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            Đăng ký thành công! Bạn sẽ được chuyển hướng về trang chủ.
                        </div>
                    <?php else: ?>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                       id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" 
                                           id="username" name="username" 
                                           value="<?= htmlspecialchars($username) ?>" 
                                           pattern="[a-zA-Z0-9_]+" 
                                           title="Chỉ chấp nhận chữ cái, số và dấu gạch dưới"
                                           required>
                                </div>
                                <div class="form-text">Chỉ sử dụng chữ cái, số và dấu gạch dưới, từ 4-50 ký tự</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       id="phone" name="phone" value="<?= htmlspecialchars($phone) ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                                       id="password" name="password" required>
                                <div class="form-text">Ít nhất 6 ký tự</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                                       id="confirm_password" name="confirm_password" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
                            </div>
                            
                            <div class="text-center mt-3">
                                Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
