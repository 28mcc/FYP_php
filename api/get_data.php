<?php
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("14.136.78.203", "Ealogin", "Abc3691591@3", "fyp");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT email, memberPW FROM memberinformation";
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>