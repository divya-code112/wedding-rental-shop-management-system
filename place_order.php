<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    die("Invalid Access");
}

$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$payment_method = $_POST['payment_method'];

$total_deposit = floatval($_POST['total_deposit']);
$total_rent = floatval($_POST['total_rent']);

$final_amount = $total_deposit * 100; // Razorpay takes paise
?>

<!DOCTYPE html>
<html>
<head>
<title>Processing Payment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f5f7fb; font-family:Poppins,sans-serif; }
.card-modern {
    background:#fff; padding:30px; border-radius:16px;
    box-shadow:0 8px 40px rgba(0,0,0,0.07);
}
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


<div class="container my-5">
    <div class="col-lg-6 mx-auto">
        <div class="card-modern text-center">
            <h3>Processing Your Payment…</h3>
            <p class="text-muted">Please wait, we are redirecting to Razorpay</p>

            <img src="https://cdni.iconscout.com/illustration/premium/thumb/online-payment-processing-illustration-download-in-svg-png-gif-file-formats--internet-refund-cartoon-pack-e-commerce-illustrations-4663435.png" width="220">
        </div>
    </div>
</div>

<!-- Razorpay Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
var options = {
    "key": "rzp_test_YourKeyHere",  // TODO: replace with your Razorpay test key
    "amount": "<?= $final_amount ?>",
    "currency": "INR",
    "name": "Royal Drapes",
    "description": "Deposit Payment",
    "image": "https://static.vecteezy.com/system/resources/thumbnails/009/665/422/small/rd-logo-design-vector.jpg",

    "handler": function (response){
        // Payment Success → move to backend
        window.location.href = "payment_success.php?payid=" + response.razorpay_payment_id 
                              + "&name=<?= urlencode($full_name) ?>"
                              + "&address=<?= urlencode($address) ?>"
                              + "&mobile=<?= urlencode($mobile) ?>"
                              + "&pm=<?= $payment_method ?>"
                              + "&td=<?= $total_deposit ?>"
                              + "&tr=<?= $total_rent ?>";
    },

    "prefill": {
        "name": "<?= $full_name ?>",
        "email": "<?= $_SESSION['email'] ?? '' ?>",
        "contact": "<?= $mobile ?>"
    },
    "theme": { "color": "#0d6efd" }
};

var rzp1 = new Razorpay(options);
rzp1.open();
</script>

</body>
</html>
