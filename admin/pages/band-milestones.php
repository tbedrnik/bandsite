<?php
/**
 * Skript pro vykreslení tabulky kapelník milníků
 * Obsahuje formulář pro přidání nových, mazání a editaci
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["add"])) {
    if(isset($_POST["date"])&&isset($_POST["value"])) {
      $newDate = mres($_POST["date"]);
      $newValue = mres($_POST["value"]);
      if(query("INSERT INTO milestones (id,date,value) VALUES (null,'$newDate','$newValue')")) {
        header("Location: index.php?p=band-milestones&changes=milestone-added");
      } else {
        header("Location: index.php?p=band-milestones&changes=failed");
      }
    }
  }
  if(isset($_POST["change"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      $newDate = mres($_POST["date"]);
      $newValue = mres($_POST["value"]);
      if(query("UPDATE milestones SET value = '$newValue', date = '$newDate' WHERE id = ".$_POST["id"])) {
        header("Location: index.php?p=band-milestones&changes=made");
      } else {
        header("Location: index.php?p=band-milestones&changes=failed");
      }
    }
  }
  if(isset($_POST["delete"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])) {
      if(query("DELETE FROM milestones WHERE id = ".$_POST["id"])) {
        header("Location: index.php?p=band-milestones&changes=milestone-deleted");
      } else {
        header("Location: index.php?p=band-milestones&changes=failed");
      }
    }
  }
}

include("index_header.php");

?>

<h1>Band milestones</h1>
<p>Let people see some milestones of your career.</p>
<p><a class="add"><i class="fa fa-plus"></i> add new</a></p>
<table class="milestones">
  <thead>
    <tr>
      <th>Date</th>
      <th>Value</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $result = query("SELECT * FROM milestones");
      $render = "";
      if(mysqli_num_rows($result)>0){
        $temp = file_get_contents("templates/milestones_table_row.html");
        while($ms = mysqli_fetch_assoc($result)) {
          $template = $temp;
          $template = str_replace("%id%",$ms["id"],$template);
          $template = str_replace("%date%",$ms["date"],$template);
          $template = str_replace("%value%",$ms["value"],$template);
          $render .= $template;
        }
      } else {
        $render = "<tr><td colspan=3>No milestones</td></tr>";
      }
      echo $render;
    ?>
  </tbody>
</table>
</div>
<!-- end of content -->

<!-- start of modals -->
<div class="modal" id="change">
  <div class="modal-body">
    <div class="body">
      <h1>Change milestone</h1>
      <div class="form">
        <form method="post">
          <div class="form-group">
            <label for="date">Date:</label>
            <input type="text" id="date" name="date" value="">
          </div>
          <div class="form-group">
            <label for="value">Value:</label>
            <input type="text" id="value" name="value" value="">
          </div>
          <input type="hidden" name="id" value="">
          <input type="submit" name="change" value="Change milestone">
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<div class="modal" id="delete">
  <div class="modal-body">
    <div class="body">
      <h1>Change milestone</h1>
      <div class="help">
        <p>Are you sure you want to delete this milestone?</p>
        <p><strong><span class="date"></span></strong> - <span class="value"></span></p>
      </div>
      <div class="form">
        <form method="post">
          <input type="hidden" name="id" value="">
          <input type="submit" name="delete" value="Delete milestone">
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<div class="modal" id="add">
  <div class="modal-body">
    <div class="body">
      <h1>New milestone</h1>
      <div class="form">
        <form method="post">
          <div class="form-group">
            <label for="date">Date:</label>
            <input type="text" id="date" name="date">
          </div>
          <div class="form-group">
            <label for="value">Value:</label>
            <input type="text" id="value" name="value">
          </div>
          <input type="submit" name="add" value="Add milestone">
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>

  <?php include("index_footer.php"); ?>
