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

// Lấy 10 giá trị cảm biến mới nhất
$result = $conn->query("SELECT * FROM sensor_values ORDER BY timestamp DESC LIMIT 10");

$data = [];
while ($row = $result->fetch_assoc()) {
    // Chuyển đổi múi giờ từ UTC sang Asia/Ho_Chi_Minh nếu cần
    $timestamp = new DateTime($row['timestamp'], new DateTimeZone('UTC'));
    $timestamp->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
    $row['local_timestamp'] = $timestamp->format('Y-m-d H:i:s');

    $data[] = $row;
}

// Xuất dữ liệu dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
