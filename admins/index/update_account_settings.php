<?php
require("settings.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $phoneNumber = $_POST["phoneNumber"];
    $address = $_POST["address"];
    $state = $_POST["state"];
  
    try {
        // Retrieve existing user data from the 'users' table
        $query = "SELECT * FROM users WHERE email = :userEmail";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare form data with existing user data and update if different
        if ($userData) {
            if ($userData["first_name"] !== $firstName ||
                $userData["last_name"] !== $lastName ||
                $userData["phone"] !== $phoneNumber ||
                $userData["address"] !== $address ||
                $userData["state"] !== $state) {

                // Update user data in the 'users' table
                $updateQuery = "UPDATE users SET first_name = :firstName, last_name = :lastName, phone = :phoneNumber, address = :address, state = :state WHERE email = :userEmail";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
                $updateStmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
                $updateStmt->bindParam(':phoneNumber', $phoneNumber, PDO::PARAM_STR);
                $updateStmt->bindParam(':address', $address, PDO::PARAM_STR);
                $updateStmt->bindParam(':state', $state, PDO::PARAM_STR);
                $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
                $updateStmt->execute();

                // Return a success message
                $response = array("success" => true, "message" => "Account settings updated successfully.");
            } else {
                // Return a message indicating no changes were made
                $response = array("success" => true, "message" => "No changes were made.");
            }
        } else {
            // Return an error message if user data not found
            $response = array("success" => false, "message" => "User data not found.");
        }
        
    } catch (PDOException $e) {
        // Return an error message
        $response = array("success" => false, "message" => "An error occurred while updating account settings.", "error" => $e );
    }

    

    // Return a JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $account_type = $_POST["type"];
    
  
    try {
        // Retrieve existing user data from the 'users' table
        $query = "SELECT * FROM users WHERE email = :userEmail";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Compare form data with existing user data and update if different
        if ($userData) {
            if ($userData["type"] !== $account_type) {

                // Update user data in the 'users' table
                $updateQuery = "UPDATE users SET type = :account_type WHERE email = :userEmail";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':type', $account_type, PDO::PARAM_STR);
                $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
                $updateStmt->execute();

                // Return a success message
                $response = array("success" => true, "message" => "Account login mode updated successfully.");
            } else {
                // Return a message indicating no changes were made
                $response = array("success" => true, "message" => "No changes were made.");
            }
        } else {
            // Return an error message if user data not found
            $response = array("success" => false, "message" => "User data not found.");
        }
        
    } catch (PDOException $e) {
        // Return an error message
        $response = array("success" => false, "message" => "An error occurred while updating account settings.", "error" => $e );
    }

    

    // Return a JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    // Return an error message for incorrect request method
    $response = array("success" => false, "message" => "Invalid request method.");
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
