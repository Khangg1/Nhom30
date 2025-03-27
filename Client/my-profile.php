<?php
session_start();
include('db.php');

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin người dùng từ session
$userId = $_SESSION['user']['id'];

// Truy vấn thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT * FROM nguoidung WHERE mand = :mand";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':mand', $userId, PDO::PARAM_INT);
$stmt->execute();
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy người dùng
if (!$userInfo) {
    echo "Không tìm thấy thông tin người dùng.";
    exit;
}

// Nội dung HTML
ob_start();
?>

<div class="profile-container">
    <div class="profile-header">
        <h2><i class="fas fa-user-circle"></i> Thông Tin Cá Nhân</h2>
    </div>

    <div class="profile-card">
        <div class="profile-avatar">
            <img src="https://publish-p47754-e237306.adobeaemcloud.com/adobe/dynamicmedia/deliver/dm-aid--914bcfe0-f610-4610-a77e-6ea53c53f630/_330603286208.app.webp?preferwebp=true&width=312" alt="Avatar" class="avatar-image" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
        </div>

        <div class="profile-details">
            <div class="info-group">
                <label><i class="fas fa-user"></i> Tên người dùng</label>
                <p><?php echo htmlspecialchars($userInfo['tennguoidung']); ?></p>
            </div>

            <div class="info-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <p><?php echo htmlspecialchars($userInfo['email']); ?></p>
            </div>

            <div class="info-group">
                <label><i class="fas fa-id-card"></i> ID</label>
                <p>#<?php echo htmlspecialchars($userInfo['mand']); ?></p>
            </div>
        </div>

        <!-- <div class="profile-actions">
            <a href="edit-profile.php" class="edit-btn">
                <i class="fas fa-edit"></i> Chỉnh sửa thông tin
            </a>
        </div> -->
    </div>
</div>

<style>
    .profile-container {
        padding: 2rem;
        max-width: 800px;
        margin: 2rem auto;
    }

    .profile-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-header h2 {
        font-family: 'Playfair Display', serif;
        color: #2c3e50;
        font-size: 2rem;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .profile-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        position: relative;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        background: #f0f2f5;
        border-radius: 50%;
        margin: 0 auto 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-avatar i {
        font-size: 3rem;
        color: #1976d2;
    }

    .profile-details {
        max-width: 500px;
        margin: 0 auto;
    }

    .info-group {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }

    .info-group:last-child {
        border-bottom: none;
    }

    .info-group label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .info-group p {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 500;
        margin: 0;
    }

    .profile-actions {
        text-align: center;
        margin-top: 2rem;
    }

    .edit-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 2rem;
        background: #1976d2;
        color: #fff;
        text-decoration: none;
        border-radius: 25px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(25, 118, 210, 0.2);
    }

    .edit-btn:hover {
        background: #1565c0;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem;
            margin: 1rem;
        }

        .profile-card {
            padding: 1.5rem;
        }

        .profile-header h2 {
            font-size: 1.5rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
include('layout.php');
?>