<?php
require('settings.php');

try {
    // Check if all $_POST variables are set and not empty
    $requiredPostVariables = array("disco_name", "meter_type", "meter_number");
    foreach ($requiredPostVariables as $variable) {
        if (!isset($_POST[$variable]) || empty($_POST[$variable])) {
            throw new Exception("All form fields are required.  [err_code: #2073]");
        }
    }

    // Sanitize and validate the input data
    $disco_name = (int) filter_input(INPUT_POST, 'disco_name', FILTER_SANITIZE_NUMBER_INT);
    $meter_type = filter_input(INPUT_POST, 'meter_type', FILTER_SANITIZE_SPECIAL_CHARS);
    $meter_number = filter_input(INPUT_POST, 'meter_number', FILTER_SANITIZE_NUMBER_INT);

    // Validate the input data
    if (!$disco_name || !in_array($disco_name, [1, 2, 3, 4, 5, 6, 7, 8])) {
        throw new Exception("Invalid disco selection!  [err_code: #2493]");
    }

    if (!$meter_type || !in_array($meter_type, ['prepaid', 'postpaid'])) {
        throw new Exception("Invalid meter type selection!  [err_code: #2493]");
    }

    // Validate the meter number if necessary
    // Uncomment the following lines if the meter number needs validation
    /*
    if (!$meter_number || strlen((string) $meter_number) !== 22) {
        throw new Exception("Invalid meter number! [err_code: #0973]");
    }
    */

    $url = 'https://datasub247.com/api/bill/bill-validation';
    $url .= '?meter_number=' . urlencode($meter_number);
    $url .= '&disco=' . urlencode($disco_name);
    $url .= '&meter_type=' . urlencode($meter_type);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Authorization: Token c9518a8f3a778f1524c26830f96f14c6474c0ac30438c18de6aa09a47831",
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if ($errno == CURLE_COULDNT_RESOLVE_HOST) {
            //just a network error on user's end
            throw new Exception("Couldn't complete your request. Please check your internet connection and try again. [err_code: #4672]");
        } else {
            //todo please check whether this is a network issue on the user end
            sendErrorEmailToAdmin($error);
            throw new Exception("Couldn't complete your request at the moment. Please check your internet connection and try again. If the issue persists, try again in 30 minutes. [err_code: #2473]");
        }
    }

    curl_close($ch);

    $responseData = json_decode($response, true);

    // Process the API response
    if ($responseData !== null) {
        // Extract relevant information from the response
        $status = $responseData['status'];
        $name = $responseData['name'];
        $message = $responseData['message'];
        // Display the results to the user
        if ($status === 'success') {
            // Bill payment was successful
            echo "$status \n";
            echo "$name \n";
        } elseif ($status === 'false') {
            echo "$status \n";
            echo "$message \n";
        } else {
            //user input validation failed
            sendErrorEmailToAdmin($message);
            throw new Exception("[err_code: #3323]");
        }
    } else {
        // Error handling if the API call fails
        $error = "The code for processing API calls failed. Please confirm all services are working fine.  [err_code: #3273]";
        sendErrorEmailToAdmin($error);
        throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3743]");
    }
} catch (Exception $e) {
    // Catch any exceptions that occurred during the API calls or data processing
    $error = "New Thrown Exceptions ";
    sendErrorEmailToAdmin($error);
    echo $e->getMessage();
}
?>