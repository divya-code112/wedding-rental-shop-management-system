<?php
session_start();
include "../includes/db.php";

$id = intval($_POST['order_id']);
$amt = floatval($_POST['amount']);

mysqli_query($conn,"UPDATE orders SET final_payment_status='paid', paid_amount=paid_amount+$amt, paid_at=NOW() WHERE order_id=$id");

header("Location: order_success.php?order_id=$id");
exit();
