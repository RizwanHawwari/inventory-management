<?php 
require "functions.php";

if ( isset($_POST["submit"]) ) {
  $email = strtolower(htmlspecialchars(stripslashes($_POST["email"])));
  $password = mysqli_real_escape_string($conn, $_POST["password"]);

  $result = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");
  if ( mysqli_num_rows($result) > 0) {
    
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row["password"])) {
      header("Location: main.php");
      exit;
    }
  }
}
  // $error = true;
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
        <?php if (isset($error)) : ?>
        <p style="color: red;">Email atau password salah!</p>
        <?php endif; ?>
        <form action="" method="post" autocomplete="off">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>

          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>

          <button type="submit" name="submit">Login</button>
        </form>
        <div class="regis">
          <a href="register.php">Don't Have Account?</a>
        </div>
      </div>
    </div>
</body>

</html>