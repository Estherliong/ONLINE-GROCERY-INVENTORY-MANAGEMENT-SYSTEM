<?php
session_start();
include('connection.php');

if (isset($_POST['addExpenseBtn'])) {
    $expense_id = $_POST['expenses_id'];
    $supplier_id = $_POST['supplier_id'];
    $payment_status = $_POST['payment_status'];
    $expense_amount = $_POST['expense_amount'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];

    $expense_query = "INSERT INTO expenses (supplier_id, payment_status, expenses_amount, date, reason) 
                      VALUES ('$supplier_id', '$payment_status', '$expense_amount', '$date', '$reason')";
    $expense_query_run = mysqli_query($connect, $expense_query);

    if ($expense_query_run) {
        $expense_id = mysqli_insert_id($connect);

        foreach ($_POST['item_id'] as $index => $item_id) {
            $quantity = $_POST['quantity'][$index];
            $item_cost = $_POST['item_cost'][$index];
            $total_cost = $_POST['total_cost'][$index];

            $expense_item_query = "INSERT INTO expenses_item (expenses_id, item_id, quantity, item_cost, total_cost) 
                                   VALUES ('$expense_id', '$item_id', '$quantity', '$item_cost', '$total_cost')";
            mysqli_query($connect, $expense_item_query);

            // Update inventory
            $update_stock_query = "UPDATE inventory SET current_stock = current_stock + '$quantity' WHERE item_id = '$item_id'";
            mysqli_query($connect, $update_stock_query);
        }

        header('Location: expenses.php');
        exit();
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
    <title>Add Expense</title>
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
                        <h2>Add Expense</h2>
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <?php
                                    $expense_query = "SELECT MAX(expenses_id) AS max_id FROM expenses";
                                    $expense_result = mysqli_query($connect, $expense_query);
                                    $expense = mysqli_fetch_assoc($expense_result);
                                    $expense_id = $expense['max_id'] + 1;
                                    ?>
                                    <label class="small mb-1" for="expenses_id">Expense ID</label>
                                    <input class="form-control" name="expenses_id" id="expenses_id" type="text" value="<?= $expense_id ?>" placeholder="Enter Expense ID" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="supplier_id">Supplier Name</label>
                                    <select class="form-control" name="supplier_id" id="supplier_id" required>
                                        <option value="">Select Supplier</option>
                                        <?php
                                        $suppliers_query = "SELECT * FROM supplier WHERE verify_status = 1";
                                        $suppliers_result = mysqli_query($connect, $suppliers_query);
                                        while($supplier = mysqli_fetch_assoc($suppliers_result)){
                                            echo '<option value="'.$supplier['supplier_id'].'">'.$supplier['fname']. " " . $supplier['lname'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="payment_status">Payment Status</label>
                                    <select class="form-control" name="payment_status" id="payment_status" required>
                                        <option value="0">Unpaid</option>
                                        <option value="1">Paid</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="expense_amount">Expenses Amount(RM)</label>
                                    <input class="form-control" name="expense_amount" id="expense_amount" type="number" placeholder="Enter Expense Amount(RM)" readonly>
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-6">
                                    <label class="small mb-1" for="date">Date</label>
                                    <input class="form-control" name="date" id="date" type="date" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="reason">Reason</label>
                                    <input class="form-control" name="reason" id="reason" type="text" placeholder="Enter Reason" required>
                                </div>
                            </div>
                            <div id="product-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Item Cost</th>
                                            <th>Total Cost</th>
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
                                                <input class="form-control item_cost" name="item_cost[]" type="number" placeholder="Enter Item Cost(RM)" readonly>
                                            </td>
                                            <td>
                                                <input class="form-control total-cost" name="total_cost[]" type="number" readonly>
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
                                <button class="btn btn-primary" name="addExpenseBtn" type="submit">Add Expense</button>
                                <a href="expenses.php" class="btn btn-secondary">Back to Expenses</a>
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

    <script>
        $(document).ready(function(){
            function updateProductCost(itemSelect) {
                var item_id = itemSelect.val(); // Get selected item ID
                var costInput = itemSelect.closest('tr').find('.item_cost'); // Target the item cost field

                $.ajax({
                    url: 'get_item_cost.php', 
                    method: 'POST',
                    data: { item_id: item_id }, 
                    success: function(data) {
                        costInput.val(parseFloat(data).toFixed(2)); 
                        updateTotalCost(itemSelect.closest('tr')); 
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error); 
                    }
                });
            }

            function updateTotalCost(productRow) {
                var quantity = parseFloat(productRow.find('.quantity').val());
                var item_cost = parseFloat(productRow.find('.item_cost').val());
                var total_cost = quantity * item_cost;
                productRow.find('.total-cost').val(total_cost.toFixed(2));
                updateExpenseAmount();
            }

            function updateExpenseAmount() {
                var totalAmount = 0;
                $('.total-cost').each(function() {
                    totalAmount += parseFloat($(this).val());
                });
                $('#expense_amount').val(totalAmount.toFixed(2));
            }

            $('#product-container').on('change', '.item-select', function(){
                updateProductCost($(this));
            });

            $('#product-container').on('input', '.quantity', function(){
                updateTotalCost($(this).closest('tr'));
            });

            $('#product-container').on('click', '.remove-product', function(){
                if ($('#product-container .product-row').length > 1) {
                    $(this).closest('tr').remove();
                    updateExpenseAmount();
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
    $query = "SELECT item_cost FROM item WHERE item_id = '$item_id'";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['item_cost'];
    } else {
        echo 0; 
    }
}
?>