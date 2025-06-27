<?php
require_once __DIR__ . '/../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Vui lòng đăng nhập để xem hồ sơ';
    header('Location: /controllers/login.php');
    exit;
}

// Lấy thông tin người dùng hiện tại
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (!$user) {
    $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng';
    header('Location: /controllers/login.php');
    exit;
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    // Validate dữ liệu
    if (empty($first_name)) {
        $errors[] = 'Vui lòng nhập họ';
    }
    
    if (empty($last_name)) {
        $errors[] = 'Vui lòng nhập tên';
    }
    
    if (empty($email)) {
        $errors[] = 'Vui lòng nhập email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ';
    } else {
        // Kiểm tra email đã tồn tại chưa (trừ tài khoản hiện tại)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->fetch()) {
            $errors[] = 'Email đã được sử dụng bởi tài khoản khác';
        }
    }
    
    // Nếu có thay đổi mật khẩu
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (empty($current_password)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
        } elseif (!password_verify($current_password, $user->password)) {
            $errors[] = 'Mật khẩu hiện tại không chính xác';
        }
        
        if (empty($new_password)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới';
        } elseif (strlen($new_password) < 6) {
            $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = 'Xác nhận mật khẩu không khớp';
        }
    }
    
    // Nếu không có lỗi, cập nhật thông tin
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Cập nhật thông tin cơ bản
            $update_data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Nếu có đổi mật khẩu
            if (!empty($new_password)) {
                $update_data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
            }
            
            // Tạo câu lệnh SQL động
            $set_parts = [];
            $params = [];
            foreach ($update_data as $key => $value) {
                $set_parts[] = "$key = ?";
                $params[] = $value;
            }
            $params[] = $user_id; // Điều kiện WHERE
            
            $sql = "UPDATE users SET " . implode(', ', $set_parts) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            // Cập nhật thông tin trong session
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            
            $pdo->commit();
            
            $_SESSION['success_message'] = 'Cập nhật thông tin thành công';
            header('Location: /controllers/profile.php');
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Có lỗi xảy ra khi cập nhật thông tin: ' . $e->getMessage();
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode('<br>', $errors);
    }
}

// Đặt tiêu đề trang
$page_title = 'Hồ sơ cá nhân';

// Include header
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->first_name . ' ' . $user->last_name) ?>&size=150&background=0D6EFD&color=fff" 
                             alt="Avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <button class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle" 
                                style="width: 36px; height: 36px;" title="Đổi ảnh đại diện">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>
                    <h5 class="my-3"><?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?></h5>
                    <p class="text-muted mb-1">
                        <i class="bi bi-envelope me-2"></i> <?= htmlspecialchars($user->email) ?>
                    </p>
                    <?php if (!empty($user->phone)): ?>
                    <p class="text-muted mb-1">
                        <i class="bi bi-telephone me-2"></i> <?= htmlspecialchars($user->phone) ?>
                    </p>
                    <?php endif; ?>
                    <p class="text-muted mb-0">
                        <i class="bi bi-calendar3 me-2"></i> Tham gia từ <?= date('d/m/Y', strtotime($user->created_at)) ?>
                    </p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tùy chọn</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <a href="/controllers/orders.php" class="text-decoration-none">
                                <i class="bi bi-cart-check me-2"></i> Đơn hàng của tôi
                            </a>
                            <span class="badge bg-primary rounded-pill">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-heart me-2"></i> Sản phẩm yêu thích
                            </a>
                            <span class="badge bg-primary rounded-pill">0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-gear me-2"></i> Cài đặt tài khoản
                            </a>
                        </li>
                        <li class="list-group-item px-0">
                            <a href="/controllers/logout.php" class="text-danger text-decoration-none">
                                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin cá nhân</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error_message'] ?>
                            <?php unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['success_message'] ?>
                            <?php unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="/controllers/profile.php">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Họ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= htmlspecialchars($user->first_name) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= htmlspecialchars($user->last_name) ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user->email) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($user->phone ?? '') ?>">
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-3">Đổi mật khẩu</h5>
                        <p class="text-muted small mb-4">Để trống nếu không muốn đổi mật khẩu</p>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <div class="form-text">Tối thiểu 6 ký tự</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hiển thị/hide mật khẩu
    function setupPasswordToggle(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);
        
        if (toggleButton) {
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bi-eye');
                this.querySelector('i').classList.toggle('bi-eye-slash');
            });
        }
    }
    
    // Áp dụng cho các trường mật khẩu
    setupPasswordToggle('current_password', 'toggleCurrentPassword');
    setupPasswordToggle('new_password', 'toggleNewPassword');
    setupPasswordToggle('confirm_password', 'toggleConfirmPassword');
    
    // Xử lý form đổi mật khẩu
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Xác nhận mật khẩu không khớp!');
            }
        });
    }
});
</script>

<?php
// Include footer
require_once __DIR__ . '/../includes/footer.php';
?>
