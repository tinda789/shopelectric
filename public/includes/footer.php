    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-about">
                        <a href="/" class="d-inline-block mb-3">
                            <img src="/assets/images/logo-white.png" alt="ShopElectrics" height="40">
                        </a>
                        <p class="text-white-50 mb-3">
                            ShopElectrics - Địa chỉ mua sắm điện tử tin cậy với đa dạng sản phẩm công nghệ, 
                            phụ kiện chính hãng, giá tốt nhất thị trường.
                        </p>
                        <div class="social-links">
                            <a href="#" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" target="_blank" title="Youtube"><i class="bi bi-youtube"></i></a>
                            <a href="#" target="_blank" title="Zalo"><i class="bi bi-chat-dots"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5>Về chúng tôi</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="/about.php">Giới thiệu</a></li>
                        <li><a href="/contact.php">Liên hệ</a></li>
                        <li><a href="/blog.php">Tin tức</a></li>
                        <li><a href="/careers.php">Tuyển dụng</a></li>
                        <li><a href="/stores.php">Hệ thống cửa hàng</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5>Hỗ trợ</h5>
                    <ul class="footer-links list-unstyled">
                        <li><a href="/faq.php">Câu hỏi thường gặp</a></li>
                        <li><a href="/shipping-policy.php">Chính sách vận chuyển</a></li>
                        <li><a href="/return-policy.php">Chính sách đổi trả</a></li>
                        <li><a href="/privacy-policy.php">Chính sách bảo mật</a></li>
                        <li><a href="/terms.php">Điều khoản sử dụng</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h5>Liên hệ</h5>
                    <ul class="footer-contact list-unstyled text-white-50">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i> 123 Đường ABC, Phường XYZ, Quận 1, TP.HCM
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2"></i> <a href="tel:0123456789" class="text-white-50">0123 456 789</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2"></i> <a href="mailto:contact@shopelectrics.com" class="text-white-50">contact@shopelectrics.com</a>
                        </li>
                        <li class="mt-4">
                            <h6>Đăng ký nhận tin khuyến mãi</h6>
                            <form class="subscribe-form" action="/subscribe.php" method="post">
                                <div class="input-group mb-3">
                                    <input type="email" class="form-control" placeholder="Email của bạn" aria-label="Email" required>
                                    <button class="btn btn-primary" type="submit">Đăng ký</button>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="mt-4 mb-4 bg-white-10">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-white-50">&copy; <?= date('Y') ?> ShopElectrics. Bảo lưu mọi quyền.</p>
                </div>
                <div class="col-md-6">
                    <div class="payment-methods text-center text-md-end">
                        <span class="me-2 text-white-50">Chấp nhận thanh toán:</span>
                        <img src="/assets/images/payment-methods.png" alt="Payment Methods" height="30" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary btn-lg back-to-top" id="backToTop" title="Lên đầu trang">
        <i class="bi bi-arrow-up"></i>
    </a>
    
    <!-- Shopping Cart Sidebar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar" aria-labelledby="cartSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="cartSidebarLabel">Giỏ hàng của bạn</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-items">
                <div class="text-center py-5">
                    <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Giỏ hàng của bạn đang trống</p>
                    <a href="/products.php" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
                </div>
            </div>
            <div class="cart-summary d-none">
                <div class="d-flex justify-content-between mb-3">
                    <span>Tạm tính:</span>
                    <span class="cart-subtotal fw-bold">0₫</span>
                </div>
                <div class="d-grid">
                    <a href="/checkout.php" class="btn btn-primary">Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-4">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/main.js"></script>
    
    <script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Back to top button
    var backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Show/hide back to top button on load
    window.addEventListener('load', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('show');
        }
    });
    
    // Initialize dropdown submenu on hover
    document.querySelectorAll('.dropdown-menu a.dropdown-toggle').forEach(function(element) {
        element.addEventListener('mouseenter', function(e) {
            var dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                var parentDropdown = this.closest('.dropdown-submenu');
                if (parentDropdown) {
                    parentDropdown.classList.add('show');
                    dropdownMenu.classList.add('show');
                }
            }
        });
        
        element.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                e.stopPropagation();
                var dropdownMenu = this.nextElementSibling;
                if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                    dropdownMenu.classList.toggle('show');
                }
            }
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-menu') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                menu.classList.remove('show');
            });
        }
    });
    
    // Show toast messages if any
    <?php if (isset($_SESSION['success_message'])): ?>
        toastr.success('<?= addslashes($_SESSION['success_message']) ?>');
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        toastr.error('<?= addslashes($_SESSION['error_message']) ?>');
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['warning_message'])): ?>
        toastr.warning('<?= addslashes($_SESSION['warning_message']) ?>');
        <?php unset($_SESSION['warning_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['info_message'])): ?>
        toastr.info('<?= addslashes($_SESSION['info_message']) ?>');
        <?php unset($_SESSION['info_message']); ?>
    <?php endif; ?>
    </script>
</body>
</html>
