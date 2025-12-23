<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success'=>false,'message'=>'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$cart_id = intval($data['cart_id']);
$start_date = mysqli_real_escape_string($conn, $data['start_date']);
$rental_days = intval($data['rental_days']);

mysqli_query($conn,"UPDATE cart SET start_date='$start_date', rental_days=$rental_days WHERE cart_id=$cart_id AND user_id=".intval($_SESSION['user_id']));

echo json_encode(['success'=>true]);
