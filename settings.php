<?php
// // Start or resume the session
session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$servername = "localhost";
$username = "virasubc_virasub";
$password = "T.gxMx)md)UO{)D]Id";
$database = "virasubc_virasub";


// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "utility";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}






if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$deposit_fee = 30;
$public_key = "FLWPUBK-918cdc8ecd9975a7f9116c39f6f6fe1f-X";
$secret_key = "FLWSECK-dcc0035b7ffd3869c475e89f2091f21e-18cd9d35a4avt-X";


?>