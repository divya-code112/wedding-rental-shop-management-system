<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['admin_id'])) exit;

$order_id = intval($_POST['order_id']);
$status = $_POST['order_status'];
$damage_type = $_POST['damage_type'];
$damage_fee = floatval($_POST['damage_fee']);

mysqli_query($conn, "UPDATE orders SET order_status='$status', damage_fee=$damage_fee WHERE order_id=$order_id");

header("Location: admin_manage_orders.php");
exit;
