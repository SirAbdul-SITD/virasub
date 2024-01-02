<?php

require("settings.php");


// Get the raw POST data
$webhookData = file_get_contents("php://input");

// Verify the secret hash
$secretHash = 'YOUR_SECRET_HASH'; // Replace with your actual secret hash
$signature = isset($_SERVER['HTTP_VERIF-HASH']) ? $_SERVER['HTTP_VERIF-HASH'] : null;

if (!$signature || ($signature !== $secretHash)) {
    // Secret hash doesn't match, discard the request
    http_response_code(401); // Unauthorized
    echo "Invalid secret hash.";
    exit;
}


// Decode the JSON data
$decodedData = json_decode($webhookData, true);

// Check if decoding was successful
if ($decodedData !== null) {
    $status = $decodedData['data']['status'];
    $tx_ref = $decodedData['data']['tx_ref'];
    $payment_type = $decodedData['data']['payment_type'];
    $customer_email = $decodedData['data']['customer']['email'];
    $narration = $decodedData['data']['narration'];
    $amount = $decodedData['data']['amount'];


    // check if this webhook already exist
    $query = "SELECT `tx_ref` FROM webhook_data WHERE tx_ref = :tx_ref";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':tx_ref', $tx_ref, PDO::PARAM_STR);
    $stmt->execute();
    $transactionData = $stmt->fetch(PDO::FETCH_ASSOC);


    // dump into webhook table
    if ($transactionData == null) {

        // Prepare and execute the SQL query
        $insertQuery = "INSERT INTO `webhook_data` (
        `event_status`, `tx_ref`, `flw_ref`, `device_fingerprint`, `amount`, 
        `currency`, `charged_amount`, `app_fee`, `merchant_fee`, `processor_response`, 
        `auth_model`, `ip`, `narration`, `payment_status`, `payment_type`, 
        `created_at`, `account_id`, `customer_id`, `customer_name`, 
        `customer_phone`, `customer_email`
    ) VALUES (
        :eventStatus, :txRef, :flwRef, :deviceFingerprint, :amount, 
        :currency, :chargedAmount, :appFee, :merchantFee, :processorResponse, 
        :authModel, :ip, :narration, :status, :paymentType, 
        :createdAt, :accountId, :customerId, :customerName, 
        :customerPhone, :customerEmail
    )";

        $insertStmt = $pdo->prepare($insertQuery);

        // Bind parameters
        $insertStmt->bindParam(':eventStatus', $decodedData['data']['status'], PDO::PARAM_STR);
        $insertStmt->bindParam(':txRef', $decodedData['data']['tx_ref'], PDO::PARAM_STR);
        $insertStmt->bindParam(':flwRef', $decodedData['data']['flw_ref'], PDO::PARAM_STR);
        $insertStmt->bindParam(':deviceFingerprint', $decodedData['data']['device_fingerprint'], PDO::PARAM_STR);
        $insertStmt->bindParam(':amount', $decodedData['data']['amount'], PDO::PARAM_INT);
        $insertStmt->bindParam(':currency', $decodedData['data']['currency'], PDO::PARAM_STR);
        $insertStmt->bindParam(':chargedAmount', $decodedData['data']['charged_amount'], PDO::PARAM_INT);
        $insertStmt->bindParam(':appFee', $decodedData['data']['app_fee'], PDO::PARAM_STR);
        $insertStmt->bindParam(':merchantFee', $decodedData['data']['merchant_fee'], PDO::PARAM_STR);
        $insertStmt->bindParam(':processorResponse', $decodedData['data']['processor_response'], PDO::PARAM_STR);
        $insertStmt->bindParam(':authModel', $decodedData['data']['auth_model'], PDO::PARAM_STR);
        $insertStmt->bindParam(':ip', $decodedData['data']['ip'], PDO::PARAM_STR);
        $insertStmt->bindParam(':narration', $decodedData['data']['narration'], PDO::PARAM_STR);
        $insertStmt->bindParam(':status', $decodedData['data']['status'], PDO::PARAM_STR);
        $insertStmt->bindParam(':paymentType', $decodedData['data']['payment_type'], PDO::PARAM_STR);
        $insertStmt->bindParam(':createdAt', $decodedData['data']['created_at'], PDO::PARAM_STR);
        $insertStmt->bindParam(':accountId', $decodedData['data']['account_id'], PDO::PARAM_INT);
        $insertStmt->bindParam(':customerId', $decodedData['data']['customer']['id'], PDO::PARAM_INT);
        $insertStmt->bindParam(':customerName', $decodedData['data']['customer']['name'], PDO::PARAM_STR);
        $insertStmt->bindParam(':customerPhone', $decodedData['data']['customer']['phone_number'], PDO::PARAM_STR);
        $insertStmt->bindParam(':customerEmail', $decodedData['data']['customer']['email'], PDO::PARAM_STR);

        // Execute the query
        $insertStmt->execute();
    }

    $query = "SELECT `status` FROM funding WHERE trx_ref = :trx_ref";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
    $stmt->execute();
    $transactionData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transactionData['status'] !== $status || $status == "successful") {

        $trx_status = "Completed";

        $updateQuery = "UPDATE funding SET status = :status, type = :type, narration = :narration WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $trx_status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $payment_type, PDO::PARAM_STR);
        $updateStmt->bindParam(':narration', $secretHashFromHeader, PDO::PARAM_STR);
        $updateStmt->execute();
        

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :userEmail");
        $stmt->bindParam(':userEmail', $customer_email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $balance = $row['balance'];

        $newBal = $balance + $amount;

        $updateQuery = "UPDATE users SET balance = :newBal WHERE email = :user_email";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newBal', $newBal, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_email', $customer_email, PDO::PARAM_STR);
        $updateStmt->execute();


    } elseif ($status == "failed") {

        $trx_status = "Failed";

        $updateQuery = "UPDATE funding SET status = :status, type = :type, narration = :narration WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $trx_status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $payment_type, PDO::PARAM_STR);
        $updateStmt->bindParam(':narration', $narration, PDO::PARAM_STR);
        $updateStmt->execute();
        
    }

    // Close the database connection
    $pdo = null;



    // Respond with a 200 status code to acknowledge receipt
    http_response_code(200);
    echo "Webhook data received and processed successfully.";
    print_r($decodedData);
} else {
    // Respond with an error if the data is not valid JSON
    http_response_code(400); // Bad Request
    echo "Invalid JSON data.";
}

?>