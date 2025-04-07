<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? $_SESSION['user']['tennguoidung'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layout Quản lý đồng hồ</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f5f0;
            color: #2c2c2c;
        }

        header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            color: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
        }

        .header-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2em;
            color: #d4af37;
            margin: 0;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .header-logo h1:hover {
            transform: scale(1.05);
            color: #e5c158;
        }

        .main-nav {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .main-nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95em;
            letter-spacing: 1px;
            padding: 8px 0;
            position: relative;
            transition: all 0.3s ease;
        }

        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #d4af37;
            transition: width 0.3s ease;
        }

        .main-nav a:hover {
            color: #d4af37;
        }

        .main-nav a:hover::after {
            width: 100%;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .header-actions a {
            color: #fff;
            text-decoration: none;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 20px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
        }

        .header-actions a:hover {
            background: rgba(212, 175, 55, 0.2);
            transform: translateY(-2px);
        }

        .header-actions i {
            font-size: 1.1em;
        }

        .cart-icon {
            position: relative;
            padding: 8px 15px !important;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #d4af37;
            color: #1a1a1a;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75em;
            font-weight: bold;
            border: 2px solid #1a1a1a;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(212, 175, 55, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(212, 175, 55, 0);
            }
        }

        /* Thêm hiệu ứng hover cho icons */
        .header-actions i {
            transition: transform 0.3s ease;
        }

        .header-actions a:hover i {
            transform: scale(1.2);
        }

        footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        .container {
            display: flex;
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
            gap: 30px;
        }

        .nav {
            width: 280px;

            background: linear-gradient(145deg, #2c2c2c, #1a1a1a);
            color: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 20px;
            height: 1200px;
        }

        .nav h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5em;
            margin: 0 0 25px 0;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
        }

        .nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav ul li {
            margin-bottom: 12px;
        }

        .nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            padding: 12px 18px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav ul li a i {
            margin-right: 12px;
            font-size: 1.1em;
            color: #d4af37;
            transition: all 0.3s ease;
            width: 20px;
            text-align: center;
        }

        .nav ul li a:hover {
            background: rgba(212, 175, 55, 0.15);
            transform: translateX(5px);
            border-color: rgba(212, 175, 55, 0.3);
        }

        .nav ul li a:hover i {
            transform: scale(1.2);
            color: #fff;
        }

        .nav ul li.active a {
            background: rgba(212, 175, 55, 0.2);
            border-color: rgba(212, 175, 55, 0.4);
            color: #d4af37;
        }

        /* Thêm hiệu ứng hover cho category count */
        .nav ul li a .category-count {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            transition: all 0.3s ease;
        }

        .nav ul li a:hover .category-count {
            background: rgba(212, 175, 55, 0.3);
        }

        .content {
            flex-grow: 1;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        a,
        button {
            transition: all 0.3s ease;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .watch-carousel {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .carousel-container {
            display: flex;
            transition: transform 0.5s ease-in-out;
            height: 100%;
        }

        .carousel-slide {
            min-width: 100%;
            position: relative;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-caption {
            position: absolute;
            bottom: 50px;
            left: 50px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .carousel-caption h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #d4af37;
        }

        .carousel-caption p {
            font-size: 1.2em;
            margin: 0;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-btn:hover {
            background: rgba(212, 175, 55, 0.8);
        }

        .carousel-btn.prev {
            left: 20px;
        }

        .carousel-btn.next {
            right: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-top">
            <div class="header-logo">
                <a style="text-decoration: none;" href="products.php">
                    <h1>TIMEPIECE</span></h1>
                </a>
            </div>
            <nav class="main-nav">
                <a href="products.php">TRANG CHỦ</a>
                <a href="about.php">GIỚI THIỆU</a>
                <a href="contact.php">LIÊN HỆ</a>

            </nav>
            <div class="header-actions">


                <?php if ($isLoggedIn): ?>

                    <!-- Khi người dùng đã đăng nhập -->
                    <div class="user-dropdown">
                        <span class="user-welcome">Xin chào, <strong><?php echo htmlspecialchars($username); ?></strong> <i class="fas fa-caret-down"></i></span>
                        <div class="dropdown-content">
                            <a href="my-profile.php"><i class="fas fa-user-circle"></i> Thông tin của tôi</a>

                            <a href="my-orders.php"><i class="fas fa-box"></i> Đơn hàng của tôi</a>
                            <a href="my-wishlist.php"><i class="fas fa-user-circle"></i> Sản phẩm yêu thích </a>

                        </div>
                        <style>
                            .user-dropdown {
                                position: relative;
                                display: inline-block;
                                margin-right: 15px;
                            }

                            .user-welcome {
                                color: #fff;
                                cursor: pointer;
                                padding: 8px 12px;
                                border-radius: 4px;
                                transition: all 0.3s ease;
                            }

                            .user-welcome:hover {
                                background: rgba(255, 255, 255, 0.1);
                            }

                            .dropdown-content {
                                display: none;
                                position: absolute;
                                right: 0;
                                background-color: #fff;
                                min-width: 200px;
                                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
                                border-radius: 8px;
                                overflow: hidden;
                                z-index: 1000;
                                margin-top: 10px;
                            }

                            .user-dropdown:hover .dropdown-content {
                                display: block;
                                animation: fadeIn 0.3s ease;
                            }

                            .dropdown-content a {
                                color: #333;
                                padding: 12px 20px;
                                text-decoration: none;
                                display: flex;
                                align-items: center;
                                transition: all 0.2s ease;
                            }

                            .dropdown-content a i {
                                margin-right: 10px;
                                color: #666;
                            }

                            .dropdown-content a:hover {
                                background-color: #f8f9fa;
                                color: #007bff;
                            }

                            .dropdown-content a:hover i {
                                color: #007bff;
                            }

                            @keyframes fadeIn {
                                from {
                                    opacity: 0;
                                    transform: translateY(-10px);
                                }

                                to {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }
                        </style>
                    </div>
                    <a href="cart.php" class="cart-icon">
                        <i class="fas fa-cart-shopping" style="color: #fff; font-size: 1.3em;"></i>
                    </a>
                    <a href="logout.php" class="account-icon">Đăng Xuất</a>
                <?php else: ?>
                    <!-- Khi người dùng chưa đăng nhập -->
                    <a href="login.php" class="account-icon">Đăng Nhập</a>
                    <a href="register.php" class="account-icon">Đăng Ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Thêm carousel panel -->
    <div class="watch-carousel">
        <div class="carousel-container">
            <div class="carousel-slide">
                <img src="https://images.unsplash.com/photo-1587836374828-4dbafa94cf0e?auto=format&fit=crop&w=1200" alt="Luxury Watch 1">
                <div class="carousel-caption">
                    <h2>Rolex Submariner</h2>
                    <p>Biểu tượng của sự sang trọng và đẳng cấp</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=1200" alt="Luxury Watch 2">
                <div class="carousel-caption">
                    <h2>Omega Speedmaster</h2>
                    <p>Chinh phục không gian, định nghĩa thời gian</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="https://images.unsplash.com/photo-1622434641406-a158123450f9?auto=format&fit=crop&w=1200" alt="Luxury Watch 3">
                <div class="carousel-caption">
                    <h2>Patek Philippe</h2>
                    <p>Tuyệt tác của ngh��� thuật chế tác đồng hồ</p>
                </div>
            </div>
        </div>
        <button class="carousel-btn prev"><i class="fas fa-chevron-left"></i></button>
        <button class="carousel-btn next"><i class="fas fa-chevron-right"></i></button>
    </div>

    <div class="container">
        <?php
        // Lấy dữ liệu từ bảng loaidongho
        $sql_loaidongho = "SELECT maloaidh, tenloai FROM loaidongho WHERE isdelete = 0";
        $stmt_loaidongho = $conn->prepare($sql_loaidongho);
        $stmt_loaidongho->execute();
        $categories = $stmt_loaidongho->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <nav class="nav">
            <h3>Danh mục</h3>
            <ul>
                <li class="<?php echo !isset($_GET['category']) ? 'active' : ''; ?>">
                    <a href="products.php">
                        <i class="fas fa-compass"></i>
                        Tất cả Bộ sưu tập
                    </a>
                </li>
                <?php foreach ($categories as $category): ?>
                    <li class="<?php echo (isset($_GET['category']) && $_GET['category'] == $category['maloaidh']) ? 'active' : ''; ?>">
                        <a href="products.php?category=<?php echo $category['maloaidh']; ?>">
                            <i class="fas fa-watch"></i>
                            <?php echo htmlspecialchars($category['tenloai']); ?>
                            <span class="category-count">
                                <?php
                                // Nếu bạn muốn hiển thị số lượng sản phẩm trong mỗi danh mục
                                // echo $category['product_count']; 
                                ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="content">
            <?php
            if (isset($content)) {
                echo $content;
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>TIMEPIECE</h4>
                <p>Chuyên cung cấp các dòng đồng hồ cao cấp chính hãng từ những thương hiệu hàng đầu thế giới.</p>
                <div class="social-links">
                    <a href="https://www.facebook.com/changxkoi/"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/koi_sce/"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.youtube.com/@koisc"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Liên Hệ</h4>
                <p><i class="fas fa-map-marker-alt"></i> 123 Đường ABC, Quận 1, TP.HCM</p>
                <p><i class="fas fa-phone"></i> 0123.456.789</p>
                <p><i class="fas fa-envelope"></i> scgroup.entertainment@gmail.com</p>
            </div>
            <div class="footer-section">
                <h4>Chính Sách</h4>
                <ul>
                    <li><a href="#">Chính sách bảo hành</a></li>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Chính sách vận chuyển</a></li>
                    <li><a href="#">Điều khoản dịch vụ</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 TIMEPIECE. Tất cả quyền được bảo lưu.</p>
        </div>
        <style>
            .footer {
                background: #1a1a1a;
                color: #fff;
                padding: 60px 0 20px;
                margin-top: 50px;
            }

            .footer-content {
                max-width: 1200px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 40px;
                padding: 0 20px;
            }

            .footer-section h4 {
                color: #d4af37;
                font-size: 1.2em;
                margin-bottom: 20px;
                font-weight: 600;
            }

            .footer-section p {
                color: #999;
                line-height: 1.6;
                margin-bottom: 10px;
            }

            .footer-section i {
                margin-right: 10px;
                color: #d4af37;
            }

            .social-links {
                margin-top: 20px;
            }

            .social-links a {
                color: #fff;
                margin-right: 15px;
                font-size: 1.2em;
                transition: color 0.3s ease;
            }

            .social-links a:hover {
                color: #d4af37;
            }

            .footer-section ul {
                list-style: none;
                padding: 0;
            }

            .footer-section ul li {
                margin-bottom: 10px;
            }

            .footer-section ul li a {
                color: #999;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .footer-section ul li a:hover {
                color: #d4af37;
            }

            .footer-bottom {
                text-align: center;
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #333;
            }

            .footer-bottom p {
                color: #666;
                font-size: 0.9em;
            }

            @media (max-width: 768px) {
                .footer-content {
                    grid-template-columns: 1fr;
                    text-align: center;
                }

                .footer-section {
                    margin-bottom: 30px;
                }

                .social-links {
                    justify-content: center;
                }
            }
        </style>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.carousel-container');
            const slides = document.querySelectorAll('.carousel-slide');
            const prevBtn = document.querySelector('.prev');
            const nextBtn = document.querySelector('.next');

            let currentIndex = 0;

            function updateCarousel() {
                container.style.transform = `translateX(-${currentIndex * 100}%)`;
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                updateCarousel();
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateCarousel();
            }

            // Auto slide every 5 seconds
            setInterval(nextSlide, 5000);

            prevBtn.addEventListener('click', prevSlide);
            nextBtn.addEventListener('click', nextSlide);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hàm cập nhật số lượng giỏ hàng
            function updateCartCount() {
                fetch('get_cart_count.php')
                    .then(response => response.json())
                    .then(data => {
                        const cartCountElement = document.querySelector('.cart-count');
                        if (data.count > 0) {
                            if (cartCountElement) {
                                cartCountElement.textContent = data.count;
                            } else {
                                const cartIcon = document.querySelector('.cart-icon');
                                const newCountElement = document.createElement('span');
                                newCountElement.className = 'cart-count';
                                newCountElement.textContent = data.count;
                                cartIcon.appendChild(newCountElement);
                            }
                        } else {
                            cartCountElement?.remove();
                        }
                    });
            }

            // Lắng nghe sự kiện tùy chỉnh để cập nhật giỏ hàng
            window.addEventListener('cartUpdated', function() {
                updateCartCount();
            });
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>