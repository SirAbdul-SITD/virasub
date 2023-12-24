<?php
require("config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {


    $trx_ref = filter_input(INPUT_POST, 'trx_ref', FILTER_VALIDATE_STRING);
    // $trx_ref = $_POST['trx_ref'];
    $response = array();

    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE trx_ref = ?");
    $stmt->execute([$trx_ref]);
    $transactions = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($transactions) {

        $email = $transactions['email'];
        $type = $transactions['type'];
        $date = $transactions['date'];
        $dated = date("j F, Y", strtotime($date));
        $time = date("H:i", strtotime($date));

        if ($type == "Airtime") {
                
                $network = $transactions['network'];
                $date = $transactions['date'];
                $phone = $transactions['phone'];
                
        
                $to = $email;
                $subject = "Receipt from ViraSub";
                $message = file_get_contents("send_receipt_airtime.html");
                $message = str_replace("{email}", $email, $message);
                $message = str_replace("{trx_ref}", $trx_ref, $message);
                $message = str_replace("{network}", $network, $message);
                $message = str_replace("{phone}", $phone, $message);
                $message = str_replace("{date}", $dated, $message);
                $message = str_replace("{time}", $time, $message);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: noreply@billzwave.com.ng' . "\r\n";
                mail($to, $subject, $message, $headers);
                
                $_SESSION['otp_requester'] = $email;

                $response['success'] = true;
                $response['message'] = 'Receipt sent successfully.'; 

        } elseif ($type == "Data") {

            $network = $transactions['network'];
            $phone = $transactions['phone'];
            $quantity = $transactions['quantity'];
            $amount = $transactions['amount'];

                
                $to = $email;
                $subject = "Receipt from ViraSub";
                $message = file_get_contents("send_receipt_data.html");
                $message = str_replace("{email}", $email, $message);
                $message = str_replace("{trx_ref}", $trx_ref, $message);
                $message = str_replace("{network}", $network, $message);
                $message = str_replace("{phone}", $phone, $message);
                $message = str_replace("{quantity}", $quantity, $message);
                $message = str_replace("{amount}", $amount, $message);
                $message = str_replace("{date}", $dated, $message);
                $message = str_replace("{time}", $time, $message);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: noreply@billzwave.com.ng' . "\r\n";
                mail($to, $subject, $message, $headers);
                

                $response['success'] = true;
                $response['message'] = 'Receipt sent successfully.'; 

        } elseif ($type == "Cable") {

                $disco = $transactions['disco'];
                $iuc = $transactions['iuc'];
                $name = $transactions['name'];
                $plan = $transactions['plan'];
                $amount = $transactions['amount'];
                $fee = $transactions['fee'];
                $total = $amount + $fee;
                $plan_amount = "$plan || NGN $amount";
    
        
        
                
                $to = $email;
                $subject = "Receipt from ViraSub";
                $message = file_get_contents("send_receipt_cable.html");
                $message = str_replace("{email}", $email, $message);
                $message = str_replace("{trx_ref}", $trx_ref, $message);
                $message = str_replace("{disco}", $disco, $message);
                $message = str_replace("{iuc}", $iuc, $message);
                $message = str_replace("{name}", $name, $message);
                $message = str_replace("{plan_amount}", $plan_amount, $message);
                $message = str_replace("{fee}", $fee, $message);
                $message = str_replace("{total}", $total, $message);
                $message = str_replace("{date}", $dated, $message);
                $message = str_replace("{time}", $time, $message);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: noreply@billzwave.com.ng' . "\r\n";
                mail($to, $subject, $message, $headers);
                

                $response['success'] = true;
                $response['message'] = 'Receipt sent successfully.'; 

        } elseif ($type == "Utility") {

                $distro = $transactions['distro'];
                $meter = $transactions['meter'];
                $name = $transactions['name'];
                $amount = $transactions['amount'];
                $fee = $transactions['fee'];
        
                $total = $amount + $fee;
        
        
                
                $to = $email;
                $subject = "Receipt from ViraSub";
                $message = file_get_contents("send_receipt_utility.html");
                $message = str_replace("{email}", $email, $message);
                $message = str_replace("{trx_ref}", $trx_ref, $message);
                $message = str_replace("{disco}", $distro, $message);
                $message = str_replace("{meter}", $meter, $message);
                $message = str_replace("{name}", $name, $message);
                $message = str_replace("{amount}", $amount, $message);
                $message = str_replace("{fee}", $fee, $message);
                $message = str_replace("{total}", $total, $message);
                $message = str_replace("{date}", $dated, $message);
                $message = str_replace("{time}", $time, $message);
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: noreply@billzwave.com.ng' . "\r\n";
                mail($to, $subject, $message, $headers);
                
                $_SESSION['otp_requester'] = $email;

                $response['success'] = true;
                $response['message'] = 'Receipt sent successfully.'; 

        }


    } else {
        $response['success'] = false;
        $response['message'] = 'Invalid Transaction. Please check and try again';
    }


    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} else {
    echo "hepless";
}
?>
