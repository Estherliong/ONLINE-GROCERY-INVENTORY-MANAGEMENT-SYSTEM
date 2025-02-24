<?php
session_start();
include('php/header.php');
include("connection.php");

if (!isset($_SESSION['otp'])) {
    echo '<script>window.location.href="login.php"</script>';
    exit();
}

if (isset($_POST['verifyOtpBtn'])) {
    if (isset($_POST['otp'])) {
        $entered_otp = $_POST['otp'];
        if ($entered_otp == $_SESSION['otp']) {
            // OTP is correct, log the user in
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    swal({
                        title: "Success",
                        text: "Login Successfully",
                        icon: "success"
                    }).then(() => {
                        window.location.href = "index.php";
                    });
                });
            </script>';
            unset($_SESSION['otp']);
        } else {
            echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    swal({
                        title: "Failed",
                        text: "Invalid OTP",
                        icon: "error"
                    });
                });
            </script>';
        }
    } else {
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                swal({
                    title: "Failed",
                    text: "OTP is required",
                    icon: "error"
                });
            });
        </script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>OTP Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="image/logo.png">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <main>
        <div class="container">
            <h1 class="form-title">OTP Verification</h1>
            <form method="POST" action=''>
                <div class="main-user-info">
                    <div class="user-input-box">
                        <label for="otp">Enter OTP</label>
                        <input type="text"
                               id="otp"
                               name="otp"
                               placeholder="Enter OTP"
                               required/>
                    </div>
                </div>
                <div class="form-submit-btn">
                    <input type="submit" name="verifyOtpBtn" value="Verify OTP">
                </div>
            </form>   
        </div>
    </main>
</body>
</html>