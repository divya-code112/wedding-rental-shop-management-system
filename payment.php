<?php
session_start();
include "../includes/db.php";

if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['order_id'] ?? 0);

if(!$order_id) die("Invalid order");

// Fetch order
$order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM orders WHERE order_id=$order_id AND user_id=$user_id LIMIT 1"));
if(!$order) die("Invalid order");

$amount_to_pay = floatval($order['total_deposit']); // deposit
$payment_label = "Pay Deposit Amount";
$upi_id = "royaldrapes@upi";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{background:#f4f4f4;font-family:'Poppins',sans-serif;}
.payment-box{max-width:700px;margin:50px auto;background:#fff;padding:30px;border-radius:14px;box-shadow:0 6px 25px rgba(0,0,0,0.12);}
.btn-method{width:140px;}
.payment-fields{margin-top:20px;padding:18px;border:1px solid #ddd;border-radius:12px;background:#fafafa;}
.qr-box{width:220px;height:220px;border:2px dashed #ccc;display:flex;align-items:center;justify-content:center;border-radius:10px;margin-bottom:15px;font-size:14px;color:#777;}
.pay-btn{background:#ffc107;border:none;border-radius:8px;padding:12px;width:100%;font-weight:600;}
.pay-btn:hover{background:#e0a800;color:#000;}
.modal-backdrop{opacity:0.5 !important;}
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


<div class="payment-box">
    <h3 class="mb-3"><?= $payment_label ?> for Order #<?= $order_id ?></h3>
    <p>Deposit Amount: <strong>₹<?= number_format($amount_to_pay,2) ?></strong></p>
    <p>Delivery Date: <strong><?= $order['delivery_date'] ?></strong></p>
    <p>Return Due: <strong><?= $order['return_due_date'] ?></strong></p>

    <h5>Select Payment Method</h5>
    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-outline-primary btn-method" onclick="showMethod('upi')">UPI</button>
        <button class="btn btn-outline-success btn-method" onclick="showMethod('card')">Card</button>
        <button class="btn btn-outline-dark btn-method" onclick="showMethod('net')">NetBanking</button>
    </div>

    <form method="POST" id="paymentForm">
        <input type="hidden" name="order_id" value="<?= $order_id ?>">
        <input type="hidden" name="payment_type" id="payment_type">
        <input type="hidden" name="amount" value="<?= number_format($amount_to_pay,2,'.','') ?>">

        <!-- UPI -->
        <div id="upi" class="payment-fields" style="display:none;">
            <p>Scan QR to Pay or use UPI ID: <strong><?= $upi_id ?></strong></p>
            <div class="qr-box">Shop QR Code</div>
            <label>UPI Transaction Ref</label>
            <input type="text" name="upi_tx_ref" class="form-control mb-2" required placeholder="UPI Ref ID">
            <button type="button" class="pay-btn" onclick="processPayment()">Pay ₹<?= number_format($amount_to_pay,2) ?></button>
        </div>

        <!-- Card -->
        <div id="card" class="payment-fields" style="display:none;">
            <label>Card Number</label>
            <input type="text" name="card_number" class="form-control mb-2" placeholder="1234 5678 9012" required>
            <label>Name on Card</label>
            <input type="text" name="card_name" class="form-control mb-2" placeholder="John Doe" required>
            <label>Expiry</label>
            <input type="text" name="expiry" class="form-control mb-2" placeholder="MM/YY" required>
            <label>PIN (5 digits)</label>
            <input type="password" id="card_pin" name="cvv" class="form-control mb-2" placeholder="5-digit PIN" required maxlength="5">
            <button type="button" class="pay-btn" onclick="validateCard()">Pay ₹<?= number_format($amount_to_pay,2) ?></button>
        </div>

        <!-- NetBanking -->
        <div id="net" class="payment-fields" style="display:none;">
            <label>Bank Name</label>
            <input type="text" name="bank_name" class="form-control mb-2" placeholder="HDFC / SBI / AXIS" required>
            <label>Account Number</label>
            <input type="text" name="acc_no" class="form-control mb-2" placeholder="XXXX XXXX XXXX" required>
            <label>Account Holder</label>
            <input type="text" name="acc_name" class="form-control mb-2" placeholder="Full Name" required>
            <button type="button" class="pay-btn" onclick="processPayment()">Pay ₹<?= number_format($amount_to_pay,2) ?></button>
        </div>
    </form>
</div>

<!-- Processing Modal -->
<div class="modal" tabindex="-1" id="processingModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5>Processing Payment...</h5>
      <div class="spinner-border text-warning mt-3" role="status"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showMethod(method){
    document.getElementById('upi').style.display='none';
    document.getElementById('card').style.display='none';
    document.getElementById('net').style.display='none';
    document.getElementById(method).style.display='block';
    document.getElementById('payment_type').value = method;
}

function validateCard(){
    var pin = document.getElementById('card_pin').value;
    if(pin.length !== 5){
        alert("PIN must be exactly 5 digits");
        return;
    }
    processPayment();
}

function processPayment(){
    var form = document.getElementById('paymentForm');
    var modal = new bootstrap.Modal(document.getElementById('processingModal'));
    modal.show();

    // Simulate processing
    setTimeout(function(){
        modal.hide();
        form.action = "confirm_payment.php"; // This will mark deposit as paid
        form.submit();
    }, 2000);
}
</script>

</body>
</html>
