<?php
// // Start or resume the session
session_start();

if (isset($_SESSION['email'])) {

} else {
  // User is not logged in, redirect to the login page
  header('Location: auth/index.php');

  exit();
}

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



$user_email = $_SESSION['email'];


$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :userEmail");
$stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  $bal = $row['balance'];
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $phone_number = $row['phone'];
  $address = $row['address'];
  $state = $row['state'];
  $type = $row['type'];
  $status = $row['status'];
  $user_name = $row['first_name'] . ' ' . $row['last_name'];
  $_SESSION['user_balance'] = $balance;
  $public_key = 'FLWPUBK_TEST-12ebb25efcc7b39ddffd59f6ef057a68-X';

  if (is_null($bal)) {
    $balance = "0.00";
  } else {
    $balance = $row['balance'];
  }

  if ($user_email == "muhammadbash24727@gmail.com" || $user_email == "muhammadbash24727@gmail.com") {


    $plansData = array(
      [1, '1', 'SME', '1.0GB', '₦275.00', '1 Month'],
      [2, '1', 'SME', '3.0GB', '₦790.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦530.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,320.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,620.00', '1 Month'],
      [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [7, '1', 'COOPERATE GIFTING', '500MB', '₦155.00', '1 Month'],
      [8, '1', 'COOPERATE GIFTING', '1.0GB', '₦285.00', '1 Month'],
      [9, '1', 'COOPERATE GIFTING', '2.0GB', '₦650.00', '1 Month'],
      [10, '1', 'COOPERATE GIFTING', '3.0GB', '₦850.00', '1 Month'],
      [11, '1', 'COOPERATE GIFTING', '5.0GB', '₦1,600.00', '1 Month'],
      [12, '1', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦120.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦220.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦430.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [19, '3', 'GIFTING', '1.05GB', '₦500.00', '14 Days'],
      [20, '3', 'GIFTING', '3.90GB', '₦1200.00', '1 Month'],
      [21, '3', 'GIFTING', '5.8GB', '₦2100.00', '1 Month'],
      [22, '3', 'GIFTING', '4.10GB', '₦1,590.00', '1 Month'],
      [23, '4', 'GIFTING', '1.5GB', '₦1040.00', '1 Month'],
      [24, '4', 'GIFTING', '2.0GB', '₦1,220.00', '1 Month'],
      [25, '4', 'GIFTING', '3.0GB', '₦1,500.00', '1 Month'],
      [26, '4', 'GIFTING', '500MB', '₦500.00', '14 Days'],
      [27, '4', 'GIFTING', '4.50GB', '₦1,880.00', '1 Month'],
      [31, '3', 'GIFTING', '7.70GB', '₦2,520.00', '1 Month'],
      [32, '3', 'GIFTING', '10.0GB', '₦3000.00', '1 Month'],
      [33, '3', 'GIFTING', '13.25GB', '₦4000.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦150.00', '1 Month'],
      [37, '2', 'COOPERATE GIFTING', '100MB', '₦120.00', '1 Month'],
      [38, '3', 'GIFTING', '18.25GB', '₦5,250.00', '1 Month'],
      [39, '3', 'GIFTING', '29.5GB', '₦8500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦13000.00', '1 Month'],
      [41, '4', 'GIFTING', '11.0GB', '₦4,030.00', '1 Month'],
      [42, '4', 'GIFTING', '15.0GB', '₦5200.00', '1 Month'],
      [43, '4', 'GIFTING', '40.0GB', '₦11500.00', '1 Month'],
      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦135.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦480.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦700.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,160.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,320.00', '30 days'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3850.00', '30 days'],
      [53, '2', 'COOPERATE GIFTING', '20.0GB', '₦5000.00', '30 days'],
      [54, '2', 'GIFTING', '750MB', '₦500.00', '14 Days'],
      [55, '2', 'GIFTING', '1.5GB', '₦1,030.00', '30 days'],
      [56, '2', 'GIFTING', '2.0GB', '₦1,250.00', '30 days'],
      [57, '2', 'GIFTING', '3.0GB', '₦1,515.00', '30 days'],
      [58, '2', 'GIFTING', '4.5GB', '₦2100.00', '30 days'],
      [59, '1', 'GIFTING', '6.0GB', '₦2,600.00', '30 days'],
      [60, '1', 'GIFTING', '10.0GB', '₦3000.00', '30 days'],
      [61, '2', 'GIFTING', '11.0GB', '₦4000.00', '30 days'],
      [62, '2', 'GIFTING', '20.0GB', '₦5000.00', '30 days'],
      [63, '4', 'SME', '500MB', '₦150.00', '14 days'],
      [64, '4', 'SME', '1.0GB', '₦220.00', '30 days'],
      [65, '4', 'SME', '2.0GB', '₦450.00', '30 days'],
      [66, '4', 'SME', '3.0GB', '₦670.00', '30 days'],
      [67, '4', 'SME', '5.0GB', '₦1050.00', '30 days'],
      [68, '4', 'SME', '10.0GB', '₦2,300.00', '30 days'],
      [69, '4', 'COOPERATE GIFTING', '500MB', '₦150.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦750.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,200.00', '30 days'],
      [74, '4', 'COOPERATE GIFTING', '10.0GB', '₦2,500.00', '30 days'],
      [83, '1', 'GIFTING', '1.0GB', '₦255.00', '7 days'],
      [85, '1', 'GIFTING', '3.0GB', '₦750.00', '1 Month'],
    );
    ?>


    <script>
      plansData = [
        [1, '1', 'SME', '1.0GB', '₦275.00', '1 Month'],
        [2, '1', 'SME', '3.0GB', '₦790.00', '1 Month'],
        [3, '1', 'SME', '2.0GB', '₦530.00', '1 Month'],
        [4, '1', 'SME', '5.0GB', '₦1,320.00', '1 Month'],
        [5, '1', 'SME', '10.0GB', '₦2,620.00', '1 Month'],
        [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
        [7, '1', 'COOPERATE GIFTING', '500MB', '₦155.00', '1 Month'],
        [8, '1', 'COOPERATE GIFTING', '1.0GB', '₦285.00', '1 Month'],
        [9, '1', 'COOPERATE GIFTING', '2.0GB', '₦650.00', '1 Month'],
        [10, '1', 'COOPERATE GIFTING', '3.0GB', '₦850.00', '1 Month'],
        [11, '1', 'COOPERATE GIFTING', '5.0GB', '₦1,600.00', '1 Month'],
        [12, '1', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '1 Month'],
        [13, '2', 'COOPERATE GIFTING', '500MB', '₦120.00', '1 Month'],
        [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦220.00', '1 Month'],
        [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦430.00', '1 Month'],
        [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
        [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
        [19, '3', 'GIFTING', '1.05GB', '₦500.00', '14 Days'],
        [20, '3', 'GIFTING', '3.90GB', '₦1200.00', '1 Month'],
        [21, '3', 'GIFTING', '5.8GB', '₦2100.00', '1 Month'],
        [22, '3', 'GIFTING', '4.10GB', '₦1,590.00', '1 Month'],
        [23, '4', 'GIFTING', '1.5GB', '₦1040.00', '1 Month'],
        [24, '4', 'GIFTING', '2.0GB', '₦1,220.00', '1 Month'],
        [25, '4', 'GIFTING', '3.0GB', '₦1,500.00', '1 Month'],
        [26, '4', 'GIFTING', '500MB', '₦500.00', '14 Days'],
        [27, '4', 'GIFTING', '4.50GB', '₦1,880.00', '1 Month'],
        [31, '3', 'GIFTING', '7.70GB', '₦2,520.00', '1 Month'],
        [32, '3', 'GIFTING', '10.0GB', '₦3000.00', '1 Month'],
        [33, '3', 'GIFTING', '13.25GB', '₦4000.00', '1 Month'],
        [36, '2', 'COOPERATE GIFTING', '300MB', '₦150.00', '1 Month'],
        [37, '2', 'COOPERATE GIFTING', '100MB', '₦120.00', '1 Month'],
        [38, '3', 'GIFTING', '18.25GB', '₦5,250.00', '1 Month'],
        [39, '3', 'GIFTING', '29.5GB', '₦8500.00', '1 Month'],
        [40, '3', 'GIFTING', '50.0GB', '₦13000.00', '1 Month'],
        [41, '4', 'GIFTING', '11.0GB', '₦4,030.00', '1 Month'],
        [42, '4', 'GIFTING', '15.0GB', '₦5200.00', '1 Month'],
        [43, '4', 'GIFTING', '40.0GB', '₦11500.00', '1 Month'],
        [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
        [45, '3', 'COOPERATE GIFTING', '500MB', '₦135.00', '30 days'],
        [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '30 days'],
        [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦480.00', '30 days'],
        [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦700.00', '30 days'],
        [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,160.00', '30 days'],
        [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,320.00', '30 days'],
        [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3850.00', '30 days'],
        [53, '2', 'COOPERATE GIFTING', '20.0GB', '₦5000.00', '30 days'],
        [54, '2', 'GIFTING', '750MB', '₦500.00', '14 Days'],
        [55, '2', 'GIFTING', '1.5GB', '₦1,030.00', '30 days'],
        [56, '2', 'GIFTING', '2.0GB', '₦1,250.00', '30 days'],
        [57, '2', 'GIFTING', '3.0GB', '₦1,515.00', '30 days'],
        [58, '2', 'GIFTING', '4.5GB', '₦2100.00', '30 days'],
        [59, '1', 'GIFTING', '6.0GB', '₦2,600.00', '30 days'],
        [60, '1', 'GIFTING', '10.0GB', '₦3000.00', '30 days'],
        [61, '2', 'GIFTING', '11.0GB', '₦4000.00', '30 days'],
        [62, '2', 'GIFTING', '20.0GB', '₦5000.00', '30 days'],
        [63, '4', 'SME', '500MB', '₦150.00', '14 days'],
        [64, '4', 'SME', '1.0GB', '₦220.00', '30 days'],
        [65, '4', 'SME', '2.0GB', '₦450.00', '30 days'],
        [66, '4', 'SME', '3.0GB', '₦670.00', '30 days'],
        [67, '4', 'SME', '5.0GB', '₦1050.00', '30 days'],
        [68, '4', 'SME', '10.0GB', '₦2,300.00', '30 days'],
        [69, '4', 'COOPERATE GIFTING', '500MB', '₦150.00', '14 Days'],
        [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
        [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '30 days'],
        [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦750.00', '30 days'],
        [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,200.00', '30 days'],
        [74, '4', 'COOPERATE GIFTING', '10.0GB', '₦2,500.00', '30 days'],
        [83, '1', 'GIFTING', '1.0GB', '₦255.00', '7 days'],
        [85, '1', 'GIFTING', '3.0GB', '₦750.00', '1 Month'],
      ];
    </script>

    <?php
  } else {

    $plansData = array(
      [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
      [2, '1', 'SME', '3.0GB', '₦850.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
      [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [7, '1', 'COOPERATE GIFTING', '500MB', '₦155.00', '1 Month'],
      [8, '1', 'COOPERATE GIFTING', '1.0GB', '₦285.00', '1 Month'],
      [9, '1', 'COOPERATE GIFTING', '2.0GB', '₦650.00', '1 Month'],
      [10, '1', 'COOPERATE GIFTING', '3.0GB', '₦850.00', '1 Month'],
      [11, '1', 'COOPERATE GIFTING', '5.0GB', '₦1,600.00', '1 Month'],
      [12, '1', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦140.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦220.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦430.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [19, '3', 'GIFTING', '1.05GB', '₦500.00', '14 Days'],
      [20, '3', 'GIFTING', '3.90GB', '₦1,200.00', '1 Month'],
      [21, '3', 'GIFTING', '5.8GB', '₦2,100.00', '1 Month'],
      [22, '3', 'GIFTING', '4.10GB', '₦1,590.00', '1 Month'],
      [23, '4', 'GIFTING', '1.5GB', '₦1,040.00', '1 Month'],
      [24, '4', 'GIFTING', '2.0GB', '₦1,220.00', '1 Month'],
      [25, '4', 'GIFTING', '3.0GB', '₦1,500.00', '1 Month'],
      [26, '4', 'GIFTING', '500MB', '₦500.00', '14 Days'],
      [27, '4', 'GIFTING', '4.50GB', '₦1,880.00', '1 Month'],
      [31, '3', 'GIFTING', '7.70GB', '₦2,520.00', '1 Month'],
      [32, '3', 'GIFTING', '10.0GB', '₦3,000.00', '1 Month'],
      [33, '3', 'GIFTING', '13.25GB', '₦4000.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦150.00', '1 Month'],
      [37, '2', 'COOPERATE GIFTING', '100MB', '₦120.00', '1 Month'],
      [38, '3', 'GIFTING', '18.25GB', '₦5,250.00', '1 Month'],
      [39, '3', 'GIFTING', '29.5GB', '₦8500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦13,000.00', '1 Month'],
      [41, '4', 'GIFTING', '11.0GB', '₦4,030.00', '1 Month'],
      [42, '4', 'GIFTING', '15.0GB', '₦5,200.00', '1 Month'],
      [43, '4', 'GIFTING', '40.0GB', '₦11,500.00', '1 Month'],
      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦135.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦480.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦700.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,160.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,320.00', '30 days'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],
      [53, '2', 'COOPERATE GIFTING', '20.0GB', '₦5,000.00', '30 days'],
      [54, '2', 'GIFTING', '750MB', '₦500.00', '14 Days'],
      [55, '2', 'GIFTING', '1.5GB', '₦1,030.00', '30 days'],
      [56, '2', 'GIFTING', '2.0GB', '₦1,250.00', '30 days'],
      [57, '2', 'GIFTING', '3.0GB', '₦1,515.00', '30 days'],
      [58, '2', 'GIFTING', '4.5GB', '₦2,100.00', '30 days'],
      [59, '1', 'GIFTING', '6.0GB', '₦2,400.00', '30 days'],
      [60, '1', 'GIFTING', '10.0GB', '₦2,880.00', '30 days'],
      [61, '2', 'GIFTING', '11.0GB', '₦4,000.00', '30 days'],
      [62, '2', 'GIFTING', '20.0GB', '₦5,000.00', '30 days'],
      [63, '4', 'SME', '500MB', '₦150.00', '14 days'],
      [64, '4', 'SME', '1.0GB', '₦220.00', '30 days'],
      [65, '4', 'SME', '2.0GB', '₦450.00', '30 days'],
      [66, '4', 'SME', '3.0GB', '₦670.00', '30 days'],
      [67, '4', 'SME', '5.0GB', '₦1,050.00', '30 days'],
      [68, '4', 'SME', '10.0GB', '₦2,300.00', '30 days'],
      [69, '4', 'COOPERATE GIFTING', '500MB', '₦150.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦750.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,200.00', '30 days'],
      [74, '4', 'COOPERATE GIFTING', '10.0GB', '₦2,500.00', '30 days'],
      [83, '1', 'GIFTING', '1.0GB', '₦275.00', '7 days'],
      [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
    );
    ?>
    <script>
      plansData = [
        [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
        [2, '1', 'SME', '3.0GB', '₦850.00', '1 Month'],
        [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
        [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
        [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
        [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
        [7, '1', 'COOPERATE GIFTING', '500MB', '₦155.00', '1 Month'],
        [8, '1', 'COOPERATE GIFTING', '1.0GB', '₦285.00', '1 Month'],
        [9, '1', 'COOPERATE GIFTING', '2.0GB', '₦650.00', '1 Month'],
        [10, '1', 'COOPERATE GIFTING', '3.0GB', '₦850.00', '1 Month'],
        [11, '1', 'COOPERATE GIFTING', '5.0GB', '₦1,600.00', '1 Month'],
        [12, '1', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '1 Month'],
        [13, '2', 'COOPERATE GIFTING', '500MB', '₦140.00', '1 Month'],
        [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦220.00', '1 Month'],
        [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦430.00', '1 Month'],
        [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
        [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
        [19, '3', 'GIFTING', '1.05GB', '₦500.00', '14 Days'],
        [20, '3', 'GIFTING', '3.90GB', '₦1,200.00', '1 Month'],
        [21, '3', 'GIFTING', '5.8GB', '₦2,100.00', '1 Month'],
        [22, '3', 'GIFTING', '4.10GB', '₦1,590.00', '1 Month'],
        [23, '4', 'GIFTING', '1.5GB', '₦1,040.00', '1 Month'],
        [24, '4', 'GIFTING', '2.0GB', '₦1,220.00', '1 Month'],
        [25, '4', 'GIFTING', '3.0GB', '₦1,500.00', '1 Month'],
        [26, '4', 'GIFTING', '500MB', '₦500.00', '14 Days'],
        [27, '4', 'GIFTING', '4.50GB', '₦1,880.00', '1 Month'],
        [31, '3', 'GIFTING', '7.70GB', '₦2,520.00', '1 Month'],
        [32, '3', 'GIFTING', '10.0GB', '₦3,000.00', '1 Month'],
        [33, '3', 'GIFTING', '13.25GB', '₦4000.00', '1 Month'],
        [36, '2', 'COOPERATE GIFTING', '300MB', '₦150.00', '1 Month'],
        [37, '2', 'COOPERATE GIFTING', '100MB', '₦120.00', '1 Month'],
        [38, '3', 'GIFTING', '18.25GB', '₦5,250.00', '1 Month'],
        [39, '3', 'GIFTING', '29.5GB', '₦8500.00', '1 Month'],
        [40, '3', 'GIFTING', '50.0GB', '₦13,000.00', '1 Month'],
        [41, '4', 'GIFTING', '11.0GB', '₦4,030.00', '1 Month'],
        [42, '4', 'GIFTING', '15.0GB', '₦5,200.00', '1 Month'],
        [43, '4', 'GIFTING', '40.0GB', '₦11,500.00', '1 Month'],
        [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
        [45, '3', 'COOPERATE GIFTING', '500MB', '₦135.00', '30 days'],
        [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '30 days'],
        [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦480.00', '30 days'],
        [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦700.00', '30 days'],
        [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,160.00', '30 days'],
        [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,320.00', '30 days'],
        [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],
        [53, '2', 'COOPERATE GIFTING', '20.0GB', '₦5,000.00', '30 days'],
        [54, '2', 'GIFTING', '750MB', '₦500.00', '14 Days'],
        [55, '2', 'GIFTING', '1.5GB', '₦1,030.00', '30 days'],
        [56, '2', 'GIFTING', '2.0GB', '₦1,250.00', '30 days'],
        [57, '2', 'GIFTING', '3.0GB', '₦1,515.00', '30 days'],
        [58, '2', 'GIFTING', '4.5GB', '₦2,100.00', '30 days'],
        [59, '1', 'GIFTING', '6.0GB', '₦2,400.00', '30 days'],
        [60, '1', 'GIFTING', '10.0GB', '₦2,880.00', '30 days'],
        [61, '2', 'GIFTING', '11.0GB', '₦4,000.00', '30 days'],
        [62, '2', 'GIFTING', '20.0GB', '₦5,000.00', '30 days'],
        [63, '4', 'SME', '500MB', '₦150.00', '14 days'],
        [64, '4', 'SME', '1.0GB', '₦220.00', '30 days'],
        [65, '4', 'SME', '2.0GB', '₦450.00', '30 days'],
        [66, '4', 'SME', '3.0GB', '₦670.00', '30 days'],
        [67, '4', 'SME', '5.0GB', '₦1,050.00', '30 days'],
        [68, '4', 'SME', '10.0GB', '₦2,300.00', '30 days'],
        [69, '4', 'COOPERATE GIFTING', '500MB', '₦150.00', '14 Days'],
        [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
        [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '30 days'],
        [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦750.00', '30 days'],
        [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,200.00', '30 days'],
        [74, '4', 'COOPERATE GIFTING', '10.0GB', '₦2,500.00', '30 days'],
        [83, '1', 'GIFTING', '1.0GB', '₦275.00', '7 days'],
        [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
      ];
    </script>
    <?php
  }

} else {
  // Handle the case where no row is found
  $_SESSION['user_balance'] = 0; // Default value
}


$deposit_fee = 30;
$public_key = "FLWPUBK_TEST-12ebb25efcc7b39ddffd59f6ef057a68-X";
$secret_key = "FLWSECK_TEST-e659c6e0eb2370218c1bb9bc513612ce-X";

function sendErrorEmailToAdmin($errorMessage)
{
  $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
  $subject = "API Request Error";

  $message = "An error occurred during a request:\n\n";
  $message .= $errorMessage;

  $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  mail($to, $subject, $message, $headers);
}
function sendErrorEmailToManagement($message)
{
  $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
  $subject = "API Request Error";

  $message = "An error occurred during the API request:\n\n";
  $message .= "A user just tried to make purchase but was unable to because your wallet balance at DATASUB247 is low, please fund your wallet so users can make purchases. Message: $message";

  $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  mail($to, $subject, $message, $headers);
}
function securityBridged($email)
{
  $to = "abdulkarimhussain7@gmail.com";
  $subject = "Critical security issue";

  $message = "Security Issues Detected!";
  $message .= "A user just tried to make purchase on a low balance: $email, User account has been deactivated. please check";

  $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  mail($to, $subject, $message, $headers);
}

function newAirtimeTransaction($status, $networkName, $requestId, $profit, $message, $phoneNumber, $oldBalance, $newBalance, $system, $planType, $walletVending)
{
  $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
  $subject = "New Transaction";

  $message = "A new transaction was successfully completed:\n\n";
  $message .= "Status: $status\n";
  $message .= "Network Name: $networkName\n";
  $message .= "Request ID: $requestId\n";
  $message .= "Profit: $profit\n";
  $message .= "Message: $message\n";
  $message .= "Phone Number: $phoneNumber\n";
  $message .= "Old Balance: $oldBalance\n";
  $message .= "New Balance: $newBalance\n";
  $message .= "System: $system\n";
  $message .= "Plan Type: $planType\n";
  $message .= "Wallet Vending: $walletVending\n";

  $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  mail($to, $subject, $message, $headers);
}

function newDataTransaction($status, $networkName, $requestId, $profit, $message, $dataplan, $phoneNumber, $oldBalance, $newBalance, $system, $planType, $walletVending)
{
  $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
  $subject = "New Transaction";

  $message = "A new transaction was successfully completed:\n\n";
  $message .= "Status: $status\n";
  $message .= "Network Name: $networkName\n";
  $message .= "Request ID: $requestId\n";
  $message .= "Profi: $profit\n";
  $message .= "Amount: $dataplan\n";
  $message .= "Message: $message\n";
  $message .= "Phone Number: $phoneNumber\n";
  $message .= "Old Balance: $oldBalance\n";
  $message .= "New Balance: $newBalance\n";
  $message .= "System: $system\n";
  $message .= "Plan Type: $planType\n";
  $message .= "Wallet Vending: $walletVending\n";

  $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

  mail($to, $subject, $message, $headers);
}


// Function to update user's balance in the database (replace with your database logic)

function update_initBalance($pdo, $newBalance, $user_email, $txRef)
{
  try {


    $pdo->beginTransaction();
    // Update the user's balance in the database
    $updateStmt = $pdo->prepare("UPDATE users SET balance = :newBalance WHERE email = :userEmail");
    $updateStmt->bindParam(':newBalance', $newBalance, PDO::PARAM_INT);
    $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
    $updateStmt->execute();

    $status = "Completed";


    $updateQuery = "UPDATE transactions SET status = :status WHERE trx_ref = :trx_ref";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
    $updateStmt->execute();

    $updateQuery = "UPDATE init_transactions SET status = :status WHERE trx_ref = :trx_ref";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
    $updateStmt->execute();


    // Commit the transaction
    $pdo->commit();

    $_SESSION['user_balance'] = $newBalance;
  } catch (PDOException $e) {
    $pdo->rollBack();
  }
}




?>