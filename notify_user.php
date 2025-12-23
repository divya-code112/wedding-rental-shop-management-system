<?php
include "../includes/db.php";
include "../includes/send_notifications.php";

$order_id = intval($_GET['order_id']);

$o = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT o.*, u.full_name, u.email, u.mobile
FROM orders o
JOIN users u ON u.user_id=o.user_id
WHERE o.order_id=$order_id
"));

if(!$o){ die("Invalid Order"); }

$msg = "
Dear {$o['full_name']},

Your order #{$o['order_id']} has been returned.

Late Fee: ₹{$o['late_fee']}
Damage Fee: ₹{$o['damage_fee']}
Final Amount Due: ₹{$o['final_amount']}
Refund Amount: ₹{$o['refund_amount']}

Thank you for choosing Royal Drapes.
";

// Send
sendEmail($o['email'], "Order Return Summary", $msg);
sendSMS($o['mobile'], "Order {$o['order_id']} returned. Final Due ₹{$o['final_amount']} Refund ₹{$o['refund_amount']}");

// Redirect
header("Location: admin_manage_orders.php?notified=1");
exit;
