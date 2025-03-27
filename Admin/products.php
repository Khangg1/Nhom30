<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['maadmin'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';
require_once 'layoutadmin.php';

// Xử lý thêm sản phẩm mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $tendongho = $_POST['tendongho'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $maloaidh = $_POST['maloaidh'];

    // Xử lý upload hình ảnh
    $hinhanh = '';
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === 0) {
        $target_dir = "/images/";
        $hinhanh = time() . '_' . basename($_FILES['hinhanh']['name']);
        $target_file = $target_dir . $hinhanh;
        move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file);
    }

    $stmt = $conn->prepare("INSERT INTO dongho (tendongho, mota, hinhanh, gia, maloaidh) VALUES (:tendongho, :mota, :hinhanh, :gia, :maloaidh)");
    $stmt->execute([
        ':tendongho' => $tendongho,
        ':mota' => $mota,
        ':hinhanh' => $hinhanh,
        ':gia' => $gia,
        ':maloaidh' => $maloaidh
    ]);
}

// Xử lý xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $madh = $_POST['madh'];
    $stmt = $conn->prepare("UPDATE dongho SET isdelete = 1 WHERE madh = :madh");
    $stmt->execute([':madh' => $madh]);
}

// Xử lý cập nhật sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $madh = $_POST['madh'];
    $tendongho = $_POST['tendongho'];
    $mota = $_POST['mota'];
    $gia = $_POST['gia'];
    $maloaidh = $_POST['maloaidh'];

    $sql = "UPDATE dongho SET tendongho = :tendongho, mota = :mota, gia = :gia, maloaidh = :maloaidh";
    $params = [
        ':madh' => $madh,
        ':tendongho' => $tendongho,
        ':mota' => $mota,
        ':gia' => $gia,
        ':maloaidh' => $maloaidh
    ];

    // Kiểm tra nếu có upload hình ảnh mới
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === 0) {
        $target_dir = "/images/";
        $hinhanh = time() . '_' . basename($_FILES['hinhanh']['name']);
        $target_file = $target_dir . $hinhanh;
        move_uploaded_file($_FILES['hinhanh']['tmp_name'], $target_file);

        $sql .= ", hinhanh = :hinhanh";
        $params[':hinhanh'] = $hinhanh;
    }

    $sql .= " WHERE madh = :madh";
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
}

// Lấy danh sách loại đồng hồ
$stmt = $conn->query("SELECT * FROM loaidongho WHERE isdelete = 0");
$loaidongho = $stmt->fetchAll();

// Lấy danh sách sản phẩm
$stmt = $conn->query("SELECT d.*, l.tenloai FROM dongho d 
                     LEFT JOIN loaidongho l ON d.maloaidh = l.maloaidh 
                     WHERE d.isdelete = 0");
$products = $stmt->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid mt-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h2 class="m-0 font-weight-bold text-primary">Quản lý sản phẩm</h2>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
                <i class="fas fa-plus"></i> Thêm sản phẩm mới
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Hình ảnh</th>
                            <th>Tên đồng hồ</th>
                            <th>Mô tả</th>
                            <th>Giá</th>
                            <th>Loại</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['madh']; ?></td>
                                <td class="text-center">
                                    <img src="./images/<?php echo $product['hinhanh']; ?>"
                                        alt="<?php echo $product['tendongho']; ?>"
                                        class="img-thumbnail"
                                        style="max-width: 80px;">
                                </td>
                                <td class="font-weight-bold"><?php echo $product['tendongho']; ?></td>
                                <td><?php echo $product['mota']; ?></td>
                                <td class="text-right font-weight-bold text-primary">
                                    <?php echo number_format($product['gia'], 0, ',', '.'); ?>đ
                                </td>
                                <td>
                                    <span class="badge badge-info"><?php echo $product['tenloai']; ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-outline-info edit-product"
                                            data-id="<?php echo $product['madh']; ?>"
                                            data-toggle="modal"
                                            data-target="#editProductModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger delete-product"
                                            data-id="<?php echo $product['madh']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm sản phẩm -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm sản phẩm mới</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="form-group">
                        <label>Tên đồng hồ</label>
                        <input type="text" class="form-control" name="tendongho" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea class="form-control" name="mota" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" class="form-control-file" name="hinhanh" required>
                    </div>
                    <div class="form-group">
                        <label>Giá</label>
                        <input type="number" class="form-control" name="gia" required>
                    </div>
                    <div class="form-group">
                        <label>Loại đồng hồ</label>
                        <select class="form-control" name="maloaidh" required>
                            <?php foreach ($loaidongho as $loai): ?>
                                <option value="<?php echo $loai['maloaidh']; ?>">
                                    <?php echo $loai['tenloai']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal sửa sản phẩm -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="madh" id="edit-madh">
                    <div class="form-group">
                        <label>Tên đồng hồ</label>
                        <input type="text" class="form-control" name="tendongho" id="edit-tendongho" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea class="form-control" name="mota" id="edit-mota" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Hình ảnh</label>
                        <input type="file" class="form-control-file" name="hinhanh">
                        <small class="form-text text-muted">Chỉ chọn nếu muốn thay đổi hình ảnh</small>
                    </div>
                    <div class="form-group">
                        <label>Giá</label>
                        <input type="number" class="form-control" name="gia" id="edit-gia" required>
                    </div>
                    <div class="form-group">
                        <label>Loại đồng hồ</label>
                        <select class="form-control" name="maloaidh" id="edit-maloaidh" required>
                            <?php foreach ($loaidongho as $loai): ?>
                                <option value="<?php echo $loai['maloaidh']; ?>">
                                    <?php echo $loai['tenloai']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Xử lý xóa sản phẩm
        $('.delete-product').click(function() {
            if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
                var madh = $(this).data('id');
                $.post('products.php', {
                    action: 'delete',
                    madh: madh
                }, function() {
                    location.reload();
                });
            }
        });

        // Xử lý sửa sản phẩm
        $('.edit-product').click(function() {
            var madh = $(this).data('id');
            var row = $(this).closest('tr');

            $('#edit-madh').val(madh);
            $('#edit-tendongho').val(row.find('td:eq(2)').text());
            $('#edit-mota').val(row.find('td:eq(3)').text());
            $('#edit-gia').val(parseFloat(row.find('td:eq(4)').text().replace(/[^\d]/g, '')));
            $('#edit-maloaidh').val(row.find('td:eq(5)').data('maloaidh'));
        });
    });
</script>