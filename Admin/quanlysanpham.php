<!-- quanlysanpham.php -->
<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("UPDATE dongho SET isdelete = 1 WHERE madh = ?");
    $stmt->execute([$id]);
    header('Location: quanlysanpham.php');
    exit;
}

// Truy vấn danh sách sản phẩm
$stmt = $conn->prepare("SELECT * FROM dongho WHERE isdelete = 0");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý Sản phẩm</h1>
        <a href="addproduct.php" class="add-product-btn">
            <i class="fas fa-plus"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Mã đồng hồ</th>
                    <th>Tên đồng hồ</th>
                    <th>Mô tả</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Loại đồng hồ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo $product['madh']; ?></td>
                        <td class="product-name"><?php echo $product['tendongho']; ?></td>
                        <td class="product-desc"><?php echo substr($product['mota'], 0, 100) . '...'; ?></td>
                        <td>
                            <div class="product-image-container">
                                <img src="../Client/images/<?php echo htmlspecialchars($product['hinhanh']); ?>"
                                    alt="<?php echo htmlspecialchars($product['tendongho']); ?>"
                                    class="product-thumbnail">
                            </div>
                        </td>
                        <td class="product-price"><?php echo number_format($product['gia'], 0, ',', '.'); ?> ₫</td>
                        <td class="product-category">
                            <?php
                            $stmt = $conn->prepare("SELECT tenloai FROM loaidongho WHERE maloaidh = ?");
                            $stmt->execute([$product['maloaidh']]);
                            $loaidh = $stmt->fetch();
                            echo $loaidh['tenloai'];
                            ?>
                        </td>
                        <td class="action-btns">
                            <a href="editproduct.php?id=<?php echo $product['madh']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="quanlysanpham.php?delete=<?php echo $product['madh']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .container {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        color: #2c3e50;
        font-size: 2rem;
        margin: 0;
    }

    .add-product-btn {
        background: #2ecc71;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .add-product-btn:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }

    .table-responsive {
        overflow-x: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .product-table th,
    .product-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .product-table th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
    }

    .product-table tr:hover {
        background: #f8f9fa;
    }

    .product-image-container {
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        overflow: hidden;
        background: #f8f9fa;
        border: 2px solid #eee;
        transition: all 0.3s ease;
    }

    .product-image-container:hover {
        border-color: #3498db;
    }

    .product-thumbnail {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
    }

    .product-name {
        font-weight: 500;
        color: #2c3e50;
    }

    .product-desc {
        color: #7f8c8d;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-price {
        font-weight: 600;
        color: #e74c3c;
    }



    .edit-btn,
    .delete-btn {
        padding: 0.6rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .edit-btn {
        background-color: #3498db;
        color: white;
    }

    .delete-btn {
        background-color: #e74c3c;
        color: white;
    }

    .edit-btn:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
    }

    .delete-btn:hover {
        background-color: #c0392b;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .product-desc {
            max-width: 150px;
        }
    }
</style>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>