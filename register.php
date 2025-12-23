<?php
session_start();
include "../includes/db.php";

$message = '';

if(isset($_POST['register'])){
    $full_name = mysqli_real_escape_string($conn,$_POST['full_name']);
    $email     = mysqli_real_escape_string($conn,$_POST['email']);
    $mobile    = mysqli_real_escape_string($conn,$_POST['mobile']);
    $password  = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $address   = mysqli_real_escape_string($conn,$_POST['address']);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "Email already registered!";
    } else {
        mysqli_query($conn,"INSERT INTO users(full_name,email,mobile,password,address) VALUES('$full_name','$email','$mobile','$password','$address')");
        $message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Royal Drapes</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
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
body {
    font-family:'Poppins',sans-serif;
    background: linear-gradient(to right, #f7f0e3, #f4f4f4);
}
.form-container {
    max-width:500px;
    margin:60px auto;
    background:white;
    padding:40px 30px;
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,0.15);
    text-align:center;
    position:relative;
    transition:0.3s;
}
.form-container:hover {
    box-shadow:0 25px 50px rgba(0,0,0,0.2);
}
.logo-area img {
    height:70px;
    width:70px;
    border-radius:12px;
    border:2px solid #D4AF37;
    padding:5px;
}
.logo-title {
    font-size:26px;
    font-weight:700;
    margin-top:10px;
    color:#0D0D0D;
}
.tagline {
    font-size:14px;
    color:#D4AF37;
    margin-bottom:25px;
    letter-spacing:1px;
}
.input-group-text {
    background: #fff;
    border: 1px solid #ccc;
    border-right: 0;
}
.input-group input, .input-group textarea {
    border-left: 0;
    border-radius: 0 10px 10px 0;
}
input:focus, textarea:focus {
    border-color:#D4AF37;
    box-shadow:0 0 8px rgba(212,175,55,0.3);
    outline:none;
}
.btn-custom {
    background:#0D0D0D;
    color:#D4AF37;
    font-weight:600;
    border-radius:12px;
    padding:12px 0;
    transition:0.3s;
}
.btn-custom:hover {
    background:#D4AF37;
    color:#0D0D0D;
}
a { color:#D4AF37; text-decoration:none; }
a:hover { text-decoration:underline; }
</style>
</head>
<body>
<!-- NAVBAR -->
<header>
    <div class="logo-area">
        <img src="../assets/logo.jpg" alt="Royal Drapes">
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
<div class="form-container">
    <div class="logo-area">
        <img src="../assets/logo.jpeg" alt="Royal Drapes">
        <div class="logo-title">Royal Drapes</div>
    </div>

    <h3 class="mb-4">Create Account</h3>
    <?php if($message) echo '<div class="alert alert-info">'.$message.'</div>'; ?>
    <form method="post">
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
            <input type="text" name="mobile" class="form-control" placeholder="Mobile" required>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
            <textarea name="address" class="form-control" placeholder="Address" required></textarea>
        </div>
        <button class="btn btn-custom w-100" name="register">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
</div>

</body>
</html>