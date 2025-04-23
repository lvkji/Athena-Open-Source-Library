<?php
define("USERNAME", "group5-sp25");
define("PASSWORD", "olemi$$2025");
define("DBHOST", "localhost");
define("DBNAME", "group5-sp25");

try {
    $conn = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, USERNAME, PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
