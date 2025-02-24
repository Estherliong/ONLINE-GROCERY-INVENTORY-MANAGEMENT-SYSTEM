<?php
session_start();
include "connection.php";
include('php/header.php');
include("cryptography.php");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($email, $verify_token)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    //$mail->SMTPDebug = 2;
    $mail->isSMTP();   
      
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;  
    $mail->Username = 'estherliong61@gmail.com';
    $mail->Password = 'lcmilpbrevixjcpb';

    $mail->SMTPSecure = 'ssl';   
    $mail->Port = 465;

    $mail->setFrom('estherliong61@gmail.com','Power Inventory');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Email Verification from Power Inventory';

    $email_template = "
        <h2>You have Registered In Power Inventory</h2>
        <h4>Click the link to Verify your email address to login with the below given link</h4>
        <br /><br />
        <a href='http://localhost/FYP/verify_email.php?token=$verify_token'> Click Me to Verify Email</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
    //echo 'Message has been sent';
}

?> 

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Registration</title>
        <meta name="viewpoint" content="width=device-width, initial-scale=1.0"/>
        <link rel= "stylesheet" href= "registration.css">
        <link rel="icon" href="image/logo.png">

        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
        <script src="script.js"></script>
    </head>
    <body > 
        <main>
        <div class="container">
        <h1 class="form-title">Supplier Registration</h1>
        <form method="POST">
            <div class="main-user-info">
                <div class="user-input-box">
                    <label for="fname">First Name</label>
                    <input type="text" id=fname name="fname" placeholder="Your First Name" required>
                
                <div class="user-input-box">
                    <label for="lname">Last Name</label>
                    <input type="text" id=lname name="lname" placeholder="Your Last Name" required>
                </div>
                <div class="user-input-box">
                    <label for="cname">Company Name</label>
                    <input type="text" id=cname name="cname" placeholder="Your Company Name" required>
                </div>
                <div class="user-input-box">
                    <label for="email">Email</label>
                    <input type="email"
                             id="email"
                             name="uemail"
                             pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                             placeholder="Enter Email" required/>
                </div>
                <div class="user-input-box">
                    <label for="phone">Phone</label>
                    <input type="text"
                             id="phone"
                             name="phone"
                             pattern="\d{10,15}"
                             placeholder="Enter Phone Number" required/>
                </div>
                <div class="user-input-box">
                    <label for="address">Address</label>
                    <input type="text"
                             id="address"
                             name="address"
                             placeholder="Enter Address" required/>
                </div>
                <div class="user-input-box" id="show_hide_password" onclick="showhidepassword()">
                    <label for="password">Password</label>

                    <input type="password"
                             id="password"
                             name="upassword"
                             placeholder="Enter Password" required onfocus="passwordvalidation()">
                             
                   <a href=""><i class="fa fa-eye-slash" aria-hidden="true" ></i></a>
                            

                </div>
                <div class="user-input-box" id="show_hide_password" required onclick="showhidepassword()">
                    <label for="password">Confirm Password</label>
                    <input type="password"
                             id="cpassword"
                             name="cpassword"
                             placeholder="Enter Password" required/>
                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true" ></i></a>
                </div>

                <div class="password_required">
                    <ul>
                        <li class = 'length'><span></span>At Least 8 Character</li>
                        <li class = 'lowercase'><span></span>One Lower Letter</li>
                        <li class = 'uppercase'><span></span>One Capiter Letter</li>
                        <li class = 'number'><span></span>One Number</li>
                        <li class = 'special'><span></span>One Special Character</li>
                    </ul>
                </div>

            </div>
            
            <div class="form-submit-btn">
                <input class="input_submit" type="submit" name="submit" value="Register">
            </div>

            
            <p><a href="login.php">Already have an account? Login!</a></p>

            
            <!-- <div class="form-submit-btn">
                <input type="Login" name="submit" value="Login">
            </div> -->
        </form>   
        </div>

        <?php
        if(isset($_POST['submit']))
        {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $cname = $_POST['cname'];
            $password = $_POST["upassword"];
            $email = $_POST["uemail"];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $confirmp = $_POST['cpassword'];
            $encrypted_email = encrypt_data($email);

            $select = mysqli_query($connect, "SELECT * from `supplier` where `email` = '$encrypted_email'");

            $verify_token = md5(rand());
            
            if(mysqli_num_rows($select)>0){
                echo '<script type="text/javascript">swal("Email already Exists", "Please change another email!", "error");</script>';
            }
            else if($confirmp != $password )
            {
                echo '<script type="text/javascript">swal("Wrong", "Confirm Password Must Same with password", "error");</script>';
            }
            else if(!preg_match("/^[a-zA-Z ]*$/", $fname) || !preg_match("/^[a-zA-Z ]*$/", $lname))
            {
                echo '<script type="text/javascript">swal("Invalid Name", "First Name and Last Name should only contain letters and spaces", "error");</script>';
            }
            else if(!preg_match("/^\d{10,15}$/", $phone))
            {
                echo '<script type="text/javascript">swal("Invalid Phone Number", "Phone number should contain only numbers and be 10 to 15 digits long", "error");</script>';
            }
            else{
                // Encrypt data
                $encrypted_email = encrypt_data($email);
                $encrypted_phone = encrypt_data($phone);
                $encrypted_address = encrypt_data($address);
                $encrypted_cname = encrypt_data($cname);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into database
                mysqli_query($connect,"INSERT INTO `supplier`( `fname`,`lname`,`cname`,`email`,`pass`,`phone`,`address`,`token`,`verify_status`) values('$fname','$lname','$cname','$encrypted_email','$hashed_password','$encrypted_phone','$encrypted_address','$verify_token',0)");
          
                ?>
                <script>
                    swal({
                        title: "Success!",
                        text: "Please Check Your Email For Verify Purpose",
                        type: "success",
                        timer: 3000,
                        showConfirmButton: false
                        }, function(){
                            window.location.href = "login.php";
                        });
                </script>
                <?php
                sendemail_verify($email, $verify_token);
            }
        }
    ?>
    <script src="script.js"></script>
    </main>
    </body>
</html>