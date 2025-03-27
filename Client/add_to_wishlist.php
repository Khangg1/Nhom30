<?php
session_start();
include('db.php');  // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['mand'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập trước.']);
    exit;
}

if (isset($_POST['productId'])) {
    $productId = intval($_POST['productId']);
    $userId = $_SESSION['mand'];  // Lấy ID người dùng từ session

    // Kiểm tra nếu sản phẩm đã có trong danh sách yêu thích chưa
    $sql = "SELECT COUNT(*) FROM yeuthich WHERE madh = :madh";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':madh', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Sản phẩm đã có trong danh sách yêu thích.']);
    } else {
        // Thêm sản phẩm vào danh sách yêu thích
        $sql = "INSERT INTO yeuthich (mand, madh) VALUES (:mand, :madh)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':mand', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':madh', $productId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra. Vui lòng thử lại.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ.']);
}
