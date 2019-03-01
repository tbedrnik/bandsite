<?php
/**
 * Skript pro reset hesla uživatele.
 * Přístupné pouze pro SUPERUSER
 *
 * @package Bandsite Manager
 */

session_start();

if($_SESSION["user"]["username"]=="superuser") {
  $id = htmlspecialchars($_GET["id"]);
  if($id>1) {
    require("../../db/db.php");
    // nové heslo bude podle hodnoty v tabulce settings
    $newpass = password_hash(getValue("password_reset"),PASSWORD_DEFAULT);
    if(query("UPDATE users SET password = '$newpass' WHERE id = $id")) {
      header("Location: ../index.php?p=users&changes=password-reset");
      die();
    } else {
      header("Location: ../index.php?p=users&changes=failed");
      die();
    }
  } else {
    header("Location: ../index.php?p=users&changes=failed");
    die();
  }
} else {
  header('HTTP/1.0 403 Forbidden');
  die();
}

?>
