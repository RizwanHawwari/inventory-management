<?php 
require "functions.php";


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <title>Login Form</title>
</head>

<body>
  <div class="container">
    <div class="logo-cn">
      <img src="img/logo-cn.png" alt="logo-cn" width="150">
    </div>
    <div class="container">
      <div class="logo-rpl">
        <img src="img/logo-rpl.png" alt="logo-rpl" width="150">
      </div>
      <div class="login-box">
        <h2>Login</h2>
        <form action="" method="post" autocomplete="off">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>

          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>

          <button type="submit" name="submit">Login</button>
        </form>
      </div>
    </div>
</body>

</html>