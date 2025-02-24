<?php
include('connection.php'); // Include the database connection file

if (isset($_POST['item_id'])) {
    $item_id = mysqli_real_escape_string($connect, $_POST['item_id']); // Sanitize input
    $query = "SELECT item_cost FROM item WHERE item_id = '$item_id'";
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['item_cost']; // Output the item cost
    } else {
        echo 0; // Output 0 if no item is found
    }
} else {
    echo 0; // Output 0 if item_id is not set
}
?>