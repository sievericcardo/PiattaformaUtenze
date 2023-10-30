<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Riccardo Sieve">
    <!-- <link rel="icon" href="./img/favicon.ico"> -->

    <title>Piattaforma Utenze</title>

    <!-- Custom bar color for mobile -->
    <meta name="theme-color" content="#00853e">

    <!-- Import the css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css" />
    <link rel="stylesheet" href="<base_url>/piattaforma-utenze/public/css/custom.css" />

    <script
      src="https://code.jquery.com/jquery-3.4.1.min.js"
      crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.0/additional-methods.js"></script>

  </head>

  <body>
    <nav class="navbar ui secondary menu custom-menu" id="navbar">
      <?php if(isset($_SESSION['signed_in']) && $_SESSION['signed_in']) { ?>
      <a class="item" href="<?= SITE_ROOT . '/public/utenza/as'?>">
        Utenza AS
      </a>
      <a class="item" href="<?= SITE_ROOT . '/public/utenza/db'?>">
        Utenza DB
      </a>
      <a class="item" href="<?= SITE_ROOT . '/public/utenza/cyb/as'?>">
        Cyb As
      </a>
      <a class="item" href="<?= SITE_ROOT . '/public/utenza/cyb/db'?>">
        Cyb Db
      </a>
      <a class="item" href="<?= SITE_ROOT . '/public/utenza/cyb/user'?>">
        Cyb Utenti
      </a>

      <?php
        require_once($_SERVER['DOCUMENT_ROOT'] . '/piattaforma-utenze/resources/config.php');
        require_once(LIBRARY_PATH . '/Connection.php');
        require_once(LIBRARY_PATH . '/DbOperations.php');

        $db = new DbOperations();
        $user = array();

        if(isset($_SESSION['signed_in']) && $_SESSION['signed_in']) {
          $user = json_decode($db->getUser($_SESSION['user_name']), true);
        }
      ?>
      <div class="right menu">
        <div class="ui floating labeled icon simple dropdown button">
          <i class="cog icon"></i>
          <img class="ui image" id="profile_settings" src="
            <?php 
                echo(SITE_ROOT . '/public/images/layout/blank-profile-picture.png');
            ?>" width="30" height="30"/>
          <div class="menu">
            <div class="header"><?= $_SESSION['user_name'] ?></div>
            <a class="item">Impostazioni</a>
            <a class="item" href="<?= SITE_ROOT . '/logout' ?>">Logout</a>
          </div>
        </div>
      </div>
      <?php } ?>
    </nav>
    <div id="content-wrapper">
    
    
