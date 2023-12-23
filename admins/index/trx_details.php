<?php
// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST["type"] == "Airtime") {
        # code...

        echo json_encode(array(
            "type" => "Airtime",
            "id" => "airtime_o0397489y87y",
            "network" => "MTN",
            "amount" => "100",
            "number" => "09039904194",
            "date" => "10-12-2023",
            "time" => "10:23 am",
            "status" => "success",
        ));
    } elseif ($_POST["type"] == "Data") {
        # code...
    } elseif ($_POST["type"] == "Cable") {

    } elseif ($_POST["type"] == "Utility") {

    }

    // Save the form inputs to PHP session
    $_SESSION["disco_name"] = $_POST["disco_name"];
    $_SESSION["meter_type"] = $_POST["meter_type"];
    $_SESSION["meter_number"] = $_POST["meter_number"];
    $_SESSION["amount"] = $_POST["amount"];


    // Respond with a success message (You can customize this response as needed)

}
?>