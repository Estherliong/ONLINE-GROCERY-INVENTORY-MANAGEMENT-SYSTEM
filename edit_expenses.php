<?php
session_start();
include('connection.php');

if (isset($_POST['editbtn'])) {
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

    // Fetch expense items
    $expense_items_query = "SELECT ei.*, i.item_name FROM expenses_item ei JOIN item i ON ei.item_id = i.item_id WHERE ei.expenses_id = '$expense_id'";
    $expense_items_result = mysqli_query($connect, $expense_items_query);
}

if (isset($_POST['updateExpenseBtn'])) {
    $expense_id = $_POST['expense_id'];
    $supplier_id = $_POST['supplier_id'];
    $payment_status = $_POST['payment_status'];
    $expense_amount = str_replace('RM ', '', $_POST['expense_amount']);
    $date = $_POST['date'];

    // Update expense details
    $update_expense_query = "UPDATE expenses SET supplier_id = '$supplier_id', payment_status = '$payment_status', expenses_amount = '$expense_amount', date = '$date' WHERE expenses_id = '$expense_id'";
    mysqli_query($connect, $update_expense_query);

    // Update expense items
    foreach ($_POST['item_id'] as $index => $item_id) {
        $quantity = $_POST['quantity'][$index];
        $item_cost = str_replace('RM ', '', $_POST['item_cost'][$index]);
        $total_cost = str_replace('RM ', '', $_POST['total_cost'][$index]);

        $update_expense_item_query = "UPDATE expenses_item SET quantity = '$quantity', item_cost = '$item_cost', total_cost = '$total_cost' WHERE expenses_id = '$expense_id' AND item_id = '$item_id' LIMIT 1";
        mysqli_query($connect, $update_expense_item_query);
    }

    header('Location: expenses.php');
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
    <title>Edit Expense</title>
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
                        <h2 class="mb-4">Edit Expense</h2>
                        <form method="POST">
                            <input type="hidden" name="expense_id" value="<?= $expense['expenses_id']; ?>">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Expense Information</h5>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" name="date" value="<?= $expense['date']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="supplier_id">Supplier Name</label>
                                        <select class="form-control" name="supplier_id" required>
                                            <option value="">Select Supplier</option>
                                            <?php
                                            $suppliers_query = "SELECT * FROM supplier WHERE verify_status = 1";
                                            $suppliers_result = mysqli_query($connect, $suppliers_query);
                                            while($supplier = mysqli_fetch_assoc($suppliers_result)){
                                                $selected = ($supplier['supplier_id'] == $expense['supplier_id']) ? 'selected' : '';
                                                echo '<option value="'.$supplier['supplier_id'].'" '.$selected.'>'.$supplier['fname'].' '.$supplier['lname'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status</label>
                                        <select class="form-control" name="payment_status" required>
                                            <option value="0" <?= ($expense['payment_status'] == 0) ? 'selected' : ''; ?>>Unpaid</option>
                                            <option value="1" <?= ($expense['payment_status'] == 1) ? 'selected' : ''; ?>>Paid</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="expense_amount">Expense Amount</label>
                                        <input type="text" class="form-control" name="expense_amount" value="RM <?= number_format($expense['expenses_amount'], 2); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-4">
                                <div class="card-body">
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
                                                    <td><input type="number" class="form-control quantity" name="quantity[]" value="<?= $item['quantity']; ?>" required></td>
                                                    <td><input type="text" class="form-control item_cost" name="item_cost[]" value="RM <?= number_format($item['item_cost'], 2); ?>" readonly></td>
                                                    <td><input type="text" class="form-control total_cost" name="total_cost[]" value="RM <?= number_format($item['total_cost'], 2); ?>" readonly></td>
                                                    <input type="hidden" name="item_id[]" value="<?= $item['item_id']; ?>">
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4" name="updateExpenseBtn">Save</button>
                            <a href="expenses.php" class="btn btn-secondary mt-4">Back to Expenses</a>
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
    $(document).ready(function () {
        function updateTotalCost(productRow) {
            var quantity = parseFloat(productRow.find('.quantity').val()) || 0;
            var item_cost = parseFloat(productRow.find('.item_cost').val().replace(/[^\d.-]/g, '')) || 0;
            var total_cost = quantity * item_cost;

            productRow.find('.total_cost').val('RM ' + total_cost.toFixed(2));
            updateExpenseAmount();
        }

        function updateExpenseAmount() {
            var totalAmount = 0;
            $('.total_cost').each(function () {
                var cost = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                totalAmount += cost;
            });
            $('input[name="expense_amount"]').val('RM ' + totalAmount.toFixed(2));
        }

        // Update the cost when quantity changes
        $('.quantity').on('input', function () {
            updateTotalCost($(this).closest('tr'));
        });

        // Trigger the total cost update before form submission
        $('form').on('submit', function (event) {
            // Ensure that all total costs are updated before submitting
            $('.quantity').each(function () {
                updateTotalCost($(this).closest('tr'));
            });
        });
    });
</script>
</body>
</html>