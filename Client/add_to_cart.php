<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['mand'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập trước.']);
    exit;
}

// Kiểm tra phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy ID sản phẩm từ yêu cầu
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;

    // Kiểm tra ID sản phẩm hợp lệ
    if ($productId <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'ID sản phẩm không hợp lệ.'
        ]);
        exit;
    }

    // Khởi tạo giỏ hàng nếu chưa có
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra sản phẩm đã có trong giỏ hàng hay chưa
    if (isset($_SESSION['cart'][$productId])) {
        // Tăng số lượng sản phẩm nếu đã có
        $_SESSION['cart'][$productId]['quantity']++;
    } else {
        // Thêm sản phẩm mới vào giỏ hàng
        $_SESSION['cart'][$productId] = [
            'productId' => $productId,
            'quantity' => 1
        ];
    }

    // Tính tổng số lượng sản phẩm trong giỏ hàng
    $cartTotal = array_sum(array_column($_SESSION['cart'], 'quantity'));

    // Trả về kết quả JSON
    echo json_encode([
        'success' => true,
        'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
        'cartTotal' => $cartTotal
    ]);
    exit;
}

// Nếu không phải phương thức POST, trả về lỗi
http_response_code(405);
echo json_encode([
    'success' => false,
    'message' => 'Phương thức không được hỗ trợ.'
]);
