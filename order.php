<?php
session_start();
include('connection.php');
include('cryptography.php');
if (isset($_POST['generateReportBtn'])) {
    generateReport($connect);
}
function generateReport($connect) {
    $filename = "orders_report_" . date('Ymd') . ".csv";
    $output = fopen('php://output', 'w');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    fputcsv($output, array('No', 'Date', 'Customer Name', 'Order Status', 'Payment', 'Amount'));

    $orders_query = "SELECT * FROM sales_order";
    $orders_result = mysqli_query($connect, $orders_query);
    if (mysqli_num_rows($orders_result) > 0) {
        $i = 1;
        while ($order = mysqli_fetch_assoc($orders_result)) {
            
            $customer_id = $order['customer_id'];
            $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
            $customer_result = mysqli_query($connect, $customer_query);
            $customer = mysqli_fetch_assoc($customer_result);
            $customer_fname = decrypt_data($customer['fname']);
            $customer_lname = decrypt_data($customer['lname']);
            $customer_name = $customer_fname . " " . $customer_lname;

            $order_status = ($order['order_status'] == 0) ? "Unchecked" : "Invoiced";
            $payment_status = ($order['payment_status'] == 0) ? "Unpaid" : "Paid";
            $amount = "RM " . number_format($order['sales_order_amount'], 2);

            fputcsv($output, array($i++, $order['date'], $customer_name, $order_status, $payment_status, $amount));
        }
    }

    fclose($output);
    exit();
}
include('header.php');
include('navigation.php'); 

if (isset($_POST['deletebtn'])) {
    $order_id = $_POST['order_id'];

    // Fetch order items to update stock
    $order_items_query = "SELECT * FROM order_item WHERE order_id = '$order_id'";
    $order_items_result = mysqli_query($connect, $order_items_query);

    while ($item = mysqli_fetch_assoc($order_items_result)) {
        $item_id = $item['item_id'];
        $quantity = $item['quantity'];

        // Update inventory
        $update_stock_query = "UPDATE inventory SET reserved_stock = reserved_stock - '$quantity', available_stock = available_stock + '$quantity' WHERE item_id = '$item_id'";
        mysqli_query($connect, $update_stock_query);
    }

    // Delete order items
    $delete_items_query = "DELETE FROM order_item WHERE order_id = '$order_id'";
    mysqli_query($connect, $delete_items_query);

    // Delete order
    $delete_order_query = "DELETE FROM sales_order WHERE sales_order_id = '$order_id'";
    if (mysqli_query($connect, $delete_order_query)) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Order deleted successfully!", "", "success");
                });
              </script>';
    } else {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Failed to delete order.", "", "error");
                });
              </script>';
    }
}




?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Orders</title>
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
                        <h2 class="mb-4">Sales Orders</h2>
                        <a href="add_order.php" class="btn btn-primary mb-4">Add Order</a>
                        <form method="POST" action="" style="display:inline-block;">
                            <button class="btn btn-secondary mb-4" name="generateReportBtn" type="submit">Generate Report</button>
                        </form>
                        <table class="table table-striped fs-5" width="100%" id="ordersTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Customer Name</th>
                                    <th scope="col" class="text-center">Order Status</th>
                                    <th scope="col" class="text-center">Payment</th>
                                    <th scope="col" class="text-center">Amount</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders_query = "SELECT * FROM sales_order";
                                $orders_result = mysqli_query($connect, $orders_query);
                                if(mysqli_num_rows($orders_result) > 0) {
                                    $i = 1;
                                    while($order = mysqli_fetch_assoc($orders_result)) {
                                        $customer_id = $order['customer_id'];
                                        $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
                                        $customer_result = mysqli_query($connect, $customer_query);
                                        $customer = mysqli_fetch_assoc($customer_result);
                                        $customer_fname = decrypt_data($customer['fname']);
                                        $customer_lname = decrypt_data($customer['lname']);
                                        $customer_name = $customer_fname . " " . $customer_lname;

                                        $order_status = ($order['order_status'] == 0) ? "Unchecked" : "Invoiced";
                                        $payment_status = ($order['payment_status'] == 0) ? "Unpaid" : "Paid";
                                        ?>
                                        <tr>
                                            <th scope="row" class="text-center"><?= $i++; ?></th>
                                            <td class="text-center"><?= $order['date']; ?></td>
                                            <td class="text-center"><?= $customer_name; ?></td>
                                            <td class="text-center"><?= $order_status; ?></td>
                                            <td class="text-center"><?= $payment_status; ?></td>
                                            <td class="text-center">RM <?= number_format($order['sales_order_amount'],2); ?></td>
                                            <td class="text-center">
                                            <form action="view_order.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="order_id" value="<?= $order['sales_order_id']; ?>">
                                                <button class="btn btn-primary" name="viewbtn" type="submit">View</button>
                                            </form>
                                            <form action="edit_order.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="order_id" value="<?= $order['sales_order_id']; ?>">
                                                <button class="btn btn-primary" name="editbtn" type="submit" <?= ($order['payment_status'] == 1) ? 'disabled' : ''; ?>>Edit</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="order_id" value="<?= $order['sales_order_id']; ?>">
                                                <button class="btn btn-danger" name="deletebtn" type="submit" <?= ($order['payment_status'] == 1) ? 'disabled' : ''; ?> onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No orders found</td>
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
    <script src="search.js"></script>
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable();
        });
    </script>
</body>
</html>