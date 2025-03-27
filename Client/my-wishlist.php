<?php
session_start();
include('db.php');

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['mand'])) {
    header('Location: login.php');
    exit;
}

// Lấy ID người dùng từ session
$userId = $_SESSION['mand'];

// Truy vấn danh sách sản phẩm yêu thích của người dùng
$sql = "SELECT sp.madh, sp.tendongho, sp.gia, sp.hinhanh
        FROM yeuthich yt
        JOIN dongho sp ON yt.madh = sp.madh
        WHERE yt.mand = :mand AND sp.isdelete = 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':mand', $userId, PDO::PARAM_INT);
$stmt->execute();
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm yêu thích - TIMEPIECE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .favorites-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product-card h3 {
            margin: 10px 0;
            font-size: 1.1em;
        }

        .product-card p {
            color: #d4af37;
            font-weight: bold;
            margin: 10px 0;
        }

        .remove-btn {
            background: #ff4444;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .remove-btn:hover {
            background: #cc0000;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>

<body>
    <?php
    $content = '
    <div class="favorites-container">
        <h2>Sản Phẩm Yêu Thích</h2>
        <div id="wishlistContainer">';

    if (!empty($favorites)) {
        $content .= '<div class="product-list">';
        foreach ($favorites as $favorite) {
            $content .= '
            <div class="product-card" id="product-' . $favorite['madh'] . '">
                <img src="images/' . htmlspecialchars($favorite['hinhanh']) . '" alt="' . htmlspecialchars($favorite['tendongho']) . '">
                <h3>' . htmlspecialchars($favorite['tendongho']) . '</h3>
                <p>' . number_format($favorite['gia'], 0, ',', '.') . ' VNĐ</p>
                <button class="remove-btn" onclick="removeFromWishlist(' . $favorite['madh'] . ')">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>';
        }
        $content .= '</div>';
    } else {
        $content .= '<p>Chưa có sản phẩm yêu thích.</p>';
    }

    $content .= '</div>
    </div>
    <script>
        async function removeFromWishlist(productId) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này khỏi danh sách yêu thích?")) {
                try {
                    const response = await fetch("remove_from_wishlist.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: "productId=" + productId
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        const productCard = document.getElementById(`product-${productId}`);
                        productCard.classList.add("fade-out");
                        
                        setTimeout(() => {
                            productCard.remove();
                            
                            // Kiểm tra nếu không còn sản phẩm nào
                            const wishlistContainer = document.getElementById("wishlistContainer");
                            if (!wishlistContainer.querySelector(".product-card")) {
                                wishlistContainer.innerHTML = "<p>Chưa có sản phẩm yêu thích.</p>";
                            }
                        }, 500);
                    } else {
                        alert(data.message || "Có lỗi xảy ra khi xóa sản phẩm!");
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert("Đã có lỗi xảy ra. Vui lòng thử lại sau.");
                }
            }
        }
    </script>';

    include('layout.php');
    ?>
</body>

</html>