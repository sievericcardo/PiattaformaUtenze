<?php # index.php

ob_start('ob_gzhandler');
# Start the session
session_start();
session_regenerate_id();

require_once($_SERVER['DOCUMENT_ROOT'] . '/piattaforma-utenze/resources/config.php');
require_once(TEMPLATE_PATH . '/header.php');
require_once(LIBRARY_PATH . '/Connection.php');
require_once(LIBRARY_PATH . '/DbOperations.php');

if(!isset($_SESSION['signed_in']) || !$_SESSION['signed_in'] || empty($_SESSION['user_name'])) {
  header('Location: '.SITE_ROOT);
  die();
}
?>

<main>
    <div class="message message_dimension">
      <p>Benvenuto <?= $_SESSION['user_name'] ?>. Puoi creare una nuova <a href="new_recipe.php" >utenza</a> o guardare quelle gi&agrave; esistenti.</p>
    </div>
</main>

<?php
require_once(TEMPLATE_PATH . '/footer.php');
?>
