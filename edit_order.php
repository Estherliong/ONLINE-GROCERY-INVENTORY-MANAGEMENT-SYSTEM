<?php
session_start();
include('connection.php');
include('cryptography.php'); // Include the cryptography functions

if (isset($_POST['editbtn'])) {
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

    // Fetch order items
    $order_items_query = "SELECT oi.*, i.item_name, inv.current_stock, inv.reserved_stock 
                          FROM order_item oi 
                          JOIN item i ON oi.item_id = i.item_id 
                          JOIN inventory inv ON oi.item_id = inv.item_id 
                          WHERE oi.order_id = '$order_id'";
    $order_items_result = mysqli_query($connect, $order_items_query);
}

if (isset($_POST['updateOrderBtn'])) {
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $order_status = $_POST['order_status'];
    $payment_status = $_POST['payment_status'];
    $order_amount = str_replace('RM ', '', $_POST['order_amount']);
    $date = $_POST['date'];
    $shipping_address = encrypt_data($_POST['shipping_address']);

    // Update order details
    $update_order_query = "UPDATE sales_order SET customer_id = '$customer_id', order_status = '$order_status', payment_status = '$payment_status', sales_order_amount = '$order_amount', date = '$date', shipping_address = '$shipping_address' WHERE sales_order_id = '$order_id'";
    mysqli_query($connect, $update_order_query);

    // Update order items
    foreach ($_POST['item_id'] as $index => $item_id) {
        $quantity = $_POST['quantity'][$index];
        $item_price = str_replace('RM ', '', $_POST['item_price'][$index]);
        $total_price = str_replace('RM ', '', $_POST['total_price'][$index]);

        $update_order_item_query = "UPDATE order_item SET quantity = '$quantity', item_price = '$item_price', total_price = '$total_price' WHERE order_id = '$order_id' AND item_id = '$item_id' LIMIT 1";
        mysqli_query($connect, $update_order_item_query);

        // Update inventory based on payment status
        if ($payment_status == 1) {
            // Confirmed order, deduct from current stock
            $update_stock_query = "UPDATE inventory SET current_stock = current_stock - '$quantity', reserved_stock = reserved_stock - '$quantity', available_stock = available_stock - '$quantity' WHERE item_id = '$item_id'";
            mysqli_query($connect, $update_stock_query);
        }
    }

    header('Location: order.php');
    exit();
}
include('header.php');
include('navigation.php');
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <!-- ...existing code... -->
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper mt-3"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row match-height">
                <div class="col-12">
                    <div class="container-fluid">
                        <h2 class="mb-4">Edit Order</h2>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?= $order['sales_order_id']; ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Order Information</h5>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" name="date" value="<?= $order['date']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="customer_id">Customer Name</label>
                                        <select class="form-control" name="customer_id" required>
                                            <option value="">Select Customer</option>
                                            <?php
                                            $customers_query = "SELECT * FROM customer";
                                            $customers_result = mysqli_query($connect, $customers_query);
                                            while($customer = mysqli_fetch_assoc($customers_result)){
                                                $fname = decrypt_data($customer['fname']);
                                                $lname = decrypt_data($customer['lname']);
                                                $selected = ($customer['customer_id'] == $order['customer_id']) ? 'selected' : '';
                                                echo '<option value="'.$customer['customer_id'].'" '.$selected.'>'.$fname.' '.$lname.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="order_status">Order Status</label>
                                        <select class="form-control" name="order_status" required>
                                            <option value="0" <?= ($order['order_status'] == 0) ? 'selected' : ''; ?>>Unchecked</option>
                                            <option value="1" <?= ($order['order_status'] == 1) ? 'selected' : ''; ?>>Invoiced</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select class="form-control" name="payment_status" required>
                                            <option value="0" <?= ($order['payment_status'] == 0) ? 'selected' : ''; ?>>Unpaid</option>
                                            <option value="1" <?= ($order['payment_status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="order_amount">Order Amount</label>
                                        <input type="text" class="form-control" name="order_amount" value="RM <?= number_format($order['sales_order_amount'], 2); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="shipping_address">Shipping Address</label>
                                        <input type="text" class="form-control" name="shipping_address" value="<?= $order['shipping_address']; ?>" required>
                                    </div>
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
                                                <th>Quantity on Hand</th>
                                                <th>Reserved Stock</th>
                                                <th>Available Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($item = mysqli_fetch_assoc($order_items_result)) {
                                                $available_stock = $item['current_stock'] - $item['reserved_stock'];
                                                ?>
                                                <tr>
                                                    <td><?= $item['item_name']; ?></td>
                                                    <td><input type="number" class="form-control quantity" name="quantity[]" value="<?= $item['quantity']; ?>" required></td>
                                                    <td><input type="text" class="form-control item_price" name="item_price[]" value="RM <?= number_format($item['item_price'], 2); ?>" readonly></td>
                                                    <td><input type="text" class="form-control total_price" name="total_price[]" value="RM <?= number_format($item['total_price'], 2); ?>" readonly></td>
                                                    <td><input type="number" class="form-control current_stock" name="current_stock[]" value="<?= $item['current_stock']; ?>" readonly></td>
                                                    <td><input type="number" class="form-control reserved_stock" name="reserved_stock[]" value="<?= $item['reserved_stock']; ?>" readonly></td>
                                                    <td><input type="number" class="form-control available_stock" name="available_stock[]" value="<?= $available_stock; ?>" readonly></td>
                                                    <input type="hidden" name="item_id[]" value="<?= $item['item_id']; ?>">
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4" name="updateOrderBtn">Save</button>
                            <a href="order.php" class="btn btn-secondary mt-4">Back to Orders</a>
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
    <script>
    $(document).ready(function () {
        function updateTotalPrice(productRow) {
            var quantity = parseFloat(productRow.find('.quantity').val()) || 0;
            var item_price = parseFloat(productRow.find('.item_price').val().replace(/[^\d.-]/g, '')) || 0;
            var total_price = quantity * item_price;

            productRow.find('.total_price').val('RM ' + total_price.toFixed(2));
            updateOrderAmount();
        }

        function updateOrderAmount() {
            var totalAmount = 0;
            $('.total_price').each(function () {
                var price = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                totalAmount += price;
            });
            $('input[name="order_amount"]').val('RM ' + totalAmount.toFixed(2));
        }

        function updateReservedStock(productRow) {
            var quantity = parseFloat(productRow.find('.quantity').val()) || 0;
            productRow.find('.reserved_stock').val(quantity);
        }

        function updateAvailableStock(productRow) {
            var current_stock = parseFloat(productRow.find('.current_stock').val()) || 0;
            var reserved_stock = parseFloat(productRow.find('.reserved_stock').val()) || 0;
            var available_stock = current_stock - reserved_stock;
            productRow.find('.available_stock').val(available_stock);
        }

        // Update the price, reserved stock, and available stock when quantity changes
        $('.quantity').on('input', function () {
            var productRow = $(this).closest('tr');
            updateTotalPrice(productRow);
            updateReservedStock(productRow);
            updateAvailableStock(productRow);
        });

        // Trigger the total price, reserved stock, and available stock update before form submission
        $('form').on('submit', function (event) {
            // Ensure that all total prices, reserved stocks, and available stocks are updated before submitting
            $('.quantity').each(function () {
                var productRow = $(this).closest('tr');
                updateTotalPrice(productRow);
                updateReservedStock(productRow);
                updateAvailableStock(productRow);
            });
        });
    });
    </script>
</body>
</html>