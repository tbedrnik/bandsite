<?php
/**
 * Skript pro mazání uživatele.
 * Přístupné pouze pro SUPERUSER
 *
 * @package Bandsite Manager
 */

session_start();

if($_SESSION["user"]["username"]=="superuser") {
  $id = htmlspecialchars($_GET["id"]);
  if($id>1) {
    require("../../db/db.php");
    if(query("DELETE FROM users WHERE id = $id")) {
      header("Location: ../index.php?p=users&changes=user-deleted");
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
