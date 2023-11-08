<?php

include_once("main.php");

$arr = [];

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $_POST["id"]);
$stmt->execute();

$result = $stmt->get_result();

$stmt->close();
$conn->close();

$post = $result->fetch_assoc();

echo json_encode($post);

?>