<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 连接数据库
$conn = new mysqli("14.136.78.203", "Ealogin", "Abc3691591@3", "fyp");
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'], $_POST['new_password'])) {
        $email = trim($_POST['email']);
        $new_password = $_POST['new_password'];

        $stmt = $conn->prepare("UPDATE memberinformation SET memberPW = ? WHERE email = ?");
        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Password updated successfully"]);
        } else {
            echo json_encode(["message" => "Failed to update password: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["message" => "Email and new password are required"]);
    }
}

$conn->close();
?>