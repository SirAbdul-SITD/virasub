<?php
require('settings.php');

try {
  if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    throw new Exception("input validation failed. Please refresh page. [err_code: #2873]");
  } else {
    unset($_SESSION['csrf_token']);
  }

  // Check if all $_POST variables are set and not empty
  $requiredPostVariables = array("data_network", "data_phone", "data_plan");
  foreach ($requiredPostVariables as $variable) {
    if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
      throw new Exception("All form fields are required.  [err_code: #2073]");
    }
  }

  // Sanitize and validate the input data
  $network = filter_input(INPUT_POST, 'data_network', FILTER_SANITIZE_NUMBER_INT);
  $phone = filter_input(INPUT_POST, 'data_phone', FILTER_SANITIZE_NUMBER_INT);
  $planID = filter_input(INPUT_POST, 'data_plan', FILTER_SANITIZE_NUMBER_INT);



  // Validate the input data
  if (!$network || !in_array($network, [1, 2, 3, 4])) {
    throw new Exception("Invalid network selection!  [err_code: #2493]");
  }

  if (!$phone || strlen((string) $phone) !== 11) {
    throw new Exception("Invalid phone number! [err_code: #0973]");
  }

  if (!$planID || $planID <= 0 || $planID >= 75) {
    throw new Exception("Invalid Plan! [err_code: #8723]");
  }


  // Verify user balance
  $stmt = $pdo->prepare("SELECT balance FROM users WHERE email = :userEmail");
  $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $balance = $row['balance'];
  }

  $plansData = array(
    [1, '1', 'SME', '1.0GB', '₦245.00', '1 Month'],
    [2, '1', 'SME', '3.0GB', '₦685.00', '1 Month'],
    [3, '1', 'SME', '2.0GB', '₦500.00', '1 Month'],
    [4, '1', 'SME', '5.0GB', '₦2000.00', '1 Month'],
    [5, '1', 'SME', '10.0GB', '₦2,650.00', '1 Month'],
    [6, '1', 'SME', '500MB', '₦200.00', '1 Month'],
    [7, '1', 'COOPERATE GIFTING', '500MB', '₦155.00', '1 Month'],
    [8, '1', 'COOPERATE GIFTING', '1.0GB', '₦285.00', '1 Month'],
    [9, '1', 'COOPERATE GIFTING', '2.0GB', '₦650.00', '1 Month'],
    [10, '1', 'COOPERATE GIFTING', '3.0GB', '₦850.00', '1 Month'],
    [11, '1', 'COOPERATE GIFTING', '5.0GB', '₦1,600.00', '1 Month'],
    [12, '1', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '1 Month'],
    [13, '2', 'COOPERATE GIFTING', '500MB', '₦150.00', '1 Month'],
    [14, '2', 'COOPERATE GIFTING', '1.0GB', '₦250.00', '1 Month'],
    [15, '2', 'COOPERATE GIFTING', '2.0GB', '₦500.00', '1 Month'],
    [17, '2', 'COOPERATE GIFTING', '5.0GB', '₦1,300.00', '1 Month'],
    [18, '2', 'COOPERATE GIFTING', '10.0GB', '₦2,600.00', '1 Month'],
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
    [44, '3', 'COOPERATE GIFTING', '200MB', '₦125.00', '30 days'],
    [45, '3', 'COOPERATE GIFTING', '500MB', '₦155.00', '30 days'],
    [46, '3', 'COOPERATE GIFTING', '1.0GB', '₦260.00', '30 days'],
    [47, '3', 'COOPERATE GIFTING', '2.0GB', '₦485.00', '30 days'],
    [48, '3', 'COOPERATE GIFTING', '3.0GB', '₦720.00', '30 days'],
    [49, '3', 'COOPERATE GIFTING', '5.0GB', '₦1,250.00', '30 days'],
    [50, '3', 'COOPERATE GIFTING', '10.0GB', '₦2,650.00', '30 days'],
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
  );

  $strippedPrice = null;

  foreach ($plansData as $plan) {

    if ($plan[0] == $planID) {
      $value = $plan[4];
      $strippedPrice = intval(str_replace(",", "", str_replace("₦", "", $value)));
      if ($balance < $strippedPrice) {
        throw new Exception("Your Balance is not sufficient for this transaction [err_code: #9073]");
      }
    }
  }






  // Create the payload for the data subscription request
  $payload = array(
    'network' => $network,
    'phone' => $phone,
    'data_plan' => $planID,
    'bypass' => false,
    'request-id' => 'Airtime_' . uniqid()
  );


  $maxRetries = 2;
  $retryDelay = 1000; // Retry delay in milliseconds (1000ms = 1 second)

  for ($retry = 0; $retry <= $maxRetries; $retry++) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/data');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
      "Authorization: Token 7d2ba8138c02d5ad579285dc645ff10d1c2630a9f3cbee50db293abdbcf0",
      'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    curl_close($ch);

    if ($response === false) {
      $error = curl_error($ch);
      if (strpos($error, "Couldn't resolve host") === false) {
        ///todo please check whether this is a network issue omn the user end
        sendErrorEmailToAdmin($error);
        throw new Exception("Couldn't complete your request at the moment, please try again in 30 minutes. [err_code: #2473]");
      } else {
        //just a network error on user's end
        throw new Exception("Couldn't complete your request. Please check your internet connection and try again. [err_code: #4672]");
      }
    }

    $responseData = json_decode($response, true);
    if ($responseData !== null) {
      break;
    }


    usleep($retryDelay * 1000); // usleep uses microseconds, so we multiply by 1000 to convert milliseconds to microseconds
  }

  // Process the API response
  if ($response) {
    $responseData = json_decode($response, true);

    //Extract relevant information from the response
    $status = $responseData['status'];
    $networkName = $responseData['network'];
    $requestId = $responseData['request-id'];
    $amount = $responseData['amount'];
    $dataplan = $responseData['dataplan'];
    $message = $responseData['message'];
    $phoneNumber = $responseData['phone_number'];
    $oldBalance = $responseData['oldbal'];
    $newBalance = $responseData['newbal'];
    $system = $responseData['system'];
    $planType = $responseData['plan_type'];
    $walletVending = $responseData['wallet_vending'];

    // $status = 'success';
    // $networkName = 'MTN';
    // $requestId = '897576';
    // $amount = '₦2500';
    // $message = 'Sample message';
    // $phoneNumber = '1234567890';
    // $oldBalance = '₦5000';
    // $newBalance = '₦3000';
    // $system = 'Sample System';
    // $planType = 'Sample Plan Type';
    // $dataPlan = '1GB';
    // $walletVending = 'Sample Wallet Vending';
    // $discount = '₦100';

    // Display the results to the user
    if ($status === 'success') {
      echo "$networkName \n";
      echo "$requestId \n";
      echo "$strippedPrice \n";
      echo "$message \n";
      echo "$phoneNumber \n";
      echo "$dataplan \n";
      echo "$status \n";



      unset($_SESSION['user_balance']);
      unset($_SESSION['data_network']);
      unset($_SESSION['data_phone']);
      unset($_SESSION['data_plan']);

      $profit = $strippedPrice - $amount;

      newDataTransaction($status, $networkName, $requestId, $profit, $dataplan, $message, $phoneNumber, $oldBalance, $newBalance, $system, $planType, $walletVending);
      //update user's balance
      try {

        $pdo->beginTransaction();

        if ($balance >= $strippedPrice) {
          $newBalance = $balance - $strippedPrice;

          // Update the user's balance in the database
          $updateStmt = $pdo->prepare("UPDATE users SET balance = :newBalance WHERE email = :userEmail");
          $updateStmt->bindParam(':newBalance', $newBalance, PDO::PARAM_INT);
          $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
          $updateStmt->execute();

          // Commit the transaction
          $pdo->commit();
          echo "$newBalance \n";

          $_SESSION['user_balance'] = $newBalance;

          $type = "Data";
          $status = "Completed";

          $updateQuery = "INSERT INTO `transactions` (`user`, `type`, `amount`, `status`, `trx_ref`) VALUES (:user, :type, :amount, :status, :trx_ref)";
          $updateStmt = $pdo->prepare($updateQuery);
          $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
          $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
          $updateStmt->bindParam(':amount', $strippedPrice, PDO::PARAM_INT);
          $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
          $updateStmt->bindParam(':trx_ref', $requestId, PDO::PARAM_STR);
          $updateStmt->execute();
        } else {
          securityBridged($user_email);
          $status = 'Suspended';
          // Update the user's balance in the database
          $updateStmt = $pdo->prepare("UPDATE users SET status = :status WHERE email = :userEmail");
          $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
          $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
          $updateStmt->execute();
          throw new Exception('Critical error, Your account has been suspended! [err_code: #6343]');

        }
      } catch (PDOException $e) {

        $pdo->rollBack();
      }



      //handle error messages
    } elseif (strpos($message, "Insufficient Account") !== false) {
      sendErrorEmailToManagement($message);
      throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes.  [err_code: #3933]");
    } elseif (strpos($message, "This is not a") !== false) {
      throw new Exception($message);
    } elseif (strpos($message, "You have Reach Daily Transaction Limit") !== false) {
      sendErrorEmailToManagement($message);
      sendErrorEmailToAdmin($message);
      throw new Exception("Couldn't complete your request. Please try again in 30 minutes.  [err_code: #4304]");
    } else {
      //user input validation failed
      sendErrorEmailToAdmin(" #3323" . $message);
      throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3323]");
    }

  } else {
    // Error handling if the API call fails
    $error = "The code for processing API calls failed, please confirm all services are working fine.  [err_code: #3273]";
    sendErrorEmailToAdmin("#3743" . $error);
    throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3743]");
    ;
  }
} catch (Exception $e) {
  // Catch any exceptions that occurred during the API calls or data processing
  sendErrorEmailToAdmin("New Thrown Exception during Data API transaction. Error: $e");
  echo $e->getMessage();
}
?>