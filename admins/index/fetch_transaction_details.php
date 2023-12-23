<?php
require("settings.php");

if (isset($_GET['trx_ref'])) {
  $trxRef = $_GET['trx_ref'];
  $query = "SELECT * FROM transactions WHERE trx_ref = :trx_ref";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':trx_ref', $trxRef, PDO::PARAM_STR);
  $stmt->execute();
  $transactionDetails = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($transactionDetails) {
    // Return the transaction details as JSON
    echo json_encode($transactionDetails);
  }
}
?>
