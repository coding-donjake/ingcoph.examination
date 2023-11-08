<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("INSERT INTO posts (id_user, caption) VALUES (?, ?)");
$stmt->bind_param("is", $_SESSION['id'], $_POST['caption']);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode($arr);

?>