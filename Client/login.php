<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('db.php');

// Biến lưu thông báo lỗi
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kiểm tra email và mật khẩu
    $sql = "SELECT * FROM nguoidung WHERE email = :email AND matkhau = :password AND isdelete = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Lưu thông tin người dùng vào session
        $_SESSION['user'] = [
            'id' => $user['mand'],
            'tennguoidung' => $user['tennguoidung'],
            'email' => $user['email']
        ];
        $_SESSION['mand'] = $user['mand'];

        header('Location: products.php'); // Chuyển hướng về trang chủ
        exit;
    } else {
        $error = 'Email hoặc mật khẩu không chính xác!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - TIMEPIECE</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 25px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
        }

        .logo {
            text-align: center;
            margin-bottom: 2.5rem;
            animation: fadeInDown 0.8s ease;
        }

        .logo h1 {
            font-family: 'Playfair Display', serif;
            color: #1a1a1a;
            font-size: 3rem;
            margin: 0;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            animation: fadeIn 1s ease;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: #1a1a1a;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-group {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            color: #444;
            font-weight: 500;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-group input:focus {
            border-color: #1976d2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.2);
            background: #fff;
        }

        .form-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            transition: color 0.3s ease;
        }

        .error {
            background: rgba(244, 67, 54, 0.1);
            color: #d32f2f;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 1rem;
            border: 1px solid rgba(244, 67, 54, 0.3);
            animation: shake 0.5s ease;
        }

        button {
            background: linear-gradient(45deg, #1976d2, #2196f3);
            color: white;
            border: none;
            padding: 1.2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        button:hover {
            background: linear-gradient(45deg, #1565c0, #1976d2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 118, 210, 0.4);
        }

        p {
            text-align: center;
            color: #555;
            margin-top: 2rem;
            font-size: 1.05rem;
        }

        a {
            color: #1976d2;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
            position: relative;
        }

        a:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #1976d2;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        a:hover:after {
            transform: scaleX(1);
        }

        .social-login {
            margin-top: 2.5rem;
            text-align: center;
            position: relative;
        }

        .social-login:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
            z-index: 1;
        }

        .social-login p {
            background: #fff;
            display: inline-block;
            padding: 0 1rem;
            color: #666;
            position: relative;
            z-index: 2;
            margin: 0 0 1.5rem;
        }

        .social-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }

        .social-button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            color: #333;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            border: 2px solid #eee;
        }

        .social-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .social-button.facebook:hover {
            background: #1877f2;
            color: white;
            border-color: #1877f2;
        }

        .social-button.google:hover {
            background: #db4437;
            color: white;
            border-color: #db4437;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
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
    </style>
</head>

<body>
    <div class="auth-container">

        <form method="POST" action="login.php">
            <h2>Đăng Nhập</h2>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Nhập địa chỉ email của bạn">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required placeholder="Nhập mật khẩu của bạn">
            </div>
            <button type="submit">Đăng Nhập</button>
            <p>Chưa có tài khoản? <a href="register.php">Đăng Ký Ngay</a></p>
        </form>
    </div>
</body>

</html>