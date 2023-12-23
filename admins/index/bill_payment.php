<?php
require('settings.php');

try {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception("input validation failed. Please refresh page. [err_code: #2873]");
    } else {
        unset($_SESSION['csrf_token']);
    }

    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("disco_name", "meter_type", "meter_number", "amount");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required.  [err_code: #2073]");
        }
    }

    // Sanitize and validate the input data
    $disco_name = (int) filter_input(INPUT_POST, 'disco_name', FILTER_SANITIZE_NUMBER_INT);
    $meter_type = filter_input(INPUT_POST, 'meter_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $meter_number = filter_input(INPUT_POST, 'meter_number', FILTER_SANITIZE_NUMBER_INT);
    $amount = (int) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);

    // Validate the input data
    if (!$disco_name || !in_array($disco_name, [1, 2, 3, 4, 5, 6, 7, 8])) {
        throw new Exception("Invalid disco selection!  [err_code: #2493]");
    }

    if (!$meter_type || !in_array($meter_type, ['prepaid', 'postpaid'])) {
        throw new Exception("Invalid meter type selection!  [err_code: #2493]");
    }

    // if (!$meter_number || strlen((string) $meter_number) !== 22) {
    //     throw new Exception("Invalid meter number! [err_code: #0973]");
    // }

    if (!$amount || $amount <= 0 || $amount >= 100000) {
        throw new Exception("Invalid amount! [err_code: #8723]");
    }



    // Verify user balance
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE email = :userEmail");
    $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $balance = $row['balance'];
    }
    if ($balance < $amount) {
        throw new Exception("Your Balance is not sufficient for this transaction [err_code: #9073]");
    }





    // Create the payload for the data subscription request
    $payload = array(
        'disco' => $disco_name,
        'meter_type' => $meter_type,
        'meter_number' => $meter_number,
        'amount' => $amount,
        'bypass' => false,
        'request-id' => 'Bill' . uniqid()
    );


    $maxRetries = 2;
    $retryDelay = 1000; // Retry delay in milliseconds (1000ms = 1 second)

    for ($retry = 0; $retry <= $maxRetries; $retry++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/bill');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
            "Authorization: Token ",
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);

        curl_close($ch);

        if ($response === false) {
            $error = curl_error($ch);
            if (strpos($error, "Couldn't resolve host") === false) {
                ///todo please check whether this is a network issue on the user end
                sendErrorEmailToAdmin($error);
                throw new Exception("Couldn't complete your request at the moment, Please check your internet connection and try again, If issue persists try again in in 30 minutes. [err_code: #2473]");
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

        // Extract relevant information from the response
        $status = $responseData['status'];
        $disco = $responseData['disco_name'];
        $requestId = $responseData['request-id'];
        $amount = $responseData['amount'];
        $fee = $responseData['charges'];
        $message = $responseData['message'];
        $meter_number = $responseData['meter_number'];
        $meter_type = $responseData['meter_type'];
        $oldBalance = $responseData['oldbal'];
        $newBalance = $responseData['newbal'];
        $system = $responseData['system'];
        $token = $responseData['token'];
        $walletVending = $responseData['wallet_vending'];

        // Display the results to the user
        if ($status === 'success') {
            // Bill payment was successful
            
         newBillTransaction($status, $disco, $requestId, $amount, $fee, $message, $meter_number, $meter_type, $oldBalance, $newBalance, $system, $token, $walletVending);
             
            echo "$disco \n";
            echo "$requestId \n";
            echo "$amount \n";
            echo "$meter_type \n";
            echo "$token \n";
            echo "$meter_number \n";
            echo "$fee \n";
            echo "$status \n";

             // TODO: Send the OTP to the user via email
             $to = $user_email;
             $subject = "Successful Bill Payment";
             $message = file_get_contents("email_template.html"); 
             $message = str_replace("{token}", $token, $message); 
             $headers = "MIME-Version: 1.0" . "\r\n";
             $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
             $headers .= 'From: noreply@billzwave.com.ng' . "\r\n";
             mail($to, $subject, $message, $headers);

            


            unset($_SESSION['user_balance']);
            unset($_SESSION['airtime_network']);
            unset($_SESSION['airtime_phone']);
            unset($_SESSION['airtime_plan']);

            //update user's balance
            try {

                $pdo->beginTransaction();

                if ($balance >= $amount) {
                    $newBalance = $balance - $amount;

                    // Update the user's balance in the database
                    $updateStmt = $pdo->prepare("UPDATE users SET balance = :newBalance WHERE email = :userEmail");
                    $updateStmt->bindParam(':newBalance', $newBalance, PDO::PARAM_INT);
                    $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR_CHAR);
                    $updateStmt->execute();

                    // Commit the transaction
                    $pdo->commit();

                    $_SESSION['user_balance'] = $newBalance;

                    $type = "Utility";
                    $status = "Completed";

                    $updateQuery = "INSERT INTO `transactions` (`user`, `type`, `amount`, `status`, `trx_ref`) VALUES (:user, :type, :amount, :status, :trx_ref)";
                    $updateStmt = $pdo->prepare($updateQuery);
                    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
                    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
                    $updateStmt->bindParam(':amount', $amount, PDO::PARAM_INT);
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
            sendErrorEmailToAdmin($message);
            throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3323] $response");
        }

    } else {
        // Error handling if the API call fails
        $error = "The code for processing API calls failed, please confirm all services are working fine.  [err_code: #3273]";
        sendErrorEmailToAdmin($error);
        throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3743]");
        ;
    }
} catch (Exception $e) {
    // Catch any exceptions that occurred during the API calls or data processing
    sendErrorEmailToAdmin($e);
    echo $e->getMessage();
}
?>