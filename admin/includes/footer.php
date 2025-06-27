            </main>
        </div>
    </div>

    <script>
        // Kích hoạt dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            // Kích hoạt tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Xác nhận trước khi xóa
            document.querySelectorAll('.confirm-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Bạn có chắc chắn muốn xóa mục này không?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
