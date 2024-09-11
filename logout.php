<?php 
session_start();
setcookie('key', '', time()-60*60*24);
session_unset();
session_destroy();

header("Location: login.php");
?>