<?php

include_once("main.php");

$arr = array('status' => 200);

if ($_POST['password'] != '') {
  $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("si", $password, $_SESSION['id']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
$stmt->bind_param("si", $_POST['full_name'], $_SESSION['id']);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode($arr);

?>