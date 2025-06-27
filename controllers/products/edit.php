<?php
require_once __DIR__ . '/../../includes/init.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /controllers/login.php');
    exit;
}

// Lấy ID sản phẩm từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error_message'] = 'ID sản phẩm không hợp lệ';
    header('Location: /controllers/products.php');
    exit;
}

// Lấy thông tin sản phẩm
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_OBJ);

if (!$product) {
    $_SESSION['error_message'] = 'Không tìm thấy sản phẩm';
    header('Location: /controllers/products.php');
    exit;
}

// Lấy danh sách danh mục và thương hiệu
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_OBJ);
$brands = $pdo->query("SELECT * FROM brands ORDER BY name")->fetchAll(PDO::FETCH_OBJ);

// Lấy danh sách ảnh sản phẩm
$stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
$stmt->execute([$id]);
$product_images = $stmt->fetchAll(PDO::FETCH_OBJ);

$page_title = 'Chỉnh sửa sản phẩm';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Chỉnh sửa sản phẩm</h2>
                <a href="/controllers/products.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_message'] ?>
                    <?php unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <form id="editProductForm" action="/controllers/products/update.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $product->id ?>">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product->name) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">Mã SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku" value="<?= htmlspecialchars($product->sku ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <option value="">Chọn danh mục</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category->id ?>" <?= $product->category_id == $category->id ? 'selected' : '' ?>><?= htmlspecialchars($category->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Thương hiệu</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <option value="">Chọn thương hiệu</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand->id ?>" <?= $product->brand_id == $brand->id ? 'selected' : '' ?>><?= htmlspecialchars($brand->name) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?= $product->status === 'active' ? 'selected' : '' ?>>Đang bán</option>
                                        <option value="inactive" <?= $product->status === 'inactive' ? 'selected' : '' ?>>Ngừng kinh doanh</option>
                                        <option value="out_of_stock" <?= $product->status === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="price" name="price" min="0" step="1000" value="<?= $product->price ?>" required>
                                        <span class="input-group-text">₫</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="original_price" class="form-label">Giá gốc</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="original_price" name="original_price" min="0" step="1000" value="<?= $product->original_price ?? '' ?>">
                                        <span class="input-group-text">₫</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" value="<?= $product->stock_quantity ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Trọng lượng (g)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" min="0" step="0.01" value="<?= $product->weight ?? '' ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="2"><?= htmlspecialchars($product->short_description ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($product->description ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Hình ảnh sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="row" id="image-preview-container">
                            <?php foreach ($product_images as $image): ?>
                                <div class="col-md-3 mb-3 image-item" data-id="<?= $image->id ?>">
                                    <div class="card">
                                        <img src="/uploads/products/<?= $image->image_url ?>" class="card-img-top" alt="Product Image">
                                        <div class="card-body p-2 text-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input set-primary-image" type="radio" name="primary_image_id" id="primary_<?= $image->id ?>" value="<?= $image->id ?>" <?= $image->is_primary ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="primary_<?= $image->id ?>">Ảnh chính</label>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-image" data-id="<?= $image->id ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="col-md-3 mb-3">
                                <div class="card h-100 d-flex align-items-center justify-content-center" style="min-height: 150px; cursor: pointer;" id="upload-area">
                                    <div class="text-center p-3">
                                        <i class="bi bi-plus-circle display-4 text-muted"></i>
                                        <p class="mb-0">Thêm ảnh</p>
                                    </div>
                                    <input type="file" id="product_images" name="images[]" multiple accept="image/*" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" id="deleteProductBtn">
                        <i class="bi bi-trash"></i> Xóa sản phẩm
                    </button>
                    <div>
                        <a href="/controllers/products.php" class="btn btn-secondary me-2">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa sản phẩm -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" action="/controllers/products/delete.php" method="post" class="d-inline">
                    <input type="hidden" name="id" value="<?= $product->id ?>">
                    <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý sự kiện khi click nút xóa ảnh
document.querySelectorAll('.delete-image').forEach(button => {
    button.addEventListener('click', function() {
        const imageId = this.getAttribute('data-id');
        if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
            fetch(`/controllers/products/delete_image.php?id=${imageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `_method=DELETE&id=${imageId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`.image-item[data-id="${imageId}"]`).remove();
                    showToast('Xóa ảnh thành công', 'success');
                } else {
                    showToast(data.message || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi xóa ảnh', 'error');
            });
        }
    });
});

// Xử lý sự kiện khi chọn ảnh chính
document.querySelectorAll('.set-primary-image').forEach(radio => {
    radio.addEventListener('change', function() {
        const imageId = this.value;
        fetch('/controllers/products/set_primary_image.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=<?= $product->id ?>&image_id=${imageId}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
                // Reset radio nếu có lỗi
                this.checked = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra khi cập nhật ảnh chính', 'error');
            this.checked = false;
        });
    });
});

// Xử lý sự kiện khi click vào vùng tải ảnh
document.getElementById('upload-area').addEventListener('click', function() {
    document.getElementById('product_images').click();
});

// Xử lý khi chọn file ảnh
document.getElementById('product_images').addEventListener('change', function(e) {
    const files = e.target.files;
    if (files.length === 0) return;
    
    // Tạo form data để gửi ảnh lên server
    const formData = new FormData();
    formData.append('product_id', '<?= $product->id ?>');
    
    // Thêm từng file vào form data
    for (let i = 0; i < files.length; i++) {
        formData.append('images[]', files[i]);
    }
    
    // Gửi request lên server
    fetch('/controllers/products/upload_images.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tải lại trang để hiển thị ảnh mới
            window.location.reload();
        } else {
            showToast(data.message || 'Có lỗi xảy ra khi tải ảnh lên', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra khi tải ảnh lên', 'error');
    });
});

// Xử lý sự kiện khi click nút xóa sản phẩm
document.getElementById('deleteProductBtn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    modal.show();
});

// Hàm hiển thị thông báo
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
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
