<?php
// // Start or resume the session
session_start();

$servername = "localhost";
$username = "mytvglow_virasub";
$password = "VYIhLghDd,m@4NxDmW";
$database = "mytvglow_virasub";


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

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

?>