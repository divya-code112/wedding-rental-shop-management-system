<?php
include "../includes/db.php";

if(isset($_POST['add_type'])){
    $name = mysqli_real_escape_string($conn,$_POST['type_name']);
    mysqli_query($conn,"INSERT INTO type(type_name) VALUES('$name')");
}
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM type WHERE type_id=$id");
}

$types = mysqli_query($conn,"SELECT * FROM type");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Types - Admin</title>
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
<!-- Sidebar same as before -->
<div class="sidebar" id="sidebar">
<h4 class="text-center mb-4">Admin Panel</h4>
<a href="dashboard.php">Dashboard</a>
<a href="category.php">Categories</a>
<a href="subcategory.php">Subcategories</a>
<a href="type.php" class="active">Types</a>
<a href="products.php">Products</a>
<a href="admin_orders.php">Orders</a>
<a href="payments.php">Payments</a>
<a href="returns.php">Returns</a>
<a href="feedback.php">Feedback</a>
<a href="users.php">Users</a>
<a href="reports.php">Reports</a>
<a href="logout.php">Logout</a>
</div>
<i class="bi bi-list" id="toggleBtn"></i>

<div class="content container">
<h2 class="mb-4">Type Management</h2>

<form method="post" class="mb-3 d-flex">
<input type="text" name="type_name" class="form-control me-2" placeholder="Type (Men/Women)" required>
<button class="btn btn-primary" name="add_type">Add Type</button>
</form>

<table class="table table-bordered">
<tr><th>ID</th><th>Type</th><th>Action</th></tr>
<?php while($row=mysqli_fetch_assoc($types)){ ?>
<tr>
<td><?= $row['type_id'] ?></td>
<td><?= $row['type_name'] ?></td>
<td>
<a href="?delete=<?= $row['type_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
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
