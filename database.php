<?php
// $host = "localhost"; // Change if using a remote database
// $username = "adignati";
// $password = "Scooter123$$";
// $dbname = "adignati";

// $conn = new mysqli($host, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

class Database {
    public function __construct() {
      die('Init function error');
    }
  
    public static function dbConnect() {
      $mysqli = null;
      //try connecting to your database
  
      require_once("/home/group5-sp25/connect.php");
        if($mysqli == null) {
            try {
                 $mysqli = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME, USERNAME, PASSWORD);
            }
            catch(PDOException $e) {
                 
                 die($e->getMessage());
            }
        }
  
      //catch a potential error, if unable to connect
   
      return $mysqli;
    }
  
    public static function dbDisconnect() {
      $mysqli = null;
    }
  }
?>

