<?php
$servername = "fdb1030.awardspace.net";  // Thay bằng hostname của bạn
$username = "4547000_weblau";  // Thay bằng username của bạn
$password = "demo_123456";  // Thay bằng mật khẩu của bạn
$dbname = "4547000_weblau";  // Thay bằng tên cơ sở dữ liệu của bạn

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Nhận dữ liệu từ yêu cầu POST
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $lux = $_POST['lux'];
    $water_level = $_POST['water_level'];
    $soil_moisture = $_POST['soil_moisture'];

    // Chèn dữ liệu vào bảng
    $stmt = $conn->prepare("INSERT INTO sensor_values (temperature, humidity, lux, water_level, soil_moisture) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ddddd", $temperature, $humidity, $lux, $water_level, $soil_moisture);
    $stmt->execute();
    $stmt->close();
    echo "Dữ liệu đã được lưu";
}

$conn->close();
?>
