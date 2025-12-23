<?php
include "../includes/db.php";

$order_id = intval($_POST['order_id']);
$late_fee = floatval($_POST['late_fee']);
$damage_fee = floatval($_POST['damage_fee']);
$damage_status = $_POST['damage_status'];

$o = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM orders WHERE order_id=$order_id
"));

$total = $o['total_rent_amount'] + $late_fee + $damage_fee;
$advance = $o['advance_amount'];

$remaining = max(0, $total - $advance);
$refund = max(0, $advance - $total);

mysqli_query($conn,"
UPDATE orders SET
order_status='returned',
returned_at=NOW(),
late_fee='$late_fee',
damage_fee='$damage_fee',
refund_amount='$refund',
final_amount='$remaining',
final_payment_status='".($remaining>0?'pending':'paid')."'
WHERE order_id=$order_id
");

header("Location: return_invoice.php?order_id=$order_id");
