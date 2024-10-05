<?php 
session_start();
require "functions.php";

if ( isset($_COOKIE["key"]) ) {
  $key = $_COOKIE["key"];

  $r1 = mysqli_query($conn, "SELECT email FROM account WHERE email = '$key'");
  $row1 = mysqli_fetch_assoc($r1);
  if ( $key === hash('sha256', $row1["email"]) ) {
    $_SESSION["login"] = true;
  }
}

if ( isset($_SESSION["login"]) ) {
  header("Location: index.php");
  exit;
}

$errorMessage = '';

if ( isset($_POST["submit"]) ) {
  $email = strtolower(htmlspecialchars(stripslashes($_POST["email"])));
  $password = mysqli_real_escape_string($conn, $_POST["password"]);

  $result = mysqli_query($conn, "SELECT * FROM account WHERE email = '$email'");

  if ( mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row["password"])) {
      $_SESSION["login"] = true;

      if ( isset($_POST["remember"]) ) {
        setcookie("key", hash('sha256', $email), time()+60*60*24);
      }

      header("Location: index.php");
      exit;
    } else {
      $errorMessage = 'Password salah!';
    }
  } else {
    $errorMessage = 'Email tidak tersedia!';
  }
}
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
  <div class="header-logos">
    <div class="logo-cn">
      <img src="img/logo-cn.png" alt="logo-cn">
    </div>
    <div class="logo-rpl">
      <img src="img/logo-rpl.png" alt="logo-rpl">
    </div>
  </div>
  <div class="container">
    <div class="login-box">
      <h2>Login</h2>

      <?php if (!empty($errorMessage)) : ?>
      <p style="color: red; text-align: center; margin: 20px 10px; font-style: italic;"><?php echo $errorMessage; ?></p>
      <?php endif; ?>

      <form action="" method="post" autocomplete="off">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <div class="remember">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember">Remember Me</label>
        </div>

        <button type="submit" name="submit">Login</button>
      </form>
    </div>
  </div>
</body>

</html>