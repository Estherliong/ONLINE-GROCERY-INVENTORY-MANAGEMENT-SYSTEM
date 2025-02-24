<?php
include('connection.php');
session_start();
$error_message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    if($_SESSION['identity'] == 'supplier')
    {
        $query = "SELECT * FROM supplier WHERE token='$token'";
    }
    else
    {
        $query = "SELECT * FROM admin WHERE token='$token'";
    }
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        if (isset($_POST['reset_password'])) {
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if ($new_password === $confirm_password) {
                if (strlen($new_password) >= 8) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    if($_SESSION['identity'] == 'supplier')
                    {
                        $update_query = "UPDATE supplier SET pass='$hashed_password', token=NULL WHERE token='$token'";
                    }
                    else
                    {
                        $update_query = "UPDATE admin SET pass='$hashed_password', token=NULL WHERE token='$token'";
                    }

                    if (mysqli_query($connect, $update_query)) {
                        echo '<script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    Swal.fire({
                                        title: "Success",
                                        text: "Your password has been updated!",
                                        icon: "success"
                                    }).then(() => {
                                        window.location.href = "login.php";
                                    });
                                });
                              </script>';
                    } else {
                        $error_message = 'Failed to update password!';
                    }
                } else {
                    $error_message = 'Password must be at least 8 characters long!';
                }
            } else {
                $error_message = 'Passwords do not match!';
            }
        }
    } else {
        $error_message = 'Invalid token!';
    }
} else {
    $error_message = 'No token provided!';
}

// Display error message using SweetAlert if it exists
if ($error_message) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Error",
                    text: "' . $error_message . '",
                    icon: "error"
                });
            });
          </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
                var newPassword = $('input[name="new_password"]').val();
                var confirmPassword = $('input[name="confirm_password"]').val();

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Error",
                        text: "Passwords do not match!",
                        icon: "error"
                    });
                } else if (newPassword.length < 8) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Error",
                        text: "Password must be at least 8 characters long!",
                        icon: "error"
                    });
                }
            });
        });
    </script>
</head>
<body>
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="homepage.php" class="h1"><b>Power Inventory</b></a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Enter your new password</p>
            <form action="" method="post">
                <div class="input-group mb-3">
                    <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" name="reset_password" class="btn btn-primary btn-block">Reset Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
