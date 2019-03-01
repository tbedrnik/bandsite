<?php
/**
 * Vykreslení logovacího formuláře
 * Když nikdo není přihlášen, vykreslí login form
 * Když je někdo přihlášen, vykreslí logout form
 *
 * @package Bandsite Manager
 */

mb_internal_encoding("UTF-8");
session_start();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet/less" href="css/logform.less">
  <script src="../js/less.min.js" charset="utf-8"></script>
  <title>Bandsite</title>
</head>
<body>
<?php

  if(isset($_SESSION["user"])) {
    $template = file_get_contents("templates/logout.html");
    $template = str_replace("%userid%",$_SESSION["user"]["fullname"],$template);
    echo $template;
  } else {
    $template = file_get_contents("templates/login.html");
    $usr = "";
    if(isset($_GET["user"])) {
      $usr = htmlspecialchars($_GET["user"]);
    }
    $template = str_replace("%usr%",$usr,$template);
    $handler = "";
    if(isset($_GET["s"])) {
      $s = htmlspecialchars($_GET["s"]);
      switch($s) {
        case "error":
        $handler = '<p class="error">Username or password incorrect</p>';
        break;
        case "success":
        $handler = '<p class="success">Logged out successfully</p>';
        break;
        case "reset":
        $handler = '<p>Password has been reset</p>';
      }
    }
    $template = str_replace("%handler%",$handler,$template);
    echo $template;
  }

?>
<script src="../js/jquery-3.2.1.min.js" charset="utf-8"></script>
</body>
</html>
