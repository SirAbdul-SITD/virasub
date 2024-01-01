<?php

// Get the JSON data
$jsonData = '{"event":"charge.completed","data":{"id":4820873,"tx_ref":"Funds_6592e1b7cbfe5","flw_ref":"flwm3s4m0c1704124874181","device_fingerprint":"0c70c48bf1e4486fd31fb467f152596a","amount":1030,"currency":"NGN","charged_amount":1030,"app_fee":14.42,"merchant_fee":0,"processor_response":"Transaction completed","auth_model":"USSD","ip":"52.209.154.143","narration":"Shinami Information Technology Development ","status":"successful","payment_type":"ussd","created_at":"2024-01-01T16:01:13.000Z","account_id":161061,"customer":{"id":2317751,"name":"Anonymous Customer","phone_number":null,"email":"abdulkarimhussain2@gmail.com","created_at":"2024-01-01T16:01:13.000Z"}},"event.type":"USSD_TRANSACTION"}';

// Define the webhook URL
$webhookUrl = 'https://virasub.com.ng/webhook_handler.php';

// Initialize cURL session
$ch = curl_init($webhookUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL session
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
    // Handle the response
    echo 'Webhook sent successfully. Response: ' . $response;
}

// Close cURL session
curl_close($ch);

?>
