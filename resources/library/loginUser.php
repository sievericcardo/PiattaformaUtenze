<?php

ob_start('ob_gzhandler');
session_start();

require_once('../config.php');
require_once(LIBRARY_PATH . '/Connection.php');
require_once(LIBRARY_PATH . '/DbOperations.php');

$response = array();

header('Content-type: application/json');

if($_SERVER['REQUEST_METHOD'] != 'POST') {
  $response['code'] = 403;
  $response['message'] = 'Tentativo di accesso non valido';

  echo(json_encode($response));
  die();
}

$connection = new Connection();
$conn = $connection->getConnection();

if($conn) {
  $user_name = filter_input(INPUT_POST, 
                           'user_name', 
                           FILTER_SANITIZE_STRING, 
                           array('flags' => FILTER_FLAG_STRIP_LOW));
  $user_pass = password_hash($_POST['user_pass'], PASSWORD_ARGON2ID);

  $sql = 'SELECT username, password 
    FROM utenti_sicurezza
    WHERE username = :username';
  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
  $result = $stmt->execute(array(
    ':username' => $user_name
  ));
  $fetch = $stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($fetch['password']) && 
     password_verify($_POST['user_pass'], $fetch['password'])) {

    $response['code'] = 200;
    $response['message'] = 'Utente loggato';

    $_SESSION['signed_in'] = true;
    $_SESSION['user_name'] = $user_name;

    echo(json_encode($response));
  } else {
    $response['code'] = 404;
    $response['message'] = $fetch['password'];

    echo(json_encode($response));
  }
} else {
  $response['code'] = 500;
  $response['message'] = 'Server error';

  echo(json_encode($response));
}

exit();
?>
