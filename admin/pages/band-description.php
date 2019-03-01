<?php
/**
 * Skript pro vykreslení a úpravu proměnné *band_description* v tabulce *settings*
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["change"])) {
    if(isset($_POST["description"])) {
      $newValue = mres($_POST["description"]);
      if(query("UPDATE settings SET value = '$newValue' WHERE name = 'band_description'")) {
        header("Location: index.php?p=band-description&changes=made");
        die();
      } else {
        header("Location: index.php?p=band-description&changes=failed");
        die();
      }
    }
  }
}

include("index_header.php");

?>

<h1>Band description</h1>
<p>Please, describe your band as good as possible.</p>
<p>Remember that you want to entertain your fans, but also promoters can see it.</p>
<div class="form">
  <form method="post">
    <div class="form-group">
      <textarea name="description" rows="8"><?php echo getValue("band_description");?></textarea>
    </div>
    <input type="submit" name="change" value="Change description">
  </form>
</div>

<?php include("index_footer.php"); ?>
