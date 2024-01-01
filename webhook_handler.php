<?php

require("settings.php");


// Get the raw POST data
$webhookData = '{"event":"charge.completed","data":{"id":4820873,"tx_ref":"Funds_6592e1b7cbfe5","flw_ref":"flwm3s4m0c1704124874181","device_fingerprint":"0c70c48bf1e4486fd31fb467f152596a","amount":1030,"currency":"NGN","charged_amount":1030,"app_fee":14.42,"merchant_fee":0,"processor_response":"Transaction completed","auth_model":"USSD","ip":"52.209.154.143","narration":"Shinami Information Technology Development ","status":"successful","payment_type":"ussd","created_at":"2024-01-01T16:01:13.000Z","account_id":161061,"customer":{"id":2317751,"name":"Anonymous Customer","phone_number":null,"email":"abdulkarimhussain2@gmail.com","created_at":"2024-01-01T16:01:13.000Z"}},"event.type":"USSD_TRANSACTION"}';

// Decode the JSON data
$decodedData = json_decode($webhookData, true);

// Check if decoding was successful
if ($decodedData !== null) {
    $status = $decodedData['data']['status'];
    $tx_ref = $decodedData['data']['tx_ref'];
    $payment_type = $decodedData['data']['payment_type'];
    $customer_email = $decodedData['data']['customer']['email'];
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
        $insertStmt->bindParam(':eventStatus', $data['data']['status'], PDO::PARAM_STR);
        $insertStmt->bindParam(':txRef', $data['data']['tx_ref'], PDO::PARAM_STR);
        $insertStmt->bindParam(':flwRef', $data['data']['flw_ref'], PDO::PARAM_STR);
        $insertStmt->bindParam(':deviceFingerprint', $data['data']['device_fingerprint'], PDO::PARAM_STR);
        $insertStmt->bindParam(':amount', $data['data']['amount'], PDO::PARAM_INT);
        $insertStmt->bindParam(':currency', $data['data']['currency'], PDO::PARAM_STR);
        $insertStmt->bindParam(':chargedAmount', $data['data']['charged_amount'], PDO::PARAM_INT);
        $insertStmt->bindParam(':appFee', $data['data']['app_fee'], PDO::PARAM_STR);
        $insertStmt->bindParam(':merchantFee', $data['data']['merchant_fee'], PDO::PARAM_STR);
        $insertStmt->bindParam(':processorResponse', $data['data']['processor_response'], PDO::PARAM_STR);
        $insertStmt->bindParam(':authModel', $data['data']['auth_model'], PDO::PARAM_STR);
        $insertStmt->bindParam(':ip', $webhookData, PDO::PARAM_STR);
        $insertStmt->bindParam(':narration', $data['data']['narration'], PDO::PARAM_STR);
        $insertStmt->bindParam(':status', $data['data']['status'], PDO::PARAM_STR);
        $insertStmt->bindParam(':paymentType', $data['data']['payment_type'], PDO::PARAM_STR);
        $insertStmt->bindParam(':createdAt', $data['data']['created_at'], PDO::PARAM_STR);
        $insertStmt->bindParam(':accountId', $data['data']['account_id'], PDO::PARAM_INT);
        $insertStmt->bindParam(':customerId', $data['data']['customer']['id'], PDO::PARAM_INT);
        $insertStmt->bindParam(':customerName', $data['data']['customer']['name'], PDO::PARAM_STR);
        $insertStmt->bindParam(':customerPhone', $data['data']['customer']['phone_number'], PDO::PARAM_STR);
        $insertStmt->bindParam(':customerEmail', $data['data']['customer']['email'], PDO::PARAM_STR);

        // Execute the query
        $insertStmt->execute();
        echo "success";
    }





    $query = "SELECT `status` FROM funding WHERE trx_ref = :trx_ref";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
    $stmt->execute();
    $transactionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($transactionData['status'] !== $status || $status == "successful") {

        $trx_status = "Completed";

        $updateQuery = "UPDATE funding SET status = :status, type = :type WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $trx_status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $payment_type, PDO::PARAM_STR);
        $updateStmt->execute();


        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :userEmail");
        $stmt->bindParam(':userEmail', $customer_email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $balance = $row['balance'];

        $newBal = $balance + $amount;

        $updateQuery = "UPDATE users SET balance = :newBal WHERE user = :user_email";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newBal', $newBal, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_email', $customer_email, PDO::PARAM_STR);
        $updateStmt->execute();


    } elseif ($status == "failed") {

        $trx_status = "Failed";

        $updateQuery = "UPDATE funding SET status = :status, type = :type WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $trx_status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $payment_type, PDO::PARAM_STR);
        $updateStmt->execute();

    }

    // Close the database connection
    $pdo = null;



    // Respond with a 200 status code to acknowledge receipt
    http_response_code(200);
    echo "Webhook data received and processed successfully.";
} else {
    // Respond with an error if the data is not valid JSON
    http_response_code(400); // Bad Request
    echo "Invalid JSON data.";
}

?>


