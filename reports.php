<?php
session_start();
include "../includes/db.php";

$type = $_GET['type'] ?? 'profit';
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

$data = [];
$title = "";

// ---------- DATA QUERIES ----------
if($type=='sales'){
    $title="Sales Report";
    $sql="SELECT order_id, order_date, total_amount_payable
          FROM orders WHERE DATE(order_date) BETWEEN '$from' AND '$to'";
}
elseif($type=='returns'){
    $title="Return Report";
    $sql="SELECT r.return_id, o.order_id, p.product_name,
          r.late_days, r.late_fee, r.damage_status, r.damage_fee
          FROM returns r
          JOIN orders o ON r.order_id=o.order_id
          JOIN products p ON r.product_id=p.product_id
          WHERE DATE(r.return_date) BETWEEN '$from' AND '$to'";
}
elseif($type=='payments'){
    $title="Payment Report";
    $sql="SELECT payment_id, transaction_date, amount,
          payment_type, payment_method, payment_status
          FROM payment WHERE DATE(transaction_date) BETWEEN '$from' AND '$to'";
}
elseif($type=='rental'){
    $title="Rental Report";
    $sql="SELECT o.order_id,u.full_name,p.product_name,
          oi.rental_days,oi.total_price
          FROM order_items oi
          JOIN orders o ON oi.order_id=o.order_id
          JOIN products p ON oi.product_id=p.product_id
          JOIN users u ON o.user_id=u.user_id
          WHERE DATE(o.order_date) BETWEEN '$from' AND '$to'";
}
elseif($type=='profit'){
    $title="Profit vs Loss Report";

    $profit = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(amount),0) c FROM payment
         WHERE payment_status='success' AND DATE(transaction_date) BETWEEN '$from' AND '$to'"
    ))['c'];

    $loss_late = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(late_fee),0) c FROM returns
         WHERE DATE(return_date) BETWEEN '$from' AND '$to'"
    ))['c'];

    $loss_damage = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(damage_fee),0) c FROM returns
         WHERE DATE(return_date) BETWEEN '$from' AND '$to'"
    ))['c'];

    $refund = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(amount),0) c FROM payment
         WHERE payment_type='refund' AND DATE(transaction_date) BETWEEN '$from' AND '$to'"
    ))['c'];

    $total_loss = $loss_late + $loss_damage + $refund;
    $net_profit = $profit - $total_loss;
}

// ---------- FETCH TABLE DATA ----------
if(isset($sql)){
    $res=mysqli_query($conn,$sql);
    while($row=mysqli_fetch_assoc($res)){ $data[]=$row; }
}

// ---------- CHART DATA ----------
$months=[]; $profits=[]; $losses=[]; $sales=[]; $returns=[];
for($m=1;$m<=12;$m++){
    $months[]=date("M",mktime(0,0,0,$m,1));

    // Profit chart
    $p=mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(amount),0)c FROM payment
         WHERE payment_status='success' AND MONTH(transaction_date)=$m"
    ))['c'];
    $l=mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(late_fee+damage_fee),0)c FROM returns
         WHERE MONTH(return_date)=$m"
    ))['c'];

    $profits[]=$p; $losses[]=$l;

    // Sales chart
    $s=mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT IFNULL(SUM(total_amount_payable),0)c FROM orders
         WHERE MONTH(order_date)=$m"))['c'];
    $sales[]=$s;

    // Returns chart
    $r=mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) c FROM returns WHERE MONTH(return_date)=$m"))['c'];
    $returns[]=$r;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Reports - Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body{background:#f4f5f7;}
.sidebar{width:250px;height:100vh;position:fixed;background:#1e1e2f;color:#fff;}
.sidebar a{display:block;padding:12px 20px;color:#ddd;text-decoration:none}
.sidebar a.active,.sidebar a:hover{background:#34344e;color:#fff}
.content{margin-left:260px;padding:20px}
.card:hover{box-shadow:0 6px 15px rgba(0,0,0,.2)}
</style>
</head>
<body>
<div class="sidebar">
<h4 class="text-center mb-4">Admin Panel</h4>
<a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="category.php"><i class="bi bi-list-task"></i> Categories</a>
<a href="subcategory.php"><i class="bi bi-diagram-2"></i> Subcategories</a>
<a href="type.php"><i class="bi bi-tag"></i> Types</a>
<a href="products.php"><i class="bi bi-bag"></i> Products</a>
<a href="admin_orders.php"><i class="bi bi-cart-check"></i> Orders</a>
<a href="payments.php"><i class="bi bi-credit-card"></i> Payments</a>
<a href="returns.php"><i class="bi bi-arrow-return-left"></i> Returns</a>
<a href="manage_feedback.php"><i class="bi bi-chat-dots"></i> Feedback</a>
<a href="users.php"><i class="bi bi-people"></i> Users</a>
<a href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
<a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>


<div class="content">
<h2><?= $title ?></h2>

<form class="row g-3 mb-3">
<div class="col-md-3">
<select name="type" class="form-select">
<option value="profit" <?= $type=='profit'?'selected':'' ?>>Profit vs Loss</option>
<option value="sales" <?= $type=='sales'?'selected':'' ?>>Sales</option>
<option value="returns" <?= $type=='returns'?'selected':'' ?>>Returns</option>
<option value="payments" <?= $type=='payments'?'selected':'' ?>>Payments</option>
<option value="rental" <?= $type=='rental'?'selected':'' ?>>Rental</option>
</select>
</div>
<div class="col-md-3"><input type="date" name="from" value="<?= $from ?>" class="form-control"></div>
<div class="col-md-3"><input type="date" name="to" value="<?= $to ?>" class="form-control"></div>
<div class="col-md-3"><button class="btn btn-primary w-100">Generate</button></div>
</form>

<a href="export_report_pdf.php?type=<?= $type ?>&from=<?= $from ?>&to=<?= $to ?>" class="btn btn-danger mb-3">
<i class="bi bi-file-earmark-pdf"></i> Export PDF
</a>

<?php if($type=='profit'): ?>
<div class="row g-3 mb-4">
<div class="col-md-3"><div class="card p-3 bg-success text-white">Profit ₹<?= number_format($profit,2) ?></div></div>
<div class="col-md-3"><div class="card p-3 bg-danger text-white">Loss ₹<?= number_format($total_loss,2) ?></div></div>
<div class="col-md-3"><div class="card p-3 bg-primary text-white">Net ₹<?= number_format($net_profit,2) ?></div></div>
</div>

<div class="card p-3 mb-4"><canvas id="plChart" height="120"></canvas></div>

<?php elseif($type=='sales'): ?>
<div class="card p-3 mb-4"><canvas id="salesChart" height="120"></canvas></div>
<?php elseif($type=='returns'): ?>
<div class="card p-3 mb-4"><canvas id="returnChart" height="120"></canvas></div>
<?php endif; ?>

<?php if($type!='profit'): ?>
<div class="card p-3 table-responsive">
<table class="table table-bordered">
<thead class="table-dark">
<tr>
<?php foreach(array_keys($data[0]??[]) as $h) echo "<th>".ucwords(str_replace('_',' ',$h))."</th>"; ?>
</tr>
</thead>
<tbody>
<?php foreach($data as $r): ?><tr>
<?php foreach($r as $v): ?><td><?= htmlspecialchars($v) ?></td><?php endforeach; ?>
</tr><?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>

<script>
<?php if($type=='profit'): ?>
new Chart(document.getElementById("plChart"),{
type:'bar',
data:{labels:<?= json_encode($months) ?>,
datasets:[
{label:'Profit',data:<?= json_encode($profits) ?>,backgroundColor:'green'},
{label:'Loss',data:<?= json_encode($losses) ?>,backgroundColor:'red'}
]}});

<?php elseif($type=='sales'): ?>
new Chart(document.getElementById("salesChart"),{
type:'line',
data:{labels:<?= json_encode($months) ?>,
datasets:[{label:'Sales Amount',data:<?= json_encode($sales) ?>,borderColor:'blue',fill:false}]}
});
<?php elseif($type=='returns'): ?>
new Chart(document.getElementById("returnChart"),{
type:'line',
data:{labels:<?= json_encode($months) ?>,
datasets:[{label:'Returns Count',data:<?= json_encode($returns) ?>,borderColor:'orange',fill:false}]}
});
<?php endif; ?>
</script>
</div>
</body>
</html>
