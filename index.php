<?php
session_start();
include 'db.php';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Dummy data for demonstration
$mostViewed = [
    ['name'=>'Royal Sherwani','image'=>'sherwani.jpg','views'=>245,'rating'=>4.5],
    ['name'=>'Bridal Lehenga','image'=>'lehenga.jpg','views'=>198,'rating'=>4.8],
    ['name'=>'Classic Tuxedo','image'=>'tuxedo.jpg','views'=>173,'rating'=>4.2]
];

$mostRated = [
    ['name'=>'Bridal Lehenga','image'=>'lehenga.jpg','rating'=>4.8],
    ['name'=>'Royal Sherwani','image'=>'sherwani.jpg','rating'=>4.5],
    ['name'=>'Classic Tuxedo','image'=>'tuxedo.jpg','rating'=>4.2]
];

$recommended = [
    ['name'=>'Muslim Wedding Outfit','image'=>'muslim.jpg','rating'=>4.5],
    ['name'=>'Gujarati Chaniya Choli','image'=>'gujarati.jpg','rating'=>4.3],
    ['name'=>'South Indian Saree','image'=>'southindian.jpg','rating'=>4.4]
];

function renderStars($rating) {
    $html = '<div class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= '<i class="fa-solid fa-star ' . ($i <= round($rating) ? 'active' : '') . '" data-val="' . $i . '"></i>';
    }
    $html .= '</div>';
    return $html;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Royal Drapes | Wedding Rentals</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:'Poppins',sans-serif;background:#f4f4f4;color:#111;}
.navbar-brand{font-weight:700;font-size:1.5rem;}
.glass-card{
    background:rgba(255,255,255,0.18);
    backdrop-filter:blur(14px);
    border-radius:20px;
    border:1px solid rgba(255,255,255,0.2);
    box-shadow:0 10px 25px rgba(0,0,0,.1);
    transition:.3s;
}
.glass-card:hover{transform:translateY(-8px);}
.stars i{cursor:pointer;color:#ccc;}
.stars i.active{color:#D4AF37;}
.hero-section{position:relative;height:70vh;background:url('../assets/banner.jpg') center/cover no-repeat;}
.hero-overlay{position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;flex-direction:column;}
.hero-overlay h1{font-size:3rem;font-weight:700;}
.hero-overlay p{font-size:1.2rem;margin-bottom:20px;}
.category-card{cursor:pointer;transition:.3s;}
.category-card:hover{transform:translateY(-5px);background:rgba(212,175,55,0.1);}
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

<!-- HERO -->
<section class="hero-section">
  <div class="hero-overlay">
    <h1>Dress Like Royalty</h1>
    <p>Premium Wedding & Event Rentals</p>
    <a class="btn btn-warning px-4 py-2 fw-bold mb-2" href="collection.php">Explore Collection</a>
    <a class="btn btn-outline-light px-4 py-2 fw-bold" href="contact.php">Contact Us</a>
  </div>
</section>

<!-- CATEGORIES -->
<div class="container py-5">
  <h3 class="mb-4">Wedding Categories</h3>
  <div class="row g-4">
    <div class="col-6 col-md-3">
      <div class="glass-card p-3 text-center category-card">Christian Wedding</div>
    </div>
    <div class="col-6 col-md-3">
      <div class="glass-card p-3 text-center category-card">Muslim Wedding</div>
    </div>
    <div class="col-6 col-md-3">
      <div class="glass-card p-3 text-center category-card">South Indian Wedding</div>
    </div>
    <div class="col-6 col-md-3">
      <div class="glass-card p-3 text-center category-card">Gujarati Wedding</div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="text-center py-4 bg-dark text-white">
  © <?php echo date('Y');?> Royal Drapes
</footer>

<?php if($user_id): ?>
<!-- Recommended Modal -->
<div class="modal fade" id="recommendedModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4 p-3">
      <h5 class="mb-3">🔥 Recommended For You</h5>
      <div class="row g-3">
        <?php foreach($recommended as $p): ?>
        <div class="col-12 col-md-4">
          <div class="glass-card p-3 text-center">
            <img src="../assets/<?php echo $p['image'];?>" class="img-fluid rounded mb-2">
            <h6><?php echo $p['name'];?></h6>
            <?php echo renderStars($p['rating']); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Most Viewed Modal -->
<div class="modal fade" id="viewedModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4 p-3">
      <h5 class="mb-3">👁 Most Viewed</h5>
      <div class="row g-3">
        <?php foreach($mostViewed as $p): ?>
        <div class="col-12 col-md-4">
          <div class="glass-card p-3 text-center">
            <img src="../assets/<?php echo $p['image'];?>" class="img-fluid rounded mb-2">
            <h6><?php echo $p['name'];?></h6>
            <small><?php echo $p['views'];?> views</small>
            <?php echo renderStars($p['rating']); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Most Rated Modal -->
<div class="modal fade" id="ratedModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4 p-3">
      <h5 class="mb-3">⭐ Most Rated</h5>
      <div class="row g-3">
        <?php foreach($mostRated as $p): ?>
        <div class="col-12 col-md-4">
          <div class="glass-card p-3 text-center">
            <img src="../assets/<?php echo $p['image'];?>" class="img-fluid rounded mb-2">
            <h6><?php echo $p['name'];?></h6>
            <?php echo renderStars($p['rating']); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
<?php if($user_id): ?>
// Sequential modals
window.addEventListener('DOMContentLoaded',()=> {
  let recommended = new bootstrap.Modal(document.getElementById('recommendedModal'));
  let viewed = new bootstrap.Modal(document.getElementById('viewedModal'));
  let rated = new bootstrap.Modal(document.getElementById('ratedModal'));
  recommended.show();
  document.getElementById('recommendedModal').addEventListener('hidden.bs.modal',()=> viewed.show());
  document.getElementById('viewedModal').addEventListener('hidden.bs.modal',()=> rated.show());
});
<?php endif; ?>

// Clickable stars
document.querySelectorAll('.stars i').forEach(star=>{
 star.onclick=()=>{
   let parent=star.parentElement;
   let val=star.dataset.val;
   parent.querySelectorAll('i').forEach(s=>s.classList.toggle('active',s.dataset.val<=val));
   alert('⭐ Rated '+val+' (AJAX placeholder)');
 };
});
</script>
</body>
</html>
