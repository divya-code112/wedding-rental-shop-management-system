<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

include __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$errors = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comments = isset($_POST['comments']) ? mysqli_real_escape_string($conn, trim($_POST['comments'])) : '';

    if($rating < 1 || $rating > 5){
        $errors[] = "Please select a rating between 1 and 5.";
    }

    if(empty($errors)){
        mysqli_query($conn, "INSERT INTO feedback (user_id, product_id, rating, comments) 
                             VALUES ($user_id, NULL, $rating, '$comments')");
        $success = "Thank you! Your feedback has been submitted.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{font-family:'Poppins',sans-serif;background:#f6f7fb;color:#111;}
.container-main{max-width:600px;margin:50px auto;padding:25px;background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.1);}
h3{font-weight:700;margin-bottom:25px;text-align:center;color:#111827;}
textarea{border-radius:10px;border:1px solid #e0e0e0;}
.btn-primary{background:#111827;border:none;border-radius:10px;padding:10px;font-weight:600;width:100%;transition:.3s;}
.btn-primary:hover{background:#1e40af;}
.alert{border-radius:10px;}

/* Star Rating */
.star-rating{display:flex;gap:6px;font-size:36px;justify-content:center;cursor:pointer;user-select:none;}
.star-rating label{color:#ccc;transition:.3s;transform-origin:center; display:inline-block;}
.star-rating input{display:none;}
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label{
    color:#facc15;
    animation: popStar 0.3s ease;
}

/* Animation */
@keyframes popStar{
    0%{transform: scale(1);}
    50%{transform: scale(1.4) rotate(-10deg);}
    100%{transform: scale(1);}
}

/* Show selected rating number */
.rating-value{display:block;text-align:center;font-weight:600;color:#111827;margin-top:6px;font-size:18px;}
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

        nav.nav-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 25px;
        }

        nav.nav-right a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: .2s;
        }

        nav.nav-right a:hover, nav.nav-right a.active {
            color: var(--gold);
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
        }

        .hamburger div {
            width: 25px;
            height: 3px;
            background: white;
            transition: 0.3s;
        }

        @media (max-width: 900px) {
            nav.nav-right {
                position: fixed;
                top: 0;
                right: -100%;
                height: 100vh;
                width: 250px;
                background: var(--black);
                flex-direction: column;
                padding-top: 80px;
                transition: 0.3s;
            }
            nav.nav-right.show {
                right: 0;
            }
            .hamburger { display: flex; }
        }

        .cart-badge {
            background: var(--gold);
            color: var(--black);
            font-size: 12px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -5px;
            right: -10px;
            animation: pop 0.3s ease;
        }

        @keyframes pop {
            0% { transform: scale(0); }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); }
        }

        /* HERO */
        .hero{
            height:70vh;position:relative;display:flex;align-items:center;justify-content:center;
        }
        .hero img{position:absolute;width:100%;height:100%;object-fit:cover;filter:brightness(0.65);} 
        .hero-content{position:relative;text-align:center;color:white;max-width:700px;}
        .hero-content h1{font-size:48px;font-weight:700;margin-bottom:10px;}
        .hero-content p{margin-bottom:20px;font-size:18px;opacity:0.9;}
        .btn{padding:12px 22px;border-radius:10px;text-decoration:none;font-weight:700;}
        .btn-gold{background:var(--gold);color:black;}
        .btn-outline{border:1px solid white;color:white;margin-left:10px;} 

        /* SECTIONS */
        .wrapper{max-width:1200px;margin:auto;padding:30px;} 
        .section-title{font-size:26px;font-weight:700;margin-bottom:5px;}
        .section-sub{color:var(--gray);margin-bottom:18px;} 

        /* CARD SCROLLER */
        .scroller{display:flex;gap:16px;overflow-x:auto;padding-bottom:10px;}
        .scroller::-webkit-scrollbar{height:8px;}
        .scroller::-webkit-scrollbar-thumb{background:#ccc;border-radius:10px;} 
        .item-card{min-width:240px;background:white;border-radius:14px;overflow:hidden;box-shadow:0 6px 20px var(--shadow);transition:.25s;}
        .item-card:hover{transform:translateY(-8px);}
        .item-card img{width:100%;height:170px;object-fit:cover;} 
        .item-body{padding:12px;} 

        /* CATEGORY GRID */
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;} 
        .cat-box{background:white;padding:20px;border-radius:14px;text-align:center;cursor:pointer;box-shadow:0 6px 20px var(--shadow);transition:.25s;}
        .cat-box:hover{transform:translateY(-8px);background:rgba(212,175,55,0.08);} 

        /* FOOTER */
        footer{background:var(--black);color:white;padding:30px 20px;margin-top:40px;}
        footer small{color:var(--gold-soft);} 
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
        <div></div>
        <div></div>
        <div></div>
    </div>

    <nav class="nav-right">
        <a href="index.php" class="active"><i class="fa-solid fa-house"></i> Home</a>
        <a href="collection.php"><i class="fa-solid fa-shirt"></i> Collection</a>
         <a href="about.php">About Us</a>
        <a href="cart.php" style="position:relative;"><i class="fa-solid fa-cart-shopping"></i> <span class="cart-badge" id="cart-count">3</span></a>
        <a href="order_details.php"><i class="fa-solid fa-receipt"></i> My Orders</a>
        <a href="payment_history.php"><i class="fa-solid fa-wallet"></i> Payment</a>
        <a href="feedback.php"><i class="bi bi-chat-dots-fill" style="font-size:24px;"></i>Feedback</a>
        <a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a>
        <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
    </nav>
</header>


<div class="container-main">
    <h3>Submit Your Feedback</h3>

    <?php if(!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
        </div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST">
        <!-- Star Rating -->
        <div class="mb-4">
            <label class="form-label d-block text-center">Rating</label>
            <div class="star-rating">
                <?php for($i=5;$i>=1;$i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                    <label for="star<?= $i ?>">★</label>
                <?php endfor; ?>
            </div>
            <span class="rating-value" id="ratingValue">0</span>
        </div>

        <!-- Comments -->
        <div class="mb-3">
            <label class="form-label">Comments</label>
            <textarea name="comments" rows="4" class="form-control" placeholder="Write your feedback..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Feedback</button>
    </form>
</div>

<script>
// Live update of selected rating number
const stars = document.querySelectorAll('.star-rating input');
const ratingValue = document.getElementById('ratingValue');

stars.forEach(star => {
    star.addEventListener('change', () => {
        ratingValue.textContent = star.value + " / 5";
    });
});
</script>

</body>
</html>
