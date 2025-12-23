<?php
require_once "../includes/TCPDF-main/tcpdf.php";
include "../includes/db.php";

$order_id = intval($_GET['order_id'] ?? 0);

if (!$order_id) {
    die("Invalid Order");
}

/* =========================
   FETCH ORDER + USER
========================= */
$q = mysqli_query($conn, "
    SELECT o.*, u.full_name, u.email, u.mobile
    FROM orders o
    JOIN users u ON u.user_id = o.user_id
    WHERE o.order_id = $order_id
    LIMIT 1
");

$order = mysqli_fetch_assoc($q);

if (!$order) {
    die("Order not found");
}

/* =========================
   VALUES (NO CALCULATION)
========================= */
$rent      = $order['total_rent_amount'];
$deposit   = $order['advance_amount'];
$late_fee  = $order['late_fee'];
$damage    = $order['damage_fee'];
$final     = $order['final_amount'];
$refund    = $order['refund_amount'];
$status    = ucfirst($order['order_status']);

/* =========================
   TCPDF SETUP
========================= */
$pdf = new TCPDF('P','mm','A4',true,'UTF-8',false);
$pdf->SetCreator('Royal Drapes');
$pdf->SetTitle('Return Invoice');
$pdf->SetMargins(15,15,15);
$pdf->AddPage();
$pdf->SetFont('helvetica','',11);

/* =========================
   HEADER
========================= */
$html = "
<h2 style='text-align:center;'>Royal Drapes</h2>
<p style='text-align:center;'>Wedding Wear Rental Invoice</p>
<hr>

<table cellpadding='6'>
<tr>
<td><strong>Invoice No:</strong> RD-$order_id</td>
<td align='right'><strong>Date:</strong> ".date('d M Y')."</td>
</tr>
<tr>
<td><strong>Customer:</strong> {$order['full_name']}</td>
<td align='right'><strong>Status:</strong> $status</td>
</tr>
<tr>
<td><strong>Email:</strong> {$order['email']}</td>
<td align='right'><strong>Mobile:</strong> {$order['mobile']}</td>
</tr>
</table>
<br>
";

/* =========================
   ORDER DETAILS
========================= */
$html .= "
<h4>Order Details</h4>
<table border='1' cellpadding='8'>
<tr bgcolor='#f2f2f2'>
<th>Description</th>
<th align='right'>Amount (₹)</th>
</tr>

<tr>
<td>Total Rent Amount</td>
<td align='right'>".number_format($rent,2)."</td>
</tr>

<tr>
<td>Deposit Paid</td>
<td align='right'>- ".number_format($deposit,2)."</td>
</tr>

<tr>
<td>Late Fee</td>
<td align='right'>".number_format($late_fee,2)."</td>
</tr>

<tr>
<td>Damage Fee</td>
<td align='right'>".number_format($damage,2)."</td>
</tr>

<tr bgcolor='#f9f9f9'>
<td><strong>Final Amount Payable</strong></td>
<td align='right'><strong>".number_format($final,2)."</strong></td>
</tr>

<tr>
<td>Refund Amount</td>
<td align='right'>".number_format($refund,2)."</td>
</tr>
</table>
<br>
";

/* =========================
   DATES
========================= */
$html .= "
<h4>Rental Timeline</h4>
<table cellpadding='6'>
<tr>
<td><strong>Delivery Date:</strong> {$order['delivery_date']}</td>
<td><strong>Return Due:</strong> {$order['return_due_date']}</td>
</tr>
<tr>
<td><strong>Delivered At:</strong> {$order['delivered_at']}</td>
<td><strong>Returned At:</strong> {$order['returned_at']}</td>
</tr>
</table>
<br>
";

/* =========================
   FOOTER
========================= */
$html .= "
<hr>
<p style='text-align:center;font-size:10px;'>
Thank you for choosing <strong>Royal Drapes</strong><br>
This is a system-generated invoice.
</p>
";

/* =========================
   OUTPUT
========================= */
$pdf->writeHTML($html);
$pdf->Output("RoyalDrapes_Invoice_$order_id.pdf", "I");
