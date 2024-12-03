<?php
session_start();
$conn = new mysqli('fdb1030.awardspace.net', '4547000_weblau', 'demo_123456', '4547000_weblau');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Tìm người dùng với tên đăng nhập phù hợp
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu đã nhập với mật khẩu trong cơ sở dữ liệu
        if (password_verify($password, $user['password'])) {
            if ($user['is_approved'] == 1) {
                // Đăng nhập thành công, thiết lập session
                $_SESSION['username'] = $username;

                if ($user['is_admin'] == 1) {
                    // Nếu là admin, chuyển hướng đến trang admin
                    header("Location: admin.php");
                } else {
                    // Nếu là người dùng bình thường, chuyển hướng đến trang người dùng
                    header("Location: quyetdaukhac.php");
                }
                exit();
            } else {
                $message = "Tài khoản của bạn chưa được phê duyệt bởi admin.";
            }
        } else {
            $message = "Mật khẩu không chính xác.";
        }
    } else {
        $message = "Người dùng không tồn tại.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #b2f2a5, #a3d89f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 128, 0, 0.2);
            width: 350px;
            text-align: center;
            position: relative;
            animation: fadeIn 1.2s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -20px;
            left: -20px;
            width: 60px;
            height: 60px;
            background: rgba(0, 128, 0, 0.5);
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 128, 0, 0.4);
            animation: floating 6s ease-in-out infinite;
        }

        .login-container::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 60px;
            height: 60px;
            background: rgba(0, 128, 0, 0.5);
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0, 128, 0, 0.4);
            animation: floating 6s ease-in-out infinite;
            animation-delay: 3s;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        h2 {
            color: #006400;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="password"] {
            background: rgba(0, 128, 0, 0.1);
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: none;
            border-radius: 30px;
            outline: none;
            color: #006400;
            transition: all 0.3s;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(0, 128, 0, 0.7);
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            background: rgba(0, 128, 0, 0.2);
            box-shadow: 0 0 10px rgba(0, 128, 0, 0.3);
        }

        button {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s, transform 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #388e3c, #66bb6a);
            transform: translateY(-3px);
        }

        p {
            margin-top: 15px;
            color: #006400;
        }

        a {
            color: #006400;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #d32f2f;
            margin-bottom: 15px;
            font-weight: bold;
            animation: shake 0.4s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        .additional-info {
            margin-top: 30px;
            font-size: 0.9em;
            color: #006400;
            background: rgba(0, 128, 0, 0.1);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 128, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <?php if ($message): ?>
            <p class="error-message"> <?php echo $message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Tên đăng nhập" required><br>
            <input type="password" name="password" placeholder="Mật khẩu" required><br>
            <button type="submit">Đăng nhập</button>
        </form>
        <p>Nếu bro chưa có tài khoản, <a href="register.php">nhấp đây để đăng ký nè</a>.</p>
        <div class="additional-info">
            <p>Luôn bảo vệ tài khoản của bạn bằng mật khẩu mạnh và không chia sẻ cho người khác.</p>
        </div>
    </div>
</body>
</html>
