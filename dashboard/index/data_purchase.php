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
      "Authorization: Token c9518a8f3a778f1524c26830f96f14c6474c0ac30438c18de6aa09a47831",
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