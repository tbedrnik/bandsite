<?php
/**
 * Hlavní stránka prezentační části
 *
 *
 * @author Tomáš Bedrník <tomas@bedrnik.cz>
 *
 * @package Bandsite
 *
 * @see db/db
 * @see db/functions
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 */


mb_internal_encoding("UTF-8");
session_start();
require("db/db.php");
require("db/functions.php");
/**
 * Proměnná, která se v případě neodeslání mailu naplní errorem
 *
 * @var string
 */
$sentMsg = "";

if($_SERVER["REQUEST_METHOD"]=="POST"&&isset($_POST)) {

 /**
   * Pole pro uchování stavu polí
   *
   * @var array
   */
  $valid = array("name"=>0,"email"=>0,"event"=>0,"date"=>0,"message"=>0);

  if(isset($_POST["name"])) {
    $value["name"] = htmlspecialchars($_POST["name"]);
    $valid["name"] = strlen($value["name"])>3;
    // echo $value["name"]." ".(string)$valid["name"]."<br>";
  }

  if(isset($_POST["email"])) {
    $value["email"] = htmlspecialchars($_POST["email"]);
    $valid["email"] = (bool)filter_var($value["email"],FILTER_VALIDATE_EMAIL);
    // echo $value["email"]." ".(string)$valid["email"]."<br>";
  }

  if(isset($_POST["event"])) {
    $value["event"] = htmlspecialchars($_POST["event"]);
    $valid["event"] = strlen($value["event"])>3;
    // echo $value["event"]." ".(string)$valid["event"]."<br>";
  }

  if(isset($_POST["date"])) {
    $value["date"] = htmlspecialchars($_POST["date"]);
    $tmp_date = explode("-",$value["date"]);
    if(count($tmp_date)==3){
      $valid["date"] = checkdate($tmp_date[1],$tmp_date[2],$tmp_date[0]);
    }
    // echo $value["date"]." ".(string)$valid["date"]."<br>";
  }

  if(isset($_POST["message"])) {
    $value["message"] = htmlspecialchars($_POST["message"]);
    $valid["message"] = strlen($value["message"])>20;
    // echo $value["message"]." ".(string)$valid["message"]."<br>";
  }


  if($valid["name"]&&$valid["email"]&&$valid["event"]&&$valid["date"]&&$valid["message"]) {

    require("libs/PHPMailer.php");

    $mail = new PHPMailer(false);
    $mail->CharSet = 'UTF-8';
    $mail->addAddress(getValue("band_email"),getValue("bandname"));
    $mail->setFrom($_POST["email"],$_POST["name"]);
    $mail->Subject = "Booking: ".$_POST["event"];
    $mail->Body = $_POST["message"];

    if($mail->send()) {
      header("Location: index.php?mail=sent#booking");
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

?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" href="css/index.css">
  <title>Ešus // sci-fi punkrock</title>
</head>
<body>
<div class="header_bg"></div>
<section id="header">
  <div class="header">
    <h1>
    <?php if(file_exists("img/logo.png")): ?>
      <img src="img/logo.png" alt="<?php echo getValue("bandname"); ?>">
    <?php else: ?>
      <?php echo getValue("bandname"); ?>
    <?php endif ?>
    </h1>
    <nav>
      <ul>
        <li><a href="#latest">Latest</a></li>
        <li><a href="#tour">Tour</a></li>
        <li><a href="#music">Music</a></li>
        <li><a href="#band">Band</a></li>
        <li><a href="#booking">Booking</a></li>
      </ul>
    </nav>
  </div>
</section>
<section id="latest">
  <div class="latest">
    <h1>Latest</h1>
    <?php loadVideoPlayer(); ?>
  </div>
</section>
<section id="music">
  <div class="music">
    <h1>Music</h1>
    <?php loadMusicPlayer(); ?>
  </div>
</section>
<section id="tour">
  <div class="tour">
    <h1>Tour</h1>
    <div class="gig-holder">
      <?php loadGigs(3); ?>
    </div>
  </div>
</section>
<section id="band">
  <div class="band">
    <h1>Band</h1>
    <div class="desc">
    	<?php echo getValue("band_description"); ?>
	  </div>
    <div class="milestones">
    	<h2>Milestones</h2>
      <?php loadMilestones(); ?>
    </div>
    <div class="members">
    	<h2>Members</h2>
    	<?php loadMembers(); ?>
    </div>
  </div>
</section>
<section id="booking">
  <div class="booking">
    <h1>Booking</h1>
    <?php if (isset($_GET["mail"])&&$_GET["mail"]=="sent"): ?>
    <p>Thank you for your interest. We'll respond as soon as possible.</p>
    <p><a href="index.php#booking">New message</a></p>
    <?php else: ?>
    <p>If you're intrested in our music and want to book us for your event, please contact us through the form below.</p>
    <div class="form">
      <form method="post" action="#booking">
        <div class="form-group">
          <label>Name / Company:</label>
          <input type="text" autocomplete="name" class="<?php echoClass("name") ?>" <?php echoValue("name") ?> name="name" required>
        </div>
        <div class="form-group">
          <label>Email:</label>
          <input type="email" autocomplete="email" class="<?php echoClass("email") ?>" <?php echoValue("email") ?> name="email" required>
        </div>
        <div class="form-group">
          <label>Event:</label>
          <input type="text" class="<?php echoClass("event") ?>" <?php echoValue("event") ?> name="event" required>
        </div>
        <div class="form-group">
          <label>Date:</label>
          <input type="date" class="<?php echoClass("date") ?>" <?php echoValue("date") ?> name="date" title="In format YYYY-MM-DD (year-month-date)" required>
        </div>
        <div class="form-group">
          <label>Message:</label>
          <textarea class="<?php echoClass("message") ?>" name="message" rows="7" title="Message of at least 20 characters" required><?php echoValue("message",false) ?></textarea>
        </div>
        <div class="form-group"><input type="submit" value="Send"> <?php echo isset($sentMsg) ? $sentMsg : "" ?></div>
      </form>
    </div>
    <?php endif ?>
  </div>
</section>
<?php if(isset($_SESSION["user"])): ?>
  <div class="floating"><a href="admin/">Bandsite Manager</a></div>
<?php endif; ?>
<script src="js/jquery-3.2.1.min.js" charset="utf-8"></script>
<script src="js/index.js" charset="utf-8"></script>
</body>
</html>
