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

    // if (!$meter_number || strlen((string) $meter_number) !== 22) {
    //     throw new Exception("Invalid meter number! [err_code: #0973]");
    // }



    $payload = array(
        'meter_number' => $meter_number,
        'disco' => $disco_name,
        'meter_type' => $meter_type,
    );
    //250497331
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/bill/bill-validation');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Authorization: Token c9518a8f3a778f1524c26830f96f14c6474c0ac30438c18de6aa09a47831",
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);

    curl_close($ch);

    if ($response === false) {
        $error = curl_error($ch);
        if (strpos($error, "Couldn't resolve host") === false) {
            ///todo please check whether this is a network issue on the user end
            sendErrorEmailToAdmin($error);
            throw new Exception("Couldn't complete your request at the moment, Please check your internet connection and try again, If issue persists try again in in 30 minutes. [err_code: #2473]");
        } else {
            //just a network error on user's end
            throw new Exception("Couldn't complete your request. Please check your internet connection and try again. [err_code: #4672]");
        }
    }

    $responseData = json_decode($response, true);
    if ($responseData !== null) {

    }




    // Process the API response
    if ($response) {
        $responseData = json_decode($response, true);



        // Extract relevant information from the response
        $status = $responseData['status'];
        $name = $responseData['name'];
        $message = $responseData['message'];

        //demo data dump

        // $status = 'success';
        // $name = 'Abduljbar Abdulmalik';
        // $message = 'Meter Details verified!';


        // Display the results to the user
        if ($status === 'success') {
            // Bill payment was successful

            echo "$status \n";
            echo "$name \n";

            //handle error messages
        } elseif ($status === 'false') {
            echo "$status \n";
            echo "$message \n";
        } else {
            //user input validation failed
            sendErrorEmailToAdmin($message);
            echo "$message \n";
            echo "$message \n";
            throw new Exception("[err_code: #3323]");
        }

    } else {
        // Error handling if the API call fails
        $error = "The code for processing API calls failed, please confirm all services are working fine.  [err_code: #3273]";
        sendErrorEmailToAdmin($error);
        throw new Exception("Couldn't complete your request. Please check your internet connection or try again in 30 minutes. [err_code: #3743]");
        ;
    }
} catch (Exception $e) {
    // Catch any exceptions that occurred during the API calls or data processing
    $error = "New Thrown Exceptions ";
    sendErrorEmailToAdmin($error);
    echo $e->getMessage();
}
?>