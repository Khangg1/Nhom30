<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Hàm kiểm tra active menu
function isActiveMenu($page)
{
    $currentPage = basename($_SERVER['PHP_SELF']);
    return ($currentPage == $page) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - TIMEPIECE</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            min-height: 100vh;
            color: white;
            padding: 20px 0;
        }

        .sidebar .logo {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            border-bottom: 1px solid #34495e;
        }

        .sidebar .menu {
            padding: 20px;
        }

        .sidebar .menu a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .sidebar .menu a:hover,
        .sidebar .menu a.active {
            background: #3498db;
        }

        .sidebar .menu i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background: #f5f6fa;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-btn {
            color: #e74c3c;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #fde2e2;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-clock"></i> TIMEPIECE
        </div>
        <div class="menu">
            <a href="dashboard2.php" class="<?php echo isActiveMenu('dashboard2.php'); ?>">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <a href="quanlysanpham.php" class="<?php echo isActiveMenu('quanlysanpham.php'); ?>">
                <i class="fas fa-box"></i> Quản Lý Sản phẩm
            </a>
            <a href="quanlyloaisanpham.php" class="<?php echo isActiveMenu('quanlyloaisanpham.php'); ?>">
                <i class="fas fa-list"></i> Quản Lý Loại
                <a href="quanlydonhang.php" class="<?php echo isActiveMenu('quanlydonhang.php'); ?>">
                    <i class="fas fa-shopping-cart"></i> Đơn hàng
                </a>
                <a href="quanlynguoidung.php" class="<?php echo isActiveMenu('quanlynguoidung.php'); ?>">
                    <i class="fas fa-users"></i> Khách hàng
                </a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span><?php echo $_SESSION['tenadmin']; ?></span>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>

        <div class="content">
            <?php
            if (isset($content)) {
                echo $content;
            }
            ?>
        </div>
    </div>
</body>

</html>