<?php
session_start();
include "../includes/db.php";

$id = intval($_GET['order_id']);
$o = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM orders WHERE order_id=$id"));
?>
<!DOCTYPE html>
<html>
<head>
<title>Pay Remaining</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 col-md-6">
<div class="card shadow p-4">
<h4>Pay Remaining Amount</h4>

<p>Rent Remaining: ₹<?= $o['total_rent_amount'] - $o['advance_amount'] ?></p>
<p>Late Fee: ₹<?= $o['late_fee'] ?></p>
<p>Damage Fee: ₹<?= $o['damage_fee'] ?></p>
<hr>
<h5>Total Payable: ₹<?= $o['final_amount'] ?></h5>

<form method="post" action="confirm_remaining_payment.php">
<input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">
<input type="hidden" name="amount" value="<?= $o['final_amount'] ?>">
<input type="hidden" name="payment_type" value="upi">
<button class="btn btn-success w-100 mt-3">Pay Now</button>
</form>
</div>
</div>
</body>
</html>
