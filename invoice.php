<?php
session_start();
include('connection.php');

if (isset($_POST['generatePdfBtn'])) {
    $invoice_id = $_POST['invoice_id'];
    generateInvoicePdf($connect, $invoice_id);
}

function generateInvoicePdf($connect, $invoice_id) {
    require('fpdf186/fpdf.php'); 

    // Fetch invoice details
    $invoice_query = "SELECT * FROM invoice WHERE invoice_id = '$invoice_id'";
    $invoice_result = mysqli_query($connect, $invoice_query);
    $invoice = mysqli_fetch_assoc($invoice_result);

    // Fetch customer details
    function decrypt_data($data) {
        $encryption_key = 'flower'; 
        $iv = '12345678912';
        $data = base64_decode($data);
        if (strpos($data, '::') !== false) {
            list($encrypted_data, $iv) = explode('::', $data, 2);
            return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
        }
        return false; 
    }
    $customer_id = $invoice['customer_id'];
    $customer_query = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
    $customer_result = mysqli_query($connect, $customer_query);
    $customer = mysqli_fetch_assoc($customer_result);
    $customer_name = decrypt_data($customer['fname']) . " " . decrypt_data($customer['lname']);

    // Fetch order details
    $order_id = $invoice['order_id'];
    $order_query = "SELECT * FROM sales_order WHERE sales_order_id = '$order_id'";
    $order_result = mysqli_query($connect, $order_query);
    $order = mysqli_fetch_assoc($order_result);

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Add company logo
    $pdf->Image('image/inventory.png', 10, 10, 30);
    
    // Add company information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Power Inventory', 0, 1, 'R');
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 10, 'Jalan Ayer Keroh Lama, ', 0, 1, 'R');
    $pdf->Cell(0, 10, '75450 Bukit Beruang, Melaka', 0, 1, 'R');
    $pdf->Cell(0, 10, '75450', 0, 1, 'R');
    $pdf->Cell(0, 10, 'Phone: 1-300-80-0668', 0, 1, 'R');
    $pdf->Cell(0, 10, 'Email: powerinventory@gmail.com', 0, 1, 'R');
    
    // Add a line break
    $pdf->Ln(20);
    
    // Add invoice title
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
    
    // Add invoice details
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Invoice ID: ' . $invoice_id, 0, 1);
    $pdf->Cell(0, 10, 'Customer Name: ' . $customer_name, 0, 1);
    $pdf->Cell(0, 10, 'Order Date: ' . $order['date'], 0, 1);
    $pdf->Cell(0, 10, 'Invoice Date: ' . $invoice['created_at'], 0, 1);
    $pdf->Cell(0, 10, 'Amount: RM ' . number_format($order['sales_order_amount'], 2), 0, 1);
    
    // Add a line break
    $pdf->Ln(20);
    
    // Add table header
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(20, 10, 'Item', 1);
    $pdf->Cell(100, 10, 'Name', 1);
    $pdf->Cell(20, 10, 'Quantity', 1);
    $pdf->Cell(25, 10, 'Unit Price', 1);
    $pdf->Cell(20, 10, 'Total', 1);
    $pdf->Ln();
    
    // Fetch order items
    $order_items_query = "SELECT * FROM order_item WHERE order_id = '$order_id'";
    $order_items_result = mysqli_query($connect, $order_items_query);
    
    // Add table rows
    $pdf->SetFont('Arial', '', 8);
    while ($item = mysqli_fetch_assoc($order_items_result)) {
        
  
        $pdf->Cell(20, 10, $item['item_id'], 1);
        
        $pdf->Cell(100, 10, $item['item_name'], 1);
        $pdf->Cell(20, 10, $item['quantity'], 1);
        $pdf->Cell(25, 10, 'RM ' . number_format($item['item_price'], 2), 1);
        $pdf->Cell(20, 10, 'RM ' . number_format($item['quantity'] * $item['item_price'], 2), 1);
        $pdf->Ln();
    }
    
    // Output PDF
    $pdf->Output('D', 'invoice_' . $invoice_id . '.pdf');
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
    <title>View Invoices</title>
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
                        <h2 class="mb-4">Invoices</h2>
                        <a href="add_invoice.php" class="btn btn-primary mb-4">Add Invoice</a>
                        <table class="table table-striped fs-5" width="100%" id="invoicesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Date</th>
                                    <th scope="col" class="text-center">Invoice Date</th>
                                    <th scope="col" class="text-center">Customer Name</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $invoices_query = "SELECT * FROM invoice";
                                $invoices_result = mysqli_query($connect, $invoices_query);
                                
                                if(mysqli_num_rows($invoices_result) > 0) {
                                    $i = 1;
                                    while($invoice = mysqli_fetch_assoc($invoices_result)) {
                                        $customer_id = $invoice['customer_id'];
                                        $cus = "SELECT * FROM customer WHERE customer_id = '$customer_id'";
                                        $cus_run = mysqli_query($connect,$cus);
                                        $cus_info = mysqli_fetch_assoc($cus_run);
                                        $customer_name = decrypt_data($cus_info['fname']) . " " . decrypt_data($cus_info['lname']);
                                        $order_id = $invoice['order_id'];
                                        $order_query = "SELECT * FROM sales_order WHERE sales_order_id = '$order_id'";
                                        $order_result = mysqli_query($connect, $order_query);
                                        $order = mysqli_fetch_assoc($order_result);
                                        ?>
                                        <tr>
                                            <th scope="row" class="text-center"><?= $i++; ?></th>
                                            <td class="text-center"><?= $order['date']; ?></td>
                                            <td class="text-center"><?= $invoice['created_at']; ?></td>
                                            <td class="text-center"><?= $customer_name; ?></td>
                                            <td class="text-center">
                                            <form action="view_invoice.php" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="invoice_id" value="<?= $invoice['invoice_id']; ?>">
                                                <button class="btn btn-primary" name="viewbtn" type="submit">View</button>
                                            </form>
                                            <form action="" method="POST" style="display:inline-block;">
                                                <input type="hidden" name="invoice_id" value="<?= $invoice['invoice_id']; ?>">
                                                <button class="btn btn-secondary" name="generatePdfBtn" type="submit">Generate PDF</button>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No invoices found</td>
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
</body>
</html>