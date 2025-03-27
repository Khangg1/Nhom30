<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin loại sản phẩm cần sửa
if (!isset($_GET['id'])) {
    header('Location: quanlyloaisanpham.php');
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM loaidongho WHERE maloaidh = ? AND isdelete = 0");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header('Location: quanlyloaisanpham.php');
    exit;
}

// Xử lý cập nhật loại sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenloai = trim($_POST['tenloai']);

    if (!empty($tenloai)) {
        $stmt = $conn->prepare("UPDATE loaidongho SET tenloai = ? WHERE maloaidh = ?");
        if ($stmt->execute([$tenloai, $id])) {
            header('Location: quanlyloaisanpham.php');
            exit;
        }
    }
}

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Sửa Loại Sản Phẩm</h1>
    </div>

    <div class="form-container">
        <form method="POST" class="category-form">
            <div class="form-group">
                <label for="tenloai">Tên loại sản phẩm:</label>
                <input type="text" id="tenloai" name="tenloai" value="<?php echo htmlspecialchars($category['tenloai']); ?>" required>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="quanlyloaisanpham.php" class="cancel-btn">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
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
    }

    .form-container {
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

    .form-group input {
        width: 100%;
        padding: 0.8rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .submit-btn,
    .cancel-btn {
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .submit-btn {
        background: #2ecc71;
        color: white;
    }

    .cancel-btn {
        background: #e74c3c;
        color: white;
    }

    .submit-btn:hover {
        background: #27ae60;
    }

    .cancel-btn:hover {
        background: #c0392b;
    }
</style>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>