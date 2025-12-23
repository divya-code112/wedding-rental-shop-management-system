<?php
include "../includes/db.php";

// Add new subcategory
if(isset($_POST['add_subcat'])){
    $category_id = intval($_POST['category_id']);
    $name = mysqli_real_escape_string($conn,$_POST['subcat_name']);
    mysqli_query($conn,"INSERT INTO subcategory(category_id,subcat_name) VALUES($category_id,'$name')");
}

// Delete subcategory
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM subcategory WHERE subcat_id=$id");
}

// Fetch categories & subcategories
$categories = mysqli_query($conn,"SELECT * FROM category");
$subcategories = mysqli_query($conn,"SELECT s.subcat_id,s.subcat_name,c.category_name FROM subcategory s JOIN category c ON s.category_id=c.category_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Subcategories - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
    </style>
</head>
<body>
<!-- Sidebar (same as category.php) -->
<div class="sidebar" id="sidebar"> 
<!-- same links, set subcategory.php as active --> 
<h4 class="text-center mb-4">Admin Panel</h4>
<a href="dashboard.php"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
<a href="category.php"><i class="bi bi-list-task"></i> <span>Categories</span></a>
<a href="subcategory.php" class="active"><i class="bi bi-diagram-2"></i> <span>Subcategories</span></a>
<a href="type.php"><i class="bi bi-tag"></i> <span>Types</span></a>
<a href="products.php"><i class="bi bi-bag"></i> <span>Products</span></a>
<a href="admin_orders.php"><i class="bi bi-cart-check"></i> <span>Orders</span></a>
<a href="payments.php"><i class="bi bi-credit-card"></i> <span>Payments</span></a>
<a href="returns.php"><i class="bi bi-arrow-return-left"></i> <span>Returns</span></a>
<a href="feedback.php"><i class="bi bi-chat-dots"></i> <span>Feedback</span></a>
<a href="users.php"><i class="bi bi-people"></i> <span>Users</span></a>
<a href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> <span>Reports</span></a>
<a href="logout.php"><i class="bi bi-box-arrow-left"></i> <span>Logout</span></a>
</div>
<i class="bi bi-list" id="toggleBtn"></i>

<div class="content container">
<h2 class="mb-4">Subcategory Management</h2>

<form method="post" class="mb-3">
<div class="row g-2">
<div class="col-md-4">
<select name="category_id" class="form-control" required>
<option value="">Select Category</option>
<?php while($cat=mysqli_fetch_assoc($categories)){ ?>
<option value="<?= $cat['category_id'] ?>"><?= $cat['category_name'] ?></option>
<?php } ?>
</select>
</div>
<div class="col-md-4">
<input type="text" name="subcat_name" class="form-control" placeholder="Subcategory Name" required>
</div>
<div class="col-md-4">
<button class="btn btn-primary" name="add_subcat">Add Subcategory</button>
</div>
</div>
</form>

<table class="table table-bordered">
<tr><th>ID</th><th>Category</th><th>Subcategory</th><th>Actions</th></tr>
<?php while($row=mysqli_fetch_assoc($subcategories)){ ?>
<tr>
<td><?= $row['subcat_id'] ?></td>
<td><?= $row['category_name'] ?></td>
<td><?= $row['subcat_name'] ?></td>
<td>
<a href="?delete=<?= $row['subcat_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
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
