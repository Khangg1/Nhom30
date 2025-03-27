<?php
session_start();
require_once 'db.php'; // Thêm kết nối database
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công | HYBE Shop</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php
    try {
        if (isset($conn)) {
            // Bắt đầu output buffering
            ob_start();
    ?>
            <div class="success-container">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1>Đặt Hàng Thành Công!</h1>
                    <p class="success-message">Cảm ơn bạn đã tin tưởng và mua sắm tại Shop.</p>
                    <div class="order-info">
                        <p><i class="fas fa-info-circle"></i> Chúng tôi sẽ sớm xử lý đơn hàng của bạn.</p>
                        <p><i class="fas fa-envelope"></i> Thông tin chi tiết đơn hàng đã được gửi đến email của bạn.</p>
                    </div>
                    <div class="action-buttons">
                        <a href="my-orders.php" class="view-order-btn">
                            <i class="fas fa-box"></i> Xem Đơn Hàng
                        </a>
                        <a href="http://localhost:8080/quanlydongho/Client/products.php" class="continue-shopping-btn">
                            <i class="fas fa-shopping-cart"></i> Tiếp Tục Mua Sắm 123
                        </a>
                    </div>
                </div>
            </div>
    <?php
            // Lưu nội dung vào biến
            $content = ob_get_clean();

            // Include layout và truyền nội dung
            include 'layout.php';
        } else {
            throw new Exception("Database connection not established");
        }
    } catch (Exception $e) {
        error_log("Error including layout: " . $e->getMessage());
    }
    ?>

    <style>
        .success-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #f8f9fa;
        }

        .success-card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .success-icon {
            font-size: 5rem;
            color: #28a745;
            margin-bottom: 1.5rem;
            animation: scale-up 0.5s ease;
        }

        .success-card h1 {
            color: #2c3e50;
            font-size: 2.2rem;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .success-message {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .order-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .order-info p {
            color: #495057;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .view-order-btn,
        .continue-shopping-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .view-order-btn {
            background: #2c3e50;
            color: white;
        }

        .continue-shopping-btn {
            background: #28a745;
            color: white;
        }

        .view-order-btn:hover,
        .continue-shopping-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @keyframes scale-up {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        @media (max-width: 480px) {
            .success-card {
                padding: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</body>

</html>