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



$user_email = 'user2@example.com';
$_SESSION['admin_email'] = "abdulkarimhussain7@gmail.com";
$_SESSION['email'] = 'user2@example.com';
$_SESSION['name'] = "Abdulkarim Hussain";

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :userEmail");
$stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR); 
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  $balance = $row['balance'];
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $phone_number = $row['phone'];
  $address = $row['address'];
  $state = $row['state'];
  $status = $row['status'];
  $user_name = $row['first_name'] ." ". $row['last_name'];
  $_SESSION['user_balance'] = $balance;
} else {
  // Handle the case where no row is found
  $_SESSION['user_balance'] = 0; // Default value
}


$deposit_fee = 30;
$secret_key = "FLWSECK-25775e6bf331078bb1ba111e828a3c26-18c99641f6evt-X";
$public_key = "FLWPUBK-7629f619c8c46d8a65020bb53f1def79-X";

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
    $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
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
function updateBalance($balance, $amount, $user_email, $pdo) {

  $newBal = $balance + $amount;
  // Your database update logic here
  // Example: Update user's balance by adding the $amount
  $updateQuery = "UPDATE users SET balance = :balance WHERE email = :userEmail";
  $updateStmt = $pdo->prepare($updateQuery);

  $updateStmt->bindParam(':balance', $newBal, PDO::PARAM_STR);
  $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
  $updateStmt->execute();
  // Handle the database query result as needed
}

?>