<?php
session_start();
include('connection.php');
include('cryptography.php'); // Include the cryptography functions
include('header.php');
include('navigation.php');

if (isset($_POST['viewbtn'])) {
    $order_id = $_POST['order_id'];

    // Fetch order details
    $order_query = "SELECT * FROM sales_order WHERE sales_order_id = '$order_id'";
    $order_result = mysqli_query($connect, $order_query);
    $order = mysqli_fetch_assoc($order_result);
    $order['shipping_address'] = decrypt_data($order['shipping_address']);

    // Fetch customer details
    $customer_id = $order['customer_id'];
    $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
    $customer_result = mysqli_query($connect, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);
    $customer['fname'] = decrypt_data($customer['fname']);
    $customer['lname'] = decrypt_data($customer['lname']);
    $customer['cname'] = decrypt_data($customer['cname']);
    $customer['phone'] = decrypt_data($customer['phone']);
    $customer['address'] = decrypt_data($customer['address']);

    // Fetch order items
    $order_items_query = "SELECT oi.*, i.item_name FROM order_item oi JOIN item i ON oi.item_id = i.item_id WHERE oi.order_id = '$order_id'";
    $order_items_result = mysqli_query($connect, $order_items_query);
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Order</title>
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
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper mt-3"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row match-height">
                <div class="col-12">
                    <div class="container-fluid">
                        <h2 class="mb-4">Order Details</h2>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Information</h5>
                                <p><strong>Date:</strong> <?= $order['date']; ?></p>
                                <p><strong>Customer Name:</strong> <?= $customer['fname'] . " " . $customer['lname']; ?></p>
                                <p><strong>Company Name:</strong> <?= $customer['cname']; ?></p>
                                <p><strong>Phone:</strong> <?= $customer['phone']; ?></p>
                                <p><strong>Address:</strong> <?= $customer['address']; ?></p>
                                <p><strong>Shipping Address:</strong> <?= $order['shipping_address']; ?></p>
                                <p><strong>Order Status:</strong> <?= ($order['order_status'] == 0) ? "Unchecked" : "Invoiced"; ?></p>
                                <p><strong>Payment Status:</strong> <?= ($order['payment_status'] == 0) ? "Unpaid" : "Paid"; ?></p>
                                <p><strong>Order Amount:</strong> RM <?= number_format($order['sales_order_amount'], 2); ?></p>
                            </div>
                        </div>
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Order Items</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Item Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($item = mysqli_fetch_assoc($order_items_result)) {
                                            ?>
                                            <tr>
                                                <td><?= $item['item_name']; ?></td>
                                                <td><?= $item['quantity']; ?></td>
                                                <td>RM <?= number_format($item['item_price'], 2); ?></td>
                                                <td>RM <?= number_format($item['total_price'], 2); ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="order.php" class="btn btn-secondary mt-4">Back to Orders</a>
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