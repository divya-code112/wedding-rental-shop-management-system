<?php
session_start();
include "../includes/db.php";
require_once "../includes/TCPDF-main/tcpdf.php";

$order_id = intval($_GET['order_id']);
if(!$order_id) exit("Invalid order ID");

// Fetch order, user, and returns info
$order = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT o.*, u.full_name, u.mobile, u.email
    FROM orders o 
    JOIN users u ON o.user_id=u.user_id
    WHERE o.order_id=$order_id
"));

$items = mysqli_query($conn, "
    SELECT oi.*, p.product_name, r.late_days, r.late_fee, r.damage_status, r.damage_fee
    FROM order_items oi
    JOIN products p ON oi.product_id=p.product_id
    LEFT JOIN returns r ON oi.order_id=r.order_id AND oi.product_id=r.product_id
    WHERE oi.order_id=$order_id
");

// Create new PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('dejavusans','',10);

$html = "<h2>Royal Drapes - Return Invoice</h2>";
$html .= "<p><strong>Order ID:</strong> {$order['order_id']}<br>
          <strong>Customer:</strong> {$order['full_name']}<br>
          <strong>Mobile:</strong> {$order['mobile']}<br>
          <strong>Email:</strong> {$order['email']}<br>
          <strong>Delivery Date:</strong> {$order['delivery_date']}<br>
          <strong>Return Date:</strong> {$order['return_due_date']}</p>";

$html .= "<table border='1' cellpadding='4'>
<tr>
<th>#</th><th>Product</th><th>Rental Days</th><th>Price/Day</th><th>Deposit</th><th>Total</th><th>Late Fee</th><th>Damage Fee</th>
</tr>";

$i=1;
$total_price = 0; $total_late = 0; $total_damage = 0;
while($item = mysqli_fetch_assoc($items)){
    $total = $item['total_price'] ?? ($item['price_per_day']*$item['rental_days']);
    $total_price += $total;
    $total_late += $item['late_fee'] ?? 0;
    $total_damage += $item['damage_fee'] ?? 0;

    $html .= "<tr>
        <td>{$i}</td>
        <td>{$item['product_name']}</td>
        <td>{$item['rental_days']}</td>
        <td>₹".number_format($item['price_per_day'],2)."</td>
        <td>₹".number_format($item['deposit_amount'],2)."</td>
        <td>₹".number_format($total,2)."</td>
        <td>₹".number_format($item['late_fee'] ?? 0,2)."</td>
        <td>₹".number_format($item['damage_fee'] ?? 0,2)."</td>
    </tr>";
    $i++;
}

$refund = $order['refund_amount'];
$final = $order['final_amount'];

$html .= "</table>";
$html .= "<p><strong>Total Rent + Deposit:</strong> ₹".number_format($total_price,2)."<br>
<strong>Total Late Fee:</strong> ₹".number_format($total_late,2)."<br>
<strong>Total Damage Fee:</strong> ₹".number_format($total_damage,2)."<br>
<strong>Refund Amount:</strong> ₹".number_format($refund,2)."<br>
<strong>Final Amount:</strong> ₹".number_format($final,2)."</p>";

// Output PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output("Return_Invoice_Order_$order_id.pdf", "I");
