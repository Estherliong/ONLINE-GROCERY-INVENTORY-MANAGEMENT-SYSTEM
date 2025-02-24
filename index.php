<?php
session_start();
include('connection.php');
include('cryptography.php'); 

if(!isset($_SESSION['identity'])) {
    echo '<script>window.location.href="login.php"</script>';
    exit();
} else {
    if($_SESSION['identity'] == 'supplier') {
        echo '<script>window.location.href="inventory.php"</script>';
        exit();
    }
}

$date_filter = isset($_GET['date_filter']) ? $_GET['date_filter'] : 'this_month';

switch ($date_filter) {
    case 'this_week':
        $start_date = date('Y-m-d', strtotime('monday this week'));
        $end_date = date('Y-m-d', strtotime('sunday this week'));
        break;
    case 'this_year':
        $start_date = date('Y-01-01');
        $end_date = date('Y-12-31');
        break;
    case 'this_month':
    default:
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        break;
}
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Admin Dashboard</title>
    <link rel="icon" href="../image/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.2.3/cerulean/bootstrap.min.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <?php include('header.php'); ?>
    <?php include('navigation.php'); ?>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="row mt-5">
                <!-- To be packed -->
                <div class="col-md-3">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-250">
                            <h5 class="text-muted danger position-absolute p-1">Packed</h5>
                            <div>
                                <i data-feather="package" class="danger font-large-1 float-right p-1"></i>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <?php
                                   $get_packed = "SELECT * FROM sales_order WHERE package_status = 0";
                                   $get_packed_run = mysqli_query($connect,$get_packed);
                                   $num_packed = mysqli_num_rows($get_packed_run);
                                ?>
                                <a href="package.php"><h1 class=""><?= $num_packed ?></h1></a>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- To be shipped -->
                <div class="col-md-3">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-250">
                            <h5 class="text-muted danger position-absolute p-1">Shipped</h5>
                            <div>
                                <i data-feather="truck" class="danger font-large-3 float-right p-1"></i>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <?php
                                    $get_shipped = "SELECT * FROM sales_order WHERE package_status = 1";
                                    $get_shipped_run = mysqli_query($connect,$get_shipped);
                                    $num_shipped = mysqli_num_rows($get_shipped_run);
                                ?>
                                <a href="package.php"><h1 class=""><?= $num_shipped?></h1></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- To be delivered -->
                <div class="col-md-3">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-250">
                            <h5 class="text-muted danger position-absolute p-1">Delivered</h5>
                            <div>
                                <i data-feather="box" class="danger font-large-1 float-right p-1"></i>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <?php
                                    $get_delivered = "SELECT * FROM sales_order WHERE package_status = 2";
                                    $get_delivered_run = mysqli_query($connect,$get_delivered);
                                    $num_delivered = mysqli_num_rows($get_delivered_run);
                                ?>
                                <a href="package.php"><h1 class=""><?= $num_delivered?></h1></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- To be invoiced -->
                <div class="col-md-3">
                    <div class="card pull-up ecom-card-1 bg-white">
                        <div class="card-content ecom-card2 height-250">
                            <h5 class="text-muted danger position-absolute p-1">To be invoiced</h5>
                            <div>
                                <i data-feather="file" class="danger font-large-1 float-right p-1"></i>
                            </div>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <?php
                                    $get_invoiced = "SELECT * FROM sales_order WHERE order_status = 0 AND payment_status = 1";
                                    $get_invoiced_run = mysqli_query($connect,$get_invoiced);
                                    $num_invoiced = mysqli_num_rows($get_invoiced_run);
                                ?>
                                <a href="invoice.php"><h1 class=""><?= $num_invoiced?></h1></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row match-height mt-2">
                <!-- Inventory Summary -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h3 class="card-title">Inventory Summary</h3>
                                <?php
                                    $total = 0;
                                    $inventory = "SELECT * FROM inventory";
                                    $inventory_run = mysqli_query($connect, $inventory);
                                    if (mysqli_num_rows($inventory_run) > 0) {
                                        while ($stock = mysqli_fetch_assoc($inventory_run)) {
                                            $current = $stock['current_stock'];
                                            $total += $current; 
                                        }
                                    }
                                ?>
                                <p style="color:#000000" class="card-text">Quantity In Hand : <?= $total ?></p>
                                <p style="margin-top:90px"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Product Details</h4>
                                <?php
                                    $low_stock = "SELECT * FROM inventory WHERE current_stock <= 20";
                                    $low_stock_run = mysqli_query($connect,$low_stock);
                                    if(mysqli_num_rows($low_stock_run) > 0) {
                                        $low_stock_count = mysqli_num_rows($low_stock_run);
                                    } else {
                                        $low_stock_count = 0;
                                    }
                                    $cate = "SELECT * FROM category";
                                    $cate_run = mysqli_query($connect,$cate);
                                    if(mysqli_num_rows($cate_run) > 0) {
                                        $cate_count = mysqli_num_rows($cate_run);
                                    } else {
                                        $cate_count = 0;
                                    }
                                    $item = "SELECT * FROM item";
                                    $item_run = mysqli_query($connect,$item);
                                    if(mysqli_num_rows($item_run) > 0) {
                                        $item_count = mysqli_num_rows($item_run);
                                    } else {
                                        $item_count = 0;
                                    }
                                ?>
                                <p style="color:#000000" class="card-text">Low Stock Items : <?= $low_stock_count ?></p>
                                <p style="color:#000000" class="card-text">All Item Categories : <?= $cate_count ?></p>
                                <p style="color:#000000" class="card-text">All Items  : <?= $item_count ?></p>
                                <p style="margin-top:20px"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Purchase Order Items -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Purchase Order</h4>
                                <p class="card-text"  style="color:#000000">Details of the purchase orders.</p>
                                <?php
                                    $quantity_ordered = 0;
                                    $total_cost = 0;
                                    $expenses = "SELECT * FROM expenses_item";
                                    $expenses_run = mysqli_query($connect,$expenses);
                                    while($item_expenses = mysqli_fetch_assoc($expenses_run)) {
                                        $quantity_ordered += $item_expenses['quantity'];
                                        $total_cost += $item_expenses['total_cost'];
                                    }
                                ?>
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Quantity Ordered:</strong> <?php echo $quantity_ordered; ?></li>
                                    <li class="list-group-item"><strong>Total Cost:</strong> RM <?php echo number_format($total_cost,2); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row match-height mt-2">
                <!-- Top Selling Items -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Top Selling Items</h4>
                                <div class="mb-3">
                                    <form method="GET" action="">
                                        <select name="date_filter" class="form-select" onchange="this.form.submit()">
                                            <option value="this_month" <?= $date_filter == 'this_month' ? 'selected' : '' ?>>This Month</option>
                                            <option value="this_week" <?= $date_filter == 'this_week' ? 'selected' : '' ?>>This Week</option>
                                            <option value="this_year" <?= $date_filter == 'this_year' ? 'selected' : '' ?>>This Year</option>
                                        </select>
                                    </form>
                                </div>
                                <?php
                                    $count = 1;
                                    $top_items = "SELECT i.item_name, SUM(oi.quantity) as total_quantity 
                                                  FROM order_item oi 
                                                  JOIN item i ON oi.item_id = i.item_id 
                                                  JOIN sales_order so ON oi.order_id = so.sales_order_id
                                                  WHERE so.date BETWEEN '$start_date' AND '$end_date'
                                                  GROUP BY oi.item_id 
                                                  ORDER BY total_quantity DESC 
                                                  LIMIT 3";
                                    $top_items_run = mysqli_query($connect,$top_items);
                                    if(mysqli_num_rows($top_items_run) > 0) {
                                        while($top_items_row = mysqli_fetch_assoc($top_items_run)) {
                                            ?>
                                            <p style="color:#000000" class="card-text"><?= $count ?> . <?= $top_items_row['item_name']?> (<?= $top_items_row['total_quantity']?> sold)</p>
                                            <?php
                                            $count++;
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div><!-- Low Stock Items -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Low Stock Items</h4>
                                <?php
                                    $count = 1;
                                    $low_stock = "SELECT item_id FROM inventory WHERE current_stock <= 20";
                                    $low_stock_run = mysqli_query($connect, $low_stock);
                                    if(mysqli_num_rows($low_stock_run) > 0) {
                                        while($low_stock_row = mysqli_fetch_assoc($low_stock_run)) {
                                            $item_id = $low_stock_row['item_id'];
                                            $item = "SELECT item_name FROM item WHERE item_id = $item_id";
                                            $item_run = mysqli_query($connect, $item);
                                            $low_item_row = mysqli_fetch_assoc($item_run);
                                            ?>
                                            <p style="color:#000000" class="card-text"><?= $count ?>. <?= $low_item_row['item_name']?></p>
                                            <?php
                                            $count++;
                                        }
                                    } else {
                                        echo '<p style="color:#000000" class="card-text">No low stock items found.</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sales Order -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Sales Order</h4>
                                <p class="card-text" style="color:#000000">Recent sales orders.</p>
                                <div class="mb-3">
                                    <form method="GET" action="">
                                        <select name="date_filter" class="form-select" onchange="this.form.submit()">
                                            <option value="this_month" <?= $date_filter == 'this_month' ? 'selected' : '' ?>>This Month</option>
                                            <option value="this_week" <?= $date_filter == 'this_week' ? 'selected' : '' ?>>This Week</option>
                                            <option value="this_year" <?= $date_filter == 'this_year' ? 'selected' : '' ?>>This Year</option>
                                        </select>
                                    </form>
                                </div>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $recent_orders_query = "SELECT so.sales_order_id, c.fname, c.lname, so.date, so.order_status, so.sales_order_amount 
                                                                FROM sales_order so 
                                                                JOIN customer c ON so.customer_id = c.customer_id 
                                                                WHERE so.date BETWEEN '$start_date' AND '$end_date'
                                                                ORDER BY so.sales_order_id DESC LIMIT 5";
                                        $recent_orders_result = mysqli_query($connect, $recent_orders_query);
                                        if (mysqli_num_rows($recent_orders_result) > 0) {
                                            while ($order = mysqli_fetch_assoc($recent_orders_result)) {
                                                $order['fname'] = decrypt_data($order['fname']);
                                                $order['lname'] = decrypt_data($order['lname']);
                                                ?>
                                                <tr>
                                                    <td><?php echo $order['sales_order_id']; ?></td>
                                                    <td><?php echo $order['fname'] . " " . $order['lname']; ?></td>
                                                    <td><?php echo $order['date']; ?></td>
                                                    <td><?php echo ($order['order_status'] == 0) ? "Unchecked" : "Invoiced"; ?></td>
                                                    <td>RM <?php echo number_format($order['sales_order_amount'], 2); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No recent orders found</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>    
        </div>    
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.0/js/bootstrap.min.js"></script>
    <!-- END JS-->
    <script>
        feather.replace();
    </script>
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->
</body>
</html>