<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Xử lý cập nhật trạng thái đơn hàng
if (isset($_POST['update_status'])) {
    $madh = $_POST['madh'];
    $trangthai = $_POST['trangthai'];
    $stmt = $conn->prepare("UPDATE donhang SET trangthai = ? WHERE madh = ?");
    $stmt->execute([$trangthai, $madh]);
    header('Location: quanlydonhang.php');
    exit;
}

// Truy vấn danh sách đơn hàng
$stmt = $conn->prepare("
    SELECT 
        dh.madonhang,
        dh.ngaydat,
        dh.tongtien,
        dh.diachigiaohang,
        nd.tennguoidung,
        nd.email,
        tgh.tentrangthai as trangthai_giaohang,
        ttt.tentrangthai as trangthai_thanhtoan,
        dh.phuongthucthanhtoan
    FROM donhang dh 
    JOIN nguoidung nd ON dh.mand = nd.mand 
    JOIN trangthai_giaohang tgh ON dh.matrangthai_giaohang = tgh.matrangthai
    JOIN trangthai_thanhtoan ttt ON dh.matrangthai_thanhtoan = ttt.matrangthai
    WHERE dh.isdelete = 0
    ORDER BY dh.ngaydat DESC
");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý Đơn hàng</h1>
    </div>

    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Thông tin liên hệ</th>
                    <th>Địa chỉ</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Phương thức thanh toán</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo $order['madonhang']; ?></td>
                        <td class="customer-info">
                            <div class="customer-name"><?php echo $order['tennguoidung']; ?></div>
                        </td>
                        <td class="contact-info">
                            <div><?php echo $order['email']; ?></div>
                        </td>
                        <td class="address"><?php echo $order['diachigiaohang']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></td>
                        <td class="order-total"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> ₫</td>
                        <td>
                            <div>Giao hàng: <?php echo $order['trangthai_giaohang']; ?></div>
                            <div>Thanh toán: <?php echo $order['trangthai_thanhtoan']; ?></div>
                        </td>
                        <td><?php echo $order['phuongthucthanhtoan']; ?></td>
                        <td>
                            <button class="view-details-btn" onclick="showOrderDetails(<?php echo $order['madonhang']; ?>)">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal chi tiết đơn hàng -->
<div id="orderDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Chi tiết đơn hàng</h2>
        <div id="orderDetailsContent"></div>
    </div>
</div>

<style>
    .container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        color: #2c3e50;
        font-size: 2rem;
        margin: 0;
    }

    .table-responsive {
        overflow-x: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .order-table th,
    .order-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .order-table th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
    }

    .order-table tr:hover {
        background: #f8f9fa;
    }

    .customer-name {
        font-weight: 500;
        color: #2c3e50;
    }

    .contact-info {
        color: #7f8c8d;
    }

    .order-total {
        font-weight: 600;
        color: #e74c3c;
    }

    .status-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        width: 100%;
    }

    .view-details-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .view-details-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 12px;
        width: 80%;
        max-width: 800px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .order-table {
            font-size: 0.9rem;
        }

        .status-select {
            font-size: 0.9rem;
        }
    }
</style>

<script>
    function showOrderDetails(orderId) {
        // Hiển thị modal
        const modal = document.getElementById('orderDetailsModal');
        modal.style.display = 'block';

        // Gọi AJAX để lấy chi tiết đơn hàng
        fetch(`get_order_details.php?madh=${orderId}`)
            .then(response => response.text())
            .then(data => {
                document.getElementById('orderDetailsContent').innerHTML = data;
            });
    }

    // Đóng modal khi click vào nút close
    document.querySelector('.close').onclick = function() {
        document.getElementById('orderDetailsModal').style.display = 'none';
    }

    // Đóng modal khi click bên ngoài modal
    window.onclick = function(event) {
        const modal = document.getElementById('orderDetailsModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    function updateOrderStatus(madh, loaiTrangThai, maTrangThai) {
        // Thêm console.log để debug
        console.log('Sending data:', {
            madh: madh,
            loaiTrangThai: loaiTrangThai,
            maTrangThai: maTrangThai
        });

        fetch('update_order_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    madh: String(madh), // Chuyển sang string
                    loaiTrangThai: loaiTrangThai,
                    maTrangThai: String(maTrangThai) // Chuyển sang string
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Thêm thông báo thành công
                    alert('Cập nhật trạng thái thành công');
                    // Có thể refresh trang hoặc cập nhật UI
                    location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                    console.error('Error data:', data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi cập nhật trạng thái');
            });
    }
</script>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>