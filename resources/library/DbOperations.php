<?php
require_once(dirname(__FILE__) . '/../config.php');

class DbOperations extends Connection {

  private $conn;

  public function __construct() {
    parent::__construct();
    $this->conn = parent::getConnection();
  }

  public function getConn() {
    return $this->conn;
  }

  /**
   *  Function to get the username that is logged in (used for info)
   */
  public function getUser($username) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$username) {
      $errors['message'] = 'Username non autenticato';
      return json_encode($errors);
    }

    $user_name = filter_var($username,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($user_name) {
      $sql = ('SELECT *
               FROM utenti_sicurezza
               WHERE username = :username');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':username' => $user_name
      ));

      if(!$result) {
        $errors['message'] = 'Username non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the company information given the name
   */
  public function getAzienda($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome azienda richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));

    if($nome) {
      $sql = ('SELECT *
               FROM azienda
               WHERE nome = :nome');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Azienda non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds the company given the name
   */
  public function addAzienda($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome azienda richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));

    if($nome) {
      $sql = ('INSERT INTO azienda (nome) VALUES (:nome)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di una nuova azienda';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM azienda
               WHERE nome = :nome');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $result = $stmt_get->execute(array(
          ':nome' => $nome
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }
  
  /**
   *  Function to get the richiedente information given the name
   */
  public function getRichiedente($nome, $cognome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome || !$cognome) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $cognome = filter_var($cognome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));
    $cognome = ucfirst(strtolower($cognome));

    if($nome && $cognome) {
      $sql = ('SELECT *
               FROM richiedente
               WHERE nome = :nome
                AND cognome = :cognome');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':cognome' => $cognome
      ));

      if(!$result) {
        $errors['message'] = 'Richiedente non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds the richiedente
   */
  public function addRichiedente($nome, $cognome, $azienda) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome || !$cognome) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $cognome = filter_var($cognome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));
    $cognome = ucfirst(strtolower($cognome));

    if($nome && $cognome) {
      $sql = ('INSERT INTO richiedente (nome, cognome, azienda) 
        VALUES (:nome, :cognome, :azienda)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':cognome' => $cognome,
        ':azienda' => $azienda
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un richiedente';
        return json_encode($errors);
      } else {
        $sql = ('SELECT *
               FROM richiedente
               WHERE nome = :nome
                AND cognome = :cognome');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        
        $result = $stmt_get->execute(array(
          ':nome' => $nome,
          ':cognome' => $cognome
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the richiedente information given the name
   */
  public function getUtente($nome, $cognome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome || !$cognome) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $cognome = filter_var($cognome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));
    $cognome = ucfirst(strtolower($cognome));

    if($nome && $cognome) {
      $sql = ('SELECT *
               FROM utente
               WHERE nome = :nome
                AND cognome = :cognome');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':cognome' => $cognome
      ));

      if(!$result) {
        $errors['message'] = 'Utente non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds the utente
   */
  public function addUtente($nome, $cognome, $azienda) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome || !$cognome) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $cognome = filter_var($cognome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));
    $cognome = ucfirst(strtolower($cognome));

    if($nome && $cognome) {
      $sql = ('INSERT INTO utente (nome, cognome, azienda) 
        VALUES (:nome, :cognome, :azienda)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':cognome' => $cognome,
        ':azienda' => $azienda
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un utente';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM utente
               WHERE nome = :nome
                AND cognome = :cognome');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        
        $result = $stmt_get->execute(array(
          ':nome' => $nome,
          ':cognome' => $cognome
        ));

        // Send back the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the company information given the name
   */
  public function getServer($ip) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$ip) {
      $errors['message'] = 'IP Server richiesto';
      return json_encode($errors);
    }

    $ip = filter_var($ip,
      FILTER_SANITIZE_NUMBER_FLOAT,
      array('flags' => FILTER_FLAG_ALLOW_FRACTION));

    if($ip) {
      $sql = ('SELECT *
               FROM server
               WHERE ip = :ip');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':ip' => $ip
      ));

      if(!$result) {
        $errors['message'] = 'IP Server non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds the company given the name
   */
  public function addServer($ip) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$ip) {
      $errors['message'] = 'IP Server richiesto';
      return json_encode($errors);
    }

    $ip = filter_var($ip,
      FILTER_SANITIZE_NUMBER_FLOAT,
      array('flags' => FILTER_FLAG_ALLOW_FRACTION));

    if($ip) {
      $sql = ('INSERT INTO server (ip) VALUES (:ip)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':ip' => $ip
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo server';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM server
               WHERE ip = :ip');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        
        $result = $stmt_get->execute(array(
          ':ip' => $ip
        ));

        // Send back data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get a specific utenza_as
   */
  public function getUtenzaAs($richiedente, $utente, $azienda_user,
    $scadenza, $server, $privilegi) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$richiedente |  !$utente |  !$azienda_user | !$scadenza | 
      !$server | !$privilegi) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $richiedente = filter_var($richiedente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $azienda_user = filter_var($azienda_user,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $scadenza = filter_var($scadenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $server = filter_var($server,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $privilegi = filter_var($privilegi,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $sql = 'SELECT * FROM utenza_as
    WHERE richiedente = :richiedente
      AND utenza = :utente
      AND azienda = :azienda
      AND scadenza = :scadenza
      AND nome_server = :server
      AND privilegi = :privilegi';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':richiedente' => $richiedente,
      ':utente' => $utente,
      ':azienda' => $azienda_user,
      ':scadenza' => $scadenza,
      ':server' => $server,
      ':privilegi' => $privilegi
    ));

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to get a specific utenza_as given the id
   */
  public function getSingleUtenzaAs($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $sql = 'SELECT * FROM utenza_as
    WHERE id = :id';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':id' => $id
    ));

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to add a new utenza_as
   */
  public function addUtenzaAs($richiedente, $utente, $azienda_user,
    $scadenza, $server, $privilegi) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$richiedente |  !$utente |  !$azienda_user | !$scadenza | 
      !$server | !$privilegi) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $richiedente = filter_var($richiedente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $azienda_user = filter_var($azienda_user,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $scadenza = filter_var($scadenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $server = filter_var($server,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $privilegi = filter_var($privilegi,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $sql = 'INSERT INTO utenza_as (richiedente, utenza, azienda, 
        scadenza, nome_server, privilegi)
      VALUES (:richiedente, :utente, :azienda, :scadenza, :server, :privilegi)';
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':richiedente' => $richiedente,
      ':utente' => $utente,
      ':azienda' => $azienda_user,
      ':scadenza' => $scadenza,
      ':server' => $server,
      ':privilegi' => $privilegi
    ));

    if(!$result) {
      $errors['message'] = 'Errore nella creazione';
      return json_encode($errors);
    } else {
      $sql = 'SELECT * FROM utenza_as
      WHERE richiedente = :richiedente
        AND utenza = :utente
        AND azienda = :azienda
        AND scadenza = :scadenza
        AND nome_server = :server
        AND privilegi = :privilegi';
      
      $stmt_get = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      $result = $stmt_get->execute(array(
        ':richiedente' => $richiedente,
        ':utente' => $utente,
        ':azienda' => $azienda_user,
        ':scadenza' => $scadenza,
        ':server' => $server,
        ':privilegi' => $privilegi
      ));
      // Send back data
      $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to get the db given the name
   */
  public function getDb($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome db richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = strtoupper($nome);

    if($nome) {
      $sql = ('SELECT *
               FROM db
               WHERE nome = :nome');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Db non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds a new db
   */
  public function addDb($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome db richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = strtoupper($nome);

    if($nome) {
      $sql = ('INSERT INTO db (nome) VALUES (:nome)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo server';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM db
               WHERE nome = :nome');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        
        $result = $stmt_get->execute(array(
          ':nome' => $nome
        ));

        // Send back data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get a specific utenza_db
   */
  public function getUtenzaDb($richiedente, $utente, $azienda_user,
    $scadenza, $db, $schema, $privilegi) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$richiedente |  !$utente |  !$azienda_user | !$scadenza | 
      !$db | !$schema | !$privilegi) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $richiedente = filter_var($richiedente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $azienda_user = filter_var($azienda_user,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $scadenza = filter_var($scadenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $db = filter_var($db,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $schema = filter_var($schema,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $privilegi = filter_var($privilegi,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $sql = 'SELECT * FROM utenza_db
    WHERE richiedente = :richiedente
      AND utenza = :utente
      AND azienda = :azienda
      AND scadenza = :scadenza
      AND nome_db = :db
      AND db_schema = :schema
      AND privilegi = :privilegi';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':richiedente' => $richiedente,
      ':utente' => $utente,
      ':azienda' => $azienda_user,
      ':scadenza' => $scadenza,
      ':db' => $db,
      ':schema' => $schema,
      ':privilegi' => $privilegi
    ));

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to get a specific utenza_db given the id
   */
  public function getSingleUtenzaDb($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $sql = 'SELECT * FROM utenza_db
    WHERE id = :id';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':id' => $id
    ));

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to add a new utenza_db
   */
  public function addUtenzaDb($richiedente, $utente, $azienda_user,
    $scadenza, $db, $schema, $privilegi) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$richiedente |  !$utente |  !$azienda_user | !$scadenza | 
      !$db | !$schema | !$privilegi) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $richiedente = filter_var($richiedente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $azienda_user = filter_var($azienda_user,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $scadenza = filter_var($scadenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $db = filter_var($db,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $schema = filter_var($schema,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $privilegi = filter_var($privilegi,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $schema = strtoupper($schema);

    $sql = 'INSERT INTO utenza_db (richiedente, utenza, azienda, 
        scadenza, nome_db, db_schema, privilegi)
      VALUES (:richiedente, :utente, :azienda, 
        :scadenza, :db, :schema, :privilegi)';
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute(array(
      ':richiedente' => $richiedente,
      ':utente' => $utente,
      ':azienda' => $azienda_user,
      ':scadenza' => $scadenza,
      ':db' => $db,
      ':schema' => $schema,
      ':privilegi' => $privilegi
    ));

    if(!$result) {
      $errors['message'] = 'Errore nella creazione';
      return json_encode($errors);
    } else {
      $sql = 'SELECT * FROM utenza_db
      WHERE richiedente = :richiedente
        AND utenza = :utente
        AND azienda = :azienda
        AND scadenza = :scadenza
        AND nome_db = :db
        AND db_schema = :schema
        AND privilegi = :privilegi';
      
      $stmt_get = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      $result = $stmt_get->execute(array(
        ':richiedente' => $richiedente,
        ':utente' => $utente,
        ':azienda' => $azienda_user,
        ':scadenza' => $scadenza,
        ':db' => $db,
        ':schema' => $schema,
        ':privilegi' => $privilegi
      ));
      // Send back data
      $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  /**
   *  Function to get the safe name
   */
  public function getSafeByName($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome safe richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = strtoupper($nome);

    if($nome) {
      $sql = ('SELECT *
               FROM safe_cyb
               WHERE nome_safe = :nome');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Safe non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds a new safe
   */
  public function addSafe($nome) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome) {
      $errors['message'] = 'Nome safe richiesto';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $nome = ucfirst(strtolower($nome));

    $nome = strtoupper($nome);

    if($nome) {
      $sql = ('INSERT INTO safe_cyb (nome_safe) VALUES (:nome)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo safe';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM safe_cyb
               WHERE nome_safe = :nome');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $result = $stmt_get->execute(array(
          ':nome' => $nome
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the safe user information by data
   */
  public function getSafeUserByData($nome, $utente, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$utente | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($nome && $utente && $ticket) {
      $sql = ('SELECT *
        FROM utente_cyb
        WHERE nome_safe = :nome
          AND utenza = :utente
          AND ticket = :ticket');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':utente' => $utente,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Safe non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds a new safe user
   */
  public function addSafeUser($nome, $utente, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$utente | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
    FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utente = filter_var($utente,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $ticket = strtoupper($ticket);

    if($nome && $utente && $ticket) {
      $sql = ('INSERT INTO utente_cyb (nome_safe, utenza, ticket) 
        VALUES (:nome, :utente, :ticket)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':utente' => $utente,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo safe user';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
               FROM utente_cyb
               WHERE nome_safe = :nome
                AND utenza = :utente
                AND ticket = :ticket');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $result = $stmt_get->execute(array(
          ':nome' => $nome,
          ':utente' => $utente,
          ':ticket' => $ticket
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the safe as information by data
   */
  public function getSafeAsByData($nome, $server, $utenza, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$server | !$utenza | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $server = filter_var($server,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utenza = filter_var($utenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $utenza = strtoupper($utenza);
    $ticket = strtoupper($ticket);

    if($nome && $server && $utenza && $ticket) {
      $sql = ('SELECT *
        FROM as_cyb
        WHERE nome_safe = :nome
          AND nome_server = :server
          AND nome_utenza = :utenza
          AND ticket = :ticket');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':server' => $server,
        ':utenza' => $utenza,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Safe non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds a new safe as
   */
  public function addSafeAs($nome, $server, $utenza, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$server | !$utenza | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $server = filter_var($server,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utenza = filter_var($utenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $utenza = strtoupper($utenza);
    $ticket = strtoupper($ticket);

    if($nome && $server && $utenza && $ticket) {
      $sql = ('INSERT INTO as_cyb (nome_safe, nome_server, 
          nome_utenza, ticket) 
        VALUES (:nome, :server, :utenza, :ticket)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':server' => $server,
        ':utenza' => $utenza,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo safe as';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
          FROM as_cyb
          WHERE nome_safe = :nome
            AND nome_server = :server
            AND nome_utenza = :utenza
            AND ticket = :ticket');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $result = $stmt_get->execute(array(
          ':nome' => $nome,
          ':server' => $server,
          ':utenza' => $utenza,
          ':ticket' => $ticket
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get the safe db information by data
   */
  public function getSafeDbByData($nome, $db, $utenza, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$db | !$utenza | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $db = filter_var($db,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utenza = filter_var($utenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $utenza = strtoupper($utenza);
    $ticket = strtoupper($ticket);

    if($nome && $db && $utenza && $ticket) {
      $sql = ('SELECT *
        FROM db_cyb
        WHERE nome_safe = :nome
          AND nome_db = :db
          AND nome_utenza = :utenza
          AND ticket = :ticket');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':db' => $db,
        ':utenza' => $utenza,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Safe non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   *  Function to get that adds a new safe db
   */
  public function addSafeDb($nome, $db, $utenza, $ticket) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$nome | !$db | !$utenza | !$ticket) {
      $errors['message'] = 'Dati mancanti';
      return json_encode($errors);
    }

    $nome = filter_var($nome,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $db = filter_var($db,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $utenza = filter_var($utenza,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));
    $ticket = filter_var($ticket,
      FILTER_SANITIZE_STRING,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    $utenza = strtoupper($utenza);
    $ticket = strtoupper($ticket);

    if($nome && $db && $utenza && $ticket) {
      $sql = ('INSERT INTO db_cyb (nome_safe, nome_db, 
          nome_utenza, ticket) 
        VALUES (:nome, :db, :utenza, :ticket)');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':nome' => $nome,
        ':db' => $db,
        ':utenza' => $utenza,
        ':ticket' => $ticket
      ));

      if(!$result) {
        $errors['message'] = 'Errore nella creazione di un nuovo safe as';
        return json_encode($errors);
      } else {
        // Retrieve the data
        $sql = ('SELECT *
          FROM db_cyb
          WHERE nome_safe = :nome
            AND nome_db = :db
            AND nome_utenza = :utenza
            AND ticket = :ticket');
        $stmt_get = $this->conn->prepare($sql, array(
          PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
        ));
        $result = $stmt_get->execute(array(
          ':nome' => $nome,
          ':db' => $db,
          ':utenza' => $utenza,
          ':ticket' => $ticket
        ));

        // Return the data
        $fetch = $stmt_get->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  /**
   * Start all getters for aggregate data
   */
  public function getAllAziende() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM azienda';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Azienda non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getAziendaById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM azienda
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Azienda non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllRichiedenti() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM richiedente';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getRichiedenteById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM richiedente
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Richiedente non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllUtenti() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM utente';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Utenza non presente';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getUtenteById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Informazioni mancanti';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM utente
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Utente non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllServer() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM server';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Server non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getServerById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM server
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Server non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllDb() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM server';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Server non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getDbById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM db
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Db non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllUtenzeAs() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM utenza_as';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Utenze AS non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      // $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getUtenzaAsById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM utenza_as
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Utenza AS non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllUtenzeDb() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM utenza_db';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Utenze DB non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getUtenzaDbById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM utenza_db
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Utenza DB non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllSafe() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM safe_cyb';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'Safe non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getSafeById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM safe_cyb
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'Safe non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllCybAs() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM as_cyb';
    
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'AS Cyb non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getCybAsById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM as_cyb
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'AS Cyb non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllCybDb() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM db_cyb';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'DB Cyb non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll();
      return json_encode($fetch);
    }
  }

  public function getCybDbById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM db_cyb
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'DB Cyb non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }

  public function getAllCybUser() {
    $errors = array();
    $fetch = array(); // we'll use this for return

    $sql = 'SELECT * FROM utente_cyb';
      
    $stmt = $this->conn->prepare($sql, array(
      PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
    ));
    $result = $stmt->execute();

    if(!$result) {
      $errors['message'] = 'DB Cyb non presenti';
      return json_encode($errors);
    } else {
      // Send back data
      $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($fetch);
    }
  }

  public function getCybUserById($id) {
    $errors = array();
    $fetch = array(); // we'll use this for return

    if(!$id) {
      $errors['message'] = 'Id richiesto';
      return json_encode($errors);
    }

    $id = filter_var($id,
      FILTER_SANITIZE_NUMBER_INT,
      array('flags' => FILTER_FLAG_STRIP_LOW));

    if($id) {
      $sql = ('SELECT *
               FROM utente_cyb
               WHERE id = :id');
      $stmt = $this->conn->prepare($sql, array(
        PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
      ));
      
      $result = $stmt->execute(array(
        ':id' => $id
      ));

      if(!$result) {
        $errors['message'] = 'DB Cyb non presente';
        return json_encode($errors);
      } else {
        $fetch = $stmt->fetch(PDO::FETCH_ASSOC);
        return json_encode($fetch);
      }
    }
  }
}
?>
