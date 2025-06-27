<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý Danh mục</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-circle"></i> Thêm danh mục
                </button>
            </div>
            
            <!-- Form tìm kiếm và lọc -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" action="" class="row g-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Tìm kiếm theo tên hoặc mô tả...">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i> Tìm kiếm
                                </button>
                                <?php if (!empty($search)): ?>
                                    <a href="<?= buildUrl(['search' => '']) ?>" class="btn btn-outline-danger">
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
                                    <th>
                                        <a href="<?= buildUrl(['sort' => 'name', 'order' => $sort === 'name' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Tên danh mục
                                            <?php if ($sort === 'name'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Mô tả</th>
                                    <th>
                                        <a href="<?= buildUrl(['sort' => 'created_at', 'order' => $sort === 'created_at' && $order === 'ASC' ? 'DESC' : 'ASC']) ?>" class="text-decoration-none text-dark">
                                            Ngày tạo
                                            <?php if ($sort === 'created_at'): ?>
                                                <i class="bi bi-caret-<?= $order === 'ASC' ? 'up' : 'down' ?>-fill"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Số sản phẩm</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không có danh mục nào</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td><?= $category->id ?></td>
                                            <td><?= htmlspecialchars($category->name) ?></td>
                                            <td><?= !empty($category->description) ? htmlspecialchars($category->description) : 'N/A' ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($category->created_at)) ?></td>
                                            <td class="text-center">
                                                <span class="badge bg-primary"><?= $category->product_count ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary edit-category" 
                                                        data-id="<?= $category->id ?>"
                                                        data-name="<?= htmlspecialchars($category->name) ?>"
                                                        data-description="<?= htmlspecialchars($category->description) ?>">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-category" 
                                                        data-id="<?= $category->id ?>"
                                                        data-name="<?= htmlspecialchars($category->name) ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Hiển thị phân trang -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($current_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildUrl(['page' => $current_page - 1]) ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i === $current_page ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= buildUrl(['page' => $i]) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= buildUrl(['page' => $current_page + 1]) ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm danh mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="add_category" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Chỉnh sửa danh mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="/controllers/update_category.php">
                <input type="hidden" name="category_id" id="edit_category_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="delete_category_name"></strong>?</p>
                <p class="text-danger"><small>Lưu ý: Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="post" action="/controllers/delete_category.php" class="d-inline">
                    <input type="hidden" name="category_id" id="delete_category_id">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý sự kiện khi click nút sửa
document.querySelectorAll('.edit-category').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description') || '';
        
        document.getElementById('edit_category_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        
        // Mở modal
        const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
        modal.show();
    });
});

// Xử lý sự kiện khi click nút xóa
document.querySelectorAll('.delete-category').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');
        
        document.getElementById('delete_category_id').value = id;
        document.getElementById('delete_category_name').textContent = name;
        
        // Mở modal
        const modal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
        modal.show();
    });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
