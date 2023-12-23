<?php
require("settings.php");

if (isset($_GET['show_details'])) {
  $trx_ref = $_GET['show_details'];

  // Fetch the transaction details using the $transactionId
  // Modify your database query to fetch details based on the $transactionId

  // Example:
  $query = "SELECT * FROM funding WHERE trx_ref = :trx_ref";
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

  <title>Successful Deposit History | Billzwave</title>

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


  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.3/dist/css/bootstrap.min.css">

  <!-- Bootstrap JS (jQuery is required) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.3/dist/js/bootstrap.min.js"></script>


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

          <li class="menu-item active open">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
              <i> <img src="icons/histories.png" style="width: 20px; margin-right: 10px;"> </i>
              <div data-i18n="History">Transactions</div>
            </a>

            <ul class="menu-sub">
              <li class="menu-item active">
                <a href="transactions.php" class="menu-link">
                  <i> <img src="icons/history.png" style="width: 20px; margin-right: 10px;"> </i>
                  <div data-i18n="Transactions">Completed</div>
                </a>
              </li>
              <li class="menu-item">
                <a href="fundings.php" class="menu-link">
                  <i> <img src="icons/wallet_history.png" style="width: 20px; margin-right: 10px;"> </i>
                  <div data-i18n="Transaction History">Failed</div>
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

            <!-- Loading screen -->
            <div id="loadingScreen">Loading...</div>

            <!-- Details Models -->

            <?php if ($transactionDetails) { ?>
              <div class="modal fade" id="_details" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
                data-bs-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content" style="text-align: center">
                    <div class="modal-body">
                      <strong>USSD Deposit Details</strong>
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
                          <th>Type:</th>
                          <td>
                            <?php echo $_type; ?>
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
                          <td style="color: green">
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
                  $("#_details").modal("show");
                });
              </script>
            <?php } ?>

            <!-- Details Models End-->



            <!-- Deposits -->

            <div class="card">
              <span style="display: flex; justify-content: space-between; align-items: center;">
                <h5 class="card-header">Reseller Funding from flutterwave</h5>
                <div class="dropdown" style="display: flex; align-items: center; padding-right: 5%;">
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                    <a class="dropdown-item filter-option" data-filter="All" href="javascript:void(0);">All
                      Deposit</a>
                    <a class="dropdown-item filter-option" data-filter="USSD" href="javascript:void(0);">USSD
                      Deposit</a>
                    <a class="dropdown-item filter-option" data-filter="Bank" href="javascript:void(0);">Bank
                      Deposit</a>
                    <a class="dropdown-item filter-option" data-filter="Credit Card" href="javascript:void(0);">Credit Card
                      Deposit</a>
                    <a class="dropdown-item filter-option" data-filter="Bank Transfer" href="javascript:void(0);">Bank Transfer
                      Deposit</a>
                    <a class="dropdown-item filter-option" data-filter="Manual Funding"
                      href="javascript:void(0);">Manual Funding</a>
                  </div>
                </div>
              </span>

              <div class="table-responsive">
                <?php
                $status = "Completed";
                $query = "SELECT * FROM funding WHERE status = :status ORDER BY `funding`.`date` DESC";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                $stmt->execute();
                $transactionData = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Determine the current page from the query parameter, default to 1 if not set
                $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

                // Define the number of results to display per page
                $results_per_page = 25;

                // Calculate the starting index for results based on the current page
                $start_index = ($current_page - 1) * $results_per_page;

                // Slice the array of transactions to get the results for the current page
                $paginated_data = array_slice($transactionData, $start_index, $results_per_page);

                // Initialize the index for the first row on the current page
                $row_index = $start_index;

                // Calculate the total number of pages based on the paginated data
                $total_pages = ceil(count($transactionData) / $results_per_page);

                // Define how many pagination links to show before and after the current page
                $max_links = 1;

                // Calculate the range of pagination links to display
                $start_link = max($current_page, 1);
                $end_link = min($current_page + $max_links, $total_pages);

                ?>

                <table class="table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Type</th>
                      <th>Amount</th>
                      <th>User</th>
                      <th>Date</th>
                      <th>Trx_ref</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    <?php foreach ($paginated_data as $transaction): ?>
                      <tr>
                        <td>
                          <?= $row_index + 1; ?>
                        </td>
                        <td>
                          <?= $transaction['type']; ?>
                        </td>
                        <td>₦
                          <?= $transaction['amount']; ?>
                        </td>
                        <td>
                          <?= $transaction['user']; ?>
                        </td>
                        <td>
                          <?=
                            $date = date("j F, Y", strtotime($transaction['date']));
                          $date; ?>
                        </td>
                        <td>
                          <?= $transaction['trx_ref']; ?>
                        </td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" class="details"
                                href="?show_details=<?= $transaction['trx_ref'] ?>">
                                <i class="bx bx-book-alt me-1"></i> Details
                              </a>
                              <a class="dropdown-item" id="send">
                                <i class="bx bx-send me-1"></i> Send Receipt
                              </a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php $row_index++; ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>



                <!-- Pagination links -->
                <ul class="pagination" style="justify-content: right; margin: 20px;">
                  <?php
                  // Generate the first pagination link
                  if ($start_link > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                    if ($start_link > 2) {
                      if ($start_link > 3) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                      }
                      echo '<li class="page-item"><a class="page-link" href="?page=' . ($start_link - 1) . '">' . ($start_link - 1) . '</a></li>';
                    }
                  }

                  // Generate the pagination links within the range
                  for ($page = $start_link; $page <= $end_link; $page++) {
                    $active_class = ($page === $current_page) ? 'active' : '';
                    echo '<li class="page-item ' . $active_class . '"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                  }

                  // Generate the last pagination link
                  if ($end_link < $total_pages) {
                    if ($end_link < $total_pages - 1) {
                      if ($end_link < $total_pages - 2) {
                        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                      }
                    }
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                  }
                  ?>
                </ul>


                <script>
                  document.addEventListener('DOMContentLoaded', function () {
                    const filterOptions = document.querySelectorAll('.filter-option');
                    const transactionRows = document.querySelectorAll('.table tbody tr');

                    // Get the query parameter from the URL
                    const queryParams = new URLSearchParams(window.location.search);
                    const defaultFilter = queryParams.get('filter') || 'All';

                    filterOptions.forEach(option => {
                      const selectedFilter = option.getAttribute('data-filter');
                      option.addEventListener('click', () => {
                        transactionRows.forEach(row => {
                          const typeColumn = row.querySelector('td:nth-child(2)');
                          const statusColumn = row.querySelector('td:nth-child(4)');
                          if (selectedFilter === 'All' || typeColumn.textContent.trim() === selectedFilter) {
                            row.style.display = 'table-row';
                          } else {
                            row.style.display = 'none';
                          }
                        });
                      });

                      // Apply the default filter
                      if (selectedFilter === defaultFilter) {
                        option.click();
                      }
                    });
                  });
                </script>

              </div>
            </div>
            <!--/ Deposits End -->
          </div>
          <!-- / Content -->

          <script>
            // Function to handle "Details" click event
            document.getElementsById("details").addEventListener("click", function () {
              console.log("ouytdf");
              $("#loadingScreen").show();
            });

          </script>




          <script>

            // $(document).ready(function () {

            //   $('#details').click(function () {
            //     console.log("hi there");
            //     // Find the closest row (parent <tr>)
            //     var row = $(this).closest('tr');

            //     // Extract the "trx_ref" and "type" values from the row
            //     var trxRef = row.find('td:nth-child(5)').text();
            //     var type = row.find('td:nth-child(1)').text(); // Assuming the "type" is in the second column

            //     // Send both "trx_ref" and "type" to details.php via AJAX
            //     $.ajax({
            //       url: 'trx_details.php',
            //       method: 'POST',
            //       data: { trx_ref: trxRef, type: type },
            //       beforeSend: function () {
            //         $('#modelUSSD').modal('hide');
            //         // document.getElementById("loadingScreen").style.display = "block";
            //         $("#loadingScreen").show();
            //       },
            //       success: function (response) {
            //         $("#loadingScreen").hide();
            //         // Airtime
            //         if (response.includes("Airtime")) {
            //           console.log(response);


            //           var airtime_type = response[type];
            //           var airtime_id = response[id];
            //           var airtime_network = response[network];
            //           var airtime_amount = response[amount];
            //           var airtime_number = response[number];
            //           var airtime_date = response[date];
            //           var airtime_time = response[time];
            //           var airtime_status = response[status];




            //           document.getElementById("airtime_type").innerText = airtime_type;
            //           document.getElementById("airtime_id").innerText = airtime_id;
            //           document.getElementById("airtime_network").innerText = airtime_network;
            //           document.getElementById("airtime_amount").innerText = airtime_amount;
            //           document.getElementById("airtime_number").innerText = airtime_number;
            //           document.getElementById("airtime_date").innerText = airtime_date;
            //           document.getElementById("airtime_time").innerText = airtime_time;
            //           document.getElementById("airtime_status").innerText = airtime_status;

            //           $('#Airtime_details').modal('show'); //use this to show a success model
            //         } else {

            //         }

            //       },
            //       error: function (xhr, status, error) {
            //         $("#loadingScreen").hide();
            //         document.getElementById("status_report").innerText = "Error: Please try again in few minutes [err_code: #8704]";
            //         $('#modelError').modal('show');
            //       }
            //     });


            //   });
            // });



                // Actions

                // document.getElementById("details").addEventListener("click", function () {
                // // const ref = document.getElementById("trx_ref").value;
                // // document.getElementById("airtime_id").innerText = ref;


                // try {
                //   $.ajax({
                //     type: 'POST',
                //     url: 'trx_details.php',
                //     // data: $('#trx_details').serialize(),
                //     data: "Airtime",

                //     dataType: 'json',
                //     beforeSend: function () {
                //       $('#modelUSSD').modal('hide');
                //       // document.getElementById("loadingScreen").style.display = "block";
                //       $("#loadingScreen").show();
                //     },
                //     success: function (response) {
                //       $("#loadingScreen").hide();
                //       // Airtime
                //       if (response.includes("Airtime")) {
                //         console.log(response);


                //         var airtime_type = response[type];
                //         var airtime_id = response[id];
                //         var airtime_network = response[network];
                //         var airtime_amount = response[amount];
                //         var airtime_number = response[number];
                //         var airtime_date = response[date];
                //         var airtime_time = response[time];
                //         var airtime_status = response[status];




                //         document.getElementById("airtime_type").innerText = airtime_type;
                //         document.getElementById("airtime_id").innerText = airtime_id;
                //         document.getElementById("airtime_network").innerText = airtime_network;
                //         document.getElementById("airtime_amount").innerText =  airtime_amount;
                //         document.getElementById("airtime_number").innerText = airtime_number;
                //         document.getElementById("airtime_date").innerText = airtime_date;
                //         document.getElementById("airtime_time").innerText = airtime_time;
                //         document.getElementById("airtime_status").innerText = airtime_status;

                //         $('#Airtime_details').modal('show'); //use this to show a success model
                //       } else {

                //       }

                //     },
                //     error: function (xhr, status, error) {
                //       $("#loadingScreen").hide();
                //       document.getElementById("status_report").innerText = "Error: Please try again in few minutes [err_code: #8704]";
                //       $('#modelError').modal('show');
                //     }
                //   });
                // } catch (error) {
                //   $("#loadingScreen").hide();
                //   document.getElementById("status_report").innerText = "Error: Please try again in few minutes [err_code: #8701]";
                //   $('#modelError').modal('show');
                // }

                // });




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