<?php
// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save the form inputs to PHP session
    $_SESSION["disco_name"] = $_POST["disco_name"];
    $_SESSION["meter_type"] = $_POST["meter_type"];
    $_SESSION["meter_number"] = $_POST["meter_number"];
    $_SESSION["amount"] = $_POST["amount"];


    // Respond with a success message (You can customize this response as needed)
    echo json_encode(array("status" => "success", "message" => "Form inputs saved to session."));
}
?>
