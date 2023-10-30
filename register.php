<?php

ob_start('ob_gzhandler');
# Start the session
session_start();

require_once('resources/config.php');
require_once(TEMPLATE_PATH . '/header.php');

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in']) {
  header('Location: '.$config['urls']['baseUrl'].'/public/');
  die();
}

require_once(TEMPLATE_PATH . '/register-form.html');

require_once(TEMPLATE_PATH . '/footer.php');
?>
