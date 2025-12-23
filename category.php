<?php
include "../includes/db.php";

// Add new category
if(isset($_POST['add_category'])){
    $name = mysqli_real_escape_string($conn,$_POST['category_name']);
    mysqli_query($conn,"INSERT INTO category(category_name) VALUES('$name')");
}

// Delete category
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM category WHERE category_id=$id");
}

// Fetch all categories
$categories = mysqli_query($conn,"SELECT * FROM category");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Categories - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body{background:#f8f9fa;}
        .sidebar {width:250px; height:100vh; position:fixed; top:0; left:0; background:#1e1e2f; color:#fff; padding-top:20px; transition:0.3s;}
        .sidebar a {display:block; padding:12px 20px; color:#ddd; text-decoration:none; font-size:15px; font-weight:500; transition:0.2s;}
        .sidebar a:hover, .sidebar a.active{background:#34344e;color:#fff;}
        .content {margin-left:260px; padding:20px; transition:0.3s;}
        #toggleBtn{position:absolute; left:260px; top:15px; font-size:25px; cursor:pointer; color:#444;}
        .collapsed{width:70px !important;}
        .collapsed a span{display:none;}
        .collapsed + #toggleBtn{left:80px !important;}
    
        body{background:#f8f9fa;}
        .sidebar {width:250px; height:100vh; position:fixed; top:0; left:0; background:#1e1e2f; color:#fff; padding-top:20px; transition:0.3s;}
        .sidebar a {display:block; padding:12px 20px; color:#ddd; text-decoration:none; font-size:15px; font-weight:500; transition:0.2s;}
        .sidebar a:hover, .sidebar a.active{background:#34344e;color:#fff;}
        .content {margin-left:260px; padding:20px; transition:0.3s;}
        #toggleBtn{position:absolute; left:260px; top:15px; font-size:25px; cursor:pointer; color:#444;}
        .collapsed{width:70px !important;}
        .collapsed a span{display:none;}
        .collapsed + #toggleBtn{left:80px !important;}
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center mb-4">Admin Panel</h4>
    <a href="dashboard.php"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
    <a href="category.php" class="active"><i class="bi bi-list-task"></i> <span>Categories</span></a>
    <a href="subcategory.php"><i class="bi bi-diagram-2"></i> <span>Subcategories</span></a>
    <a href="type.php"><i class="bi bi-tag"></i> <span>Types</span></a>
    <a href="products.php"><i class="bi bi-bag"></i> <span>Products</span></a>
    <a href="manage_orders.php"><i class="bi bi-cart-check"></i> <span>Orders</span></a>
    <a href="payments.php"><i class="bi bi-credit-card"></i> <span>Payments</span></a>
    <a href="returns.php"><i class="bi bi-arrow-return-left"></i> <span>Returns</span></a>
    <a href="manage_feedback.php"><i class="bi bi-chat-dots"></i> <span>Feedback</span></a>
    <a href="users.php"><i class="bi bi-people"></i> <span>Users</span></a>
    <a href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> <span>Reports</span></a>
    <a href="logout.php"><i class="bi bi-box-arrow-left"></i> <span>Logout</span></a>
</div>
<i class="bi bi-list" id="toggleBtn"></i>

<div class="content container">
<h2 class="mb-4">Category Management</h2>

<form method="post" class="mb-3">
<div class="input-group mb-2">
<input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
<button class="btn btn-primary" name="add_category">Add Category</button>
</div>
</form>

<table class="table table-bordered">
<tr><th>ID</th><th>Name</th><th>Actions</th></tr>
<?php while($row=mysqli_fetch_assoc($categories)){ ?>
<tr>
<td><?= $row['category_id'] ?></td>
<td><?= $row['category_name'] ?></td>
<td>
<a href="?delete=<?= $row['category_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<script>
let sidebar=document.getElementById("sidebar");
let toggleBtn=document.getElementById("toggleBtn");
toggleBtn.onclick=()=>{sidebar.classList.toggle("collapsed");}
</script>
</body>
</html>
