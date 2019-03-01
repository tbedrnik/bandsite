<?php
/**
 * Skript, který vrátí odpovídající hodnoty z tabulky *members*
 * Volá se POSTem s proměnnou *member* = string ke hledání v tabulce
 *
 * @package Bandsite Manager
 */

if(isset($_POST["member"])) {
  require("../../db/db.php");
  $wtf = mres($_POST["member"]); //ošetření vstupní hodnoty
  $query = "SELECT id, fullname FROM members WHERE fullname LIKE '%$wtf%' OR nickname LIKE '%$wtf%'";
  $result = query($query); //vyhledání odpovídajích dat
  $return = []; //vytvoření pole, do kterého se budou vkládat záznamy
  if(mysqli_num_rows($result)>0) {
    while($member = mysqli_fetch_assoc($result)) {
      $return[] = $member;
    }
  }
  echo json_encode($return);
}

 ?>
