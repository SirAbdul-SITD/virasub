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

      $to = "sirabdul@nijamart.ng, support@billzwave.com.ng";
      $subject = "New Balance Deposit";
  
      $message = "A User Just Added Funds To Their Wallet:\n\n";
      $message .= " Transaction Reference No: $txRef";
      $message .= " Transaction Amount: $amount";
  
      $headers = "From: Your App <noreply@billzwave.com.ng>\r\n";
      $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
  
      mail($to, $subject, $message, $headers);
    
  } else {
    $to = "sirabdul@nijamart.ng, support@billzwave.com.ng";
    $subject = "Balance Deposit Error";

    $message = "An error occurred during a wallet funding:\n\n";
    $message .= "A user just tried funding their wallet but the tx_ref is invalid or has already been completed. please check";
    $message .= " Transaction Reference No: $txRef";

    $headers = "From: Your App <noreply@billzwave.com.ng>\r\n";
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

  <title>Cable Subscribe | Billzwave</title>

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
            <span style="margin-left:0px;" class="app-brand-text demo menu-text fw-bolder ms-2">illz<span style="color: #fB9149">w</span style="color: #14A39A">ave</span>
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

            <!-- Input Sizing -->
            <div class="col-md-8">
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
                      <input id="IUC_NO" name="IUC" class="form-control form-control-lg" type="tel" placeholder="IUC" />
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
                      <p   style="color: gold;">Unable to verify meter details at the moment! Please continue with
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
                          would you like to continue this
                          purchase by paying through your bank instead?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Back</button>
                        <button type="button" class="btn btn-warning" id="paymentButton_false">Pay With Bank</button>
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
                      <p   style="color: gold;">Unable to verify meter details at the moment! Please continue with
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
                            <button type="button" class="btn btn-outline-secondary"
                              data-bs-dismiss="modal">Refresh</button>
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
                        }else{
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
                        }else{
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
                  function populateModel_1(name, cable,  IUC_NO, priceString) {
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
                  function populateModel_3(name, cable,  IUC_NO, priceString) {
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

                              populateModel_0(name, cable,  IUC_NO, planSelect);
                              populateModel_1(name, cable,  IUC_NO, planSelect);
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

                              populateModel_2(name, cable,  IUC_NO, planSelect);
                              populateModel_3(name, cable,  IUC_NO, planSelect);
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

              </div>

              <script src="https://checkout.flutterwave.com/v3.js"></script>
              <script>
                document.getElementById("paymentButton").addEventListener("click", function () {
                  try {
                    $.ajax({
                      type: 'POST',
                      url: 'payment.php',
                      data: $('#cablePaymentForm').serialize(),
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

                document.getElementById("paymentButton_false").addEventListener("click", function () {
                  try {
                    $.ajax({
                      type: 'POST',
                      url: 'payment.php',
                      data: $('#cablePaymentForm').serialize(),
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
                      | Billzwave
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