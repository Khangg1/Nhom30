<?php
session_start();
include('db.php'); // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng có phải admin không
if (!isset($_SESSION['admin_id'])) {
    header('Location: login_admin.php'); // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
    exit;
}

// Lấy danh sách người dùng (hoặc các thông tin khác tùy thuộc vào hệ thống)
$sql_users = "SELECT * FROM dbo.nguoidung WHERE isdelete = 0";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->execute();
$users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách đơn hàng (hoặc các thông tin khác tùy thuộc vào hệ thống)
$sql_orders = "SELECT * FROM dbo.donhang WHERE isdelete = 0";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->execute();
$orders = $stmt_orders->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .container {
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h2>
        <p><a href="logout_admin.php">Logout</a></p>

        <h3>Danh sách người dùng</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['mand']; ?></td>
                        <td><?php echo $user['tennguoidung']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['mand']; ?>">Sửa</a> |
                            <a href="delete_user.php?id=<?php echo $user['mand']; ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Danh sách đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>ID Người dùng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Địa chỉ giao hàng</th>
                    <th>Trạng thái giao hàng</th>
                    <th>Trạng thái thanh toán</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['madonhang']; ?></td>
                        <td><?php echo $order['mand']; ?></td>
                        <td><?php echo $order['ngaydat']->format('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $order['tongtien']; ?> VND</td>
                        <td><?php echo $order['diachigiaohang']; ?></td>
                        <td><?php echo $order['matrangthai_giaohang']; ?></td>
                        <td><?php echo $order['matrangthai_thanhtoan']; ?></td>
                        <td>
                            <a href="edit_order.php?id=<?php echo $order['madonhang']; ?>">Sửa</a> |
                            <a href="delete_order.php?id=<?php echo $order['madonhang']; ?>">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>