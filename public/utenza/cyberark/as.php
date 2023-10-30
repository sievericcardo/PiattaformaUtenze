<?php # index.php

ob_start('ob_gzhandler');
// ob_start();
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
      <p>Creazione di una nuova utenza Application Server Cyb</p>
    </div>

    <?php require_once(TEMPLATE_PATH . '/new-cyb-as.html'); ?>

    <hr>

    <div class="divTable" style="border: 1px solid #000;">
      <div class="divTableBody">
        <div class="divTableRow green-bg">
          <div class="divTableCell">Safe</div>
          <div class="divTableCell">Server</div>
          <div class="divTableCell">Utenza</div>
          <div class="divTableCell">Ticket</div>
        </div>
        <?php
        $utenze_as = array();
        $safe = array();
        $server = array();

        $utenze_as = json_decode($db->getAllCybAs(), true);

        $file_content = array();
        $fields = array('Safe', 'Server', 'Utenza', 'Ticket');

        $file_content[] = $fields;

        foreach($utenze_as as $utenza) {
          $id = $utenza['id'];
          $safe = json_decode($db->getSafeById($utenza['nome_safe']), true);
          $server = json_decode($db->getServerById(
            $utenza['nome_server']), true);
          $nome_utenza = $utenza['nome_utenza'];
          $ticket = $utenza['ticket'];

          $row = array($safe['nome_safe'], $server['ip'], 
            $nome_utenza, $ticket);

          // $file_content = array_push($file_content, $row);
          $file_content[] = $row;
          ?>
          <div class="divTableRow">
            <div class="divTableCell"><?php print_r($safe['nome_safe']) ?></div>
            <div class="divTableCell"><?php print_r($server['ip']) ?></div>
            <div class="divTableCell"><?= $nome_utenza ?></div>
            <div class="divTableCell"><?= $ticket ?></div>
          </div>
          <?php
        }
        ?>
      </div>
    </div>
    <!-- DivTable.com -->
    <form method="post" class="center-block">
      <input type="submit" name="download_csv" class="ui primary button" value="Download CSV" />
    </form>

    <?php
      if(array_key_exists('download_csv', $_POST)) {
        ob_end_clean();
        ob_start();
        $csv_file = "cyb_as_export_".date('Ymd') . ".csv";

        // Header file content
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$csv_file\"");

        // File
        $fh = fopen( 'php://output', 'rw+' );

        $is_coloumn = true;
        if(!empty($file_content)) {
          foreach($file_content as $record) {
            if($is_coloumn) {
              fputcsv($fh, array_keys($record));
              $is_coloumn = false;
            }

            fputcsv($fh, array_values($record));
          }

          fclose($fh);
        }

        exit();

        ob_end_clean();
        ob_start('ob_gzhandler');
      }
    ?>
</main>

<?php
require_once(TEMPLATE_PATH . '/footer.php');
?>
