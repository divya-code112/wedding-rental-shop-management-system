<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}
include __DIR__ . '/../includes/db.php';

// ---------- sanitize inputs ----------
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$cat = isset($_GET['category']) ? intval($_GET['category']) : 0;
$subcat = isset($_GET['subcat']) ? intval($_GET['subcat']) : 0;
$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
$size = isset($_GET['size']) ? mysqli_real_escape_string($conn, $_GET['size']) : '';
$stock = isset($_GET['stock']) ? mysqli_real_escape_string($conn, $_GET['stock']) : '';
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($conn, $_GET['gender']) : '';
$min_price = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// pagination
$limit = 12;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page']>0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ---------- build WHERE conditions ----------
$where = ["1"];
if ($search !== '') $where[] = "(p.product_name LIKE '%$search%')";
if ($cat) $where[] = "p.category_id = $cat";
if ($subcat) $where[] = "p.subcat_id = $subcat";
if ($type) $where[] = "p.type_id = $type";
if ($size !== '') $where[] = "p.size = '" . mysqli_real_escape_string($conn, $size) . "'";
if ($stock !== '') $where[] = "p.stock_status = '" . mysqli_real_escape_string($conn, $stock) . "'";
if ($gender !== '') $where[] = "p.gender = '" . mysqli_real_escape_string($conn, $gender) . "'";
if ($min_price > 0 && $max_price > 0 && $max_price >= $min_price) $where[] = "p.price_per_day BETWEEN $min_price AND $max_price";
elseif ($min_price > 0 && $max_price == 0) $where[] = "p.price_per_day >= $min_price";
elseif ($max_price > 0 && $min_price == 0) $where[] = "p.price_per_day <= $max_price";
$where_sql = implode(' AND ', $where);

// ---------- sort ----------
$order_sql = "p.created_at DESC";
if ($sort === 'low_high') $order_sql = "p.price_per_day ASC";
elseif ($sort === 'high_low') $order_sql = "p.price_per_day DESC";
elseif ($sort === 'rating') $order_sql = "p.rating DESC";
elseif ($sort === 'newest') $order_sql = "p.created_at DESC";

// ---------- total count ----------
$count_q = "SELECT COUNT(*) AS total FROM products p WHERE $where_sql";
$totalRows = (int)mysqli_fetch_assoc(mysqli_query($conn, $count_q))['total'];
$totalPages = $limit ? (int)ceil($totalRows / $limit) : 1;

// ---------- fetch products ----------
$sql = "SELECT p.*, c.category_name, s.subcat_name
        FROM products p
        LEFT JOIN category c ON p.category_id = c.category_id
        LEFT JOIN subcategory s ON p.subcat_id = s.subcat_id
        WHERE $where_sql
        ORDER BY $order_sql
        LIMIT $limit OFFSET $offset";
$prod_res = mysqli_query($conn, $sql);

// ---------- fetch filters data ----------
$cats = mysqli_query($conn, "SELECT * FROM category ORDER BY category_name");
$types = mysqli_query($conn, "SELECT * FROM type ORDER BY type_name");
$subcats = mysqli_query($conn, "SELECT * FROM subcategory ORDER BY subcat_name");

// min/max price present in DB for UI hints
$price_bounds = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MIN(price_per_day) AS minp, MAX(price_per_day) AS maxp FROM products"));
$global_min = $price_bounds['minp'] !== null ? (float)$price_bounds['minp'] : 0;
$global_max = $price_bounds['maxp'] !== null ? (float)$price_bounds['maxp'] : 0;

// ---------- Most Viewed / Top Rated for sidebar ----------
$mostViewed = mysqli_query($conn, "SELECT product_id, product_name, image, views, rating FROM products ORDER BY views DESC LIMIT 5");
$topRated = mysqli_query($conn, "SELECT product_id, product_name, image, views, rating FROM products ORDER BY rating DESC LIMIT 5");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Collection — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
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
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="filter-title">Filters</div>
      <form method="GET">
        <div class="filter-group">
          <label>Category</label>
          <select name="category">
            <option value="">All Categories</option>
            <?php mysqli_data_seek($cats,0); while($r = mysqli_fetch_assoc($cats)): ?>
              <option value="<?= $r['category_id'] ?>" <?= $r['category_id']==$cat?'selected':'' ?>><?= htmlspecialchars($r['category_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="filter-group">
          <label>Subcategory</label>
          <select name="subcat">
            <option value="">All Subcategories</option>
            <?php mysqli_data_seek($subcats,0); while($s = mysqli_fetch_assoc($subcats)): ?>
              <option value="<?= $s['subcat_id'] ?>" <?= $s['subcat_id']==$subcat?'selected':'' ?>><?= htmlspecialchars($s['subcat_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="filter-group">
          <label>Type</label>
          <select name="type">
            <option value="">All Types</option>
            <?php mysqli_data_seek($types,0); while($t = mysqli_fetch_assoc($types)): ?>
              <option value="<?= $t['type_id'] ?>" <?= $t['type_id']==$type?'selected':'' ?>><?= htmlspecialchars($t['type_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="filter-group">
          <label>Size</label>
          <select name="size">
            <option value="">All</option>
            <?php foreach(['S','M','L','XL','XXL','Free Size'] as $s): ?>
              <option value="<?= $s ?>" <?= $size===$s?'selected':'' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="filter-group">
          <label>Gender</label>
          <select name="gender">
            <option value="">Any</option>
            <option value="Male" <?= $gender=='Male'?'selected':'' ?>>Male</option>
            <option value="Female" <?= $gender=='Female'?'selected':'' ?>>Female</option>
          </select>
        </div>
        <div style="display:flex;gap:8px;margin-top:6px;">
          <button type="submit" class="btn-primary" style="flex:1;">Apply</button>
          <a href="collection.php" class="btn-outline" style="flex:1;text-align:center;">Reset</a>
        </div>
      </form>

      <!-- Most Viewed Carousel -->
      <div class="mt-4">
        <h6>Most Viewed</h6>
        <div id="mostViewedSidebar" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php $first=true; while($mv=mysqli_fetch_assoc($mostViewed)): ?>
            <?php $rating_val = $mv['rating'] ? (float)$mv['rating'] : round(rand(30,50)/10,1); ?>
            <div class="carousel-item <?= $first?'active':'' ?>">
              <img src="../assets/<?= htmlspecialchars($mv['image']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($mv['product_name']) ?>">
              <div class="carousel-caption d-none d-md-block">
                <h6><?= htmlspecialchars($mv['product_name']) ?></h6>
                <small style="color:#f59e0b;"><?= number_format($rating_val,1) ?> ★</small>
              </div>
            </div>
            <?php $first=false; endwhile; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#mostViewedSidebar" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#mostViewedSidebar" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>

      <!-- Top Rated Carousel -->
      <div class="mt-4">
        <h6>Top Rated</h6>
        <div id="topRatedSidebar" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php $first=true; while($tr=mysqli_fetch_assoc($topRated)): ?>
            <?php $rating_val = $tr['rating'] ? (float)$tr['rating'] : round(rand(30,50)/10,1); ?>
            <div class="carousel-item <?= $first?'active':'' ?>">
              <img src="../assets/<?= htmlspecialchars($tr['image']) ?>" class="d-block w-100" alt="<?= htmlspecialchars($tr['product_name']) ?>">
              <div class="carousel-caption d-none d-md-block">
                <h6><?= htmlspecialchars($tr['product_name']) ?></h6>
                <small style="color:#f59e0b;"><?= number_format($rating_val,1) ?> ★</small>
              </div>
            </div>
            <?php $first=false; endwhile; ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#topRatedSidebar" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#topRatedSidebar" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </aside>

    <!-- PRODUCTS AREA -->
    <section class="products-area">
      <?php if($totalRows == 0): ?>
        <div class="empty-state text-center">
          <h4>No products found</h4>
          <p class="small">Try adjusting filters or clearing search.</p>
        </div>
      <?php else: ?>
        <div class="grid">
          <?php while($p = mysqli_fetch_assoc($prod_res)): ?>
            <?php $rating_val = $p['rating'] ? (float)$p['rating'] : round(rand(30,50)/10,1); ?>
            <article class="card">
              <a class="card-media" href="product_details.php?id=<?= $p['product_id'] ?>">
                <img loading="lazy" src="../assets/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['product_name']) ?>">
              </a>
              <div class="card-body">
                <div class="title"><?= htmlspecialchars($p['product_name']) ?></div>
                <div class="meta"><?= htmlspecialchars($p['category_name'] . ' • ' . $p['subcat_name']) ?></div>
                <div class="price-row">
                  <div>
                    <div class="price">₹<?= number_format($p['price_per_day'],2) ?> /day</div>
                    <div class="deposit">Deposit: ₹<?= number_format($p['deposit_amount'],2) ?></div>
                  </div>
                  <div style="text-align:right">
                    <div style="font-weight:700;color:#f59e0b;">
                      <?php
                        $full = floor($rating_val);
                        $half = $rating_val - $full >= 0.5 ? 1 : 0;
                        for($i=1;$i<=$full;$i++){ echo '★'; }
                        if($half) echo '½';
                        for($i=$full+$half+1;$i<=5;$i++){ echo '☆'; }
                      ?>
                      <br><?= number_format($rating_val,1) ?>
                    </div>
                    <div class="small"><?= htmlspecialchars(ucfirst($p['stock_status'])) ?></div>
                  </div>
                </div>
                <div class="actions">
                  <?php if($p['stock_status'] === 'available'): ?>
                    <button class="btn-primary" onclick="window.location='product_details.php?id=<?= $p['product_id'] ?>'">View</button>
                  <?php else: ?>
                    <button class="btn-primary" disabled style="background:#ccc;cursor:not-allowed;">Out of Stock</button>
                  <?php endif; ?>
                </div>
                <div class="small">
                    Max rental days: <?= (int)$p['max_rental_days'] ?><br>
                    <?= $p['stock_status'] !== 'available' ? '<span style="color:#e11d48;font-weight:600;">Out of Stock</span>' : '' ?>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <!-- pagination -->
        <div class="pagination">
          <?php
          $start = max(1, $page-2);
          $end = min($totalPages, $start + 4);
          for($i=$start;$i<=$end;$i++):
          ?>
            <a class="page-link <?= $i==$page?'active':'' ?>" href="?<?= http_build_query(array_merge($_GET, ['page'=>$i])) ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</div>

<div class="footer">
    © <?php echo date("Y"); ?> Royal Drapes – All Rights Reserved
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
