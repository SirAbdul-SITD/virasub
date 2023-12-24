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

  <title>Buy Airtime | ViraSub</title>

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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

    .table tr th {
      text-align: left;
    }

    .table tr td {
      text-align: left;
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
            <span style="margin-left:0px;" class="app-brand-text demo menu-text fw-bolder ms-2">illz<span
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

          <li class="menu-item active">
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
                          <small class="text-muted">Profit Balance: ₦
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

            <!-- Loading screen -->
            <div id="loadingScreen">Loading...</div>


            <div class="row">
              <!-- Funding -->

              <!-- Purchase form -->

              <div class="col-md-6 col-lg-6 order-2 mb-4" style="position: relative;">
                <div class="conta" style="position: sticky; top: 15px;">
                  <div class="card mb-7">
                    <h5 class="card-header">Buy Airtime</h5>
                    <div class="card-body">
                      <form id="airtimePurchaseForm">
                        <div class="mt-3 mb-3">
                          <select id="networkSelect" name="airtime_network" class="form-select form-select-lg">
                            <option value="" disabled selected>Select Network</option>
                            <option value="1">MTN</option>
                            <option value="2">AIRTEL</option>
                            <option value="3">GLO</option>
                            <option value="4">9MOBILE</option>
                          </select>
                        </div>

                        <div class="mt-2 mb-3">
                          <input id="phoneInput" name="airtime_phone" class="form-control form-control-lg" type="tel"
                            placeholder="Phone Number" />
                          <p class="error-message" id="phoneError" style="font-size: small; color: red;"></p>
                        </div>

                        <div class="mt-2 mb-3">
                          <input id="amountInput" name="amount" class="form-control form-control-lg" type="tel"
                            placeholder="Amount" oninput="checkAmount()" />
                          <p class="error-message" id="amountError" style="font-size: small; color: red;"></p>
                        </div>

                        <div style="display: flex;">
                          <p id="balanceText" style="font-size: small">Reseller Balance: ₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </p>
                        </div>

                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="page" value="airtime.php">
                        <button id="purchaseButton" class="btn btn-warning btn-lg" type="button"
                          style="min-width: 100%;" disabled>
                          Purchase
                        </button>
                      </form>

                      <script>
                        let phoneErr = false;
                        let amountErr = false;

                        function validatePhone() {
                          const phoneInput = document.getElementById("phoneInput").value;
                          const phoneError = document.getElementById("phoneError");
                          const phoneRegex = /^(0703|0706|0803|0806|0810|0813|0814|0816|0903|0906|0705|0805|0807|0811|0815|0905|0802|0902|0701|0708|0809|0817|0818|0909|0812|0908|0907|0819|0901)\d{7}$/;
                          const isPhoneValid = phoneRegex.test(phoneInput);

                          if (isPhoneValid) {
                            phoneError.innerText = "";
                          } else {
                            phoneError.innerText = "Invalid phone number.";
                          }

                          phoneErr = isPhoneValid;
                          validateForm(); // Call validateForm() after phone validation
                        }

                        function validateAmount() {
                          const amountInput = document.getElementById("amountInput").value;
                          const amountError = document.getElementById("amountError");
                          const amountRegex = /^[1-9][0-9]{0,4}$/;
                          const isAmountValid = amountRegex.test(amountInput);

                          if (isAmountValid && parseInt(amountInput) >= 50 && parseInt(amountInput) <= 10000) {
                            amountError.innerText = "";
                          } else {
                            amountError.innerText = "Amount must be a whole number between ₦50 and ₦10,000.";
                          }

                          amountErr = isAmountValid && parseInt(amountInput) >= 50 && parseInt(amountInput) <= 10000;
                          validateForm();
                        }

                        function validateForm() {
                          const purchaseButton = document.getElementById("purchaseButton");


                          if (phoneErr && amountErr) {
                            purchaseButton.removeAttribute("disabled");
                          } else {
                            purchaseButton.setAttribute("disabled", "disabled");
                          }
                        }

                        // Add event listeners to call the appropriate validation function
                        document.getElementById("phoneInput").addEventListener("input", validatePhone);
                        document.getElementById("phoneInput").addEventListener("focus", validatePhone);

                        document.getElementById("amountInput").addEventListener("input", validateAmount);
                        document.getElementById("amountInput").addEventListener("focus", validateAmount);


                        // Call validateForm() on page load to initialize the form validation
                        validateForm();
                      </script>

                    </div>

                    <!-- USSD Model -->
                    <div class="modal fade" id="modelUSSD" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                      data-bs-keyboard="false">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalLongTitle">Insufficient Balance</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Looks like you have low balance to make this purchase. Please fund your reseller account
                              to
                              be able to make this purchase.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                              data-bs-dismiss="modal">Cancel</button>
                            <a href="flutterwave.php">
                              <button type="button" class="btn btn-warning">Fund From flutterwave</button>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Purchase Model -->
                    <div class="modal fade" id="modelPurchase" tabindex="-1" aria-hidden="true"
                      data-bs-backdrop="static" data-bs-keyboard="false">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="modalLongTitle">Confirm Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Kindly verify the accuracy of the following details before proceeding further.</p>
                            <div style="display: flex;">
                              <b>Network:</b>
                              <p id="networkValue"></p>
                            </div>

                            <div style="display: flex;">
                              <b>Phone Number:</b>
                              <p id="phoneValue"></p>
                            </div>
                            <div style="display: flex;">
                              <b>Amount:</b>
                              <p id="amountValue"></p>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                              data-bs-dismiss="modal">Back</button>
                            <button type="button" class="btn btn-warning" id="confirmButton">Confirm</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Success Model -->
                    <div class="modal fade" id="successPurchase" tabindex="-1" aria-hidden="true"
                      data-bs-backdrop="static" data-bs-keyboard="false">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="text-align: center">
                          <div class="modal-header" style=" justify-content: center;">
                            <img src="smile.gif" alt="success" srcset=""
                              style="max-width: 30%; max-height: 30%; align-items: center">
                          </div>
                          <div class="modal-body">
                            <strong>Airtime Purchase Successful</strong>

                            <table class="table">

                              <tr>
                                <th>Request ID:</th>
                                <td id="responseID"></td>
                              </tr>
                              <tr>
                                <th>Network:</th>
                                <td id="networkName"> </td>
                              </tr>
                              <tr>
                                <th>Amount:</th>
                                <td id="responseAmount"></td>
                              </tr>
                              <tr>
                                <th>Phone Number:</th>
                                <td id="responsePhone"></td>
                              </tr>
                              <tr>
                                <th>Message:</th>
                                <td id="responseMessage"></td>
                              </tr>
                            </table>

                          </div>

                          <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                            <a href="">
                              <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal"
                                style="min-width:45%">Close</button>
                            </a>
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
                            <strong>Action Failed Success.</strong>
                            <p id="status_report"></p>
                            <div class="modal-footer" style="justify-content: center;">
                              <a href="">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Try
                                  Again</button>
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
                      //Buy airtime
                      document.getElementById("confirmButton").addEventListener("click", function () {
                        $.ajax({
                          type: 'POST',
                          url: 'airtime_purchase.php',
                          data: $('#airtimePurchaseForm').serialize(),
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

                              var networkName = lines[0];
                              var requestId = lines[1];
                              var amount = lines[2];
                              var message = lines[3];
                              var phoneNumber = lines[4];


                              document.getElementById("responseMessage").innerText = message;
                              document.getElementById("responseID").innerText = requestId;
                              document.getElementById("responseAmount").innerText = amount;
                              document.getElementById("responsePhone").innerText = phoneNumber;
                              document.getElementById("networkName").innerText = networkName;



                              $('#successPurchase').modal('show'); //use this to show a success model
                            } else {
                              $('#modelError').modal('show'); //use this to show a differnt model
                              // alert(data);
                            }


                          },
                          error: function () {
                            // Hide the loading screen in case of an error
                            $("#loadingScreen").hide();
                            $('#successPurchase').modal('show');
                          }
                        });
                      });

                      function checkAmount() {
                        const amountInput = parseFloat(document.getElementById("amountInput").value);
                        const balance = '<?php echo $balance; ?>';

                        const purchaseButton = document.getElementById("purchaseButton");
                        const balanceText = document.getElementById("balanceText");

                        if (amountInput > balance) {
                          purchaseButton.textContent = "Pay with Bank";
                          balanceText.classList.add("red");
                        } else {
                          purchaseButton.textContent = "Purchase";
                          balanceText.classList.remove("red");
                        }
                      }

                      // Function to populate the "Purchase" model with form data
                      function populatePurchaseModel(network, phone, amount) {
                        let networkName;
                        switch (network) {
                          case '1':
                            networkName = 'MTN';
                            break;
                          case '2':
                            networkName = 'AIRTEL';
                            break;
                          case '3':
                            networkName = 'GLO';
                            break;
                          case '4':
                            networkName = '9MOBILE';
                            break;
                          default:
                            networkName = 'Unknown Network';
                        }


                        document.getElementById("networkValue").innerText = networkName;
                        document.getElementById("phoneValue").innerText = phone;
                        document.getElementById("amountValue").innerText = '₦ ' + amount;

                      }


                      document.getElementById("purchaseButton").addEventListener("click", function () {
                        const amountInput = parseFloat(document.getElementById("amountInput").value);
                        const balance = '<?php echo $balance; ?>';

                        if (amountInput > balance) {
                          $('#modelUSSD').modal('show');
                          document.getElementById("modelPurchase").style.display = "none";
                        } else {
                          $('#modelPurchase').modal('show');
                          document.getElementById("modelUSSD").style.display = "none";
                          // Get the form data and populate the "Purchase" model
                          const network = document.getElementById("networkSelect").value;
                          const phone = document.getElementById("phoneInput").value;
                          const amount = document.getElementById("amountInput").value;
                          populatePurchaseModel(network, phone, amount);
                        }

                        $.ajax({
                          type: "POST",
                          url: "airtime_session.php",
                          data: $("#airtimePurchaseForm").serialize(),
                        });
                      });
                    </script>
                  </div>
                </div>
              </div>
              <!-- Purchase form end-->

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
                            <td id="recreipt-user">
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
                        <button type="button" class="btn btn-warning" id="send-recreipt-button" style="min-width:45%"> <i
                            class="bx bx-send me-1"></i> Send Recreipt</button>
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
                            class="bx bx-send me-1"></i> Send Recreipt</button>
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
                            class="bx bx-send me-1"></i> Send Recreipt</button>
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
                            class="bx bx-send me-1"></i> Send Recreipt</button>
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
                    $tr_type = "Airtime";
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





            <!-- JavaScript to handle OTP sending and verification using AJAX -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
              $(document).ready(function () {
                // Initially, show the email section and hide the OTP section
                toggleSections(true);

                // Send OTP Button Click
                $("#recreipt-button").click(function () {
                  var email = $("#recreipt-user").val();

                  // Send an AJAX request to send_otp.php to send OTP
                  $.ajax({
                    type: "POST",
                    url: "send_recreipt.php",
                    data: { email: email },
                    dataType: "json",
                    success: function (response) {
                      if (response.success) {
                        // OTP sent successfully, show OTP section
                        toggleSections(false);
                        displayPopup(response.message, response.success);
                      } else {
                        // Check if the error message contains "OTP already sent"
                        if (response.message.indexOf("OTP already sent") !== -1) {
                          $("#email-section").hide();
                          $("#otp-section").show();
                          displayPopup(response.message, false);
                        } else {
                          // Handle other error messages
                          displayPopup(response.message, false);
                        }

                      }
                    },
                    error: function () {
                      displayPopup("An error occurred while sending OTP.", false);
                    }
                  });

                });
              });


              // Function to display a popup message
              function displayPopup(message, success) {
                var popup = document.createElement('div');
                popup.className = 'popup ' + (success ? 'success' : 'error');

                var iconClass = success ? 'fa fa-check-circle' : 'fa fa-times-circle';
                var icon = document.createElement('i');
                icon.className = iconClass;
                popup.appendChild(icon);

                var text = document.createElement('span');
                text.textContent = message;
                popup.appendChild(text);

                document.body.appendChild(popup);

                setTimeout(function () {
                  popup.remove();
                }, 5000);
              }


            </script>




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