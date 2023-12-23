<?php
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // Retrieve form data
    $otp = $_POST['otp'];
    $email = $_SESSION['otp_requester'];
    $status = "expired";

    // Validate and sanitize the OTP
    $otp = filter_var($otp, FILTER_SANITIZE_NUMBER_INT);

    // Create an array to store the response data
    $response = array();

    if (!$otp || strlen($otp) !== 6) {
        // Invalid OTP format
        $response['success'] = false;
        $response['message'] = 'Invalid OTP format. Please enter a 6-digit OTP.';
    } else {
        // Check if the OTP exists and is still valid (created within the last 8 minutes)
        $now = date("Y-m-d H:i:s");
        $stmt = $pdo->prepare("SELECT * FROM otp WHERE otp = :otp AND create_datetime >= DATE_SUB(NOW(), INTERVAL 8 MINUTE)");
        // $stmt->bindParam(':status', $status);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        $otpRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otpRecord['status'] !== "expired") {
            if ($otpRecord['user'] == $email) {
                // OTP is valid, log the user in (you can perform the login actions here or in otp_login.php)
                $_SESSION['email'] = $otpRecord['user'];

                // Assuming you want to set the status to "expired"

                $query = "UPDATE otp SET status = :status WHERE otp = :otp";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':status', $status); // Fix the variable name here
                $stmt->bindParam(':otp', $otp);
                $stmt->execute();

                $response['success'] = true;
                $response['message'] = 'OTP verified successfully. You are now logged in.';
            } else {
                // Invalid or expired OTP
                $response['success'] = false;
                $response['message'] = 'Invalid or expired OTP. Please check and try again or request a new OTP.';
            }
        } else {
            // Invalid or expired OTP
            $response['success'] = false;
            $response['message'] = 'Invalid or expired OTP. Please check and try again or request a new OTP.';
        }
    }

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>