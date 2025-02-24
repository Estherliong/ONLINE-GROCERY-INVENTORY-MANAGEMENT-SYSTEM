<?php
session_start();
include('connection.php');
include('cryptography.php'); // Include the cryptography functions

if (isset($_POST['updateStatusBtn'])) {
    $package_id = $_POST['package_id'];
    $status = $_POST['status'];

    // Update package status
    $update_status_query = "UPDATE sales_order SET package_status = '$status' WHERE sales_order_id = '$package_id'";
    mysqli_query($connect, $update_status_query);

    header('Location: package.php');
    exit();
}
include('header.php');
include('navigation.php');
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Packages</title>
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
                        <h2 class="mb-4">Package Status</h2>
                        <table class="table table-striped fs-5" width="100%" id="packagesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Amount</th>
                                    <th scope="col" class="text-center">Customer Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $packages_query = "SELECT * FROM sales_order WHERE payment_status = 1";
                                $packages_result = mysqli_query($connect, $packages_query);
                                if(mysqli_num_rows($packages_result) > 0) {
                                    $i = 1;
                                    while($package = mysqli_fetch_assoc($packages_result)) {
                                        $customer_id = $package['customer_id'];
                                        $cname = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
                                        $cname_run = mysqli_query($connect,$cname);
                                        $customer = mysqli_fetch_assoc($cname_run);
                        
                                        $customer_fname = decrypt_data($customer['fname']);
                                        $customer_lname = decrypt_data($customer['lname']);
                                        $customer_name = $customer_fname . " " . $customer_lname;
                                        $status = '';
                                        switch ($package['package_status']) {
                                            case 0:
                                                $status = 'Packed';
                                                break;
                                            case 1:
                                                $status = 'Shipped';
                                                break;
                                            case 2:
                                                $status = 'Delivered';
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <th scope="row" class="text-center"><?= $i++; ?></th>
                                            <td class="text-center"><?= $package['date']; ?></td>
                                            <td class="text-center">RM <?= number_format($package['sales_order_amount'],2) ?></td>
                                            <td class="text-center"><?= $customer_name; ?></td>
                                            <td class="text-center"><?= $status; ?></td>
                                            <td class="text-center">
                                                <form action="view_package.php" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="order_id" value="<?= $package['sales_order_id']; ?>">
                                                    <button class="btn btn-primary" name="viewbtn" type="submit">View</button>
                                                </form>
                                                <button class="btn btn-primary editStatusBtn" data-package-id="<?= $package['sales_order_id']; ?>" data-status="<?= $package['package_status']; ?>">Edit Status</button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No packages found</td>
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

    <!-- Edit Status Modal -->
    <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStatusModalLabel">Edit Package Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="package_id" id="package_id">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="0">Packed</option>
                                <option value="1">Shipped</option>
                                <option value="2">Delivered</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="updateStatusBtn">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.editStatusBtn').on('click', function() {
                var packageId = $(this).data('package-id');
                var status = $(this).data('status');

                $('#package_id').val(packageId);
                $('#status').val(status);

                $('#editStatusModal').modal('show');
            });
        });
    </script>
</body>
</html>