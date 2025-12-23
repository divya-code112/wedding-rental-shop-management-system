<?php
session_start();
include __DIR__ . '/../includes/db.php';

// ---------- Handle deletion ----------
if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM contact_messages WHERE id = $id");
    header("Location: admin_messages.php");
    exit();
}

// ---------- Handle search/filter ----------
$where = ["1"];
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$from = isset($_GET['from']) ? $_GET['from'] : '';
$to = isset($_GET['to']) ? $_GET['to'] : '';

if($search){
    $where[] = "(name LIKE '%$search%' OR email LIKE '%$search%' OR subject LIKE '%$search%' OR message LIKE '%$search%')";
}
if($from) $where[] = "DATE(submitted_at) >= '$from'";
if($to) $where[] = "DATE(submitted_at) <= '$to'";

$where_sql = implode(' AND ', $where);

// ---------- Fetch messages ----------
$res = mysqli_query($conn, "SELECT * FROM contact_messages WHERE $where_sql ORDER BY submitted_at DESC");

// ---------- Export to CSV ----------
if(isset($_GET['export']) && $_GET['export']=='csv'){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="contact_messages.csv"');
    $output = fopen('php://output','w');
    fputcsv($output,['ID','Name','Email','Subject','Message','Submitted At']);
    mysqli_data_seek($res,0);
    while($row=mysqli_fetch_assoc($res)){
        fputcsv($output,[$row['id'],$row['name'],$row['email'],$row['subject'],$row['message'],$row['submitted_at']]);
    }
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Messages — Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { font-family:'Poppins', sans-serif; background:#f8f9fa; margin:0; }
.container-main { max-width:1200px; margin:40px auto; padding:20px; }
h2 { text-align:center; margin-bottom:30px; color:#111827; position: relative;}
h2::after { content:""; width:60px; height:3px; background:#1e40af; display:block; margin:8px auto 0; border-radius:2px;}
.table { background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,0.06); }
.table th, .table td { vertical-align: middle !important; }
.delete-btn { color:#fff; background:#e11d48; border:none; border-radius:6px; padding:5px 10px; transition:0.3s; }
.delete-btn:hover { background:#be123c; transform:translateY(-2px); }
.alert { border-radius:50px; }
.filter-form .form-control { border-radius:50px; margin-bottom:8px; }
.filter-form button { border-radius:50px; }
</style>
</head>
<body>

<div class="container-main">
    <h2>Contact Messages</h2>

    <!-- Search & Filter Form -->
    <form method="GET" class="row g-2 filter-form mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, subject or message" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>">
        </div>
        <div class="col-md-3">
            <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>">
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
            <a href="admin_messages.php?export=csv" class="btn btn-success flex-fill"><i class="fa-solid fa-file-csv"></i> CSV</a>
        </div>
    </form>

    <?php if(mysqli_num_rows($res) == 0): ?>
        <div class="alert alert-info text-center">No messages found.</div>
    <?php else: ?>
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; mysqli_data_seek($res,0); while($row = mysqli_fetch_assoc($res)): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                        <td><?= date("d M Y, H:i", strtotime($row['submitted_at'])) ?></td>
                        <td>
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this message?');" class="delete-btn"><i class="fa-solid fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
