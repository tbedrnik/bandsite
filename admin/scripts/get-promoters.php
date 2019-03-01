<?php
/**
 * Skript, který vrátí odpovídající hodnoty z tabulky *promoters*
 * Volá se POSTem s proměnnou *promoter* = string ke hledání v tabulce
 *
 * @package Bandsite Manager
 */

if(isset($_POST["promoter"])) {
  require("../../db/db.php");
  $wtf = mres($_POST["promoter"]); //ošetření vstupní hodnoty
  $query = "SELECT promoter_id, promoter_name, promoter_company FROM promoters WHERE promoter_name LIKE '%$wtf%' OR promoter_company LIKE '%$wtf%'";
  $result = query($query); //vyhledání odpovídajích dat
  $return = []; //vytvoření pole, do kterého se budou vkládat záznamy
  if(mysqli_num_rows($result)>0) {
    while($promoter = mysqli_fetch_assoc($result)) {
      $return[] = $promoter;
    }
  }
  echo json_encode($return);
}

 ?>
