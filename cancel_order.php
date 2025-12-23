<?php
// public/cancel_order.php
session_start();
header('Content-Type: application/json');
include "../includes/db.php";
if(!isset($_SESSION['user_id'])){ echo json_encode(['success'=>false,'message'=>'Login required']); exit(); }
$user_id = intval($_SESSION['user_id']);
$order_id = intval($_POST['order_id'] ?? 0);
if(!$order_id){ echo json_encode(['success'=>false,'message'=>'Missing order id']); exit(); }

$q = mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id AND user_id=$user_id LIMIT 1");
if(!$q || mysqli_num_rows($q)===0){ echo json_encode(['success'=>false,'message'=>'Order not found']); exit(); }
$order = mysqli_fetch_assoc($q);
if($order['order_status'] === 'cancelled'){ echo json_encode(['success'=>false,'message'=>'Already cancelled']); exit(); }

$now = date('Y-m-d H:i:s');
$deadline = $order['cancellation_deadline'];

if($deadline && strtotime($now) <= strtotime($deadline)){
    // within 5 hours: full refund
    $refund_amount = floatval($order['total_amount_payable']);
} else {
    // after 5 hours: refund deposit only
    $refund_amount = floatval($order['total_deposit']);
}

mysqli_begin_transaction($conn);
try {
    // update order status and refund_amount (accumulate)
    mysqli_query($conn, "UPDATE orders SET order_status='cancelled', refund_amount = ".number_format($refund_amount,2,'.','')." WHERE order_id=$order_id");

    // insert payment refund record (dummy)
    mysqli_query($conn, "INSERT INTO payment (order_id,user_id,amount,payment_type,payment_method,payment_status,transaction_date,charges_for) VALUES ($order_id,$user_id,".number_format($refund_amount,2,'.','').",'refund','dummy','success',NOW(),'advance')");

    mysqli_commit($conn);
    echo json_encode(['success'=>true,'message'=>'Order cancelled. Refund processed: ₹'.number_format($refund_amount,2), 'refund_amount'=>round($refund_amount,2)]);
    exit();
} catch(Exception $e){
    mysqli_rollback($conn);
    echo json_encode(['success'=>false,'message'=>'Cancel/refund failed']);
    exit();
}
