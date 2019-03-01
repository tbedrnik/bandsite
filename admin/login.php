<?php
/**
 * Skript pro přihlášení uživatele
 *
 * @package Bandsite Manager
 */

mb_internal_encoding("UTF-8");
session_start();

if(isset($_SESSION["user"]["id"])) {
  header("Location: index.php?p=dashboard");
  exit();
} elseif(isset($_POST["login"])) {

  require('../db/db.php');

  $username = mres($_POST["username"]);
  $password = mres($_POST["password"]);

  if (empty($username)||empty($password)) {
    header("Location: logform.php?s=error");
    exit();
  } else {
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = query($sql);
    if (mysqli_num_rows($result)>0) {
      if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
          $_SESSION["user"] = $row;
          header("Location: index.php?p=dashboard");
          exit();
        } else {
          header("Location: logform.php?s=error&user=$username");
          exit();
        }
      }
    } else {
      header("Location: logform.php?s=error");
      exit();
    }
  }
} else {
  header("Location: logform.php?s=error");
  exit();
}
