<?php
session_start();
include "../includes/db.php";

/* =========================
   UPDATE ORDER STATUS
========================= */
if (isset($_POST['update_status'])) {

    $order_id = intval($_POST['order_id']);
    $status   = $_POST['order_status'];

    // Fetch order
    $o = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM orders WHERE order_id = $order_id"
    ));

    if (!$o) {
        header("Location: admin_manage_orders.php");
        exit();
    }

    // ---------------- DELIVERED ----------------
    if ($status === 'delivered') {
        mysqli_query($conn, "
            UPDATE orders 
            SET order_status='delivered',
                delivered_at = NOW()
            WHERE order_id = $order_id
        ");
    }

    // ---------------- RETURNED ----------------
    if ($status === 'returned') {

        $return_due  = strtotime($o['return_due_date']);
        $returned_at = strtotime(date('Y-m-d'));

        // ✅ LATE FEE ONLY IF OVERDUE
        $late_fee = ($returned_at > $return_due) ? 500 : 0;

        mysqli_query($conn, "
            UPDATE orders 
            SET order_status='returned',
                late_fee = $late_fee,
                returned_at = NOW()
            WHERE order_id = $order_id
        ");
    }

    // ---------------- OTHER STATUS ----------------
    if (!in_array($status, ['delivered','returned'])) {
        mysqli_query($conn, "
            UPDATE orders 
            SET order_status='$status'
            WHERE order_id = $order_id
        ");
    }

    header("Location: admin_manage_orders.php");
    exit();
}

/* =========================
   FETCH ORDERS
========================= */
$orders = mysqli_query($conn, "
    SELECT o.*, u.full_name
    FROM orders o
    JOIN users u ON u.user_id = o.user_id
    ORDER BY o.order_id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin | Manage Orders</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{background:#f4f6f9;}
.card{border-radius:14px;}
.badge-status{font-size:13px;padding:6px 10px;}
</style>
</head>

<body>

<div class="container mt-4">

    <h3 class="mb-4">
        <i class="bi bi-box-seam"></i> Admin Order Management
    </h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Delivery</th>
                        <th>Return Due</th>
                        <th>Late Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php while($o = mysqli_fetch_assoc($orders)): ?>
                <tr>

                    <td>#<?= $o['order_id'] ?></td>
                    <td><?= htmlspecialchars($o['full_name']) ?></td>

                    <td>
                        <span class="badge bg-primary badge-status">
                            <?= ucfirst($o['order_status']) ?>
                        </span>
                    </td>

                    <td><?= $o['delivery_date'] ?: '-' ?></td>
                    <td><?= $o['return_due_date'] ?: '-' ?></td>

                    <td>
                        <?php if($o['late_fee'] > 0): ?>
                            <span class="text-danger fw-bold">₹<?= $o['late_fee'] ?></span>
                        <?php else: ?>
                            <span class="text-success">₹0</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <form method="post" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?= $o['order_id'] ?>">

                            <select name="order_status" class="form-select form-select-sm" required>
                                <?php
                                $statuses = ['pending','confirmed','processing','delivered','returned','cancelled'];
                                foreach($statuses as $s){
                                    $sel = ($o['order_status']==$s)?'selected':'';
                                    echo "<option value='$s' $sel>".ucfirst($s)."</option>";
                                }
                                ?>
                            </select>

                            <button name="update_status" class="btn btn-sm btn-success">
                                <i class="bi bi-check2"></i>
                            </button>

                            <a href="return_invoice.php?order_id=<?= $o['order_id'] ?>"
                               class="btn btn-sm btn-outline-secondary">
                               PDF
                            </a>
                        </form>
                    </td>

                </tr>
                <?php endwhile; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
