<?php
/**
 * Hlavní soubor, který má na starost kontrolu uživatele a načítání jednotlivých stránek
 *
 * @package Bandsite Manager
 */

mb_internal_encoding("UTF-8");
session_start();

if(!isset($_SESSION["user"])) {
  header("Location: logform.php");
  exit();
}


if(isset($_GET["p"])){
  $page = "pages/".$_GET["p"].".php";
  $pageName = $_GET["p"];
  if(!file_exists($page)) {
    header("Location: index.php?p=404");
  }
} else {
  header("Location: index.php?p=dashboard");
  die();
}

//jestliže je přihlášen superuser, je true
$su = $_SESSION["user"]["username"]=="superuser";

//načteme databázi
require("../db/db.php");

//načteme funkce
require("functions.php");

//zda soubor existuje jsme už ověřili, nyní ho můžeme načíst
include($page);
