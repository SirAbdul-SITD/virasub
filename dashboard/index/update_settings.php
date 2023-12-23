<?php
require("settings.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            if ($userData["password"]) {

                // Update user data in the 'users' table
                $updateQuery = "UPDATE users SET type = :type WHERE email = :userEmail";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':type', $account_type, PDO::PARAM_STR);
                $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
                $updateStmt->execute();

                // Return a success message
                $response = array("success" => true, "message" => "Account login mode updated successfully.");
            } else {
                // Return a message indicating no changes were made
                $response = array("success" => false, "message" => "Please set a password first before changing this settings.");
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
