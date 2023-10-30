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
$nome_safe = filter_input(INPUT_POST, 
  'nome_safe', 
  FILTER_SANITIZE_STRING, 
  array('flags' => FILTER_FLAG_STRIP_LOW));
$azienda_utente = filter_input(INPUT_POST, 
  'azienda_utente', 
  FILTER_SANITIZE_STRING, 
  array('flags' => FILTER_FLAG_ALLOW_FRACTION));
$nome_utente = filter_input(INPUT_POST, 
  'nome_utente', 
  FILTER_SANITIZE_STRING, 
  array('flags' => FILTER_FLAG_ALLOW_FRACTION));
$cognome_utente = filter_input(INPUT_POST, 
  'cognome_utente',
  FILTER_SANITIZE_STRING,
  array('flags' => FILTER_FLAG_STRIP_LOW));
$ticket = filter_input(INPUT_POST,
  'ticket',
  FILTER_SANITIZE_STRING, 
  array('flags' => FILTER_FLAG_STRIP_LOW));

$not_null_empty = $nome_safe && $nome_utente && $cognome_utente && $ticket
  && $azienda_utente;

if(!$not_null_empty) {
  $response['code'] = 500;
  $response['message'] = 'Dati mancanti o incorretti';

  echo(json_encode($response));
  exit();
}

if($conn) {
  // Check safe name
  $safe = array();
  $safe = json_decode($db->getSafeByName($nome_safe), true);

  if(!isset($safe['id'])) {
    $safe = json_decode($db->addSafe($nome_safe), true);

    if(!isset($safe['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "safe"';
    }
  }

  // Check azienda
  $azienda = array();
  $azienda = json_decode($db->getAzienda($azienda_utente), true);

  if(!isset($azienda['id'])) {
    $azienda = json_decode($db->addAzienda($azienda_utente), true);

    if(!isset($azienda['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "azienda"';
    }
  }

  // Check user
  $utente = array();
  $utente = json_decode($db->getUtente($nome_utente,
    $cognome_utente), true);

  if(!isset($utente['id'])) {
    $utente = json_decode($db->addUtente($nome_utente,
      $cognome_utente, $azienda['id']), true);

    if(!isset($utente['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "utente"';
    }
  }
  
  // Check utenza_cyb_as getSafeAsByData($nome, $server, $utenza, $ticket)
  $utenza_cyb = array();
  $utenza_cyb = json_decode($db->getSafeUserByData($safe['id'],
    $utente['id'], $ticket), true);

  if(!isset($utenza_cyb['id'])) {
    $utenza_cyb = json_decode($db->addSafeUser($safe['id'],
      $utente['id'], $ticket), true);
    
    if(!isset($utenza_cyb['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "new utenza_cyb"';
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
