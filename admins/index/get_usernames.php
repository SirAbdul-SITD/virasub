<?php
require("settings.php");


// Handle the AJAX request
if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    // Use PDO to fetch user data based on the search term
    $query = "SELECT email FROM users WHERE email LIKE :term";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':term', "%$searchTerm%", PDO::PARAM_STR);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the user data as JSON
    header('Content-Type: application/json');
    echo json_encode($users);
} else {
    // Handle other requests or provide an error response
}
?>