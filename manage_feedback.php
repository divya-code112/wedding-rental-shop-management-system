<?php
session_start();


include __DIR__ . '/../includes/db.php';

// Fetch all feedback
$sql = "SELECT f.feedback_id, f.rating, f.comments, f.feedback_date, 
               u.full_name AS user_name,
               p.product_name
        FROM feedback f
        LEFT JOIN users u ON f.user_id = u.user_id
        LEFT JOIN products p ON f.product_id = p.product_id
        ORDER BY f.feedback_date DESC";
$res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Feedback — Royal Drapes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f6f7fb;color:#111;}
.container-main{max-width:1000px;margin:40px auto;padding:20px;}
h2{text-align:center;margin-bottom:25px;color:#111827;}
.table thead{background:#111827;color:#fff;}
.table tbody tr:hover{background:#f1f5f9;}
.star{color:#facc15;font-size:16px;}
.star-empty{color:#ccc;font-size:16px;}
body{background:#f4f5f7;}
.sidebar {width:250px; height:100vh; position:fixed; top:0; left:0; background:#1e1e2f; color:#fff; padding-top:20px;}
.sidebar a {display:block; padding:12px 20px; color:#ddd; text-decoration:none; font-size:15px; font-weight:500; transition:0.2s;}
.sidebar a:hover, .sidebar a.active{background:#34344e;color:#fff;}
.content {margin-left:260px; padding:20px;}
.card:hover {box-shadow:0 5px 15px rgba(0,0,0,0.2);transition:0.3s;}
.summary-card {border-left:5px solid #0d6efd;}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h4 class="text-center mb-4">Admin Panel</h4>
<a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="category.php"><i class="bi bi-list-task"></i> Categories</a>
<a href="subcategory.php"><i class="bi bi-diagram-2"></i> Subcategories</a>
<a href="type.php"><i class="bi bi-tag"></i> Types</a>
<a href="products.php"><i class="bi bi-bag"></i> Products</a>
<a href="admin_orders.php"><i class="bi bi-cart-check"></i> Orders</a>
<a href="payments.php"><i class="bi bi-credit-card"></i> Payments</a>
<a href="returns.php"><i class="bi bi-arrow-return-left"></i> Returns</a>
<a href="manage_feedback.php"><i class="bi bi-chat-dots"></i> Feedback</a>
<a href="users.php"><i class="bi bi-people"></i> Users</a>
<a href="reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
<a href="logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a>
</div>

<div class="container-main">
    <h2>All Feedback</h2>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                
                <th>Rating</th>
                <th>Comments</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php if(mysqli_num_rows($res) === 0): ?>
            <tr>
                <td colspan="6" class="text-center">No feedback found</td>
            </tr>
        <?php else: ?>
            <?php $count=1; while($f = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><?= htmlspecialchars($f['user_name']) ?></td>
                    
                    <td>
                        <?php
                        $rating = (int)$f['rating'];
                        for($i=1;$i<=5;$i++){
                            echo $i<=$rating ? '<span class="star">★</span>' : '<span class="star-empty">★</span>';
                        }
                        ?>
                    </td>
                    <td><?= htmlspecialchars($f['comments']) ?></td>
                    <td><?= date("d M Y, h:i A", strtotime($f['feedback_date'])) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
