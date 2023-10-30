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
$nome_server = filter_input(INPUT_POST, 
  'nome_server', 
  FILTER_SANITIZE_NUMBER_FLOAT, 
  array('flags' => FILTER_FLAG_ALLOW_FRACTION));
$nome_utenza = filter_input(INPUT_POST, 
  'nome_utenza',
  FILTER_SANITIZE_STRING,
  array('flags' => FILTER_FLAG_STRIP_LOW));
$ticket = filter_input(INPUT_POST,
  'ticket',
  FILTER_SANITIZE_STRING, 
  array('flags' => FILTER_FLAG_STRIP_LOW));

$not_null_empty = $nome_safe && $nome_server && $nome_utenza && $ticket;

$correct_length = strlen($_POST['nome_server']) >= 7 &&
  strlen($_POST['nome_server']) <= 15;

if(!$not_null_empty) {
  $response['code'] = 500;
  $response['message'] = 'Dati mancanti o incorretti';

  echo(json_encode($response));
  exit();
}

if (!$correct_length) {
  $response['code'] = 500;
  $response['message'] = 'Server IP non consono';

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
      $response['message'] = 'Server error for "azienda richiedente"';
    }
  }

  // Check del server
  $server = array();
  $server = json_decode($db->getServer($nome_server), true);

  if(!isset($server['id'])) {
    $server = json_decode($db->addServer($nome_server), true);
    
    if(!isset($server['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "new server"';
    }
  }

  // Check utenza_cyb_as getSafeAsByData($nome, $server, $utenza, $ticket)
  $utenza_as = array();
  $utenza_as = json_decode($db->getSafeAsByData($safe['id'],
    $server['id'], $nome_utenza, $ticket), true);

  if(!isset($utenza_as['id'])) {
    $utenza_as = json_decode($db->addSafeAs($safe['id'],
      $server['id'], $nome_utenza, $ticket), true);
    
    if(!isset($utenza_as['id'])) {
      $response['code'] = 500;
      $response['message'] = 'Server error for "new utenza_as"';
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
