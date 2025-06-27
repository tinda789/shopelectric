<?php
require_once 'includes/init.php';

// Lấy danh sách đơn hàng
$stmt = $pdo->query("SELECT o.*, u.name as customer_name, os.name as status_name 
                   FROM orders o 
                   LEFT JOIN users u ON o.user_id = u.id 
                   LEFT JOIN order_statuses os ON o.status_id = os.id 
                   ORDER BY o.created_at DESC");
$orders = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h2>Quản lý đơn hàng</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Địa chỉ giao hàng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['customer_name'] ?? 'Khách vãng lai') ?></td>
                        <td><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</td>
                        <td>
                            <span class="badge bg-<?= 
                                $order['status_name'] === 'Đã giao hàng' ? 'success' : 
                                ($order['status_name'] === 'Đang giao hàng' ? 'primary' : 'warning') ?>">
                                <?= $order['status_name'] ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
