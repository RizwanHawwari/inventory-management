<?php 
require "functions.php";

if ( isset($_POST["submit"]) ) {
  $email = strtolower(htmlspecialchars(stripslashes($_POST["email"])));
  $password = mysqli_real_escape_string($conn, $_POST["password"]);
  $confirm = mysqli_real_escape_string($conn, $_POST["confirm"]);

  $result = mysqli_query($conn, "SELECT email FROM account WHERE email = '$email'");
  if (mysqli_num_rows($result) > 0) {
    echo "<script>
    alert('Username telah terdaftar');
    window.location.href= 'register.php';
    </script>";
    exit;
  }

  if ($password != $confirm) {
    echo "<script>
    alert('Konfirmasi Password tidak sesuai!');
    window.location.href= 'register.php';
    </script>";
    exit;
  }

  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  // $hashed_confirm = password_hash($confirm, PASSWORD_DEFAULT);
  $result2 = mysqli_query($conn, "INSERT INTO account VALUES ('$email', '$hashed_password')");
  if ( mysqli_affected_rows($conn) > 0 ) {
    echo "<script>
    alert('Success!!');
    window.location.href= 'login.php';
    </script>";
  } else {
    echo "<script>
    alert('Failed to add data');
    </script>";
  }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/register.css">
  <title>Register Form</title>
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
        <h2>Register</h2>
        <?php if (isset($error)) : ?>
        <p style="color: red;">Email atau password salah!</p>
        <?php endif; ?>
        <form action="" method="post" autocomplete="off">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>

          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>

          <label for="confirm">Confirm Password</label>
          <input type="confirm" id="confirm" name="confirm" required>

          <button type="submit" name="submit">Login</button>
        </form>
        <div class="login">
          <a href="login.php">Already Have an Account?</a>
        </div>
      </div>
    </div>
</body>

</html>