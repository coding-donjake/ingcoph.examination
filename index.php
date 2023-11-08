<?php

include("api/main.php");

$_SESSION['id'] = '';

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
  
  <form id="login">
    <h1 class="text-center">Post Anything</h1>
    <h3 class="text-center">Log In</h3>
    <div class="form-group">
      <label for="username">Email</label>
      <input type="email" class="form-control" id="username">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password">
    </div>
    <button type="submit" class="btn btn-primary" onclick="login(this)">Log In</button>
  </form>

  <hr>

  <form id="register">
    <h3 class="text-center">Register</h3>
    <div class="form-group">
      <label for="reg_username">Email</label>
      <input type="email" class="form-control" id="reg_username">
    </div>
    <div class="form-group">
      <label for="reg_password">Password</label>
      <input type="password" class="form-control" id="reg_password">
    </div>
    <div class="form-group">
      <label for="reg_full_name">Full name</label>
      <input type="text" class="form-control" id="reg_full_name">
    </div>
    <button type="submit" class="btn btn-success" onclick="register(this)">Register</button>
  </form>

  <script>
    document.getElementById("login").addEventListener("submit", function(event) {
      event.preventDefault();
    });

    document.getElementById("register").addEventListener("submit", function(event) {
      event.preventDefault();
    });

    let login = (button) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('username', document.querySelector('#username').value);
      formdata.append('password', document.querySelector('#password').value);
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          let res = JSON.parse(xml.responseText);
          if (res.status === 200) {
            location.assign('/home.php');
            return;
          }
          if (res.status === 404) window.alert('User not found.');
          button.innerHTML = btnText;
        }
      }
      button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
      xml.open("POST", "/api/login.php", true);
      xml.send(formdata);
    }

    let register = (button) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('username', document.querySelector('#reg_username').value);
      formdata.append('password', document.querySelector('#reg_password').value);
      formdata.append('full_name', document.querySelector('#reg_full_name').value);
      if (!document.querySelector('#reg_username').value) {
        window.alert('Username is blank.');
        button.innerHTML = btnText;
        return;
      }
      if (document.querySelector('#reg_username').value.length < 4) {
        window.alert('Username must be atleast 4 characters.');
        button.innerHTML = btnText;
        return;
      }
      if (document.querySelector('#reg_password').value.length < 4) {
        window.alert('Password must be atleast 4 characters.');
        button.innerHTML = btnText;
        return;
      }
      if (!document.querySelector('#reg_full_name').value) {
        window.alert('Full name is blank.');
        button.innerHTML = btnText;
        return;
      }
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          let res = JSON.parse(xml.responseText);
          if (res.status === 200) {
            window.alert('Registration success! Please login.');
            location.reload();
          }
          if (res.status === 409) window.alert('Username already been used.');
          button.innerHTML = btnText;
        }
      }
      button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
      xml.open("POST", "/api/register.php", true);
      xml.send(formdata);
    }
  </script>
</body>
</html>