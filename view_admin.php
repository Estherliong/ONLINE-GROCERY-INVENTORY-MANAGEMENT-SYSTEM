<?php
session_start();
ob_start(); // Start output buffering
include('connection.php');
include('header.php');

// Add new admin
if(isset($_POST['addAdminBtn'])){
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = encrypt_data($_POST['email']);
  $address = encrypt_data($_POST['address']);
  $phone = encrypt_data($_POST['phone']);
  $password = $_POST['password'];
  $status = 1; // Default status to active

  // Validate email
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Invalid email format.", "", "error");
            });
          </script>';
  } 
  else if(mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admin` WHERE `email`='$email'")) > 0 || mysqli_num_rows(mysqli_query($connect,"SELECT * FROM `supplier` WHERE `email`='$email'")) > 0) {
    echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Email already exists.", "", "error");
            });
          </script>';
  }
  
  else {
    // Validate password
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/";
    if (preg_match($password_pattern, $password)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $add_sql = mysqli_query($connect,"INSERT INTO `admin` (`fname`, `lname`, `email`, `address`, `phone`, `pass`) VALUES ('$fname', '$lname', '$email', '$address', '$phone', '$hashed_password')");
      if($add_sql)
      {
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Admin Added Successfully.", "", "success");
                });
              </script>';  
      }
    } else {
      echo '<script type="text/javascript">
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one special character.", "", "error");
              });
            </script>';
    }
  }
  ob_end_flush(); // Flush the output buffer
}

// Edit admin information
if(isset($_POST['editAdminBtn'])){
  $admin_id = $_POST['adminid'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = encrypt_data($_POST['email']);
  $address = encrypt_data($_POST['address']);
  $phone = encrypt_data($_POST['phone']);
  $password = $_POST['password'];

  // Validate email
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Invalid email format.", "", "error");
            });
          </script>';
  } 
  else if(mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `admin` WHERE `email`='$email'")) > 0 || mysqli_num_rows(mysqli_query($connect,"SELECT * FROM `supplier` WHERE `email`='$email'")) > 0) {
    echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Email already exists.", "", "error");
            });
          </script>';
  }
  else {
    // Validate password
    $password_pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]).{8,}$/";
    if (preg_match($password_pattern, $password)) {
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $edit_sql = mysqli_query($connect,"UPDATE `admin` SET `fname`='$fname', `lname`='$lname', `email`='$email', `address`='$address', `phone`='$phone', `password`='$hashed_password' WHERE `admin_id`='$admin_id'");
      if($edit_sql)
      {
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Admin Updated Successfully.", "", "success");
                });
              </script>';  
      }
    } else {
      echo '<script type="text/javascript">
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one special character.", "", "error");
              });
            </script>';
    }
  }
  
  ob_end_flush(); // Flush the output buffer
}
?>

<?php include('navigation.php'); 
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Admins</title>
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
    <style>
        body {
            color: #000; /* Set the text color to black */
        }
        .modal-content {
            background-color: #fff; /* Set the modal background color to white */
            color: #000; /* Set the modal text color to black */
        }
        .table {
            color: #000; /* Set the table text color to black */
        }
        .form-control {
            background-color: #fff; /* Set the form control background color to white */
            color: #000; /* Set the form control text color to black */
        }
        .form-control::placeholder {
            color: #6c757d; /* Set the form control placeholder text color */
        }
    </style>
    <!-- END Custom CSS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper mt-3"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row match-height">
                <div class="col-12">
                    <div class="container-fluid">
                        <h2>View Admins</h2>
                        <button class="btn btn-primary mb-4" name="modaladdbtn" type="button" data-bs-toggle="modal" data-bs-target="#addAdminModal">Add Admin</button>
                        <table class="table table-striped fs-5" width="100%" id="myTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">First Name</th>
                                    <th scope="col" class="text-center">Last Name</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Address</th>
                                    <th scope="col" class="text-center">Phone</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $viewadmin = "SELECT * FROM `admin`";
                                    $sql_run = mysqli_query($connect, $viewadmin);
                                    $count = 1;
                                    if(mysqli_num_rows($sql_run) > 0)
                                    {
                                        foreach($sql_run as $admin)
                                        {
                                            ?>
                                            <tr>
                                                <th scope="row" class="text-center"><?= $count++ ?></th>
                                                <td class="text-center"><?= $admin['fname'] ?></td>
                                                <td class="text-center"><?= $admin['lname'] ?></td>
                                                <td class="text-center"><?= decrypt_data($admin['email']) ?></td>
                                                <td class="text-center"><?= decrypt_data($admin['address']) ?></td>
                                                <td class="text-center"><?= decrypt_data($admin['phone']) ?></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary" name="modaleditbtn" type="button" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $admin['admin_id'] ?>">Edit</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Admin -->
                                            <div class="modal fade" id="editAdminModal<?= $admin['admin_id'] ?>" tabindex="-1" aria-labelledby="editAdminLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editAdminLabel">Edit Admin</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="adminid" value="<?= $admin['admin_id'] ?>">
                                                                <!-- Form Row-->
                                                                <div class="row gx-3 mb-3">
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1" style="color:#000000" for="inputFirstName">First Name</label>
                                                                        <input class="form-control" name="fname" id="inputFirstName" type="text" value="<?= $admin['fname'] ?>" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="small mb-1" style="color:#000000" for="inputLastName">Last Name</label>
                                                                        <input class="form-control" name="lname" id="inputLastName" type="text" value="<?= $admin['lname'] ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row gx-3 mb-3">
                                                                    <div class="col-md-12">
                                                                        <label class="small mb-1" style="color:#000000" for="inputEmail">Email</label>
                                                                        <input class="form-control" name="email" id="inputEmail" type="email" value="<?= decrypt_data($admin['email']) ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row gx-3 mb-3">
                                                                    <div class="col-md-12">
                                                                        <label class="small mb-1" style="color:#000000" for="inputAddress">Address</label>
                                                                        <input class="form-control" name="address" id="inputAddress" type="text" value="<?= decrypt_data($admin['address']) ?>" required>
                                                                    </div>
                                                                </div>
                                                                <div class="row gx-3 mb-3">
                                                                    <div class="col-md-12">
                                                                        <label class="small mb-1" style="color:#000000" for="inputPhone">Phone</label>
                                                                        <input class="form-control" name="phone" id="inputPhone" type="text" value="<?= decrypt_data($admin['phone']) ?>" required>
                                                                    </div>
                                                                </div>
                                                                
                                                        </div>
                                                        <div class="modal-footer">
                                                            <!-- Save changes button-->
                                                            <button class="btn btn-primary" name="editAdminBtn" type="submit">Save Changes</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                        <td colspan="7" class="text-center">No admins found</td>
                                        </tr>
                                        <?php
                                        
                                    }
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- Modal Add Admin -->
                        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addAdminLabel">Add New Admin</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <form method="POST" enctype="multipart/form-data">
                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="small mb-1" for="inputFirstName">First Name</label>
                                                    <input class="form-control" name="fname" id="inputFirstName" type="text" placeholder="Enter First Name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small mb-1" for="inputLastName">Last Name</label>
                                                    <input class="form-control" name="lname" id="inputLastName" type="text" placeholder="Enter Last Name" required>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-1" for="inputEmail">Email</label>
                                                    <input class="form-control" name="email" id="inputEmail" type="email" placeholder="Enter Email" required>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-1" for="inputAddress">Address</label>
                                                    <input class="form-control" name="address" id="inputAddress" type="text" placeholder="Enter Address" required>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-1" for="inputPhone">Phone</label>
                                                    <input class="form-control" name="phone" id="inputPhone" type="text" placeholder="Enter Phone" required>
                                                </div>
                                            </div>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-12">
                                                    <label class="small mb-1" for="inputPassword">Password</label>
                                                    <input class="form-control" name="password" id="inputPassword" type="password" placeholder="Enter Password" required>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" name="addAdminBtn" type="submit">Add Admin</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>
        </div> <!------- close div for app-content------>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="theme-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN JS-->
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <!-- END JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="theme-assets/js/scripts/pages/dashboard-lite.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    <script src="search.js"></script>
</body>
</html>