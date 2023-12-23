<?php
require("settings.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $user_email = $_POST["user_email"];
    $action = $_POST["action"];
    

    if ($action == "activate") {
        try {
            // Retrieve existing user data from the 'users' table
            $query = "SELECT * FROM users WHERE email = :userEmail";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Compare form data with existing user data and update if different
            if ($userData) {
                // Update user status in the 'users' table
                $status = "Active";
                $updateQuery = "UPDATE users SET status = :status WHERE email = :userEmail";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':status', $status, PDO::PARAM_STR); // Bind the status separately
                $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR); // Bind the userEmail
                $updateStmt->execute();
        
                // Return a success message
                $response = array("success" => true, "message" => "Account activated successfully.");
            } else {
                // Return an error message if user data not found
                $response = array("success" => false, "message" => "User not found.");
            }
        } catch (PDOException $e) {
            // Return an error message
            $response = array("success" => false, "message" => "An error occurred while updating account settings.", "error" => $e );
        }
    } elseif ($action == "deactivate") {
        try {
            // Retrieve existing user data from the 'users' table
            $query = "SELECT * FROM users WHERE email = :userEmail";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
            $stmt->execute();
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Compare form data with existing user data and update if different
            if ($userData) {
                // Update user status in the 'users' table
                $status = "Deactivated";
                $updateQuery = "UPDATE users SET status = :status WHERE email = :userEmail";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':status', $status, PDO::PARAM_STR); // Bind the status separately
                $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR); // Bind the userEmail
                $updateStmt->execute();
        
                // Return a success message
                $response = array("success" => true, "message" => "Account deactivated successfully.");
            } else {
                // Return an error message if user data not found
                $response = array("success" => false, "message" => "User not found.");
            }
        } catch (PDOException $e) {
            // Return an error message
            $response = array("success" => false, "message" => "An error occurred while updating account settings.", "error" => $e );
        }
    }

    // Return a JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>