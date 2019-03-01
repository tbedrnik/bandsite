<?php
/**
 * Vykresluje tabulku s proměnnými v tabulce *settings*
 * Umožňuje je upravovat
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["change"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      $newValue = mres($_POST["value"]);
      if(query("UPDATE settings SET value = '$newValue' WHERE id = ".$_POST["id"])) {
        header("Location: index.php?p=settings&changes=made");
        die();
      } else {
        header("Location: index.php?p=settings&changes=failed");
        die();
      }
    }
  }
}

include("index_header.php");

?>

<h1>Settings</h1>
<p>These variables runs your bandsite.</p>
<p>You can change them by clicking on their name.</p>
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $result = query("SELECT * FROM settings WHERE name NOT LIKE 'band_description' AND name NOT LIKE 'install' ORDER BY name");
      if(mysqli_num_rows($result)>0){
        $temp = file_get_contents("templates/set_table_row.html");
        while($set = mysqli_fetch_assoc($result)) {
          $template = $temp;
          $template = str_replace("%id%",$set["id"],$template);
          $template = str_replace("%name%",$set["name"],$template);
          $template = str_replace("%value%",$set["value"],$template);
          echo $template;
        }
      }
    ?>
  </tbody>
</table>
</div>
<!-- end of content -->

<!-- start of modals -->
<div class="modal">
  <div class="modal-body">
    <div class="body">
      <h1>Change value</h1>
      <p>
        You are changing value of <span class="name"></span>
      </p>
      <div class="help">
        <h3>What's this?</h3>
        <p></p>
      </div>
      <div class="form">
        <form method="post">
          <input type="text" name="value" value="">
          <input type="hidden" name="id" value="">
          <input type="submit" name="change" value="Change value">
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>

  <?php include("index_footer.php"); ?>
