<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['mand'])) {
    header("Location: login.php");
    exit();
}

$madh = isset($_GET['madh']) ? intval($_GET['madh']) : null;

// Lấy thông tin sản phẩm
$sql_product = "SELECT tendongho FROM dongho WHERE madh = ? AND isdelete = 0";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->execute([$madh]);
$product = $stmt_product->fetch();

if (!$product) {
    header("Location: products.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noidung = $_POST['review'];
    $diem = intval($_POST['rating']);
    $mand = $_SESSION['mand'];

    $sql = "INSERT INTO DanhGia (masp, manguoidung, noidung, diem) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$madh, $mand, $noidung, $diem]);

    header("Location: product_detail.php?madh=$madh");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Viết đánh giá - <?php echo htmlspecialchars($product['tendongho']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .review-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .review-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .review-textarea {
            width: 100%;
            min-height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }

        .rating-container {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            gap: 5px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            font-size: 25px;
            color: #ddd;
        }

        .star-rating label:hover,
        .star-rating label:hover~label,
        .star-rating input:checked~label {
            color: #ffd700;
        }

        .submit-btn {
            padding: 10px 20px;
            background: #1976d2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background: #1565c0;
        }
    </style>
</head>

<body>
    <div class="review-container">
        <h1>Viết đánh giá cho <?php echo htmlspecialchars($product['tendongho']); ?></h1>
        <form method="POST" class="review-form">
            <div>
                <label for="review">Nội dung đánh giá:</label>
                <textarea
                    name="review"
                    id="review"
                    class="review-textarea"
                    required
                    placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
            </div>

            <div class="rating-container">
                <label>Đánh giá:</label>
                <div class="star-rating">
                    <input type="radio" name="rating" value="5" id="star5" required>
                    <label for="star5" title="5 sao"><i class="fas fa-star"></i></label>

                    <input type="radio" name="rating" value="4" id="star4">
                    <label for="star4" title="4 sao"><i class="fas fa-star"></i></label>

                    <input type="radio" name="rating" value="3" id="star3">
                    <label for="star3" title="3 sao"><i class="fas fa-star"></i></label>

                    <input type="radio" name="rating" value="2" id="star2">
                    <label for="star2" title="2 sao"><i class="fas fa-star"></i></label>

                    <input type="radio" name="rating" value="1" id="star1">
                    <label for="star1" title="1 sao"><i class="fas fa-star"></i></label>
                </div>
            </div>

            <button type="submit" class="submit-btn">Gửi đánh giá</button>
        </form>
    </div>
</body>

</html>