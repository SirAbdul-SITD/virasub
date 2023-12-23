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
    if (!$cable_name || !in_array($cable_name, [1, 2, 3,])) {
        throw new Exception("Invalid disco selection!  [err_code: #2493]");
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
        throw new Exception("Invalid meter type selection!  [err_code: #2493]");
    }

    // if (!$IUC || strlen((string) $IUC) !== 22) {
    //     throw new Exception("Invalid meter number! [err_code: #0973]");
    // }



    $payload = array(
        'IUC' => $IUC,
        'disco' => $cable_name,
        'cable_plan' => $cable_plan,
    );
    //250497331
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/cable/cable-validation');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        "Authorization: Token 7d2ba8138c02d5ad579285dc645ff10d1c2630a9f3cbee50db293abdbcf0",
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
        } elseif ($status !== 'success') {
            echo "$status \n";
            echo "$message \n";
        } else {
            //user input validation failed
            sendErrorEmailToAdmin($message);
            throw new Exception("[err_code: #3323] $response");
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
    sendErrorEmailToAdmin($e);
    echo $e->getMessage();
}
?>