<?php
/**
 * Skript vykresluje tabulky veřejních a soukromých členů kapely a jejich kontaktních údajů
 * Obsahuje také formulář pro vytvoření nového člena, mazání a editaci hodnot
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["add-member"])) {
    if(isset($_POST["nickname"])&&isset($_POST["role"])&&isset($_POST["public"])&&isset($_POST["fullname"])&&isset($_POST["email"])&&isset($_POST["phone"])) {
      $mnickname = mres($_POST["nickname"]);
      $mrole = mres($_POST["role"]);
      $mpublic = mres($_POST["public"]);
      $mfullname = mres($_POST["fullname"]);
      $memail = mres($_POST["email"]);
      $mphone = mres($_POST["phone"]);
      if(query("INSERT INTO members (id,nickname,role,public,fullname,email,phone) VALUES (NULL,'$mnickname','$mrole',$mpublic,'$mfullname','$memail','$phone')")) {
        header("Location: index.php?p=band-members&changes=member-added");
        die();
      } else {
        header("Location: index.php?p=band-members&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["edit-member"])) {
    if(isset($_POST["nickname"])&&isset($_POST["role"])&&isset($_POST["public"])&&isset($_POST["fullname"])&&isset($_POST["email"])&&isset($_POST["phone"])&&isset($_POST["id"])&&is_numeric($_POST["id"])) {
      $mid = mres($_POST["id"]);
      $mnickname = mres($_POST["nickname"]);
      $mrole = mres($_POST["role"]);
      $mpublic = mres($_POST["public"]);
      $mfullname = mres($_POST["fullname"]);
      $memail = mres($_POST["email"]);
      $mphone = mres($_POST["phone"]);
      if(query("UPDATE members SET nickname='$mnickname',role='$mrole',public=$mpublic,fullname='$mfullname',email='$memail',phone='$mphone' WHERE id=$mid")) {
        header("Location: index.php?p=band-members&changes=made");
        die();
      } else {
        header("Location: index.php?p=band-members&changes=failed");
        die();
      }
    }
  }
  if(isset($_POST["delete-member"])) {
    if(isset($_POST["id"])&&is_numeric($_POST["id"])){
      if(query("DELETE FROM members WHERE id=".$_POST["id"])) {
        header("Location: index.php?p=band-members&changes=member-deleted");
        die();
      } else {
        header("Location: index.php?p=band-members&changes=failed");
        die();
      }
    }
  }
}

include("index_header.php");

?>


<h1>Band members</h1>
<a class="new-member"><i class="fa fa-plus"></i> New member</a>
<div class="cols">
  <div class="col">
    <h2><i class="fa fa-eye"></i> Public members</h2>
    <table class="member">
      <thead>
        <tr>
          <th rowspan="2">Image</th>
          <th>Fullname</th>
          <th>Nickname</th>
          <th>Role</th>
        </tr>
        <tr>
          <th>Email</th>
          <th>Phone</th>
          <th>Controls</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = query("SELECT * FROM members WHERE public = 1");

        if(mysqli_num_rows($result)>0) {

          while($member = mysqli_fetch_assoc($result)) {

            $template = file_get_contents("templates/member_table_row.html");

            $template = str_replace("%id%",$member["id"],$template);

            if(file_exists("img/members/".$member["id"].".jpg")) {
              $template = str_replace("user.png","members/".$member["id"].".jpg",$template);
            }

            $template = str_replace("%name%",$member["nickname"],$template);
            $template = str_replace("%role%",$member["role"],$template);
            $template = str_replace("%public%",$member["public"],$template);
            $template = str_replace("%fullname%",$member["fullname"],$template);
            $template = str_replace("%email%",$member["email"],$template);
            $template = str_replace("%phone%",$member["phone"],$template);

            echo $template;
          }
        }

        else {
          echo "<tr><td></td><td colspan=3><strong>No</strong> public members</td></tr>";
        }

        ?>
      </tbody>
    </table>
  </div>
  <div class="col">
    <h2><i class="fa fa-eye-slash"></i> Secret members</h2>
    <table class="member">
      <thead>
        <tr>
          <th rowspan="2">Image</th>
          <th>Fullname</th>
          <th>Nickname</th>
          <th>Role</th>
        </tr>
        <tr>
          <th>Email</th>
          <th>Phone</th>
          <th>Controls</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = query("SELECT * FROM members WHERE public = 0");

        if(mysqli_num_rows($result)>0) {

          while($member = mysqli_fetch_assoc($result)) {

            $template = file_get_contents("templates/member_table_row.html");

            $template = str_replace("%id%",$member["id"],$template);

            if(file_exists("img/members/".$member["id"].".jpg")) {
              $template = str_replace("user.png","members/".$member["id"].".jpg",$template);
            }

            $template = str_replace("%name%",$member["nickname"],$template);
            $template = str_replace("%role%",$member["role"],$template);
            $template = str_replace("%public%",$member["public"],$template);
            $template = str_replace("%fullname%",$member["fullname"],$template);
            $template = str_replace("%email%",$member["email"],$template);
            $template = str_replace("%phone%",$member["phone"],$template);

            echo $template;
          }
        }

        else {
          echo "<tr><td></td><td colspan=3><strong>No</strong> secret members</td></tr>";
        }

        ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal" id="new-member">
  <div class="modal-body">
    <div class="body">
      <h1>New member</h1>
      <div class="form">
        <form method="post">
          <div class="group">
            <label for="nm_nickname">Nickname</label>
            <input type="text" id="nm_nickname" name="nickname">
          </div>
          <div class="group">
            <label for="nm_role">Role</label>
            <input type="text" id="nm_role" name="role">
          </div>
          <div class="group">
            <label for="nm_fullname">Fullname</label>
            <input type="text" id="nm_fullname" name="fullname">
          </div>
          <div class="group">
            <label for="nm_email">Email</label>
            <input type="text" id="nm_email" name="email">
          </div>
          <div class="group">
            <label for="nm_phone">Phone</label>
            <input type="text" id="nm_phone" name="phone">
          </div>
          <div class="group">
            <input type="button" data-name="public" data-value="1" value="Public" class="checked">
            <input type="button" data-name="secret" data-value="0" value="Secret">
            <input type="hidden" name="public" value="1">
          </div>
          <div class="group">
            <input type="submit" name="add-member" value="Add member">
          </div>
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<div class="modal" id="edit-member">
  <div class="modal-body">
    <div class="body">
      <h1>Edit member</h1>
      <div class="form">
        <form method="post">
          <input type="hidden" name="id">
          <div class="group">
            <label for="em_nickname">Nickname</label>
            <input type="text" id="em_nickname" name="nickname">
          </div>
          <div class="group">
            <label for="em_role">Role</label>
            <input type="text" id="em_role" name="role">
          </div>
          <div class="group">
            <label for="em_fullname">Fullname</label>
            <input type="text" id="em_fullname" name="fullname">
          </div>
          <div class="group">
            <label for="em_email">Email</label>
            <input type="text" id="em_email" name="email">
          </div>
          <div class="group">
            <label for="em_phone">Phone</label>
            <input type="text" id="em_phone" name="phone">
          </div>
          <div class="group">
            <input type="button" data-name="public" data-value="1" value="Public" class="checked">
            <input type="button" data-name="secret" data-value="0" value="Secret">
            <input type="hidden" name="public" value="1">
          </div>
          <div class="group">
            <input type="submit" name="edit-member" value="Edit member">
          </div>
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<div class="modal" id="delete-member">
  <div class="modal-body">
    <div class="body">
      <h1>Delete member</h1>
      <p>Do you really want to delete <span class="name"></span>?</p>
      <div class="form">
        <form method="post">
          <input type="hidden" name="id">
          <div class="group">
            <input type="submit" name="delete-member" value="Delete member">
          </div>
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<?php include("index_footer.php"); ?>
