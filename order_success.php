<?php
session_start();
include "../includes/db.php";

if (!isset($_GET['order'])) {
    die("Order not found!");
}

$order_code = $_GET['order'];

// Fetch order
$order = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM orders WHERE order_code='$order_code' LIMIT 1"
));

if (!$order) { die("Invalid Order ID!"); }

// Fetch order items
$items = mysqli_query($conn, 
    "SELECT * FROM order_items WHERE order_id={$order['order_id']}"
);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f7f8fa; font-family: Poppins, sans-serif; }
        .card-soft { background: #fff; border-radius: 16px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,.05); }
        .success-icon { font-size: 80px; color:#28a745; }
        .order-box { background:#fafafa; padding:15px; border-radius:10px; border-left:4px solid #28a745; }
        .item-img { width: 70px; height: 70px; border-radius: 10px; object-fit: cover; }
    </style>
</head>

<body>
<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fs-4 fw-bold" href="collection.php">Royal Drapes</a>
    </div>
</nav>

<div class="container my-5">
    <div class="card-soft text-center">
        <div class="success-icon mb-2">✔</div>
        <h2 class="fw-bold">Order Placed Successfully!</h2>
        <p class="text-muted mb-1">Thank you for shopping with Royal Drapes.</p>

        <div class="order-box mt-4">
            <h5 class="mb-0">Order ID: <strong><?= $order_code ?></strong></h5>
            <small class="text-muted">Keep this for your reference</small>
        </div>

        <h4 class="mt-4 mb-3 text-start">Order Items</h4>
        
        <div class="text-start">
            <?php while($it = mysqli_fetch_assoc($items)) { ?>
                <div class="d-flex align-items-center mb-3 p-2 bg-white rounded shadow-sm">
                    <img src="../assets/<?= $it['image'] ?>" class="item-img me-3">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><?= $it['product_name'] ?></h6>
                        <small class="text-muted">Rental Days: <?= $it['rental_days'] ?></small><br>
                        <small>Rent: ₹<?= number_format($it['rent_price'],2) ?></small><br>
                        <span class="text-success">Deposit: ₹<?= number_format($it['deposit_amount'],2) ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>

        <hr>

        <h5 class="fw-bold">Delivery Details</h5>
        <p class="mb-0"><strong><?= $order['full_name'] ?></strong></p>
        <p class="mb-0"><?= $order['mobile'] ?></p>
        <p class="text-muted"><?= $order['address'] ?></p>

        <div class="d-flex justify-content-between mt-4 fw-bold fs-5">
            <div>Total Deposit Paid:</div>
            <div class="text-success">₹<?= number_format($order['total_deposit'],2) ?></div>
        </div>

        <div class="d-flex justify-content-between fw-bold fs-5">
            <div>Total Rent (Pay Later):</div>
            <div>₹<?= number_format($order['total_rent'],2) ?></div>
        </div>

        <div class="mt-4 d-flex gap-3 justify-content-center">
            <a href="tracking.php?order=<?= $order_code ?>" class="btn btn-dark px-4">Track Order</a>
            <a href="collection.php" class="btn btn-outline-primary px-4">Continue Shopping</a>
        </div>
    </div>
</div>

</body>
</html>
