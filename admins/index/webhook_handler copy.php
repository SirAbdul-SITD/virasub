<?php
require("settings.php");

// Specify your secret hash (recommended to be stored as an environment variable)
$secretHash = "your_secret_hash"; // Replace with your actual secret hash

// Accept the JSON data from the webhook
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data) {
    // Verify the secret hash
    $webhookSignature = $_SERVER['HTTP_VERIF-HASH'];
    if (!$webhookSignature || $webhookSignature !== $secretHash) {
        // Signature doesn't match; discard the request
        http_response_code(401); // Unauthorized
        echo "Unauthorized request.";
        exit;
    }




    // Extract data
    $event = $data['event'];
    $tx_ref = $data['data']['tx_ref'];
    $flw_ref = $data['data']['flw_ref'];
    $device_fingerprint = $data['data']['device_fingerprint'];
    $amount = $data['data']['amount'];
    $currency = $data['data']['currency'];
    $charged_amount = $data['data']['charged_amount'];
    $app_fee = $data['data']['app_fee'];
    $merchant_fee = $data['data']['merchant_fee'];
    $processor_response = $data['data']['processor_response'];
    $auth_model = $data['data']['auth_model'];
    $ip = $data['data']['ip'];
    $narration = $data['data']['narration'];
    $status = $data['data']['status'];
    $payment_type = $data['data']['payment_type'];
    $created_at = $data['data']['created_at'];
    $account_id = $data['data']['account_id'];

    // Check if customer data is present
    if (isset($data['data']['customer'])) {
        $customer = $data['data']['customer'];
        $customer_id = $customer['id'];
        $customer_name = $customer['name'];
        $customer_phone_number = $customer['phone_number'];
        $customer_email = $customer['email'];
        $customer_created_at = $customer['created_at'];
    } else {
        $customer_id = $customer_name = $customer_phone_number = $customer_email = $customer_created_at = null;
    }

    // Insert data into the database
    $stmt = $pdo->prepare("INSERT INTO webhook_data 
    (event, tx_ref, flw_ref, device_fingerprint, amount, currency, charged_amount, app_fee, merchant_fee, processor_response, auth_model, ip, narration, status, payment_type, created_at, account_id, customer_id, customer_name, customer_phone_number, customer_email, customer_created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $event, $tx_ref, $flw_ref, $device_fingerprint, $amount, $currency, $charged_amount, $app_fee, $merchant_fee, $processor_response, $auth_model, $ip, $narration, $status, $payment_type, $created_at, $account_id, $customer_id, $customer_name, $customer_phone_number, $customer_email, $customer_created_at
    ]);


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