<?php
/**
 * Soubor s funkcemi, které se používají v administrační části
 *
 * @package Bandsite Manager
 */

/**
 * Vypíše zprávu
 *
 * @param string $message Zpráva k zobrazení
 * @param string $type Typ zprávy (success, error)
 */
function echoMessage($message,$type) {
  echo "<div class=\"message $type\"><span class=\"text\">$message</span><span class=\"close\"><i class=\"fa fa-close\"></i></span></div>";
}

/**
 * Vypíše zprávu podle parametrů v url
 *
 * @see functions:echoMessage
 */
function messageHandler() {
  if(isset($_GET["changes"])) {
    switch($_GET["changes"]) {
      case "made":
        echoMessage("Changes were saved.","success");
        break;
      case "gig-added":
        echoMessage("Gig was added.","success");
        break;
      case "gig-deleted":
        echoMessage("Gig was deleted.","success");
        break;
      case "gig-cloned":
        echoMessage("Gig was cloned.","success");
        break;
      case "member-added":
        echoMessage("Member was added.","success");
        break;
      case "member-deleted":
        echoMessage("Member was deleted.","success");
        break;
      case "milestone-added":
        echoMessage("Milestone was added.","success");
        break;
      case "milestone-deleted":
        echoMessage("Milestone was deleted.","success");
        break;
      case "promoter-added":
        echoMessage("Promoter was added.","success");
        break;
      case "promoter-deleted":
        echoMessage("Promoter was deleted.","success");
        break;
      case "file-size":
        echoMessage("File size must be less than 1MB.","error");
        break;
      case "file-type":
        echoMessage("File must be JPG, JPEG, PNG or GIF.","error");
        break;
      case "file-select":
        echoMessage("No file was selected.","error");
        break;
      case "poster":
        echoMessage("New poster was uploaded.","success");
        break;
      case "mail-sent":
        echoMessage("Email was successfully sent.","success");
        break;
      case "user-added":
        echoMessage("User was created.","success");
        break;
      case "user-deleted":
        echoMessage("User was deleted.","success");
        break;
      case "password-reset":
        echoMessage("Password has been reset to default value.","success");
        break;
      case "passwords-dont-match":
        echoMessage("Passwords don't match.","error");
        break;
      case "old-password-wrong":
        echoMessage("Your old password isn't correct.","error");
        break;
      case "failed":
        echoMessage("Something went wrong. Please try again later.","error");
        break;
    }
  }
}

/**
 * Vygeneruje full-path url cílové stránky
 *
 * @param string $target Jméno cílové stránky
 * @param bool $return True pro vrácení stringu, False (výchozí) pro vyechování stringu
 *
 * @return string Jen když je $return True
 */
function pageLink($target,$return=false) {
  if($return){
    return $_SERVER["PHP_SELF"]."?p=".$target;
  }
  else {
    echo $_SERVER["PHP_SELF"]."?p=".$target;
  }
}

/**
 * Ekvivalent k checkdate
 *
 * @see https://gist.github.com/toshimaru/3017096 Checkdate GitHub projekt
 *
 * @param int $hour Hodiny
 * @param int $min Minuty
 * @param int $sec Vteřiny
 *
 * @return bool Je čas validní?
 */
function checktime($hour, $min, $sec) {
     if ($hour < 0 || $hour > 23 || !is_numeric($hour)) {
         return false;
     }
     if ($min < 0 || $min > 59 || !is_numeric($min)) {
         return false;
     }
     if ($sec < 0 || $sec > 59 || !is_numeric($sec)) {
         return false;
     }
     return true;
}

/**
 * Naformátuje datum
 *
 * @param string $date Datum ve formátu Y-m-d
 *
 * @return string Datum ve formátu j.n.Y
 */
function formDate($date) {
  return date_format(date_create_from_format("Y-m-d",$date),"j.n.Y");
}

/**
 * Naformátuje čas
 *
 * @param string $time Čas ve formátu H:i:s
 *
 * @return string Čas ve formátu G:i
 */
function formTime($time) {
  return date_format(date_create_from_format("H:i:s",$time),"G:i");
}

/**
 * Vyrobí timestamp
 *
 * @param string $date Datum ve formátu Y-m-d
 * @param string $time Čas ve formátu H:i:s
 *
 * @return string Vteřinový timestamp
 */
function formTimestamp($date, $time) {
  return date_timestamp_get(date_create_from_format("Y-m-d H:i:s",$date." ".$time));
}

/**
 * Uloží koncert do databáze
 *
 * @see db:query
 *
 * @param array $gig Pole s hodnotami
 *
 * @return bool Výsledek query() vytvořeného dotazu
 */
function saveGig(array $gig) {

    if(isset($gig["time"]))
        $gig["time"].=":00";

    $keys = $values = "";

    foreach($gig as $key => $value) {
      $keys .= $key.",";
      $values .= "'".mysqli_real_escape_string($GLOBALS["db"],$value)."',";
    }

    $keys = rtrim($keys,",");
    $values = rtrim($values,",");

    $query = "INSERT INTO tour(";
    $query .= $keys;
    $query .= ") VALUES (";
    $query .= $values;
    $query .= ")";

    return query($query);
}

/**
 * Vymaže koncert z databáze podle ID
 *
 * @see db:query
 *
 * @param int $id Identifikátor koncertu
 *
 * @return bool Výsledek query() vytvořeného dotazu
 */
function deleteGig($id) {
  return query("DELETE FROM tour WHERE id=$id");
}

/**
 * Naklonuje koncert v databázi podle ID
 *
 * @see db:query
 *
 * @param int $id Identifikátor koncertu
 *
 * @return bool Výsledek query() vytvořeného dotazu
 */
function cloneGig($id) {
  return query("INSERT INTO tour(`event`,`venue`,`address`,`date`,`time`,`playtime`,`revenue`,`notes`,`promoter`,`poster`,`fb`,`web`) (SELECT `event`,`venue`,`address`,`date`,`time`,`playtime`,`revenue`,`notes`,`promoter`,`poster`,`fb`,`web` FROM tour WHERE id=$id)");
}
