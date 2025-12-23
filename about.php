<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>About Us | Royal Drapes – Wedding Clothing Rental</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#faf7f2;
    color:#222;
}
h2{font-weight:800;}
.section{padding:90px 0}

/* HERO */
.hero{
    height:550px;
    background:
    linear-gradient(rgba(236, 231, 231, 0.89),rgba(210, 205, 205, 0.6)),
    url("assets/wedding-bg.jpg") center/cover no-repeat;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
    color:#fff;
}
.hero h1{font-size:70px;font-weight:900;letter-spacing:2px;color:white}
.hero p{color:#fbbf24;font-size:30px;margin-top:10px}

/* FEATURE BOX */
.feature{
    background:#fff;
    padding:30px;
    border-radius:18px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    transition:.4s;
}
.feature:hover{transform:translateY(-10px)}
.feature i{font-size:40px;color:#caa03c;margin-bottom:15px}

/* STEPS */
.step{
    text-align:center;
}
.step span{
    width:60px;height:60px;
    background:#caa03c;
    color:#fff;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-size:22px;
    font-weight:700;
    margin-bottom:10px;
}

/* GALLERY */
.gallery img{
    width:100%;
    border-radius:15px;
    transition:.4s;
}
.gallery img:hover{transform:scale(1.05)}

/* TESTIMONIAL */
.testimonial{
    background:#fff;
    padding:30px;
    border-radius:18px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.testimonial i{color:#fbbf24}

/* CTA */
.cta{
    background:linear-gradient(135deg,#caa03c,#fbbf24);
    padding:70px;
    border-radius:25px;
    text-align:center;
}
.cta a{
    background:#111;
    color:#fff;
    padding:15px 32px;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
}

/* WhatsApp */
.whatsapp{
    position:fixed;
    right:20px;
    bottom:20px;
    background:#25D366;
    color:#fff;
    width:60px;height:60px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    font-size:30px;
    box-shadow:0 8px 25px rgba(12, 10, 10, 0.3);
    z-index:999;
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
        .hero-content h1{font-size:60px;font-weight:700;margin-bottom:10px;}
        .hero-content p{margin-bottom:20px;font-size:30px;opacity:0.9;}
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

        .gallery img{
    width:100%;
    height:320px;          /* 👈 fixed height */
    object-fit:cover;      /* 👈 crop properly without distortion */
    border-radius:15px;
    transition:0.4s;
}

.gallery img:hover{
    transform:scale(1.05);
}
        /* CATEGORY GRID */
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;} 
        .cat-box{background:white;padding:20px;border-radius:14px;text-align:center;cursor:pointer;box-shadow:0 6px 20px var(--shadow);transition:.25s;}
        .cat-box:hover{transform:translateY(-8px);background:rgba(212,175,55,0.08);} 

        /* FOOTER */
        footer{background:var(--black);color:white;padding:30px 20px;margin-top:40px;}
        footer small{color:var(--gold-soft);} 

        @media(max-width:768px){
    .gallery img{
        height:220px;
    }
}

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

<!-- HERO -->
<section class="hero">
    <img src="../assets/banner.jpg" alt="Wedding Rentals">
    <div class="hero-content">
        <h1>Royal Drapes</h1>
        <p>Where Royalty Meets Rent</p>
    </div>
</section>

<!-- ABOUT -->
<section class="section container text-center">
    <h2>Who We Are</h2>
    <p>
        Royal Drapes is a premium wedding clothing rental boutique offering
        bridal, groom, and festive wear on rent.
        We help couples look royal on their big day without spending lakhs.
    </p>
</section>

<!-- WHY RENT -->
<section class="section container">
<div class="row g-4">
    <div class="col-md-4">
        <div class="feature">
            <i class="fa-solid fa-crown"></i>
            <h5>Royal Collection</h5>
            <p>Designer lehengas, sarees & sherwanis.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature">
            <i class="fa-solid fa-wallet"></i>
            <h5>Save Money</h5>
            <p>Wear luxury without buying expensive outfits.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="feature">
            <i class="fa-solid fa-heart"></i>
            <h5>Stress Free</h5>
            <p>Cleaned, fitted & ready-to-wear outfits.</p>
        </div>
    </div>
</div>
</section>

<!-- HOW IT WORKS -->
<section class="section container text-center">
<h2>How Renting Works</h2>
<div class="row mt-4">
    <div class="col-md-3 step">
        <span>1</span>
        <p>Select Outfit</p>
    </div>
    <div class="col-md-3 step">
        <span>2</span>
        <p>Order & Try</p>
    </div>
    <div class="col-md-3 step">
        <span>3</span>
        <p>Wear On Event</p>
    </div>
    <div class="col-md-3 step">
        <span>4</span>
        <p>Easy Return</p>
    </div>
</div>
</section>

<!-- GALLERY -->
<section class="section container">
<h2 class="text-center">Wedding Collection</h2>
<div class="row g-4 gallery mt-3">
    <div class="col-md-4"><img src="../assets/couple1.jpg"></div>
    <div class="col-md-4"><img src="../assets/couple2.jpg"></div>
    <div class="col-md-4"><img src="../assets/couple3.jpg"></div>
    <div class="col-md-4"><img src="../assets/couple4.jpg"></div>
    <div class="col-md-4"><img src="../assets/couple5.jpg"></div>
    <div class="col-md-4"><img src="../assets/couple6.jpg"></div>

    

</div>
</section>

<!-- TESTIMONIALS -->
<section class="section container">
<h2 class="text-center">Happy Brides & Grooms</h2>
<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="testimonial">
            <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
            <p>“Felt like a queen on my wedding day.”</p>
            <strong>— Divya</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="testimonial">
            <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
            <p>“Best wedding rental shop in the city.”</p>
            <strong>— Kshitij</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="testimonial">
            <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>
            <p>“Affordable & premium designs.”</p>
            <strong>— Suhani</strong>
        </div>
    </div>
</div>
</section>

<!-- CTA -->
<section class="section container">
<div class="cta">
    <h2>Make Your Wedding Look Royal</h2>
    <p>Browse our exclusive wedding outfits available on rent</p>
    <a href="collection.php">View Collection</a>
</div>
</section>

<!-- WhatsApp -->
<a href="https://wa.me/919067316913" class="whatsapp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

</body>
</html>
