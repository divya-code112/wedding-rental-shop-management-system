<?php
session_start();
include "../includes/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success'=>false,'message'=>'Not logged in']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if(!isset($input['cart_id'], $input['start_date'], $input['rental_days'])){
    echo json_encode(['success'=>false,'message'=>'Invalid input']);
    exit();
}

$cart_id = intval($input['cart_id']);
$start_date = mysqli_real_escape_string($conn, $input['start_date']);
$rental_days = max(1, intval($input['rental_days'])); // minimum 1 day

$user_id = intval($_SESSION['user_id']);

// Optional: Check if cart belongs to the user
$res = mysqli_query($conn, "SELECT * FROM cart WHERE cart_id=$cart_id AND user_id=$user_id");
if(mysqli_num_rows($res) == 0){
    echo json_encode(['success'=>false,'message'=>'Cart item not found']);
    exit();
}

// Update cart
$update_sql = "UPDATE cart SET start_date='$start_date', rental_days=$rental_days WHERE cart_id=$cart_id";
if(mysqli_query($conn, $update_sql)){
    echo json_encode(['success'=>true]);
} else {
    echo json_encode(['success'=>false,'message'=>'DB update failed']);
}
