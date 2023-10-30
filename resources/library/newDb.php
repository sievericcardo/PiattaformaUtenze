<?php

ob_start('ob_gzhandler');
session_start();

require_once('../config.php');
require_once(LIBRARY_PATH . '/Connection.php');
require_once(LIBRARY_PATH . '/DbOperations.php');

$response = array();

header('Content-type: application/json');

// Accept only post request
if($_SERVER['REQUEST_METHOD'] != 'POST') {
  $response['code'] = 403;
  $response['message'] = 'Tentativo di accesso non valido';

  echo(json_encode($response));
  die();
}

$connection = new Connection();
$conn = $connection->getConnection();
$db = new DbOperations();
// $conn = $db->getConn();

// Input sanification
$nome_richiedente = filter_input(INPUT_POST, 
                           'nome_richiedente', 
                           FILTER_SANITIZE_STRING, 
                           array('flags' => FILTER_FLAG_STRIP_LOW));
$cognome_richiedente = filter_input(INPUT_POST, 
                          'cognome_richiedente', 
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$azienda_richiedente = filter_input(INPUT_POST, 
                          'azienda_richiedente',
                          FILTER_SANITIZE_STRING,
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$nome_utente = filter_input(INPUT_POST,
                          'nome_utente',
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$cognome_utente = filter_input(INPUT_POST, 
                          'cognome_utente',
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$azienda_utente = filter_input(INPUT_POST, 
                          'azienda_utente',
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$nome_db = filter_input(INPUT_POST, 
                          'nome_db', 
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$schema = filter_input(INPUT_POST, 
                          'schema', 
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$scadenza = filter_input(INPUT_POST, 
                          'scadenza', 
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$privilegi = filter_input(INPUT_POST, 
                          'privilegi',
                          FILTER_SANITIZE_STRING, 
                          array('flags' => FILTER_FLAG_STRIP_LOW));

$not_null_empty = $nome_richiedente && $cognome_richiedente && 
  $azienda_richiedente && $nome_utente && $cognome_utente &&
  $azienda_utente && $nome_db && $schema && $scadenza && $privilegi;

if($conn && $not_null_empty) {
  // Prima si controlla se l'azienda di richiedente esiste; se non esiste, si 
  // aggiunge e si prende il valore per le relazioni
  $azienda_ric = array();
  $azienda_ric = json_decode($db->getAzienda($azienda_richiedente), true);
  
  if(!isset($azienda_ric['id'])) {
    $azienda_ric = json_decode($db->addAzienda($azienda_richiedente), true);

    if(!isset($azienda_ric['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "azienda richiedente"';
    }
  }

  // Poi si controlla se l'azienda di utente esiste; se non esiste, si aggiunge
  // e si prende il valore per le relazioni
  $azienda_user = array();
  $azienda_user = json_decode($db->getAzienda($azienda_utente), true);
  
  if(!isset($azienda_user['id'])) {
    $azienda_user = json_decode($db->addAzienda($azienda_utente), true);

    if(!isset($azienda_user['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "azienda richiedente"';
    }
  }

  // Check della presenza dell'utente richiedente
  $richiedente = array();
  $richiedente = json_decode($db->getRichiedente($nome_richiedente,
    $cognome_richiedente), true);

  if(!isset($richiedente['id'])) {
    $richiedente = json_decode($db->addRichiedente($nome_richiedente,
      $cognome_richiedente, $azienda_ric['id']), true);

    if(!isset($richiedente['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "richiedente"';
    }
  }

  // Check della presenza dell'utente
  $utente = array();
  $utente = json_decode($db->getUtente($nome_utente,
    $cognome_utente), true);

  if(!isset($utente['id'])) {
    $utente = json_decode($db->addUtente($nome_utente,
      $cognome_utente, $azienda_user['id']), true);

    if(!isset($utente['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "utente"';
    }
  }

  // Check del db
  $newDb = array();
  $newDb = json_decode($db->getDb($nome_db), true);

  if(!isset($newDb['id'])) {
    $newDb = json_decode($db->addDb($nome_db), true);
    
    if(!isset($newDb['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "new db"';
    }
  }

  // Check utenza_db
  $utenza_db = array();
  $utenza_db = json_decode($db->getUtenzaDb($richiedente['id'],
    $utente['id'], $azienda_user['id'], $scadenza, $newDb['id'],
    $schema, $privilegi), true);

  if(!isset($utenza_db['id'])) {
    $utenza_db = json_decode($db->addUtenzaDb($richiedente['id'],
      $utente['id'], $azienda_user['id'], $scadenza, $newDb['id'],
      $schema, $privilegi), true);
    
    if(!isset($utenza_db['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "new utenza_db"';
    }
  }

  $response['code'] = 200;
  $response['message'] = 'Utenza aggiunta';

  echo(json_encode($response));
} else {
  $response['code'] = 500;
  $response['message'] = 'Server error';

  echo(json_encode($response));
}

exit();
?>
