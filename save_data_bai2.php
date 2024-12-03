<?php
// Kết nối tới cơ sở dữ liệu
$conn = new mysqli("fdb1030.awardspace.net", "4547000_weblau", "demo_123456", "4547000_weblau");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem dữ liệu từ AJAX có tồn tại không
if (isset($_POST['device']) && isset($_POST['status'])) {
    // Nhận dữ liệu từ AJAX
    $device = $_POST['device'];
    $status = $_POST['status'];

    // Thực hiện truy vấn an toàn để cập nhật trạng thái thiết bị
    $sql = "UPDATE device_status SET status=? WHERE device_name=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $status, $device);

    if ($stmt->execute()) {
        echo "Cập nhật thành công";
    } else {
        echo "Lỗi khi cập nhật: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Thiếu thông tin thiết bị hoặc trạng thái.";
}

$conn->close();
?>
