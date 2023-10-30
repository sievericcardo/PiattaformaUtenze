<?php
class Connection {

  private $conn;
  private $host = 'localhost';
  private $dbname = 'piattaforma_utenze';
  private $username = 'your_username';
  private $password = 'your_password';

  public function __construct() {
    # PDO connection
    try {
      $this->conn = new PDO('mysql:host=' . $this->host . ';port=3306;dbname=' . $this->dbname, $this->username, $this->password);

      # TODO: remove when exiting the dev.
      # set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
      # Add error handling
      echo "Connection failed: " . $e->getMessage();
    }
  }

  public function getConnection() {
    return $this->conn;
  }
}
?>
