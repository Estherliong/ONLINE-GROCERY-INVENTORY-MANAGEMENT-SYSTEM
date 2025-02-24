<?php
session_start();
include('connection.php');
include('cryptography.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($email, $token)
{
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'estherliong61@gmail.com';
    $mail->Password = 'lcmilpbrevixjcpb';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('estherliong61@gmail.com');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request from Power Inventory';

    $email_template = "
        <h2>Password Reset Request</h2>
        <p>Click the link below to reset your password:</p>
        <a href='http://localhost/FYP/reset-password.php?token=$token'>Reset Password</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            background-color: #f2f6fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .login-box {
            width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .login-box .card-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-box .card-header a {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
        }
        .login-box .card-body {
            padding: 20px;
        }
        .login-box .card-body .login-box-msg {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            color: #666;
        }
        .login-box .input-group {
            margin-bottom: 15px;
        }
        .login-box .input-group .form-control {
            border-right: 0;
        }
        .login-box .input-group .input-group-text {
            background-color: #fff;
            border-left: 0;
        }
        .login-box .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }
        .login-box .mt-3 a {
            color: #007bff;
            text-decoration: none;
        }
        .login-box .mt-3 a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="homepage.php"><b>Power Inventory</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Enter your email to reset password.</p>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="submitbtn" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="login.php">Login</a>
                </p>
            </div>
        </div>
    </div>

    <?php
    if(isset($_POST['submitbtn'])){
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(50)); 
        $encrypted_email = encrypt_data($email);
        if($_SESSION['identity'] == 'supplier')
        {
            $checkemail = "SELECT * from supplier WHERE `email` = '$encrypted_email'";
        }
        else
        {
            $checkemail = "SELECT * from admin WHERE `email` = '$encrypted_email'";
        }
        

        $result = mysqli_query($connect, "$checkemail");
        if(mysqli_num_rows($result) > 0)
        {
            if($_SESSION['identity'] == 'supplier')
            {
                mysqli_query($connect, "UPDATE `supplier` SET `token` = '$token' WHERE `email` = '$encrypted_email'");            }
            else
            {
                mysqli_query($connect, "UPDATE `admin` SET `token` = '$token' WHERE `email` = '$encrypted_email'");
            }
            
            sendemail_verify($email, $token);
            echo '<script type="text/javascript">swal("Success","Check your email for the password reset link!","success");</script>';
        }
        else
        {
            echo '<script type="text/javascript">swal("Error","Email does not exist","error");</script>';
        }
    }
    ?>
</body>
</html>