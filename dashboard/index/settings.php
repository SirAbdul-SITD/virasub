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

$deposit_fee = 30;
$public_key = "FLWPUBK-7629f619c8c46d8a65020bb53f1def79-X";
$secret_key = "FLWSECK-dcc0035b7ffd3869c475e89f2091f21e-18cd9d35a4avt-X";


  if (is_null($bal)) {
    $balance = "0.00";
  } else {
    $balance = $row['balance'];
  }

  if ($user_email == "abdulkarimhussain7@gmail.com" || $user_email == "muhammadbash24727@gmail.com" || $user_email == "muhammadbash24727@gmail.com") {


    $plansData = array(
      [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [83, '1', 'GIFTING', '1.0GB', '₦250.00', '7 days'],
      [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
      [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],

      [37, '2', 'COOPERATE GIFTING', '100MB', '₦60.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦100.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦130.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [62, '2', 'GIFTING', '20.0GB', '₦4,790.00', '30 days'],

      

      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦140.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦490.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦730.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,195.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,390.00', '30 days'],
      [39, '3', 'GIFTING', '29.5GB', '₦7,500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦9,500.00', '1 Month'],

      [69, '4', 'COOPERATE GIFTING', '500MB', '₦140.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦230.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦440.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦650.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,100.00', '30 days'],
      [43, '4', 'GIFTING', '40.0GB', '₦8,500.00', '1 Month'],
    );
    ?>


    <script>
      plansData = [
        [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [83, '1', 'GIFTING', '1.0GB', '₦250.00', '7 days'],
      [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
      [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],

      [37, '2', 'COOPERATE GIFTING', '100MB', '₦60.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦100.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦130.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [62, '2', 'GIFTING', '20.0GB', '₦4,790.00', '30 days'],

      

      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦140.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦490.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦730.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,195.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,390.00', '30 days'],
      [39, '3', 'GIFTING', '29.5GB', '₦7,500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦9,500.00', '1 Month'],

      [69, '4', 'COOPERATE GIFTING', '500MB', '₦140.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦230.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦440.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦650.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,100.00', '30 days'],
      [43, '4', 'GIFTING', '40.0GB', '₦8,500.00', '1 Month'],
      ];
    </script>

    <?php
  } else {

    $plansData = array(
      [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [83, '1', 'GIFTING', '1.0GB', '₦250.00', '7 days'],
      [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
      [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],

      [37, '2', 'COOPERATE GIFTING', '100MB', '₦60.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦100.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦130.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [62, '2', 'GIFTING', '20.0GB', '₦4,790.00', '30 days'],

      

      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦140.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦490.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦730.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,195.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,390.00', '30 days'],
      [39, '3', 'GIFTING', '29.5GB', '₦7,500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦9,500.00', '1 Month'],

      [69, '4', 'COOPERATE GIFTING', '500MB', '₦140.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦230.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦440.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦650.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,100.00', '30 days'],
      [43, '4', 'GIFTING', '40.0GB', '₦8,500.00', '1 Month'],
    );
    ?>
    <script>
      plansData = [
        [6, '1', 'SME', '500MB', '₦145.00', '1 Month'],
      [83, '1', 'GIFTING', '1.0GB', '₦250.00', '7 days'],
      [1, '1', 'SME', '1.0GB', '₦290.00', '1 Month'],
      [3, '1', 'SME', '2.0GB', '₦570.00', '1 Month'],
      [85, '1', 'GIFTING', '3.0GB', '₦800.00', '1 Month'],
      [4, '1', 'SME', '5.0GB', '₦1,400.00', '1 Month'],
      [5, '1', 'SME', '10.0GB', '₦2,750.00', '1 Month'],
      [51, '1', 'COOPERATE GIFTING', '15.0GB', '₦3,850.00', '30 days'],

      [37, '2', 'COOPERATE GIFTING', '100MB', '₦60.00', '1 Month'],
      [36, '2', 'COOPERATE GIFTING', '300MB', '₦100.00', '1 Month'],
      [13, '2', 'COOPERATE GIFTING', '500MB', '₦130.00', '1 Month'],
      [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦240.00', '1 Month'],
      [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦450.00', '1 Month'],
      [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,070.00', '1 Month'],
      [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,130.00', '1 Month'],
      [62, '2', 'GIFTING', '20.0GB', '₦4,790.00', '30 days'],

      

      [44, '3', 'COOPERATE GIFTING', '200MB', '₦105.00', '30 days'],
      [45, '3', 'COOPERATE GIFTING', '500MB', '₦140.00', '30 days'],
      [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '30 days'],
      [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦490.00', '30 days'],
      [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦730.00', '30 days'],
      [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,195.00', '30 days'],
      [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,390.00', '30 days'],
      [39, '3', 'GIFTING', '29.5GB', '₦7,500.00', '1 Month'],
      [40, '3', 'GIFTING', '50.0GB', '₦9,500.00', '1 Month'],

      [69, '4', 'COOPERATE GIFTING', '500MB', '₦140.00', '14 Days'],
      [70, '4', 'COOPERATE GIFTING', '1.0GB', '₦230.00', '30 days'],
      [71, '4', 'COOPERATE GIFTING', '2.0GB', '₦440.00', '30 days'],
      [72, '4', 'COOPERATE GIFTING', '3.0GB', '₦650.00', '30 days'],
      [73, '4', 'COOPERATE GIFTING', '5.0GB', '₦1,100.00', '30 days'],
      [43, '4', 'GIFTING', '40.0GB', '₦8,500.00', '1 Month'],
      ];
    </script>
    <?php
  }

} else {
  // Handle the case where no row is found
  $_SESSION['user_balance'] = 0; // Default value
}


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