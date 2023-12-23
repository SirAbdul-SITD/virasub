<?php
// Start or resume the session
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save the form inputs to PHP session
    $_SESSION["data_network"] = $_POST["data_network"];
    $_SESSION["data_plan"] = $_POST["data_plan"];
    $_SESSION["data_phone"] = $_POST["data_phone"];


    // Respond with a success message (You can customize this response as needed)
    echo json_encode(array("status" => "success", "message" => "Form inputs saved to session."));
}
?>
