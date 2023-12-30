<?php require("settings.php");

if (isset($_GET['user'])) {
  $user_email = $_GET['user'];
} else {
  header('Location: ' . $_SERVER['HTTP_REFERER']);
  exit();
}

  


$stmt = $pdo->prepare("
    SELECT 
        u.*,
        IFNULL(SUM(t.amount + t.fee), 0) AS total_amount,
        COUNT(CASE WHEN t.status = 'Completed' THEN 1 ELSE NULL END) AS completed_transactions
    FROM users u
    LEFT JOIN transactions t ON u.email = t.user
    WHERE u.email = :userEmail
    GROUP BY u.email
");

$stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  $bal = $row['balance'];
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $phone_number = $row['phone'];
  $address = $row['address'];
  $state = $row['state'];
  $type = $row['type'];
  $status = $row['status'];
  $create_datetime = $row["create_datetime"];
  $user_name = $row['first_name'] . " " . $row['last_name'];
  $_SESSION['user_balance'] = $bal;

  if (is_null($bal)) {
    $balance = "0.00";
  } else {
    $balance = $row['balance'];
  }

  $total_amount = $row['total_amount'];
  $completed_transactions = $row['completed_transactions'];

  // Now you have the user's information, total amount, and completed transactions
} else {
  // Handle the case where no user is found

}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Account settings - Account | ViraSub</title>

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
    #top-card-mobile {
      display: none;
    }

    @media (max-width: 767px) {
      #top-card-desktop {
        display: none;
      }

      #top-card-mobile {
        display: block;
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
          <li class="menu-item active">
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

          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
              <div id="toastContainer"></div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                  <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="bx bx-link-alt me-1"></i>
                      User</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="activities.php?user=<?= $user_email ?>"><i class="bx bx-bell me-1"></i>
                      Activities</a>
                  </li>
                </ul>
                <div class="card mb-4">
                  <h5 class="card-header">Account Status: <span
                      style="color: <?php echo $status === "Deactivated" ? "red" : "green"; ?>">
                      <?php echo $status === "Deactivated" ? "Deactivated" : "Active"; ?></span></h5>

                  <!-- Account -->

                  <!-- Desktop Card -->
                  <div class="card-body" id="top-card-desktop">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                      <img src="icons/user.jpg" alt="user-avatar" class="d-block rounded" height="200" width="200"
                        id="uploadedAvatar" />
                      <div class="button-wrapper">
                        <table class="table" style="text-align: left">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Date Registered:</th>
                            <td>
                            <?php $dt = date("j F, Y", strtotime($create_datetime));
                            echo $dt; ?>
                            </td>
                          </tr>
                          <tr>
                            <th>User Balance:</th>
                            <td>
                              <?php echo number_format($balance, 2, '.', ','); ?>
                            </td>
                          </tr>
                        </table>
                        <br>
                        <br>
                      </div>
                      <div>
                        <table class="table" style="text-align: left">
                          <tr>
                            <th></th>
                            <td></td>
                          </tr>
                          <tr>
                            <th>Total Transactions:</th>
                            <td>
                              <?php echo $completed_transactions ?>
                            </td>
                          </tr>
                          <tr>
                            <th>Total Spent:</th>
                            <td>
                              <?php echo number_format($total_amount, 2, '.', ','); ?>
                            </td>
                          </tr>
                        </table>
                        <br>
                        <br>
                      </div>
                    </div>
                  </div>




                  <!-- Mobile Card -->
                  <div class="card-body" id="top-card-mobile">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                      <img src="icons/user.jpg" alt="user-avatar" class="d-block rounded" height="300%" width="300"
                        id="uploadedAvatar" />
                    </div>
                    <div class="button-wrapper">
                      <table class="table" style="text-align: left">
                        <tr>
                          <th></th>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Date Registered:</th>
                          <td>
                            <div style="font-size: 14px">25 AUG, 2023</div>
                          </td>
                        </tr>
                        <tr>
                          <th>User Balance:</th>
                          <td>
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </td>
                        </tr>
                      </table>
                      <br>
                      <br>
                    </div>
                    <div>
                      <table class="table" style="text-align: left">
                        <tr>
                          <th></th>
                          <td></td>
                        </tr>
                        <tr>
                          <th>Total Transactions:</th>
                          <td>
                            <?php echo $completed_transactions ?>
                          </td>
                        </tr>
                        <tr>
                          <th>Total Spent:</th>
                          <td>
                            <?php echo number_format($total_amount, 2, '.', ','); ?>
                          </td>
                        </tr>
                      </table>
                      <br>
                      <br>
                      <label class="btn btn-warning me-2 mb-4" tabindex="0">
                        <a style="color: white;" class="d-sm-block" href="fund_wallet.php?email=<?= $user_email ?>">Fund
                          User Account</a>
                      </label>
                    </div>

                  </div>


                </div>
                <hr class="my-0" />
                <div class="card-body">
                  <div class="row">
                    <div class="mb-3 col-md-6">
                      <label for="firstName" class="form-label">First Name</label>
                      <input class="form-control" type="text" style="color: black" value="<?php echo $first_name; ?>"
                        disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="lastName" class="form-label">Last Name</label>
                      <input class="form-control" type="text" style="color: black" value="<?php echo $last_name; ?>"
                        disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="email" class="form-label">E-mail</label>
                      <input class="form-control" type="text" id="email" style="color: black"
                        value="<?php echo $user_email; ?>" disabled />
                    </div>
                    <div class="mb-3 col-md-6">
                      <label for="organization" class="form-label">Phone Number</label>
                      <input class="form-control" type="text" style="color: black" value="<?php echo $phone_number; ?>"
                        disabled />
                    </div>

                    <div class="mb-3 col-md-6">
                      <label for="address" class="form-label">Address</label>
                      <input class="form-control" type="text" style="color: black" value="<?php echo $address; ?>"
                        disabled />
                    </div>

                    <div class="mb-3 col-md-6">
                      <label class="form-label" for="country">State</label>
                      <input class="form-control" type="text" style="color: black" value="<?php echo $state; ?>"
                        disabled />
                    </div>
                  </div>
                </div>
                <!-- /Account -->
              </div>
              <div class="card">
                <h5 class="card-header">Deactivate Account</h5>
                <div class="card-body">
                  <?php if ($status === "Active") { ?>
                    <div class="mb-3 col-12 mb-0">
                      <div class="alert alert-warning">
                        <h6 class="alert-heading fw-bold mb-1">Are you sure you want to deactivate this user account?</h6>
                        <p class="mb-0">Once you deactivate the account, the user will be notified of this action via
                          email.</p>
                      </div>
                    </div>
                    <form id="formAccountDeactivation" onsubmit="return false">
                      <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                        <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                      </div>
                      <button type="submit" class="btn btn-danger deactivate-account"
                        id="deactivate-user-button">Deactivate Account</button>
                    </form>
                  <?php } else { ?>
                    <p>This account is currently Deactivated.</p>
                    <button class="btn btn-success activate-account" id="activate-user-button">Activate Account</button>
                  <?php } ?>
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

  <!-- update user -->
  <script>
    $(document).ready(function () {
      // Attach click event to the "Save changes" button
      $("#deactivate-user-button").on("click", function () {

        var email = $("#email").val();
        var action = "deactivate";

        // Send AJAX request to the update page
        $.ajax({
          url: "modify_user_status.php", // Replace with your PHP script
          type: "POST",
          data: {
            user_email: email,
            action: action
          },
          success: function (response) {
            const toastContainer = document.getElementById("toastContainer");

            // Create a toast element
            const toastElement = document.createElement("div");
            toastElement.classList.add("toast");
            toastElement.setAttribute("role", "alert");
            toastElement.setAttribute("data-bs-autohide", "true");
            toastElement.setAttribute("data-bs-delay", "3000"); // 3 seconds

            // Set the toast content based on the response
            if (response.success) {
              toastElement.classList.add("bg-success");
              toastElement.innerHTML = `
                  <div class="toast-header">
                    <strong class="me-auto">Success</strong>
                    <small>Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">${response.message}</div>
                `;

              // Refresh the page after 2 seconds
              setTimeout(function () {
                location.reload();
              }, 2000);

            } else {
              toastElement.classList.add("bg-danger");
              toastElement.innerHTML = `
                  <div class="toast-header">
                    <strong class="me-auto">Error</strong>
                    <small>Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                  </div>
                  <div class="toast-body">${response.message}</div>
                `;
            }

            // Append the toast to the container and show it
            toastContainer.appendChild(toastElement);
            new bootstrap.Toast(toastElement).show();
          },

          error: function () {
            // Handle error if needed
          }
        });
      });


      $("#activate-user-button").on("click", function () {

        var email = $("#email").val();
        var action = "activate";

        // Send AJAX request to the update page
        $.ajax({
          url: "modify_user_status.php", // Replace with your PHP script
          type: "POST",
          data: {
            user_email: email,
            action: action
          },
          success: function (response) {
            const toastContainer = document.getElementById("toastContainer");

            // Create a toast element
            const toastElement = document.createElement("div");
            toastElement.classList.add("toast");
            toastElement.setAttribute("role", "alert");
            toastElement.setAttribute("data-bs-autohide", "true");
            toastElement.setAttribute("data-bs-delay", "3000"); // 3 seconds

            // Set the toast content based on the response
            if (response.success) {
              toastElement.classList.add("bg-success");
              toastElement.innerHTML = `
          <div class="toast-header">
            <strong class="me-auto">Success</strong>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">${response.message}</div>
        `;

              // Refresh the page after 2 seconds
              setTimeout(function () {
                location.reload();
              }, 2000);

            } else {
              toastElement.classList.add("bg-danger");
              toastElement.innerHTML = `
          <div class="toast-header">
            <strong class="me-auto">Error</strong>
            <small>Now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">${response.message}</div>
        `;
            }

            // Append the toast to the container and show it
            toastContainer.appendChild(toastElement);
            new bootstrap.Toast(toastElement).show();
          },

          error: function () {
            // Handle error if needed
          }
        });
      });
    });

  </script>

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
  <script src="../assets/js/pages-account-settings-account.js"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>