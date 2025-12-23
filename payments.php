<?php
session_start();
include "../includes/db.php";

// Filter by date if provided
$filter_query = "";
if(isset($_GET['start_date'], $_GET['end_date']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])){
    $start = $_GET['start_date'];
    $end = $_GET['end_date'];
    $filter_query = "WHERE p.transaction_date BETWEEN '$start' AND '$end'";
}

// Fetch all payments with customer name
$query = "
    SELECT p.*, o.order_date, u.full_name AS customer_name
    FROM payment p
    LEFT JOIN orders o ON p.order_id=o.order_id
    LEFT JOIN users u ON p.user_id=u.user_id
    $filter_query
    ORDER BY p.transaction_date DESC
";
$payments = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Payments - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    
    <style>
        body { background: #f4f6f9; font-family: 'Poppins', sans-serif; }
        .container { margin-top: 50px; }
        .status-success { color: #198754; font-weight: 600; }
        .status-pending { color: #ffc107; font-weight: 600; }
        .status-failed { color: #dc3545; font-weight: 600; }
        table.dataTable tbody tr:hover { background-color: #e2f0d9; }
    </style>
</head>
<body>
    
<div class="container">
    <h2 class="mb-4"><i class="fa fa-credit-card"></i> All Payments</h2>

    <!-- Filter by Date -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-auto">
            <input type="date" name="start_date" class="form-control" placeholder="Start Date" value="<?= $_GET['start_date'] ?? '' ?>">
        </div>
        <div class="col-auto">
            <input type="date" name="end_date" class="form-control" placeholder="End Date" value="<?= $_GET['end_date'] ?? '' ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="fa fa-filter"></i> Filter</button>
            <a href="payment_history.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table id="paymentTable" class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Amount</th>
                    <th>Payment Type</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if(mysqli_num_rows($payments) > 0){
                    $i = 1;
                    while($row = mysqli_fetch_assoc($payments)){
                        $status_class = strtolower($row['payment_status']);
                        echo "<tr>
                            <td>{$i}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['payment_id']}</td>
                            <td><a href='order_details.php?order_id={$row['order_id']}'>{$row['order_id']}</a></td>
                            <td>{$row['order_date']}</td>
                            <td>₹{$row['amount']}</td>
                            <td>{$row['payment_type']}</td>
                            <td>{$row['payment_method']}</td>
                            <td class='status-{$status_class}'>".ucfirst($row['payment_status'])."</td>
                            <td>{$row['transaction_date']}</td>
                        </tr>";
                        $i++;
                    }
                } else {
                    echo "<tr><td colspan='10' class='text-center'>No payment records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#paymentTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'print'
        ],
        order: [[ 9, "desc" ]], // order by Payment Date desc
        pageLength: 10
    });
});
</script>
</body>
</html>
