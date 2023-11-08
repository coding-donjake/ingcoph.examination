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
      <button class="btn btn-primary my-2 my-sm-0" onclick="location.assign('/home.php')">Home<i class="fa-solid fa-house ml-2"></i></button>
      <button class="btn btn-primary mx-2 my-2 my-sm-0" onclick="location.assign('/profile.php')">Profile<i class="fa-solid fa-user ml-2"></i></button>
      <button class="btn btn-danger my-2 my-sm-0" onclick="location.assign('/index.php')">Log Out<i class="fa-solid fa-right-from-bracket ml-2"></i></button>
    </div>
  </nav>
  <div class="form-group">
    <label for="caption">Post Something</label>
    <textarea class="form-control" id="caption" rows="5" placeholder="What is in your mind..."></textarea>
  </div>
  <button class="btn btn-primary" onclick="post(this)">Post<i class="fa-solid fa-globe ml-2"></i></button>
  <hr>
  <h3 class="text-center">Public Posts</h3>
  <div id="posts"></div>
  <script>
    let post = (button) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
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
            window.alert('Post success.');
            location.reload();
          }
          button.innerHTML = btnText;
        }
      }
      button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
      xml.open("POST", "/api/post.php", true);
      xml.send(formdata);
    }

    let posts = () => {
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          document.querySelector('#posts').innerHTML = '';
          console.log(xml.responseText);
          let str = '<div class="card">';
          let res = JSON.parse(xml.responseText);
          for (let i = 0; i < res.length; i++) {
            const element = res[i];
            if (i != 0) str += '<hr>';
            let add = '';
            if (element.owner) {
              add = `<button class="btn btn-secondary mb-4" onclick="location.assign('/update.php?id=${element.id}')">Update Post</button><button class="btn btn-danger mb-4 ml-2" onclick="remove(this, ${element.id})">Delete Post</button>`;
            }
            str += `<div class="card-body">
      <h5 class="card-title">${element.full_name}</h5>
      <i>${element.datetime}</i>
      <p class="card-text">${element.caption}</p>
      ${add}
      <div class="card">
        <div class="card-body">`;
          if (element.comments.length > 0) {
            for (let j = 0; j < element.comments.length; j++) {
            const commnent = element.comments[j];
            if (j != 0) str += '<hr>';
            str += `<p class="card-text">
            Comment by: <b>${commnent.full_name}</b><br>
            <i>${commnent.content}</i>
          </p>`;
          }
          } else {
            str += 'No comments.';
          }
        str += `</div>
      </div>
      <input type="text" class="form-control my-2" id="comment-for-${element.id}" placeholder="Leave a comment...">
      <button class="btn btn-outline-primary" onclick="comment(this, ${element.id})">Comment</button>
    </div>`;
          }
          str += '</div>';
          document.querySelector('#posts').innerHTML = str;
        }
      }
      xml.open("POST", "/api/posts.php", true);
      xml.send(formdata);
    }

    let comment = (button, id_post) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('id_post', id_post);
      formdata.append('content', document.querySelector(`#comment-for-${id_post}`).value);
      if (!document.querySelector(`#comment-for-${id_post}`).value) {
        window.alert('Comment is blank.');
        button.innerHTML = btnText;
        return;
      }
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          console.log(xml.responseText);
          let res = JSON.parse(xml.responseText);
          if (res.status === 200) {
            window.alert('Comment success.');
            location.reload();
          }
          button.innerHTML = btnText;
        }
      }
      button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
      xml.open("POST", "/api/comment.php", true);
      xml.send(formdata);
    }

    let remove = (button, id) => {
      let btnText = button.innerHTML;
      let formdata = new FormData();
      let xml = new XMLHttpRequest();
      formdata.append('id', id);
      xml.onreadystatechange = () => {
        if (xml.readyState === 4 && xml.status === 200) {
          console.log(xml.responseText);
          let res = JSON.parse(xml.responseText);
          if (res.status === 200) {
            window.alert('Delete success.');
            location.reload();
          }
          button.innerHTML = btnText;
        }
      }
      if (window.confirm('Are you sure you want to delete this post?')) {
        button.innerHTML = '<i class="fa-solid fa-circle-notch spin-cw"></i>';
        xml.open("POST", "/api/delete.php", true);
        xml.send(formdata);
      }
    }

    posts();
  </script>
</body>
</html>