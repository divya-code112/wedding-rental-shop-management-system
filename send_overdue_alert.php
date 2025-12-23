<?php
include "../includes/db.php";

$order_id = intval($_GET['order_id']);

$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT u.mobile, o.return_due_date
    FROM orders o
    JOIN users u ON o.user_id=u.user_id
    WHERE o.order_id=$order_id
"));

$message = "Order #$order_id overdue. Return was due on ".$data['return_due_date'];

file_put_contents("../logs/sms_log.txt",
    date('d-m-Y H:i:s')." | ".$data['mobile']." | ".$message."\n",
    FILE_APPEND
);

header("Location: manage_orders.php");
exit();
