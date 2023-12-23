<?php
require('settings.php');


try {
    // ... (Input validation and sanitization)
    $requiredPostVariables = array("amount", "userInput");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required. [err_code: #2173]");
        }
    }

    // Sanitize and validate the input data
    $amount = (int) filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_INT);
    $user_email = (string) filter_input(INPUT_POST, 'userInput', FILTER_SANITIZE_EMAIL);

    if (!$amount || $amount <= 0 || $amount >= 50000) {
        throw new Exception("Invalid amount! [err_code: #8923]");
    }

    // Check if the user email exists in the users table
    $checkUserQuery = "SELECT id, first_name, last_name FROM users WHERE email = :user_email";
    $checkUserStmt = $pdo->prepare($checkUserQuery);
    $checkUserStmt->bindParam(':user_email', $user_email, PDO::PARAM_STR);
    $checkUserStmt->execute();
    $userInfo = $checkUserStmt->fetch(PDO::FETCH_ASSOC);

    if ($userInfo === false) {
        throw new Exception("User not found. [err_code: #4821]");
    } else {
        $name = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
    }


    // Start a database transaction
    $pdo->beginTransaction();

    $trx_ref = 'Funds_' . uniqid();
    $type = "Admin Funding";
    $status = "Completed";
    $narration = "Account funded by admin";
    $updateQuery = "INSERT INTO `funding` (`user`, `type`, `amount`, `status`, `narration`, `trx_ref`) VALUES (:user, :type, :amount, :status, :narration, :trx_ref)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':user', $user_email, PDO::PARAM_STR);
    $updateStmt->bindParam(':type', $type, PDO::PARAM_STR);
    $updateStmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
    $updateStmt->bindParam(':narration', $narration, PDO::PARAM_STR);
    $updateStmt->bindParam(':trx_ref', $trx_ref, PDO::PARAM_STR);
    $updateStmt->execute();

    $updateBalanceQuery = "UPDATE users SET balance = balance + :amount WHERE email = :userEmail";
    $updateBalanceStmt = $pdo->prepare($updateBalanceQuery);
    $updateBalanceStmt->bindParam(':amount', $amount, PDO::PARAM_INT);
    $updateBalanceStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
    $updateBalanceStmt->execute();

    $pdo->commit();

    header('Content-Type: application/json');
    echo json_encode(array('name' => $name, 'email' => $user_email, 'amount' => $amount));
} catch (Exception $e) {
    sendErrorEmailToAdmin($e);

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    header('Content-Type: application/json');
    echo json_encode(array('error' => $e->getMessage()));
}

?>