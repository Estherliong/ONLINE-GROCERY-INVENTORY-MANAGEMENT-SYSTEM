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
    $update_supplier_query = "UPDATE user SET email = '$email', phone = '$phone', address = '$address', cname = '$company_name' WHERE role = 1 AND user_id = '$supplier_id'";
    mysqli_query($connect, $update_supplier_query);

    header('Location: view_supplier.php');
    exit();
}
ob_end_flush(); // Flush the output buffer
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Suppliers</title>
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
                        <h2 class="mb-4">Suppliers</h2>
                        <table class="table table-striped fs-5" width="100%" id="suppliersTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Email</th>
                                    <th scope="col" class="text-center">Phone No</th>
                                    <th scope="col" class="text-center">Address</th>
                                    <th scope="col" class="text-center">Company Name</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $suppliers_query = "SELECT * FROM supplier WHERE verify_status=1";
                                $suppliers_result = mysqli_query($connect, $suppliers_query);
                                if(mysqli_num_rows($suppliers_result) > 0) {
                                    $i = 1;
                                    while($supplier = mysqli_fetch_assoc($suppliers_result)) {
                                        $decrypted_email = decrypt_data($supplier['email']);
                                        $decrypted_phone = decrypt_data($supplier['phone']);
                                        $decrypted_address = decrypt_data($supplier['address']);
                                        $decrypted_company_name = decrypt_data($supplier['cname']);
                                        ?>
                                        <tr>
                                            <th scope="row" class="text-center"><?= $i++; ?></th>
                                            <td class="text-center"><?= $decrypted_email; ?></td>
                                            <td class="text-center"><?= $decrypted_phone; ?></td>
                                            <td class="text-center"><?= $decrypted_address; ?></td>
                                            <td class="text-center"><?= $decrypted_company_name; ?></td>
                                            <td class="text-center">
                                                <form method="POST" action="edit_supplier.php">
                                                    <input type="hidden" name="supplier_id" value="<?= $supplier['supplier_id']; ?>">
                                                    <input type="hidden" name="email" value="<?= $decrypted_email; ?>">
                                                    <input type="hidden" name="phone" value="<?= $decrypted_phone; ?>">
                                                    <input type="hidden" name="address" value="<?= $decrypted_address; ?>">
                                                    <input type="hidden" name="company_name" value="<?= $decrypted_company_name; ?>">
                                                    <button type="submit" class="btn btn-info text-white">Edit</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No suppliers found</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
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