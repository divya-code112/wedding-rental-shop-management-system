<?php
session_start();
header('Content-Type: application/json');
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false,'message'=>'Please login first']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Read JSON input
$input = json_decode(file_get_contents('php://input'), true);
if(!$input){
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

$product_id = intval($input['product_id'] ?? 0);
$rental_days = intval($input['rental_days'] ?? 1);
$quantity = intval($input['quantity'] ?? 1);
$delivery_date = $input['delivery_date'] ?? '';

if(!$product_id || !$delivery_date){
    echo json_encode(['success'=>false,'message'=>'Missing product or delivery date']);
    exit;
}

// Fetch product details
$prod_q = mysqli_query($conn, "SELECT * FROM products WHERE product_id=$product_id");
if(mysqli_num_rows($prod_q) === 0){
    echo json_encode(['success'=>false,'message'=>'Product not found']);
    exit;
}

$product = mysqli_fetch_assoc($prod_q);
$price = floatval($product['price_per_day']);
$deposit = floatval($product['deposit_amount']);
$max_days = intval($product['max_rental_days']);

// Calculate return date
$return_date = date('Y-m-d', strtotime($delivery_date. " + $max_days days"));

// Calculate item totals
$item_rent_total = $price * $rental_days * $quantity;
$item_deposit = $deposit * $quantity;

// Check if item already in cart for user
$check_q = mysqli_query($conn, "SELECT cart_id FROM cart WHERE user_id=$user_id AND product_id=$product_id");
if(mysqli_num_rows($check_q) > 0){
    // If exists, update quantity and rental_days
    $row = mysqli_fetch_assoc($check_q);
    $cart_id = intval($row['cart_id']);
    mysqli_query($conn, "UPDATE cart SET 
        rental_days=$rental_days, 
        quantity=quantity + $quantity,
        added_at=NOW(),
        item_rent_total=$item_rent_total,
        item_deposit=$item_deposit
        WHERE cart_id=$cart_id
    ");
}else{
    // Insert new
    mysqli_query($conn, "INSERT INTO cart
        (user_id, product_id, rental_days, quantity, added_at, item_rent_total, item_deposit)
        VALUES ($user_id, $product_id, $rental_days, $quantity, NOW(), $item_rent_total, $item_deposit)
    ");
}

// Return cart count
$cart_count_q = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM cart WHERE user_id=$user_id");
$cart_count = mysqli_fetch_assoc($cart_count_q)['cnt'];

echo json_encode(['success'=>true,'message'=>'Added to cart','cart_count'=>$cart_count]);
