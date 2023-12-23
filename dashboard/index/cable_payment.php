<?php
require('settings.php');

try {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception("input validation failed. Please refresh page. [err_code: #2873]");
    } else {
        unset($_SESSION['csrf_token']);
    }

    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("cable_name", "cable_plan", "IUC", );
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required.  [err_code: #2073]");
        }
        break;
    }

    // Sanitize and validate the input data
    $cable_name = (int) filter_input(INPUT_POST, 'cable_name', FILTER_SANITIZE_NUMBER_INT);
    $cable_plan = filter_input(INPUT_POST, 'cable_plan', FILTER_SANITIZE_NUMBER_INT);
    $IUC = filter_input(INPUT_POST, 'IUC', FILTER_SANITIZE_NUMBER_INT);

    // Validate the input data
    if (!$cable_name || !in_array($cable_name, [1, 2, 3])) {
        throw new Exception("Invalid cable selection!  [err_code: #2493]");
    }

    if (!$cable_plan || $cable_plan <= 0 || $cable_plan >= 86) {
        throw new Exception("Invalid Plan! [err_code: #8723]");
    } else {
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
        [1, '2', 'DStv Padi', '₦2500'],
        [2, '2', 'DStv -YANGA', '₦3500'],
        [5, '2', 'DStv Premium', '₦24500'],
        [6, '2', 'DStv Asia', '₦8300'],
        [9, '2', 'DStv Premium-Asia', '₦23500'],
        [10, '2', 'DStv Confam + ExtraView', '₦8200'],
        [11, '2', 'DStv Yanga + ExtraView', '₦5850'],
        [12, '2', 'DStv Padi + ExtraView', '₦5050'],
        [32, '2', 'ExtraView Access', '₦2900'],
        [34, '2', 'Dstv Confam', '₦6200'],
        [35, '2', 'DStv Compact', '₦10500'],
        [38, '2', 'DStv Compact Plus', '₦16600'],
        [39, '2', 'DStv Premium-French', '₦25550'],
        [45, '2', 'DStv Compact + Extra View', '₦11900'],
        [59, '1', 'GOtv Max', '₦4850'],
        [60, '1', 'GOtv Jolli', '₦3300'],
        [61, '1', 'GOtv Jinja', '₦2250'],
        [62, '1', 'GOtv Smallie - monthly', '₦900'],
        [63, '1', 'GOtv Smallie - quarterly', '₦2100'],
        [64, '1', 'GOtv Smallie - yearly', '₦7000'],
        [66, '3', 'Nova - 1 Month', '₦1200'],
        [67, '3', 'Basic (Antenna) - 1 Month', '₦2100'],
        [68, '3', 'Smart (Dish) -1 Month', '₦2800'],
        [69, '3', 'Classic (Antenna) - 1 Month', '₦3100'],
        [70, '3', 'Super (Dish) - 1 Month', '₦5300'],
        [71, '3', 'Nova - 1 Week', '₦400'],
        [72, '3', 'Basic (Antenna) - 1 Week', '₦700'],
        [73, '3', 'Smart (Dish) -- 1 Week', '₦900'],
        [74, '3', 'Classic (Antenna) - 1 Week', '₦1200'],
        [75, '3', 'Super (Dish) - 1 Week', '₦1800'],
        [76, '3', 'Nova- 1 Day', '₦100'],
        [77, '3', 'Basic (Antenna) -- 1 Day', '₦200'],
        [78, '3', 'Smart (Dish) 1 Day', '₦250'],
        [79, '3', 'Classic (Antenna) - 1 Day', '₦320'],
        [80, '3', 'Super (Dish) - 1 Day', '₦500'],
        [81, '2', 'DStv Compact Plus -Extra view', '₦17150'],
        [82, '2', 'Asian Bouquet', '₦20295'],
        [83, '2', 'DStv HDPVR Access Service', '₦2900'],
        [84, '2', 'DStv Premium + Extra View', '₦23900'],
        [85, '1', 'Supa', '₦6400'],
        [86, '3', 'Chinese', '₦9800'],
    );
    $strippedPrice = null;

    foreach ($plansData as $plan) {
        if ($plan[0] == $cable_plan) {
            $value = $plan[3];
            $strippedPrice = intval(str_replace(",", "", str_replace("₦", "", $value)));
            if ($balance < $strippedPrice) {
                throw new Exception("Your Balance is not sufficient for this transaction [err_code: #9073]");
            }
            break;
        }
    }

    // Create the payload for the cable subscription request
    $payload = array(
        'cable' => $cable_name,
        'cable_plan' => $cable_plan,
        'iuc' => $IUC,
        'bypass' => false,
        'request-id' => 'Cable' . uniqid()
    );


    $maxRetries = 3;
    $retryDelay = 1000; // Retry delay in milliseconds (1000ms = 1 second)

    for ($retry = 0; $retry <= $maxRetries; $retry++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/cable');
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
        $cabl_name = $responseData['cabl_name'];
        $request = $responseData['request-id'];
        $amount = $responseData['amount'];
        $charges = $responseData['charges'];
        $status = $responseData['status'];
        $message = $responseData['message'];
        $iuc = $responseData['iuc'];
        $oldbal = $responseData['oldbal'];
        $newbal = $responseData['newbal'];
        $system = $responseData['system'];
        $wallet_vending = $responseData['wallet_vending'];
        $plan_name = $responseData['plan_name'];

        // Display the results to the user
        if ($status === 'success') {
            // Bill payment was successful
            echo "$request  \n";
            echo "$cable_name \n";
            echo "$cable_plan \n";
            echo "$iuc \n";
            echo "$strippedPrice \n";
            echo "$message \n";

            // echo "ygfyugyeuiuibu \n";
            // echo "DSTV \n";
            // echo "DSTV joli joli \n";
            // echo "8y38778rt378t87t \n";
            // echo "$strippedPrice \n";
            // echo "successfully purchase DSTV joli joli ₦12000 to 0701339708866 \n";

            unset($_SESSION['user_balance']);
            unset($_SESSION['airtime_network']);
            unset($_SESSION['airtime_phone']);
            unset($_SESSION['airtime_plan']);

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

                        $_SESSION['user_balance'] = $newBalance;
                        echo "$newBalance \n";
                        
                        $type = "Cable";
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