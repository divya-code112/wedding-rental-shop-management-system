<?php
include "../includes/db.php";

$order_id = intval($_POST['order_id']);
$type = $_POST['damage_type'];

$order = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM orders WHERE order_id=$order_id
"));

$final_rent_due = $order['total_rent_amount'] - $order['total_deposit'];

$damage_fee = 0;
if($type=='minor') $damage_fee = $final_rent_due * 0.10;
if($type=='major') $damage_fee = $final_rent_due * 0.30;

$remaining = $final_rent_due + $order['late_fee'] + $damage_fee;
$refund = max(0, $order['total_deposit'] - ($order['late_fee'] + $damage_fee));

mysqli_query($conn,"
UPDATE orders SET
    damage_fee = $damage_fee,
    final_amount = $remaining,
    refund_amount = $refund
WHERE order_id=$order_id
");

header("Location: admin_manage_orders.php");
exit();
