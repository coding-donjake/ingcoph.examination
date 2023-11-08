<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("INSERT INTO comments (id_post, id_user, content) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $_POST['id_post'], $_SESSION['id'], $_POST['content']);
$stmt->execute();
$stmt->close();
$conn->close();

echo json_encode($arr);

?>