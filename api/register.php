<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("INSERT INTO users (username, password, full_name) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $_POST['username'], $password, $_POST['full_name']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

try {
  $stmt->execute();
} catch (\Throwable $th) {
  $arr['status'] = 409;
}

$stmt->close();
$conn->close();

echo json_encode($arr);

?>