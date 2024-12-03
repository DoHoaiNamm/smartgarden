<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli("fdb1030.awardspace.net", "4547000_weblau", "demo_123456", "4547000_weblau");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy trạng thái của các thiết bị
$sql = "SELECT device_name, status FROM device_status";
$result = $conn->query($sql);

// Kiểm tra xem truy vấn có thành công hay không
if (!$result) {
    die("Lỗi truy vấn SQL: " . $conn->error);
}

$device_status = array();
while ($row = $result->fetch_assoc()) {
    $device_status[$row['device_name']] = $row['status'];
}

// Trả về dữ liệu dạng JSON
echo json_encode($device_status);

$conn->close();
?>
