<?php
session_start();
include('db.php');

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['mand'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để xóa sản phẩm yêu thích.']);
    exit;
}

// Kiểm tra nếu có ID sản phẩm trong yêu cầu
if (!isset($_POST['productId'])) {
    echo json_encode(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
    exit;
}

$productId = intval($_POST['productId']);
$userId = $_SESSION['mand'];

// Xóa sản phẩm khỏi danh sách yêu thích
$sql = "DELETE FROM yeuthich WHERE madh = :madh AND mand = :mand";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':madh', $productId, PDO::PARAM_INT);
$stmt->bindParam(':mand', $userId, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được xóa khỏi danh sách yêu thích.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi xóa sản phẩm.']);
}
