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
      <p>Creazione di una nuova utenza Cyb</p>
    </div>

    <?php require_once(TEMPLATE_PATH . '/new-cyb-user.html'); ?>

    <hr>

    <div class="divTable" style="border: 1px solid #000;">
      <div class="divTableBody">
        <div class="divTableRow green-bg">
          <div class="divTableCell">Safe</div>
          <div class="divTableCell">Utente</div>
          <div class="divTableCell">Ticket</div>
        </div>
        <?php
        $utenze_user = array();
        $safe = array();
        $utente = array();

        $utenze_user = json_decode($db->getAllCybUser(), true);

        $file_content = array();
        $fields = array('Safe', 'Utente', 'Ticket');

        $file_content[] = $fields;

        foreach($utenze_user as $utenza) {
          $id = $utenza['id'];
          $safe = json_decode($db->getSafeById($utenza['nome_safe']), true);
          $utente = json_decode($db->getUtenteById(
            $utenza['utenza']), true);
          $ticket = $utenza['ticket'];

          $row = array($safe['nome_safe'], 
            $utente['nome'] . ' ' . $utente['cognome'], $ticket);

          $file_content[] = $row;
          ?>
          <div class="divTableRow">
            <div class="divTableCell"><?php print_r($safe['nome_safe']) ?></div>
            <div class="divTableCell"><?php print_r($utente['nome']) ?>
              <?php print_r($utente['cognome']) ?></div>
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
        $csv_file = "cyb_user_export_".date('Ymd') . ".csv";

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
