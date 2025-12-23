<?php
session_start();
include __DIR__ . '/../includes/db.php';

if(!isset($_GET['id'])){
    header("Location: collection.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product details
$product_q = mysqli_query($conn, "
    SELECT p.*, c.category_name, s.subcat_name
    FROM products p
    LEFT JOIN category c ON p.category_id=c.category_id
    LEFT JOIN subcategory s ON p.subcat_id=s.subcat_id
    WHERE p.product_id=$product_id
");
if(mysqli_num_rows($product_q)==0){
    die("Product not found");
}
$product = mysqli_fetch_assoc($product_q);
$product['rating'] = $product['rating'] ?? rand(30,50)/10;
$product['views'] = $product['views'] ?? rand(10,150);

// Get current cart count
$cart_count = 0;
if(isset($_SESSION['user_id'])){
    $uid = intval($_SESSION['user_id']);
    $res = mysqli_query($conn,"SELECT COUNT(*) AS cnt FROM cart WHERE user_id=$uid");
    $cart_count = mysqli_fetch_assoc($res)['cnt'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?= htmlspecialchars($product['product_name']) ?> — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{background:#f6f7fb;font-family:'Poppins',sans-serif;color:#111;padding:20px;}
.card{border-radius:12px;box-shadow:0 6px 20px rgba(0,0,0,0.05);}
.rating-stars{color:#f59e0b;}
.toast-container{position:fixed;top:20px;right:20px;z-index:9999;}
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
    <a href="collection.php" class="btn btn-outline-secondary mb-3">&larr; Back to Collection</a>
    <div class="row">
        <div class="col-md-5">
            <div class="card p-3 mb-3">
                <img src="../assets/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($product['product_name']) ?>">
                <div class="mt-2">
                    <strong>Views:</strong> <?= $product['views'] ?><br>
                    <strong>Rating:</strong> <span class="rating-stars"><?= number_format($product['rating'],1) ?> ★</span>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card p-3">
                <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p class="text-muted"><?= htmlspecialchars($product['category_name'].' • '.$product['subcat_name']) ?></p>
                <h4>₹<?= number_format($product['price_per_day'],2) ?> /day</h4>
                <p>Deposit: ₹<?= number_format($product['deposit_amount'],2) ?></p>
                <p>Stock Status: <?= ucfirst($product['stock_status']) ?></p>
                <p>Max Rental Days: <?= $product['max_rental_days'] ?></p>

                <!-- Fees Info -->
                <div class="bg-warning bg-opacity-25 p-2 rounded mb-3">
                    <h6>Fees Information</h6>
                    <ul class="mb-0">
                        <li>Late Fee: 10% of daily rent per day</li>
                        <li>Damage Fee: Minor ₹500, Major ₹1000, Repair TBD</li>
                    </ul>
                </div>

                <?php if($product['stock_status']==='available'): ?>
                <button id="addCartBtn" class="btn btn-success"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                <?php else: ?>
                <button class="btn btn-secondary" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Toast notification -->
<div class="toast-container">
    <div class="toast align-items-center text-bg-success border-0" id="cartToast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          Product added to cart!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
</div>

<script>
document.getElementById('addCartBtn')?.addEventListener('click', async function(){
    const res = await fetch('add_to_cart.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({product_id:<?= $product['product_id'] ?>, quantity:1})
    });
    const data = await res.json();
    if(data.success){
        // Update cart count
        document.getElementById('cartCount').textContent = data.cart_count;
        // Show toast
        const toastEl = document.getElementById('cartToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    } else {
        alert('Failed to add to cart.');
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
