<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Fetch cart items
$sql = "SELECT c.cart_id, c.product_id, c.rental_days, c.start_date,
               p.product_name, p.image, p.price_per_day, p.deposit_amount, p.max_rental_days
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = $user_id";
$res = mysqli_query($conn, $sql);

$items = [];
$total_deposit = 0;

while ($r = mysqli_fetch_assoc($res)) {
    $r['deposit_total'] = floatval($r['deposit_amount']);
    
    // Get booked dates for this product
    $booked_dates = [];
    $bd_res = mysqli_query($conn, "SELECT start_date, rental_days FROM cart WHERE product_id={$r['product_id']} AND cart_id != {$r['cart_id']}");
    while ($bd = mysqli_fetch_assoc($bd_res)) {
        $start = new DateTime($bd['start_date']);
        $days = intval($bd['rental_days']);
        for ($i=0;$i<$days;$i++){
            $booked_dates[] = $start->format('Y-m-d');
            $start->modify('+1 day');
        }
    }
    $r['booked_dates'] = $booked_dates;

    $items[] = $r;
    $total_deposit += $r['deposit_total'];
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cart — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f8f9fa;margin:0;padding:0;}
.container-main{max-width:1200px;margin:40px auto;}
.card{border-radius:12px;box-shadow:0 8px 25px rgba(0,0,0,0.08);transition:transform .2s;}
.card:hover{transform:translateY(-5px);}
.card img{border-radius:12px;height:140px;object-fit:cover;transition:transform .3s;}
.card:hover img{transform:scale(1.05);}
.input-sm{width:100px;}
.btn-remove{background:#e95353;color:white;border:none;border-radius:50%;width:35px;height:35px;}
.btn-remove:hover{background:#d84343;}
.cart-summary{background:#111;color:white;padding:25px;border-radius:12px;margin-top:20px;box-shadow:0 8px 25px rgba(0,0,0,0.08);}
.cart-summary div{display:flex;justify-content:space-between;margin-bottom:12px;font-weight:600;}
.checkout-btn{display:block;text-align:center;padding:14px 0;background:#f0c040;color:#111;font-weight:700;border-radius:8px;text-decoration:none;font-size:16px;}
label{font-weight:600;font-size:13px;}
.return-date, .days-input{background:#e9ecef;border:none;border-radius:6px;}
.badge-custom{font-size:0.85rem;padding:0.4em 0.6em;}
.countdown{font-size:13px;color:#dc3545;font-weight:600;}
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


<div class="container-main">
<h3 class="mb-4">My Cart (<?= count($items) ?>)</h3>

<?php if(empty($items)): ?>
<div class="alert alert-info">Your cart is empty. <a href="collection.php">Browse collection</a></div>
<?php else: ?>
<div class="row">
<div class="col-lg-8">
<?php foreach($items as $it):
    $start_date = $it['start_date'] ?: date('Y-m-d');
    $booked_json = json_encode($it['booked_dates']);
?>
<div class="card mb-4 p-3 d-flex flex-row align-items-center gap-3" data-cart-id="<?= intval($it['cart_id']) ?>" data-booked='<?= $booked_json ?>'>
    <img src="../assets/<?= htmlspecialchars($it['image']) ?>" class="me-3" width="140" alt="<?= htmlspecialchars($it['product_name']) ?>">
    <div class="flex-grow-1">
        <h5><?= htmlspecialchars($it['product_name']) ?></h5>
        <span class="badge bg-success mb-2 badge-custom">₹<?= number_format($it['price_per_day'],2) ?> /day</span>
        <span class="badge bg-warning text-dark mb-2 badge-custom">Deposit ₹<?= number_format($it['deposit_amount'],2) ?></span>

        <div class="d-flex gap-3 align-items-center mb-2 flex-wrap mt-2">
            <div>
                <label>Days</label>
                <input type="number" class="form-control input-sm days days-input" min="1" max="<?= intval($it['max_rental_days']) ?>" value="<?= intval($it['rental_days']) ?>">
            </div>
            <div>
                <label>Delivery Date</label>
                <input type="text" class="form-control input-sm start-date" value="<?= $start_date ?>">
            </div>
            <div>
                <label>Return Date</label>
                <input type="text" class="form-control input-sm return-date" readonly>
                <div class="countdown mt-1"></div>
            </div>
        </div>

        <p class="mt-2">Subtotal Deposit: ₹<span class="item-sub"><?= number_format($it['deposit_total'],2) ?></span></p>
    </div>
    <div class="text-end d-flex flex-column justify-content-start ms-3">
        <button class="btn-remove mb-2" data-cart-id="<?= intval($it['cart_id']) ?>"><i class="bi bi-trash"></i></button>
    </div>
</div>
<?php endforeach; ?>
</div>

<div class="col-lg-4">
    <div class="cart-summary">
        <h5>Payment Summary</h5>
        <div><div>Total Deposit</div><div>₹<span id="totalDeposit"><?= number_format($total_deposit,2) ?></span></div></div>
        <hr style="border-color:#444;">
        <div><div>Grand Total</div><div>₹<span id="grandTotal"><?= number_format($total_deposit,2) ?></span></div></div>
        <a href="checkout.php" class="checkout-btn mt-3">Pay Deposit & Proceed</a>
    </div>
</div>
</div>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Calculate return date
function calcReturnDate(start, days){
    let d = new Date(start);
    d.setDate(d.getDate() + parseInt(days));
    return d.toISOString().split('T')[0];
}

// Countdown days
function calcCountdown(returnDate){
    const today = new Date();
    const r = new Date(returnDate);
    const diffTime = r - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
}

// Init flatpickr and block booked dates
document.querySelectorAll('.card[data-cart-id]').forEach(card=>{
    const cartId = card.getAttribute('data-cart-id');
    const startInput = card.querySelector('.start-date');
    const daysInput = card.querySelector('.days');
    const returnInput = card.querySelector('.return-date');
    const countdownEl = card.querySelector('.countdown');

    const bookedDates = JSON.parse(card.getAttribute('data-booked'));

    flatpickr(startInput, {
        minDate: "today",
        disable: bookedDates,
        onChange: updateCart
    });

    function updateCart(){
        const returnDate = calcReturnDate(startInput.value, daysInput.value);
        returnInput.value = returnDate;
        countdownEl.textContent = calcCountdown(returnDate) > 0 ? `${calcCountdown(returnDate)} days left` : 'Return due!';
        saveCartUpdate();
    }

    function saveCartUpdate(){
        fetch('update_cart_ajax.php', {
            method: 'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({
                cart_id: cartId,
                start_date: startInput.value,
                rental_days: daysInput.value
            })
        });
    }

    updateCart();
    daysInput.addEventListener('input', updateCart);
});

// Remove item
document.querySelectorAll('.btn-remove').forEach(btn=>{
    btn.addEventListener('click', async function(){
        if(!confirm('Remove this item?')) return;
        const cartId = this.getAttribute('data-cart-id');
        const res = await fetch('remove_cart.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({cart_id:cartId})
        });
        const data = await res.json();
        if(data.success){
            this.closest('.card').remove();
            let total=0;
            document.querySelectorAll('.item-sub').forEach(e=>total+=parseFloat(e.textContent.replace(/,/g,'')));
            document.getElementById('totalDeposit').textContent = total.toFixed(2);
            document.getElementById('grandTotal').textContent = total.toFixed(2);
        } else { alert('Failed to remove'); }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
