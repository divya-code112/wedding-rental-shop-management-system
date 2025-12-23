<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { font-family:'Poppins', sans-serif; background:#f8f9fa; margin:0; }
.container-main { max-width:1000px; margin:40px auto; padding:20px; }
h2 { text-align:center; margin-bottom:30px; color:#111827; position: relative;}
h2::after { content:""; width:60px; height:3px; background:#1e40af; display:block; margin:8px auto 0; border-radius:2px;}
.card { border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); padding:20px; margin-bottom:30px; }
.form-control { border-radius:12px; }
.btn-primary { border-radius:12px; background:#1e40af; border:none; }
.map-container { width:100%; height:400px; border-radius:12px; overflow:hidden; box-shadow:0 6px 18px rgba(0,0,0,0.06); margin-bottom:30px; }
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
    <h2>Contact Us</h2>

    <div class="map-container" id="map"></div>

    <div class="card">
        <h4>Send Us a Message</h4>
        <form action="contact_submit.php" method="POST">
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
        </form>
    </div>
</div>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>
<script>
function initMap(){
    var location = {lat: 19.0760, lng: 72.8777}; // Example: Mumbai
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: location
    });
    var marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "Royal Drapes"
    });
}
window.onload = initMap;
</script>

</body>
</html>
