<?php
require("settings.php");

// Secret key for validating the webhook request (set this to your secret key)
$webhookSecret = "your_webhook_secret_key";

// Read the incoming webhook data
$input = file_get_contents('php://input');
$hash = $_SERVER['HTTP_VERIF_HASH'];

// Verify the webhook request
if ($hash === hash_hmac("sha512", $input, $webhookSecret)) {
    $data = json_decode($input, true);

    // Extract relevant data from the webhook payload
    // Extract specific data fields
    $event = $data['event'];
    $txRef = $data['data']['tx_ref'];
    $flwRef = $data['data']['flw_ref'];
    $amount = $data['data']['amount'];
    $currency = $data['data']['currency'];
    $status = $data['data']['status'];
    $paymentType = $data['data']['payment_type'];
    $createdAt = $data['data']['created_at'];

    $customerName = $data['data']['customer']['name'];
    $customerPhoneNumber = $data['data']['customer']['phone_number'];
    $customerEmail = $data['data']['customer']['email'];

    $cardType = $data['data']['card']['type'];
    $cardIssuer = $data['data']['card']['issuer'];
    $cardExpiry = $data['data']['card']['expiry'];

    // Now you can use these extracted data fields as needed for your application
// For example, you can update the user's balance in your database based on the transaction status

    // Respond to the webhook to acknowledge receipt
    http_response_code(200);

    if ($status === 'successful') {
        // Perform database update to add the amount to user's balance
        $newBal = $balance + $amount;
        // Your database update logic here

        $type = "Card";

        $updateQuery = "UPDATE funding SET status = :status, type = :type WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
        $updateStmt->execute();

        $updateQuery = "UPDATE users SET balance = :newBal WHERE email = :user_email";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':newBal', $newBal, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_email', $user_email, PDO::PARAM_STR);
        $updateStmt->execute();


        // Respond with a success status to Flutterwave
        http_response_code(200);
    } else {
        // Respond with a status indicating unsuccessful processing
        http_response_code(400);

        $updateQuery = "UPDATE funding SET status = :status, type = :type WHERE trx_ref = :trx_ref";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
        $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
        $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
        $updateStmt->execute();
    }
} else {
    // Respond with a status indicating invalid request
    http_response_code(403);
}

?>