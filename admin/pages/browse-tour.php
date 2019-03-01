<?php
/**
 * Skript pro vykreslení tabulek s budoucími a minulými koncerty
 *
 * @package Bandsite Manager
 */

include("index_header.php");

?>

<h1>Browse Tour</h1>
<a href="<?php pageLink("add-a-gig");?>"><i class="fa fa-plus"></i> Add a gig</a>
<div class="cols">
  <div class="col">
    <h2><i class="fa fa-arrow-right"></i> Upcoming shows</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Showtime</th>
          <th>Event</th>
          <th>Place</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php

        $result = query("SELECT id, date, time, venue, event, public FROM tour WHERE date>=CURRENT_DATE ORDER BY date, time");

        if(mysqli_num_rows($result)>0) {

          $temp = file_get_contents("templates/gig_table_row.html");

          while($gig = mysqli_fetch_assoc($result)) {
            $template = $temp;
            $template = str_replace("%date%",formDate($gig["date"]),$template);
            $template = str_replace("%time%",formTime($gig["time"]),$template);
            $template = str_replace("%venue%",$gig["venue"],$template);
            $template = str_replace("%event%",$gig["event"],$template);
            $template = str_replace("%id%",$gig["id"],$template);
            $template = str_replace("%detail%",pageLink("gig-detail",true)."&g=".$gig["id"],$template);
            if(!$gig["public"]) $template = str_replace("eye","eye-slash",$template);
            else $template = str_replace("%public%","",$template);
            echo $template;
          }
        }

        else {
          echo '<tr><td colspan=5>No upcoming shows</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col">
    <h2><i class="fa fa-arrow-left"></i> Past shows</h2>
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Showtime</th>
          <th>Event</th>
          <th>Place</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php

        $result = query("SELECT id, date, time, venue, event, public FROM tour WHERE date<CURRENT_DATE ORDER BY date, time DESC");

        if(mysqli_num_rows($result)>0) {

          $temp = file_get_contents("templates/gig_table_row.html");

          while($gig = mysqli_fetch_assoc($result)) {
            $template = $temp;
            $template = str_replace("%date%",formDate($gig["date"]),$template);
            $template = str_replace("%time%",formTime($gig["time"]),$template);
            $template = str_replace("%venue%",$gig["venue"],$template);
            $template = str_replace("%event%",$gig["event"],$template);
            $template = str_replace("%id%",$gig["id"],$template);
            $template = str_replace("%detail%",pageLink("gig-detail",true)."&g=".$gig["id"],$template);
            if(!$gig["public"]) $template = str_replace("eye","eye-slash",$template);
            else $template = str_replace("%public%","",$template);
            echo $template;
          }
        }

        else {
          echo '<tr><td colspan=5>No upcoming shows</td></tr>';
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php include("index_footer.php"); ?>
