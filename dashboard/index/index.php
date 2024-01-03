<?php
require('settings.php');


if (isset($_GET['tx_ref']) || $_GET['tx_ref'] === "completed") {
  $txRef = $_GET['tx_ref'];

  // Check if tx_ref exists and is in "Pending" status   
  $checkQuery = "SELECT * FROM init_transactions WHERE trx_ref = :trx_ref AND status = 'Pending'";
  $checkStmt = $pdo->prepare($checkQuery);
  $checkStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
  $checkStmt->execute();

  if ($checkStmt->rowCount() === 1) {
    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
    $amount = $row['amount'];
    $network = $row['network'];
    $plan = $row['plan'];
    $phone = $row['phone'];

    ///run sub transaction


    try {
      // Create the payload for the data subscription request
      $payload = array(
        'network' => "1",
        'phone' => "09039904194",
        'data_plan' => "7",
        'bypass' => false,
        'request-id' => 'Airtime_' . uniqid()
      );

      $maxRetries = 2;
      $retryDelay = 1000; // Retry delay in milliseconds (1000ms = 1 second)

      for ($retry = 0; $retry <= $maxRetries; $retry++) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://datasub247.com/api/data');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = [
          "Authorization: Token c9518a8f3a778f1524c26830f96f14c6474c0ac30438c18de6aa09a47831",
          'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
          $error = curl_error($ch);
          if (strpos($error, "Couldn't resolve host") === false) {
            sendErrorEmailToAdmin($error);
            throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #2473]. The deposited amount has been successfully credited to your balance.");
          } else {
            throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #4672]. The deposited amount has been successfully credited to your balance.");
          }
        }

        $responseData = json_decode($response, true);
        if ($responseData !== null) {
          break;
        }

        usleep($retryDelay * 1000); // usleep uses microseconds, so we multiply by 1000 to convert milliseconds to microseconds
      }

      if ($response) {
        $responseData = json_decode($response, true);

        $status = $responseData['status'];
        $message = $responseData['message'];

        if ($status === 'success') {
          $networkName = $responseData['network'];
          $requestId = $responseData['request-id'];
          $amount = $responseData['amount'];
          $dataplan = $responseData['dataplan'];
          $phoneNumber = $responseData['phone_number'];
          $oldBalance = $responseData['oldbal'];
          $newBalance = $responseData['newbal'];
          $system = $responseData['system'];
          $planType = $responseData['plan_type'];
          $walletVending = $responseData['wallet_vending'];
          $status = $responseData['status'];



          $profit = $amount;
          newDataTransaction($status, $networkName, $requestId, $profit, $dataplan, $message, $phoneNumber, $oldBalance, $newBalance, $system, $planType, $walletVending);




          ///update database
          $status = "Completed";

          $updateQuery = "UPDATE transactions SET status = :status WHERE trx_ref = :trx_ref";
          $updateStmt = $pdo->prepare($updateQuery);
          $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
          $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
          $updateStmt->execute();

          $updateQuery = "UPDATE init_transactions SET status = :status WHERE trx_ref = :trx_ref";
          $updateStmt = $pdo->prepare($updateQuery);
          $updateStmt->bindParam(':status', $status, PDO::PARAM_STR);
          $updateStmt->bindParam(':trx_ref', $txRef, PDO::PARAM_STR);
          $updateStmt->execute();


          $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
          $subject = "New Balance Deposit";

          $narration = "A User Just Added Funds To Their Wallet:\n\n";
          $narration .= " Transaction Reference No: $txRef";
          $narration .= " Transaction Amount: $amount";

          $headers = "From: Your App <noreply@virasub.com.ng>\r\n";
          $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

          mail($to, $subject, $narration, $headers);


          //handle error messages
        } elseif (strpos($message, "Insufficient Account") !== false) {
          update_initBalance($pdo, $amount, $user_email, $txRef);
          sendErrorEmailToManagement($message);
          throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #3933]. The deposited amount has been successfully credited to your balance.");
        } elseif (strpos($message, "This is not a") !== false) {
          update_initBalance($pdo, $amount, $user_email, $txRef);
          throw new Exception($message);
        } elseif (strpos($message, "You have Reach Daily Transaction Limit") !== false) {
          update_initBalance($pdo, $amount, $user_email, $txRef);
          sendErrorEmailToManagement($message);
          sendErrorEmailToAdmin($message);
          throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #4304]. The deposited amount has been successfully credited to your balance.");
        } else {
          //user input validation failed
          sendErrorEmailToAdmin($message);
          update_initBalance($pdo, $amount, $user_email, $txRef);
          throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #3323]. The deposited amount has been successfully credited to your balance.");
        }
      } else {
        // Error handling if the API call fails
        $error = "The code for processing API calls failed, please confirm all services are working fine.  [err_code: #3273]. The deposited amount has been successfully credited to your balance.";
        sendErrorEmailToAdmin($error);
        update_initBalance($pdo, $amount, $user_email, $txRef);
        throw new Exception("We regret to inform you that your request could not be finalized at this time. Kindly verify your internet connection or attempt again within the next 30 minutes. [err_code: #3743]. The deposited amount has been successfully credited to your balance.");
      }
    } catch (Exception $e) {
      update_initBalance($pdo, $amount, $user_email, $txRef);
      $balance = $amount;
      sendErrorEmailToAdmin("New Thrown Exception during Data API transaction. Error: $e");
      $init_error = $e->getMessage();
    }




  } else {
    $to = "abdulkarimhussain7@gmail.com, support@virasub.com.ng";
    $subject = "Balance Deposit Error";

    $message = "An error occurred during a wallet funding:\n\n";
    $message .= "A user just tried funding their wallet but the tx_ref is invalid or has already been completed. please check";
    $message .= " Transaction Reference No: $txRef";
    // $message .= " Transaction Amount: $amount";

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
    }

    @media (min-width: 768px) {
      .mobile {
        display: none;
        min-width: 720px;
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
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
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
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
    }

    .myMenu03::before {
      /* Create the overlay effect */
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 0, 0.4);
      background-image: url('Frame.png');
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
    }

    .myMenu04::before {
      /* Create the overlay effect */
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 0, 0, 0.4);
      background-image: url('Frame.png');
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
    }

    .myMenu05::before {
      /* Create the overlay effect */
      content: "";
      border-radius: 15px;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 128, 128, 0.4);
      background-image: url('Frame.png');
      /* Replace with your image path */
      background-size: cover;
      background-position: center;
      /* z-index: -1; */
      opacity: 0.1;
      /* Adjust the overlay opacity */
    }

    .number {
      display: inline-block;
      width: 1.5rem;
      height: 1.5rem;
      line-height: 1.5rem;
      text-align: center;
      background-color: #14a39a;
      /* You can use your desired color here */
      color: #ffffff;
      /* Text color */
      border-radius: 15%;
      /* Circular shape */
      font-weight: bold;
      margin-bottom: 0.5rem;
      margin-right: 0.5rem;
      /* Add spacing between number and text */
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
            <span style="margin-left:0px;" class="app-brand-text demo menu-text fw-bolder ms-2"><span style="color: #fB9149">V</span style="color: #14A39A">ira<span style="color: #fB9149">S</span style="color: #14A39A">ub</span>
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


          <div class="modal fade" id="successPurchase" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content" style="text-align: center">
                <div class="modal-header" style=" justify-content: center;">
                  <img src="handshake.png" alt="success" srcset=""
                    style="max-width: 30%; max-height: 30%; align-items: center">
                </div>
                <div class="modal-body">
                  <strong>Data Purchase Successful</strong>

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
                      <th>Data Plan:</th>
                      <td id="responsePlan"></td>
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
                  <a href="index.php">
                    <button type="button" class="btn btn-warning" id="" style="min-width:45%">Dashboard</button>
                  </a>
                </div>
              </div>
            </div>
          </div>


          <script>
            // Check if $init_error has data
            var initError = "<?php echo $init_error; ?>";
            if (initError) {
              document.getElementById("status_report").innerText = initError;
              $('#modelError').modal('show');
            }

            // Check if the PHP variables have data
            var networkName = "<?php echo $networkName; ?>";
            var requestId = "<?php echo $requestId; ?>";
            var amount = "<?php echo $amount; ?>";
            var message = "<?php echo $message; ?>";
            var phoneNumber = "<?php echo $phoneNumber; ?>";
            var dataplan = "<?php echo $dataplan; ?>";

            if (networkName !== "" && requestId !== "" && amount !== "" && message !== "" && phoneNumber !== "" && dataplan !== "") {
              // Show the modal
              $('#successPurchase').modal('show');

              // Assign values to the modal elements
              document.getElementById('responseID').innerText = requestId;
              document.getElementById('networkName').innerText = networkName;
              document.getElementById('responsePlan').innerText = dataplan;
              document.getElementById('responseAmount').innerText = amount;
              document.getElementById('responsePhone').innerText = phoneNumber;
              document.getElementById('responseMessage').innerText = message;

              // Close the modal when "Buy Again" button is clicked
              var buyAgainBtn = document.querySelector('#successPurchase .btn-outline-secondary');
              buyAgainBtn.addEventListener('click', function () {
                modal.style.display = 'none';
              });

              // You can add similar functionality for other buttons or actions in the modal
            }
          </script>

          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-8 mb-4 order-0">
                <div class="card balanceCard"
                  style="position: relative; background-color: #14a39a; box-shadow: 0px 0px 5px #14a39a; padding-bottom:0px">
                  <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                      <div class="card-body" style="padding-bottom: 0">
                        <h5 class="card-title" style="color: white; font-weight: normal;">Welcome Back,
                          <?php echo $first_name; ?> 🎉
                        </h5>
                        <p class="mb-4" style="color: white; padding-bottom: 0px ">

                          <span class="mb-4" style="color: white; font-weight: bolder; font-size: 36px">₦
                            <?php echo number_format($balance, 2, '.', ','); ?>
                          </span>
                        </p>
                        <span class="justify-content-between" style="display: flex; padding-bottom: 0px; z-index: -2">
                          <a href="data.php" style="z-index: 1">
                            <div class="balance_cardItems text-center">
                              <img src="icons/purchase_menu.png" alt="" srcset="" style="width: 25%; padding-bottom: 5px">
                              <p>Purchase</p>
                            </div>
                          </a>
                          <a href="fund_wallet.php" style="z-index: 1">
                            <div class="balance_cardItems text-center">
                              <img src="icons/wallet_menu.png" alt="" srcset="" style="width: 25%; padding-bottom: 5px">
                              <p>Fund Wallet</p>
                            </div>
                          </a>
                          <a href="fundings.php" style="z-index: 1">
                            <div class="balance_cardItems text-center">
                              <img src="icons/history_menu.png" alt="" srcset="" style="width: 25%; padding-bottom: 5px">
                              <p>View History</p>
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
                    <div class="card myMenu01">
                      <div class="card-body" style="z-index: 1">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="data.php">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/data.png" alt="chart success" class="rounded" />
                            </div>
                          </a>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                              <a class="dropdown-item" href="transactions.php?filter=Data">View History</a>
                            </div>
                          </div>
                        </div>
                        <a href="data.php">
                          <span style="color: black;">Buy</span>
                          <h4 class="card-title mb-2" style="color: black">Data</h4>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i>₦ 255/GB</small>
                        </a>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card myMenu02">
                      <div class="card-body" style="z-index: 1">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="cable.php">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/cable.png" alt="Credit Card" class="rounded" />
                            </div>
                          </a>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">

                              <a class="dropdown-item" href="transactions.php?filter=Cable">View History</a>
                            </div>
                          </div>
                        </div>
                        <a href="cable.php">
                          <span style="color: black">Cable</span>
                          <h4 class="card-title text-nowrap mb-2" style="color: black">TV</h4>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i>₦ 1,200</small>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Total Revenue -->
              <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4 desktop">
                <div class="col-md mb-4 mb-md-0">
                  <span class="text-dark fw-semibold" style="font-size: large;">How to?</span>
                  <div class="accordion mt-3" id="accordionExample">
                    <div class="card accordion-item active" >
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
                          <span class="number">2</span> Select your network and provide the phone number to recharge. <br />
                          <span class="number">3</span> Enter your desired amount you ant to recharge. <br />
                          <span class="number">4</span> Click "Purchase" to trigger a confirmation popup. <br />
                          <span class="number">5</span> Confirm your details and wait a few seconds for the recharge.<br />
                        </div>
                      </div>
                    </div>
                    <div class="card accordion-item" >
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
                          <span class="number">4</span> Click on Purchase and a model will popup to confirm details. <br />
                          <span class="number">5</span> Click on confirm and wait few seconds to be recharged. <br />
                        </div>
                      </div>
                    </div>
                    <div class="card accordion-item" >
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
                          <span class="number">4</span> Click on verify button to verify our details before proceeding.<br />
                          <span class="number">5</span> Click on confirm on the verification model and wait few seconds to be recharged. <br />
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
                          <span class="number">4</span> Click on verify button to verify our details before proceeding.<br />
                          <span class="number">5</span> Click on confirm on the verification model and wait few seconds to be recharged. <br />
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
                    <div class="card myMenu03">
                      <div class="card-body" style="z-index: 1">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="bill.php">
                            <div class="avatar flex-shrink-0">
                              <img src="icons/utility.png" alt="Credit Card" class="rounded" />
                            </div>
                          </a>
                          <div class="dropdown">
                            <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                              aria-haspopup="true" aria-expanded="false">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                              <a class="dropdown-item" href="transactions.php?filter=Utility">View History</a>
                            </div>
                          </div>
                        </div>
                        <a href="bill.php">
                          <span class="d-block mb-1" style="color: black">Utility</span>
                          <h4 class="card-title text-nowrap mb-2" style="color: black">Bills</h4>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> ₦ 50.00
                          </small>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="col-6 mb-4">
                    <div class="card myMenu04">
                      <div class="card-body" style="z-index: 1">
                        <div class="card-title d-flex align-items-start justify-content-between">
                          <a href="airtime.php">
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

                              <a class="dropdown-item" href="transactions.php?filter=Airtime">View History</a>
                            </div>
                          </div>
                        </div>
                        <a href="airtime.php">
                          <span class="fw-semibold d-block mb-1" style="color: black">Buy</span>
                          <h4 class="card-title mb-2" style="color: black">Airtime</h4>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> ₦ 50.00</small>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4 mobile">
                  <div class="col-md mb-4 mb-md-0">
                    <span class="text-dark fw-semibold" style="font-size: large;">How to?</span>
                    <div class="accordion mt-3" id="accordionExample">
                      <div class="card accordion-item active" >
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
                            <span class="number">3</span> Enter your desired amount you want to recharge. <br />
                            <span class="number">4</span> Click "Purchase" to trigger a confirmation popup. <br />
                            <span class="number">5</span> Confirm your details and wait a few seconds for the
                            recharge.<br />
                          </div>
                        </div>
                      </div>
                      <div class="card accordion-item" >
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
                            <span class="number">4</span> Click on Purchase and a model will popup to confirm details. <br />
                            <span class="number">5</span> Click on confirm and wait few seconds to be recharged. <br />
                          </div>
                        </div>
                      </div>
                      <div class="card accordion-item" >
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
                            <span class="number">4</span> Click on verify button to verify our details before proceeding.<br />
                            <span class="number">5</span> Click on confirm on the verification model and wait few seconds to be recharged. <br />
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
                            <span class="number">4</span> Click on verify button to verify our details before proceeding.<br />
                            <span class="number">5</span> Click on confirm on the verification model and wait few seconds to be recharged. <br />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="row">
                  <div class="col-12 mb-4">
                    <div class="card myMenu05">
                      <div class="card-body" style="z-index: 1">
                        <h5 class="card-title">WhatsApp Customer Support</h5>
                        <p class="card-text">Have questions or need assistance? Reach out to our customer support on
                          WhatsApp.</p>
                        <a href="https://wa.me/message/MPIMCPUUGT3WK1" target="_blank" class="btn btn-warning"
                          style="background-color: #14a39a;">
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
              $status = "Completed";
              $query = "SELECT * FROM transactions WHERE user = :userEmail AND status = :status";
              $stmt = $pdo->prepare($query);
              $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
              $stmt->bindParam(':status', $status, PDO::PARAM_STR);
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
                    <!-- <div class="dropdown">
                      <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                        <a class="dropdown-item" href="transactions.php">View All</a>
                      </div>
                    </div> -->
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
                          <span class="avatar-initial rounded bg-label-primary"><i > <img src="icons/data.png" style="width:20px"> </i></span>
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
                          <span class="avatar-initial rounded bg-label-success"><i > <img src="icons/cable.png" style="width:20px"> </i></span>
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
                          <span class="avatar-initial rounded bg-label-info"><i > <img src="icons/utility.png" style="width:20px"> </i></span>
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
                          <span class="avatar-initial rounded bg-label-secondary"><i > <img src="icons/airtime.png" style="width:20px"> </i></span>
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
                        <a class="dropdown-item" href="fundings.php">View All</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php
                    $query = "SELECT * FROM funding WHERE user = :userEmail ORDER BY date DESC LIMIT 6";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':userEmail', $user_email, PDO::PARAM_STR);
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

                          if ($mode === 'ussd') {
                            $iconClass = 'ussd.jpg';
                          } elseif ($mode === 'Bank') {
                            $iconClass = 'bank.jpg';
                          } elseif ($mode === 'card') {
                            $iconClass = 'card.jpg';
                          } elseif ($mode === 'bank_transfer') {
                            $iconClass = 'transfer.jpg';
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
  <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script src="../assets/js/dashboards-analytics.js"></script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>