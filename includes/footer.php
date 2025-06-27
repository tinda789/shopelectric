        <footer class="mt-5 py-4 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="mb-0">© 2025 ShopElectrics. Tất cả các quyền được bảo lưu.</p>
                        <p class="mb-0 small text-muted">
                            <a href="/about" class="text-muted text-decoration-none me-2">Giới thiệu</a> | 
                            <a href="/contact" class="text-muted text-decoration-none mx-2">Liên hệ</a> | 
                            <a href="/privacy" class="text-muted text-decoration-none mx-2">Chính sách bảo mật</a> | 
                            <a href="/terms" class="text-muted text-decoration-none ms-2">Điều khoản sử dụng</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script src="/assets/js/auth.js"></script>
    
    <script>
    // Bật tooltip Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Bật popover
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });
    </script>
</body>
</html>
