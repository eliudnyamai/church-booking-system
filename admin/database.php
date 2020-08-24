<?php
require_once '../vendor/autoload.php';
use SecureEnvPHP\SecureEnvPHP;
(new SecureEnvPHP())->parse('../.env.enc', '../.env.key');
Class Connection {
private  $server;
private  $user;
private  $pass;
private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
protected $con;
function __construct() {
  $this->server = getenv('SERVER');
  $this->user = getenv('DB_USER');
  $this->pass = getenv('DB_PASS');
}
 
            public function openConnection()
             {
               try
                 {
          $this->con =  new PDO($this->server, $this->user,$this->pass,$this->options);
          return $this->con;
                  }
               catch (PDOException $e)
                 {
                     echo "There is some problem in connection: " . $e->getMessage();
                 }
             }
public function closeConnection() {
     $this->con = null;
  }
}
?>