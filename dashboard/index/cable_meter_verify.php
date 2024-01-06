<?php
require('settings.php');

try {
    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("cable_name", "cable_plan", "IUC");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required.  [err_code: #2073]");
        }
    }

    // Sanitize and validate the input data
    $cable_name = (int) filter_input(INPUT_POST, 'cable_name', FILTER_SANITIZE_NUMBER_INT);
    $cable_plan = filter_input(INPUT_POST, 'cable_plan', FILTER_SANITIZE_SPECIAL_CHARS);
    $IUC = filter_input(INPUT_POST, 'IUC', FILTER_SANITIZE_NUMBER_INT);

    // Validate the input data
    if (!$cable_name || !in_array($cable_name, [1, 2, 3])) {
        throw new Exception("Invalid cable selection!  [err_code: #2493]");
    }

    if (!$cable_plan || !in_array($cable_plan, [
        1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
        11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
        21, 22, 23, 24, 25, 26, 27, 28, 29, 30,
        31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
        41, 42, 43, 44, 45, 46, 47, 48, 49, 50,
        51, 52, 53, 54, 55, 56, 57, 58, 59, 60,
        61, 62, 63, 64, 65, 66, 67, 68, 69, 70,
        71, 72, 73, 74, 75, 76, 77, 78, 79, 80,
        81, 82, 83, 84, 85, 86
    ])) {
        throw new Exception("Invalid cable plan selection!  [err_code: #2493]");
    }

    $payload = array(
        'IUC' => $IUC,
        'disco' => $cable_name,
        'cable_plan' => $cable_plan,
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/cable/cable-validation');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Authorization: Token c9518a8f3a778f1524c26830f96f14c6474c0ac30438c18de6aa09a47831",
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    $error = curl_error($ch);
    $errno = curl_errno($ch);

    curl_close($ch);

    if ($response === false) {
        if (strpos($error, "Couldn't resolve host") === false) {
            sendErrorEmailToAdmin($error);
            throw new Exception("Couldn't complete your request at the moment. Please check your internet connection and try again. If the issue persists, try again in 30 minutes. [err_code: #2473]");
        } else {
            throw new Exception("Couldn't complete your request. Please check your internet connection and try again. [err_code: #4672]");
        }
    }

    if ($response) {
        $responseData = json_decode($response, true);

        $status = $responseData['status'];
        $name = $responseData['name'];
        $message = $responseData['message'];

        if ($status === 'success') {
            echo "$status \n";
            echo "$name \n";
        } else {
            echo "$status \n";
            echo "$message \n";
        }
    } else {
        $error = "The code for processing API calls failed. Please confirm all services are working fine.  [err_code: #3273]";
        sendErrorEmailToAdmin($error);
        throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3743]");
    }
} catch (Exception $e) {
    sendErrorEmailToAdmin($e);
    echo $e->getMessage();
}
?>
