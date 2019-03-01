<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet/less" media="screen" href="css/admin.less">
  <script src="../js/less.min.js" charset="utf-8"></script>
  <?php
    if($pageName=="gig-detail")
    echo '<link rel="stylesheet" media="print" href="css/gig-print.css">';
  ?>

  <title>Bandsite</title>
</head>
<body>
  <div class="wrapper<?php echo $pageName=="dashboard" ? " dashboard" : ""?>">
    <div class="menu">
      <div class="user">
        <?php
        $tmp = '<img src="%filepath%" alt="User photo">';
        if($su) {
          echo str_replace("%filepath%","img/superuser.png",$tmp);
        } else {
          $filepath = "img/members/".$_SESSION["user"]["member"].".jpg";
          if(file_exists($filepath)){
            echo str_replace("%filepath%",$filepath,$tmp);
          } else {
            echo str_replace("%filepath%","img/user.png",$tmp);
          }
        }
        ?>
        <span class="username"><?php echo $_SESSION["user"]["fullname"];?></span>
        <a href="logout.php" class="logout">Log out</a>
      </div>
      <div class="page-settings">
        <a class="b" href="<?php pageLink("dashboard");?>">Dashboard</a>
        <span class="title">Bandsite manager</span>
        <ul>
          <li><a href="<?php pageLink("browse-tour");?>">Browse tour</a></li>
          <li><a href="<?php pageLink("add-a-gig");?>">Add a gig</a></li>
          <li><a href="<?php pageLink("promoters");?>">Promoters</a></li>
          <li><a href="<?php pageLink("mail") ?>">New mail</a></li>
          <li><a href="<?php pageLink("band-members");?>">Band members</a></li>
          <li><a href="<?php pageLink("band-description");?>">Band description</a></li>
          <li><a href="<?php pageLink("band-milestones");?>">Band Milestones</a></li>
          <li><a href="<?php pageLink("settings");?>">Settings</a></li>
          <li><a href="<?php pageLink("users");?>">Users</a></li>
        </ul>
      </div>
      <div class="page-back">
        <a href="../index.php"><div>Go back to BANDSITE</div></a>
      </div>
    </div>
    <div class="content">
      <?php
        //messageHandler v souboru functions.php
        messageHandler();
      ?>
