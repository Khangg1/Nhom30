<?php
session_start();
include('db.php');

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Lấy ID người dùng từ session
$userId = $_SESSION['user']['id'];

// Truy vấn danh sách đơn hàng của người dùng
$sql = "SELECT dh.madonhang, dh.ngaydat, dh.tongtien, ttgh.tentrangthai AS trangthai_giaohang, tttt.tentrangthai AS trangthai_thanhtoan
        FROM donhang dh
        JOIN trangthai_giaohang ttgh ON dh.matrangthai_giaohang = ttgh.matrangthai
        JOIN trangthai_thanhtoan tttt ON dh.matrangthai_thanhtoan = tttt.matrangthai
        WHERE dh.mand = :mand AND dh.isdelete = 0
        ORDER BY dh.ngaydat DESC";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':mand', $userId, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nội dung HTML
ob_start();
?>

<div class="orders-container">
    <div class="orders-header">
        <h2><i class="fas fa-box"></i> Đơn Hàng Của Tôi</h2>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="orders-wrapper">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> Mã Đơn Hàng</th>
                        <th><i class="far fa-calendar-alt"></i> Ngày Đặt</th>
                        <th><i class="fas fa-money-bill-wave"></i> Tổng Tiền</th>
                        <th><i class="fas fa-truck"></i> Trạng Thái Giao Hàng</th>
                        <th><i class="fas fa-credit-card"></i> Trạng Thái Thanh Toán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="order-id">#<?php echo htmlspecialchars($order['madonhang']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($order['ngaydat']))); ?></td>
                            <td class="price"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> VNĐ</td>
                            <td>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $order['trangthai_giaohang'])); ?>">
                                    <?php if ($order['trangthai_giaohang'] == 'Chưa giao'): ?>
                                        <i class="fas fa-truck-loading"></i> Chưa giao
                                    <?php else: ?>
                                        <i class="fas fa-check-circle"></i> Đã giao
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge <?php echo strtolower(str_replace(' ', '-', $order['trangthai_thanhtoan'])); ?>">
                                    <?php if ($order['trangthai_thanhtoan'] == 'Chưa thanh toán'): ?>
                                        <i class="fas fa-times-circle"></i> Chưa thanh toán
                                    <?php else: ?>
                                        <i class="fas fa-check-circle"></i> Đã thanh toán
                                    <?php endif; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-orders">
            <i class="fas fa-box-open empty-icon"></i>
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="products.php" class="shop-now-btn">Mua Sắm Ngay</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .orders-container {
        padding: 2rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        max-width: 1000px;
        margin: 2rem auto;
    }

    .orders-header {
        margin-bottom: 2rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
    }

    .orders-header h2 {
        font-family: 'Playfair Display', serif;
        color: #2c3e50;
        margin: 0;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .orders-wrapper {
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1rem;
    }

    .orders-table th,
    .orders-table td {
        padding: 1.2rem 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .orders-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #2c3e50;
        white-space: nowrap;
    }

    .orders-table th i {
        margin-right: 0.5rem;
        color: #666;
    }

    .orders-table tr:hover {
        background: #f8f9fa;
        transition: background-color 0.3s ease;
    }

    .order-id {
        font-weight: 600;
        color: #2c3e50;
    }

    .price {
        font-weight: 600;
        color: #2c3e50;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-block;
    }

    .empty-orders {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 1rem;
    }

    .shop-now-btn {
        display: inline-block;
        padding: 0.8rem 2rem;
        background: #1976d2;
        color: #fff;
        text-decoration: none;
        border-radius: 25px;
        margin-top: 1rem;
        transition: all 0.3s ease;
    }

    .shop-now-btn:hover {
        background: #1565c0;
        transform: translateY(-2px);
    }

    .action-btn {
        padding: 0.5rem;
        border: none;
        background: none;
        cursor: pointer;
        color: #666;
        transition: all 0.3s ease;
    }

    .action-btn:hover {
        color: #1976d2;
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .orders-container {
            padding: 1rem;
            margin: 1rem;
        }

        .orders-table th,
        .orders-table td {
            padding: 0.8rem 0.5rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
include('layout.php');
?>