<?php
// // Start or resume the session
session_start();

//  if (isset($_SESSION['email'])) {
    
//  } else {
//      // User is not logged in, redirect to the login page
//      header('Location: auth/index.php');

//      exit();
//  }

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


// $servername = "localhost";
// $username = "mytvglow_virasub";
// $password = "VYIhLghDd,m@4NxDmW";
// $database = "mytvglow_virasub";

$servername = "localhost";
$username = "virasubc_virasub";
$password = "T.gxMx)md)UO{)D]Id";
$database = "virasubc_virasub";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}






if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}



// $user_email = 'abdulkarimhussain7@gmail.com';
$user_email = $_SESSION['email'];

$deposit_fee = 30;
$public_key = "FLWPUBK-918cdc8ecd9975a7f9116c39f6f6fe1f-X";
$secret_key = "FLWSECK-dcc0035b7ffd3869c475e89f2091f21e-18cd9d35a4avt-X";


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
  $_SESSION['user_balance'] = $bal;

  if (is_null($bal)) {
    $balance = "0.00";
  } else {
    $balance = $row['balance'];
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
    $message .= "Profit: $profit\n";
    $message .= "DataPlan: $dataplan\n";
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

function newBillTransaction($status, $disco, $requestId, $amount, $fee, $message, $meter_number, $meter_type, $oldBalance, $newBalance, $system, $walletVending)
{
  $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
    $subject = "New Transaction";

    $message = "A new transaction was successfully completed:\n\n";
    $message .= "Status: $status\n";
    $message .= "Disco Name: $disco\n";
    $message .= "Request ID: $requestId\n";
    $message .= "fee: $fee\n";
    $message .= "Amount: $amount\n";
    $message .= "Message: $message\n";
    $message .= "Meter Number: $meter_number\n";
    $message .= "Meter Type: $meter_type\n";
    $message .= "Old Balance: $oldBalance\n";
    $message .= "New Balance: $newBalance\n";
    $message .= "System: $system\n";
    $message .= "Wallet Vending: $walletVending\n";

    $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);
}


// Function to update user's balance in the database (replace with your database logic)

function update_initBalance($pdo, $newBalance, $user_email, $txRef) {
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