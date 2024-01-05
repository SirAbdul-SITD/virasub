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
    $amount = (float) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($amount <= 0 || $amount >= 50000) {
        throw new Exception("Invalid amount! [err_code: #8923]");
    }

    // Calculate the total amount (including deposit fee)
    $totalAmount = $amount + $deposit_fee;

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
        "name" => $user_name, // Ensure $user_name is defined before using it
    );

    $customer = array(
        "email" => $user_email, // Ensure $user_email is defined before using it
    );

    // Concatenate values for hashing
    $string_to_be_hashed = $totalAmount . $currency . $customer_email . $tx_ref . $public_key;

    // Generate payload hash
    $payload_hash = hash('sha256', $string_to_be_hashed);

    $paymentPayload = array(
        "public_key" => $public_key,
        "tx_ref" => $tx_ref,
        "amount" => $totalAmount, // Use totalAmount instead of amount
        "currency" => $currency,
        "payment_options" => $payment_options,
        "redirect_url" => $redirect_url,
        "meta" => $meta,
        "customer" => $customer,
        "customizations" => $customizations,
        "payload_hash" => $payload_hash, // Include the payload hash here
    );

    $paymentPayloadJson = json_encode($paymentPayload);
    header('Content-Type: application/json');

    $type = "Bank";
    $status = "Pending";

    // Insert into the funding table
    $updateQuery = "INSERT INTO `funding` (`user`, `type`, `amount`, `status`, `trx_ref`) VALUES (:user, :type, :amount, :status, :trx_ref)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
    $updateStmt->bindParam(':amount', $totalAmount, PDO::PARAM_INT); // Use totalAmount instead of amount
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
