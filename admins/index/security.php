<?php
require("settings.php");
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Account settings - Security | ViraSub</title>

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
              <li class="menu-item active">
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

            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                  <li class="nav-item">
                    <a class="nav-link" href="account.php"><i class="bx bx-user me-1"></i>
                      Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="settings_page.php"><i class="bx bx-bell me-1"></i>
                      Settings</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="bx bx-link-alt me-1"></i>
                      Security</a>
                  </li>
                </ul>
                <div class="row">
                  <div class="col-md-12 col-12 mb-md-0 mb-4">
                    <div class="card">
                      <h5 class="card-header">Change Password</h5>
                      <div class="card-body">
                        <!-- Change Password -->
                        <form id="passwordChangeForm" method="post">
                          <div>
                            <div class="form-password-toggle">
                              <label class="form-label" for="oldPassword">Current Password</label>
                              <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="oldPassword" name="oldPassword"
                                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                  aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                    class="bx bx-hide"></i></span>
                              </div>
                            </div>
                          </div>
                          <div>
                            <div class="form-password-toggle">
                              <label class="form-label" for="newPassword">New Password</label>
                              <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="newPassword" name="newPassword"
                                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                  aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                    class="bx bx-hide"></i></span>
                              </div>
                            </div>
                          </div>
                          <div>
                            <div class="form-password-toggle">
                              <label class="form-label" for="confirmPassword">Confirm New Password</label>
                              <div class="input-group input-group-merge">
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword"
                                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                  aria-describedby="basic-default-password" />
                                <span class="input-group-text cursor-pointer" id="basic-default-password"><i
                                    class="bx bx-hide"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="mt-4">
                            <button type="button" class="btn btn-warning me-2" id="savePasswordBtn">Save
                              changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Discard</button>
                          </div>
                        </form>

                        <div id="passwordChangeToastContainer" class="position-fixed top-3 end-0 p-3">
                          <div id="passwordChangeToast" class="toast hide" role="alert" aria-live="assertive"
                            aria-atomic="true">
                            <div class="toast-header">
                              <strong class="me-auto">Status</strong>
                              <small>Now</small>
                              <button type="button" class="btn-close" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                            </div>
                            <div class="toast-body"></div>
                          </div>
                        </div>

                        <script>
                          document.getElementById("savePasswordBtn").addEventListener("click", function () {

                            const oldPassword = document.getElementById("oldPassword").value;
                            const newPassword = document.getElementById("newPassword").value;
                            const confirmPassword = document.getElementById("confirmPassword").value;

                            if (newPassword !== confirmPassword) {
                              // Display error toast if passwords don't match
                              const toastElement = document.getElementById("passwordChangeToast");
                              const toastBody = toastElement.querySelector(".toast-body");
                              toastElement.classList.add("bg-danger");
                              toastBody.textContent = "New passwords do not match.";
                              new bootstrap.Toast(toastElement).show();
                              return;
                            } else {
                              $.ajax({
                                url: "password_change.php",
                                type: "POST",
                                data: $('#passwordChangeForm').serialize(),
                                dataType: "json",
                                success: function (response) {
                                  const alertType = response.success ? "bg-success" : "bg-danger";
                                  const toastElement = document.getElementById("passwordChangeToast");
                                  const toastBody = toastElement.querySelector(".toast-body");

                                  toastElement.classList.add(alertType);
                                  toastBody.textContent = response.message;
                                  new bootstrap.Toast(toastElement).show();

                                  if (response.success) {
                                    setTimeout(function () {
                                      location.reload(); // Refresh the page after 2 seconds of showing success toast
                                    }, 2000);
                                  }
                                },
                                error: function () {
                                  // Display error toast for AJAX error
                                  const toastElement = document.getElementById("passwordChangeToast");
                                  const toastBody = toastElement.querySelector(".toast-body");
                                  toastElement.classList.add("bg-danger");
                                  toastBody.textContent = "An error occurred while changing the password.";
                                  new bootstrap.Toast(toastElement).show();
                                }
                              });
                            }
                          });
                        </script>
                        <!-- Change Password -->
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