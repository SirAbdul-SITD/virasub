<?php
require('settings.php');
try {
    // Retrieve data from the data_plans table
    $sql = "SELECT * FROM data_plans";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $plansData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $conn = null;

    // Output plansData as JSON
    header('Content-Type: application/json');
    echo json_encode($plansData);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>