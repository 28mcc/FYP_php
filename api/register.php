<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
session_start();

// 連接到資料庫
$conn = new mysqli("14.136.78.203", "Ealogin", "Abc3691591@3", "your_database_name");

// 檢查資料庫連接
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// 只處理 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password_hash'], $_POST['userType'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password_hash = password_hash($_POST['password_hash'], PASSWORD_BCRYPT);
        $userType = trim($_POST['userType']);

        // 驗證電子郵件格式
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["message" => "Invalid email format"]);
            exit();
        }

        // 檢查電子郵件是否已存在
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["message" => "Email already exists"]);
        } else {
            // 將用戶名、電子郵件、密碼和用戶類型存儲到資料庫中
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, userType) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password_hash, $userType);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Registration successful"]);
            } else {
                echo json_encode(["message" => "Registration failed: " . $stmt->error]); // 顯示具體錯誤
            }
        }
        $stmt->close();
    } else {
        echo json_encode(["message" => "Username, email, password, and userType are required"]);
    }
} else {
    echo json_encode(["message" => "Invalid request method"]);
}

$conn->close();
?>
