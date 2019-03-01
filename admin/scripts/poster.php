<?php
/**
 * Skript, který vykonává uložení plakátu a přiřazení ho ke koncertu v databázi
 *
 * @package Bandsite Manager
 */
if(isset($_POST["changePoster"])&&isset($_POST["id"])&&is_numeric($_POST["id"])) {
  if(!empty($_FILES["poster"]["name"])) {
    $posterType = strtolower(pathinfo($_FILES["poster"]["name"],PATHINFO_EXTENSION));
    if(in_array($posterType,["jpg","jpeg","png","gif"])) {
      if($_FILES["poster"]["size"] < 1000000) {

        $posterFolder = "../img/posters/";
        $posterName = $_POST["id"];
        $posterFile = $posterFolder.$posterName.".".$posterType;
        while(file_exists($posterFile)) {
          $characters = "abcdefghijklmnopqrstuvwxyz";
          $posterName .= $characters[rand(0,strlen($characters))];
          $posterFile = $posterFolder.$posterName.".".$posterType;
        }
        if(move_uploaded_file($_FILES["poster"]["tmp_name"],$posterFile)) {
          if(query("UPDATE tour SET poster = '".$posterName.".".$posterType."' WHERE id = ".$_POST["id"])) {
            header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=poster");
          } else {
            header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
            die();
          }
        } else {
          header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
          die();
        }
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=file-size");
        die();
      }
    } else {
      header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=file-type");
      die();
    }
  } else {
    header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=file-select");
    die();
  }

}
