<?php
require('settings.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['purchase-select'])) {
  $purchaseSelect = $_POST['purchase-select'];
  switch ($purchaseSelect) {
    case 'airtime':
      header('Location: airtime.php');
      exit();
    case 'data':
      header('Location: data.php');
      exit();
    case 'bill':
      header('Location: bill.php');
      exit();
    case 'cable':
      header('Location: cable.php');
      exit();
    default:
    // Handle other cases or show an error message
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

  <title>Dashboard | ViraSub</title>

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

  <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <style>
    .card {
      border-radius: 20px;
      box-shadow: 0px 0px 5px rgba(3, 36, 0, 0.3);
    }


    @media (max-width: 768px) {
      .desktop {
        display: none;
        min-width: 720px;
      }

      .bal_desktop {
        display: none;
        min-width: 720px;
      }

      .select-card__label p {
        display: none;
      }

      .select-card__label h3 {
        font-weight: 400;
        font-size: 14px;
      }

      .modal-body h1 {
        font-size: 16px;
      }

      .close-model-credit {
        display: none;
      }
    }


    @media (min-width: 768px) {
      .mobile {
        display: none;
        min-width: 720px;
      }

      .bal_mobile {
        display: none;
        min-width: 720px;
      }

      .select-card__label h3 {
        font-weight: 400;
        font-size: 14px;
      }

    }




    .balance_cardItems p {
      color: rgba(255, 255, 255, 0.8);
      transition: all 0.3s ease-in-out;
    }

    .balance_cardItems p:hover {
      color: white;
      font-weight: 400;
    }

    .balanceCard::before {
      /* Create the overlay effect */
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('Frame.png');
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
    }

    .myMenu01::before {
      /* Create the overlay effect */
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 255, 0.4);
      background-image: url('Frame.png');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .card a {
      z-index: 1;
    }

    .myMenu02::before {
      /* Create the overlay effect */
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 255, 0, 0.4);
      background-image: url('Frame.png');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .myMenu03::before {
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 0, 0.4);
      background-image: url('Frame.png');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .myMenu04::before {
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 0, 0, 0.4);
      background-image: url('Frame.png');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .myMenu05::before {
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 128, 128, 0.4);
      background-image: url('Frame.png');
      background-size: cover;
      background-position: center;
      opacity: 0.1;
    }

    .number {
      display: inline-block;
      width: 1.5rem;
      height: 1.5rem;
      line-height: 1.5rem;
      text-align: center;
      background-color: #14a39a;
      color: #ffffff;
      border-radius: 15%;
      font-weight: bold;
      margin-bottom: 0.5rem;
      margin-right: 0.5rem;
    }

    .selection {
      border-radius: 15px;
      box-shadow: 0px 0px 5px rgba(3, 36, 0, 0.3);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .selection__title {
      font-size: 24px;
      margin: 15px;
    }

    .selection__items {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      justify-content: center;
    }

    .select-card {
      padding: 15px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: background-color 0.2s, transform 0.2s;
      cursor: pointer;
      position: relative;
      border: 2px solid #fff;
    }

    .selection__items a {
      decoration: none;
    }

    .select-card:hover {
      background-color: #fb9149;
      color: black;
      transform: scale(1.02);
    }

    .select-card__input {
      display: none;
    }

    .select-card__input:checked+.select-card__label p {
      color: #14a39a;
    }

    .select-card__input:checked+.select-card__label h3 {
      color: black;
    }


    .select-card__label {
      padding: 12px;
    }

    .select-card__label__title {
      font-size: 14px;
      font-weight: bold;
      margin: 0;
    }

    .select-card__label p {
      font-size: 12px;
      margin-top: 10px;
    }

    .checkbox {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 20px;
      height: 20px;
      background-color: #fff;
      border: 2px solid #14a39a;
      border-radius: 50%;
      cursor: pointer;
    }

    .select-card__input:checked+.select-card__label .checkbox::before {
      content: "";
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 2px;
      height: 6px;
      border: solid coral;
      border-width: 0 3px 3px 0;
      display: inline-block;
      padding: 3px;
      border-radius: 2px;
      transition: transform 0.2s ease;
      transform-origin: center;
    }

    .select-card__input:checked+.select-card__label .checkbox::before {
      transform: translate(-50%, -50%) rotate(45deg);
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
          <li class="menu-item active">
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
            <a href="transactions.php" class="menu-link">
              <i> <img src="icons/history.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="Transaction History">Transaction History</div>
            </a>
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


          <!-- Error Model -->

          <!-- Include Bootstrap and jQuery -->
          <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
          <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

          <!-- Error Model -->
          <div class="modal fade" id="modelError" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="text-align: center">
                <div class="modal-body">
                  <div class="modal-header" style=" justify-content: center;">
                    <img src="red.png" alt="Failed" srcset=""
                      style="max-width: 30%; max-height: 30%; align-items: center">
                  </div>
                  <strong>Operation Unsuccessful.</strong>
                  <p id="status_report"></p>
                </div>
              </div>
            </div>
          </div>


          <div class="modal fade" id="select-purchase-option" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document" style=" border-radius: 20px">
              <div class="modal-content" style="text-align: center;">
                <form data-v-3a6f080c="" class="selection" method="POST">
                  <div class="modal-body">
                    <h1 data-v-3a6f080c="" class="selection__title">
                      What would you like to purchase?
                    </h1>
                    <div data-v-3a6f080c="" class="selection__items selection__items--wrap cards">
                      <label class="select-card selection__item" data-v-3a6f080c="" for="select-card__1fw0ahc0v"><input
                          required name="purchase-select" type="radio" class="select-card__input" value="airtime"
                          id="select-card__1fw0ahc0v"> <label class="select-card__label" for="select-card__1fw0ahc0v">
                          <h3 class="select-card__label__title">Airtime Purchase</h3>
                          <p>Recharge your mobile anytime, anywhere with seamless Airtime Purchase service.</p>
                          <div class="checkbox"></div>
                        </label>
                      </label>
                      <label class="select-card selection__item" data-v-3a6f080c="" for="select-card__oy6wafzri"><input
                          required name="purchase-select" type="radio" class="select-card__input" value="data"
                          id="select-card__oy6wafzri"> <label class="select-card__label" for="select-card__oy6wafzri">
                          <h3 class="select-card__label__title">Subscribe Data</h3>
                          <p>Stay connected with lightning-fast data plans. Subscribe to data services with ease.</p>
                          <div class="checkbox"></div>
                        </label>
                      </label>
                      <label class="select-card selection__item" data-v-3a6f080c="" for="select-card__x9cwa4e7j"><input
                          required name="purchase-select" type="radio" class="select-card__input" value="bill"
                          id="select-card__x9cwa4e7j"> <label class="select-card__label" for="select-card__x9cwa4e7j">
                          <h3 class="select-card__label__title">Bill Payment</h3>
                          <p>Conveniently pay your bills with Bill Payment service. Simplify your financial
                            transactions.</p>
                          <div class="checkbox"></div>
                        </label>
                      </label>
                      <label class="select-card selection__item" data-v-3a6f080c="" for="select-card__48hcc8mnb"><input
                          required name="purchase-select" type="radio" class="select-card__input" value="cable"
                          id="select-card__48hcc8mnb"> <label class="select-card__label" for="select-card__48hcc8mnb">
                          <h3 class="select-card__label__title">Cable subscription</h3>
                          <p>Enjoy your favorite shows with hassle-free Cable Subscription. Access a world of
                            entertainment at your fingertips.</p>
                          <div class="checkbox"></div>
                        </label></label>
                    </div>
                  </div>
                  <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                    <button type="submit" class="btn btn-warning" id="" style="min-width:45%">Proceed</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Reseller -->
          <div class="modal fade" id="reseller" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="text-align: center">
                <div class="modal-body">
                  <strong style="font-size: large;">Reseller Account</strong>
                  <p>Request Account Funding History?</p>
                </div>
                <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                  <button type="button" id="close-model" class="btn btn-outline-secondary close-model-credit"
                    data-bs-dismiss="modal" style="min-width:45%">Close</button>
                  <button type="button" class="btn btn-warning" id="" style="min-width:45%"> <i
                      class="bx bx-money me-1"></i>Send Request</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Credit Reseller -->
          <div class="modal fade" id="credit-info" tabindex="-1" aria-hidden="true" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="text-align: center">
                <div class="modal-body">
                  <strong style="font-size: large;">Credit Your Reseller Account</strong>
                  <table class="table" style="text-align: left;">
                    <tr>
                      <th></th>
                      <td></td>
                    </tr>
                    <tr>
                      <p>Fund the bank account below and your reseller account will be automatically credited</p>
                    </tr>
                    <tr>
                      <th>Bank Name:</th>
                      <td>
                        First Bank PLC
                      </td>
                    </tr>
                    <tr>
                      <th>Account Name:</th>
                      <td>
                        Abdulkarim Hussain
                      </td>
                    </tr>
                    <tr>
                      <th>Account Number:</th>
                      <td>
                        3135350530
                      </td>
                    </tr>
                    <tr>
                      <th>Fee:</th>
                      <td>₦
                        50
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="modal-footer" style="min-width: 100%; justify-content: center;">
                  <button type="button" id="close-model" class="btn btn-outline-secondary close-model-credit"
                    data-bs-dismiss="modal" style="min-width:45%">Close</button>
                  <button type="button" class="btn btn-warning" id="" style="min-width:45%"> <i
                      class="bx bx-money me-1"></i> Credit From Flutterwave</button>
                </div>
              </div>
            </div>
          </div>


          <!-- Credit Reseller End -->

          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-8 mb-4 order-0">
                <div class="card balanceCard"
                  style="position: relative; background-color: #14a39a; box-shadow: 0px 0px 5px #14a39a; padding-bottom:0px">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                      <div class="card-body" style="padding-bottom: 0">
                        <h5 class="card-title" style="color: white; font-weight: normal;">Available Profit Balance
                        </h5>
                        <p class="mb-4" style="color: white; padding-bottom: 0px ">

                          <span class="mb-4 bal_mobile"
                            style="color: white; font-weight: bolder; font-size: 30px">Total: ₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </span>
                          <span class="mb-4 bal_desktop"
                            style="color: white; font-weight: bolder; font-size: 34px">Total: ₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </span>
                        </p>
                        <span class="justify-content-between" style="display: flex; padding-bottom: 0px; z-index: -2">
                          <a href="data.php" style="z-index: 1">
                            <div class="balance_cardItems text-center">
                              <img src="icons/purchase_menu.png" alt="" srcset=""
                                style="width: 25%; padding-bottom: 5px">
                              <p>Withdraw</p>
                            </div>
                          </a>
                          <a href="#" id="credit-reseller" data-bs-toggle="modal" data-bs-target="#credit-info">
                            <div class="balance_cardItems text-center">
                              <img src="icons/wallet_menu.png" alt="" srcset="" style="width: 25%; padding-bottom: 5px">
                              <p>Add Money</p>
                            </div>
                          </a>

                          <a href="users.php" style="z-index: 1">
                            <div class="balance_cardItems text-center">
                              <img src="icons/history_menu.png" alt="" srcset=""
                                style="width: 25%; padding-bottom: 5px">
                              <p>Users</p>
                            </div>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left desktop">
                      <div class="card-body pb-0 px-0 px-md-4">
                        <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140"
                          alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                          data-app-light-img="illustrations/man-with-laptop-light.png" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <a href="#" data-bs-toggle="modal"
                          data-bs-target="#reseller">
                      <div class="card myMenu01">
                        <div class="card-body" style="z-index: 1">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/data.png" alt="chart success" class="rounded" />
                            </div>
                          </div>
                          <div>
                            <span style="color: black">Reseller </br> Account</span>
                            <h5 class="card-title text-nowrap mb-2" style="color: black">₦ 13,000</h5>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>
                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <a href="flutterwave.php">
                      <div class="card myMenu02">
                        <div class="card-body" style="z-index: 1">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/cable.png" alt="Credit Card" class="rounded" />
                            </div>
                          </div>
                          <span style="color: black">Flutterwave </br> Account</span>
                          <h5 class="card-title text-nowrap mb-2" style="color: black">₦ 13,000</h5>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
              <!-- Total Revenue -->
              <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4 desktop">
                <div class="col-md mb-4 mb-md-0">
                  <span class="text-dark fw-semibold" style="font-size: large;">How to?</span>
                  <div class="accordion mt-3" id="accordionExample">
                    <div class="card accordion-item active">
                      <h2 class="accordion-header" id="headingOne">
                        <button type="button" class="accordion-button" data-bs-toggle="collapse"
                          data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne"
                          style="color: black">
                          How to buy airtime
                        </button>
                      </h2>
                      <div id="accordionOne" class="accordion-collapse collapse show"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span class="number">1</span> Click on "Buy Airtime." <br />
                          <span class="number">2</span> Select your network and provide the phone number to recharge.
                          <br />
                          <span class="number">3</span> Enter your desired amount you ant to recharge. <br />
                          <span class="number">4</span> Click "Purchase" to trigger a confirmation popup. <br />
                          <span class="number">5</span> Confirm your details and wait a few seconds for the
                          recharge.<br />
                        </div>
                      </div>
                    </div>
                    <div class="card accordion-item">
                      <h2 class="accordion-header" id="headingTwo">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                          data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo"
                          style="color: black">
                          How to subscribe data plan
                        </button>
                      </h2>
                      <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span class="number">1</span> Click on Buy Data. <br />
                          <span class="number">2</span> Select your network first and your preferred plan.<br />
                          <span class="number">3</span> Provide the phone number you want to subscribe. <br />
                          <span class="number">4</span> Click on Purchase and a model will popup to confirm details.
                          <br />
                          <span class="number">5</span> Click on confirm and wait few seconds to be recharged. <br />
                        </div>
                      </div>
                    </div>
                    <div class="card accordion-item">
                      <h2 class="accordion-header" id="headingThree">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                          data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree"
                          style="color: black">
                          How to pay Electricity Bills
                        </button>
                      </h2>
                      <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span class="number">1</span> Click on Utility Bills. <br />
                          <span class="number">2</span> Select your disco and meter type. <br />
                          <span class="number">3</span> Input your meter number and the amount you want to pay.<br />
                          <span class="number">4</span> Click on verify button to verify our details before
                          proceeding.<br />
                          <span class="number">5</span> Click on confirm on the verification model and wait few seconds
                          to be recharged. <br />
                        </div>
                      </div>
                    </div>
                    <div class="card accordion-item">
                      <h2 class="accordion-header" id="headingFour">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                          data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour"
                          style="color: black">
                          How to subscribe tv
                        </button>
                      </h2>
                      <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                          <span class="number">1</span> Click on Cable Sub. <br />
                          <span class="number">2</span> Select your provider and the plan you want to subscribe. <br />
                          <span class="number">3</span> Input your IUC (decoder number). <br />
                          <span class="number">4</span> Click on verify button to verify our details before
                          proceeding.<br />
                          <span class="number">5</span> Click on confirm on the verification model and wait few seconds
                          to be recharged. <br />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Total Revenue -->
              <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="row">
                  <div class="col-6 mb-4">
                    <a href="users.php">
                      <div class="card myMenu03">
                        <div class="card-body" style="z-index: 1">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/utility.png" alt="Credit Card" class="rounded" />
                            </div>
                          </div>
                          <div>
                            <span style="color: black">Users </br> Balance</span>
                            <h5 class="card-title text-nowrap mb-2" style="color: black">₦ 13,000</h5>
                          </div>
                        </div>
                      </div>
                    </a>
                  </div>


                  <div class="col-6 mb-4">
                    <div class="card myMenu04">
                      <div class="card-body" style="z-index: 1">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="#" id="credit-reseller" data-bs-toggle="modal"
                            data-bs-target="#select-purchase-option">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/airtime.png" alt="Credit Card" class="rounded" />
                            </div>
                          </a>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="cardOpt1">
                              <a class="dropdown-item" href="purchase_history.php">View History</a>
                            </div>
                          </div>
                        </div>
                        <a href="#" id="credit-reseller" data-bs-toggle="modal"
                          data-bs-target="#select-purchase-option">
                          <div>
                            <span style="color: black">Manual</span>
                            <h5 class="card-title text-nowrap mb-2" style="color: black">Purchase</h5>
                            <small class="text-success fw-semibold">Cable, Data ...</small>

                          </div>
                        </a>
                      </div>

                    </div>
                  </div>
                </div>
                <!-- Tutorials -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4 mobile">
                  <div class="col-md mb-4 mb-md-0">
                    <span class="text-dark fw-semibold" style="font-size: large;">How to?</span>
                    <div class="accordion mt-3" id="accordionExample">
                      <div class="card accordion-item active">
                        <h2 class="accordion-header" id="headingOne">
                          <button type="button" class="accordion-button" data-bs-toggle="collapse"
                            data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne"
                            style="color: black">
                            How to buy airtime
                          </button>
                        </h2>
                        <div id="accordionOne" class="accordion-collapse collapse show"
                          data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <span class="number">1</span> Click on "Buy Airtime." <br />
                            <span class="number">2</span> Select your network and provide the phone number to
                            recharge.
                            <br />
                            <span class="number">3</span> Enter your desired amount you want to recharge. <br />
                            <span class="number">4</span> Click "Purchase" to trigger a confirmation popup. <br />
                            <span class="number">5</span> Confirm your details and wait a few seconds for the
                            recharge.<br />
                          </div>
                        </div>
                      </div>
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                            data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo"
                            style="color: black">
                            How to subscribe data plan
                          </button>
                        </h2>
                        <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                          data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <span class="number">1</span> Click on Buy Data. <br />
                            <span class="number">2</span> Select your network first and your preferred plan.<br />
                            <span class="number">3</span> Provide the phone number you want to subscribe. <br />
                            <span class="number">4</span> Click on Purchase and a model will popup to confirm details.
                            <br />
                            <span class="number">5</span> Click on confirm and wait few seconds to be recharged.
                            <br />
                          </div>
                        </div>
                      </div>
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                            data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree"
                            style="color: black">
                            How to pay Electricity Bills
                          </button>
                        </h2>
                        <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                          data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <span class="number">1</span> Click on Utility Bills. <br />
                            <span class="number">2</span> Select your disco and meter type. <br />
                            <span class="number">3</span> Input your meter number and the amount you want to
                            pay.<br />
                            <span class="number">4</span> Click on verify button to verify our details before
                            proceeding.<br />
                            <span class="number">5</span> Click on confirm on the verification model and wait few
                            seconds to be recharged. <br />
                          </div>
                        </div>
                      </div>
                      <div class="card accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                          <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                            data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour"
                            style="color: black">
                            How to subscribe tv
                          </button>
                        </h2>
                        <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                          data-bs-parent="#accordionExample">
                          <div class="accordion-body">
                            <span class="number">1</span> Click on Cable Sub. <br />
                            <span class="number">2</span> Select your provider and the plan you want to subscribe.
                            <br />
                            <span class="number">3</span> Input your IUC (decoder number). <br />
                            <span class="number">4</span> Click on verify button to verify our details before
                            proceeding.<br />
                            <span class="number">5</span> Click on confirm on the verification model and wait few
                            seconds to be recharged. <br />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Tutorials -->
                <div class="row">
                  <div class="col-12 mb-4">
                    <div class="card myMenu05">
                      <div class="card-body" style="z-index: 1">
                        <h5 class="card-title">WhatsApp Customer Support</h5>
                        <p class="card-text">Have questions or need assistance? Reach out to our customer support on
                          WhatsApp.</p>
                        <a href="https://wa.me/message/MPIMCPUUGT3WK1" target="_blank" class="btn btn-warning"
                          style="background-color: #fb9149;">
                          <i class='bx bxl-whatsapp'></i> Chat Now
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- Order Statistics -->

              <?php
              // Fetch transaction data from the database
              $query = "SELECT * FROM transactions WHERE user = :userEmail";
              $stmt = $pdo->prepare($query);
              $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
              $stmt->execute();
              $transactionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

              // Calculate totals and sums
              $totalPurchase = count($transactionData);
              $sumData = 0;
              $sumCable = 0;
              $sumUtility = 0;
              $sumAirtime = 0;

              foreach ($transactionData as $transaction) {
                $amount = intval($transaction['amount']);

                if ($transaction['type'] === 'Data') {
                  $sumData += $amount;
                } elseif ($transaction['type'] === 'Cable') {
                  $sumCable += $amount;
                } elseif ($transaction['type'] === 'Utility') {
                  $sumUtility += $amount;
                } elseif ($transaction['type'] === 'Airtime') {
                  $sumAirtime += $amount;
                }
              }
              ?>
              <div class="col-md-6 col-lg-6 order-2 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                      <h5 class="m-0 me-2" style="color: black">Lifetime Order Statistics</h5>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div class="d-flex flex-column align-items-center gap-1">
                        <h2 class="mb-2" style="color: black">
                          <?php echo $totalPurchase ?>
                        </h2>
                        <span style="color: black">Total Purchase</span>
                      </div>
                      <!-- <div id="orderStatisticsChart"></div> -->
                      <!-- <div style="height: 5%">

                        </div> -->
                      <img src="payment.png" alt="" srcset="" style="width: 25%;">
                    </div>
                    <ul class="p-0 m-0">
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-primary"><i> <img src="icons/data.png"
                                style="width:20px"> </i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0" style="color: black">Data</h6>
                            <small class="text-muted">MTN, GLO, Airtel, 9Mobile</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold" style="color: black">
                              <?php echo "₦ " . $sumData; ?>
                            </small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-success"><i> <img src="icons/cable.png"
                                style="width:20px"> </i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0" style="color: black">Cable TV</h6>
                            <small class="text-muted">Startimes, DSTV, GoTV</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold" style="color: black">
                              <?php echo "₦ " . $sumCable; ?>
                            </small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-info"><i> <img src="icons/utility.png"
                                style="width:20px"> </i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0" style="color: black">Utility</h6>
                            <small class="text-muted">KADCO, Auja Electricity, etc</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold" style="color: black">
                              <?php echo "₦ " . $sumUtility; ?>
                            </small>
                          </div>
                        </div>
                      </li>
                      <li class="d-flex">
                        <div class="avatar flex-shrink-0 me-3">
                          <span class="avatar-initial rounded bg-label-secondary"><i> <img src="icons/airtime.png"
                                style="width:20px"> </i></span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                          <div class="me-2">
                            <h6 class="mb-0" style="color: black">Airtime</h6>
                            <small class="text-muted">MTN, Airtel, Glo, 9Mobile</small>
                          </div>
                          <div class="user-progress">
                            <small class="fw-semibold" style="color: black">
                              <?php echo "₦ " . $sumAirtime; ?>
                            </small>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>

                </div>
              </div>


              <!-- Transactions -->
              <div class="col-md-6 col-lg-6 order-2 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2" style="color: black">Wallet Funding</h5>
                    <div class="dropdown">
                      <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                        <a class="dropdown-item" href="fund_wallet.php">View All</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php
                    $query = "SELECT * FROM funding ORDER BY date DESC LIMIT 6";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $fundingData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($fundingData) === 0) {
                      echo '<p class="text-center">No Data Yet!</p>';
                    } else {
                      ?>
                      <ul class="p-0 m-0" id="fundingList">
                        <?php
                        foreach ($fundingData as $funding) {
                          $mode = $funding['type'];
                          $date = date("j F, Y", strtotime($funding['date']));
                          $amount = "+{$funding['amount']}";
                          $iconClass = '';

                          if ($mode === 'USSD') {
                            $iconClass = 'mobile.png';
                          } elseif ($mode === 'Bank') {
                            $iconClass = 'bank.png';
                          } elseif ($mode === 'Credit card') {
                            $iconClass = '../assets/img/icons/unicons/cc-success.png';
                          } elseif ($mode === 'Bank Transfer') {
                            $iconClass = '../assets/img/icons/unicons/wallet.png';
                          }
                          ?>
                          <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                              <img src="<?= $iconClass ?>" alt="User" class="rounded" />
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                              <div class="me-2">
                                <small class="text-muted d-block mb-1 bl">
                                  <?= $mode ?>
                                </small>
                                <h6 class="mb-0">
                                  <?= $date ?>
                                </h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-1">
                                <h6 class="mb-0" style="color: black">
                                  <?= $amount ?>
                                </h6>
                                <span class="text-muted">NGN</span>
                              </div>
                            </div>
                          </li>
                        <?php } ?>
                      </ul>
                    <?php } ?>
                  </div>
                </div>
              </div>

              <!--/ Transactions -->
            </div>

          </div>

          <!-- Content -->


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
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../assets/js/dashboards-analytics.js"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>