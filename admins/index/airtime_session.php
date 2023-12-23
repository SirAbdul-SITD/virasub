<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["airtime_network"] = $_POST["networkSelect"];
    $_SESSION["airtime_phone"] = $_POST["phoneInput"];
    $_SESSION["airtime_amount"] = $_POST["amountInput"];


    // echo json_encode(array("status" => "success", "message" => "Form inputs saved to airtime_session."));
}
?>
