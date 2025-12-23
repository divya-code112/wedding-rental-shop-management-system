<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['admin_id'])){
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit();
}

$order_id = intval($_POST['order_id'] ?? 0);
$new_status = $_POST['order_status'] ?? '';
$late_fee = floatval($_POST['late_fee'] ?? 0);
$damage_fee = floatval($_POST['damage_fee'] ?? 0);

$valid_statuses = ['pending','confirmed','processing','delivered','returned','cancelled'];
if(!$order_id || !in_array($new_status, $valid_statuses)){
    echo json_encode(['success'=>false,'message'=>'Invalid input']);
    exit();
}

$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id"));
if(!$order){
    echo json_encode(['success'=>false,'message'=>'Order not found']);
    exit();
}

$updates = [];
$updates[] = "order_status='$new_status'";

if($new_status === 'returned'){
    $updates[] = "late_fee=$late_fee";
    $updates[] = "damage_fee=$damage_fee";
    $total = $order['total_rent_amount'] + $late_fee + $damage_fee - $order['advance_amount'];
    $updates[] = "total_amount_payable=$total";
    $updates[] = "returned_at=NOW()";
}

if($new_status === 'delivered'){
    $updates[] = "delivered_at=NOW()";
}

if($new_status === 'returned' && isset($total) && $total <= 0){
    $updates[] = "final_payment_status='paid'";
}

$sql = "UPDATE orders SET ".implode(',', $updates)." WHERE order_id=$order_id";
if(mysqli_query($conn, $sql)){
    echo json_encode(['success'=>true,'message'=>'Order updated successfully']);
}else{
    echo json_encode(['success'=>false,'message'=>'Database error: '.mysqli_error($conn)]);
}
