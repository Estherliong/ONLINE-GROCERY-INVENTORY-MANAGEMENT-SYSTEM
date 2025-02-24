<?php
session_start();
include('connection.php');
include('cryptography.php');
if (isset($_POST['category_id']) && !is_array($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    $products_query = "SELECT item_id, item_name FROM item WHERE category_id = '$category_id'";
    $products_result = mysqli_query($connect, $products_query);
    $options = "<option value=''>Select Product</option>";
    while ($product = mysqli_fetch_assoc($products_result)) {
        $options .= "<option value='" . $product['item_id'] . "'>" . $product['item_name'] . "</option>";
    }
    echo $options;
    exit();
}

if (isset($_POST['item_id']) && !is_array($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $query = "SELECT item_cost FROM item WHERE item_id = '$item_id'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    echo $row['item_cost'];
    exit();
}

if (isset($_POST['addCostBtn'])) {
    $reason = $_POST['reason'];
    $date = $_POST['date'];
    $category_ids = $_POST['category_id'];
    $item_ids = $_POST['item_id'];
    $current_costs = $_POST['current_cost'];
    $new_costs = $_POST['new_cost'];
    $adjustments = $_POST['adjustment'];
    $email = $_SESSION['email'];
    $encrypted_email = encrypt_data($email);
    if($_SESSION['identity'] == 'admin')
    {
        $user_query = "SELECT * FROM admin WHERE email = '$encrypted_email'";
        $role = 0;
        $get_user = mysqli_query($connect, $user_query);
        if(mysqli_num_rows($get_user) > 0)
        {
            $user = mysqli_fetch_assoc($get_user);
            $user_id = $user['admin_id'];
        }
    }
    else
    {
        $user_query = "SELECT * FROM supplier WHERE email = '$encrypted_email'";
        $role = 1;
        $get_user = mysqli_query($connect, $user_query);
        if(mysqli_num_rows($get_user) > 0)
        {
            $user = mysqli_fetch_assoc($get_user);
            $user_id = $user['supplier_id'];
        }
    }
    $adjustment_query = "INSERT INTO adjustment (user_id, reason, type, date, role) VALUES ('$user_id', '$reason', 2, '$date', '$role')";
    $adjustment_query_run = mysqli_query($connect, $adjustment_query);
    if ($adjustment_query_run) {
        $adjustment_id = mysqli_insert_id($connect);

        foreach ($item_ids as $index => $item_id) {
            $category_id = $category_ids[$index];
            $current_cost = $current_costs[$index];
            $new_cost = $new_costs[$index];
            $adjustment = $adjustments[$index];

            $update_item_query = "UPDATE item SET item_cost = '$new_cost' WHERE item_id = '$item_id'";
            mysqli_query($connect, $update_item_query);

            $update_cost_adjustment_query = "INSERT INTO price_adjustment (item_id, adjustment_id, adjustment_amount,adjustment_type) VALUES ('$item_id', '$adjustment_id', '$adjustment',1)";
            mysqli_query($connect, $update_cost_adjustment_query);
        }

        header('Location: cost.php');
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
    <title>Add Cost Adjustment</title>
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
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row gx-3 mb-3">
                            <h2>Cost Adjustment</h2>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputreason">Reason</label>
                                    <input class="form-control" name="reason" id="inputreason" type="text" placeholder="Enter Reason" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputdate">Date</label>
                                    <input class="form-control" name="date" id="inputdate" type="date" required>
                                </div>
                            </div>
                            <div id="product-container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Product</th>
                                            <th>Current Cost</th>
                                            <th>New Cost</th>
                                            <th>Cost Adjustment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="product-row">
                                            <td>
                                                <select class="form-control category-select" name="category_id[]" required>
                                                    <option value="">Select Category</option>
                                                    <?php
                                                    $categories_query = "SELECT * FROM category WHERE status = 1";
                                                    $categories_result = mysqli_query($connect, $categories_query);
                                                    while($category = mysqli_fetch_assoc($categories_result)){
                                                        echo '<option value="'.$category['category_id'].'">'.$category['category_name'].'</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control item-select" name="item_id[]" required>
                                                    <option value="">Select Product</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input class="form-control current-cost" name="current_cost[]" type="number" pattern="^\d*(\.\d{0,2})?$" step="0.01" oninput="validateDecimal(this)" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input class="form-control new-cost" name="new_cost[]" type="number" pattern="^\d*(\.\d{0,2})?$" step="0.01" oninput="validateDecimal(this)" placeholder="Enter New Cost">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">RM</span>
                                                    <input class="form-control adjustment" name="adjustment[]" type="number" pattern="^\d*(\.\d{0,2})?$" step="0.01" oninput="validateDecimal(this)" placeholder="Enter Cost Adjustment">
                                                </div>
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
                                <button class="btn btn-primary" name="addCostBtn" type="submit">Add Cost Adjustment</button>
                                <a href="cost.php" class="btn btn-secondary">Back to Cost Adjustments</a>
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
        function updateProductOptions(categorySelect) {
            var category_id = categorySelect.val();
            var itemSelect = categorySelect.closest('tr').find('.item-select');
            $.ajax({
                url: 'cost_adjust.php',
                method: 'POST',
                data: {category_id: category_id},
                success: function(data){
                    itemSelect.html(data);
                }
            });
        }

        function updateCurrentCost(itemSelect) {
            var item_id = itemSelect.val();
            var currentCostInput = itemSelect.closest('tr').find('.current-cost');
            $.ajax({
                url: 'cost_adjust.php',
                method: 'POST',
                data: {item_id: item_id},
                success: function(data){
                    currentCostInput.val(parseFloat(data).toFixed(2));
                }
            });
        }

        function updateAdjustmentAndNewCost(productRow) {
            var current_cost = parseFloat(productRow.find('.current-cost').val());
            var new_cost = parseFloat(productRow.find('.new-cost').val());
            var adjustment = new_cost - current_cost;
            productRow.find('.adjustment').val(adjustment.toFixed(2));
        }

        function updateNewCostAndAdjustment(productRow) {
            var current_cost = parseFloat(productRow.find('.current-cost').val());
            var adjustment = parseFloat(productRow.find('.adjustment').val());
            var new_cost = current_cost + adjustment;
            productRow.find('.new-cost').val(new_cost.toFixed(2));
        }

        $('#product-container').on('change', '.category-select', function(){
            updateProductOptions($(this));
        });

        $('#product-container').on('change', '.item-select', function(){
            updateCurrentCost($(this));
        });

        $('#product-container').on('input', '.new-cost', function(){
            updateAdjustmentAndNewCost($(this).closest('tr'));
        });

        $('#product-container').on('input', '.adjustment', function(){
            updateNewCostAndAdjustment($(this).closest('tr'));
        });

        $('#product-container').on('click', '.remove-product', function(){
            if ($('#product-container .product-row').length > 1) {
                $(this).closest('tr').remove();
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

        function validateDecimal(input) {
            var value = input.value;
            var regex = /^\d+(\.\d{0,2})?$/;
            if (!regex.test(value)) {
                input.value = value.slice(0, -1);
            }
        }
    });
</script>
</body>
</html>