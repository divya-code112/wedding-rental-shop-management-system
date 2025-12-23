<?php
session_start();
include "../includes/db.php";

// Admin check

// COUNTS
$total_users     = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM users"))['c'];
$total_products  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products"))['c'];
$total_orders    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM orders"))['c'];
$total_returns   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM returns"))['c'];

// PRODUCT STATUS
$available_products = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE stock_status='available'"))['c'];
$rented_products    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE stock_status='rented'"))['c'];
$damaged_products   = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE stock_status='damaged'"))['c'];
$repair_products    = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE stock_status='repair'"))['c'];

// RETURN DAMAGE
$return_minor  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM returns WHERE damage_status='minor'"))['c'];
$return_major  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM returns WHERE damage_status='major'"))['c'];
$return_repair = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM returns WHERE damage_status='repair'"))['c'];

// MONTHLY GRAPH DATA
$monthly_orders = $monthly_returns = $monthly_products = [];
for($m=1;$m<=12;$m++){
    $o = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM orders WHERE MONTH(order_date)=$m"))['c'];
    $r = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM returns WHERE MONTH(return_date)=$m"))['c'];
    $p = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM products WHERE MONTH(created_at)=$m"))['c'];
    array_push($monthly_orders,$o); 
    array_push($monthly_returns,$r); 
    array_push($monthly_products,$p);
}

// TOP PRODUCTS
$top_products = [];
$res = mysqli_query($conn,"SELECT p.product_name, COUNT(oi.item_id) AS times_rented 
                           FROM order_items oi
                           JOIN products p ON oi.product_id = p.product_id
                           GROUP BY p.product_id ORDER BY times_rented DESC LIMIT 5");
while($row=mysqli_fetch_assoc($res)) $top_products[] = $row;
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{background:#f4f5f7;}
.sidebar {width:250px; height:100vh; position:fixed; top:0; left:0; background:#1e1e2f; color:#fff; padding-top:20px;}
.sidebar a {display:block; padding:12px 20px; color:#ddd; text-decoration:none; font-size:15px; font-weight:500; transition:0.2s;}
.sidebar a:hover, .sidebar a.active{background:#34344e;color:#fff;}
.content {margin-left:260px; padding:20px;}
.card:hover {box-shadow:0 5px 15px rgba(0,0,0,0.2);transition:0.3s;}
.summary-card {border-left:5px solid #0d6efd;}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h4 class="text-center mb-4">Admin Panel</h4>
<a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="category.php"><i class="bi bi-list-task"></i> Categories</a>
<a href="subcategory.php"><i class="bi bi-diagram-2"></i> Subcategories</a>
<a href="type.php"><i class="bi bi-tag"></i> Types</a>
<a href="products.php"><i class="bi bi-bag"></i> Products</a>
<a href="admin_manage_orders.php"><i class="bi bi-cart-check"></i> Orders</a>
<a href="payments.php"><i class="bi bi-credit-card"></i> Payments</a>

<a href="manage_feedback.php"><i class="bi bi-chat-dots"></i> Feedback</a>
<a href="admin_messages.php"><<i class="bi bi-envelope-fill"></i> Contact</a>
<a href="users.php"><i class="bi bi-people"></i> Users</a>

<a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<!-- CONTENT -->
<div class="content">
<h2 class="mb-4">Admin Dashboard</h2>

<!-- SUMMARY CARDS -->
<div class="row g-3">
<div class="col-md-3"><div class="card p-3 summary-card border-primary"><h5>Users</h5><h3><?= $total_users ?></h3></div></div>
<div class="col-md-3"><div class="card p-3 summary-card border-success"><h5>Products</h5><h3><?= $total_products ?></h3></div></div>
<div class="col-md-3"><div class="card p-3 summary-card border-warning"><h5>Orders</h5><h3><?= $total_orders ?></h3></div></div>
<div class="col-md-3"><div class="card p-3 summary-card border-danger"><h5>Returns</h5><h3><?= $total_returns ?></h3></div></div>
</div>

<h4 class="mt-5">Product Status</h4>
<div class="row g-3">
<div class="col-md-3"><div class="card p-3 bg-light">Available: <?= $available_products ?></div></div>
<div class="col-md-3"><div class="card p-3 bg-warning">Rented: <?= $rented_products ?></div></div>
<div class="col-md-3"><div class="card p-3 bg-danger text-white">Damaged: <?= $damaged_products ?></div></div>
<div class="col-md-3"><div class="card p-3 bg-info text-white">Repair: <?= $repair_products ?></div></div>
</div>

<h4 class="mt-5">Return Damage</h4>
<div class="row g-3">
<div class="col-md-4"><div class="card p-3 bg-warning">Minor: <?= $return_minor ?></div></div>
<div class="col-md-4"><div class="card p-3 bg-danger text-white">Major: <?= $return_major ?></div></div>
<div class="col-md-4"><div class="card p-3 bg-info text-white">Repair: <?= $return_repair ?></div></div>
</div>

<h4 class="mt-5">Monthly Overview</h4>
<div class="card p-3 mb-4"><canvas id="mainChart" height="120"></canvas></div>

<h4 class="mt-5">Top Products</h4>
<div class="card p-3 mb-4"><canvas id="topProductsChart" height="120"></canvas></div>

</div>

<script>
const ctx=document.getElementById('mainChart').getContext('2d');
new Chart(ctx,{type:'line',data:{
labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
datasets:[
{label:"Orders",data:<?= json_encode($monthly_orders) ?>,borderWidth:2,tension:0.3,borderColor:"blue"},
{label:"Returns",data:<?= json_encode($monthly_returns) ?>,borderWidth:2,tension:0.3,borderColor:"red"},
{label:"New Products",data:<?= json_encode($monthly_products) ?>,borderWidth:2,tension:0.3,borderColor:"green"}
]}});

const topProductsCtx=document.getElementById('topProductsChart').getContext('2d');
new Chart(topProductsCtx,{
type:'bar',
data:{
labels:<?= json_encode(array_column($top_products,'product_name')) ?>,
datasets:[{label:"Times Rented",data:<?= json_encode(array_column($top_products,'times_rented')) ?>,backgroundColor:'rgba(54,162,235,0.7)'}]
},
options:{responsive:true,plugins:{legend:{display:true}}}
});
</script>
</body>
</html>
