<?php
session_start();
include('connection.php');
include('cryptography.php'); // Include the cryptography functions

if (isset($_POST['addOrderBtn'])) {
    $sales_order_id = $_POST['sales_order_id'];
    $customer_id = $_POST['customer_id'];
    $order_status = $_POST['order_status'];
    $payment_status = $_POST['payment_status'];
    $order_amount = $_POST['order_amount'];
    $date = $_POST['date'];
    $shipping_address = encrypt_data($_POST['shipping_address']);


    // Fetch and encrypt_data customer name
    $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
    $customer_result = mysqli_query($connect, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);
    $customer_name = encrypt_data(decrypt_data($customer['fname']) . ' ' . decrypt_data($customer['lname']));

    $order_query = "INSERT INTO sales_order ( customer_id, customer_name, order_status, payment_status, sales_order_amount, date, shipping_address) 
                    VALUES ( '$customer_id', '$customer_name', '$order_status', '$payment_status', '$order_amount', '$date', '$shipping_address')";
    $order_query_run = mysqli_query($connect, $order_query);

    if ($order_query_run) {
        $order_id = mysqli_insert_id($connect);

        foreach ($_POST['item_id'] as $index => $item_id) {
            $quantity = $_POST['quantity'][$index];
            $item_price = $_POST['item_price'][$index];
            $total_price = $_POST['total_price'][$index];

            $item_query = "SELECT item_name FROM item WHERE item_id = '$item_id'";
            $item_result = mysqli_query($connect, $item_query);
            $item = mysqli_fetch_assoc($item_result);
            $item_name = $item['item_name'];
            $inv_query = "SELECT current_stock, reserved_stock FROM inventory WHERE item_id = '$item_id'";
            $inv_result = mysqli_query($connect, $inv_query);
            $inv = mysqli_fetch_assoc($inv_result);
            $current_stock = $inv['current_stock'];
            $reserved_stock = $inv['reserved_stock'];
            $available_stock = $current_stock - $reserved_stock;

            $order_item_query = "INSERT INTO order_item (order_id, item_id, item_name, quantity, item_price, total_price) 
                                 VALUES ('$order_id', '$item_id', '$item_name', '$quantity', '$item_price', '$total_price')";
            mysqli_query($connect, $order_item_query);

            // Update inventory based on payment status
            if ($payment_status == 1) {
                // Confirmed order, deduct from available stock
                $update_stock_query = "UPDATE inventory SET available_stock = available_stock - '$quantity' WHERE item_id = '$item_id'";
                mysqli_query($connect, $update_stock_query);
            } else {
                // Unpaid order, add to reserved quantity
                $update_reserved_query = "UPDATE inventory SET reserved_stock = reserved_stock + '$quantity', available_stock = available_stock - '$quantity' WHERE item_id = '$item_id'";
                mysqli_query($connect, $update_reserved_query);
            }
        }

        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Order Added Successfully.","", "success").then(function() {
                        window.location = "order.php";
                    });
                });
              </script>';
    } else {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Failed to Add Order.","", "error");
                });
              </script>';
    }
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
    <title>Add Order</title>
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
                    <h2 class="mb-4">Add Sales Orders</h2>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <?php
                                    $sales_order_query = "SELECT MAX(sales_order_id) AS max_id FROM sales_order";
                                    $sales_order_result = mysqli_query($connect, $sales_order_query);
                                    $sales_order = mysqli_fetch_assoc($sales_order_result);
                                    $order_id = $sales_order['max_id'] + 1;
                                    ?>
                                    <label class="small mb-1" for="sales_order_id">Sales Order ID</label>
                                    <input class="form-control" name="sales_order_id" id="sales_order_id" type="text" value="<?= $order_id ?>" placeholder="Enter Sales Order ID" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="customer_id">Customer Name</label>
                                    <select class="form-control" name="customer_id" id="customer_id" required>
                                        <option value="">Select Customer</option>
                                        <?php
                                        $customers_query = "SELECT * FROM customer";
                                        $customers_result = mysqli_query($connect, $customers_query);
                                        while($customer = mysqli_fetch_assoc($customers_result)){
                                            $fname = decrypt_data($customer['fname']);
                                            $lname = decrypt_data($customer['lname']);
                                            echo '<option value="'.$customer['customer_id'].'">'.$fname.' '.$lname.'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="order_status">Order Status</label>
                                    <select class="form-control" name="order_status" id="order_status" required>
                                        <option value="0">Unchecked</option>
                                        <option value="1">Invoiced</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="payment_status">Payment Status</label>
                                    <select class="form-control" name="payment_status" id="payment_status" required>
                                        <option value="0">Unpaid</option>
                                        <option value="1">Paid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="order_amount">Order Amount</label>
                                    <input class="form-control" name="order_amount" id="order_amount" type="number" placeholder="Enter Order Amount" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="date">Date</label>
                                    <input class="form-control" name="date" id="date" type="date" required>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="shipping_address">Shipping Address</label>
                                    <input class="form-control" name="shipping_address" id="shipping_address" type="text" placeholder="Enter Shipping Address" required>
                                </div>
                            </div>
                            <div id="product-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Item Price</th>
                                            <th>Total Price</th>
                                            <th>Quantity on Hand</th>
                                            <th>Reserved Stock</th>
                                            <th>Available Stock</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="product-row">
                                            <td>
                                                <select class="form-control item-select" name="item_id[]" required>
                                                    <option value="">Select Product</option>
                                                    <?php
                                                    $products_query = "SELECT item_id, item_name FROM item";
                                                    $products_result = mysqli_query($connect, $products_query);
                                                    while($product = mysqli_fetch_assoc($products_result)){
                                                        echo '<option value="'.$product['item_id'].'">'.$product['item_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input class="form-control quantity" name="quantity[]" type="number" placeholder="Enter Quantity" required>
                                            </td>
                                            <td>
                                                <input class="form-control item_price" name="item_price[]" type="number" placeholder="Enter Item Price" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control total-price" name="total_price[]" type="number" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control current-stock" name="current_stock[]" type="number" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control reserved-stock" name="reserved_stock[]" type="number" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control available-stock" name="available_stock[]" type="number" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-product">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" id="add-product">Add Another Product</button>
                                <button class="btn btn-primary" name="addOrderBtn" type="submit">Add Order</button>
                                <a href="order.php" class="btn btn-secondary">Back to Orders</a>
                            </div>
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
    <script src="search.js"></script>

    <script>
        $(document).ready(function(){
            function updateProductPrice(itemSelect) {
                var item_id = itemSelect.val();
                var priceInput = itemSelect.closest('tr').find('.item_price');
                var quantityInput = itemSelect.closest('tr').find('.quantity');
                var currentStockInput = itemSelect.closest('tr').find('.current-stock');
                var reservedStockInput = itemSelect.closest('tr').find('.reserved-stock');
                var availableStockInput = itemSelect.closest('tr').find('.available-stock');
                $.ajax({
                    url: 'get_item_price.php',
                    method: 'POST',
                    data: {item_id: item_id},
                    success: function(data) {
                        var response = JSON.parse(data);
                        priceInput.val(response.item_price);
                        currentStockInput.val(response.current_stock);
                        reservedStockInput.val(response.reserved_stock);
                        availableStockInput.val(response.available_stock);
                        quantityInput.attr('max', response.available_stock);
                        updateTotalPrice(itemSelect.closest('tr'));
                    }
                });
            }

            function updateTotalPrice(productRow) {
                var quantity = parseFloat(productRow.find('.quantity').val());
                var item_price = parseFloat(productRow.find('.item_price').val());
                var total_price = quantity * item_price;
                productRow.find('.total-price').val(total_price);
                updateOrderAmount();
            }

            function updateOrderAmount() {
                var totalAmount = 0;
                $('.total-price').each(function() {
                    totalAmount += parseFloat($(this).val());
                });
                $('#order_amount').val(totalAmount);
            }

            $('#product-container').on('change', '.item-select', function(){
                updateProductPrice($(this));
            });

            $('#product-container').on('input', '.quantity', function(){
                var max = parseFloat($(this).attr('max'));
                var value = parseFloat($(this).val());
                if (value > max) {
                    $(this).val(max);
                }
                updateTotalPrice($(this).closest('tr'));
            });

            $('#product-container').on('click', '.remove-product', function(){
                if ($('#product-container .product-row').length > 1) {
                    $(this).closest('tr').remove();
                    updateOrderAmount();
                } else {
                    $(this).closest('tr').find('input').val('');
                    $(this).closest('tr').find('select').val('');
                }
            });

            $('#add-product').click(function(){
                var newProductRow = $('.product-row:first').clone();
                newProductRow.find('input').val('');
                newProductRow.find('select').val('');
                $('#product-container tbody').append(newProductRow);
            });
        });
    </script>
</body>
</html>

<?php
if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $query = "SELECT * FROM item WHERE item_id = '$item_id'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    echo $row['item_price'];
    exit();
}
?>