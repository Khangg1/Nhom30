<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['mand'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập để đánh giá']);
    exit();
}

$productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
$review = isset($_POST['review']) ? htmlspecialchars($_POST['review']) : '';

if ($productId > 0 && $rating >= 1 && $rating <= 5 && !empty($review)) {
    // Truy vấn để lưu đánh giá vào cơ sở dữ liệu
    $sql = "INSERT INTO danhgia (masp, mand, diem, noidung) VALUES (:productId, :mand, :diem, :noidung)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt->bindParam(':mand', $_SESSION['mand'], PDO::PARAM_INT);
    $stmt->bindParam(':diem', $rating, PDO::PARAM_INT);
    $stmt->bindParam(':noidung', $review, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Đánh giá của bạn đã được gửi thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi, vui lòng thử lại.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
}
