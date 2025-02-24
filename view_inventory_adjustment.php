<?php
session_start();
include('connection.php');
include('header.php');
include('navigation.php'); 
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>View Inventory Adjustments</title>
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
                        <h2 class="mb-4">Adjustment Details</h2>
                        <?php
                            if(isset($_POST['adjustmentid']))
                            {
                                $adjustment_id = $_POST['adjustmentid'];
                                $adjustment_query = "SELECT * FROM adjustment WHERE adjustment_id = $adjustment_id";
                                $adjustment_query_run = mysqli_query($connect, $adjustment_query);
                                
                                if(mysqli_num_rows($adjustment_query_run) > 0)
                                {
                                    $adjustment = mysqli_fetch_assoc($adjustment_query_run);

                                    $user_id = $adjustment['user_id'];
                                    if($adjustment['role']==0)
                                    {
                                        $user_query = "SELECT * FROM admin WHERE admin_id = '$user_id'";
                                    }
                                    
                                    else
                                    {
                                        $user_query = "SELECT * FROM supplier WHERE supplier_id = '$user_id'";
                                    }
                                    
                                    $user_get = mysqli_query($connect, $user_query);
                                    $row = mysqli_fetch_assoc($user_get);
                                    $type = ($adjustment['type'] == 0) ? "Inventory" : "Value";
                                    $inv_adjustment_query = "SELECT * FROM inventory_adjustment WHERE adjustment_id = $adjustment_id";
                                    $inv_adjustment_query_run = mysqli_query($connect, $inv_adjustment_query);
                                
                                    ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Adjustment Information</h5>
                                            <p><strong>Date:</strong> <?= $adjustment['date']; ?></p>
                                            <p><strong>Reason:</strong> <?= $adjustment['reason']; ?></p>
                                            <?php
                                            if($adjustment['role'] == '0')
                                            {
                                                ?>
                                                    <p><strong>Admin:</strong> <?= $row['fname'] . " " . $row['lname']; ?></p>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <p><strong>Supplier:</strong> <?= $row['fname'] . " " . $row['lname']; ?></p>
                                                <?php
                                            }
                                            ?>
                                            <p><strong>Adjustment Type:</strong> <?= $type; ?></p>
                                        </div>
                                    </div>
                                    <?php
                                }
                            

                        ?>
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Adjustment Items</h5>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <?php
                                            if($type == "Inventory")
                                            {
                                                ?>
                                                <th>Quantity Adjusted</th>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <th>Price Adjusted</th>
                                                <?php
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                        if(mysqli_num_rows($inv_adjustment_query_run) > 0)
                                        {
                                            $i = 1;
                                            while($inv_adjustment = mysqli_fetch_assoc($inv_adjustment_query_run))
                                            {
                                                $item_id = $inv_adjustment['item_id'];
                                                $item_query = "SELECT * FROM item WHERE item_id = '$item_id'";
                                                $item_result = mysqli_query($connect, $item_query);
                                                $item = mysqli_fetch_assoc($item_result);
                                                ?>
                                                <tr>
                                                    <th scope="row" class="text-center"><?= $i++; ?></th>
                                                    <td class="text-center"><?= $item['item_name']; ?></td>
                                                    <td class="text-center"><?= number_format($inv_adjustment['adjustment_amount'], 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            echo "<tr><td colspan='3' class='text-center'>No adjustments found for this ID.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                                ?>
                                <a href="inventory.php" class="btn btn-secondary mt-4">Back to Inventory</a>
                                <?php
                            }
                            else
                            {
                                echo "<div class='alert alert-danger'>No adjustment ID provided.</div>";
                            }
                        ?>
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