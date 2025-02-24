<?php
session_start();
include('connection.php');
include('header.php');
include('navigation.php');

if (isset($_POST['deletebtn'])) {
    $expense_id = $_POST['expense_id'];

    // Delete expense items
    $delete_items_query = "DELETE FROM expenses_item WHERE expenses_id = '$expense_id'";
    $delete_items_result = mysqli_query($connect, $delete_items_query);

    // Delete expense
    $delete_expense_query = "DELETE FROM expenses WHERE expenses_id = '$expense_id'";
    if (mysqli_query($connect, $delete_expense_query)) {
        $success_message = "Expense deleted successfully!";
    } else {
        $error_message = "Failed to delete expense.";
    }
}
?>


<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Expenses</title>
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
                        <h2 class="mb-4">Expenses</h2>
                        <a href="add_expenses.php" class="btn btn-primary mb-4">Add Expenses</a>
                        <table class="table table-striped fs-5" width="100%" id="expensesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Supplier Name</th>
                                    <th scope="col" class="text-center">Payment</th>
                                    <th scope="col" class="text-center">Amount</th>
                                    <th scope="col" class="text-center">Reason</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $expenses_query = "SELECT * FROM expenses";
                                $expenses_result = mysqli_query($connect, $expenses_query);
                                if(mysqli_num_rows($expenses_result) > 0) {
                                    $i = 1;
                                    while($expense = mysqli_fetch_assoc($expenses_result)) {
                                        $supplier_id = $expense['supplier_id'];
                                        $get_supp = "SELECT * FROM supplier WHERE supplier_id = '$supplier_id'";
                                        $get_supp_run = mysqli_query($connect,$get_supp);
                                        $get_supp_row = mysqli_fetch_assoc($get_supp_run);
                                        $supplier_name = $get_supp_row['fname'] . " " . $get_supp_row['lname'];
                                        ?>
                                            
                                        <tr>
                                            <th scope="row" class="text-center"><?= $i++; ?></th>
                                            <td class="text-center"><?= $expense['date']; ?></td>
                                            <td class="text-center"><?= $supplier_name; ?></td>
                                            <td class="text-center"><?php echo ($expense['payment_status'] ==0 )  ? "Unpaid":"Paid" ?> </td>
                                            <td class="text-center">RM <?= number_format($expense['expenses_amount'], 2); ?></td>
                                            <td class="text-center"><?= $expense['reason']; ?></td>
                                            <td class="text-center">
                                                <form action="view_expenses.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="expense_id" value="<?= $expense['expenses_id']; ?>">
                                                <button class="btn btn-primary" name="viewbtn" type="submit">View</button>
                                                </form>
                                                <form action="edit_expenses.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="expense_id" value="<?= $expense['expenses_id']; ?>">
                                                <button class="btn btn-primary" name="editbtn" type="submit">Edit</button>
                                                </form>
                                                <form action="" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="expense_id" value="<?= $expense['expenses_id']; ?>">
                                                <button class="btn btn-danger" name="deletebtn" type="submit" onclick="return confirm('Are you sure you want to delete this order?');">Delete</button>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No expenses found</td>
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