<?php
session_start();
$conn = new mysqli('fdb1030.awardspace.net', '4547000_weblau', 'demo_123456', '4547000_weblau');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin người dùng hiện tại từ cơ sở dữ liệu
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Kiểm tra quyền admin
    if ($user['is_admin'] != 1) {
        // Nếu không phải admin, chuyển hướng về trang không có quyền truy cập
        header("Location: unauthorized.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

// Phê duyệt người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_user'])) {
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET is_approved = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Lấy danh sách người dùng chưa được phê duyệt
$sql = "SELECT * FROM users WHERE is_approved = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Trị Viên - Phê Duyệt Người Dùng</title>
</head>
<body>
    <h2>Quản Trị Viên - Phê Duyệt Người Dùng</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Đăng Nhập</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td>
                    <form action="admin.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve_user">Phê Duyệt</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="logout.php">Đăng Xuất</a>
</body>
</html>

<?php
$conn->close();
?>
