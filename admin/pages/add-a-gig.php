<?php
/**
 * Skript s vykreslením formuláře a jeho validací k ukládání koncertů do databáze
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  //validating
  $errors = 0;

  //validate
  $errors += validate("event","strlen",5);
  $errors += validate("venue","strlen",10);
  $errors += validate("venue-info","novalidation");
  $errors += validate("address","strlen",10);
  $errors += validate("trip-info","novalidation");
  $errors += validate("date","date");
  $errors += validate("time","time");
  $errors += validate("playtime","number");
  $errors += validate("revenue","number");
  $errors += validate("notes","strlen",0);
  $errors += validate("promoter","promoter");
  $errors += validate("selectedPromoter","novalidation");
  $errors += validate("add","novalidation");

  if($errors==0) {
    if(saveGig($_POST)) {
      header("Location: index.php?p=browse-tour&changes=gig-added");
      die();
    }
    else {
      header("Location: index.php?p=browse-tour&changes=failed");
      die();
    }
  }
}

/**
 * Volá validační funkce, podle zadaných parametrů
 * Error - 1, Success - 0
 *
 * @param string $co Klíč hodnoty v $_POST
 * @param string $jak Jakou metodou validovat
 * @param string|int $par Parametr k validační funkci, pokud je potřeba
 *
 * @return bool Výsledek validační funkce
 */
function validate($co,$jak,$par=NULL) {
  global $value, $class;
  if(isset($_POST[$co])) {
    $value[$co]=$_POST[$co];
    switch($jak) {
      case "strlen":
        return validateStrlen($co,$par);
      case "number":
        return validateNumber($co);
      case "date":
        return validateDate($co);
      case "time":
        return validateTime($co);
      case "promoter":
        return validatePromoter($co);
      case "novalidation":
        return noValidation($co);
      default:
        return 1;
    }
  } else {
    $value[$co]="";
    return validationError($co);
  }
}


/**
 * Hodnotu zapíše do $value, ale unsetne z $_POST
 * Používá se pro inputy, které nechceme ukládat do databáze
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool 0 - Nepřičte error
 */
function noValidation($co) {
  global $value;
  $value[$co] = $_POST[$co];
  unset($_POST[$co]);
  return 0;
}

/**
 * Validuje pomocí funkce strlen
 *
 * @param string $co Klíč hodnoty v poli $_POST
 * @param int $par Porovnávaná délka textu
 *
 * @return bool Výsledek validace
 */
function validateStrlen($co,$par) {
  global $value, $class;
  if(strlen($_POST[$co])<$par) {
    return validationError($co);
  } else {
    return validationSuccess($co);
  }
}

/**
 * Validuje pomocí funkce checkdate
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool Výsledek validace
 */
function validateDate($co) {
  if(strlen($co)>0){
    $date = explode("-",$_POST[$co]);
    if(count($date)==3){
      if(checkdate($date[1],$date[2],$date[0])) {
        return validationSuccess($co);
      } else {
        return validationError($co);
      }
    } else {
      return validationError($co);
    }
  } else {
    return validationError($co);
  }
}

/**
 * Validuje pomocí funkce checktime
 *
 * @see functions/checktime
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool Výsledek validace
 */
function validateTime($co) {
  global $value, $class;
  if(strlen($co)>0){
    $time = explode(":",$_POST[$co]);
    if(count($time)==2) {
      if(checktime($time[0],$time[1],0)) {
        return validationSuccess($co);
      } else {
        return validationError($co);
      }
    } else {
      return validationError($co);
    }
  } else {
    return validationError($co);
  }
}

/**
 * Validuje pomocí funkce ctype_digit
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool Výsledek validace
 */
function validateNumber($co) {
  if(!ctype_digit($_POST[$co])) {
  return validationError($co);
  } else {
    return validationSuccess($co);
  }
}

/**
 * Validuje pomocí funkce ctype_digit a zároveň porovnává velikost
 * Hodnota *promoter* musí být větší než 1
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool Výsledek validace
 */
function validatePromoter($co) {
  if(!ctype_digit($_POST[$co])||$_POST[$co]<1) {
  return validationError($co);
  } else {
    return validationSuccess($co);
  }
}

/**
 * Hodnotu z pole $_POST přijme za validní a vrátí 0
 * Zapíše do proměnné $class pod klíč $co hodnotu "success"
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool 0
 */
function validationSuccess($co) {
  global $class;
  $class[$co] = "success";
  return 0;
}

/**
 * Hodnotu z pole $_POST přijme za validní a vrátí 0
 * Zapíše do proměnné $class pod klíč $co hodnotu "error"
 *
 * @param string $co Klíč hodnoty v poli $_POST
 *
 * @return bool 1
 */
function validationError($co) {
  global $class;
  $class[$co] = "error";
  return 1;
}

/**
 * Vypíše hodnotu z proměnné $value
 * Na místo promotéra vypisuje i defaultní údaje
 *
 * @param string $co Klíč hodnoty v poli $value
 * @param bool $attr Vypsat jako atribut?
 */
function echoValue($co,$attr=true) {
  global $value;
  if(isset($value[$co])) {
    if($attr) {
      echo 'value="'.$value[$co].'"';
    }
    else {
      echo $value[$co];
    }
  }
  else {
    if($co=="promoter") {
      echo 'value="1"';
    }
    if($co=="selectedPromoter") {
      echo 'value="Own band gig"';
    }
  }
}

/**
 * Vypíše CSS třídu z pole $class
 *
 * @param string $co Klíč hodnoty v poli
 */
function echoClass($co) {
  global $class;
  if(isset($class[$co])) {
    echo $class[$co];
  }
}

include("index_header.php");

?>

<h1>Add a gig</h1>
<div class="form">
  <form class="" method="post">
    <div class="form-group">
      <label for="event">Event:</label>
      <input id="event" type="text" name="event" <?php echoValue("event") ?> class="<?php echoClass("event") ?>">
    </div>
    <div class="form-group">
      <label for="venue">Venue:</label>
      <input id="venue" type="text" name="venue" <?php echoValue("venue") ?> class="<?php echoClass("venue") ?>">
      <input type="text" <?php if(isset($value["address"])) echo "hidden" ?> name="venue-info" readonly placeholder="Fill venue to get trip info" <?php echoValue("venue-info") ?> class="aboveinfo">
    </div>
    <div class="form-group" <?php if(!isset($value["address"])) echo "hidden" ?>>
      <label for="address">Address:</label>
      <input id="address" type="text" name="address" <?php echoValue("address") ?> class="<?php echoClass("address") ?>">
      <input type="text" name="trip-info" readonly placeholder="Fill address to get trip info" <?php echoValue("trip-info") ?> class="aboveinfo">
    </div>
    <div class="form-group cols">
      <label for="date">Date:</label>
      <input id="date" type="date" name="date" <?php echoValue("date") ?> class="<?php echoClass("date") ?>">
      <label for="time">Time:</label>
      <input id="time" type="time" name="time" <?php echoValue("time") ?> class="<?php echoClass("time") ?>">
    </div>
    <div class="form-group cols">
      <label for="playtime">Playtime:</label>
      <input id="playtime" type="number" name="playtime" <?php echoValue("playtime") ?> class="<?php echoClass("playtime") ?>">
      <label for="revenue">Revenue:</label>
      <input id="revenue" type="number" name="revenue" <?php echoValue("revenue") ?> class="<?php echoClass("revenue") ?>">
    </div>
    <div class="form-group">
      <label for="notes">Notes:</label>
      <textarea id="notes" name="notes" rows="5"><?php echoValue("notes",false) ?></textarea>
    </div>
    <div class="form-group">
      <label for="promoter">Promoter:</label>
      <input type="text" readonly <?php echoValue("selectedPromoter") ?> name="selectedPromoter" class="selected">
      <input type="text" placeholder="Search promoter..." class="select-promoter">
      <p class="promoters-hint"></p>
      <input type="hidden" name="promoter" <?php echoValue("promoter") ?>>
    </div>
    <input type="submit" name="add" value="Save gig">
  </form>
</div>

<?php include("index_footer.php"); ?>
