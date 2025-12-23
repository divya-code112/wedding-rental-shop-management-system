<?php
// public/order_view.php
session_start();
include "../includes/db.php";
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['order_id'] ?? 0);
if(!$order_id){ echo "Invalid order ID"; exit(); }

// Fetch order
$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id AND user_id=$user_id LIMIT 1");
$order = mysqli_fetch_assoc($order_res);
if(!$order){ echo "Order not found"; exit(); }

// Fetch order items
$items_res = mysqli_query($conn, "SELECT oi.*, p.product_name, p.image 
                                 FROM order_items oi 
                                 JOIN products p ON oi.product_id=p.product_id 
                                 WHERE oi.order_id=$order_id");

// Time difference to disable cancel button after 5 hours
$order_time = strtotime($order['order_date']);
$now = time();
$can_cancel = ($now - $order_time <= 5*3600) && ($order['order_status'] !== 'cancelled');
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Order #<?= $order_id ?> — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f4f4f4;color:#111;}
.container{max-width:900px;margin:30px auto;}
.order-header{background:#111;color:#fff;padding:15px;border-radius:8px;margin-bottom:20px;}
.order-item{background:white;padding:12px;border-radius:8px;margin-bottom:12px;display:flex;gap:12px;align-items:center;box-shadow:0 2px 8px rgba(0,0,0,0.05);}
.order-item img{width:100px;height:80px;object-fit:cover;border-radius:6px;}
.item-details div{margin-bottom:4px;}
.status-badge{padding:5px 10px;border-radius:6px;color:white;font-weight:600;}
.status-pending{background:#f0ad4e;}
.status-confirmed{background:#5bc0de;}
.status-processing{background:#0275d8;}
.status-delivered{background:#5cb85c;}
.status-returned{background:#292b2c;}
.status-cancelled{background:#d9534f;}
.btn-cancel:disabled{opacity:0.6;cursor:not-allowed;}
:root{--bg:#f6f7fb;--card:#ffffff;--accent:#111827;--muted:#6b7280;--primary:#111;}
body{font-family:'Poppins',system-ui,Segoe UI,Roboto,Arial; background:var(--bg); color:#111; margin:0;}
.container-main{max-width:1200px;margin:28px auto;padding:12px;}
.layout{display:grid;grid-template-columns:300px 1fr;gap:18px;align-items:start;}
@media(max-width:992px){ .layout{grid-template-columns:1fr; padding:0 12px;} .sidebar{order:2;} .products-area{order:1;}}
.sidebar{background:var(--card);padding:18px;border-radius:12px;box-shadow:0 6px 20px rgba(12,13,14,0.06);}
.filter-title{font-weight:600;margin-bottom:12px;}
.filter-group{margin-bottom:16px;}
.filter-group label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px;}
.filter-group select, .filter-group input{width:100%;padding:8px;border-radius:8px;border:1px solid #e6e7eb;font-size:14px;}
.products-area{background:transparent;}
.grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;}
@media(max-width:1200px){ .grid{grid-template-columns:repeat(3,1fr);} }
@media(max-width:900px){ .grid{grid-template-columns:repeat(2,1fr);} }
@media(max-width:600px){ .grid{grid-template-columns:repeat(1,1fr);} }
.card{background:var(--card);border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(12,13,14,0.06);transition:transform .18s ease, box-shadow .18s ease;display:flex;flex-direction:column;}
.card:hover{transform:translateY(-6px);box-shadow:0 12px 34px rgba(12,13,14,0.1);}
.card-media{width:100%;height:240px;background:#f3f4f6;display:block;overflow:hidden;position:relative;}
.card-media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .35s ease;}
.card:hover .card-media img{ transform: scale(1.05); filter:brightness(.92);}
.card-body{padding:14px 14px 18px;flex:1;display:flex;flex-direction:column;}
.title{font-size:15px;font-weight:600;line-height:1.2;height:40px;overflow:hidden;color:var(--accent);}
.meta{font-size:13px;color:var(--muted);margin-top:6px;}
.price-row{display:flex;justify-content:space-between;align-items:center;margin-top:8px;}
.price{font-weight:700;color:var(--primary);font-size:16px;}
.deposit{font-size:13px;color:var(--muted);}
.actions{margin-top:12px;display:flex;gap:8px;}
.btn-primary{background:var(--primary);color:#fff;border:0;padding:8px 12px;border-radius:10px;font-weight:600;}
.btn-outline{background:#fff;border:1px solid #e6e7eb;padding:8px 12px;border-radius:10px;color:var(--primary);}
.small{font-size:12px;color:var(--muted);margin-top:8px;}
.pagination{display:flex;gap:8px;justify-content:center;margin:22px 0;}
.page-link{padding:8px 12px;background:#fff;border-radius:8px;border:1px solid #e6e7eb;color:#333;text-decoration:none;}
.page-link.active{background:var(--primary);color:#fff;}
.text-center{text-align:center;}
.empty-state{background:#fff;padding:40px;border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,.05);}
.footer{background:#111;color:#fff;padding:25px;text-align:center;margin-top:40px;}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 mb-4">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="../assets/logo.jpeg" height="40" class="me-2"> Royal Drapes
  </a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
      <li class="nav-item"><a class="nav-link" href="collection.php"><i class="fa-solid fa-shirt"></i> Collection</a></li>
      <li class="nav-item"><a class="nav-link" href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
      <li class="nav-item"><a class="nav-link" href="order_details.php"><i class="fa-solid fa-receipt"></i> Orders</a></li>
      <li class="nav-item"><a class="nav-link" href="payment_history.php"><i class="fa-solid fa-wallet"></i> Payment</a></li>
      <li class="nav-item"><a class="nav-link" href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
    </ul>
  </div>
</nav>

<div class="container">
  <div class="order-header">
    <h4>Order #<?= $order_id ?></h4>
    <div>
      Status: 
      <?php 
      $status_class = "status-".$order['order_status'];
      ?>
      <span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($order['order_status']) ?></span>
    </div>
    <div>Order Date: <?= htmlspecialchars($order['order_date']) ?></div>
    <div>Return Due: <?= htmlspecialchars($order['return_due_date']) ?></div>
  </div>

  <h5>Items</h5>
  <?php while($item=mysqli_fetch_assoc($items_res)): ?>
  <div class="order-item">
    <img src="../assets/<?= htmlspecialchars($item['image'] ?? '') ?>" alt="<?= htmlspecialchars($item['product_name'] ?? '') ?>">
    <div class="item-details flex-fill">
      <div><strong><?= htmlspecialchars($item['product_name'] ?? '-') ?></strong></div>
      <div>Size: <?= htmlspecialchars($item['size'] ?? '-') ?> | Qty: <?= intval($item['quantity'] ?? 1) ?> | Days: <?= intval($item['rental_days'] ?? 1) ?></div>
      <div>Price/day: ₹<?= number_format($item['price_per_day'] ?? 0,2) ?> | Subtotal: ₹<?= number_format($item['total_price'] ?? 0,2) ?> | Deposit: ₹<?= number_format($item['deposit_amount'] ?? 0,2) ?></div>
    </div>
  </div>
  <?php endwhile; ?>

  <div class="card p-3 mb-3">
    <h5>Payment Summary</h5>
    <div class="d-flex justify-content-between"><div>Total Rent:</div><div>₹<?= number_format($order['total_rent_amount'] ?? 0,2) ?></div></div>
    <div class="d-flex justify-content-between"><div>Total Deposit Paid:</div><div>₹<?= number_format($order['total_deposit'] ?? 0,2) ?></div></div>
    <?php 
      $remaining = max(0,floatval($order['total_rent_amount']) - floatval($order['total_deposit']));
    ?>
    <div class="d-flex justify-content-between"><div>Remaining Amount:</div><div>₹<?= number_format($remaining,2) ?></div></div>
    <div class="d-flex justify-content-between"><div>Total Payable:</div><div>₹<?= number_format($order['total_amount_payable'] ?? 0,2) ?></div></div>
  </div>

  <div class="d-flex gap-2 mb-5">
    <button class="btn btn-danger btn-cancel" data-id="<?= $order_id ?>" <?= $can_cancel ? '' : 'disabled' ?>>Cancel Order</button>
    <a href="track_order.php?order_id=<?= $order_id ?>" class="btn btn-primary">Track Order</a>
  </div>
</div>

<script>
document.querySelectorAll('.btn-cancel').forEach(btn=>{
  btn.addEventListener('click', async function(){
    if(!confirm('Cancel this order?')) return;
    const order_id = this.getAttribute('data-id');
    const resp = await fetch('cancel_order.php',{
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'order_id='+encodeURIComponent(order_id)
    });
    const data = await resp.json();
    alert(data.message);
    if(data.success) location.reload();
  });
});
</script>
</body>
</html>
