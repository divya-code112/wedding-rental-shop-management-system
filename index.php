<?php
require_once __DIR__ . '/../includes/header.php';
if(!is_admin()) { echo "<div class='alert alert-danger'>Admin only.</div>"; require_once __DIR__ . '/../includes/footer.php'; exit; }
?>
<h3>Admin Dashboard</h3>
<ul>
  <li><a href="products.php">Manage Products</a></li>
  <li><a href="orders.php">Orders</a></li>
  <li><a href="reports.php">Reports</a></li>
</ul>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
