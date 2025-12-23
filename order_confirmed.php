<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['order_id'] ?? 0);

$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id AND user_id=$user_id LIMIT 1");
$order = mysqli_fetch_assoc($order_res);

if(!$order){
    die("Order not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Confirmed — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<style>
body{background:#f4f4f4;font-family:'Poppins',sans-serif;text-align:center;padding-top:100px;}
.card{max-width:500px;margin:auto;background:white;padding:30px;border-radius:20px;box-shadow:0 8px 30px rgba(0,0,0,0.15);}
h1{color:#28a745;font-weight:700;margin-bottom:20px;}
p{font-size:18px;margin-bottom:10px;}
.btn{border-radius:10px;padding:10px 25px;font-weight:600;}
</style>
</head>
<body>

<div class="card">
    <h1>🎉 Payment Successful!</h1>
    <p>Order #<strong><?= $order_id ?></strong> has been confirmed.</p>
    <p>Paid Amount: <strong>₹<?= number_format($order['paid_amount'],2) ?></strong></p>
    <p>Payment Method: <strong><?= strtoupper($order['payment_method']) ?></strong></p>
    <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
    <a href="order_details.php?order_id=<?= $order_id ?>" class="btn btn-warning mt-3">View Orders</a>
</div>

<script>
// Confetti animation
confetti({
    particleCount: 200,
    spread: 70,
    origin: { y: 0.6 }
});
</script>
</body>
</html>
