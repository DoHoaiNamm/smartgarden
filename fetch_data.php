<?php
// Thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weblau";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy 10 bản ghi mới nhất
$sql = "SELECT timestamp, light, temperature, humidity, water_level, soil_moisture FROM sensor_readings ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

// Tạo nội dung HTML cho bảng
$output = '';
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $output .= "<tr>
            <td>{$row['timestamp']}</td>
            <td>{$row['light']}</td>
            <td>{$row['temperature']}</td>
            <td>{$row['humidity']}</td>
            <td>{$row['water_level']}</td>
            <td>{$row['soil_moisture']}</td>
        </tr>";
    }
} else {
    $output .= "<tr><td colspan='6'>Không có dữ liệu nào.</td></tr>";
}

// Trả về nội dung HTML
echo $output;

$conn->close();
?>
