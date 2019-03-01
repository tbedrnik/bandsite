<?php
/**
 * Skript vykresluje formulář pro odeslání emailu
 *
 * K odeslání využívá PHPMailer
 *
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 *
 * @package Bandsite Manager
 */

if(isset($_GET["to"])) {
   $value["to"] = $_GET["to"];
}

if(isset($_GET["subject"])) {
   $value["subject"] = $_GET["subject"];
}

if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST)) {

 /**
   * Pole pro uchování stavu polí
   *
   * @var array
   */
  $valid = array("to"=>0,"subject"=>0,"message"=>0);

  if(isset($_POST["subject"])) {
    $value["subject"] = htmlspecialchars($_POST["subject"]);
    $valid["subject"] = strlen($value["subject"])>0;
    // echo $value["subject"]." ".(string)$valid["subject"]."<br>";
  }

  if(isset($_POST["to"])) {
    $value["to"] = htmlspecialchars($_POST["to"]);
    $valid["to"] = (bool)filter_var($value["to"],FILTER_VALIDATE_EMAIL);
    // echo $value["to"]." ".(string)$valid["to"]."<br>";
  }

  if(isset($_POST["message"])) {
    $value["message"] = htmlspecialchars($_POST["message"]);
    $valid["message"] = strlen($value["message"])>0;
    // echo $value["message"]." ".(string)$valid["message"]."<br>";
  }


  if($valid["to"]&&$valid["subject"]&&$valid["message"]) {

    require("../libs/PHPMailer.php");

    $mail = new PHPMailer(false);
    $mail->CharSet = 'UTF-8';
    $mail->setFrom(getValue("band_email"),$_SESSION["user"]["fullname"]." - ".getValue("bandname"));
    $mail->addAddress($_POST["to"]);
    $mail->Subject = $_POST["subject"];
    $mail->Body = $_POST["message"];

    if($mail->send()) {
      header("Location: index.php?p=mail&changes=mail-sent");
      die();
    } else {
      $sentMsg = $mail->ErrorInfo;
    }
  }
}

/**
 * Vypíše hodnotu z proměnné $value
 *
 * @param string $co Klíč hodnoty v poli $value
 * @param bool $attr Vypsat jako atribut?
 */
function echoValue($co,$attr=true) {
  global $value;
  if(isset($value[$co])) {
    if($attr) echo "value=\"".$value[$co]."\"";
    else echo $value[$co];
  }
}

/**
 * Vypíše CSS třídu podle validnosti
 ** Success / Error
 *
 * @param string $co Klíč hodnoty v poli $valid
 */
function echoClass($co) {
  global $valid;
  if(isset($valid[$co]))
    echo $valid[$co] ? "success" : "error";
}

include("index_header.php");

?>

<h1>New mail</h1>
<div class="form">
  <form method="post">
    <div class="input-group">
      <label for="to">To:</label>
      <input type="text" name="to" id="to" class="<?php echoClass("to") ?>" <?php echoValue("to") ?>>
    </div>
    <div class="input-group">
      <label for="subject">Subject:</label>
      <input type="text" name="subject" id="subject" class="<?php echoClass("subject") ?>" <?php echoValue("subject") ?>>
    </div>
    <div class="input-group">
      <label for="message">Message:</label>
      <textarea name="message" rows="12" name="message" id="message" class="<?php echoClass("message") ?>"><?php echoValue("message",false) ?></textarea>
    </div>
    <input type="submit" name="send" value="Send Mail"> <?php echo isset($sentMsg) ? $sentMsg : "" ?>
  </form>
</div>


<?php include("index_footer.php"); ?>
