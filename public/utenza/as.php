<?php # index.php

ob_start('ob_gzhandler');
# Start the session
session_start();
session_regenerate_id();

require_once($_SERVER['DOCUMENT_ROOT'] . '/piattaforma-utenze/resources/config.php');
require_once(TEMPLATE_PATH . '/header.php');
require_once(LIBRARY_PATH . '/Connection.php');
require_once(LIBRARY_PATH . '/DbOperations.php');

$connection = new Connection();
$conn = $connection->getConnection();
$db = new DbOperations();

if(!isset($_SESSION['signed_in']) || !$_SESSION['signed_in'] || empty($_SESSION['user_name'])) {
  header('Location: '.SITE_ROOT);
  die();
}
?>

<main>
    <div class="message message_dimension">
      <p>Creazione di una nuova utenza Server</p>
    </div>

    <?php require_once(TEMPLATE_PATH . '/new-server.html'); ?>

    <hr>

    <div class="divTable" style="border: 1px solid #000;">
      <div class="divTableBody">
        <div class="divTableRow green-bg">
          <div class="divTableCell">Richiedente</div>
          <div class="divTableCell">Utente</div>
          <div class="divTableCell">Server</div>
          <div class="divTableCell">Privilegi</div>
          <div class="divTableCell">Scadenza</div>
        </div>
        <?php
        $utenze_as = array();
        $richiedente = array();
        $utente = array();
        $server = array();

        $utenze_as = json_decode($db->getAllUtenzeAs(), true);

        $file_content = array();
        $fields = array('Richiedente', 'Utente', 'Server', 
          'Privilegi', 'Scadenza');

        $file_content[] = $fields;

        foreach($utenze_as as $utenza) {
          $id = $utenza['id'];
          $richiedente = json_decode($db->getRichiedenteById(
            $utenza['richiedente']), true);
          $utente = json_decode($db->getUtenteById($utenza['utenza']), true);
          $azienda = json_decode($db->getAziendaById($utenza['azienda']), true);
          $scadenza = $utenza['scadenza'];
          $server = json_decode($db->getServerById(
            $utenza['nome_server']), true);
          $privilegi = $utenza['privilegi'];

          $row = array($richiedente['nome'] . ' ' . $richiedente['nome'], 
            $utente['nome'] . ' ' . $utente['cognome'], $server['ip'],
            $privilegi, $scadenza);

          $file_content[] = $row;
          ?>
          <div class="divTableRow">
            <div class="divTableCell"><?php print_r($richiedente['nome']) ?> 
              <?php print_r($richiedente['cognome']) ?></div>
            <div class="divTableCell"><?php print_r($utente['nome']) ?> 
              <?php print_r($utente['cognome']) ?></div>
            <div class="divTableCell"><?php print_r($server['ip']) ?></div>
            <div class="divTableCell"><?= $privilegi ?></div>
            <div class="divTableCell"><?= $scadenza ?></div>
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
