<?php
require('settings.php');
try {
    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("amount");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required. [err_code: #2173]");
        }
    }

    // Sanitize and validate the input data
    $amounts = (int) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);

    if (!$amounts || $amounts <= 0 || $amounts >= 50000) {
        throw new Exception("Invalid amount! [err_code: #8923]");
    }


    $amount = $amounts + $deposit_fee;


    // Retrieve other necessary data from your preferred data store
    $customer_email = $user_email; // Replace with actual email
    $tx_ref = 'Funds_' . uniqid(); // Replace with actual reference

   


    $currency = "NGN";
    $payment_options = "ussd, card, banktransfer, account";
    $redirect_url = "fund_wallet.php";
    $logo = "https://virasub.com.ng/favlogo.png";
    $title = "ViraSub";
    $description = "Wallet Funding";
    $customizations = array(
        "title" => $title,
        "description" => $description,
        "logo" => $logo,
    );



    $meta = array(
        "name" => $user_name,
    );

    $customer = array(
        "email" => $user_email,
    );

     // Concatenate values for hashing
     $string_to_be_hashed = $amount . $currency . $customer_email . $tx_ref . $secret_key;

     // Generate payload hash
     $payload_hash = hash('sha256', $string_to_be_hashed);


    $paymentPayload = array(
        "public_key" => $public_key,
        "tx_ref" => $tx_ref,
        "amount" => $amount,
        "currency" => $currency,
        "payment_options" => $payment_options,
        "redirect_url" => $redirect_url,
        "meta" => $meta,
        "customer" => $customer,
        "customizations" => $customizations,
        // "payload_hash" => $payload_hash, // Include the payload hash here
    );

    $paymentPayloadJson = json_encode($paymentPayload);
    header('Content-Type: application/json');


    $type = "Bank";
    $status = "Pending";
    $updateQuery = "INSERT INTO `funding` (`user`, `type`, `amount`, `status`, `trx_ref`) VALUES (:user, :type, :amount, :status, :trx_ref)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
    $updateStmt->bindParam(':amount', $amounts, PDO::PARAM_INT);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
    $updateStmt->execute();





    echo trim($paymentPayloadJson);


} catch (Exception $e) {
    // Catch any exceptions that occurred during the API calls or data processing
    sendErrorEmailToAdmin($e);
    echo $e->getMessage();
}
?>