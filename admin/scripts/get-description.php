<?php

if($_SERVER["REQUEST_METHOD"]=="GET"&&!empty($_GET)) {
  if(isset($_GET["g"])&&is_numeric($_GET["g"])) {
    require("../../db/db.php");
    $result = query("SELECT description FROM settings WHERE id='".$_GET["g"]."'");
    if(mysqli_num_rows($result)==1) {
    echo mysqli_fetch_assoc($result)["description"];
    }
  }
}
