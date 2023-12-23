<?php
require("settings.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];

    try {
       
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->execute([$user_email]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($oldPassword, $user['password'])) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);


            // Update the password in the database
            $query = "UPDATE users SET password = :hashedPassword WHERE email = :userEmail";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':hashedPassword', $hashedNewPassword, PDO::PARAM_STR);
            $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
            $stmt->execute();

            $response = array("success" => true, "message" => "Password changed successfully.");
        } else {
            // Return a message indicating that the old password is incorrect
            $response = array("success" => false, "message" => "Incorrect Old Password. Please check and try again");
        }
    } catch (PDOException $e) {
        // Return an error message
        $response = array("success" => false, "message" => "An error occurred while updating account settings.", "error" => $e);
    }

    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "Invalid request method.");
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>