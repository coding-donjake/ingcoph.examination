<?php

include_once("main.php");

$arr = array('status' => 200);

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $_POST['username']);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows <= 0) {
  $arr['status'] = 404;
} else {
  $row = $result->fetch_assoc();
  if (!password_verify($_POST['password'], $row['password'])) {
    $arr['status'] = 404;
  } else {
    $_SESSION['id'] = $row['id'];
  }
}

$stmt->close();
$conn->close();

echo json_encode($arr);

?>