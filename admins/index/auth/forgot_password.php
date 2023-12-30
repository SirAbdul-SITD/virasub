<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Forgot Password | ViraSub</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../favlogo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../../assets/js/config.js"></script>
    <!-- Include the Toastify library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        #otp-section {
            display: none;
            transition: all 0.3s ease-in-out;
        }

        .popup {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            z-index: 9999;
            display: flex;
            align-items: center;
            background-color: rgba(0, 10, 5, 0.8);
            transition: all 0.3s ease-in-out;
            color: #fff;
        }

        .popup.success {
            background-color: #4CAF50;
            color: #fff;
        }

        .popup.error {
            background-color: #F44336;
            color: white;
        }

        .popup i {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Forgot Password -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.php" class="app-brand-link gap-2">
                                <span> <img src="../favlogo.png" style="width: 20px; margin-right:0px;"> </span>
                                <span style="margin-left:0px;"
                                    class="app-brand-text demo menu-text fw-bolder ms-2">ira<span
                                        style="color: #fB9149">w</span style="color: #14A39A">ave</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">Forgot Password? 🔒</h4>
                        <p class="mb-4">Enter your email and we'll send you a code to verify it's you</p>

                        <div id="password-section" style="display: none;">
    <!-- Password Entry -->
    <form id="password-form" class="mb-3">
        <div class="mb-3">
            <label for="new-password" class="form-label">Enter New Password</label>
            <input type="password" class="form-control" id="new-password" name="new-password"
                placeholder="Enter your new password" />
        </div>
        <div class="mb-3">
            <label for="confirm-password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm-password"
                name="confirm-password" placeholder="Confirm your new password" />
        </div>
        <button class="btn btn-primary d-grid w-100" id="update-password-button"
            type="button">Update Password</button>
    </form>
</div>
                        <div id="otp-section">
                            <!-- OTP Entry -->
                            <form id="otp-form" class="mb-3">
                                <div class="mb-3">
                                    <label for="otp" class="form-label">Enter OTP</label>
                                    <input type="number" class="form-control" id="otp" name="otp" placeholder="Enter OTP"
                                        autofocus />
                                </div>
                                <button class="btn btn-primary d-grid w-100" type="submit">Verify OTP</button>
                            </form>
                        </div>

                        <div id="email-section">
                            <!-- Email Entry -->
                            <form id="email-form" class="mb-3">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Enter your email" autofocus />
                                </div>
                                <button class="btn btn-primary d-grid w-100" id="send-otp-button" type="button">Send
                                    OTP</button>
                            </form>
                        </div>

                        <div class="text-center">
                            <a href="index.php" class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                                Back to password login
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /Forgot Password -->
            </div>
        </div>
    </div>

    <!-- JavaScript to handle OTP sending and verification using AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initially, show the email section and hide the OTP section
            toggleSections(true);

            togglePassword(false);

            // Send OTP Button Click
            $("#send-otp-button").click(function () {
                var email = $("#email").val();

                // Send an AJAX request to send_otp.php to send OTP
                $.ajax({
                    type: "POST",
                    url: "send_otp.php",
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

            // OTP Form Submission
            $("#otp-form").submit(function (e) {
                e.preventDefault();

                // Get the entered OTP
                var otp = $("#otp").val();

                // Send an AJAX request to verify_otp.php to verify OTP
                $.ajax({
                    type: "POST",
                    url: "verify_otp.php",
                    data: { otp: otp },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            // OTP verified successfully, you can redirect the user to the dashboard
                            displayPopup("OTP verified! Create new password now", response.success);
                            togglePassword(true);
                        } else {
                            // Display an error toast message
                            displayPopup(response.message, false);
                        }
                    },
                    error: function () {
                        // Display an error toast message
                        displayPopup("An error occurred while verifying OTP.", false);
                    }
                });
            });

            // Function to handle password update
            $("#update-password-button").click(function () {
                var newPassword = $("#new-password").val();
                var confirmPassword = $("#confirm-password").val();

                if (newPassword === confirmPassword) {
                    // Send an AJAX request to update the user's password
                    $.ajax({
                        type: "POST",
                        url: "update_password.php",
                        data: { password: newPassword },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                // Password updated successfully, display success toast
                                displayPopup(response.message, response.success);
                                setTimeout(function () {
                                    window.location.href = '../index.php';
                                }, 1000);
                            } else {
                                // Display an error toast message
                                displayPopup(response.message, false);
                            }
                        },
                        error: function () {
                            // Display an error toast message
                            displayPopup("An error occurred while updating the password.", false);
                        }
                    });
                } else {
                    // Passwords do not match, display an error message
                    displayPopup("Passwords do not match.", false);
                }
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

        // Function to toggle sections
        function toggleSections(showOTPSection) {
            if (showOTPSection) {
                $("#email-section").show();
                $("#otp-section").hide();
            } else {
                $("#email-section").hide();
                $("#otp-section").show();
            }
        }

        function togglePassword(showOTPSection) {
            if (showOTPSection) {
                $("#otp-section").hide();
                $("#password-section").show();
            }
        }

    </script>



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>