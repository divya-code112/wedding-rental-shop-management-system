<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['order_id'] ?? 0);

// Fetch order
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id AND user_id=$user_id"));
if(!$order){
    die("Order not found.");
}

// Fetch order items to calculate return due if needed
$order_items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id=$order_id");

// Auto-calculate delivery_date and return_due_date if empty
if(empty($order['delivery_date'])){
    // use earliest start_date from cart or default to order_date
    $earliest_start = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MIN(start_date) as start_date FROM cart WHERE user_id=$user_id"))['start_date'];
    $order['delivery_date'] = $earliest_start ?? date('Y-m-d', strtotime($order['order_date']));
}

if(empty($order['return_due_date'])){
    $max_days = 0;
    while($item = mysqli_fetch_assoc($order_items)){
        if($item['rental_days'] > $max_days) $max_days = $item['rental_days'];
    }
    $order['return_due_date'] = date('Y-m-d', strtotime("+$max_days days", strtotime($order['delivery_date'])));
}

// Define steps for timeline
$steps = [
    'pending' => ['title'=>'Order Placed', 'icon'=>'fa-cart-shopping'],
    'confirmed' => ['title'=>'Order Confirmed', 'icon'=>'fa-check-circle'],
    'processing' => ['title'=>'Preparing for Delivery', 'icon'=>'fa-box-open'],
    'delivered' => ['title'=>'Delivered', 'icon'=>'fa-truck'],
    'returned' => ['title'=>'Returned', 'icon'=>'fa-undo'],
    'cancelled' => ['title'=>'Cancelled', 'icon'=>'fa-times-circle']
];

$status_order = array_keys($steps);
$current_index = array_search($order['order_status'], $status_order);
$total_steps = count($status_order);
$progress_percent = ($current_index / ($total_steps - 1)) * 100;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Order #<?= $order['order_id'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body { font-family:'Poppins', sans-serif; background:#f4f6f9; }
.container { max-width:900px; margin:30px auto; }
.tracker { display:flex; justify-content:space-between; position:relative; margin-top:50px; flex-wrap:wrap; }
.progress-bar-bg { position:absolute; top:25px; left:50px; right:50px; height:4px; background:#ccc; border-radius:2px; z-index:0; }
.progress-bar-fill { position:absolute; top:25px; left:50px; height:4px; background:#198754; border-radius:2px; width:0%; z-index:1; transition:width 1s ease; }
.tracker-step { position:relative; text-align:center; flex:1; min-width:120px; margin-bottom:50px; }
.step-circle { width:50px; height:50px; border-radius:50%; background:#ccc; display:flex; justify-content:center; align-items:center; color:white; font-weight:bold; font-size:18px; margin:0 auto 10px; z-index:2; position:relative; transform:scale(0); transition:transform 0.3s; }
.completed .step-circle { background:#198754; }
.current .step-circle { background:#ffc107; color:#000; animation:pulse 1s infinite; }
.step-content { background:white; padding:10px 15px; border-radius:8px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
.step-title { font-weight:600; font-size:16px; }
.step-date { font-size:13px; color:#555; margin-top:2px; }
@keyframes pulse { 0% { transform:scale(1); box-shadow:0 0 0 rgba(255,193,7,0.7); } 50% { transform:scale(1.1); box-shadow:0 0 15px rgba(255,193,7,0.7); } 100% { transform:scale(1); box-shadow:0 0 0 rgba(255,193,7,0.7); } }
body { font-family: 'Poppins', sans-serif; background:#f4f4f4; color:#111; }
.container { max-width:900px; margin:30px auto; }
.card { border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); margin-bottom:20px; }
.card-header { font-weight:700; display:flex; justify-content:space-between; align-items:center; }
.btn-cancel:disabled { opacity:0.6; cursor:not-allowed; }
.order-info { font-size:14px; margin-top:6px; color:#555; }
.countdown { font-size:13px; color:#dc3545; margin-left:10px; }
.btn-pay { background:#ffc107; border:none; border-radius:8px; padding:8px 15px; color:#111; font-weight:600; }
.btn-pay:hover { background:#e0a800; color:#000; }

:root{
    --black:#0D0D0D;
    --dark:#1A1A1A;
    --gold:#D4AF37;
    --gold-soft:#EBD295;
    --white:#FFFFFF;
    --gray:#7E7E7E;
    --bg:#F4F4F4;
    --shadow:rgba(0,0,0,0.15);
}

*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--dark);} 

/* NAVBAR */
header{
    background:var(--black);
    padding:16px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    position:sticky;top:0;z-index:999;
    box-shadow:0 4px 15px var(--shadow);
    backdrop-filter: blur(10px);
}
.logo-area{display:flex;align-items:center;gap:12px;}
.logo-area img{height:48px;width:48px;border-radius:10px;}
.logo-title{font-size:20px;font-weight:700;}
.tagline{font-size:12px;color:var(--gold-soft);} 
nav.nav-right { margin-left:auto; display:flex; align-items:center; gap:25px; }
nav.nav-right a { color:white; text-decoration:none; font-weight:600; font-size:15px; display:flex; align-items:center; gap:6px; transition:.2s; }
nav.nav-right a:hover, nav.nav-right a.active { color:var(--gold); }
.hamburger { display:none; flex-direction:column; gap:4px; cursor:pointer; }
.hamburger div { width:25px; height:3px; background:white; transition:0.3s; }
@media (max-width:900px){
    nav.nav-right { position:fixed; top:0; right:-100%; height:100vh; width:250px; background:var(--black); flex-direction:column; padding-top:80px; transition:0.3s; }
    nav.nav-right.show { right:0; }
    .hamburger { display:flex; }
}
.cart-badge { background: var(--gold); color: var(--black); font-size:12px; font-weight:bold; padding:2px 6px; border-radius:50%; position:absolute; top:-5px; right:-10px; animation:pop 0.3s ease; }
@keyframes pop {0% {transform:scale(0);} 50%{transform:scale(1.3);} 100%{transform:scale(1);}}
</style>
</head>
<body>

<!-- NAVBAR -->
<header>
    <div class="logo-area">
        <img src="../assets/logo.jpeg" alt="Royal Drapes">
        <div>
            <div class="logo-title">Royal Drapes</div>
            <div class="tagline">Where Royalty Meets Rent</div>
        </div>
    </div>
    <div class="hamburger" onclick="toggleMenu()">
        <div></div><div></div><div></div>
    </div>
    <nav class="nav-right">
        <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Home</a>
        <a href="collection.php"><i class="fa-solid fa-shirt"></i> Collection</a>
        <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
        <a href="order_details.php"><i class="fa-solid fa-receipt"></i> My Orders</a>
        <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
    </nav>
</header>

<div class="container">
    <h2 class="mb-4">📦 Track Order #<?= $order['order_id'] ?></h2>
    <p><strong>Order Date:</strong> <?= $order['order_date'] ?></p>
    <p><strong>Delivery Date:</strong> <?= $order['delivery_date'] ?></p>
    <p><strong>Return Due:</strong> <?= $order['return_due_date'] ?></p>

    <div class="tracker">
        <div class="progress-bar-bg"></div>
        <div class="progress-bar-fill" id="progressBar"></div>

        <?php foreach($status_order as $index => $status):
            $class = $index < $current_index ? 'completed' : ($index == $current_index ? 'current' : '');
        ?>
        <div class="tracker-step <?= $class ?>">
            <div class="step-circle"><i class="fa <?= $steps[$status]['icon'] ?>"></i></div>
            <div class="step-content">
                <div class="step-title"><?= $steps[$status]['title'] ?></div>
                <?php if($status=='delivered'): ?>
                    <div class="step-date">Delivery Date: <?= $order['delivery_date'] ?></div>
                <?php elseif($status=='returned'): ?>
                    <div class="step-date">Return Due: <?= $order['return_due_date'] ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const progressBar = document.getElementById('progressBar');
    const steps = document.querySelectorAll('.tracker-step');
    const progressPercent = <?= $progress_percent ?>;

    setTimeout(() => { progressBar.style.width = progressPercent + '%'; }, 200);
    steps.forEach((step, index) => {
        setTimeout(() => { step.querySelector('.step-circle').style.transform='scale(1)'; }, 400 + index*300);
    });
});
</script>

</body>
</html>
