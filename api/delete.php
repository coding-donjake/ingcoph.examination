<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("UPDATE posts SET status = 'removed' WHERE id = ?");
$stmt->bind_param("i", $_POST['id']);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode($arr);

?>