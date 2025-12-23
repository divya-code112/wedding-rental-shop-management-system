<?php
// public/order_details.php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Fetch user orders
$q = mysqli_query($conn, "SELECT * FROM orders WHERE user_id=$user_id ORDER BY order_date DESC");
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Orders — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
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
  <h2 class="mb-4">My Orders</h2>

  <?php while($o=mysqli_fetch_assoc($q)):
        $order_time = strtotime($o['order_date']);
        $now = time();
        $cancelable_seconds = max(0, 5*3600 - ($now - $order_time)); // 5 hours window
        $cancelable = $cancelable_seconds > 0 && $o['order_status']!=='cancelled';
  ?>
  <div class="card p-3">
    <div class="card-header">
      <span>Order #<?= $o['order_id'] ?></span>
      <span class="badge bg-<?= ($o['order_status']=='cancelled')?'danger':'primary' ?>"><?= htmlspecialchars(ucfirst($o['order_status'])) ?></span>
    </div>
    <div class="order-info mt-2">
      <div><strong>Order Date:</strong> <?= htmlspecialchars($o['order_date']) ?></div>
      <div><strong>Return Due:</strong> <?= htmlspecialchars($o['return_due_date']) ?></div>
      <div><strong>Total Payable:</strong> ₹<?= number_format($o['total_amount_payable'],2) ?></div>
      <div><strong>Payment Status:</strong> <?= ucfirst($o['payment_status']) ?></div>
      <?php if($o['payment_status']=='paid'): ?>
        <div><strong>Paid Amount:</strong> ₹<?= number_format($o['paid_amount'],2) ?></div>
        <div><strong>Payment Method:</strong> <?= ucfirst($o['payment_method']) ?></div>
      <?php endif; ?>
      <?php if($cancelable): ?>
        <div class="countdown" data-seconds="<?= $cancelable_seconds ?>" id="countdown-<?= $o['order_id'] ?>"></div>
      <?php endif; ?>
    </div>
    <div class="mt-3 d-flex gap-2 flex-wrap">
      <?php if($o['order_status']!=='cancelled'): ?>
        <button class="btn btn-outline-danger btn-cancel" data-id="<?= $o['order_id'] ?>" <?= !$cancelable?'disabled':'' ?>>Cancel Order</button>
      <?php endif; ?>

      <a href="track_order.php?order_id=<?= $o['order_id'] ?>" class="btn btn-outline-primary">Track Order</a>
      <a href="order_view.php?order_id=<?= $o['order_id'] ?>" class="btn btn-outline-secondary">View Details</a>

      <!-- Pay Deposit if pending -->
      <?php if($o['payment_status']=='pending'): ?>
        <a href="payment.php?order_id=<?= $o['order_id'] ?>" class="btn btn-pay">Pay Deposit</a>
      <?php endif; ?>
      

      <!-- Download Bill button, only for delivered/returned -->
      <a href="return_invoice.php?order_id=<?= $o['order_id'] ?>" 
         class="btn btn-outline-success download-bill"
         data-status="<?= $o['order_status'] ?>">Download Bill</a>
    </div>
  </div>
  <?php endwhile; ?>
</div>

<script>
// Hamburger toggle
function toggleMenu(){
    document.querySelector('nav.nav-right').classList.toggle('show');
}

// Cancel order
document.querySelectorAll('.btn-cancel').forEach(btn=>{
  btn.addEventListener('click', async function(){
    if(!confirm('Cancel this order?')) return;
    const id = this.getAttribute('data-id');
    const resp = await fetch('cancel_order.php', {
      method:'POST',
      headers:{'Content-Type':'application/x-www-form-urlencoded'},
      body:'order_id='+encodeURIComponent(id)
    });
    const data = await resp.json();
    alert(data.message);
    if(data.success) location.reload();
  });
});

// Countdown timer
function startCountdown(el){
  let seconds = parseInt(el.dataset.seconds);
  function updateTimer(){
    if(seconds <= 0){
      el.textContent = 'Cancel window expired';
      const btn = document.querySelector('.btn-cancel[data-id="'+el.id.split('-')[1]+'"]');
      if(btn) btn.disabled = true;
      clearInterval(interval);
      return;
    }
    const h = Math.floor(seconds/3600);
    const m = Math.floor((seconds%3600)/60);
    const s = seconds%60;
    el.textContent = `Cancel in ${h}h ${m}m ${s}s`;
    seconds--;
  }
  updateTimer();
  const interval = setInterval(updateTimer, 1000);
}
document.querySelectorAll('.countdown').forEach(el=>startCountdown(el));

// Disable Download Bill if not delivered/returned
document.querySelectorAll('.download-bill').forEach(btn=>{
  btn.addEventListener('click', function(e){
    const status = this.dataset.status;
    if(status !== 'delivered' && status !== 'returned'){
        e.preventDefault();
        alert('Invoice not available until admin marks order delivered.');
    }
  });
});
</script>

</body>
</html>
