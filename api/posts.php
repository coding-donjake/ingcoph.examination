<?php

include_once("main.php");

$arr = [];

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("SELECT posts.*, users.username, users.full_name FROM posts JOIN users ON posts.id_user = users.id WHERE posts.status = 'active'");
$stmt->execute();

$result = $stmt->get_result();

$stmt->close();
$conn->close();

while ($post = $result->fetch_assoc()) {
  $post['comments'] = [];
  if ($post['id_user'] == $_SESSION['id']) {
    $post['owner'] = true;
  }

  $conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
  $stmt = $conn->prepare("SELECT comments.*, users.username, users.full_name FROM comments JOIN users ON comments.id_user = users.id WHERE comments.id_post = ?");
  $stmt->bind_param("i", $post["id"]);
  $stmt->execute();

  $result2 = $stmt->get_result();

  $stmt->close();
  $conn->close();

  while ($comments = $result2->fetch_assoc()) {
    $post["comments"][sizeof($post["comments"])] = $comments;
  }

  $arr[sizeof($arr)] = $post;
}

echo json_encode($arr);

?>