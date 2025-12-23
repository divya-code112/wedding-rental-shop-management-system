<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$product_id = intval($data['product_id']);
$quantity = intval($data['quantity']) ?: 1;
$user_id = intval($_SESSION['user_id']);

// Check if already in cart
$res = mysqli_query($conn,"SELECT cart_id FROM cart WHERE user_id=$user_id AND product_id=$product_id");
if(mysqli_num_rows($res)){
    mysqli_query($conn,"UPDATE cart SET quantity=quantity+$quantity WHERE user_id=$user_id AND product_id=$product_id");
}else{
    mysqli_query($conn,"INSERT INTO cart(user_id, product_id, quantity, rental_days) VALUES($user_id,$product_id,$quantity,1)");
}

// Return updated cart count
$res2 = mysqli_query($conn,"SELECT COUNT(*) AS cnt FROM cart WHERE user_id=$user_id");
$cart_count = mysqli_fetch_assoc($res2)['cnt'];

echo json_encode(['success'=>true, 'cart_count'=>$cart_count]);
