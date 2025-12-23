<?php
include "../includes/db.php";

$order_id = intval($_GET['order_id']);

// Fetch order
$order = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM orders WHERE order_id = $order_id
"));

if(!$order){
    die("Invalid Order");
}

/* ===== AUTO CALCULATIONS ===== */

$return_due = new DateTime($order['return_due_date']);
$actual_return = new DateTime(); // admin returning today
$final_rent_due = $order['total_rent_amount'] - $order['total_deposit'];

// Late days
$late_days = max(0, $return_due->diff($actual_return)->days);
$late_fee = $late_days * ($final_rent_due * 0.10);

// Damage fee (default none)
$damage_fee = 0;

// Remaining payable
$remaining_amount = $final_rent_due + $late_fee;

// Refund
$refund = max(0, $order['total_deposit'] - ($late_fee + $damage_fee));

/* ===== SAVE ===== */
mysqli_query($conn,"
UPDATE orders SET
    order_status='returned',
    returned_at = NOW(),
    late_fee = $late_fee,
    refund_amount = $refund,
    final_amount = $remaining_amount
WHERE order_id=$order_id
");

?>

<!DOCTYPE html>
<html>
<head>
<title>Return Processing</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card{border-radius:16px;}
</style>
</head>
<body class="bg-light">

<div class="container my-5">
<div class="card shadow">
<div class="card-body">

<h4>📦 Return Summary — Order #<?= $order_id ?></h4>
<hr>

<p><strong>Final Rent Due:</strong> ₹<?= number_format($final_rent_due,2) ?></p>
<p><strong>Late Days:</strong> <?= $late_days ?></p>
<p><strong>Late Fee:</strong> ₹<?= number_format($late_fee,2) ?></p>

<form method="POST" action="save_damage.php">
<input type="hidden" name="order_id" value="<?= $order_id ?>">

<label class="mt-3">Damage Type</label>
<select name="damage_type" class="form-select mb-3">
    <option value="none">No Damage</option>
    <option value="minor">Minor Damage (10%)</option>
    <option value="major">Major Damage (30%)</option>
</select>

<button class="btn btn-dark w-100">
    Save & Finalize
</button>
</form>

</div>
</div>
</div>

</body>
</html>
