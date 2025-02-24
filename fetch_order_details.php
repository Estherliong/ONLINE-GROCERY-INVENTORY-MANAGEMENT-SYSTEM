<?php
session_start();
include('connection.php');

if (isset($_POST['order_id'])) {
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
    $grand_total = 0;
    while ($item = mysqli_fetch_assoc($order_items_result)) {
        $total_price = $item['quantity'] * $item['item_price'];
        $order_items_html .= '<tr>
                                <td>'.$item['item_name'].'</td>
                                <td>'.$item['quantity'].'</td>
                                <td>RM '.number_format($item['item_price'], 2).'</td>
                                <td>RM '.number_format($total_price, 2).'</td>
                              </tr>';
        $grand_total += $total_price;
    }

    $response = array(
        'customer_name' => $customer['fname'] . ' ' . $customer['lname'],
        'order_items' => $order_items_html,
        'grand_total' => 'RM ' . number_format($grand_total, 2)
    );

    echo json_encode($response);
    exit();
}
?>
