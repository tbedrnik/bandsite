<?php
/**
 * Vykreslení úvodní stránky administračního prostředí Dashboard
 * Moduly:
 ** Counter - Vykreslí odpočítávání do nejbližšího koncertu (Javascript)
 *
 * @package Bandsite Manager
 */
$counterRender = "";

$result = query("SELECT `event`,`date`, `time` FROM `tour` WHERE `date` >= CURDATE() ORDER BY `date`, `time` LIMIT 1");
if(mysqli_num_rows($result)){
  $g = mysqli_fetch_assoc($result);
  $timeToNext = formTimestamp($g["date"], $g["time"]);

  $t = file_get_contents("templates/counter.html");
  $t = str_replace("%event%",$g["event"],$t);
  $t = str_replace("%timestamp%",$timeToNext,$t);

  $counterRender = $t;
}

include("index_header.php");

?>

<h1><?php echo getValue("bandname") ?></h1>
<?php
 echo $counterRender;
 
 include("index_footer.php");
 ?>
