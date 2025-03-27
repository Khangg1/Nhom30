<?php
require_once 'db.php';

$response = ['success' => false, 'message' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $madonhang = $_POST['madonhang'] ?? null;
    $trangthai_giaohang = $_POST['trangthai_giaohang'] ?? null;
    $trangthai_thanhtoan = $_POST['trangthai_thanhtoan'] ?? null;

    if ($madonhang && $trangthai_giaohang && $trangthai_thanhtoan) {
        try {
            $stmt = $conn->prepare("
                UPDATE donhang
                SET matrangthai_giaohang = ?, matrangthai_thanhtoan = ?
                WHERE madonhang = ?
            ");
            $stmt->execute([$trangthai_giaohang, $trangthai_thanhtoan, $madonhang]);

            // Chuyển hướng sau khi cập nhật
            header('Location: http://localhost:8080/quanlydongho/Admin/quanlydonhang.php');
            exit;
        } catch (PDOException $e) {
            echo 'Lỗi: ' . $e->getMessage();
        }
    } else {
        echo 'Thiếu thông tin cần thiết để cập nhật trạng thái';
    }
}


header('Content-Type: application/json');
echo json_encode($response);
