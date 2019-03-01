<?php
/**
 * Skript pro odhlášení uživatele
 *
 * @package Bandsite Manager
 */

session_start();

if(isset($_SESSION["user"])) {
  $get = "s=success";
  if(isset($_GET["user"])) {
    $get = "s=reset&user=".$_GET["user"];
  }
  session_destroy();
  header("Location: logform.php?$get");
  exit();
} else {
  header("Location: logform.php");
}
