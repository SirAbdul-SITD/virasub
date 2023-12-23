<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $email = $_POST['email'];
    $subject = "Password Reset";
    $message = "Here is your password reset link: https://example.com/reset_password.php";
    $headers = "From: your_email@example.com";

    // Send the email
    $success = mail($email, $subject, $message, $headers);

    if ($success) {
        $response['success'] = true;
        $response['message'] = 'Password reset link sent successfully.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to send the password reset link. Please contact the administrator.';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
