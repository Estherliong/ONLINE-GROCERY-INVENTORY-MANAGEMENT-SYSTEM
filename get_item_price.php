<?php
include('connection.php');

if (isset($_POST['item_id'])) {
    $item_id = $_POST['item_id'];
    $query = "SELECT item_price FROM item WHERE item_id = '$item_id'";
    $stock = "SELECT current_stock, reserved_stock FROM inventory WHERE item_id = '$item_id'";
    $result = mysqli_query($connect, $query);
    $result2 = mysqli_query($connect, $stock);
    $row = mysqli_fetch_assoc($result);
    $row2 = mysqli_fetch_assoc($result2);
    $available_stock = $row2['current_stock'] - $row2['reserved_stock'];
    echo json_encode([
        'item_price' => $row['item_price'],
        'current_stock' => $row2['current_stock'],
        'reserved_stock' => $row2['reserved_stock'],
        'available_stock' => $available_stock
    ]);
    exit();
}
?>