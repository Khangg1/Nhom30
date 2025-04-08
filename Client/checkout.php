<?php
session_start();
include('db.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Kiểm tra xem giỏ hàng có trống không
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

$userId = $_SESSION['user']['id']; // Lấy ID người dùng từ session
$cartItems = $_SESSION['cart']; // Lấy giỏ hàng từ session

// Kiểm tra dữ liệu thanh toán từ form
$paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
$deliveryAddress = isset($_POST['address']) ? $_POST['address'] : '123 Đường ABC, Thành phố XYZ'; // Có thể lấy từ form

//Sửa dfgdfghdgd
// Bắt đầu transaction
$conn->beginTransaction();

try {
    // Tính tổng tiền từ giỏ hàng
    $totalAmount = 0;
    foreach ($cartItems as $item) {
        $totalAmount += $item['quantity'] * getProductPrice($item['productId'], $conn);
    }

    // Thêm đơn hàng vào bảng `donhang`
    $sqlInsertDonHang = "INSERT INTO donhang (mand, ngaydat, tongtien, diachigiaohang, matrangthai_giaohang, matrangthai_thanhtoan, phuongthucthanhtoan)
                         VALUES (:mand, GETDATE(), :tongtien, :diachigiaohang, 1, 1, :phuongthucthanhtoan)";
    $stmtDonHang = $conn->prepare($sqlInsertDonHang);
    $stmtDonHang->execute([
        ':mand' => $userId,
        ':tongtien' => $totalAmount,
        ':diachigiaohang' => $deliveryAddress,
        ':phuongthucthanhtoan' => $paymentMethod
    ]);

    // Lấy ID đơn hàng vừa thêm
    $donHangId = $conn->lastInsertId();

    // Thêm chi tiết đơn hàng vào bảng `chitietdonhang`
    $sqlInsertChiTiet = "INSERT INTO chitietdonhang (madh, madonhang, soluong, dongia, size)
                         VALUES (:madh, :madonhang, :soluong, :dongia, :size)";
    $stmtChiTiet = $conn->prepare($sqlInsertChiTiet);

    foreach ($cartItems as $item) {
        $productPrice = getProductPrice($item['productId'], $conn);
        $stmtChiTiet->execute([
            ':madh' => $item['productId'],
            ':madonhang' => $donHangId,
            ':soluong' => $item['quantity'],
            ':dongia' => $productPrice,
            ':size' => $item['size']
        ]);
    }

    // Hoàn thành transaction
    $conn->commit();

    // Xóa giỏ hàng
    unset($_SESSION['cart']);

    if ( $paymentMethod == "atm" ) {
        vnpayPayment($totalAmount);
    }

    // Chuyển hướng đến trang thông báo thành công
    header('Location: success.php');
    exit;
} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollBack();
    die("Lỗi khi đặt hàng: " . $e->getMessage());
}

// Hàm lấy giá sản phẩm từ cơ sở dữ liệu
function getProductPrice($productId, $conn)
{
    $sql = "SELECT gia FROM dongho WHERE madh = :productId";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':productId' => $productId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['gia'] : 0;
}

function vnpayPayment($totalAmount)
{
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "http://localhost:8080/quanlydongho/Client/cart.php"; // Thay url  vào đây
    $vnp_TmnCode = "VI1OLR26"; //Mã website tại VNPAY 
    $vnp_HashSecret = "SO86DF6B0ID6FO5E286CM31R7QE7VL43"; //Chuỗi bí mật 

    $vnp_TxnRef = uniqid();
    $vnp_OrderInfo = $_POST['order_desc'] ?? 'Thanh toán đơn hàng';
    $vnp_OrderType = $_POST['order_type'] ?? 'vnpayment';

    $vnp_Amount = (int)$totalAmount * 100;
    $vnp_Locale = $_POST['language'] ?? 'vn';
    $vnp_BankCode = $_POST['bank_code'] ?? 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    // $vnp_ExpireDate = $_POST['txtexpire'];
    $vnp_ExpireDate = date('YmdHis', strtotime('+10 minutes'));

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,

    );
    
    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
        $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    }
    
    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        if ($i == 1) {
            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
        } else {
            $hashdata .= urlencode($key) . "=" . urlencode($value);
            $i = 1;
        }
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }
    
    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    $returnData = array('code' => '00'
        , 'message' => 'success'
        , 'data' => $vnp_Url);

        header('Location: ' . $vnp_Url);
        die();
       
}

