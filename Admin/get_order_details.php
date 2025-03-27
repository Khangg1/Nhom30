<?php
require_once 'db.php';

if (isset($_GET['madh'])) {
    $madh = $_GET['madh'];

    // Fetch order details
    $stmt = $conn->prepare("
        SELECT 
            dh.madonhang,
            dh.ngaydat,
            dh.tongtien,
            dh.diachigiaohang,
            nd.tennguoidung,
            nd.email,
            tgh.tentrangthai as trangthai_giaohang,
            ttt.tentrangthai as trangthai_thanhtoan
        FROM donhang dh 
        JOIN nguoidung nd ON dh.mand = nd.mand 
        JOIN trangthai_giaohang tgh ON dh.matrangthai_giaohang = tgh.matrangthai
        JOIN trangthai_thanhtoan ttt ON dh.matrangthai_thanhtoan = ttt.matrangthai
        WHERE dh.madonhang = ?
    ");
    $stmt->execute([$madh]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
?>
        <div class="order-details">
            <h3>Chi tiết đơn hàng #<?php echo $order['madonhang']; ?></h3>

            <div class="info-group">
                <div class="info-item">
                    <label>Khách hàng:</label>
                    <span><?php echo $order['tennguoidung']; ?> (<?php echo $order['email']; ?>)</span>
                </div>

                <div class="info-item">
                    <label>Địa chỉ:</label>
                    <span><?php echo $order['diachigiaohang']; ?></span>
                </div>

                <div class="info-item">
                    <label>Tổng tiền:</label>
                    <span class="total-amount"><?php echo number_format($order['tongtien'], 0, ',', '.'); ?> ₫</span>
                </div>

                <div class="info-item">
                    <label>Ngày đặt:</label>
                    <span><?php echo date('d/m/Y H:i', strtotime($order['ngaydat'])); ?></span>
                </div>
            </div>

            <form method="post" action="update_order_status.php" class="status-form">
                <input type="hidden" name="madonhang" value="<?php echo $order['madonhang']; ?>">

                <div class="form-group">
                    <label for="trangthai_giaohang">Trạng thái giao hàng:</label>
                    <select name="trangthai_giaohang" id="trangthai_giaohang">
                        <?php
                        $statusStmt = $conn->query("SELECT * FROM trangthai_giaohang");
                        while ($status = $statusStmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = $status['tentrangthai'] == $order['trangthai_giaohang'] ? 'selected' : '';
                            echo "<option value='{$status['matrangthai']}' $selected>{$status['tentrangthai']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="trangthai_thanhtoan">Trạng thái thanh toán:</label>
                    <select name="trangthai_thanhtoan" id="trangthai_thanhtoan">
                        <?php
                        $statusStmt = $conn->query("SELECT * FROM trangthai_thanhtoan");
                        while ($status = $statusStmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = $status['tentrangthai'] == $order['trangthai_thanhtoan'] ? 'selected' : '';
                            echo "<option value='{$status['matrangthai']}' $selected>{$status['tentrangthai']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="update-btn">Cập nhật trạng thái</button>
            </form>
        </div>
        <script>
            document.querySelector('.status-form').addEventListener('submit', function(e) {
                e.preventDefault(); // Ngăn trình duyệt tải lại trang

                const formData = new FormData(this);
                fetch('update_order_status.php', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cập nhật trạng thái thành công');
                            location.reload(); // Reload trang sau khi cập nhật
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi cập nhật trạng thái');
                    });
            });
        </script>
        <style>
            .order-details {
                padding: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .order-details h3 {
                color: #2c3e50;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #eee;
            }

            .info-group {
                display: grid;
                gap: 15px;
                margin-bottom: 25px;
            }

            .info-item {
                display: flex;
                align-items: baseline;
            }

            .info-item label {
                min-width: 120px;
                font-weight: 600;
                color: #34495e;
            }

            .total-amount {
                color: #e74c3c;
                font-weight: 600;
            }

            .status-form {
                display: grid;
                gap: 20px;
            }

            .form-group {
                display: grid;
                gap: 8px;
            }

            .form-group label {
                font-weight: 600;
                color: #34495e;
            }

            select {
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                width: 100%;
            }

            .update-btn {
                background: #3498db;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .update-btn:hover {
                background: #2980b9;
                transform: translateY(-2px);
            }
        </style>
<?php
    } else {
        echo "<div class='error-message'>Không tìm thấy đơn hàng.</div>";
    }
} else {
    echo "<div class='error-message'>Không có mã đơn hàng được cung cấp.</div>";
}
?>