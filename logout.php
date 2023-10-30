<?php

ob_start('ob_gzhandler');
# Start the session
session_start();

# Unset the session
session_unset();
session_destroy();

require_once('resources/config.php');
require_once(TEMPLATE_PATH . '/header.php');
require_once(LIBRARY_PATH . '/Connection.php');
require_once(LIBRARY_PATH . '/DbOperations.php');
?>

<div class="message message_dimension">
  <div class="ui loading search">
    <p>Stiamo effettuando il logout. Verrai reinderizzato al termine</p>
  </div>
</div>

<?php
require_once(TEMPLATE_PATH . '/footer.php');
header('Location: <base_url>/piattaforma-utenze/');
die();
?>
