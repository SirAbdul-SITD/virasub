<?php
require('settings.php');

try {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception("input validation failed. Please refresh page. [err_code: #2873]");
    } else {
        unset($_SESSION['csrf_token']);
    }

    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("airtime_network", "airtime_phone", "amount");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required.  [err_code: #2073]");
        }
    }

    // Sanitize and validate the input data
    $network = (int) filter_input(INPUT_POST, 'airtime_network', FILTER_SANITIZE_NUMBER_INT);
    $phone = filter_input(INPUT_POST, 'airtime_phone', FILTER_SANITIZE_NUMBER_INT);
    $amount = (int) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);

    // Validate the input data
    if (!$network || !in_array($network, [1, 2, 3, 4])) {
        throw new Exception("Invalid network selection!  [err_code: #2493]");
    }

    if (!$phone || strlen((string) $phone) !== 11) {
        throw new Exception("Invalid phone number! [err_code: #0973]");
    }

    if (!$amount || $amount <= 0 || $amount >= 50000) {
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
        'network' => $network,
        'phone' => $phone,
        'plan_type' => 'VTU',
        'bypass' => false,
        'amount' => $amount,
        'request-id' => 'Airtime_' . uniqid()
    );


    $maxRetries = 5;
    $retryDelay = 1; // Retry delay in milliseconds (1000ms = 1 second)

    for ($retry = 0; $retry <= $maxRetries; $retry++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/topup');
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
                $err = "Couldn't complete your request at the moment, Please check your internet connection and try again, If issue persists try again in in 30 minutes. [err_code: #2473] . Main Error: $error";
                sendErrorEmailToAdmin($err);
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
        // $responseData = json_decode($response, true);

        // Extract relevant information from the response
        $status = $responseData['status'];
        $networkName = $responseData['network'];
        $requestId = $responseData['request-id'];
        $price = $responseData['amount'];
        $message = $responseData['message'];
        $phoneNumber = $responseData['phone_number'];
        $oldBalance = $responseData['oldbal'];
        $newBalance = $responseData['newbal'];
        $system = $responseData['system'];
        $planType = $responseData['plan_type'];
        $walletVending = $responseData['wallet_vending'];
        $discount = $responseData['discount'];


        // $status = 'success';
        // $networkName = 'Sample Network';
        // $requestId = 'sample-request-id';
        // $amou = $amount;
        // $message = 'Sample message';
        // $phoneNumber = '1234567890';
        // $oldBalance = '₦5000';
        // $newBalance = '₦3000';
        // $system = 'Sample System';
        // $planType = 'Sample Plan Type';
        // $walletVending = 'Sample Wallet Vending';
        // $discount = '₦100';

        // Now you can use these variables in your code


        // Display the results to the user
        if ($status === 'success') {
            // Airtime purchase was successful
            // echo "$networkName \n";
            // echo "$requestId \n";
            // echo "$amou \n";
            // echo "$message \n";
            // echo "$phoneNumber \n";
            // echo "$status \n";
            // echo "$discount \n";
            // echo "$oldBalance \n";
            // echo "$newBalance \n";
            // echo "$system \n";
            // echo "$planType \n";
            // echo "$walletVending \n";

            echo "$networkName \n";
            echo "$requestId \n";
            echo "$amount \n";
            echo "$message \n";
            echo "$phoneNumber \n";
            echo "$status \n";



            unset($_SESSION['user_balance']);
            unset($_SESSION['airtime_network']);
            unset($_SESSION['airtime_phone']);
            unset($_SESSION['airtime_amount']);

            $profit = $price - $amount;

            newAirtimeTransaction($status, $networkName, $requestId, $profit, $message, $phoneNumber, $oldBalance, $newBalance, $system, $planType, $walletVending);


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

                    $type = "Airtime";
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
            throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3323]");
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
    sendErrorEmailToAdmin("New Thrown Exception during Airtime API transaction. Error: $e");
    echo $e->getMessage();
}
?>