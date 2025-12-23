<?php
session_start();
include "../includes/db.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false,'message'=>'Not logged in']);
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Get cart_id from POST (or GET)
$data = json_decode(file_get_contents('php://input'), true);
$cart_id = intval($data['cart_id'] ?? 0);

if($cart_id <= 0){
    echo json_encode(['success'=>false,'message'=>'Invalid cart item']);
    exit();
}

// Ensure the cart item belongs to this user
$stmt = $conn->prepare("DELETE FROM cart WHERE cart_id=? AND user_id=?");
$stmt->bind_param("ii", $cart_id, $user_id);

if($stmt->execute()){
    echo json_encode(['success'=>true,'message'=>'Item removed']);
} else {
    echo json_encode(['success'=>false,'message'=>'Failed to remove item']);
}
