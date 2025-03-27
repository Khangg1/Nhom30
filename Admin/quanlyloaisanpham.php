<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Xử lý xóa loại sản phẩm
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("UPDATE loaidongho SET isdelete = 1 WHERE maloaidh = ?");
    $stmt->execute([$id]);
    header('Location: quanlyloaisanpham.php');
    exit;
}

// Truy vấn danh sách loại sản phẩm
$stmt = $conn->prepare("SELECT * FROM loaidongho WHERE isdelete = 0");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý Loại Sản phẩm</h1>
        <a href="addcategory.php" class="add-category-btn">
            <i class="fas fa-plus"></i> Thêm loại sản phẩm mới
        </a>
    </div>

    <div class="table-responsive">
        <table class="category-table">
            <thead>
                <tr>
                    <th>Mã loại</th>
                    <th>Tên loại</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) : ?>
                    <tr>
                        <td><?php echo $category['maloaidh']; ?></td>
                        <td class="category-name"><?php echo $category['tenloai']; ?></td>
                        <td class="action-btns">
                            <a href="editcategory.php?id=<?php echo $category['maloaidh']; ?>" class="edit-btn">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="quanlyloaisanpham.php?delete=<?php echo $category['maloaidh']; ?>"
                                class="delete-btn"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa loại sản phẩm này?');">
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
        max-width: 1200px;
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

    .add-category-btn {
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

    .add-category-btn:hover {
        background: #27ae60;
        transform: translateY(-2px);
    }

    .table-responsive {
        overflow-x: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .category-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .category-table th,
    .category-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .category-table th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
    }

    .category-table tr:hover {
        background: #f8f9fa;
    }

    .category-name {
        font-weight: 500;
        color: #2c3e50;
    }

    .category-desc {
        color: #7f8c8d;
        max-width: 400px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-btns {
        display: flex;
        gap: 0.5rem;
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

        .category-desc {
            max-width: 200px;
        }

        .action-btns {
            flex-direction: column;
        }
    }
</style>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>