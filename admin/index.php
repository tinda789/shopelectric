<?php
/**
 * Admin Dashboard
 */

// Include initialization
require_once __DIR__ . '/includes/init.php';

// Set page title
setPageTitle('Bảng điều khiển');

// Include header
include __DIR__ . '/includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include __DIR__ . '/includes/sidebar.php'; ?>
        
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Bảng điều khiển</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Chia sẻ</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Xuất</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <span data-feather="calendar"></span>
                        Tuần này
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng doanh thu (tháng)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">40,000,000 ₫</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Đơn hàng mới (hôm nay)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">24</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-cart-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Sản phẩm mới (tháng)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">56</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-box-seam fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Khách hàng mới (tháng)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">124</div>
                                </div>
                                <div class="col-auto">
                                    <i class="bi bi-people fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                    <a href="/admin/orders.php" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-20230001</td>
                                    <td>Nguyễn Văn A</td>
                                    <td>iPhone 13 Pro Max (x1), AirPods Pro (x1)</td>
                                    <td>30,990,000 ₫</td>
                                    <td><span class="badge bg-success">Đã thanh toán</span></td>
                                    <td>27/06/2025</td>
                                    <td>
                                        <a href="/admin/order.php?id=1" class="btn btn-sm btn-info">Xem</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-20230002</td>
                                    <td>Trần Thị B</td>
                                    <td>MacBook Air M1 (x1)</td>
                                    <td>23,990,000 ₫</td>
                                    <td><span class="badge bg-warning">Chờ xử lý</span></td>
                                    <td>26/06/2025</td>
                                    <td>
                                        <a href="/admin/order.php?id=2" class="btn btn-sm btn-info">Xem</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#ORD-20230003</td>
                                    <td>Lê Văn C</td>
                                    <td>iPad Pro 11" (x1), Apple Pencil (x1)</td>
                                    <td>27,990,000 ₫</td>
                                    <td><span class="badge bg-primary">Đang giao</span></td>
                                    <td>25/06/2025</td>
                                    <td>
                                        <a href="/admin/order.php?id=3" class="btn btn-sm btn-info">Xem</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Sản phẩm mới thêm</h6>
                            <a href="/admin/products.php" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/40" class="rounded me-3" alt="...">
                                        <div>
                                            <h6 class="mb-0">Tên sản phẩm <?= $i ?></h6>
                                            <small class="text-muted">Danh mục <?= $i % 3 + 1 ?></small>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary rounded-pill"><?= rand(1, 100) ?></span>
                                </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Khách hàng mới</h6>
                            <a href="/admin/customers.php" class="btn btn-sm btn-primary">Xem tất cả</a>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php 
                                $names = ['Nguyễn Văn A', 'Trần Thị B', 'Lê Văn C', 'Phạm Thị D', 'Hoàng Văn E'];
                                $emails = ['nguyenvana@example.com', 'tranthib@example.com', 'levanc@example.com', 'phamthid@example.com', 'hoangvane@example.com'];
                                for ($i = 0; $i < 5; $i++):
                                ?>
                                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name="<?= urlencode($names[$i]) ?>"&background=random" class="rounded-circle me-3" width="40" height="40" alt="...">
                                        <div>
                                            <h6 class="mb-0"><?= $names[$i] ?></h6>
                                            <small class="text-muted"><?= $emails[$i] ?></small>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?= rand(1, 24) ?>h trước</small>
                                </a>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
// Include footer
include __DIR__ . '/includes/footer.php';
?>
