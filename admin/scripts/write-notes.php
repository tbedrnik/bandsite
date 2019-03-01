<?php
/**
 * Skript updatuje hodnotu NOTES u koncertu podle ID
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST)) {
  if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
    if(isset($_POST["notes"])) {
      require("../../db/db.php");
      $notes = mres($_POST["notes"]);
      $id = mres($_POST["id"]);
      echo query("UPDATE tour SET notes = '$notes' WHERE id = $id");
    }
  }
}

?>
