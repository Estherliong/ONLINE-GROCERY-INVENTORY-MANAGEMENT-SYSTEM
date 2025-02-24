<?php
session_start();
include('connection.php');


if (isset($_POST['createInvoiceBtn'])) {
    $order_id = $_POST['order_id'];
    $invoice_date = date('Y-m-d');

    // Fetch order details
    $order_query = "SELECT * FROM sales_order WHERE sales_order_id = '$order_id'";
    $order_result = mysqli_query($connect, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Fetch customer details
    $customer_id = $order['customer_id'];
    $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
    $customer_result = mysqli_query($connect, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);

    // Insert invoice
    $insert_invoice_query = "INSERT INTO invoice (customer_id, order_id, created_at) VALUES ('$customer_id','$order_id',  '$invoice_date')";
    mysqli_query($connect, $insert_invoice_query);

    $upt_status = "UPDATE sales_order SET order_status = 1";
    mysqli_query($connect,$upt_status);
    header('Location: invoice.php');
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
    <title>Add Invoice</title>
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
                        <h2 class="mb-4">Add Invoice</h2>
                        <form method="POST">
                            <div class="form-group">
                                <label for="order_id">Select Order</label>
                                <select class="form-control" name="order_id" id="order_id" required>
                                    <option value="">Select Order</option>
                                    <?php
                                    $orders_query = "SELECT * FROM sales_order WHERE payment_status = 1 AND order_status = 0";
                                    $orders_result = mysqli_query($connect, $orders_query);
                                    while($order = mysqli_fetch_assoc($orders_result)){
                                        echo '<option value="'.$order['sales_order_id'].'">Order ID: '.$order['sales_order_id'].' - Date: '.$order['date'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div id="orderDetails" style="display: none;">
                                <h5 class="mt-4">Customer Information</h5>
                                <p><strong>Name:</strong> <span id="customerName"></span></p>
                                <h5 class="mt-4">Order Items</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Item Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItems">
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right"><strong>Grand Total</strong></td>
                                        <td id="grandTotal"></td>
                                    </tr>
                                    </tfoot>
                                </table>
                                
                            </div>
                            <button type="submit" class="btn btn-primary mt-4" name="createInvoiceBtn">Create Invoice</button>
                            <a href="invoice.php" class="btn btn-secondary mt-4">Back to Invoices</a>
                        </form>
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

    <script>
$(document).ready(function() {
    $('#order_id').on('change', function() {
        var orderId = $(this).val();
        if (orderId) {
            $.ajax({
                url: 'fetch_order_details.php',
                type: 'POST',
                data: { order_id: orderId },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#customerName').text(data.customer_name);
                    $('#orderItems').html(data.order_items);
                    $('#grandTotal').text(data.grand_total);
                    $('#orderDetails').show();
                    swal("Success", "Order details fetched successfully!", "success");
                },
                error: function() {
                    swal("Error", "Failed to fetch order details.", "error");
                }
            });
        } else {
            $('#orderDetails').hide();
        }
    });
});
</script>
</body>
</html>

<?php

if (isset($_POST['fetch_order_details'])) {
    $order_id = $_POST['order_id'];

    // Fetch order details
    $order_query = "SELECT * FROM sales_order WHERE sales_order_id = '$order_id'";
    $order_result = mysqli_query($connect, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Fetch customer details
    $customer_id = $order['customer_id'];
    $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
    $customer_result = mysqli_query($connect, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);

    // Fetch order items
    $order_items_query = "SELECT oi.*, i.item_name FROM order_item oi JOIN item i ON oi.item_id = i.item_id WHERE oi.order_id = '$order_id'";
    $order_items_result = mysqli_query($connect, $order_items_query);

    $order_items_html = '';
    while ($item = mysqli_fetch_assoc($order_items_result)) {
        $order_items_html .= '<tr>
                                <td>'.$item['item_name'].'</td>
                                <td>'.$item['quantity'].'</td>
                                <td>RM '.number_format($item['item_price'], 2).'</td>
                                <td>RM '.number_format($item['total_price'], 2).'</td>
                              </tr>';
    }

    $response = array(
        'customer_name' => $customer['fname'] . ' ' . $customer['lname'],
        'order_items' => $order_items_html
    );

    echo json_encode($response);
    exit();
}

?>