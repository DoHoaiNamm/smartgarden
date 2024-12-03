<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$servername = "fdb1030.awardspace.net";
$username = "4547000_weblau";
$password = "demo_123456";
$dbname = "4547000_weblau";

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xóa tất cả dữ liệu và đặt lại AUTO_INCREMENT
$sql = "TRUNCATE TABLE sensor_values";

if ($conn->query($sql) === TRUE) {
    echo "Đã xóa tất cả dữ liệu thành công và đặt lại từ đầu.";
} else {
    echo "Lỗi khi xóa dữ liệu: " . $conn->error;
}

$conn->close();
?>
