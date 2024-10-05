<?php 

require "functions.php";

$email = 'admin@gmail.com';
$password = 123;
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$result = mysqli_query($conn, "INSERT INTO account VALUES ('$email', '$hashedPassword')");
if ( mysqli_affected_rows($conn) > 0 ) {
  echo "Penambahan account berhasil";
}

exit();

?>