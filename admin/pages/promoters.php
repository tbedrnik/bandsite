<?php
/**
 * Vykresluje tabulku promotérů
 * Umožňuje je mazat, přidávat nebo upravovat jejich údaje.
 * Počítá kolik koncertů každý promotér pořádal
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["add-promoter"])) {
    if(isset($_POST["name"])&&strlen($_POST["name"])>2) {
      $pname = mres($_POST["name"]);
      $pcompany = mres($_POST["company"]);
      $pemail = mres($_POST["email"]);
      $pphone = mres($_POST["phone"]);
      if(query("INSERT INTO promoters VALUES (NULL,'$pname','$pcompany','$pemail','$pphone')")) {
        header("Location: index.php?p=promoters&changes=promoter-added");
        die();
      } else {
        header("Location: index.php?p=promoters&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["edit-promoter"])) {
    if(isset($_POST["name"])&&isset($_POST["company"])&&isset($_POST["email"])&&isset($_POST["phone"])&&isset($_POST["id"])&&is_numeric($_POST["id"])) {
      $pname = mres($_POST["name"]);
      $pcompany = mres($_POST["company"]);
      $pemail = mres($_POST["email"]);
      $pphone = mres($_POST["phone"]);
      if(query("UPDATE promoters SET promoter_name = '$pname', promoter_company = '$pcompany', promoter_email = '$pemail', promoter_phone = '$pphone' WHERE promoter_id = ".$_POST["id"])) {
        header("Location: index.php?p=promoters&changes=made");
        die();
      } else {
        header("Location: index.php?p=promoters&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["delete-promoter"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])){
      if(query("UPDATE tour SET promoter=1 WHERE promoter=".$_POST["id"])) {
        if(query("DELETE FROM promoters WHERE promoter_id=".$_POST["id"])) {
          header("Location: index.php?p=promoters&changes=promoter-deleted");
          die();
        } else {
          header("Location: index.php?p=promoters&changes=failed");
          die();
        }
      } else {
        header("Location: index.php?p=promoters&changes=failed");
        die();
      }
    }
  }
}

include("index_header.php");

?>


<h1>Promoter</h1>
<p>
  <a class="add-promoter"><i class="fa fa-plus"></i> Add promoter</a>
</p>
<table class="promoter">
  <thead>
    <tr>
      <th>Name</th>
      <th>Company</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Gigs</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php
    $result = query("SELECT * FROM promoters WHERE promoter_id > 1");

    if(mysqli_num_rows($result)>0) {

      while($promoter = mysqli_fetch_assoc($result)) {

        $template = file_get_contents("templates/promoter_table_row.html");

        $template = str_replace("%id%",$promoter["promoter_id"],$template);
        $template = str_replace("%name%",$promoter["promoter_name"],$template);
        $template = str_replace("%company%",$promoter["promoter_company"],$template);
        $template = str_replace("%email%",$promoter["promoter_email"],$template);
        $emailLink = pageLink("mail",true)."&to=".urlencode($promoter["promoter_email"]);
        $template = str_replace("%emailLink%",$emailLink,$template);
        $template = str_replace("%phone%",$promoter["promoter_phone"],$template);

        $countResult = query("SELECT count(id) FROM tour where promoter = ".$promoter["promoter_id"]);
        if(mysqli_num_rows($countResult)==1){
          $count = mysqli_fetch_assoc($countResult)["count(id)"];
          $template = str_replace("%count%",$count,$template);
        }

        echo $template;
      }
    }
    ?>
  </tbody>
</table>


  <div class="modal" id="edit-promoter">
    <div class="modal-body">
      <div class="body">
        <h1>Edit promoter</h1>
        <div class="form">
          <form method="post">
            <div class="group">
              <label for="ep_name">Name</label>
              <input type="text" autocomplete="off" id="ep_name" name="name" value="">
            </div>
            <div class="group">
              <label for="ep_company">Company</label>
              <input type="text" autocomplete="off" id="ep_company" name="company" value="">
            </div>
            <div class="group">
              <label for="ep_email">Email</label>
              <input type="text" autocomplete="off" id="ep_email" name="email" value="">
            </div>
            <div class="group">
              <label for="ep_phone">Phone</label>
              <input type="text" autocomplete="off" id="ep_phone" name="phone" value="">
            </div>
            <div class="group">
              <input type="hidden" name="id" value="">
              <input type="submit" name="edit-promoter" value="Edit promoter">
            </div>
          </form>
        </div>
        <span class="close">Close</span>
      </div>
    </div>
  </div>

  <div class="modal" id="add-promoter">
    <div class="modal-body">
      <div class="body">
        <h1>Add promoter</h1>
        <div class="form">
          <form method="post">
            <div class="group">
              <label for="np_name">Name</label>
              <input type="text" id="np_name" name="name">
            </div>
            <div class="group">
              <label for="np_company">Company</label>
              <input type="text" id="np_company" name="company">
            </div>
            <div class="group">
              <label for="np_email">Email</label>
              <input type="text" id="np_email" name="email">
            </div>
            <div class="group">
              <label for="np_phone">Phone</label>
              <input type="text" id="np_phone" name="phone">
            </div>
            <div class="group">
              <input type="submit" name="add-promoter" value="Add promoter">
            </div>
          </form>
        </div>
        <span class="close">Close</span>
      </div>
    </div>
  </div>

  <div class="modal" id="delete-promoter">
    <div class="modal-body">
      <div class="body">
        <h1>Delete promoter</h1>
        <p>Do you really want to delete <span class="name"></span>?</p>
        <div class="form">
          <form method="post">
            <input type="hidden" name="id">
            <div class="group">
              <input type="submit" name="delete-promoter" value="Delete promoter">
            </div>
          </form>
        </div>
        <span class="close">Close</span>
      </div>
    </div>
  </div>


  <?php include("index_footer.php"); ?>
