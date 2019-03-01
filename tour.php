<?php
/**
 * Podstránka zobrazující koncerty se stránkováním
 *
 * @package Bandsite
 */

mb_internal_encoding("UTF-8");

require("db/db.php");
require("db/functions.php");
/**
 * Kolik koncertů bude zobrazeno na jedné stránce
 *
 * @var int
 */
$gigsperpage = getValue("gigs_per_page_tour");
if ($gigsperpage < 1) $gigsperpage = 1;

/**
 * Číslo aktuální stránky
 *
 * @var int
 */
$current_page = 1;

/**
 * Počet následujících koncertů v databázi
 *
 * @var int
 */
$gigs = howManyGigs();

/**
 * Číslo poslední stránky
 *
 * @var int
 */
$last_page = ceil($gigs/$gigsperpage);

if(isset($_GET["p"])) {
  if(!empty($_GET["p"])) {
    if(is_numeric($_GET["p"])) {
      if($_GET["p"]>0&&$_GET["p"]<=$last_page) {
        $current_page = $_GET["p"];
      } else {
        header("Location: tour.php?p=1");
      }
    } else {
      header("Location: tour.php?p=1");
    }
  } else {
    header("Location: tour.php?p=1");
  }
}

/**
 * Číslo prvního koncertu na aktuální stránce
 *
 * @var int
 */
$current_first = ($current_page-1)*$gigsperpage+1;

/**
 * Jestliže je zobrazen pouze jeden koncert, je true
 *
 * @var bool
 */
$only_one = false;

/**
 * Číslo prvního koncertu na aktuální stránce
 *
 * @var int
 */
$current_last = $current_first;

if($gigsperpage>1&&$current_first<$gigs) {
  if(($gigs-$current_first)>=$gigsperpage) {
    $current_last = $current_page*$gigsperpage;
  } else {
    $current_last = $gigs;
  }
} else {
  $only_one = true;
}

?>

<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--<meta http-equiv="X-UA-Compatible" content="ie=edge">-->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet/less" href="css/tourpage.less">
  <script src="js/less.min.js" charset="utf-8"></script>
  <title>Bandsite</title>
</head>
<body>

<section id="header">
  <div class="header">
    <h1><?php echo getValue("bandname"); ?> <small>// tour</small></h1>
  </div>
</section>

<section id="tour">
  <div class="tour">
    <div class="gig-holder">
      <?php
        loadGigs($gigsperpage,$current_page,false);
      ?>
    </div>
  </div>
</section>

<section id="pagination">
  <div class="pagination">
      <div class="info">
        <?php
          echo "You are seeing gig number ";
          echo $current_first;
          if(!$only_one) {
            echo " - ";
            echo $current_last;
          }
          echo " of all ";
          echo $gigs;
          echo " gigs.";
         ?>

      </div>
      <div class="arrows">
        <?php
          $previous_page = 0;

          if($current_page>1) {
            $previous_page = $current_page-1;
            echo "<a href=\"tour.php?p=$previous_page\" title=\"Previous page\"><i class=\"fa fa-caret-left\"></i></a>";
          }

          /**
           * Číslo právě tištěné stránky do navigace
           *
           * @var int
           */
          $current_printed_page = 1;
          while($current_printed_page>=1 && $current_printed_page<=$last_page) {
            if($current_printed_page==$current_page)
             echo "<a class=\"current\" title=\"Current page\">$current_printed_page</a>";
            else
              echo "<a href=\"tour.php?p=$current_printed_page\" title=\"Page number $current_printed_page\">$current_printed_page</a>";
            $current_printed_page++;
          }

          if($current_page<$last_page) {
            $next_page = $current_page+1;
            echo "<a href=\"tour.php?p=$next_page\" title=\"Next page\"><i class=\"fa fa-caret-right\"></i></a>";
          }

         ?>
      </div>
      <div class="home">
        <a href="index.php">Go back to homepage</a>
      </div>
    </div>
  </div>
</section>
</body>
</html>
