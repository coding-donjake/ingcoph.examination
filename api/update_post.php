<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("UPDATE posts SET caption = ? WHERE id = ?");
$stmt->bind_param("si", $_POST['caption'], $_POST['id']);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode($arr);

?>