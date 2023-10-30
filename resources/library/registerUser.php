<?php

ob_start('ob_gzhandler');

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

$user_name = filter_input(INPUT_POST,
                          'user_name',
                          FILTER_SANITIZE_STRING,
                          array('flags' => FILTER_FLAG_STRIP_LOW));
$user_email = filter_input(INPUT_POST,
                           'user_email',
                           FILTER_SANITIZE_EMAIL,
                           array('flags' => FILTER_FLAG_STRIP_LOW));
$user_pass = password_hash($_POST['user_pass'], PASSWORD_ARGON2ID);
$user_pass_check = password_hash($_POST['user_pass_check'], PASSWORD_ARGON2ID);

$not_null_empty = $user_name && $user_email && $user_pass && $user_pass_check;
$correct_length = strlen($_POST['user_name']) >= 4 &&
                  strlen($_POST['user_email']) >= 8 &&
                  strlen($_POST['user_pass']) >= 8 &&
                  strlen($_POST['user_pass_check']) >= 8;
$password_match = strcmp($_POST['user_pass'], $_POST['user_pass_check'] == 0);

if($conn && $not_null_empty && $correct_length && $password_match) {
  $sql = 'INSERT INTO utenti_sicurezza (username, email, password)
          VALUES (:user, :mail, :pass)';
  
  $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

  $result = $stmt->execute(array(
    ':user' => $user_name,
    ':mail' => $user_email,
    ':pass' => $user_pass
  ));

  if($result) {
    $response['code'] = 200;
    $response['message'] = 'Utente registrato';

    echo(json_encode($response));
    exit();
  } else {
    $response['code'] = 500;
    $response['message'] = 'Impossibile completare la registrazione';

    echo(json_encode($response));
    exit();
  }
} else {
  if(!$not_null_empty) {
    $response['code'] = 300;
    $response['message'] = 'Campi vuoti';

    echo(json_encode($response));
    exit();
  }

  if(!$correct_length) {
    $response['code'] = 300;
    $response['message'] = 'Lunghezza minima non rispettata';

    echo(json_encode($response));
    exit();
  }

  if(!$password_match) {
    $response['code'] = 400;
    $response['message'] = 'Le password non sono uguali';

    echo(json_encode($response));
    exit();
  }
}

exit();

?>
