<?php
session_start();
ob_start(); // Start output buffering
include('connection.php');
include('header.php');
include('navigation.php'); 
// Disable the Customer 
if(isset($_POST['disablebtn'])){
  $customer_id = $_POST['customerid'];
  $disable_sql = mysqli_query($connect,"UPDATE `customer` SET `status`= 0 WHERE `customer_id` = '$customer_id'");
  if($disable_sql)
  {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Customer Disabled.","", "success");
            });
          </script>';  
  }
}

// Restore customer
if(isset($_POST['restorebtn'])){
  $restore_id = $_POST['customerid'];
  $restore_sql = mysqli_query($connect,"UPDATE `customer` SET `status`=1 WHERE `customer_id` = '$restore_id'");
  if($restore_sql)
  {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Customer Restored Successfully.","", "success");
            });
          </script>';  
  }  
}

// Add new customer
if(isset($_POST['addCustomerBtn'])){
  $fname = encrypt_data($_POST['fname']);
  $lname = encrypt_data($_POST['lname']);
  $email = encrypt_data($_POST['email']);
  $address = encrypt_data($_POST['address']);
  $phone = encrypt_data($_POST['phone']);
  $company_name = encrypt_data($_POST['company_name']);
  $status = 1; // Default status to active

  // Validate email
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Invalid email format.","", "error");
            });
          </script>';
  } else {
    $add_sql = mysqli_query($connect,"INSERT INTO `customer` (`fname`, `lname`, `cname` ,`email`, `address`, `phone`, `status`) VALUES ('$fname', '$lname', '$company_name', '$email', '$address', '$phone', '$status')");
    if($add_sql)
    {
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Customer Added Successfully.","", "success");
              });
            </script>';  
    }
  }
  ob_end_flush(); // Flush the output buffer
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Customers</title>
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
                    <h2>Customer List</h2>
                        <button class="btn btn-primary mb-4" name="modaladdbtn" type="button" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
                        <table class="table table-striped fs-5" width="100%" id="myTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">First Name</th>
                                    <th scope="col" class="text-center">Last Name</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Address</th>
                                    <th scope="col" class="text-center">Phone</th>
                                    <th scope="col" class="text-center">Company Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $viewcust = "SELECT * FROM `customer`";
                                    $sql_run = mysqli_query($connect, $viewcust);
                                    $count = 1;
                                    if(mysqli_num_rows($sql_run) > 0)
                                    {
                                        foreach($sql_run as $cust)
                                        {
                                            ?>
                                            <tr>
                                                <th scope="row" class="text-center"><?= $count++ ?></th>
                                                <td class="text-center"><?= decrypt_data($cust['fname']) ?></td>
                                                <td class="text-center"><?= decrypt_data($cust['lname']) ?></td>
                                                <td class="text-center"><?= decrypt_data($cust['email']) ?></td>
                                                <td class="text-center"><?= decrypt_data($cust['address']) ?></td>
                                                <td class="text-center"><?= decrypt_data($cust['phone']) ?></td>
                                                <td class="text-center"><?= decrypt_data($cust['cname']) ?></td>
                                                <td class="text-center">
                                                <?php
                                                    if($cust['status'] == 1)
                                                    {
                                                        echo "Active";
                                                    }
                                                    else
                                                    {
                                                        echo "Inactive";
                                                    }
                                                ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                        if($cust['status'] == 1)
                                                        {
                                                    ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="customerid" value="<?= $cust['customer_id'] ?>">
                                                        <button type="submit" name="disablebtn" class="btn btn-warning text-white" onclick="return confirm('Disable <?= decrypt_data($cust['fname']) . " " . decrypt_data($cust['lname']) ?>?')">Disable</button>
                                                    </form>
                                                    <?php
                                                        }
                                                        else
                                                        {
                                                    ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="customerid" value="<?= $cust['customer_id'] ?>">
                                                        <button type="submit" name="restorebtn" class="btn btn-success" onclick="return confirm('Restore <?= decrypt_data($cust['fname']) . " " . decrypt_data($cust['lname']) ?>?')">Restore</button>
                                                    </form>
                                                    <?php
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <tr>
                                        <td colspan="9" class="text-center">No customers found</td>
                                        </tr>
                                        <?php
                                        
                                    }
                                ?>
                            </tbody>
                        </table>
                        
                        <!-- Modal Add Customer -->
                        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addCustomerLabel">Add New Customer</h5>
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
                                                    <label class="small mb-1" for="inputCompanyName">Company Name</label>
                                                    <input class="form-control" name="company_name" id="inputCompanyName" type="text" placeholder="Enter Company Name" required>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" name="addCustomerBtn" type="submit">Add Customer</button>
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