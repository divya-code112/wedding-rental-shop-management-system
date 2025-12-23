<?php
// send_sms.php
session_start();
include "../includes/db.php";

if(!isset($_GET['order_id'])) {
    die("Invalid request");
}

$order_id = intval($_GET['order_id']);
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT o.*, u.full_name, u.mobile 
    FROM orders o 
    JOIN users u ON o.user_id=u.user_id 
    WHERE o.order_id=$order_id"));

if(!$order) die("Order not found");

// Compose SMS
$message = "Dear {$order['full_name']}, your order #$order_id is overdue. Please return the items ASAP.";

// Save to SMS log (simulate sending)
file_put_contents("../sms_log.txt", date('Y-m-d H:i:s')." | {$order['mobile']} | $message\n", FILE_APPEND);

echo "<script>alert('SMS sent to {$order['full_name']} ({$order['mobile']})'); window.location='manage_orders.php';</script>";
