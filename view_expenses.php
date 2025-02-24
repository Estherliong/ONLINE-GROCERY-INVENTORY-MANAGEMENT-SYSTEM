<?php
session_start();
include('connection.php');
include('cryptography.php'); // Include the cryptography functions
include('header.php');
include('navigation.php');

if (isset($_POST['viewbtn'])) {
    $expense_id = $_POST['expense_id'];

    // Fetch expense details
    $expense_query = "SELECT * FROM expenses WHERE expenses_id = '$expense_id'";
    $expense_result = mysqli_query($connect, $expense_query);
    $expense = mysqli_fetch_assoc($expense_result);

    // Fetch supplier details
    $supplier_id = $expense['supplier_id'];
    $supplier_query = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
    $supplier_result = mysqli_query($connect, $supplier_query);
    $supplier = mysqli_fetch_assoc($supplier_result);
    $supplier['fname'] = decrypt_data($supplier['fname']);
    $supplier['lname'] = decrypt_data($supplier['lname']);
    $supplier['address'] = decrypt_data($supplier['address']);
    $supplier['phone'] = decrypt_data($supplier['phone']);
    $supplier['email'] = decrypt_data($supplier['email']);

    // Fetch expense items
    $expense_items_query = "SELECT ei.*, i.item_name FROM expenses_item ei JOIN item i ON ei.item_id = i.item_id WHERE ei.expenses_id = '$expense_id'";
    $expense_items_result = mysqli_query($connect, $expense_items_query);
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Expense</title>
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
        .card {
            background-color: #fff; /* Set the card background color to white */
            color: #000; /* Set the card text color to black */
        }
        .table {
            color: #000; /* Set the table text color to black */
        }
    </style>
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
                        <h2 class="mb-4">Expense Details</h2>
                        <div class="card">
                            <div class="card-body expense-details">
                                <h5 class="card-title">Expense Information</h5>
                                <p><strong>Date:</strong> <?= $expense['date']; ?></p>
                                <p><strong>Supplier Name:</strong> <?= $supplier['fname'] . " " . $supplier['lname']; ?></p>
                                <p><strong>Address:</strong> <?= $supplier['address']; ?></p>
                                <p><strong>Phone:</strong> <?= $supplier['phone']; ?></p>
                                <p><strong>Email:</strong> <?= $supplier['email']; ?></p>
                                <p><strong>Payment Status:</strong> <?= ($expense['payment_status'] == 0) ? "Unpaid" : "Paid"; ?></p>
                                <p><strong>Expense Amount:</strong> RM <?= number_format($expense['expenses_amount'], 2); ?></p>
                                <p><strong>Reason:</strong> <?= $expense['reason']; ?></p>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-body expense-details">
                                <h5 class="card-title">Expense Items</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Item Cost</th>
                                            <th>Total Cost</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($item = mysqli_fetch_assoc($expense_items_result)) {
                                            ?>
                                            <tr>
                                                <td><?= $item['item_name']; ?></td>
                                                <td><?= $item['quantity']; ?></td>
                                                <td>RM <?= number_format($item['item_cost'], 2); ?></td>
                                                <td>RM <?= number_format($item['total_cost'], 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="expenses.php" class="btn btn-secondary mt-4">Back to Expenses</a>
                    </div>
                </div>
            </div>
        </div> <!------- close div for app-content------>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

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