<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý Sản phẩm</h2>
                <a href="/controllers/products/create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                </a>
            </div>
            
            <!-- Form tìm kiếm và lọc -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" action="" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm kiếm tên/mô tả/SKU...">
                        </div>
                        <div class="col-md-2">
                            <select name="category_id" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>" <?= $category_id == $cat->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="brand_id" class="form-select">
                                <option value="">Tất cả thương hiệu</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand->id ?>" <?= $brand_id == $brand->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="min_price" value="<?= htmlspecialchars($min_price ?? '') ?>" placeholder="Giá từ" min="0">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="max_price" value="<?= htmlspecialchars($max_price ?? '') ?>" placeholder="Đến giá" min="0">
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="number" class="form-control" name="min_quantity" value="<?= htmlspecialchars($min_quantity ?? '') ?>" placeholder="SL từ" min="0">
                                <input type="number" class="form-control" name="max_quantity" value="<?= htmlspecialchars($max_quantity ?? '') ?>" placeholder="Đến SL" min="0">
                                <button class="btn btn-outline-primary" type="submit">
                                    <i class="bi bi-funnel"></i> Lọc
                                </button>
                                <?php if ($search || $category_id || $brand_id || $min_price !== null || $max_price !== null || $min_quantity !== null || $max_quantity !== null): ?>
                                    <a href="/controllers/products.php" class="btn btn-outline-danger">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="<?= buildUrl(['sort' => 'id', 'order' => $sort === 'id' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            ID
                                            <?php if ($sort === 'id'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Hình ảnh</th>
                                    <th>
                                        <a href="<?= buildUrl(['sort' => 'name', 'order' => $sort === 'name' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Tên sản phẩm
                                            <?php if ($sort === 'name'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Danh mục</th>
                                    <th>Thương hiệu</th>
                                    <th class="text-end">
                                        <a href="<?= buildUrl(['sort' => 'price', 'order' => $sort === 'price' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Giá
                                            <?php if ($sort === 'price'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th class="text-center">
                                        <a href="<?= buildUrl(['sort' => 'stock_quantity', 'order' => $sort === 'stock_quantity' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Tồn kho
                                            <?php if ($sort === 'stock_quantity'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="<?= buildUrl(['sort' => 'created_at', 'order' => $sort === 'created_at' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Ngày tạo
                                            <?php if ($sort === 'created_at'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Trạng thái</th>
                                    <th style="width: 150px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center">Không tìm thấy sản phẩm nào</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= $product->id ?></td>
                                            <td>
                                                <?php if (!empty($product->image)): ?>
                                                    <img src="/uploads/products/<?= htmlspecialchars($product->image) ?>" alt="" class="img-thumbnail" style="max-width: 60px;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($product->name) ?></div>
                                                <small class="text-muted">SKU: <?= htmlspecialchars($product->sku ?? 'N/A') ?></small>
                                            </td>
                                            <td>
                                                <?php if ($product->category_id): ?>
                                                    <a href="<?= buildUrl(['category_id' => $product->category_id]) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($product->category_name) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($product->brand_id): ?>
                                                    <a href="<?= buildUrl(['brand_id' => $product->brand_id]) ?>" class="text-decoration-none">
                                                        <?= htmlspecialchars($product->brand_name) ?>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                <?= number_format($product->price, 0, ',', '.') ?> đ
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-<?= $product->stock_quantity > 0 ? 'success' : 'danger' ?>">
                                                    <?= number_format($product->stock_quantity, 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div><?= date('d/m/Y', strtotime($product->created_at)) ?></div>
                                                <small class="text-muted"><?= date('H:i', strtotime($product->created_at)) ?></small>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $product->status === 'active' ? 'success' : ($product->status === 'out_of_stock' ? 'warning' : 'secondary') ?>">
                                                    <?= $product->status === 'active' ? 'Đang bán' : ($product->status === 'out_of_stock' ? 'Hết hàng' : 'Ngừng kinh doanh') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="/controllers/products/edit.php?id=<?= $product->id ?>" 
                                                       class="btn btn-outline-primary me-1" 
                                                       data-bs-toggle="tooltip" 
                                                       title="Chỉnh sửa">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-outline-danger delete-product" 
                                                            data-id="<?= $product->id ?>"
                                                            data-name="<?= htmlspecialchars($product->name) ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="Xóa sản phẩm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị <strong><?= ($current_page - 1) * $per_page + 1 ?> - <?= min($current_page * $per_page, $total_products) ?></strong> trên tổng số <strong><?= number_format($total_products, 0, ',', '.') ?></strong> sản phẩm
                        </div>
                        
                        <?php if ($total_pages > 1): ?>
                            <div class="d-flex align-items-center">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination mb-0">
                                        <!-- Nút Trang đầu -->
                                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildUrl(['page' => 1]) ?>" title="Trang đầu">
                                                <i class="bi bi-chevron-double-left"></i>
                                            </a>
                                        </li>
                                        
                                        <!-- Nút Trang trước -->
                                        <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildUrl(['page' => $current_page - 1]) ?>" title="Trang trước">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                        
                                        <!-- Các trang -->
                                        <?php
                                        // Hiển thị tối đa 5 trang xung quanh trang hiện tại
                                        $startPage = max(1, $current_page - 2);
                                        $endPage = min($total_pages, $startPage + 4);
                                        $startPage = max(1, $endPage - 4);
                                        
                                        // Hiển thị dấu ... nếu cần
                                        if ($startPage > 1) {
                                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                                        }
                                        
                                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= buildUrl(['page' => $i]) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        
                                        <?php if ($endPage < $total_pages): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                        
                                        <!-- Nút Trang sau -->
                                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildUrl(['page' => $current_page + 1]) ?>" title="Trang sau">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                        
                                        <!-- Nút Trang cuối -->
                                        <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= buildUrl(['page' => $total_pages]) ?>" title="Trang cuối">
                                                <i class="bi bi-chevron-double-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                                
                                <div class="d-flex align-items-center ms-3">
                                    <span class="me-2">Hiển thị:</span>
                                    <select class="form-select form-select-sm" id="per_page" style="width: auto;">
                                        <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                                        <option value="25" <?= $per_page == 25 ? 'selected' : '' ?>>25</option>
                                        <option value="50" <?= $per_page == 50 ? 'selected' : '' ?>>50</option>
                                        <option value="100" <?= $per_page == 100 ? 'selected' : '' ?>>100</option>
                                    </select>
                                    <span class="ms-2">sản phẩm/trang</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="delete_product_name"></strong>?</p>
                <p class="text-danger"><small>Lưu ý: Hành động này không thể hoàn tác và sẽ xóa tất cả hình ảnh liên quan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="post" action="/controllers/products/delete.php" class="d-inline">
                    <input type="hidden" name="product_id" id="delete_product_id">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Khởi tạo tooltip
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Hàm hiển thị thông báo
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra nếu có thông báo lỗi hoặc thành công
    <?php if (isset($_SESSION['error_message'])): ?>
        showToast('<?= addslashes($_SESSION['error_message']) ?>', 'error');
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        showToast('<?= addslashes($_SESSION['success_message']) ?>', 'success');
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
});

// Hàm hiển thị toast message
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 show`;
    toast.role = 'alert';
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Tự động ẩn thông báo sau 5 giây
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 150);
    }, 5000);
}

// Xử lý sự kiện khi click nút xóa
document.querySelectorAll('.delete-product').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        // Hiển thị xác nhận trước khi xóa
        if (confirm(`Bạn có chắc chắn muốn xóa sản phẩm "${name}" (ID: ${id})?\nHành động này không thể hoàn tác!`)) {
            // Tạo form ẩn để gửi yêu cầu xóa
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/controllers/products/delete.php';
            
            // Thêm CSRF token nếu có
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);
            }
            
            // Thêm ID sản phẩm cần xóa
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            form.appendChild(idInput);
            
            // Thêm form vào body và submit
            document.body.appendChild(form);
            form.submit();
        }
    });
});

// Xử lý sự kiện khi thay đổi số lượng hiển thị trên trang
document.getElementById('per_page')?.addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', this.value);
    window.location.href = url.toString();
});

// Xử lý sự kiện khi nhấn nút tìm kiếm nhanh
document.getElementById('quick_search_btn')?.addEventListener('click', function() {
    const searchTerm = document.getElementById('quick_search').value.trim();
    if (searchTerm) {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchTerm);
        window.location.href = url.toString();
    }
});

// Xử lý sự kiện khi nhấn phím Enter trong ô tìm kiếm nhanh
document.getElementById('quick_search')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('quick_search_btn').click();
    }
});
</script>

<!-- Toast container -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast-container" class="toast-container">
        <!-- Toast messages will be inserted here by JavaScript -->
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
