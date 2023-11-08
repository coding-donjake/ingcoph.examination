<?php

include("api/main.php");

if (!$_SESSION['id']) {
  header('Location: index.php');
}

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

  <!-- fontawesome icons -->
  <script src="https://kit.fontawesome.com/e480157e34.js" crossorigin="anonymous"></script>

  <!-- local css -->
  <link rel="stylesheet" href="style.css">
  <title>Post Anything</title>
</head>
<body>
<nav class="navbar navbar-light bg-light mb-4">
    <a class="navbar-brand">Post Anything</a>
    <div class="form-inline">
      <button class="btn btn-primary my-2 my-sm-0" onclick="location.assign('/home.php')">Home<i class="fa-solid fa-house ml-2"></i></button>
      <button class="btn btn-primary mx-2 my-2 my-sm-0" onclick="location.assign('/profile.php')">Profile<i class="fa-solid fa-user ml-2"></i></button>
      <button class="btn btn-danger my-2 my-sm-0" onclick="location.assign('/index.php')">Log Out<i class="fa-solid fa-right-from-bracket ml-2"></i></button>
    </div>
  </nav>
  <form id="register">
    <h3 class="text-center">Update Profile</h3>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" class="form-control" id="username" value="<?php echo $user['username'] ?>" readonly>
    </div>
    <div class="form-group">
      <label for="password">Update password (leave blank if no changes)</label>
      <input type="password" class="form-control" id="password">
    </div>
    <div class="form-group">
      <label for="full_name">Update full name</label>
      <input type="text" class="form-control" id="full_name" value="<?php echo $user['full_name']; ?>">
    </div>
    <button type="submit" class="btn btn-success" onclick="update(this)">Update<i class="fa-solid fa-floppy-disk ml-2"></i></button>
  </form>
  <script>
    document.getElementById("register").addEventListener("submit", function(event) {
      event.preventDefault();
    });

    let update = (button) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('password', document.querySelector('#password').value);
      formdata.append('full_name', document.querySelector('#full_name').value);
      if (!document.querySelector('#full_name').value) {
        window.alert('Full name is blank.');
        button.innerHTML = btnText;
        return;
      }
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          console.log(xml.responseText);
          let res = JSON.parse(xml.responseText);
          if (res.status === 200) {
            window.alert('Update success.');
            window.history.back();
          }
          button.innerHTML = btnText;
        }
      }
      button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
      xml.open("POST", "/api/update_profile.php", true);
      xml.send(formdata);
    }
  </script>
</body>
</html>