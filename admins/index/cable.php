<?php
require('settings.php');



if (isset($_GET['user'])) {
  $_SESSION["userActivity"] = $_GET["user"];
  $user_email = $_SESSION["userActivity"];
} elseif (isset($_SESSION['userActivity'])) {
  $user_email = $_SESSION["userActivity"];
} else {
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}

if (isset($_GET['show_details'])) {
  $trx_ref = $_GET['show_details'];

  // Fetch the transaction details using the $transactionId
  // Modify your database query to fetch details based on the $transactionId

  // Example:
  $query = "SELECT * FROM transactions WHERE trx_ref = :trx_ref";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':trx_ref', $trx_ref, PDO::PARAM_STR);
  $stmt->execute();
  $transactionDetails = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($transactionDetails) {
    $_type = $transactionDetails["type"];
    $_user = $transactionDetails["user"];
    $_amount = $transactionDetails["amount"];
    $_date = $transactionDetails["date"];
    $_status = $transactionDetails["status"];
  }
}
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Cable Subscribe | ViraSub</title>

  <meta name="description" content="Buy airtime at a cheap rate with amazing cableunts" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="favlogo.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />


  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>


  <style>
    #loadingScreen {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      display: none;
      background-color: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 20px;
      border-radius: 8px;
      z-index: 9999;
    }

    #balanceText {
      font-size: 1.2vw;
      color: green;
    }

    /* Media query for screens with maximum width of 768px (typical mobile screens) */
    @media (max-width: 768px) {
      #balanceText {
        font-size: 3vw;
      }
    }
  </style>

</head>

<body>

  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="index.php" class="app-brand-link">
            <span> <img src="favlogo.png" style="width: 20px; margin-right:0px;"> </span>
            <span style="margin-left:0px;" class="app-brand-text demo menu-text fw-bolder ms-2">ira<span
                style="color: #fB9149">w</span style="color: #14A39A">ave</span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- General -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">General</span></li>
          <li class="menu-item">
            <a href="index.php" class="menu-link">
              <i> <img src="icons/home.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>

          <li class="menu-item">
            <a href="airtime.php" class="menu-link">
              <i> <img src="icons/airtime.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Buy Airtime</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="data.php" class="menu-link">
              <i> <img src="icons/data.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Buy Data</div>
            </a>
          </li>
          <li class="menu-item active">
            <a href="cable.php" class="menu-link">
              <i> <img src="icons/cable.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Cable Sub</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="bill.php" class="menu-link">
              <i> <img src="icons/utility.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Utility Bills</div>
            </a>
          </li>
          <!-- Management -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>
          <li class="menu-item">
            <a href="fund_wallet.php" class="menu-link">
              <i> <img src="icons/wallet.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Fund Wallet">Fund Wallet</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i> <img src="icons/histories.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="History">History</div>
            </a>

            <ul class="menu-sub">
              <li class="menu-item">
                <a href="transactions.php" class="menu-link">
                  <i> <img src="icons/history.png" style="width: 20px; margin-right: 10px;"> </i>
                  <div data-i18n="Transactions">Transactions</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="fundings.php" class="menu-link">
                  <i> <img src="icons/wallet_history.png" style="width: 20px; margin-right: 10px;"> </i>
                  <div data-i18n="Transaction History">Wallet History</div>
                </a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="settings_page.php" class="menu-link">
              <i> <img src="icons/settings.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Settings">Account Settings</div>
            </a>
          </li>
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->
        <nav
          class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search Transactions..."
                  aria-label="Search Transactions..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->
              <li class="nav-item lh-1 me-3">
                <?php echo $first_name; ?>
              </li>

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="icons/user.jpg" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="icons/user.jpg" alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">
                            <?php echo $user_name; ?>
                          </span>
                          <small class="text-muted">Balance: ₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="account.php">
                      <i class="bx bx-user me-2"></i>
                      <span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="settings_page.php">
                      <i class="bx bx-cog me-2"></i>
                      <span class="align-middle">Settings</span>
                    </a>
                  </li>

                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="auth/logout.php">
                      <i class="bx bx-power-off me-2"></i>
                      <span class="align-middle">Log Out</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->
          <div class="container-fluid flex-grow-1 container-p-y">

            <div id="loadingScreen">Loading...</div>

            <div class="row">

              <!-- Input Sizing -->
              <div class="col-md-6 col-lg-6 order-2 mb-4" style="position: relative;">
                <div class="conta" style="position: sticky; top: 15px;">
                  <div class="card mb-7">
                    <h5 class="card-header">Cable Sub</h5>
                    <div class="card-body">
                      <form id="cablePaymentForm">
                        <div class="mt-3 mb-3">
                          <select id="cableSelect" name="cable_name" class="form-select form-select-lg"
                            onchange="loadPlans()">
                            <option value="" disabled selected>Select Provider</option>
                            <option value="1">GOTV</option>
                            <option value="2">DSTV</option>
                            <option value="3">STARTIMES</option>
                          </select>
                        </div>

                        <!-- Second Select (Plan) -->
                        <div class="mt-2 mb-3">
                          <select id="planSelect" name="cable_plan" class="form-select form-select-lg">
                            <option value="" disabled selected>Select Plan</option>
                          </select>
                        </div>

                        <div class="mt-2 mb-3">
                          <input id="IUC_NO" name="IUC" class="form-control form-control-lg" type="tel"
                            placeholder="IUC" />
                        </div>

                        <div style="display: flex;">
                          <p id="balanceText" style="font-size: small;">Balance: ₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </p>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" id="amount" name="amount" value="">
                        <input type="hidden" name="page" value="cable.php">
                        <button id="verifyButton" class="btn btn-warning btn-lg" type="button" style="min-width: 100%;">
                          Verify Cable details
                        </button>
                      </form>


                    </div>
                  </div>
                </div>
              </div>


              <!-- Details Models -->

              <!-- Airtime Admin Purchase History -->
              <?php if ($_type == "Airtime") { ?>
                <div class="modal fade" id="Airtime_details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="text-align: center">
                      <div class="modal-body">
                        <strong>Airtime Purchase Details</strong>
                        <table class="table">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>User:</th>
                            <td id="receipt-user">
                              <?php echo $_user; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Trx Ref:</th>
                            <td>
                              <?php echo $trx_ref; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Network:</th>
                            <td>
                              <?php echo $_network; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Phone Number:</th>
                            <td>
                              <?php echo $_number; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Amount:</th>
                            <td>₦
                              <?php echo $_amount; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Date:</th>
                            <td>
                              <?php $dt = date("j F, Y", strtotime($_date));
                              echo $dt; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Time:</th>
                            <td>
                              <?php $time = date("H:i", strtotime($_date));
                              echo $time;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Status:</th>
                            <td style="color: <?= $_status == "Completed" ? "green" : "red" ?>">
                              <?php echo $_status; ?>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                        <button type="button" id="close-model" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                          style="min-width:45%">Close</button>
                        <button type="button" class="btn btn-warning" id="send-receipt-button" style="min-width:45%"> <i
                            class="bx bx-send me-1"></i> Send Receipt</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  $(document).ready(function () {
                    $("#Airtime_details").modal("show");
                  });
                </script>
              <?php } ?>

              <!-- Data -->
              <?php if ($_type == "Data") { ?>
                <div class="modal fade" id="Data_details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="text-align: center">
                      <div class="modal-body">
                        <strong>Data Purchase Details</strong>
                        <table class="table">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>User:</th>
                            <td>
                              <?php echo $_user; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Trx Ref:</th>
                            <td>
                              <?php echo $trx_ref; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Network:</th>
                            <td>
                              <?php echo $_network; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Phone Number:</th>
                            <td>
                              <?php echo $_number; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Quantity:</th>
                            <td>
                              <?php echo $_data; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Amount:</th>
                            <td>₦
                              <?php echo $_amount; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Date:</th>
                            <td>
                              <?php $dt = date("j F, Y", strtotime($_date));
                              echo $dt; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Time:</th>
                            <td>
                              <?php $time = date("H:i", strtotime($_date));
                              echo $time;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Status:</th>
                            <td style="color: <?= $_status == "Completed" ? "green" : "red" ?>">
                              <?php echo $_status; ?>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                        <button type="button" id="close-model" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                          style="min-width:45%">Close</button>
                        <button type="button" class="btn btn-warning" id="" style="min-width:45%"> <i
                            class="bx bx-send me-1"></i> Send Receipt</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  $(document).ready(function () {
                    $("#Data_details").modal("show");
                  });
                </script>
              <?php } ?>

              <!-- Cable -->
              <?php if ($_type == "Cable") { ?>
                <div class="modal fade" id="Cable_details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="text-align: center">
                      <div class="modal-body">
                        <strong>Cable Subscription Details</strong>
                        <table class="table">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>User:</th>
                            <td>
                              <?php echo $_user; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Trx Ref:</th>
                            <td>
                              <?php echo $trx_ref; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Disco:</th>
                            <td>
                              <?php echo $_disco; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>IUC:</th>
                            <td>
                              <?php echo $_iuc; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Name:</th>
                            <td>
                              <?php echo $_name; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Plan:</th>
                            <td>
                              <?php echo $_plan . ' / ';
                              echo '₦ ' . $_amount; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Fee:</th>
                            <td>₦
                              <?php echo $_fee; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Date:</th>
                            <td>
                              <?php $dt = date("j F, Y", strtotime($_date));
                              echo $dt; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Time:</th>
                            <td>
                              <?php $time = date("H:i", strtotime($_date));
                              echo $time;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Status:</th>
                            <td style="color: <?= $_status == "Completed" ? "green" : "red" ?>">
                              <?php echo $_status; ?>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                        <button type="button" id="close-model" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                          style="min-width:45%">Close</button>
                        <button type="button" class="btn btn-warning" id="" style="min-width:45%"> <i
                            class="bx bx-send me-1"></i> Send Receipt</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  $(document).ready(function () {
                    $("#Cable_details").modal("show");
                  });
                </script>
              <?php } ?>

              <!-- Uility -->
              <?php if ($_type == "Utility") { ?>
                <div class="modal fade" id="Utility_details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="text-align: center">
                      <div class="modal-body">
                        <strong>Utility Payment Details</strong>
                        <table class="table">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>User:</th>
                            <td>
                              <?php echo $_user; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Trx Ref:</th>
                            <td>
                              <?php echo $trx_ref; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Distro:</th>
                            <td>
                              <?php echo $_distro; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Meter NO:</th>
                            <td>
                              <?php echo $_meter; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Name:</th>
                            <td>
                              <?php echo $_name; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Amount:</th>
                            <td>
                              <?php
                              echo '₦ ' . $_amount; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Fee:</th>
                            <td>₦
                              <?php echo $_fee; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Date:</th>
                            <td>
                              <?php $dt = date("j F, Y", strtotime($_date));
                              echo $dt; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Time:</th>
                            <td>
                              <?php $time = date("H:i", strtotime($_date));
                              echo $time;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Status:</th>
                            <td style="color: <?= $_status == "Completed" ? "green" : "red" ?>">
                              <?php echo $_status; ?>
                            </td>
                          </tr>
                        </table>
                      </div>
                      <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                        <button type="button" id="close-model" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                          style="min-width:45%">Close</button>
                        <button type="button" class="btn btn-warning" id="" style="min-width:45%"> <i
                            class="bx bx-send me-1"></i> Send Receipt</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  $(document).ready(function () {
                    $("#Utility_details").modal("show");
                  });
                </script>
              <?php } ?>



              <!-- Transactions History -->
              <div class="col-md-6 col-lg-6 order-2 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2" style="color: black">Transactions</h5>
                  </div>
                  <div class="card-body">
                    <?php

                    $admin = True;
                    $tr_type = "Cable";
                    $query = "SELECT * FROM transactions WHERE admin = :admin AND type = :tr_type ORDER BY date DESC";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':admin', $admin, PDO::PARAM_STR);
                    $stmt->bindParam(':tr_type', $tr_type, PDO::PARAM_STR);
                    $stmt->execute();
                    $transactData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($transactData) === 0) {
                      echo '<p class="text-center">No Data Yet!</p>';
                    } else {
                      // Define the number of results to display per page for transactions
                      $resultsPerTransactionPage = 20;

                      $trxpage = isset($_GET['trxpage']) ? max(1, min((int) $_GET['trxpage'], ceil(count($transactData) / $resultsPerTransactionPage))) : 1;

                      // Calculate the start and end indices for the current page for transactions
                      $startIndex = ($trxpage - 1) * $resultsPerTransactionPage;
                      $endIndex = min($startIndex + $resultsPerTransactionPage, count($transactData));

                      // Display the results for the current page for transactions
                      echo '<ul class="p-0 m-0" id="transactionList">';
                      for ($i = $startIndex; $i < $endIndex; $i++) {
                        $transact = $transactData[$i];
                        $mode = $transact['type'];
                        $trxref = $transact['trx_ref'];
                        $status = $transact['status'];
                        $date = date("j F, Y", strtotime($transact['date']));
                        $amount = "-{$transact['amount']}";
                        $iconClass = '';
                        $modeSwitch = '';

                        if ($mode === 'Data') {
                          $modeSwitch = "Data Purchase";
                          $iconClass = "icons/data.png";
                        } elseif ($mode === 'Cable') {
                          $modeSwitch = "Cable TV Subscription";
                          $iconClass = 'icons/cable.png';
                        } elseif ($mode === 'Utility') {
                          $modeSwitch = "Electricity Bill Payment";
                          $iconClass = 'icons/utility.png';
                        } elseif ($mode === 'Airtime') {
                          $modeSwitch = "Airtime Purchase";
                          $iconClass = 'icons/airtime.png';
                        }

                        if ($status === 'Failed') {
                          $amount = "{$transact['amount']}";
                        }
                        echo '<a href="?show_details=' . $trxref . '">
                                        <li class="d-flex mb-4 pb-1">
                                        <div class="avatar flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded bg-label-primary"><i> <img src="' . $iconClass . '"
                                                style="width:20px"> </i></span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <small class="text-muted d-block mb-1 bl">
                                                    ' . ($i + 1) . '. ' . $modeSwitch . '
                                                </small>
                                                <h6 class="mb-0">
                                                    ' . $date . '
                                                </h6>
                                            </div>
                                            <div class="user-progress d-flex align-items-center gap-1">
                                                <h6 class="mb-0" style="color: ' . ($status === "Failed" ? "red" : "green") . '">
                                                    ' . $amount . '
                                                </h6>
                                                <span style="color: black">' . ($status === "Failed" ? "Failed" : "NGN") . '</span>
                                            </div>
                                        </div>
                                    </li>
                                    </a>';
                      }
                      if (count($transactData) > $resultsPerTransactionPage) {
                        echo '<p class="text-center"> _-. _-. _- P a g e s -_ .-_ .-_</p>';
                      }
                      echo '</ul>';

                      // Display pagination links for transactions
                      $totalPages = ceil(count($transactData) / $resultsPerTransactionPage);
                      // ... (pagination code for transactions)
                      echo '<ul class="pagination" style="justify-content: right;">';
                      if ($trxpage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?trxpage=1">1</a></li>';
                        if ($trxpage > 2) {
                          echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                      }
                      $range = 1;
                      $startLink = max(2, $trxpage - $range);
                      $endLink = min($trxpage + $range, $totalPages - 1);
                      for ($i = $startLink; $i <= $endLink; $i++) {
                        echo '<li class="page-item ';
                        echo $trxpage == $i ? 'active' : '';
                        echo '"><a class="page-link" href="?trxpage=' . $i . '">' . $i . '</a></li>';
                      }
                      if ($trxpage < $totalPages - $range - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                      }
                      if ($trxpage < $totalPages - $range) {
                        echo '<li class="page-item"><a class="page-link" href="?trxpage=' . $totalPages . '">' . $totalPages . '</a></li>';
                      }
                      echo '</ul>';
                    }
                    ?>
                  </div>
                </div>
              </div>

              <!-- / Transactions history end -->
            </div>




            <!-- USSD Model (success)-->
            <div class="modal fade" id="modelUSSD" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Your cable details where verified! Please confirm the below information before proceeding
                      proceed.</p>
                    <div style="display: flex;">
                      <b>Name:</b>
                      <p id="Subscriber"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Cable:</b>
                      <p id="cable_name"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Plan:</b>
                      <p id="cable_plan"></p>
                    </div>
                    <div style="display: flex;">
                      <b>IUC:</b>
                      <p id="IUC"></p>
                    </div>
                    <p style="color: red; font-size: small;">Looks like you have low balance to make this purchase.
                      Please fund your reseller account to be able to make this purchase.</p>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <a href="flutterwave.php">
                      <button type="button" class="btn btn-warning">Fund From flutterwave</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>



            <!-- Purchase Model (success)-->
            <div class="modal fade" id="modelPurchase" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Your cable details where verified! Please confirm the below information before proceeding
                      proceed.</p>
                    <div style="display: flex;">
                      <b>Name:</b>
                      <p id="Subscriber_0"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Cable:</b>
                      <p id="cable_name_0"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Plan:</b>
                      <p id="planSelected"></p>
                    </div>
                    <div style="display: flex;">
                      <b>IUC:</b>
                      <p id="IUC_0"></p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-warning" id="confirmButton">Confirm</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- USSD Model (false)-->
            <div class="modal fade" id="modelUSSD_false" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p style="color: gold;">Unable to verify meter details at the moment! Please continue with
                      caution.</p>
                    <div style="display: flex;">
                      <b>Cable:</b>
                      <p id="cable_name_false"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Plan:</b>
                      <p id="cable_plan_false"></p>
                    </div>
                    <div style="display: flex;">
                      <b>IUC:</b>
                      <p id="IUC_false"></p>
                    </div>
                    <p style="color: red; font-size: small;">Looks like you have low balance to make this purchase.
                      Please fund your reseller account to be able to make this purchase.</p>
                  </div>
                  <div class="modal-footer" style="justify-content: center;">
                    <a href="flutterwave.php">
                      <button type="button" class="btn btn-warning">Fund From flutterwave</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>



            <!-- Purchase Model (false)-->
            <div class="modal fade" id="modelPurchase_false" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p style="color: gold;">Unable to verify meter details at the moment! Please continue with
                      caution.</p>
                    <div style="display: flex;">
                      <b>Cable:</b>
                      <p id="cable_name_0_false"></p>
                    </div>
                    <div style="display: flex;">
                      <b>Plan:</b>
                      <p id="planSelected_false"></p>
                    </div>
                    <div style="display: flex;">
                      <b>IUC:</b>
                      <p id="IUC_0_false"></p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-warning" id="confirmButton_false">Confirm</button>
                  </div>
                </div>
              </div>
            </div>



            <!-- Success Model -->
            <div class="modal fade" id="successPurchase" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="text-align: center">
                  <div class="modal-header" style=" justify-content: center;">
                    <img src="smile.gif" alt="success" srcset=""
                      style="max-width: 30%; max-height: 30%; align-items: center">
                  </div>
                  <div class="modal-body">
                    <strong>Bill Payment Successful </strong>

                    <table class="table">
                      <tr>
                        <th>Request ID:</th>
                        <td id="responseID"></td>
                      </tr>
                      <tr>
                        <th>Message:</th>
                        <td id="responseMessage"> </td>
                      </tr>
                      <tr>
                        <th>Amount:</th>
                        <td id="responseAmount"></td>
                      </tr>
                      <tr>
                        <th>Cable Name:</th>
                        <td id="name_cable"></td>
                      </tr>
                      <tr>
                        <th>Cable Plan:</th>
                        <td id="plan_cable"></td>
                      </tr>
                      <tr>
                        <th>IUC:</th>
                        <td id="iuc_cable"></td>
                      </tr>
                      <tr>
                        <th>New Balance:</th>
                        <td id="balance"></td>
                      </tr>
                    </table>

                  </div>

                  <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                    <a href="">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="min-width:45%">Buy Again</button>
                    </a>
                    <button type="button" class="btn btn-warning" id="" style="min-width:45%">Dashboard</button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Error Model -->
            <div class="modal fade" id="modelError" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content" style="text-align: center">
                  <div class="modal-body">
                    <div class="modal-header" style=" justify-content: center;">
                      <img src="fail.gif" alt="Failed" srcset=""
                        style="max-width: 30%; max-height: 30%; align-items: center">
                    </div>
                    <strong>Action Failed.</strong>
                    <p id="status_report"></p>
                    <div class="modal-footer" style="justify-content: center;">
                      <a href="">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Refresh</button>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <script
              src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

            <!-- Loading screen -->


            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>

              const plansData = [
                [1, '2', 'DStv Padi', '₦2500'],
                [2, '2', 'DStv -YANGA', '₦3500'],
                [5, '2', 'DStv Premium', '₦24500'],
                [6, '2', 'DStv Asia', '₦8300'],
                [9, '2', 'DStv Premium-Asia', '₦23500'],
                [10, '2', 'DStv Confam + ExtraView', '₦8200'],
                [11, '2', 'DStv Yanga + ExtraView', '₦5850'],
                [12, '2', 'DStv Padi + ExtraView', '₦5050'],
                [32, '2', 'ExtraView Access', '₦2900'],
                [34, '2', 'Dstv Confam', '₦6200'],
                [35, '2', 'DStv Compact', '₦10500'],
                [38, '2', 'DStv Compact Plus', '₦16600'],
                [39, '2', 'DStv Premium-French', '₦25550'],
                [45, '2', 'DStv Compact + Extra View', '₦11900'],
                [59, '1', 'GOtv Max', '₦4850'],
                [60, '1', 'GOtv Jolli', '₦3300'],
                [61, '1', 'GOtv Jinja', '₦2250'],
                [62, '1', 'GOtv Smallie - monthly', '₦900'],
                [63, '1', 'GOtv Smallie - quarterly', '₦2100'],
                [64, '1', 'GOtv Smallie - yearly', '₦7000'],
                [66, '3', 'Nova - 1 Month', '₦1200'],
                [67, '3', 'Basic (Antenna) - 1 Month', '₦2100'],
                [68, '3', 'Smart (Dish) -1 Month', '₦2800'],
                [69, '3', 'Classic (Antenna) - 1 Month', '₦3100'],
                [70, '3', 'Super (Dish) - 1 Month', '₦5300'],
                [71, '3', 'Nova - 1 Week', '₦400'],
                [72, '3', 'Basic (Antenna) - 1 Week', '₦700'],
                [73, '3', 'Smart (Dish) -- 1 Week', '₦900'],
                [74, '3', 'Classic (Antenna) - 1 Week', '₦1200'],
                [75, '3', 'Super (Dish) - 1 Week', '₦1800'],
                [76, '3', 'Nova- 1 Day', '₦100'],
                [77, '3', 'Basic (Antenna) -- 1 Day', '₦200'],
                [78, '3', 'Smart (Dish) 1 Day', '₦250'],
                [79, '3', 'Classic (Antenna) - 1 Day', '₦320'],
                [80, '3', 'Super (Dish) - 1 Day', '₦500'],
                [81, '2', 'DStv Compact Plus -Extra view', '₦17150'],
                [82, '2', 'Asian Bouquet', '₦20295'],
                [83, '2', 'DStv HDPVR Access Service', '₦2900'],
                [84, '2', 'DStv Premium + Extra View', '₦23900'],
                [85, '1', 'Supa', '₦6400'],
                [86, '3', 'Chinese', '₦9800'],
              ];


              //Get selected fields
              function loadPlans() {
                const cableSelect = document.getElementById('cableSelect');
                const planSelect = document.getElementById('planSelect');

                const selectedNetwork = cableSelect.value;

                // Clear previous options
                planSelect.innerHTML = '<option value="" disabled selected>Select Plan</option>';

                plansData.forEach(plan => {
                  const network = plan[1]; // Network column in your plans data
                  const planType = plan[2]; // Plan Type column in your plans data

                  if (
                    (selectedNetwork === network)
                  ) {
                    const option = document.createElement('option');
                    option.value = plan[0]; // Plan ID column in your plans data
                    option.textContent = `${plan[2]} / ${plan[3]}`; // Plan name, amount, and validity columns
                    // document.getElementsByClassName("amountValue").value = '₦ ' + `${plan[3]}`;
                    planSelect.appendChild(option);
                    priceString = plan[3];
                    const strippedPrice = parseInt(priceString.replace("₦", "").replace(",", ""), 10);
                    const amount = parseFloat(strippedPrice);
                    // Assign the value to the hidden input
                    const amountInput = document.getElementById("amount");
                    amountInput.value = amount; // Assign the stripped price
                  }
                });
              }



              //Buy cable sub
              document.getElementById("confirmButton").addEventListener("click", function () {
                $.ajax({
                  type: 'POST',
                  url: 'cable_payment.php',
                  data: $('#cablePaymentForm').serialize(),
                  beforeSend: function () {
                    $('#modelPurchase').modal('hide');
                    $("#loadingScreen").show();
                  },
                  success: function (data) {
                    $("#loadingScreen").hide();
                    $('#modelPurchase').modal('hide');
                    document.getElementById("status_report").innerText = data;

                    if (data.includes("success")) {
                      // Split the string by newline characters (\n)
                      var lines = data.split('\n');

                      // Extract individual pieces of information from each line

                      var requestId = lines[0];
                      var cable_name = lines[1];
                      var cable_plan = lines[2];
                      var IUC = lines[3];
                      var amount = lines[4];
                      var message = lines[5];
                      var balance = lines[6];


                      document.getElementById("responseMessage").innerText = message;
                      document.getElementById("responseID").innerText = requestId;
                      document.getElementById("responseAmount").innerText = "₦ " + amount;
                      document.getElementById("name_cable").innerText = cable_name;
                      document.getElementById("plan_cable").innerText = cable_plan
                      document.getElementById("iuc_cable").innerText = IUC;
                      document.getElementById("balance").innerText = "₦ " + balance;

                      $('#successPurchase').modal('show'); //use this to show a success model
                    } else {
                      $('#modelError').modal('show'); //use this to show a differnt model
                    }
                  },
                  error: function () {
                    // Hide the loading screen in case of an error
                    $("#loadingScreen").hide();
                    $('#modelError').modal('show');
                  }
                });
              });


              //Buy cable sub
              document.getElementById("confirmButton_false").addEventListener("click", function () {
                $.ajax({
                  type: 'POST',
                  url: 'cable_payment.php',
                  data: $('#cablePaymentForm').serialize(),
                  beforeSend: function () {
                    $('#modelPurchase_false').modal('hide');
                    $("#loadingScreen").show();
                  },
                  success: function (data) {
                    $("#loadingScreen").hide();
                    $('#modelPurchase_false').modal('hide');
                    document.getElementById("status_report").innerText = data;

                    if (data.includes("success")) {
                      // Split the string by newline characters (\n)
                      var lines = data.split('\n');

                      // Extract individual pieces of information from each line

                      var requestId = lines[0];
                      var cable_name = lines[1];
                      var cable_plan = lines[2];
                      var IUC = lines[3];
                      var amount = lines[4];
                      var message = lines[5];
                      var balance = lines[6];


                      document.getElementById("responseMessage").innerText = message;
                      document.getElementById("responseID").innerText = requestId;
                      document.getElementById("responseAmount").innerText = "₦ " + amount;
                      document.getElementById("name_cable").innerText = cable_name;
                      document.getElementById("plan_cable").innerText = cable_plan
                      document.getElementById("iuc_cable").innerText = IUC;
                      document.getElementById("balance").innerText = "₦ " + balance;

                      $('#successPurchase').modal('show'); //use this to show a success model
                    } else {
                      $('#modelError').modal('show'); //use this to show a differnt model
                    }
                  },
                  error: function () {
                    // Hide the loading screen in case of an error
                    $("#loadingScreen").hide();
                    $('#modelError').modal('show');
                  }
                });
              });

              // Function to populate the "Purchase" model with form data
              function populateModel_0(name, cable, IUC_NO, planSelect) {
                let cable_name;
                switch (cable) {
                  case '1':
                    cable_name = 'GOTV';
                    break;
                  case '2':
                    cable_name = 'DSTV';
                    break;
                  case '3':
                    cable_name = 'STARTIMES';
                    break;
                  default:
                    cable_name = 'Unknown Disco';
                }


                document.getElementById("Subscriber_0").innerText = name;
                document.getElementById("cable_name_0").innerText = cable_name;
                document.getElementById("IUC_0").innerText = IUC_NO;
                document.getElementById("planSelected").innerText = planSelect;

              }


              // Function to populate the "Purchase" model with form data
              function populateModel_1(name, cable, IUC_NO, priceString) {
                let cable_name;
                switch (cable) {
                  case '1':
                    cable_name = 'GOTV';
                    break;
                  case '2':
                    cable_name = 'DSTV';
                    break;
                  case '3':
                    cable_name = 'STARTIMES';
                    break;
                  default:
                    cable_name = 'Unknown Disco';
                }


                document.getElementById("Subscriber").innerText = name;
                document.getElementById("cable_name").innerText = cable_name;
                document.getElementById("cable_plan").innerText = priceString;
                document.getElementById("IUC").innerText = IUC_NO;

              }

              // Function to populate the "Purchase" model with form data
              function populateModel_2(name, cable, IUC_NO, planSelect) {
                let cable_name;
                switch (cable) {
                  case '1':
                    cable_name = 'GOTV';
                    break;
                  case '2':
                    cable_name = 'DSTV';
                    break;
                  case '3':
                    cable_name = 'STARTIMES';
                    break;
                  default:
                    cable_name = 'Unknown Disco';
                }


                document.getElementById("cable_name_0_false").innerText = cable_name;
                document.getElementById("IUC_0_false").innerText = IUC_NO;
                document.getElementById("planSelected_false").innerText = planSelect;

              }


              // Function to populate the "Purchase" model with form data
              function populateModel_3(name, cable, IUC_NO, priceString) {
                let cable_name;
                switch (cable) {
                  case '1':
                    cable_name = 'GOTV';
                    break;
                  case '2':
                    cable_name = 'DSTV';
                    break;
                  case '3':
                    cable_name = 'STARTIMES';
                    break;
                  default:
                    cable_name = 'Unknown Disco';
                }


                document.getElementById("cable_name_false").innerText = cable_name;
                document.getElementById("cable_plan_false").innerText = priceString;
                document.getElementById("IUC_false").innerText = IUC_NO;

              }

              function showLoadingScreen() {
                $('#loadingScreen').show();
              }

              function hideLoadingScreen() {
                $('#loadingScreen').hide();
              }

              // Verify Meter
              document.getElementById("verifyButton").addEventListener("click", function () {

                showLoadingScreen();

                $.ajax({
                  type: 'POST',
                  url: 'cable_meter_verify.php',
                  data: $('#cablePaymentForm').serialize(),
                  success: function (data) {
                    $("#loadingScreen").hide();
                    $('#modelPurchase').modal('hide');
                    //dump return error data on error model
                    document.getElementById("status_report").innerText = data;

                    if (data.includes("success")) {
                      // Split the string by newline characters (\n)
                      var lines = data.split('\n');

                      // Extract individual pieces of information from each line
                      var status = lines[0];
                      var name = lines[1];
                      var message = lines[2];


                      //Algorithm on which model to show
                      const planSelected = parseFloat(document.getElementById("planSelect").value);
                      const cable = document.getElementById("cableSelect").value;
                      const IUC_NO = document.getElementById("IUC_NO").value;

                      const balance = '<?php echo $balance; ?>';

                      plansData.forEach(plan => {
                        if (parseFloat(planSelected) === parseFloat(plan[0])) {
                          priceString = plan[3];
                          planSelect = `${plan[2]} / ${plan[3]}`;
                          const strippedPrice = parseInt(priceString.replace("₦", "").replace(",", ""), 10);

                          populateModel_0(name, cable, IUC_NO, planSelect);
                          populateModel_1(name, cable, IUC_NO, planSelect);
                          if (strippedPrice > balance) {
                            // populateUSSDModel(name, cable, priceString, IUC)
                            $('#modelUSSD').modal('show');
                            document.getElementById("modelPurchase").style.display = "none";
                          } else {
                            $('#modelPurchase').modal('show');
                            document.getElementById("modelUSSD").style.display = "none";
                            // populatePurchaseModel(name, cable, priceString, IUC);
                          }

                        }
                      });
                    } else {

                      //Algorithm on which model to show
                      const planSelected = parseFloat(document.getElementById("planSelect").value);
                      const cable = document.getElementById("cableSelect").value;
                      const IUC_NO = document.getElementById("IUC_NO").value;

                      const balance = '<?php echo $balance; ?>';

                      plansData.forEach(plan => {
                        if (parseFloat(planSelected) === parseFloat(plan[0])) {
                          priceString = plan[3];
                          planSelect = `${plan[2]} / ${plan[3]}`;
                          const strippedPrice = parseInt(priceString.replace("₦", "").replace(",", ""), 10);

                          populateModel_2(name, cable, IUC_NO, planSelect);
                          populateModel_3(name, cable, IUC_NO, planSelect);
                          if (strippedPrice > balance) {
                            // populateUSSDModel(name, cable, priceString, IUC)
                            $('#modelUSSD_false').modal('show');
                            document.getElementById("modelPurchase_false").style.display = "none";
                          } else {
                            $('#modelPurchase_false').modal('show');
                            document.getElementById("modelUSSD_false").style.display = "none";
                          }

                        }
                      });
                    }

                  },
                  error: function () {
                    // Handle AJAX error
                    document.getElementById("status_report").innerText = "Invalid cable details, please confirm your cable details and try again";

                    $('#modelError').modal('show');


                    // Hide the loading screen
                    hideLoadingScreen();
                  }
                });
              });
            </script>





            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    | ViraSub
                  </div>
                </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>

            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      </div>
      <!-- / Layout wrapper -->



      <!-- Core JS -->
      <!-- build:js assets/vendor/js/core.js -->
      <script src="../assets/vendor/libs/jquery/jquery.js"></script>
      <script src="../assets/vendor/libs/popper/popper.js"></script>
      <script src="../assets/vendor/js/bootstrap.js"></script>
      <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

      <script src="../assets/vendor/js/menu.js"></script>
      <!-- endbuild -->

      <!-- Vendors JS -->

      <!-- Main JS -->
      <script src="../assets/js/main.js"></script>

      <!-- Page JS -->

      <!-- Place this tag in your head or just before your close body tag. -->
      <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>