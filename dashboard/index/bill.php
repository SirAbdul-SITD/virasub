<?php
require('settings.php');

if (isset($_GET['tx_ref']) || $_GET['tx_ref'] === "completed") {
  $txRef = $_GET['tx_ref'];

  // Check if tx_ref exists and is in "Pending" status
  $checkQuery = "SELECT * FROM funding WHERE trx_ref = :trx_ref AND status = 'Pending'";
  $checkStmt = $pdo->prepare($checkQuery);
  $checkStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
  $checkStmt->execute();

  if ($checkStmt->rowCount() === 1) {
    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);  
    $amount = $row['amount'];

      $status = "Success";


      $updateQuery = "UPDATE funding SET status = :status WHERE trx_ref = :trx_ref";
      $updateStmt = $pdo->prepare($updateQuery);
      $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
      $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
      $updateStmt->execute();


      $newBal = $balance + $amount;

      $balance = $newBal;

      
      $updateQuery = "UPDATE users SET balance = :balance WHERE email = :userEmail";
      $updateStmt = $pdo->prepare($updateQuery);

      $updateStmt->bindParam(':balance', $newBal, PDO::PARAM_STR);
      $updateStmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
      $updateStmt->execute();

      $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
      $subject = "New Balance Deposit";
  
      $message = "A User Just Added Funds To Their Wallet:\n\n";
      $message .= " Transaction Reference No: $txRef";
      $message .= " Transaction Amount: $amount";
  
      $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
      $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  
      mail($to, $subject, $message, $headers);
    
  } else {
    $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
    $subject = "Balance Deposit Error";

    $message = "An error occurred during a wallet funding:\n\n";
    $message .= "A user just tried funding their wallet but the tx_ref is invalid or has already been completed. please check";
    $message .= " Transaction Reference No: $txRef";

    $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mail($to, $subject, $message, $headers);
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

  <title>Pay Electricity Bills | ViraSub</title>

  <meta name="description" content="Buy airtime at a cheap rate with amazing discounts" />

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
           <span> <span style="color: #fB9149">V</span>
            <span style="margin-left:0px;" class="app-brand-text demo menu-text fw-bolder ms-2">ira<span style="color: #fB9149">S</span style="color: #14A39A">ub</span>
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
          <li class="menu-item">
            <a href="cable.php" class="menu-link">
              <i> <img src="icons/cable.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Cable Sub</div>
            </a>
          </li>
          <li class="menu-item active">
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

            <!-- Input Sizing -->
            <div class="col-md-8">
              <div class="card mb-7">
                <h5 class="card-header">Electricity Bill</h5>
                <div class="card-body">
                  <form id="billPaymentForm">
                    <div class="mt-3 mb-3">
                      <select id="disco_name" name="disco_name" class="form-select form-select-lg">
                        <option value="" disabled selected>Disco Name</option>
                        <option value="1">Ikeja Electricity</option>
                        <option value="2">Eko Electricity</option>
                        <option value="3">Kano Electricity</option>
                        <option value="4">Port Harcourt Electricity</option>
                        <option value="5">Jos Electricity</option>
                        <option value="6">Ibadan Electricity</option>
                        <option value="7">Kaduna Electricity</option>
                        <option value="8">Abuja Electricity</option>
                      </select>
                    </div>

                    <div class="mt-2 mb-3">
                      <select id="meter_type" name="meter_type" class="form-select form-select-lg">
                        <option value="" disabled selected>Meter Type</option>
                        <option value="prepaid">Prepaid</option>
                        <option value="postpaid">Postpaid</option>

                      </select>
                    </div>


                    <div class="mt-2 mb-3">
                      <input id="meter_number" name="meter_number" class="form-control form-control-lg" type="tel"
                        placeholder="Meter Number" />
                      <p class="error-message" id="phoneError" style="font-size: 1vw; color: red;"></p>
                    </div>

                    <div class="mt-2 mb-3">
                      <input id="amount" name="amount" class="form-control form-control-lg" type="tel"
                        placeholder="Amount" />
                      <p class="error-message" id="amountError" style="font-size: 1vw; color: red;"></p>
                    </div>

                    <div style="display: flex;">
                      <p id="balanceText">Balance: ₦
                      <?php echo number_format($balance, 2, '.', ','); ?>
                      </p>
                    </div>

                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="page" value="bill.php">
                    <button id="verifyButton" class="btn btn-warning btn-lg" type="button" style="min-width: 100%;">
                      Verify Meter
                    </button>
                  </form>


                </div>

                <!-- USSD Model (False) -->
                <div class="modal fade" id="modelUSSD" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      <p   style="color: gold;">Unable to verify meter details at the moment! Please continue with
                          caution.</p>
                        <div style="display: flex;">
                          <b>Disco Name:</b>
                          <p id="false_USSDdisco"></p>
                        </div>

                        <div style="display: flex;">
                          <b>Meter Type:</b>
                          <p id="false_USSDmeter"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Number:</b>
                          <p id="false_USSDNumber"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Amount:</b>
                          <p id="false_USSDamount"></p>
                        </div>
                        <p style="color: red; font-size: small;">Looks like you have low balance to make this purchase.
                          would you like to continue this
                          purchase by paying through your bank instead?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-warning" id="paymentButton">Pay With Bank</button>
                      </div>
                    </div>
                  </div>
                </div>



                <!-- Purchase Model (False)-->
                <div class="modal fade" id="modelPurchase" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p style="color: gold;">Unable to verify meter details at the moment! Please continue with caution.</p>

                                                 <div style="display: flex;">
                          <b>Disco Name:</b>
                          <p id="false_discoName"></p>
                        </div>

                        <div style="display: flex;">
                          <b>Meter Type:</b>
                          <p id="false_meterType"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Number:</b>
                          <p id="false_meterNumber"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Amount:</b>
                          <p id="false_amountValue"></p>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-warning" id="confirmButton">Confirm</button>
                      </div>
                    </div>
                  </div>
                </div>



                <!-- USSD Model (Success) -->
                <div class="modal fade" id="modelUSSD_success" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p style="color: green">Your meter details where verified! Please confirm the below information before proceeding
                          proceed.</p>
                        <div style="display: flex;">
                          <b>Disco Name:</b>
                          <p id="USSDdisco"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Acct Name:</b>
                          <p id="USSDmeterName"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Type:</b>
                          <p id="USSDmeter"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Number:</b>
                          <p id="USSDNumber"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Amount:</b>
                          <p id="USSDamount"></p>
                        </div>
                        <p style="color: red; font-size: small;">Looks like you have low balance to make this purchase.
                          would you like to continue this
                          purchase by paying through your bank instead?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-warning" id="paymentButton_success">Pay With Bank</button>
                      </div>
                    </div>
                  </div>
                </div>



                <!-- Purchase Model (Success)-->
                <div class="modal fade" id="modelPurchase_success" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                  data-bs-keyboard="false">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p style="color: green">Your meter details where verified! Please confirm the below information before proceeding
                          proceed.</p>
                        <div style="display: flex;">
                          <b>Disco Name:</b>
                          <p id="discoName"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Acct Name:</b>
                          <p id="personName"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Type:</b>
                          <p id="meterType"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Meter Number:</b>
                          <p id="meterNumber"></p>
                        </div>
                        <div style="display: flex;">
                          <b>Amount:</b>
                          <p id="amountValue"></p>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-warning" id="confirmButton_success">Confirm</button>
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
                            <th>Meter Type:</th>
                            <td id="MeterType"></td>
                          </tr>
                          <tr>
                            <th>Meter Number:</th>
                            <td id="meterNo"></td>
                          </tr>
                          <tr>
                            <th>Disco Name:</th>
                            <td id="disco"></td>
                          </tr>
                          <tr>
                            <th>Charges:</th>
                            <td id="fee"></td>
                          </tr>
                          <tr>
                            <th>New Balance:</th>
                            <td>
                            <?php echo number_format($balance, 2, '.', ','); ?>
                            </td>
                          </tr>
                        </table>

                      </div>

                      <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                        <a href="">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="min-width:45%">Pay Bills</button>
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
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Edit
                              Info</button>
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
                    //Buy airtime (false model)
                    document.getElementById("confirmButton").addEventListener("click", function () {
                      $.ajax({
                        type: 'POST',
                        url: 'bill_payment.php',
                        data: $('#billPaymentForm').serialize(),
                        beforeSend: function () {
                          // Show the loading screen
                          $('#modelPurchase').modal('hide');
                          $("#loadingScreen").show();
                        },
                        success: function (data) {
                          // Hide the loading screen
                          $("#loadingScreen").hide();
                          // Show the API response in a popup
                          $('#modelPurchase').modal('hide');
                          document.getElementById("status_report").innerText = data;

                          if (data.includes("success")) {
                            // Split the string by newline characters (\n)
                            var lines = data.split('\n');

                            // Extract individual pieces of information from each line
                            var disco = lines[1].split(':')[1].trim();
                            var requestId = lines[2].split(':')[1].trim();
                            var amount = lines[3].split(':')[1].trim();
                            var meter_type = lines[4].split(':')[1].trim();
                            var message = lines[5].trim();
                            var meter_number = lines[6].split(':')[1].trim();
                            var fee = lines[7].split(':')[1].trim();
                            var status = lines[8].split(':')[1].trim();


                            document.getElementById("responseMessage").innerText = message;
                            document.getElementById("responseID").innerText = requestId;
                            document.getElementById("responseAmount").innerText = amount;
                            document.getElementById("MeterType").innerText = meter_type;
                            document.getElementById("meterNo").innerText = meter_number;
                            document.getElementById("disco").innerText = disco;
                            document.getElementById("fee").innerText = fee;

                            $('#successPurchase').modal('show'); //use this to show a success model
                          }

                          $('#modelError').modal('show'); //use this to show a differnt model
                          // alert(data);
                        },
                        error: function () {
                          // Hide the loading screen in case of an error
                          $("#loadingScreen").hide();
                          $('#successPurchase').modal('show');
                        }
                      });
                    });


                     //Buy airtime (success model)
                     document.getElementById("confirmButton_success").addEventListener("click", function () {
                      $.ajax({
                        type: 'POST',
                        url: 'bill_payment.php',
                        data: $('#billPaymentForm').serialize(),
                        beforeSend: function () {
                          // Show the loading screen
                          $('#modelPurchase_success').modal('hide');
                          $("#loadingScreen").show();
                        },
                        success: function (data) {
                          // Hide the loading screen
                          $("#loadingScreen").hide();
                          // Show the API response in a popup
                          $('#modelPurchase_success').modal('hide');
                          document.getElementById("status_report").innerText = data;

                          if (data.includes("success")) {
                            // Split the string by newline characters (\n)
                            var lines = data.split('\n');

                            // Extract individual pieces of information from each line
                            var disco = lines[1].split(':')[1].trim();
                            var requestId = lines[2].split(':')[1].trim();
                            var amount = lines[3].split(':')[1].trim();
                            var meter_type = lines[4].split(':')[1].trim();
                            var message = lines[5].trim();
                            var meter_number = lines[6].split(':')[1].trim();
                            var fee = lines[7].split(':')[1].trim();
                            var status = lines[8].split(':')[1].trim();


                            document.getElementById("responseMessage").innerText = message;
                            document.getElementById("responseID").innerText = requestId;
                            document.getElementById("responseAmount").innerText = amount;
                            document.getElementById("MeterType").innerText = meter_type;
                            document.getElementById("meterNo").innerText = meter_number;
                            document.getElementById("disco").innerText = disco;
                            document.getElementById("fee").innerText = fee;

                            $('#successPurchase').modal('show'); //use this to show a success model
                          }

                          $('#modelError').modal('show'); //use this to show a differnt model
                          // alert(data);
                        },
                        error: function () {
                          // Hide the loading screen in case of an error
                          $("#loadingScreen").hide();
                          $('#modelError').modal('show');
                        }
                      });
                    });





                    // Function to populate the "Purchase" model with form data
                    function populatePurchaseModel_false(discoName, meterType, meterNumber, amount) {
                      let disco;
                      switch (discoName) {
                        case '1':
                          disco = 'Ikeja Electricity';
                          break;
                        case '2':
                          disco = 'Eko Electricity';
                          break;
                        case '3':
                          disco = 'Kano Electricity';
                          break;
                        case '4':
                          disco = 'Port Harcourt Electricity';
                          break;
                        case '5':
                          disco = 'Jos Electricity';
                          break;
                        case '6':
                          disco = 'Ibadan Electricity';
                          break;
                        case '7':
                          disco = 'Kaduna Electricity';
                          break;
                        case '8':
                          disco = 'Abuja Electricity';
                          break;
                        default:
                          disco = 'Unknown Disco';
                      }

                      document.getElementById("false_discoName").innerText = disco;
                      document.getElementById("false_meterType").innerText = meterType;
                      document.getElementById("false_meterNumber").innerText = meterNumber;
                      document.getElementById("false_amountValue").innerText = '₦ ' + amount;

                    }

                    function populateUSSDModel_false(discoName, meterType, meterNumber, amount) {
                      let disco;
                      switch (discoName) {
                        case '1':
                          disco = 'Ikeja Electricity';
                          break;
                        case '2':
                          disco = 'Eko Electricity';
                          break;
                        case '3':
                          disco = 'Kano Electricity';
                          break;
                        case '4':
                          disco = 'Port Harcourt Electricity';
                          break;
                        case '5':
                          disco = 'Jos Electricity';
                          break;
                        case '6':
                          disco = 'Ibadan Electricity';
                          break;
                        case '7':
                          disco = 'Kaduna Electricity';
                          break;
                        case '8':
                          disco = 'Abuja Electricity';
                          break;
                        default:
                          disco = 'Unknown Disco';
                      }


                      document.getElementById("false_USSDdisco").innerText = disco;
                      document.getElementById("false_USSDmeter").innerText = meterType;
                      document.getElementById("false_USSDNumber").innerText = meterNumber;
                      document.getElementById("false_USSDamount").innerText = '₦ ' + amount;

                    }

                     // Function to populate the "Purchase" model with form data
                     function populatePurchaseModel_success(discoName, personName, meterType, meterNumber, amount) {
                      let disco;
                      switch (discoName) {
                        case '1':
                          disco = 'Ikeja Electricity';
                          break;
                        case '2':
                          disco = 'Eko Electricity';
                          break;
                        case '3':
                          disco = 'Kano Electricity';
                          break;
                        case '4':
                          disco = 'Port Harcourt Electricity';
                          break;
                        case '5':
                          disco = 'Jos Electricity';
                          break;
                        case '6':
                          disco = 'Ibadan Electricity';
                          break;
                        case '7':
                          disco = 'Kaduna Electricity';
                          break;
                        case '8':
                          disco = 'Abuja Electricity';
                          break;
                        default:
                          disco = 'Unknown Disco';
                      }

                      document.getElementById("discoName").innerText = disco;
                      document.getElementById("personName").innerText = personName;
                      document.getElementById("meterType").innerText = meterType;
                      document.getElementById("meterNumber").innerText = meterNumber;
                      document.getElementById("amountValue").innerText = '₦ ' + amount;

                    }

                    function populateUSSDModel_success(discoName, USSDmeterName, meterType, meterNumber, amount) {
                      let disco;
                      switch (discoName) {
                        case '1':
                          disco = 'Ikeja Electricity';
                          break;
                        case '2':
                          disco = 'Eko Electricity';
                          break;
                        case '3':
                          disco = 'Kano Electricity';
                          break;
                        case '4':
                          disco = 'Port Harcourt Electricity';
                          break;
                        case '5':
                          disco = 'Jos Electricity';
                          break;
                        case '6':
                          disco = 'Ibadan Electricity';
                          break;
                        case '7':
                          disco = 'Kaduna Electricity';
                          break;
                        case '8':
                          disco = 'Abuja Electricity';
                          break;
                        default:
                          disco = 'Unknown Disco';
                      }


                      document.getElementById("USSDdisco").innerText = disco;
                      document.getElementById("USSDmeterName").innerText = USSDmeterName;
                      document.getElementById("USSDmeter").innerText = meterType;
                      document.getElementById("USSDNumber").innerText = meterNumber;
                      document.getElementById("USSDamount").innerText = '₦ ' + amount;

                    }



                    // Function to show the loading screen
                    function showLoadingScreen() {
                      $('#loadingScreen').show();
                    }

                    // Function to hide the loading screen
                    function hideLoadingScreen() {
                      $('#loadingScreen').hide();
                    }

                    // Verify Meter
                    document.getElementById("verifyButton").addEventListener("click", function () {

                      // Show the loading screen
                      showLoadingScreen();

                      // Send AJAX request
                      $.ajax({
                        type: 'POST',
                        url: 'bill_meter_verify.php',
                        data: $('#billPaymentForm').serialize(),
                        success: function (data) {
                          // Hide the loading screen
                          $("#loadingScreen").hide();
                          // Show the API response in a popup
                          $('#modelPurchase').modal('hide');
                          document.getElementById("status_report").innerText = data;

                          if (data.includes("success")) {
                            // Split the string by newline characters (\n)
                            var lines = data.split('\n');

                            // Extract individual pieces of information from each line
                            var disco = lines[0];
                            var name = lines[1];
                            var message = lines[2];

                            const amount = document.getElementById("amount").value;
                            const balance = '<?php echo $balance; ?>';

                            if (parseFloat(amount) > parseFloat(balance)) {
                              $('#modelUSSD_success').modal('show');
                              document.getElementById("modelPurchase_success").style.display = "none";
                              const discoName = document.getElementById("disco_name").value;
                              const meterType = document.getElementById("meter_type").value;
                              const meterNumber = document.getElementById("meter_number").value;
                              const USSDmeterName = name;
                              populateUSSDModel_success(discoName, USSDmeterName, meterType, meterNumber, amount);
                            } else {
                              $('#modelPurchase_success').modal('show');
                              document.getElementById("modelUSSD_success").style.display = "none";
                              const discoName = document.getElementById("disco_name").value;
                              const meterType = document.getElementById("meter_type").value;
                              const meterNumber = document.getElementById("meter_number").value;
                              const personName = name;
                              populatePurchaseModel_success(discoName, personName, meterType, meterNumber, amount);
                            }
                          } else {
                            const amount = document.getElementById("amount").value;
                            const balance = '<?php echo $balance; ?>';

                            if (parseFloat(amount) > parseFloat(balance)) {
                              $('#modelUSSD').modal('show');
                              document.getElementById("modelPurchase").style.display = "none";
                              const discoName = document.getElementById("disco_name").value;
                              const meterType = document.getElementById("meter_type").value;
                              const meterNumber = document.getElementById("meter_number").value;
                              populateUSSDModel_false(discoName, meterType, meterNumber, amount);
                            } else {
                              $('#modelPurchase').modal('show');
                              document.getElementById("modelUSSD").style.display = "none";
                              const discoName = document.getElementById("disco_name").value;
                              const meterType = document.getElementById("meter_type").value;
                              const meterNumber = document.getElementById("meter_number").value;
                              populatePurchaseModel_false(discoName, meterType, meterNumber, amount);
                            }
                          }

                        },
                        error: function () {
                          // Handle AJAX error
                          document.getElementById("status_report").innerText = "Invalid details, please confirm your meter details and try again";

                          $('#modelError').modal('show');


                          // Hide the loading screen
                          hideLoadingScreen();
                        }
                      });
                    });

                    // Function to handle API errors
                    function handleApiError(data) {
                      // Display error message to the user (you can customize this further)
                      alert(`API Error: ${data}`);
                    }

                    // Function to handle AJAX errors
                    function handleAjaxError() {
                      // Display a generic error message to the user (you can customize this further)
                      alert("An error occurred while processing your request. Please try again later.");
                    }
                  </script>
                </div>
              </div>

              <!-- payment model (fasle)-->
              <script src="https://checkout.flutterwave.com/v3.js"></script>
              <script>
                document.getElementById("paymentButton").addEventListener("click", function () {
                  try {
                    $.ajax({
                      type: 'POST',
                      url: 'payment.php',
                      data: $('#billPaymentForm').serialize(),
                      dataType: 'json',
                      beforeSend: function () {
                        $('#modelUSSD').modal('hide');
                        // document.getElementById("loadingScreen").style.display = "block";
                        $("#loadingScreen").show();
                      },
                      success: function (paymentPayloadJson) {
                        $("#loadingScreen").hide();
                        FlutterwaveCheckout(paymentPayloadJson);
                      },
                      error: function (xhr, status, error) {
                        alert('An error occurred while fetching payment [err_code: #8704]');
                        $("#loadingScreen").hide();
                        // sendErrorEmailToManagement(error);
                        // sendErrorEmailToAdmin(error);

                      }
                    });
                  } catch (error) {
                  }

                });
              </script>

               <!-- payment model (success)-->
               <script src="https://checkout.flutterwave.com/v3.js"></script>
              <script>
                document.getElementById("paymentButton_success").addEventListener("click", function () {
                  try {
                    $.ajax({
                      type: 'POST',
                      url: 'payment.php',
                      data: $('#billPaymentForm').serialize(),
                      dataType: 'json',
                      beforeSend: function () {
                        $('#modelUSSD').modal('hide');
                        // document.getElementById("loadingScreen").style.display = "block";
                        $("#loadingScreen").show();
                      },
                      success: function (paymentPayloadJson) {
                        $("#loadingScreen").hide();
                        FlutterwaveCheckout(paymentPayloadJson);
                      },
                      error: function (xhr, status, error) {
                        $("#loadingScreen").hide();
                        document.getElementById("status_report").innerText = "Error: Please try again in few minutes [err_code: #8704]";
                        $('#modelError').modal('show');
                      }
                    });
                  } catch (error) {
                    $("#loadingScreen").hide();
                    document.getElementById("status_report").innerText = "Error: Please try again in few minutes [err_code: #8701]";
                    $('#modelError').modal('show');
                  }

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
            </div>
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