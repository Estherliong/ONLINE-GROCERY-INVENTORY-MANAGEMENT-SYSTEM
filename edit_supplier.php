<?php
session_start();
ob_start(); // Start output buffering
include('connection.php');
include('header.php');
include('navigation.php');

if (isset($_POST['updateSupplierBtn'])) {
    $supplier_id = $_POST['supplier_id'];
    $email = encrypt_data($_POST['email']);
    $phone = encrypt_data($_POST['phone']);
    $address = encrypt_data($_POST['address']);
    $company_name = encrypt_data($_POST['company_name']);

    // Update supplier details
    $update_supplier_query = "UPDATE supplier SET email = '$email', phone = '$phone', address = '$address', cname = '$company_name' WHERE supplier_id = '$supplier_id'";
    mysqli_query($connect, $update_supplier_query);

    header('Location: view_supplier.php');
    exit();
}
ob_end_flush(); // Flush the output buffer

// Fetch supplier details
if (isset($_POST['supplier_id'])) {
    $supplier_id = $_POST['supplier_id'];
    $supplier_query = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id' AND verify_status = 1";
    $supplier_result = mysqli_query($connect, $supplier_query);
    if (mysqli_num_rows($supplier_result) > 0) {
        $supplier = mysqli_fetch_assoc($supplier_result);
        $decrypted_email = decrypt_data($supplier['email']);
        $decrypted_phone = decrypt_data($supplier['phone']);
        $decrypted_address = decrypt_data($supplier['address']);
        $decrypted_company_name = decrypt_data($supplier['cname']);
    } else {
        header('Location: view_supplier.php');
        exit();
    }
} else {
    header('Location: view_supplier.php');
    exit();
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Edit Supplier</title>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper mt-3"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row match-height">
                <div class="col-12">
                    <div class="container-fluid">
                        <h2 class="mb-4">Edit Supplier</h2>
                        <form method="POST">
                            <input type="hidden" name="supplier_id" value="<?= $supplier_id; ?>">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="<?= $decrypted_email; ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone No</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?= $decrypted_phone; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" name="address" id="address" value="<?= $decrypted_address; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" class="form-control" name="company_name" id="company_name" value="<?= $decrypted_company_name; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="updateSupplierBtn">Save changes</button>
                            <a href="view_supplier.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!------- close div for app-content------>
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
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
</body>
</html>