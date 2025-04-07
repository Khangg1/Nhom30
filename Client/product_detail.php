<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn thông tin chi tiết sản phẩm
$sql = "SELECT dongho.*, loaidongho.tenloai 
        FROM dongho 
        INNER JOIN loaidongho ON dongho.maloaidh = loaidongho.maloaidh
        WHERE dongho.madh = :productId AND dongho.isdelete = 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy sản phẩm, chuyển hướng về trang sản phẩm
if (!$product) {
    header('Location: products.php');
    exit();
}

// Truy vấn đánh giá sản phẩm
$reviewSql = "SELECT danhgia.*, nguoidung.tennguoidung 
              FROM danhgia 
              INNER JOIN nguoidung ON danhgia.mand = nguoidung.mand 
              WHERE danhgia.masp = :productId AND danhgia.isdelete = 0 
              ORDER BY danhgia.ngaydanhgia DESC";
$reviewStmt = $conn->prepare($reviewSql);
$reviewStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
$reviewStmt->execute();
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

// Truy vấn top 5 sản phẩm có giá cao nhất
$topProductsSql = "SELECT TOP 5 * FROM dongho WHERE isdelete = 0 ORDER BY gia DESC";
$topProductsStmt = $conn->prepare($topProductsSql);
$topProductsStmt->execute();
$topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="product-detail-container">
    <div class="product-detail-wrapper">
        <div class="product-image-section">
            <div class="main-image">
                <img src="images/<?php echo htmlspecialchars($product['hinhanh']); ?>"
                    alt="<?php echo htmlspecialchars($product['tendongho']); ?>">
            </div>
        </div>

        <div class="product-info-section">
            <span class="product-category"><?php echo htmlspecialchars($product['tenloai']); ?></span>
            <h1 class="product-title"><?php echo htmlspecialchars($product['tendongho']); ?></h1>

            <div class="rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
                <span class="rating-count">(4.5)</span>
            </div>

            <div class="price-section">
                <span class="current-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?> VNĐ</span>
                <span class="original-price">₫2,990,000</span>
                <span class="discount-badge">-20%</span>
            </div>

            <div class="product-description">
                <h3>Mô tả sản phẩm</h3>
                <p><?php echo nl2br(htmlspecialchars($product['mota'])); ?></p>
            </div>


        </div>
    </div>
</div>

<div class="reviews-section">
    <h2>Đánh giá sản phẩm</h2>

    <?php if (isset($_SESSION['mand'])): ?>
        <div class="review-form">
            <h3>Viết đánh giá của bạn</h3>
            <form id="reviewForm" action="submit_review.php" method="POST">
                <input type="hidden" name="productId" value="<?php echo $productId; ?>">
                <div class="rating-input">
                    <label>Đánh giá:</label>
                    <div class="star-rating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" required>
                            <label for="star<?php echo $i; ?>" class="star-label">
                                <i class="fas fa-star"></i>
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <style>
                    .rating-input {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .star-rating {
                        display: flex;
                        gap: 5px;
                    }

                    .star-label {
                        cursor: pointer;
                        transition: transform 0.2s;
                    }

                    .star-label:hover,
                    .star-label:hover~.star-label {
                        transform: scale(1.2);
                        color: #ffd700;
                    }

                    .star-rating input[type="radio"] {
                        display: none;
                    }

                    .star-rating input[type="radio"]:checked~.star-label {
                        color: #ffd700;
                    }
                </style>
                <div class="review-input">
                    <textarea name="review" placeholder="Nhập đánh giá của bạn" required></textarea>
                </div>
                <button type="submit" class="submit-review-btn">Gửi đánh giá</button>
            </form>
        </div>
    <?php else: ?>
        <p class="login-prompt">Vui lòng <a href="login.php">đăng nhập</a> để đánh giá sản phẩm</p>
    <?php endif; ?>

    <div class="reviews-list">
        <?php if ($reviews): ?>
            <?php foreach ($reviews as $review): ?>
                <div class="review-item">
                    <div class="review-author">
                        <strong><?php echo htmlspecialchars($review['tennguoidung']); ?></strong>
                        <span class="review-date"><?php echo htmlspecialchars($review['ngaydanhgia']); ?></span>
                    </div>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $review['diem']): ?>
                                <i class="fas fa-star"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="review-content">
                        <p><?php echo nl2br(htmlspecialchars($review['noidung'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        <?php endif; ?>
    </div>

    <h2 style="margin-bottom: 30px; font-size: 1.5em; text-align: center;">Top 5 sản phẩm có giá cao nhất</h2>
    <div class="top-products-carousel" style="display: flex; overflow-x: auto; gap: 20px; padding: 20px 0; scroll-behavior: smooth;">
        <?php
        $count = 0;
        foreach ($topProducts as $topProduct):
            if ($count >= 4) break;
            $count++;
        ?>
            <a href="product_detail.php?id=<?php echo $topProduct['madh']; ?>" class="carousel-item" style="flex: 0 0 auto; width: 300px; text-align: center; background: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 20px; text-decoration: none; transition: transform 0.3s, box-shadow 0.3s;">
                <img src="images/<?php echo htmlspecialchars($topProduct['hinhanh']); ?>" alt="<?php echo htmlspecialchars($topProduct['tendongho']); ?>" style="max-width: 100%; border-radius: 10px; transition: transform 0.3s;">
                <h3 style="font-size: 1.2em; margin-top: 10px;"><?php echo htmlspecialchars($topProduct['tendongho']); ?></h3>
                <p style="font-size: 1.1em; color: #333;"><?php echo number_format($topProduct['gia'], 0, ',', '.'); ?> VNĐ</p>
            </a>
        <?php endforeach; ?>
    </div>
    <style>
        .carousel-item:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .carousel-item img:hover {
            transform: scale(1.1);
        }
    </style>
</div>

<style>
    .product-detail-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .product-detail-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .product-image-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .main-image {
        width: 100%;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 10px;
        overflow: hidden;
    }

    .main-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .product-info-section {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .product-category {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .product-title {
        font-size: 2rem;
        color: #2d3436;
        margin: 0;
    }

    .rating {
        color: #ffd700;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .rating-count {
        color: #666;
        margin-left: 8px;
    }

    .price-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin: 20px 0;
    }

    .current-price {
        font-size: 2rem;
        font-weight: 700;
        color: #1976d2;
    }

    .original-price {
        color: #999;
        text-decoration: line-through;
        font-size: 1.2rem;
    }

    .discount-badge {
        background: #ff4757;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .product-description {
        margin: 20px 0;
    }

    .product-description h3 {
        color: #2d3436;
        margin-bottom: 10px;
    }

    .product-description p {
        color: #666;
        line-height: 1.6;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 20px;
    }

    .add-to-cart-btn,
    .add-to-wishlist-btn {
        padding: 15px 30px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn {
        background: linear-gradient(45deg, #1976d2, #2196f3);
        color: white;
        flex: 2;
    }

    .add-to-wishlist-btn {
        background: #f8f9fa;
        color: #1976d2;
        border: 2px solid #1976d2;
        flex: 1;
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(45deg, #1565c0, #1976d2);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(25, 118, 210, 0.3);
    }

    .add-to-wishlist-btn:hover {
        background: #e3f2fd;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .product-detail-wrapper {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .add-to-cart-btn,
        .add-to-wishlist-btn {
            width: 100%;
            justify-content: center;
        }
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .reviews-section {
        margin-top: 40px;
    }

    .review-form {
        margin-bottom: 40px;
    }

    .review-form h3 {
        margin-bottom: 20px;
    }

    .rating-input {
        margin-bottom: 20px;
    }

    .star-rating {
        display: flex;
        gap: 5px;
    }

    .review-input textarea {
        width: 100%;
        height: 100px;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        resize: none;
    }

    .submit-review-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #1976d2;
        color: white;
        cursor: pointer;
    }

    .submit-review-btn:hover {
        background-color: #1565c0;
    }

    .reviews-list {
        margin-top: 20px;
    }

    .review-item {
        border-bottom: 1px solid #ccc;
        padding: 20px 0;
    }

    .review-author {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .review-rating {
        color: #ffd700;
        margin-bottom: 10px;
    }

    .review-content p {
        color: #666;
        line-height: 1.6;
    }

    .carousel-item {
        min-width: 200px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        padding: 10px;
    }

    .carousel-item img {
        max-width: 100%;
        border-radius: 10px;
    }
</style>

<script>
    function addToCart(productId) {
        $.ajax({
            url: 'add_to_cart.php',
            method: 'POST',
            data: {
                productId: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = response.cartTotal;
                    }
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function(err) {
                console.error('Error adding to cart:', err);
                alert('Không thể thêm vào giỏ hàng. Vui lòng thử lại.');
            }
        });
    }

    function addToWishlist(productId) {
        $.ajax({
            url: 'add_to_wishlist.php',
            method: 'POST',
            data: {
                productId: productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
            error: function(err) {
                console.error('Error adding to wishlist:', err);
                alert('Không thể thêm vào danh sách yêu thích. Vui lòng thử lại.');
            }
        });
    }

    document.getElementById('reviewForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('submit_review.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đánh giá của bạn đã được gửi thành công!');
                    location.reload(); // Tải lại trang để hiển thị đánh giá mới
                } else {
                    alert('Lỗi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                alert('Không thể gửi đánh giá. Vui lòng thử lại.');
            });
    });

    $(document).ready(function() {
        $('.top-products-carousel').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            arrows: true,
            dots: true,
            infinite: true,
            draggable: true, // Enable dragging with mouse
        });
    });
</script>

<?php
$content = ob_get_clean();
include('layout.php');
?>