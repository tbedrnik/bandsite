<?php
/**
 * Stránka se zobrazením uživatelů
 * Možnost měnit a přidávat nové
 *
 * @package Bandsite Manager
 */

if($_SERVER["REQUEST_METHOD"]=="POST"&&!empty($_POST))
{
  if(isset($_POST["new-user"])) {
    if($_POST["password"]==$_POST["passwordagain"]) {
      $u_fullname = mres($_POST["fullname"]);
      $u_username = mres($_POST["username"]);
      $u_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
      $u_member = 0; //Návrh na implementaci propojení users-members

      $sql = "INSERT INTO users(id,fullname,username,password,member) VALUES (NULL,'$u_fullname','$u_username','$u_password','$u_member')";
      if(query($sql)) {
        header("Location: index.php?p=users&changes=user-added");
        die();
      }
      else {
        header("Location: index.php?p=users&changes=failed");
        die();
      }
    }
  }

  if(isset($_POST["change-password"])) {
    if(password_verify($_POST["passwordold"],$_SESSION["user"]["password"])) {
      if($_POST["passwordnew"]==$_POST["passwordagain"]) {
        $newpwd = (string)password_hash($_POST["passwordnew"], PASSWORD_DEFAULT);
        $id = $_SESSION["user"]["id"];
        if(query("UPDATE users SET password = '$newpwd' WHERE id = $id")) {
          header("Location: logout.php?user=".$_SESSION["user"]["username"]);
          die();
        } else {
          header("Location: index.php?p=users&changes=failed");
          die();
        }
      } else {
        header("Location: index.php?p=users&changes=passwords-dont-match");
        die();
      }
    } else {
      header("Location: index.php?p=users&changes=old-password-wrong");
      die();
    }
  }

  if(isset($_POST["delete-account"])) {
    $id = $_SESSION["user"]["id"];
    if(query("DELETE FROM users WHERE id = $id")) {
      header("Location: logout.php");
      die();
    } else {
      header("Location: index.php?p=users&changes=failed");
      die();
    }
  }
} // end if POST

include("index_header.php");

?>

<h1>Users</h1>

<p>You can add new users<?php if (!$su) echo ", change your password or delete your account"; ?>.</p>

<div class="users-actions">
  <a class="new-user"><i class="fa fa-plus"></i> add new user</a>
  <?php if (!$su): ?>
  <a class="change-password"><i class="fa fa-key"></i> change your password</a>
  <a class="delete-account"><i class="fa fa-trash"></i> delete this account</a>
  <?php endif; ?>
</div>

<?php
if($su) {
  $result = query("SELECT id, fullname, username FROM users WHERE id != 1");
  if(mysqli_num_rows($result)>0) {
    echo "<table><thead><tr><th>Fullname</th><th>Username</th><th>Options</th></tr></thead><tbody>";
    while($user = mysqli_fetch_assoc($result)) {
      echo "<tr><td>".$user["fullname"]."</td><td>".$user["username"]."</td><td><a href=\"scripts/reset-password.php?id=".$user["id"]."\"><i class=\"fa fa-key\"></i> Reset password</a> <a href=\"scripts/delete-user.php?id=".$user["id"]."\"><i class=\"fa fa-trash\"></i> Delete user</a></td></tr>";
    }
    echo "</tbody></table>";
  }
}
?>

<div class="modal" id="new-user">
  <div class="modal-body">
    <div class="body">
      <h1>New user</h1>
      <div class="form">
        <form method="post">
          <div class="group">
            <label for="fullname">Fullname</label>
            <input type="text" name="fullname">
          </div>
          <div class="group">
            <label for="username">Username</label>
            <input type="text" name="username">
          </div>
          <div class="group">
            <label for="password">Password</label>
            <input type="password" name="password">
          </div>
          <div class="group">
            <label for="passwordagain">Password again</label>
            <input type="password" name="passwordagain">
          </div>
          <div class="group">
            <input type="submit" name="new-user" value="Create user">
          </div>
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<?php if(!$su): ?>
<div class="modal" id="change-password">
  <div class="modal-body">
    <div class="body">
      <h1>Change password</h1>
      <div class="form">
        <form method="post">
          <div class="group">
            <label for="passwordold">Old password</label>
            <input type="password" name="passwordold">
          </div>
          <div class="group">
            <label for="passwordnew">New Password</label>
            <input type="password" name="passwordnew">
          </div>
          <div class="group">
            <label for="passwordagain">New Password again</label>
            <input type="password" name="passwordagain">
          </div>
          <div class="group">
            <input type="hidden" name="id" value="<?php echo $_SESSION["user"]["id"]; ?>">
            <input type="submit" name="change-password" value="Change password">
          </div>
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<div class="modal" id="delete-account">
  <div class="modal-body">
    <div class="body">
      <h1>Delete account</h1>
      <p>Are you sure you want to delete this account?</p>
      <div class="form">
        <form method="post">
          <input type="hidden" name="id" value="<?php echo $_SESSION["user"]["id"]; ?>">
          <input type="submit" name="delete-account" value="Delete account">
        </form>
      </div>
      <span class="close">Close</span>
    </div>
  </div>
</div>

<?php

  endif;

 include("index_footer.php"); ?>
