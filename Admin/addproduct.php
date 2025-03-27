<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Lấy danh sách loại đồng hồ
$stmt = $conn->prepare("SELECT * FROM loaidongho WHERE isdelete = 0");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý thêm sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tendongho = $_POST['tendongho'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $maloaidh = $_POST['maloaidh'];

    // Xử lý upload hình ảnh
    $hinhanh = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['hinhanh']['tmp_name'];
        $file_name = $_FILES['hinhanh']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Kiểm tra extension
        $allowed_exts = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($file_ext, $allowed_exts)) {
            // Tạo tên file mới để tránh trùng lặp
            $new_file_name = uniqid() . '.' . $file_ext;
            // Di chuyển file vào thư mục images
            move_uploaded_file($file_tmp, "../Client/images/" . $new_file_name);
            $hinhanh = $new_file_name;
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO dongho (tendongho, mota, hinhanh, gia, maloaidh) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$tendongho, $mota, $hinhanh, $gia, $maloaidh]);
        header('Location: quanlysanpham.php');
        exit;
    } catch (PDOException $e) {
        $error = "Có lỗi xảy ra: " . $e->getMessage();
    }
}

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Thêm Sản phẩm mới</h1>
    </div>

    <form method="POST" enctype="multipart/form-data" class="product-form">
        <div class="form-group">
            <label for="tendongho">Tên đồng hồ:</label>
            <input type="text" id="tendongho" name="tendongho" required>
        </div>

        <div class="form-group">
            <label for="mota">Mô tả:</label>
            <textarea id="mota" name="mota" required></textarea>
        </div>

        <div class="form-group">
            <label for="hinhanh">Hình ảnh:</label>
            <input type="file" id="hinhanh" name="hinhanh" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="gia">Giá:</label>
            <input type="number" id="gia" name="gia" required>
        </div>

        <div class="form-group">
            <label for="maloaidh">Loại đồng hồ:</label>
            <select id="maloaidh" name="maloaidh" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['maloaidh']; ?>">
                        <?php echo $category['tenloai']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-buttons">
            <button type="submit" class="submit-btn">Thêm sản phẩm</button>
            <a href="quanlysanpham.php" class="cancel-btn">Hủy</a>
        </div>
    </form>
</div>

<style>
    .container {
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-header h1 {
        color: #2c3e50;
        font-size: 2rem;
        margin: 0;
    }

    .product-form {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #2c3e50;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .form-group textarea {
        height: 150px;
        resize: vertical;
    }

    .form-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .submit-btn,
    .cancel-btn {
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
    }

    .submit-btn {
        background: #2ecc71;
        color: white;
        border: none;
        cursor: pointer;
    }

    .cancel-btn {
        background: #e74c3c;
        color: white;
    }

    .submit-btn:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }

    .cancel-btn:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>