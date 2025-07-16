<?php
$allOrders = file_get_contents("orders.txt");
if (!isset($_GET['id'])) {
    $orders = explode("\n", $allOrders);
    foreach ($orders as $order) {
        if (!empty(trim($order))) {
            echo rtrim($order, ',') . "<br>";
        }
    }
}
else {
    $orders = explode("\n", $allOrders);
    
    foreach ($orders as $order) {
        if (!empty(trim($order)) && explode(' - ', $order)[0] === $_GET['id']) {
            $order = rtrim($order, ',');
            break;
        }
    }
    echo $order;
}
?>