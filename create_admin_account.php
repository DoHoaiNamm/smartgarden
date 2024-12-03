<?php
// Step 1: Connect to the database
$conn = new mysqli('fdb1030.awardspace.net', '4547000_weblau', 'demo_123456', '4547000_weblau');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Step 2: Create a simple admin user (only run this once to set up)
$username = 'namdo1211';
$password = '123456';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$is_admin = 1;
$is_approved = 1;

// Check if the user already exists to avoid duplicate creation
$sql_check = "SELECT * FROM users WHERE username = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $username);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    $sql_insert = "INSERT INTO users (username, password, is_admin, is_approved) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssii", $username, $hashed_password, $is_admin, $is_approved);
    if ($stmt_insert->execute()) {
        echo "Tạo tài khoản admin thành công.";
    } else {
        echo "Lỗi khi tạo tài khoản.";
    }
} else {
    echo "Tài khoản admin đã tồn tại.";
}

$conn->close();
?>
