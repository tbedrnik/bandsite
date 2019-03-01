<?php
/**
 * Skript vykresluje detail koncertu, dle identifikátoru v url
 * Když v url id není, přesměruje uživatele na browse-tour
 * Vlastnosti:
 ** Vykresluje trasu a údaje o cestě (Google API, Apify)
 ** Umožňuje upravovat údaje; mazat a klonovat koncert
 ** Vykresluje údaje o promotérovi koncertu
 ** Umožňuje nahrát plakát
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["edit"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])&&isset($_POST["what"])) {
      $what = mysqli_real_escape_string($GLOBALS["db"],$_POST["what"]);
      $newValue = mysqli_real_escape_string($GLOBALS["db"],$_POST["value"]);
      if(query("UPDATE tour SET $what = '$newValue' WHERE id = ".$_POST["id"])) {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=made");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["clone"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      if(cloneGig($_POST["id"])) {
        header("Location: index.php?p=browse-tour&changes=gig-cloned");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["delete"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      if(deleteGig($_POST["id"])) {
        header("Location: index.php?p=browse-tour&changes=gig-deleted");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["deletePoster"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      if(query("UPDATE tour SET poster='0.jpg' WHERE id=".$_POST["id"])) {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=made");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["public"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      //převrátíme hodnotu položky public
      if(query("UPDATE tour SET public = !public WHERE id=".$_POST["id"])) {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=made");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["promoter"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      if(query("UPDATE tour SET promoter='".$_POST["promoter"]."' WHERE id=".$_POST["id"])) {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=made");
        die();
      } else {
        header("Location: index.php?p=gig-detail&g=".$_GET["g"]."&changes=failed");
        die();
      }
    }
  }
  include_once("scripts/poster.php");
}


if(isset($_GET["g"])&&is_numeric($_GET["g"])){

  $result = query("SELECT * FROM tour INNER JOIN promoters ON tour.promoter = promoters.promoter_id WHERE tour.id=".$_GET["g"]);
  if(mysqli_num_rows($result)==1) {
    $gig = mysqli_fetch_assoc($result);

    // načtení šablony
    $template = file_get_contents("templates/gig_detail.html");

    // POSTER
    $posterFile = "../img/posters/".$gig["poster"];
    $template = str_replace("%poster%",$posterFile,$template);

    $template = str_replace("%id%",$gig["id"],$template);

    // GENERAL INFO TABLE
    $template = str_replace("%event%",$gig["event"],$template);
    //nezformátovaný čas je potřeba při vkládání hodnoty data do inputu v modalu
    $template = str_replace("%sqldate%",$gig["date"],$template);
    $template = str_replace("%date%",formDate($gig["date"]),$template);
    $template = str_replace("%time%",formTime($gig["time"]),$template);
    $template = str_replace("%venue%",$gig["venue"],$template);
    $template = str_replace("%address%",$gig["address"],$template);

    // SOCIAL TABLE
    // facebook
    $replaceFacebook = "No event";
    if(!empty($gig["fb"])) $replaceFacebook = "<a href=\"".$gig["fb"]."\" target=\"_blank\">Event link</a>";
    $template = str_replace("%facebook%",$replaceFacebook,$template);
    // web
    $replaceWeb = "No web";
    if(!empty($gig["web"])) $replaceWeb = "<a href=\"".$gig["web"]."\" target=\"_blank\">Web link</a>";
    $template = str_replace("%web%",$replaceWeb,$template);
    // tickets
    $replaceTickets = "No tickets";
    if(!empty($gig["tickets"])) $replaceTickets = "<a href=\"".$gig["tickets"]."\" target=\"_blank\">Tickets link</a>";
    $template = str_replace("%tickets%",$replaceTickets,$template);
    // public
    $replacePublic = "<i class=\"fa fa-eye";
    if($gig["public"]) {
      $replacePublic .= "\"></i> public";
    } else {
      $replacePublic .= "-slash\"></i> not public";
    }
    $template = str_replace("%public%",$replacePublic,$template);

    // NOTES TABLE
    $template = str_replace("%notes%",$gig["notes"],$template);

    // TRIP INFO TABLE
    //načtení tokenu z db
    $gkme = getValue("google_key_maps_embed");
    //url validace domovské adresy kapely načtené z db
    $origin = urlencode(getValue("band_origin"));
     //url validace cílové adresy
    $destination = urlencode($gig["address"]);
    //vložení linku do iframu v šabloně
    $template = str_replace("%gmapembed%","https://www.google.com/maps/embed/v1/directions?key=$gkme&origin=$origin&destination=$destination",$template);

    // GIG INFO TABLE
    $template = str_replace("%playtime%",$gig["playtime"],$template);
    $template = str_replace("%revenue%",$gig["revenue"],$template);

    //načtení tokenu z db
    $gkdm = getValue("google_key_distance_matrix");
    //timestamp potřebného času příjezdu (protože cesta v pátek večer trvá déle než v sobotu ráno)
    $tripArrival = formTimestamp($gig["date"], $gig["time"]);
    //url distance matrix apiny googlu
    $url = "https://maps.google.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$gkdm&arrival_time=$tripArrival";
    //instancování objektu - načtení a dekódování dat z apiny googlu
    $tripData = json_decode(file_get_contents($url));
    //jestli jsem se správně dotázal
    if($tripData->status == "OK") {
      //jestli jsem dostal správná data
      if($tripData->rows[0]->elements[0]->status == "OK") {
        // url apify apiny, kde běží webcrawler, který hledá cenu benzinu
        $url = "https://api.apify.com/v1/tQdCtY8dhEiruAi3Z/crawlers/QHmaxgF75GbquoEqj/lastExec/results?token=BeXMyMqpCaDXWM46R5rcWmehD&format=json&simplified=1";
        //instancování objektu - načtení a dekódování dat z apify
        $dataBenzinu = json_decode(file_get_contents($url));
        //načtení ceny benzinu
        $gasPrice = $dataBenzinu[0]->natural95;
        //načtení délky cesty
        $tripDistance = $tripData->rows[0]->elements[0]->distance->value;
        //výpočet ceny paliva za cestu zaokrouhlený na celé číslo nahoru
        $cost = ceil($tripDistance/1000*2*getValue("car_consumption")/100*$gasPrice);
        $template = str_replace("%cost%",$cost,$template);
        //zisk = honorář - cena paliva
        $profit = $gig["revenue"] - $cost;
        //v případě, že je kapela v minusu, tak je v červených číslech (bohužel normální situace)
        if($profit<0) $template = str_replace("<td>%profit%","<td class=\"red\">%profit%",$template);
        $template = str_replace("%profit%",$profit,$template);

        //načtení časové náročnosti cesty
        $tripDuration = $tripData->rows[0]->elements[0]->duration->value;
        //výpočet ideálního času odjezdu s rezervou 1 hodina
        $tripDeparture = $tripArrival-3600-$tripDuration;
        $template = str_replace("%departure%",date("G:i",$tripDeparture),$template);
      }
    }

    // PROMOTER TABLE
    $template = str_replace("%promoter_id%",$gig["promoter_id"],$template);
    $template = str_replace("%promoter_name%",$gig["promoter_name"],$template);
    $template = str_replace("%promoter_company%",$gig["promoter_company"],$template);
    $template = str_replace("%promoter_email%",$gig["promoter_email"],$template);
    $emailLink = pageLink("mail",true)."&to=".urlencode($gig["promoter_email"])."&subject=".urlencode($gig["event"]);
    $template = str_replace("%emailLink%",$emailLink,$template);
    $template = str_replace("%promoter_phone%",$gig["promoter_phone"],$template);

    include("index_header.php");
    echo $template;

  } else {
     //když koncert se zadaným id neexistuje, dostaneme se zpět do přehledu koncertů
    header("Location: index.php?p=browse-tour");
  }
} else {
  //když není zadáno id, dostaneme se zpět do přehledu koncertů
  header("Location: index.php?p=browse-tour");
}

include("index_footer.php");
