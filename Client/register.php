<?php
include('db.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = 'Mật khẩu và xác nhận mật khẩu không khớp!';
    } else {
        // Kiểm tra email đã tồn tại
        $sql = "SELECT * FROM nguoidung WHERE email = :email AND isdelete = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $error = 'Email đã được sử dụng!';
        } else {
            // Thêm người dùng mới
            $sql = "INSERT INTO nguoidung (tennguoidung, email, matkhau) VALUES (:username, :email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            if ($stmt->execute()) {
                $success = 'Đăng ký thành công! <a href="login.php">Đăng Nhập</a>';
            } else {
                $error = 'Đã xảy ra lỗi, vui lòng thử lại!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - TIMEPIECE</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&family=Cormorant+Garamond:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #d4af37;
            --secondary-color: #1a1a1a;
            --accent-color: #f4d03f;
            --error-color: #d32f2f;
            --success-color: #388e3c;
            --text-color: #333;
            --border-radius: 15px;
            --box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('images/luxury-bg.jpg');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
        }

        .auth-container {
            width: 100%;
            max-width: 520px;
            padding: 3.5rem;
            background: rgba(255, 255, 255, 0.98);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            position: relative;
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .auth-container:hover {
            transform: translateY(-5px) scale(1.01);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .logo {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 0.8s ease;
        }

        .logo h1 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--secondary-color);
            font-size: 3.5rem;
            margin: 0;
            letter-spacing: 4px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }

        .logo h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.4rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-group {
            position: relative;
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: var(--text-color);
            font-weight: 500;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-group input {
            width: 90%;
            padding: 1.2rem 3rem 1.2rem 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-color);
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.2);
            transform: translateY(-2px);
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .form-group:focus-within i {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .error,
        .success {
            padding: 1.2rem;
            border-radius: var(--border-radius);
            font-size: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
            font-weight: 500;
        }

        .error {
            background: rgba(244, 67, 54, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .success {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(76, 175, 80, 0.3);
        }

        button {
            width: 100%;
            background: linear-gradient(45deg, #1976d2, #2196f3);
            color: white;
            border: none;
            padding: 1.3rem;
            border-radius: var(--border-radius);
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.5);
        }

        button:hover::before {
            left: 100%;
        }

        p {
            text-align: center;
            color: #555;
            margin-top: 2.5rem;
            font-size: 1.1rem;
            font-weight: 400;
        }

        a {
            color: blue;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(45deg, #1976d2, #2196f3);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        a:hover {
            color: linear-gradient(45deg, #1976d2, #2196f3);
        }

        a:hover::after {
            transform: scaleX(1);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        @media (max-width: 768px) {
            .auth-container {
                margin: 1rem;
                padding: 2rem;
            }

            .logo h1 {
                font-size: 2.8rem;
            }

            h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 1.5rem;
            }

            .logo h1 {
                font-size: 2.4rem;
            }

            h2 {
                font-size: 1.8rem;
            }

            .form-group input {
                padding: 1rem 2.5rem 1rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">

        <form method="POST" action="register.php">
            <h2>Đăng Ký Tài Khoản</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="username">Tên Người Dùng</label>
                <input type="text" id="username" name="username" required placeholder="Nhập tên của bạn">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Nhập địa chỉ email">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu">
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác Nhận Mật Khẩu</label>
                <input type="password" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu">
            </div>
            <button type="submit">Đăng Ký</button>
            <p>Đã có tài khoản? <a href="login.php" style="color:linear-gradient(45deg, #1976d2, #2196f3)">Đăng Nhập Ngay</a></p>
        </form>
    </div>
</body>

</html>