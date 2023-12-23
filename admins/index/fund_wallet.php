<?php
require('settings.php');


?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Fund Wallet | Billzwave</title>

  <meta name="description" content="" />

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

  <!-- Page CSS -->



  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


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


  /* Define a default font size for the table */
  .table {
    font-size: 16px;
  }

  /* Media query for smaller screens (adjust the max-width as needed) */
  @media (max-width: 767px) {
    .table th, td{
      font-size: 10px; /* Decrease the font size for smaller screens */
    }
  }


    .suggestions {
      background-color: white;
      color: black;
      position: absolute;
      /* width: 129vh; */
      border-radius: 8px;
      border: 1px solid #ccc;
      display: none;
    }

    .suggestion {
      padding: 10px;
      cursor: pointer;
    }

    .suggestion:hover {
      background-color: #f0f0f0;
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
          <li class="menu-item">
            <a href="bill.php" class="menu-link">
              <i> <img src="icons/utility.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Basic">Utility Bills</div>
            </a>
          </li>
          <!-- Management -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Management</span></li>
          <li class="menu-item active">
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
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
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
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                  aria-label="Search..." />
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
                    <a class=" dropdown-item" href="auth/logout.php">
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

          <div class="container-xxl flex-grow-1 container-p-y">

            <div id="loadingScreen">Loading...</div>

            <!-- Success Model -->
            <div class="modal fade" id="success-model" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="text-align: center">
                  <div class="modal-header" style=" justify-content: center;">
                    <img src="smile.gif" alt="success" srcset=""
                      style="max-width: 30%; max-height: 30%; align-items: center">
                  </div>
                  <div class="modal-body">
                    <strong>Balance Updated Successfully </strong>
                    <table class="table" style="text-align: left">
                      <tr>
                        <th></th>
                        <td></td>
                      </tr>
                      <tr>
                        <th>User:</th>
                        <td>
                          <div id="response-name"></div>
                        </td>
                      </tr>
                      <tr>
                        <th>Email:</th>
                        <td>
                          <div id="response-email"></div>
                        </td>
                      </tr>
                      <tr>
                        <th>Amount:</th>
                        <td>
                          <div id="response-amount"></div>
                        </td>
                      </tr>
                    </table>
                  </div>
                  <!-- <div id="success-message" style="text-align: left; justify-content: center; justify-items: center;"></div> -->
                  <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CLOSE</button>
                  </div>
                </div>
              </div>
            </div>


            <!-- Error Model -->
            <div class="modal fade" id="error-model" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
              data-bs-keyboard="false">
              <div class="modal-dialog" role="document">
                <div class="modal-content" style="text-align: center">
                  <div class="modal-body">
                    <div class="modal-header" style=" justify-content: center;">
                      <img src="fail.gif" alt="Failed" srcset=""
                        style="max-width: 30%; max-height: 30%; align-items: center">
                    </div>
                    <strong>Action Failed</strong>
                    <div id="error-message"></div>
                    <div class="modal-footer" style="justify-content: center;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Try
                        Again</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12 col-12 mb-md-0 mb-4">
                    <div class="card">
                      <h5 class="card-header">Fund Wallet</h5>
                      <div class="card-body">
                        <!--Fund Wallet -->
                        <?php
                        $query = "SELECT * FROM users";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute();
                        $transactionData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div>
                          <form id="wallet_fundingForm">
                            <div style="display: flex;">
                              <p id="balanceText" style="font-size: small;">Available Profit Balance:
                                <?php echo "₦ " . $balance; ?>
                              </p>
                            </div>
                            <?php if (isset($_GET['email'])) {
                              $email = $_GET['email'];
                              ?>
                              <div style="display: flex;">
                                <strong style="font-size: medium;">Credit user:
                                  <?php echo $email; ?>
                                </strong>
                              </div>
                            <?php } ?>
                            <?php if (isset($_GET['email'])) {
                              $email = $_GET['email'];
                              ?>
                              <input type="hidden" name="userInput" class="form-control form-control-lg" id="userInput"
                                value="<?= $email; ?>">
                            <?php } else { ?>
                              <div class="autocomplete mt-2 mb-3">
                                <input type="text" name="userInput" class="form-control form-control-lg" id="userInput"
                                  placeholder="Search for a user">
                                <div id="suggestions" class="suggestions"></div>
                              </div>

                              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                              <script>
                                $(document).ready(function () {
                                  const userInput = document.getElementById("userInput");
                                  const suggestionsContainer = document.getElementById("suggestions");

                                  userInput.addEventListener("input", function () {
                                    const inputText = userInput.value.toLowerCase();

                                    // Make an AJAX request to your server to fetch user data
                                    fetch(`get_usernames.php?term=${inputText}`)
                                      .then(response => response.json())
                                      .then(data => {
                                        displaySuggestions(data);
                                      })
                                      .catch(error => {
                                        console.error(error);
                                      });

                                  });

                                  function displaySuggestions(suggestions) {
                                    suggestionsContainer.innerHTML = "";

                                    suggestions.forEach(suggestion => {
                                      const suggestionElement = document.createElement("div");
                                      suggestionElement.classList.add("suggestion");
                                      suggestionElement.textContent = suggestion.email; // Modify this based on your database structure

                                      suggestionElement.addEventListener("click", function () {
                                        userInput.value = suggestion.email; // Modify this based on your database structure
                                        suggestionsContainer.style.display = "none";
                                      });

                                      suggestionsContainer.appendChild(suggestionElement);
                                    });

                                    if (suggestions.length > 0) {
                                      suggestionsContainer.style.display = "block";
                                    } else {
                                      suggestionsContainer.style.display = "none";
                                    }
                                  };

                                  // Close the suggestions when clicking outside the autocomplete div
                                  document.addEventListener("click", function (event) {
                                    if (!event.target.closest(".autocomplete")) {
                                      suggestionsContainer.style.display = "none";
                                    }
                                  });
                                });
                              </script>
                            <?php } ?>
                            <div class="mt-2 mb-3">
                              <input id="amount" name="amount" class="form-control form-control-lg" type="number"
                                placeholder="Amount" />
                            </div>
                            <button type="submit" id="paymentButton" class="btn btn-warning btn-lg"
                              style="min-width: 100%;">
                              Fund Wallet
                            </button>
                          </form>
                        </div>






                        <!--  Form submission-->
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                          $(document).ready(function () {


                            $("#wallet_fundingForm").submit(function (event) {
                              event.preventDefault();

                              var amount = $("#amount").val();
                              var userInput = $("#userInput").val();

                              // Make an AJAX request to your PHP script
                              $.ajax({
                                type: "POST",
                                url: "add_money.php",
                                data: {
                                  amount: amount,
                                  userInput: userInput
                                },
                                dataType: "json",
                                beforeSend: function () {
                                  const paymentButton = document.getElementById("paymentButton");
                                  paymentButton.setAttribute("disabled", "disabled");
                                  $("#loadingScreen").show();
                                },
                                success: function (response) {
                                  document.getElementById("amount").value = "";
                                  $("#loadingScreen").hide();
                                  const paymentButton = document.getElementById("paymentButton");
                                  paymentButton.removeAttribute("disabled");
                                  if (response.error) {
                                    $('#error-model').modal('show');
                                    $("#error-message").text(response.error);
                                  } else {
                                    $('#success-model').modal('show');
                                    $("#response-name").text(response.name);
                                    $("#response-email").text(response.email);
                                    $("#response-amount").text(response.amount.toLocaleString('en-US', { style: 'currency', currency: 'NGN' }));
                                  }
                                },

                                error: function (xhr, status, error) {
                                  $("#loadingScreen").hide();
                                  const paymentButton = document.getElementById("paymentButton");
                                  paymentButton.removeAttribute("disabled");
                                  $('#error-model').modal('show');
                                  $("#error-message").text(error);
                                }
                              });

                            });
                          });

                        </script>
                        <!-- Payment model -->
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
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