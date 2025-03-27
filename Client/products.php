<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Lấy giá trị category từ URL (nếu có)
$category_id = isset($_GET['category']) ? intval($_GET['category']) : null;

// Câu truy vấn để lấy danh sách đồng hồ
if ($category_id) {
    // Lọc theo loại đồng hồ
    $sql = "SELECT dongho.madh, dongho.tendongho, dongho.mota, dongho.hinhanh, dongho.gia, loaidongho.tenloai 
            FROM dongho
            INNER JOIN loaidongho ON dongho.maloaidh = loaidongho.maloaidh
            WHERE dongho.isdelete = 0 AND dongho.maloaidh = :category_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
} else {
    // Hiển thị tất cả đồng hồ
    $sql = "SELECT dongho.madh, dongho.tendongho, dongho.mota, dongho.hinhanh, dongho.gia, loaidongho.tenloai 
            FROM dongho
            INNER JOIN loaidongho ON dongho.maloaidh = loaidongho.maloaidh
            WHERE dongho.isdelete = 0";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$donghoList = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tạo nội dung hiển thị danh sách đồng hồ
ob_start();
?>

<!-- Thêm thanh tìm kiếm -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Tìm kiếm đồng hồ..." onkeyup="searchProducts()">
</div>

<!-- Thêm ngay sau phần search-container và trước product-list -->
<div class="filter-container">
    <select id="sortSelect" onchange="applyFilters()">
        <option value="">Sắp xếp theo</option>
        <option value="name_asc">Tên A-Z</option>
        <option value="name_desc">Tên Z-A</option>
        <option value="price_asc">Giá: Thấp đến Cao</option>
        <option value="price_desc">Giá: Cao đến Thấp</option>
    </select>

    <div class="price-filter">
        <input type="number" id="minPrice" placeholder="Giá từ" onchange="applyFilters()">
        <span>-</span>
        <input type="number" id="maxPrice" placeholder="Giá đến" onchange="applyFilters()">
    </div>
</div>

<div class="product-list">
    <?php foreach ($donghoList as $dongho): ?>
        <div class="product-card">
            <div class="product-badge">New</div>
            <div class="product-image-container">
                <div class="product-image">
                    <img src="images/<?php echo htmlspecialchars($dongho['hinhanh']); ?>"
                        alt="<?php echo htmlspecialchars($dongho['tendongho']); ?>">
                </div>
                <div class="product-overlay">
                    <button class="quick-view-btn" title="Xem nhanh" onclick="window.location.href='product_detail.php?id=<?php echo $dongho['madh']; ?>'">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="add-to-wishlist-btn" title="Yêu thích" onclick="addToWishlist(<?php echo $dongho['madh']; ?>)">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
            <div class="product-info">
                <span class="product-category"><?php echo htmlspecialchars($dongho['tenloai']); ?></span>
                <h3 class="product-title"><?php echo htmlspecialchars($dongho['tendongho']); ?></h3>
                <div class="rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                    <span class="rating-count">(4.5)</span>
                </div>
                <p class="product-description"><?php echo htmlspecialchars($dongho['mota']); ?></p>
                <div class="product-footer">
                    <div class="price-wrapper">
                        <strong class="product-price"><?php echo number_format($dongho['gia'], 0, ',', '.'); ?> VNĐ</strong>
                        <span class="original-price">₫2,990,000</span>
                    </div>
                    <div class="button-group">
                        <button class="add-to-cart-btn" onclick="addToCart(<?php echo $dongho['madh']; ?>)">
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .search-container {
        margin: 20px auto;
        max-width: 600px;
        padding: 0 20px;
    }

    #searchInput {
        width: 100%;
        padding: 12px 20px;
        font-size: 16px;
        border: 2px solid #ddd;
        border-radius: 25px;
        outline: none;
        transition: all 0.3s ease;
    }

    #searchInput:focus {
        border-color: #1976d2;
        box-shadow: 0 0 8px rgba(25, 118, 210, 0.2);
    }

    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 2rem;
        background: #f8f9fa;
    }

    .product-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .product-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #ff4757;
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 1;
    }

    .product-image-container {
        width: 100%;
        position: relative;
        margin-bottom: 1.5rem;
        padding: 1rem;
    }

    .product-image {
        width: 200px;
        height: 200px;
        margin: 0 auto;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .product-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        gap: 1rem;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .quick-view-btn,
    .add-to-wishlist-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .quick-view-btn:hover,
    .add-to-wishlist-btn:hover {
        background: #1976d2;
        color: white;
        transform: translateY(-3px);
    }

    .product-info {
        width: 100%;
        text-align: center;
    }

    .product-category {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.4rem 1rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .product-title {
        font-size: 1.2rem;
        color: #2d3436;
        margin: 0.5rem 0;
        font-weight: 600;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rating {
        color: #ffd700;
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.2rem;
    }

    .rating-count {
        color: #666;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .product-description {
        color: #666;
        font-size: 0.9rem;
        margin: 0.5rem 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .price-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 1rem;
    }

    .product-price {
        color: #1976d2;
        font-size: 1.4rem;
        font-weight: 700;
    }

    .original-price {
        color: #999;
        text-decoration: line-through;
        font-size: 0.9rem;
    }

    .add-to-cart-btn {
        background: linear-gradient(45deg, #1976d2, #2196f3);
        color: white;
        border: none;
        padding: 0.8rem 2rem;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(45deg, #1565c0, #1976d2);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(25, 118, 210, 0.3);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .product-list {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
        }

        .product-image {
            width: 150px;
            height: 150px;
        }
    }

    .filter-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        margin: 20px auto;
        max-width: 800px;
        padding: 0 20px;
    }

    .filter-container select {
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
        min-width: 200px;
    }

    .price-filter {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .price-filter input {
        width: 150px;
        padding: 10px;
        border: 2px solid #ddd;
        border-radius: 8px;
        outline: none;
        font-size: 14px;
    }

    .filter-container select:focus,
    .price-filter input:focus {
        border-color: #1976d2;
        box-shadow: 0 0 8px rgba(25, 118, 210, 0.2);
    }

    @media (max-width: 768px) {
        .filter-container {
            flex-direction: column;
            gap: 10px;
        }

        .price-filter {
            width: 100%;
        }

        .price-filter input {
            width: 100%;
        }
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function searchProducts() {
        const searchInput = document.getElementById('searchInput');
        const filter = searchInput.value.toLowerCase();
        const productCards = document.getElementsByClassName('product-card');

        for (let i = 0; i < productCards.length; i++) {
            const title = productCards[i].querySelector('.product-title').textContent.toLowerCase();
            const description = productCards[i].querySelector('.product-description').textContent.toLowerCase();
            const category = productCards[i].querySelector('.product-category').textContent.toLowerCase();

            if (title.includes(filter) || description.includes(filter) || category.includes(filter)) {
                productCards[i].style.display = "";
            } else {
                productCards[i].style.display = "none";
            }
        }
    }

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
</script>

<script>
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
</script>

<script>
    function applyFilters() {
        const productCards = document.getElementsByClassName('product-card');
        const sortValue = document.getElementById('sortSelect').value;
        const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
        const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;

        // Chuyển NodeList thành Array để có thể sắp xếp
        let productsArray = Array.from(productCards);

        // Lọc theo khoảng giá
        productsArray.forEach(card => {
            const priceText = card.querySelector('.product-price').textContent;
            const price = parseFloat(priceText.replace(/[^\d]/g, '')); // Lấy số từ chuỗi giá

            if (price >= minPrice && price <= maxPrice) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });

        // Sắp xếp
        if (sortValue) {
            productsArray.sort((a, b) => {
                const nameA = a.querySelector('.product-title').textContent;
                const nameB = b.querySelector('.product-title').textContent;
                const priceA = parseFloat(a.querySelector('.product-price').textContent.replace(/[^\d]/g, ''));
                const priceB = parseFloat(b.querySelector('.product-price').textContent.replace(/[^\d]/g, ''));

                switch (sortValue) {
                    case 'name_asc':
                        return nameA.localeCompare(nameB);
                    case 'name_desc':
                        return nameB.localeCompare(nameA);
                    case 'price_asc':
                        return priceA - priceB;
                    case 'price_desc':
                        return priceB - priceA;
                    default:
                        return 0;
                }
            });

            // Sắp xếp lại DOM
            const productList = document.querySelector('.product-list');
            productsArray.forEach(card => {
                productList.appendChild(card);
            });
        }
    }
</script>

<?php
$content = ob_get_clean();
include('layout.php');
