<?php
session_start();
include('php/header.php');
include "connection.php";
include("cryptography.php");
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['identity']) && !isset($_SESSION['identity'])) {
    echo '<script>window.location.href="identity.php"</script>';
    exit();
}
if (isset($_POST['identity'])) {
    $_SESSION['identity'] = $_POST['identity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Login Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" href="image/logo.png">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <main>
        <div class="container">
            <h1 class="form-title">Login</h1>
            <form method="POST" action=''>
                <div class="main-user-info">
                    <div class="user-input-box">
                        <label for="userEmail">Email</label>
                        <input type="email"
                               id="userEmail"
                               name="userEmail"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                               placeholder="Enter Email"
                               required/>
                    </div>
                    <div class="user-input-box">
                        <label for="password">Password</label>
                        <input type="password"
                               id="inputpassword"
                               name="upassword"
                               placeholder="Enter Password"
                               required/>
                    </div>
                    <div class="" style="color:white; font-size:14px "><input type="checkbox" onclick="myFunction()" style="margin-right:3px">Show Password</div>
                </div>
                <div class="form-submit-btn">
                    <input type="submit" name="submit" value="Login">
                </div>
                <br>
                <br>
                <p><a href="forgot-password.php">Forgot password</a></p>
                <?php
                if (isset($_SESSION['identity']) && $_SESSION['identity'] == 'supplier') {
                    echo '<p>Don\'t have an account? <a href="registration.php">Register here</a></p>';
                }
                ?>
            </form>   
        </div>

        <?php
            if (isset($_POST["submit"])) {
                $email = $_POST["userEmail"];
                $pass = $_POST["upassword"];
                $identity = $_SESSION['identity'];

                if ($identity == 'supplier') {
                    $sql = "SELECT * FROM `supplier`";
                } else {
                    $sql = "SELECT * FROM `admin`";
                }

                $result = mysqli_query($connect, $sql);
                $found = false;

                while ($row = mysqli_fetch_assoc($result)) {
                    $decrypted_email = decrypt_data($row['email']);
                    if ($decrypted_email === $email) {
                        if (password_verify($pass, $row['pass'])) {
                            
                            if ($identity == 'supplier' && $row['verify_status'] == '0') {
                                echo '<script type="text/javascript">swal("Failed", "Please Verify Your Email", "warning");</script>';
                            } else {
                                $otp = rand(100000, 999999);
                                $_SESSION['otp'] = $otp;
                                $_SESSION['email'] = $email;
                                $_SESSION['identity'] = $identity;
                                $_SESSION['id'] = ($identity == 'supplier') ? $row['supplier_id'] : $row['admin_id'];

                                $mail = new PHPMailer(true);
                                try {
                                    //Server settings
                                    $mail->isSMTP();
                                    $mail->Host       = 'smtp.gmail.com'; 
                                    $mail->SMTPAuth   = true;
                                    $mail->Username   = 'estherliong61@gmail.com'; // SMTP username
                                    $mail->Password   = 'lcmilpbrevixjcpb'; // SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                                    $mail->Port       = 587;

                                    //Recipients
                                    $mail->setFrom('estherliong61@gmail.com', 'Power Inventory');
                                    $mail->addAddress($email);

                                    // Content
                                    $mail->isHTML(true);
                                    $mail->Subject = 'OTP code For Login In To Power Inventory';
                                    $mail->Body    = "Your OTP is: $otp";

                                    $mail->send();
                                    echo '<script type="text/javascript">
                                    swal("Success", "OTP Sent Successfully", "success").then(() => {
                                        window.location.href = "otp_verification.php";
                                    });
                                </script>';
                                    exit();
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                }
                            }
                            $found = true;
                            break;
                        }
                    }
                }

                if (!$found) {
                    echo '<script type="text/javascript">swal("Failed", "Please try again", "error");</script>';
                }
            }
        ?>
    </main>
</body>
</html>