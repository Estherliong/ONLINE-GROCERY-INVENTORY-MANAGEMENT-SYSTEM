<?php
include('connection.php');
require_once('cryptography.php');
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Item-</title>
    <link rel="icon" href="../image/logo.png">
    <!--ICON-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/app-lite.css">
    <!-- END CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/colors/palette-gradient.css">
    <!-- <link rel="stylesheet" type="text/css" href="theme-assets/css/pages/dashboard-ecommerce.css"> -->
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <!-- END Custom CSS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../ckeditor/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .password_required {
            display: none;
        }

        .password_required ul {
            padding: 0;
            margin: 0 0 15px;
            list-style: none;
        }

        .password_required ul li {
            margin-bottom: 8px;
            color: red;
            font-weight: 700;
        }

        .password_required ul li.active {
            color: #29ff5e;
        }

        .password_required ul li span::before {
            content: "✖ ";
        }

        .password_required ul li.active span::before {
            content: "✔ ";
        }

        .form-submit-btn input {
            display: block;
            width: 100%;
            margin-top: 10px;
            font-size: 20px;
            padding: 10px;
            border: none;
            border-radius: 3px;
            color: rgb(209, 209, 209);
            background: rgba(63, 114, 176, 0.7);
            cursor: pointer;
            pointer-events: none;
        }

        .form-submit-btn input.active {
            pointer-events: auto;
        }
    </style>
</head>
<?php
if (isset($_POST['profile_edit_btn'])) {
    $id = $_SESSION['id'];
    $identity = $_SESSION['identity'];
    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    if ($identity == 'supplier') {
        $cname = $_POST['cname'];
        $update = "UPDATE supplier SET fname='$fname', lname='$lname', phone='" . encrypt_data($phone) . "', address='" . encrypt_data($address) . "', cname='" . encrypt_data($cname) . "' WHERE supplier_id='$id'";
    } else {
        $update = "UPDATE admin SET fname='$fname', lname='$lname', phone='" . encrypt_data($phone) . "', address='" . encrypt_data($address) . "' WHERE admin_id='$id'";
    }
    $update_run = mysqli_query($connect, $update);
    if ($update_run) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Success!", "Profile Updated Successfully!", "success");
            });
        </script>';
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Error!", "Profile Update Failed!", "error");
            });
        </script>';
    }
    
}
if (isset($_POST['changePasswordBtn'])) {
    $id = $_SESSION['id'];
    $identity = $_SESSION['identity'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/";

    if ($new_password === $confirm_password) {
        if (preg_match($password_pattern, $new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            if ($identity == 'supplier') {
                $update_password = "UPDATE supplier SET passw='$hashed_password' WHERE supplier_id='$id'";
            } else {
                $update_password = "UPDATE admin SET pass='$hashed_password' WHERE admin_id='$id'";
            }
            $update_password_run = mysqli_query($connect, $update_password);
            if ($update_password_run) {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire("Success!", "Password Changed Successfully!", "success");
                    });
                </script>';
            } else {
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire("Error!", "Password Change Failed!", "error");
                    });
                </script>';
            }
        } else {
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Error!", "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one special character.", "error");
                });
            </script>';
        }
    } else {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Error!", "Passwords do not match!", "error");
            });
        </script>';
    }
}
?>
<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns" style="color: #f8f8f8">
    <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow fixed-top navbar-semi-light" style="color:#6f4e37">
        <div class="navbar-wrapper" style="background-color:#6f4e37">
            <div class="navbar-container content" style="color:#6f4e37">
                <div class="collapse navbar-collapse show" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-block d-md-none"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="avatar avatar-online"><img src="/FYP/image/supplier.jpg" alt="avatar"><i></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php 
                                    $identity = $_SESSION['identity'];
                                    $id = $_SESSION['id'];
                                    if($identity == 'supplier')
                                    {
                                        $getname = "SELECT * from supplier where supplier_id='$id'";
                                    }
                                    else
                                    {
                                        $getname = "SELECT * from admin where admin_id='$id'";
                                    }
                                    
                                    $getname_run = mysqli_query($connect,$getname);
                                    
                                    if(mysqli_num_rows($getname_run)>0) {
                                        $g = mysqli_fetch_assoc($getname_run);
                                        ?>
                                        <div class="arrow_box_right">
                                            <div class="dropdown-item">
                                                <span class="avatar avatar-online">Welcome, <span class="user-name text-bold-700 ml-1">
                                                <?php echo $g['fname']. " " .$g['lname']; ?>
                                                </span></span>
                                            </div>
                                        <?php
                                    } 
                                ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal" href="#"><i class="ft-user"></i> Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#passwordModal" href="#"><i class="ft-lock"></i> Change Password</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" onclick="return confirm('Are you sure want to log out?');" href="logout.php"><i class="ft-power"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <?php
        if($identity == 'supplier')
        {
            $profile_sql = mysqli_query($connect,"SELECT * from supplier where supplier_id = '$id'");
        }
        else
        {
            $profile_sql = mysqli_query($connect,"SELECT * from admin where admin_id = '$id'");
        }
       
        if(mysqli_num_rows($profile_sql)>0) {
            $pf = mysqli_fetch_assoc($profile_sql);
            $pf['phone'] = $pf['phone'] ? decrypt_data($pf['phone']) : '';
            $pf['address'] = $pf['address'] ? decrypt_data($pf['address']) : '';
            if($identity == 'supplier')
            {
                $pf['cname'] = $pf['cname'] ? decrypt_data($pf['cname']) : '';
            }
            else
            {
                $pf['cname'] = '';
            }
            
    ?>
    <!-- profile modal -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileLabel">Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <form method='POST' enctype="multipart/form-data">
                        <!-- Form Row-->
                        <div class="row gx-3 mb-3">
                            <?php
                                if($identity == 'supplier')
                                {
                                    ?>
                                <div class="col-md-4">
                                <label class="small mb-1" for="supplierid">Supplier ID</label>
                                <input class="form-control" name="supplerid" id="supplierid" type="text" placeholder="Supplier ID" value=" <?php echo $id ?>" readonly>
                                </div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                <div class="col-md-4">
                               <label class="small mb-1" for="adminid">Admin ID</label>
                                <input class="form-control" name="adminid" id="adminid" type="text" placeholder="Admin ID" value=" <?php echo $id ?>" readonly>
                                </div>
                                    <?php
                                }
                            ?>
                            
                            <div class="col-md-4">
                                <label class="small mb-1" for="firstname">First Name</label>
                                <input class="form-control" name="firstname" id="firstname" type="text" placeholder="First Name" value="<?php echo $pf['fname'] ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1" for="lastname">Last Name</label>
                                <input class="form-control" name="lastname" id="lastname" type="text" placeholder="Last Name" value="<?php echo $pf['lname'] ?>" required>
                            </div>
                        </div>
                        <!-- Form Row        -->
                        <div class="row mb-3">
                            <!-- Form Group (location)-->
                            <div class="col-md-12">
                                <label class="small mb-1" for="email">Email</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="<?php echo decrypt_data($pf['email']) ?>" readonly>
                            </div> 
                        </div>
                        <div class="row mb-3">
                            <?php
                            if($identity == 'supplier')
                            {
                                ?>
                                <div class="col-md-6">
                                <label class="small mb-1" for="cname">Company Name</label>
                                <input class="form-control" name="cname" id="cname" type="text" placeholder="Company Name" value="<?php echo $pf['cname'] ?>" required>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="col-md-6">
                                <label class="small mb-1" for="phone">Phone</label>
                                <input class="form-control" name="phone" id="phone" type="text" placeholder="Phone Number" value="<?php echo $pf['phone'] ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="address">Address</label>
                                <textarea row="3" name="address" class="form-control" placeholder="Company Address" required><?php echo $pf['address']?></textarea>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- Save changes button-->
                    <button class="btn btn-primary" name="profile_edit_btn" type="submit">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <form method='POST' enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <!-- Form Group (location) -->
                            <label class="small mb-1" for="password">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                        </div>
                        <div class="mb-3">
                            <!-- Form Group (location) -->
                            <label class="small mb-1" for="confirm">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>                        
                        </div>
                        <div class="password_required">
                            <ul>
                                <li class='length'><span></span>At Least 8 Characters</li>
                                <li class='lowercase'><span></span>One Lowercase Letter</li>
                                <li class='uppercase'><span></span>One Uppercase Letter</li>
                                <li class='number'><span></span>One Number</li>
                                <li class='special'><span></span>One Special Character</li>
                            </ul>
                        </div>
                </div>
                <div class="modal-footer">
                    <!-- Save changes button-->
                    <button class="btn btn-primary" name="changePasswordBtn" type="submit">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <script>
        $(document).ready(function() {
            $('#new_password').on('input', function() {
                var password = $(this).val();
                var length = password.length >= 8;
                var lowercase = /[a-z]/.test(password);
                var uppercase = /[A-Z]/.test(password);
                var number = /[0-9]/.test(password);
                var special = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

                $('.password_required').show();
                $('.password_required .length').toggleClass('active', length);
                $('.password_required .lowercase').toggleClass('active', lowercase);
                $('.password_required .uppercase').toggleClass('active', uppercase);
                $('.password_required .number').toggleClass('active', number);
                $('.password_required .special').toggleClass('active', special);

                var allValid = length && lowercase && uppercase && number && special;
                $('.form-submit-btn input').toggleClass('active', allValid);
            });
        });
    </script>
</body>
</html>