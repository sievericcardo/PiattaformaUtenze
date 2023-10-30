<?php # index.php

ob_start('ob_gzhandler');
# Start the session
session_start();
session_regenerate_id();

require_once('resources/config.php');
require_once(TEMPLATE_PATH . '/header.php');

if(isset($_SESSION['signed_in']) && $_SESSION['signed_in'] && !empty($_SESSION['user_name'])) {
  header('Location: '.SITE_ROOT.'/public/');
  die();
} else {
  require_once(TEMPLATE_PATH . '/login-form.html');
}

require_once(TEMPLATE_PATH . '/footer.php');
?>
