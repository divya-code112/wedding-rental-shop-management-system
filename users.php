<?php
include "../includes/db.php";

// Delete user
if(isset($_GET['delete'])){
    $uid = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM users WHERE user_id=$uid");
    echo "<script>alert('User deleted'); window.location='users.php';</script>";
}

// Fetch users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin - Manage Users</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
<h2 class="mb-4">Manage Users</h2>
<table class="table table-bordered table-hover align-middle">
<thead class="table-dark">
<tr>
<th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Address</th><th>Created At</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php while($u=mysqli_fetch_assoc($users)): ?>
<tr>
<td><?= $u['user_id'] ?></td>
<td><?= $u['full_name'] ?></td>
<td><?= $u['email'] ?></td>
<td><?= $u['mobile'] ?></td>
<td><?= $u['address'] ?></td>
<td><?= $u['created_at'] ?></td>
<td>
<a href="edit_user.php?id=<?= $u['user_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
<a href="users.php?delete=<?= $u['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?');">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</body>
</html>
