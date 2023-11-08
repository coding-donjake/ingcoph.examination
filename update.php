<?php

include("api/main.php");

if (!$_SESSION['id']) {
  header('Location: index.php');
}

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
      <button class="btn btn-primary mx-2 my-2 my-sm-0">Home<i class="fa-solid fa-house ml-2"></i></button>
      <button class="btn btn-danger my-2 my-sm-0" onclick="location.assign('/index.php')">Log Out<i class="fa-solid fa-right-from-bracket ml-2"></i></button>
    </div>
  </nav>
  <div class="form-group">
    <label for="caption">Edit Post</label>
    <textarea class="form-control" id="caption" rows="5" placeholder="What is in your mind..."></textarea>
  </div>
  <button class="btn btn-primary" onclick="update(this)">Update<i class="fa-solid fa-globe ml-2"></i></button>
  <script>
    let update = (button) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('id', <?php echo $_GET['id']; ?>);
      formdata.append('caption', document.querySelector('#caption').value);
      if (!document.querySelector('#caption').value) {
        window.alert('Post caption is blank.');
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
      xml.open("POST", "/api/update_post.php", true);
      xml.send(formdata);
    }

    let load = () => {
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('id', <?php echo $_GET['id']; ?>);
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          console.log(xml.responseText);
          let res = JSON.parse(xml.responseText);
          document.querySelector('#caption').value = res.caption;
        }
      }
      xml.open("POST", "/api/select.php", true);
      xml.send(formdata);
    }

    load();
  </script>
</body>
</html>