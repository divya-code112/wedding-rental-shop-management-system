<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_POST['order_id'] ?? 0);
$amount = floatval($_POST['amount'] ?? 0);

if(!$order_id || $amount <= 0) die("Invalid request");

// Update order deposit payment
mysqli_query($conn, "
    UPDATE orders SET 
        payment_status='paid', 
        paid_amount=$amount,
        paid_at=NOW()
    WHERE order_id=$order_id AND user_id=$user_id
");

// Remove items from cart
mysqli_query($conn, "DELETE FROM cart WHERE user_id=$user_id");

// Redirect to order confirmed page
header("Location: order_confirmed.php?order_id=$order_id");
exit();
?>
