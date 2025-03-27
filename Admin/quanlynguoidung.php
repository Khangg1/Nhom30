<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<?php
session_start();
require_once 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

// Xử lý cập nhật trạng thái người dùng
if (isset($_POST['update_status'])) {
    $mand = $_POST['mand'];
    $trangthai = $_POST['trangthai'];
    $stmt = $conn->prepare("UPDATE nguoidung SET isdelete = ? WHERE mand = ?");
    $stmt->execute([$trangthai, $mand]);
    header('Location: quanlynguoidung.php');
    exit;
}

// Truy vấn danh sách người dùng
$stmt = $conn->prepare("
    SELECT 
        mand,
        tennguoidung,
        email,
        matkhau,
        isdelete
    FROM nguoidung
    ORDER BY tennguoidung
");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

ob_start();
?>

<div class="container">
    <div class="page-header">
        <h1>Quản lý Người dùng</h1>
    </div>
    <div class="table-responsive">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Mã ND</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Mật khẩu</th>
                    <th>Trạng thái</th>
                    <th>Thao tác </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['mand']; ?></td>
                        <td><?php echo $user['tennguoidung']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo $user['matkhau']; ?></td>
                        <td>
                            <select name="trangthai" class="status-select" onchange="updateStatus(<?php echo $user['mand']; ?>, this.value)">
                                <option value="0" <?php if ($user['isdelete'] == 0) echo 'selected'; ?>>Hoạt động</option>
                                <option value="1" <?php if ($user['isdelete'] == 1) echo 'selected'; ?>>Khóa</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-success edit-btn" onclick="editUser(<?php echo $user['mand']; ?>)" data-toggle="tooltip" data-placement="top" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger delete-btn" onclick="deleteUser(<?php echo $user['mand']; ?>)" data-toggle="tooltip" data-placement="top" title="Xóa">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal chi tiết người dùng -->
<div id="userDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Chi tiết người dùng</h2>
        <div id="userDetailsContent"></div>
    </div>
</div>

<script>
    function updateStatus(mand, trangthai) {
        if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái của người dùng này không?')) {
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'quanlynguoidung.php';
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'update_status';
            input.value = '1';
            form.appendChild(input);
            var input2 = document.createElement('input');
            input2.type = 'hidden';
            input2.name = 'mand';
            input2.value = mand;
            form.appendChild(input2);
            var input3 = document.createElement('input');
            input3.type = 'hidden';
            input3.name = 'trangthai';
            input3.value = trangthai;
            form.appendChild(input3);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

<style>
    .container {
        padding: 2rem;
        max-width: 1400px;
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

    .table-responsive {
        overflow-x: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .user-table th,
    .user-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .user-table th {
        background: #f8f9fa;
        color: #2c3e50;
        font-weight: 600;
    }

    .user-table tr:hover {
        background: #f8f9fa;
    }

    .status-select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        background: white;
        width: 100%;
    }

    .view-details-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .view-details-btn:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 12px;
        width: 80%;
        max-width: 800px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 1rem;
        top: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }

        .user-table {
            font-size: 0.9rem;
        }

        .status-select {
            font-size: 0.9rem;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<?php
$content = ob_get_clean();
include('layoutadmin.php');
?>