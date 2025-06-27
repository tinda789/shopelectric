document.addEventListener('DOMContentLoaded', function() {
    // Toggle hiển thị mật khẩu
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
            
            // Giữ focus vào input sau khi toggle
            input.focus();
            // Di chuyển con trỏ về cuối nội dung
            const len = input.value.length;
            input.setSelectionRange(len, len);
        });
        
        // Thêm sự kiện keydown để xử lý phím Enter
        const input = btn.previousElementSibling;
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && input.type === 'password') {
                btn.click();
            }
        });
    });

    // Kiểm tra độ mạnh mật khẩu
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        const passwordStrength = document.createElement('div');
        passwordStrength.className = 'password-strength mt-2';
        passwordStrength.innerHTML = '<div class="password-strength-bar"></div>';
        passwordInput.parentNode.insertBefore(passwordStrength, passwordInput.nextSibling);

        passwordInput.addEventListener('input', function() {
            const strengthBar = this.nextElementSibling.querySelector('.password-strength-bar');
            const strength = calculatePasswordStrength(this.value);
            
            // Cập nhật thanh độ mạnh
            strengthBar.style.width = (strength.score * 25) + '%';
            
            // Đổi màu dựa trên độ mạnh
            if (strength.score < 2) {
                strengthBar.style.backgroundColor = '#dc3545'; // Đỏ
            } else if (strength.score < 4) {
                strengthBar.style.backgroundColor = '#ffc107'; // Vàng
            } else {
                strengthBar.style.backgroundColor = '#198754'; // Xanh lá
            }
            
            // Hiển thị gợi ý nếu có
            const feedback = this.nextElementSibling.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = strength.feedback || '';
            }
        });
    }
    
    // Xử lý form đăng nhập
    const loginForm = document.querySelector('form[action*="login"]');
    if (loginForm) {
        // Thêm lớp was-validated khi submit form
        loginForm.addEventListener('submit', function(e) {
            if (!loginForm.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            loginForm.classList.add('was-validated');
        }, false);
        
        // Xử lý khi người dùng nhập vào các trường
        const usernameInput = loginForm.querySelector('input[name="username"]');
        const passwordInput = loginForm.querySelector('input[name="password"]');
        const rememberCheckbox = loginForm.querySelector('input[name="remember"]');
        
        // Tự động focus vào trường username nếu trống
        if (usernameInput && !usernameInput.value.trim()) {
            setTimeout(() => {
                usernameInput.focus();
            }, 100);
        }
        
        // Xử lý khi nhấn Enter trong trường password
        if (passwordInput) {
            passwordInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && usernameInput.value.trim() && this.value.trim()) {
                    loginForm.dispatchEvent(new Event('submit'));
                }
            });
        }
        
        // Thêm hiệu ứng loading khi submit form
        loginForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý...';
            }
        });
    }
    
    // Xác thực form đăng ký
    const registerForm = document.querySelector('form[action*="register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Kiểm tra mật khẩu khớp
            const password = this.querySelector('#password');
            const confirmPassword = this.querySelector('#confirm_password');
            
            if (password && confirmPassword && password.value !== confirmPassword.value) {
                if (!confirmPassword.nextElementSibling || !confirmPassword.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Mật khẩu xác nhận không khớp';
                    confirmPassword.parentNode.insertBefore(errorDiv, confirmPassword.nextSibling);
                }
                confirmPassword.classList.add('is-invalid');
                isValid = false;
            } else if (confirmPassword) {
                confirmPassword.classList.remove('is-invalid');
            }
            
            // Đánh dấu các trường bắt buộc
            this.querySelectorAll('[required]').forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            this.classList.add('was-validated');
        });
    }
});

    // Xử lý tự động ẩn thông báo lỗi sau 5 giây
    const alertMessages = document.querySelectorAll('.alert.alert-dismissible');
    if (alertMessages.length > 0) {
        setTimeout(() => {
            alertMessages.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    }
    
    // Thêm hiệu ứng fade in cho các thẻ card
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });

    // Thêm sự kiện cho nút đăng xuất
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Hiển thị xác nhận trước khi đăng xuất
            if (confirm('Bạn có chắc chắn muốn đăng xuất không?')) {
                window.location.href = this.href;
            }
        });
    }

    // Hàm tính độ mạnh mật khẩu
    function calculatePasswordStrength(password) {
    let score = 0;
    let feedback = [];
    
    // Kiểm tra độ dài
    if (password.length >= 8) score++;
    if (password.length < 6) feedback.push('Mật khẩu quá ngắn (tối thiểu 6 ký tự)');
    
    // Kiểm tra chữ hoa, chữ thường, số và ký tự đặc biệt
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    // Giới hạn điểm số tối đa là 5
    score = Math.min(score, 5);
    
    // Thêm gợi ý nếu điểm thấp
    if (score < 3) {
        feedback.push('Thử thêm chữ hoa, số hoặc ký tự đặc biệt');
    }
    
    return {
        score: score,
        feedback: feedback.join(' ')
    };
}
