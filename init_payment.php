<?php
require('settings.php');

try {
    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("network", "plan", "phone", "amount", "email", "first_name", "last_name");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required. [err_code: #2173]");
        }
    }

    // Sanitize and validate the input data
    $network = (int) filter_input(INPUT_POST, 'network', FILTER_SANITIZE_NUMBER_INT);
    $plan = (int) filter_input(INPUT_POST, 'plan', FILTER_SANITIZE_NUMBER_INT);
    $phone = (int) filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
    $amount = (int) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
    $first_name = (string) filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = (string) filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $user_email = (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!$amount || $amount <= 0 || $amount >= 50000) {
        throw new Exception("Invalid amount! [err_code: #8923]");
    }

    // Retrieve other necessary data from your preferred data store
    $tx_ref = 'Funds_' . uniqid();
    $user_name = "$first_name $last_name";
    $amount += $deposit_fee;
    $currency = "NGN";
    $payment_options = "ussd, card";
    $redirect_url = "dashboard/index/index.php";
    $logo = "https://virasub.com.ng/index/favlogo.png";
    $title = "ViraSub";
    $description = "Wallet Funding";

    // Concatenate values for hashing
    $string_to_be_hashed = $amount . $currency . $user_email . $tx_ref . $public_key;

    // Generate payload hash
    $payload_hash = hash('sha256', $string_to_be_hashed);

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
        "payload_hash" => $payload_hash,
    );

    $paymentPayloadJson = json_encode($paymentPayload);
    header('Content-Type: application/json');

    // Insert transaction data into the database
    $type = "USSD";
    $status = "Pending";

    $updateQuery = "INSERT INTO `transactions` (`user`, `type`, `amount`, `status`, `trx_ref`) VALUES (:user, :type, :amount, :status, :trx_ref)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
    $updateStmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
    $updateStmt->execute();

    $type = "USSD";
    $status = "Pending";

    $updateQuery = "INSERT INTO `init_transactions` (`first_name`, `last_name`, `user`, `type`, `amount`, `status`, `phone`, `plan`, `network`, `trx_ref`) VALUES (:first_name, :last_name, :user, :type, :amount, :status, :phone, :plan, :network, :trx_ref)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $updateStmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
    $updateStmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $updateStmt->bindParam(':phone', $phone, PDO::PARAM_INT);
    $updateStmt->bindParam(':plan', $plan, PDO::PARAM_INT);
    $updateStmt->bindParam(':network', $network, PDO::PARAM_INT);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $tx_ref, PDO::PARAM_STR);
    $updateStmt->execute();

    $_SESSION['email'] = $user_email;

    echo trim($paymentPayloadJson);
    exit; // Exit the script after sending JSON response

} catch (Exception $e) {
    // Catch any exceptions that occurred during the API calls or data processing
    // Log or handle errors appropriately
    // sendErrorEmailToAdmin($e);
    echo $e->getMessage();
    exit; // Exit the script after handling the exception
}
?>
